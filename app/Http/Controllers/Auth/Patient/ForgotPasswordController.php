<?php

namespace App\Http\Controllers\Auth\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {

        $this->middleware('guest:patient');
    }
    public function showLinkRequestForm()
    {

        return view('Dashboard.auth.patient.passwords.email');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $response = Password::broker('patients')->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', __($response));
        }
        return back()->withInput($request->only('email'))
                     ->withErrors(['email' => __($response)]);
    }
}
