<?php

namespace App\Http\Controllers\Auth\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;
class ResetPasswordController extends Controller{
    protected $redirectTo;

    public function __construct()
    {
        $this->middleware('guest:patient');
        $this->redirectTo = route('dashboard.patient');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('Dashboard.auth.patient.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $response = Password::broker('patients')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect($this->redirectTo)->with('status', __($response));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($response)]);
    }

    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password); 
        // $user->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));
    }
}
