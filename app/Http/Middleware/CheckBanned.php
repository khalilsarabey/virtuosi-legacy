<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && (auth()->user()->active != 1)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Your Account is suspended, please contact Admin.');
        }
        if(auth()->check() && auth()->user()->force_reset_password == 1) {
            return redirect()->route('change-password');
        }
        return $next($request);
    }
}
