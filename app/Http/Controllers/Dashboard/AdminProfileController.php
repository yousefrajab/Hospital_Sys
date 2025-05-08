<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request; // لا يزال مطلوبًا إذا كنت ستستخدم $request مباشرة في أي مكان
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\UpdateAdminProfileRequest; // **استيراد الـ FormRequest**
use App\Models\Admin;                                 // **استيراد موديل Admin**
use App\Traits\UploadTrait;                           // **استيراد UploadTrait**
use Illuminate\Validation\ValidationException;        // **لرمي أخطاء التحقق يدويًا إذا لزم الأمر**

class AdminProfileController extends Controller
{
    use UploadTrait; // **استخدام الـ Trait**

    public function show()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            // يمكنك توجيه إلى صفحة تسجيل الدخول أو خطأ مخصص
            return redirect()->route('admin.login.form')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }
        return view('Dashboard.Admin.profile.show', compact('admin'));
    }

    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login.form')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }
        return view('Dashboard.Admin.profile.edit', compact('admin'));
    }

    public function update(UpdateAdminProfileRequest $request) // **استخدام الـ FormRequest للتحقق التلقائي**
    {
        $admin = Auth::guard('admin')->user(); // الحصول على المستخدم الحالي مباشرة بعد التحقق
        if (!$admin) {
            // هذا لن يحدث عادةً إذا كان الـ middleware 'auth:admin' مطبقًا على الـ route
            abort(401, 'غير مصرح به.');
        }

        // البيانات التي تم التحقق منها متاحة مباشرة من $request
        $validatedData = $request->validated();

        // 1. تحديث البيانات الأساسية
        $admin->name = $validatedData['name'];
        $admin->email = $validatedData['email'];
        // إذا كان حقل الهاتف موجودًا في $validatedData (من FormRequest)
        if (isset($validatedData['phone'])) {
            $admin->phone = $validatedData['phone'];
        }

        // 2. تحديث كلمة المرور (إذا تم تقديمها وتأكيدها في FormRequest)
        //   FormRequest (UpdateAdminProfileRequest) يجب أن يتضمن 'current_password' إذا تم تقديم 'password'
        if ($request->filled('password') && $request->filled('current_password')) {
            // التحقق من كلمة المرور الحالية
            if (!Hash::check($request->current_password, $admin->password)) {
                throw ValidationException::withMessages([
                    'current_password' => __('validation.current_password'), // استخدام رسالة مخصصة أو من ملفات اللغة
                ]);
            }
            // إذا كانت كلمة المرور الحالية صحيحة، قم بتحديث كلمة المرور الجديدة
            $admin->password = Hash::make($validatedData['password']); // password هنا هي new_password من الـ form
            Log::info("Admin ID: {$admin->id} password updated.");
        } elseif ($request->filled('password') && !$request->filled('current_password')) {
            // إذا تم تقديم كلمة مرور جديدة بدون الحالية (يجب أن يمنعها FormRequest بـ required_with)
            throw ValidationException::withMessages([
                'current_password' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
            ]);
        }


        // 3. التعامل مع رفع الصورة
        if ($request->hasFile('photo')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($admin->image) {
                $this->Delete_attachment(
                    'upload_image',       // اسم القرص
                    'admin_photos/' . $admin->image->filename, // المسار إلى الصورة القديمة
                    $admin->id,
                    Admin::class          // أو 'App\Models\Admin'
                );
                Log::info("Admin ID: {$admin->id} old photo deleted.");
            }
            // رفع الصورة الجديدة
            $this->verifyAndStoreImage(
                $request,
                'photo',
                'admin_photos',       // اسم المجلد لتخزين صور الأدمن
                'upload_image',
                $admin->id,
                Admin::class          // أو 'App\Models\Admin'
            );
            Log::info("Admin ID: {$admin->id} new photo uploaded.");
        }

        // 4. حفظ التغييرات
        try {
            $admin->save();
            Log::info("Admin ID: {$admin->id} profile updated successfully.");

            // 5. إعادة التوجيه مع رسالة نجاح
            return redirect()->route('admin.profile.show') // أو .show إذا كنت تفضل
                ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Exception $e) {
            Log::error("Error saving admin profile for ID {$admin->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء حفظ التغييرات.');
        }
    }
}
