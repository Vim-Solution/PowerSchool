<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ResetUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.reset_user');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     * load the reset user page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetUserPage()
    {
        $userId = Auth::user()->user_id;
        $userSectionCode = Auth::user()->sections_section_code;
        $users = User::where(trans('database/table.academic_state'), 0)->where(trans('database/table.user_id'), '!=', $userId)  ->where(trans('database/table.sections_section_code'), $userSectionCode)->get();
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
                        '<a href="' . trans('settings/routes.reset_user') . '/' . Encrypter::encrypt($user->user_id) . '" class="btn btn-success" style="width:100%;"> <i class="zmdi zmdi-accounts-alt"></i>' . trans('account_management/reset_user.reset_user_header') . '</a>' .
                        '</div>' .
                        '</div>';
                }
        if ($accounts == '') {
            $accounts = '<div class="alert alert-warning " style="width: 98%;position: relative;left: 1%;">' . trans('account_management/reset_user.empty_user_alert') . '</div>';
        }
        $data['accounts'] = $accounts;
        return view('account_management.reset_user')->with($data);
    }

    /**
     * Perform user reinstallment
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetUser($userId)
    {

        $uid = Encrypter::decrypt($userId);
        $user = User::find($uid);
        $user->academic_state = 1;
        $user->save();
        User::recordUserActions(2,$user->full_name);

        $success_alert = '<div class="alert alert-success">' . trans('account_management/reset_user.reset_user_successful', ['name' => $user->full_name]) . '</div>';
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
        if (empty($user) || $user->academic_state == 1) {
            $warning_alert = '<div class="alert alert-warning">' . trans('account_management/reset_user.user_not_found', ['email' => $email]) . '</div>';
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
            '<a href="' . trans('settings/routes.reset_user') . '/' . Encrypter::encrypt($user->user_id) . '" class="btn btn-success" style="width:100%;"> <i class="zmdi zmdi-accounts-alt"></i>' . trans('account_management/reset_user.reset_user_header') . '</a>' .
            '</div>' .
            '</div>';

        $data['accounts'] = $accounts;
        return view('account_management.reset_user')->with($data);
    }
}
