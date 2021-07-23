<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // change language to suit user language

        if (Auth::guard($guard)->check()) {
            $last_user_url = 'last_url_' . Auth::user()->user_id;
            $lastUrl = Session::get($last_user_url);
            if (!empty($lastUrl)) {
                return Redirect::to($lastUrl);
            }
            return redirect(trans('settings/routes.home'));
        }
        return $next($request);
    }
}
