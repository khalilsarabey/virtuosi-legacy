<?php

namespace App\Http\Middleware;

use Auth;
use Cache;
use Closure;
use Illuminate\Http\Request;

class CheckLabel
{
    public function handle(Request $request, Closure $next)
    {
        $agencies = Cache::pull('settings');
        if(!$agencies || $agencies->count() == 0) {
            $agencies = \Illuminate\Support\Facades\Cache::rememberForever('settings', function() {
                return \App\Models\Agency::get();
            });
        }
        $agency = $agencies->where('domain', get_domain())->first();
        if(auth()->user()) {
            if(!auth()->user()->agency || auth()->user()->agency_id != $agency->id) {
                Auth::logout();
                return redirect('/login')->withErrors([
                    'email' => 'You are not allowed to access this page.',
                ]);
            };
        }
        return $next($request);
    }
}
