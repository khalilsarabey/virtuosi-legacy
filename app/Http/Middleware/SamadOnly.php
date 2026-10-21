<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SamadOnly
{
    public function handle(Request $request, Closure $next)
    {
        if(!$request->user() || $request->user()->agency_id != 1) {
            abort(403, 'Permission Denied');
        }
        return $next($request);
    }
}
