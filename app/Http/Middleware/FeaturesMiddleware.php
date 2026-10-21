<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FeaturesMiddleware
{
    public function handle(Request $request, Closure $next, $feature)
    {
        if(config('features.'.$feature)) {
            return $next($request);
        }
        abort(403, 'Permission Denied');
    }
}
