<?php

namespace App\Http\Controllers\Dashboard\Doctors;

use App\Models\Doctor;
use App\Traits\UploadTrait;
// use Illuminate\Http\Request; // لم تعد ضرورية هنا إذا اعتمدنا كليًا على FormRequest
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Storage; // ليست مستخدمة مباشرة هنا
use App\Http\Requests\UpdateDoctorProfileRequest; // ** استخدام FormRequest المحدث **
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; // لاستخدامها إذا احتجت لرمي خطأ تحقق إضافي

class ProfileController extends Controller
{
    use UploadTrait;

    public function showProfile()
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            abort(404, 'الطبيب غير موجود أو غير مسجل الدخول.');
        }
        $doctor->load(['section', 'image', 'workingDays.breaks']); // تحميل العلاقات اللازمة
        return view('Dashboard.Doctors.profile.show', compact('doctor'));
    }

    public function edit()
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            abort(404, 'الطبيب غير موجود أو غير مسجل الدخول.');
        }
        // يمكنك جلب بيانات إضافية هنا مثل الأقسام إذا كان الطبيب يستطيع تغيير قسمه
        // $sections = \App\Models\Section::all();
        return view('Dashboard.Doctors.profile.edit', compact('doctor' /*, 'sections'*/));
    }

    public function update(UpdateDoctorProfileRequest $request) // استخدام FormRequest
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            // هذا لا يجب أن يحدث إذا كانت authorize في FormRequest تعمل
            abort(403, 'غير مصرح لك بالقيام بهذا الإجراء.');
        }

        $validatedData = $request->validated(); // الحصول على البيانات التي تم التحقق منها
        Log::info("Attempting profile update for Doctor ID: {$doctor->id}", $request->except(['_token', '_method', 'password', 'password_confirmation', 'current_password', 'photo']));

        DB::beginTransaction();
        try {
            // البيانات الأساسية التي سيتم تحديثها مباشرة
            $dataToUpdate = [
                'email' => strtolower($validatedData['email']), // حفظ الإيميل بأحرف صغيرة
                'national_id' => $validatedData['national_id'],
            ];

            // إضافة الهاتف إذا كان موجودًا في البيانات المتحقق منها
            if (isset($validatedData['phone'])) {
                $dataToUpdate['phone'] = $validatedData['phone'];
            }
            // إضافة القسم إذا كان الطبيب يعدله وكان موجودًا
            // if (isset($validatedData['section_id'])) {
            //     $dataToUpdate['section_id'] = $validatedData['section_id'];
            // }

            // تحديث كلمة المرور (التحقق من كلمة المرور الحالية تم في FormRequest)
            if (!empty($validatedData['password'])) { // اسم الحقل الآن 'password' من FormRequest
                $dataToUpdate['password'] = Hash::make($validatedData['password']);
                Log::info("Password will be updated for Doctor ID: {$doctor->id}");
            }

            $doctor->update($dataToUpdate); // تحديث الحقول غير المترجمة + كلمة المرور إذا تغيرت

            // تحديث الاسم المترجم
            // تأكد أن موديل Doctor يستخدم trait Translatable وأن name في $translatedAttributes
            $doctor->name = $validatedData['name']; // هذا سيعمل إذا كان name هو الحقل المترجم
            $doctor->save(); // حفظ التغييرات بما في ذلك الترجمة

            Log::info("Doctor data (excluding image) updated for ID: {$doctor->id}");


            // التعامل مع رفع الصورة
            if ($request->hasFile('photo')) {
                Log::info("[Doctor Profile Update] Image file detected for Doctor ID: {$doctor->id}");
                if ($doctor->image && $doctor->image->filename) {
                    $this->Delete_attachment(
                        'upload_image', // تأكد أن هذا هو اسم القرص الصحيح
                        'doctors/' . $doctor->image->filename,
                        $doctor->id,
                        Doctor::class // استخدام FQCN للموديل
                    );
                    Log::info("[Doctor Profile Update] Old image deleted for Doctor ID: {$doctor->id}");
                }
                $this->verifyAndStoreImage(
                    $request, // كائن الطلب
                    'photo',   // اسم الحقل
                    'doctors', // اسم المجلد
                    'upload_image', // اسم القرص
                    $doctor->id,
                    Doctor::class // استخدام FQCN للموديل
                );
                Log::info("[Doctor Profile Update] New image stored for Doctor ID: {$doctor->id}");
            }

            DB::commit();
            Log::info("Profile update committed successfully for Doctor ID {$doctor->id}");
            return redirect()->route('doctor.profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // هذا الـ catch قد لا يتم الوصول إليه إذا كان FormRequest يعالج كل أخطاء التحقق
            DB::rollBack();
            Log::error("Doctor Profile Update Validation Exception (Controller Level) for ID {$doctor->id}: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating doctor profile for ID {$doctor->id}: " . $e->getMessage());
            Log::error($e->getTraceAsString()); // اطبع التتبع الكامل للخطأ لتشخيص أفضل
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تحديث البيانات. يرجى المحاولة مرة أخرى أو مراجعة سجلات النظام.');
        }
    }
}
