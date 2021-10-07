<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check() && $request->user()->is_admin === 1) {
            return $next($request);
        } else {
            auth()->logout();
            return redirect()->route('login')->with('error' , 'يجب أن تكون مديراً لتتمكن من تسجيل الدخول إلى الموقع');
        }
    }
}
