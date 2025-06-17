<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateAllRoutes
{
    public function handle(Request $request, Closure $next)
    {
        $guards = ['web', 'admin', 'doctor', 'ray_employee', 'laboratorie_employee', 'patient', 'pharmacy_employee', 'pharmacy_manager'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        return redirect()->route('login'); // أو أي صفحة دخول تفضلها
    }
}
