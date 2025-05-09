<?php

namespace App\Http\Controllers\Auth\Admin; // 1. الـ Namespace الصحيح

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails; // 2. استيراد الـ Trait من مساره الصحيح

class ForgotPasswordController extends Controller // 3. اسم الكلاس الصحيح
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller (Admin)
    |--------------------------------------------------------------------------
    |
    | هذا الـ Controller مسؤول عن معالجة طلبات إرسال روابط إعادة تعيين كلمة
    | المرور لـ guard الأدمن.
    |
    */

    use SendsPasswordResetEmails; // 4. استخدام الـ Trait داخل الكلاس

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // تحديد الـ password broker الذي سيتعامل معه هذا الـ controller
        config(['auth.defaults.passwords' => 'admins']); // 'admins' يجب أن يكون اسم الـ broker في config/auth.php

        // تطبيق middleware الضيف على الـ Guard 'admin'
        $this->middleware('guest:admin');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        // مسار الـ View لصفحة طلب إعادة تعيين كلمة مرور الأدمن
        // مثال: resources/views/Dashboard/auth/admin/passwords/email.blade.php
        return view('Dashboard.auth.admin.passwords.email');
    }

    // دالة `sendResetLinkEmail(Request $request)` موجودة داخل الـ Trait `SendsPasswordResetEmails`
    // وهي التي سيتم استدعاؤها بواسطة الـ Route:
    // Route::post('/forgot-password/admin', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])
}
