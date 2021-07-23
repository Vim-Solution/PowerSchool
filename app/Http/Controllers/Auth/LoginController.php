<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     *  Create a new controller instance.
     * LoginController constructor.
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        if (cache()->has('n_language')) {
            App::setLocale(cache()->get('n_language'));
        }
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|mixed|string|null
     */
    public function redirectTo()
    {
        $last_user_url = 'last_url_' . Auth::user()->user_id;
        $lastUrl = Session::get($last_user_url);
        if (!empty($lastUrl)) {
            return $lastUrl;
        } else {
            return trans('settings/routes.home');
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setLocale()
    {
        $locale = Input::get('change-locale');
        $result_portal = trans('settings/routes.result_portal');


        cache()->set('n_language', $locale);
        return redirect()->back();
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('auth.login');
    }

}
