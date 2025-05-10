<?php

namespace App\Http\Controllers\Auth\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password; // ** استخدام Password Facade **

class ForgotPasswordController extends Controller // ** تأكد أن اسم الكلاس هو ForgotPasswordController **
{
    public function __construct()
    {
        // اسم الـ broker الذي سنستخدمه (يجب أن يكون معرفًا في config/auth.php)
        // لا نحتاج لـ config(['auth.defaults.passwords']) هنا لأننا سنحدد الـ broker مباشرة
        $this->middleware('guest:doctor'); // فقط الضيوف من guard الأدمن
    }

    /**
     * عرض فورم طلب رابط إعادة تعيين كلمة المرور.
     */
    public function showLinkRequestForm()
    {
        // تأكد من أن هذا الـ view موجود
        // resources/views/Dashboard/auth/doctor/passwords/email.blade.php
        return view('Dashboard.auth.doctor.passwords.email');
    }

    /**
     * معالجة طلب إرسال رابط إعادة تعيين كلمة المرور.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // استخدام الـ broker 'doctors' الذي عرفناه في config/auth.php
        // Password::broker('doctors') يحدد أننا نعمل مع إعدادات 'doctors' في config/auth.php -> passwords
        $response = Password::broker('doctors')->sendResetLink(
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
