<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Role;
use App\Setting;
use Exception;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ManageRoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_role');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     * show view,create,edit and delete role page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showManageRolePage()
    {
        $info = '<div class="alert alert-dismissible alert-info">' . trans('access_manager/manage_role.alert_info') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
        return view('access_manager.manage_role', compact('info'));
    }


    /**
     * Create a new role
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createRole(Request $request)
    {
        $this->validate($request,
            [
                'role-name' => 'required',
                'role-description' => 'required'
            ]);
        $data = $request->all();
        $section_code = Auth::user()->sections_section_code;
        $uid = Auth::user()->user_id;

        //create a role
        Role::create([
            trans('database/table.role_name') => $data['role-name'],
            trans('database/table.description') => $data['role-description'],
            trans('database/table.state') => 1,
            trans('database/table.users_user_id') => $uid,
            trans('database/table.sections_section_code') => $section_code,
        ]);
        $success = '<div class="alert alert-dismissible alert-success">' . trans('access_manager/manage_role.c_alert_success') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
        Session::forget('status');
        return redirect()->back()->with(['status' => $success, 'info' => '']);
    }

    /**
     * @param $roleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteRole($roleId)
    {
        $rid = Encrypter::decrypt($roleId);
        try {
            $role = Role::find($rid);
            $permissions = $role->privileges()->get();
            if($permissions->isNotEmpty() || !Role::isAssignedToStaff($roleId)){
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('access_manager/manage_role.not_empty_role',['role' => $role->role_name]))]);
            }
            $role->state = 0;
            $role->save();
            $role->delete();
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('access_manager/manage_role.d_alert_failure'))]);
        }
        return redirect()->back()->with(['status' => Setting::getAlertSuccess( trans('access_manager/manage_role.d_alert_success', ['role' => $role->role_name]))]);
    }

    /**
     * @param $roleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditRolePage($roleId)
    {
        $rid = Encrypter::decrypt($roleId);
        $role = Role::find($rid);
        return view('access_manager.edit_role', compact('role'));
    }

    /**
     * Edit the informations of a role
     * @param Request $request
     * @param $roleId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editRole(Request $request,$roleId)
    {
        $this->validate($request,
            [
                'role-name' => 'required',
                'role-description' => 'required'
            ]);
        $data = $request->all();

        try {
            $rid = Encrypter::decrypt($roleId);
            $role = Role::find($rid);
            Role::updateRoleInformationById($rid, $data);
        }catch (Exception $e){
            $failure = '<div class="alert alert-dismissible alert-danger">' . trans('access_manager/manage_role.e_alert_failure') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
            return redirect()->back()->with(['status' => $failure]);

        }
        $success = '<div class="alert alert-dismissible alert-success">' . trans('access_manager/manage_role.e_alert_success', ['role' => $role->role_name]) . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
        return redirect()->back()->with(['status' => $success]);
    }
}
