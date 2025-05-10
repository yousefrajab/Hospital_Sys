<?php

namespace App\Http\Controllers\Auth\RayEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password; // ** استخدام Password Facade **
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider; // أو حدد مسار التوجيه مباشرة

class ResetPasswordController extends Controller // ** تأكد أن اسم الكلاس هو ResetPasswordController **
{
    protected $redirectTo;

    public function __construct()
    {
        $this->middleware('guest:ray_employee');
        $this->redirectTo = route('dashboard.ray_employee'); // أو RouteServiceProvider::ADMIN إذا كان معرفًا
    }

    public function showResetForm(Request $request, $token = null)
    {
        // resources/views/Dashboard/auth/ray_employee/passwords/reset.blade.php
        return view('Dashboard.auth.ray_employee.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            // تأكد أن حقل التأكيد في الفورم اسمه password_confirmation
            'password' => 'required|confirmed|min:8',
        ]);

        // استخدام الـ broker 'ray_employees'
        $response = Password::broker('ray_employees')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            // (اختياري) تسجيل دخول الأدمن بعد إعادة التعيين
            // Auth::guard('ray_employee')->login($user); // $user هو المستخدم الذي تم تحديثه

            return redirect($this->redirectTo)->with('status', __($response));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($response)]);
    }

    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password); // ** تعديل هنا: تشفير يدوي **
        // $user->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));
    }
}
