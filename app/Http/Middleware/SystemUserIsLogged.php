<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SystemUserIsLogged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::guard('system_users')->user() && Auth::guard('system_users')->user()->active){
            return $next($request);
        }
        return redirect('/');
    }
}
