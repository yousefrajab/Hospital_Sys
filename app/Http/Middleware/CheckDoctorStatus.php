<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDoctorStatus
{
    public function handle(Request $request, Closure $next)
    {
        $guard = 'doctor';

        if (Auth::guard($guard)->check()) {
            $doctor = Auth::guard($guard)->user();

            if (!$doctor->status) {
                Auth::guard($guard)->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // *** استخدم المسار المسمى 'login' هنا ***
                return redirect()->route('login')
                             ->with('error', 'تم تعطيل حسابك. يرجى مراجعة الإدارة.');
            }
        }
        return $next($request);
    }
}
