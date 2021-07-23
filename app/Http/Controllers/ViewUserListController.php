<?php

namespace App\Http\Controllers;

use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ViewUserListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.user_list');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUserListPage(){
        $sectionCode = Auth::user()->sections_section_code;
        $users =User::getUsers($sectionCode);
        if($users->isNotEmpty()){
            $user_list = View::make('/account_management/user_list_table', compact('users'));
        }else{
            $user_list = Setting::getAlertFailure(trans('account_management/user_list.no_user_alert'));
        }

        return \view('account_management.user_list',compact('user_list'));
    }
}
