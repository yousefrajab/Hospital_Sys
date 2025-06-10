<?php

namespace App\Http\Controllers\Dashboard\PharmacyEmployee;

use App\Models\PharmacyEmployee;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Dashboard\PharmacyEmployee\UpdatePharmacyEmployeeProfileRequest; // استيراد FormRequest

class ProfilePharmacyController extends Controller // يمكنك تسميته ProfileController داخل مجلد PharmacyEmployee
{
    use UploadTrait;

    /**
     * عرض الملف الشخصي لموظف الأشعة المسجل.
     */
    public function show()
    {
        $employee = Auth::guard('pharmacy_employee')->user();
        if (!$employee) {
            // يمكنك التوجيه لصفحة تسجيل الدخول أو عرض خطأ
            return redirect()->route('pharmacy_employee.login.form'); // افترض وجود هذا الـ route
        }
        // تحميل علاقة الصورة إذا كانت موجودة
        $employee->load('image');
        return view('Dashboard.pharmacy_employee.profile.show', compact('employee'));
    }

    /**
     * عرض فورم تعديل الملف الشخصي.
     */
    public function edit()
    {
        $employee = Auth::guard('pharmacy_employee')->user();
        if (!$employee) {
            return redirect()->route('pharmacy_employee.login.form');
        }
        $employee->load('image');
        return view('Dashboard.pharmacy_employee.profile.edit', compact('employee'));
    }

    /**
     * تحديث الملف الشخصي لموظف الأشعة.
     */
    public function update(UpdatePharmacyEmployeeProfileRequest $request)
    {
        $employee = Auth::guard('pharmacy_employee')->user();
        if (!$employee) {
            abort(403, 'غير مصرح لك.');
        }

        $validatedData = $request->validated();
        Log::info("Attempting profile update for PharmacyEmployee ID: {$employee->id}");
        DB::beginTransaction();

        try {
            // تحديث البيانات الأساسية
            $employee->name = $validatedData['name'];
            $employee->email = $validatedData['email'];
            $employee->national_id = $validatedData['national_id'];

            if (isset($validatedData['phone'])) {
                $employee->phone = $validatedData['phone'];
            }

            // تحديث كلمة المرور (إذا تم تقديمها والتحقق منها)
            if ($request->filled('password') && $request->filled('current_password')) {
                if (!Hash::check($request->current_password, $employee->password)) {
                    DB::rollBack(); // تراجع قبل رمي الاستثناء
                    throw ValidationException::withMessages([
                        'current_password' => __('validation.current_password'),
                    ]);
                }
                $employee->password = Hash::make($validatedData['password']);
                Log::info("Password updated for PharmacyEmployee ID: {$employee->id}");
            } elseif ($request->filled('password') && !$request->filled('current_password')) {
                DB::rollBack();
                throw ValidationException::withMessages([
                    'current_password' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
                ]);
            }

            // التعامل مع رفع الصورة
            // تحديث الصورة إذا تم رفع صورة جديدة
            if ($request->hasFile('photo')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($employee->image) {
                    $this->Delete_attachment(
                        'upload_image',
                        'pharmacyEmployees/' . $employee->image->filename,
                        $employee->id,
                        'App\Models\PharmacyEmployee'
                    );

                    $this->verifyAndStoreImage(
                        $request,
                        'photo',
                        'pharmacyEmployees',
                        'upload_image',
                        $employee->id,
                        'App\Models\PharmacyEmployee'
                    );
                }
                // رفع الصورة الجديدة

            }

            // حفظ كل التغييرات
            $employee->save();
            DB::commit();
            Log::info("Profile update committed successfully for PharmacyEmployee ID {$employee->id}");

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('pharmacy_employee.profile.show') // اسم route عرض الملف الشخصي
                ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // تراجع في حالة خطأ التحقق الداخلي (مثل كلمة المرور الحالية)
            Log::error("PharmacyEmployee Profile Update Validation Error ID {$employee->id}: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating ray employee profile for ID {$employee->id}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.');
        }
    }
}
