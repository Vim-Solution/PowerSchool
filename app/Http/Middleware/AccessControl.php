<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AccessControl
{

    /**
     * Handle an incoming request.
     * @param $request
     * @param Closure $next
     * @param $url
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next,$url)
    {
        /*
         * if the user has permission to this privilege
         * continue request else redirect user to login if not authenticate
         * or to the home otherwise
         */
        if(!$request->user()->hasPermission($url)){
            if(Auth::check()){
                return Redirect::to(trans('settings/routes.home'));
            }else{
                return Redirect::to(trans('settings/routes.login'));
            }
        }
        return $next($request);
    }
}
