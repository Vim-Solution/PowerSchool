<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Privilege;
use App\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageAccessController extends Controller
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
    public function showManageAccessPage()
    {
        $system_functionalities = '';
        $role_functionalities = '';

        $sectionCode = Auth::user()->sections_section_code;
        //get all system functionionaliies
        $functionalities = Privilege::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.state'), 1)->get();
        $functionalities = $functionalities->concat(Privilege::where(trans('database/table.sections_section_code'), trans('database/table.bilingual'))->where(trans('database/table.state'), 1)->get());

        foreach ($functionalities as $functionality) {
            $system_functionalities .= '<a  class="listview__item bg-light" style="padding:1px 1px 1px 1px;margin: 0;" id ="' . $functionality->privilege_id . '">
                        <i class="' . trans('authorization/privilege.' . $functionality->privilege_icon) . ' avatar-char c-ewangclarks" ></i>' .
                '<div class="listview__content">
                            <div class="listview__heading p-3">' . trans('authorization/privilege.' . $functionality->privilege_name) . '</div>' .
                '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;">';

        }
        $alert_info = '<div class="alert alert-dismissible alert-info">' . trans('access_manager/manage_access.alert_info') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
        $role_users = '';
        $roleId = 0;
        $role_name = ucwords(trans('access_manager/manage_access.role_name'));
        return view('access_manager.manage_access', compact('alert_info', 'role_name', 'system_functionalities', 'role_functionalities', 'role_users', 'roleId'));
    }


    /**
     * Get a list for system functionalities and role functionalities
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getFunctionalitiesByRoleId(Request $request)
    {

        $this->validate($request, ['role' => 'required']);

        $sectionCode = Auth::user()->sections_section_code;
        $system_functionalities = '';
        $role_functionalities = '';

        $roleId = $request->get('role');
        try {
            $role = Role::find($roleId);
            if (empty($role) || !Role::isSectionRole($sectionCode, $roleId)) {
                return redirect()->back();
            }
            $role_funcs = $role->privileges()->get()->unique(trans('database/table.privilege_id'));


            $functionalities = Privilege::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.state'), 1)->get();
            $functionalities = $functionalities->concat(Privilege::where(trans('database/table.sections_section_code'), trans('database/table.bilingual'))->where(trans('database/table.state'), 1)->get());
            $functionalities = $functionalities->whereNotIn(trans('database/table.privilege_id'), $role_funcs->pluck(trans('database/table.privilege_id'))->toArray());
            //prepare selected system functionality list excluding that which is contain in the role privilege list
            foreach ($functionalities as $functionality) {
                $system_functionalities .= '<div class="' . $functionality->privilege_id . '"><a  class="listview__item bg-light system_func" style="padding:1px 1px 1px 1px;margin: 0;" onclick="swapFunctionality(' . $functionality->privilege_id . ')" id ="' . $functionality->privilege_id . '">
                      <i class="' . trans('authorization/privilege.' . $functionality->privilege_icon) . ' avatar-char c-ewangclarks" ></i>' .
                    '<div class="listview__content">
                            <div class="listview__heading p-3">' . trans('authorization/privilege.' . $functionality->privilege_name) . '</div>' .
                    '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;"></div>';

            }

            //prepare selected role functionality list
            foreach ($role_funcs as $role_func) {
                $role_functionalities .= '<div class="' . $role_func->privilege_id . '"><a  class="listview__item role_func" style="padding:1px 1px 1px 1px;margin: 0;" onclick="swapFunctionality(' . $role_func->privilege_id . ')" id ="' . $role_func->privilege_id . '">
                      <i class="' . trans('authorization/privilege.' . $role_func->privilege_icon) . ' avatar-char c-ewangclarks" ></i>' .
                    '<div class="listview__content">
                            <div class="listview__heading p-3">' . trans('authorization/privilege.' . $role_func->privilege_name) . '</div>' .
                    '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;"></div>';

            }
            $alert_info = '<div class="alert alert-dismissible alert-success">' . trans('access_manager/manage_access.alert_info') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
            $role_users = Role::getContactList($roleId);
            $role_name = $role->role_name;

            return view('access_manager.manage_access', compact('alert_info', 'role_name', 'system_functionalities', 'role_functionalities', 'role_users', 'roleId'));
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

    public function updateAuthorization(Request $request)
    {

        $this->validate($request, ['role-id' => 'required']);

        $data = $request->all();

        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

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
            $role->privileges()->detach();
            if (!empty($data['func-ids'])) {
                $role->privileges()->attach($data['func-ids'], ['users_user_id' => $userId, 'state' => 1]);
            }
            return response()->json(['response' => 'success','status' => $status], 200);

        } catch (Exception $e) {
            return response()->json(['response' => 'failure'], 202);
        }
    }
}
