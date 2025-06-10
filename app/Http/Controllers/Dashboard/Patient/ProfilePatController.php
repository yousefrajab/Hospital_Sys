<?php

namespace App\Http\Controllers\Dashboard\Patient; // تأكد من المسار الصحيح

use App\Http\Controllers\Controller;
use App\Models\Patient; // استيراد موديل Patient
use App\Traits\UploadTrait; // استيراد UploadTrait
use Illuminate\Http\Request; // لا يزال مطلوبًا لـ $request->hasFile
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Patient\UpdatePatientProfileRequest; // **استيراد الـ FormRequest**
use Illuminate\Validation\ValidationException;

class ProfilePatController extends Controller
{
    use UploadTrait; // **استخدام الـ Trait**

    /**
     * عرض صفحة الملف الشخصي للمريض.
     */
    public function show()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            Log::warning("ProfilePatController@show: Patient not authenticated for profile view.");
            return redirect()->route('login.patient'); // أو أي route تسجيل دخول مناسب للمرضى
        }

        // تحميل العلاقات التي قد تحتاجها في صفحة الملف الشخصي أو في توليد نص الـ QR
        $patient->load([
            'image',
            'diagnosedChronicDiseases', // إذا كنت ستعرض الأمراض في ملفه الشخصي أو QR
            'admissions' // إذا كنت ستعرض سجلات الدخول
        ]);

        // استدعاء دالة توليد الـ QR Code من موديل Patient
        // هذه الدالة يجب أن تكون هي نفسها التي تشفر النص المجمع (المعلومات + الرابط)
        $qrCodeSvg = $patient->generateQrCode(200, 2, 'M'); // يمكنك تعديل الحجم إذا أردت

        // (اختياري) تحضير نفس المعلومات الأساسية لعرضها كنص في صفحة الملف الشخصي
        // هذا مفيد إذا أردت أن يرى المريض ما هو مشفر في الـ QR Code
        $displayInfoForQr = [];
        $locale = app()->getLocale();
        $displayInfoForQr['الاسم'] =  $patient->name;
        $displayInfoForQr['الهوية'] = $patient->national_id;
        $displayInfoForQr['ت.الميلاد'] = $patient->Date_Birth ? \Carbon\Carbon::parse($patient->Date_Birth)->format('Y-m-d') : '-';
        $displayInfoForQr['فصيلة الدم'] = $patient->Blood_Group ?: 'غير محددة';

        $chronicDisplayList = [];
        if ($patient->relationLoaded('diagnosedChronicDiseases') && $patient->diagnosedChronicDiseases->isNotEmpty()) {
            foreach ($patient->diagnosedChronicDiseases->take(2) as $diagnosedDisease) {
                // $chronicDisplayList[] = $diagnosedDisease->disease->name;
            }
        }
        if (!empty($chronicDisplayList)) {
            $displayInfoForQr['أمراض مشخصة'] = implode('، ', $chronicDisplayList);
        }

        // ... يمكنك إضافة المزيد من المعلومات إلى $displayInfoForQr إذا أردت ...

        Log::info("ProfilePatController@show: Displaying profile for Patient ID: {$patient->id}");
        return view('Dashboard.patients.profile.show', compact(
            'patient',
            'qrCodeSvg',
            'displayInfoForQr' // (اختياري)
        ));
    }

    /**
     * عرض فورم تعديل الملف الشخصي للمريض.
     */
    public function edit()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('login');
        }
        return view('Dashboard.patients.profile.edit', compact('patient')); // سننشئ هذا الـ view
    }

    /**
     * تحديث الملف الشخصي للمريض.
     */
    public function update(UpdatePatientProfileRequest $request)
    {
        $patient = Auth::guard('patient')->user(); // الحصول على المريض الحالي
        if (!$patient) {
            abort(403, 'غير مصرح لك.');
        }

        $validatedData = $request->validated(); // البيانات التي تم التحقق منها
        Log::info("Attempting profile update for Patient ID: {$patient->id} by the patient themselves.");

        DB::beginTransaction();
        try {
            // تحديث الحقول الأساسية
            // $patient->national_id = $validatedData['national_id']; // المريض لا يعدل رقم هويته عادةً
            $patient->email = $validatedData['email'];
            $patient->Date_Birth = $validatedData['Date_Birth'];
            $patient->Phone = $validatedData['Phone']; // لاحظ P كبيرة
            $patient->Gender = $validatedData['Gender'];
            $patient->Blood_Group = $validatedData['Blood_Group'] ?? $patient->Blood_Group; // اجعله اختياريًا في التحديث

            // 3. تحديث الحقول المترجمة
            $patient->name = $request->name;
            $patient->Address = $request->Address;

            // تحديث كلمة المرور (إذا تم تقديمها والتحقق منها)
            if ($request->filled('password') && $request->filled('current_password')) {
                if (!Hash::check($request->current_password, $patient->password)) {
                    DB::rollBack();
                    throw ValidationException::withMessages([
                        'current_password' => 'كلمة المرور الحالية غير صحيحة.',
                    ]);
                }
                $patient->password = Hash::make($validatedData['password']);
                Log::info("Password updated for Patient ID: {$patient->id}");
            } elseif ($request->filled('password') && !$request->filled('current_password')) {
                DB::rollBack();
                throw ValidationException::withMessages([
                    'current_password' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
                ]);
            }

            if ($request->hasFile('photo')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($patient->image) {
                    $this->Delete_attachment(
                        'upload_image',
                        'patients/' . $patient->image->filename,
                        $patient->id,
                        'App\Models\Patient'
                    );

                    $this->verifyAndStoreImage(
                        $request,
                        'photo',
                        'patients',
                        'upload_image',
                        $patient->id,
                        'App\Models\Patient'
                    );
                }
                // رفع الصورة الجديدة

            }


            $patient->save(); // حفظ كل التغييرات
            DB::commit();
            Log::info("Profile update committed successfully for Patient ID {$patient->id}");

            return redirect()->route('profile.show') // أو .show
                ->with('success', 'تم تحديث ملفك الشخصي بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Patient Profile Update Validation Error ID {$patient->id}: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating patient profile for ID {$patient->id}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('profile.show')->with('error', 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * دالة مساعدة لتحديد مجلد تخزين صور المرضى
     * (يمكنك وضع هذا في مكان أكثر عمومية إذا لزم الأمر)
     */
    private function getPatientPhotoStoragePath(): string
    {
        return 'patients'; // افترض أن المجلد اسمه 'patients' داخل القرص المحدد
    }
}
