<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Role;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountSettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_privilege');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     * show the manage access page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAccountSettingPage()
    {
        $system_users = '';
        $role_users = '';

        $sectionCode = Auth::user()->sections_section_code;
        //get all system users
        $users = User::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.academic_state'), 1)->get();
        foreach ($users as $user) {
            $system_users .= '<a  class="listview__item">' .
                '<div class="pull-left"><img src="' . asset($user->profile) . '" alt="" class="listview__img"></div>' .
                '<div class="listview__content">
                            <div class="listview__heading">' . ucwords(strtolower($user->full_name)) . '</div><p>' . $user->position . '</p>' .
                '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;">';

        }
        $alert_info = '<div class="alert alert-dismissible alert-info">' . trans('access_manager/account_setting.alert_info') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
        $roleId = 0;
        $role_name = ucwords(trans('access_manager/account_setting.role_name'));
        return view('access_manager.account_setting', compact('alert_info', 'role_name', 'system_users', 'role_users', 'roleId'));
    }


    /**
     * Get a list for system functionalities and role functionalities
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getRoleUsers(Request $request)
    {


        $this->validate($request, ['role' => 'required']);

        $sectionCode = Auth::user()->sections_section_code;
        $system_users = '';
        $role_users = '';

        $roleId = $request->get('role');
        try {
            $role = Role::find($roleId);
            if (empty($role) || !Role::isSectionRole($sectionCode, $roleId)) {
                return redirect()->back();
            }
            $r_users = $role->users()->get()->where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.academic_state'), 1);


            $s_users = User::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.academic_state'), 1)->get();
            $s_users = $s_users->whereNotIn(trans('database/table.user_id'), $r_users->pluck(trans('database/table.user_id'))->toArray());

            //prepare selected system functionality list excluding that which is contain in the role privilege list
            foreach ($s_users as $s_user) {
                $system_users .= '<div class="' . $s_user->user_id . '"><a  class="listview__item system_u" onclick="changeRole(' . $s_user->user_id . ')" id ="' . $s_user->user_id . '">' .
                    '<div class="pull-left"><img src="' . asset($s_user->profile) . '" alt="" class="listview__img"></div>' .
                    '<div class="listview__content">
                            <div class="listview__heading">' . ucwords(strtolower($s_user->full_name)) . '</div><p>' . $s_user->position . '</p>' .
                    '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;"></div>';

            }

            foreach ($r_users as $user) {
                $role_users .= '<div class="' . $user->user_id . '"><a  class="listview__item role_u" onclick="changeRole(' . $user->user_id . ')" id ="' . $user->user_id . '">' .
                    '<div class="pull-left"><img src="' . asset($user->profile) . '" alt="" class="listview__img"></div>' .
                    '<div class="listview__content">
                            <div class="listview__heading">' . ucwords(strtolower($user->full_name)) . '</div><p>' . $user->position . '</p>' .
                    '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;"></div>';

            }
            $alert_info = '<div class="alert alert-dismissible alert-success">' . trans('access_manager/account_setting.alert_info') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
            $role_name = trans('access_manager/account_setting.uwr_title') . $role->role_name;

            return view('access_manager.account_setting', compact('alert_info', 'role_name', 'system_users', 'role_users', 'roleId'));
        } catch (Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * update role functionalities
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function updateUsersRole(Request $request)
    {

        $this->validate($request, ['role-id' => 'required']);

        $data = $request->all();

        $sectionCode = Auth::user()->sections_section_code;

        if(Auth::user()->roles_role_id == $data['role-id']){
            $status = 1;
        }else{
            $status = 2;
        }

         try {
        $role = Role::find($data['role-id']);
        if (empty($role) || !Role::isSectionRole($sectionCode, $data['role-id'])) {
            return response()->json(['response' => 'failure'], 202);
        }
        //remove all pre-existing privileges from this role
        $usersIds = $role->users()->get()->where(trans('database/table.academic_state'), 1)->pluck(trans('database/table.user_id'))->toArray();

        Role::setUserRole($usersIds, 0);
        if (!empty($data['users-ids'])) {
            Role::setUserRole($data['users-ids'], $role->role_id);
        }

        return response()->json(['response' => 'success','status' => $status], 200);

         } catch (Exception $e) {
             return response()->json(['response' => 'failure'], 202);
         }
    }

}
