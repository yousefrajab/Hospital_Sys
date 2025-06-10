<?php

namespace App\Http\Controllers\Auth\RayEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password; // ** استخدام Password Facade **

class ForgotPasswordController extends Controller // ** تأكد أن اسم الكلاس هو ForgotPasswordController **
{
    public function __construct()
    {
        // اسم الـ broker الذي سنستخدمه (يجب أن يكون معرفًا في config/auth.php)
        // لا نحتاج لـ config(['auth.defaults.passwords']) هنا لأننا سنحدد الـ broker مباشرة
        $this->middleware('guest:ray_employee'); // فقط الضيوف من guard الأدمن
    }

    /**
     * عرض فورم طلب رابط إعادة تعيين كلمة المرور.
     */
    public function showLinkRequestForm()
    {
        // تأكد من أن هذا الـ view موجود
        // resources/views/Dashboard/auth/ray_employee/passwords/email.blade.php
        return view('Dashboard.auth.ray_employee.passwords.email');
    }

    /**
     * معالجة طلب إرسال رابط إعادة تعيين كلمة المرور.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // استخدام الـ broker 'ray_employees' الذي عرفناه في config/auth.php
        // Password::broker('ray_employees') يحدد أننا نعمل مع إعدادات 'ray_employees' في config/auth.php -> passwords
        $response = Password::broker('ray_employees')->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            // إذا تم إرسال الرابط بنجاح
            return back()->with('status', __($response)); // رسالة نجاح
        }

        // إذا فشل إرسال الرابط (مثلاً الإيميل غير موجود في provider الأدمن)
        return back()->withInput($request->only('email'))
                     ->withErrors(['email' => __($response)]);
    }
}
