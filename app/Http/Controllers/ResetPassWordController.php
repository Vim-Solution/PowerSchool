<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

define('PASSWORD_LIMIT', 10);

class ResetPassWordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.password_reset');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     *  show the reset user password page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPasswordResetPage()
    {
        $userId = Auth::user()->user_id;
        $userSectionCode = Auth::user()->sections_section_code;
        $users = User::where(trans('database/table.academic_state'), 1)->where(trans('database/table.user_id'), '!=', $userId)  ->where(trans('database/table.sections_section_code'), $userSectionCode)->get();
        $accounts = '';

        foreach ($users as $user) {
            $accounts .= '<div class="col-xl-3 col-lg-4 col-sm-4 col-6">
                            <div class="contacts__item">
                                <a href="#" class="contacts__img">' .
                ' <img src="' . asset($user->profile) . '" alt="" height="120">
                                </a>

                                <div class="contacts__info">' .
                '<strong>' . strtok(ucfirst(strtolower($user->full_name)), ' ') . ' ' . ucfirst(strtolower(strtok(' '))) . '</strong>' .
                '<small>' . $user->email . '</small>' .
                '</div>' .
                '<a href="' . trans('settings/routes.password_reset') . '/' . Encrypter::encrypt($user->user_id) . '" class="btn btn-primary" style="width:100%;"><i class="zmdi zmdi-lock-outline"></i>' . trans('account_management/reset_password.reset_password_header') . '</a>' .
                '</div>' .
                '</div>';
        }
        if ($accounts == '') {
            $accounts = '<div class="alert alert-warning" style="width: 98%;position: relative;left: 1%;">' . trans('account_management/reset_password.empty_user_alert') . '</div>';
        }
        $data['accounts'] = $accounts;
        return view('account_management.reset_password')->with($data);
    }

    /**
     * Modify a users password
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetUserPassword($userId)
    {
        $uid = Encrypter::decrypt($userId);
        $user = User::find($uid);
        $password = Str::random(PASSWORD_LIMIT);
        $user->password = Hash::make($password);
        $user->save();
        User::recordUserActions(4,$user->full_name);
        $success_alert = '<div class="alert alert-success">' . trans('account_management/reset_password.reset_password_successful', ['name' => $user->full_name,'email' => $user->email,'password' => $password]) . '</div>';
        return redirect()->back()->with(['status' => $success_alert]);
    }


    /**
     * Search a user by email
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function searchUser()
    {
        $email = Input::get('email');
        $user = User::searchUserByEmail($email);
        if (empty($user) || $user->academic_state == 0 ) {
            $warning_alert = '<div class="alert alert-warning">' . trans('account_management/reset_password.user_not_found', ['email' => $email]) . '</div>';
            return redirect()->back()->with(['status' => $warning_alert]);
        }

        $accounts = '';
        $accounts .= '<div class="col-xl-3 col-lg-4 col-sm-4 col-6">
                            <div class="contacts__item">
                                <a href="#" class="contacts__img">' .
            ' <img src="' . asset($user->profile) . '" alt="" height="120">
                                </a>

                                <div class="contacts__info">' .
            '<strong>' . strtok(ucfirst(strtolower($user->full_name)), ' ') . ' ' . ucfirst(strtolower(strtok(' '))) . '</strong>' .
            '<small>' . $user->email . '</small>' .
            '</div>' .
            '<a href="' . trans('settings/routes.password_reset') . '/' . Encrypter::encrypt($user->user_id) . '" class="btn btn-primary" style="width:100%;"><i class="zmdi zmdi-accounts-list-alt"></i>' . trans('account_management/reset_password.reset_password_header') . '</a>' .
            '</div>' .
            '</div>';

        $data['accounts'] = $accounts;
        return view('account_management.reset_password')->with($data);
    }
}
