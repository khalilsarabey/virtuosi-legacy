<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->user() ||
            $request->user()->type != 'admin' &&
            $request->user()->type != 'white-admin' &&
            $request->user()->type != 'accounting'
        ) {
            abort(403, 'Permission Denied');
        }
        return $next($request);
    }
}
