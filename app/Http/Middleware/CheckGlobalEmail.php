<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\GlobalEmail;
use Illuminate\Http\Request;

class CheckGlobalEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
{
    if ($request->has('email')) {
        $exists = GlobalEmail::where('email', $request->email)->exists();
        if ($exists) {
            return response()->json(['error' => 'Email already used in another table!'], 422);
        }
    }
    return $next($request);
}
}
