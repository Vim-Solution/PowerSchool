<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class UserValidity
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::find(Auth::user()->user_id);
        //check is the user is not more part of the school
        if ($user->isHalt()) {
            Auth::logout();
            return Redirect::to(trans('settings/routes.login'))->with(['status' => trans('auth.suspension_state')]);
        }

        $user->active = 1;

        $locale = Auth::user()->lang;
        if (!(App::isLocale($locale))) {
            App::setLocale($locale);
        }
        //$user->save();

        return $next($request);
    }
}
