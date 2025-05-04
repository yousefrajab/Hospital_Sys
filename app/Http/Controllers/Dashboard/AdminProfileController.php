<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AdminProfileController extends Controller
{
    public function show()
    {

        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            abort(404);
        }

        return view('Dashboard.Admin.profile.show', compact('admin'));
    }


    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            abort(404);
        }
        return view('Dashboard.Admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        // 1. الحصول على الأدمن المسجل دخوله
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            abort(401); // Unauthorized بدلاً من 404
        }

        // 2. قواعد التحقق من الصحة
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            // التأكد من أن الإيميل فريد باستثناء المستخدم الحالي
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            // كلمة المرور الحالية مطلوبة فقط إذا تم إدخال كلمة مرور جديدة
            'current_password' => ['nullable', 'required_with:new_password'],
            // كلمة المرور الجديدة (اختيارية)، يجب أن تكون مؤكدة ومطابقة للقواعد
            'new_password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            // confirmed تبحث عن حقل new_password_confirmation
        ];

        // رسائل مخصصة للتحقق (اختياري)
        $messages = [
            'name.required' => 'حقل الاسم مطلوب.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'current_password.required_with' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
            'new_password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق.',
            'new_password.min' => 'كلمة المرور الجديدة يجب أن لا تقل عن 8 أحرف.',
            // يمكنك إضافة رسائل لقواعد Password الأخرى
        ];

        // 3. تنفيذ التحقق
        $validatedData = $request->validate($rules, $messages);

        // 4. التحقق من كلمة المرور الحالية (إذا تم إدخال كلمة مرور جديدة)
        if ($request->filled('new_password')) {
            // التحقق من إدخال كلمة المرور الحالية
            if (!$request->filled('current_password')) {
                // هذا لن يحدث بسبب required_with، لكنه تحقق إضافي
                throw ValidationException::withMessages(['current_password' => 'يرجى إدخال كلمة المرور الحالية.']);
            }
            // التحقق من تطابق كلمة المرور الحالية المدخلة مع المخزنة
            if (!Hash::check($request->current_password, $admin->password)) {
                // رمي استثناء تحقق مخصص ليعرض الخطأ تحت حقل كلمة المرور الحالية
                throw ValidationException::withMessages([
                    'current_password' => __('auth.password'), // استخدام رسالة الخطأ الافتراضية لكلمة المرور
                    // أو 'كلمة المرور الحالية غير صحيحة.'
                ]);
            }
        }

        // 5. تحديث بيانات الأدمن في قاعدة البيانات
        try {
            // تحديث الاسم والإيميل
            $admin->name = $validatedData['name'];
            $admin->email = $validatedData['email'];

            // تحديث كلمة المرور فقط إذا تم إدخال كلمة مرور جديدة
            if ($request->filled('new_password')) {
                $admin->password = Hash::make($validatedData['new_password']);
                Log::info("Admin ID: {$admin->id} password updated.");
            }

            // حفظ التغييرات
            $admin->save();
            Log::info("Admin ID: {$admin->id} profile updated successfully.");

            // 6. إعادة التوجيه مع رسالة نجاح
            return redirect()->route('admin.profile.show') // اسم مسار عرض الملف الشخصي
                ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Exception $e) {
            Log::error("Error updating admin profile for ID {$admin->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تحديث الملف الشخصي.');
        }
    } // نهاية دالة update
}
