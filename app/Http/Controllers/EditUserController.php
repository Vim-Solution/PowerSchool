<?php

namespace App\Http\Controllers;

use App\Department;
use App\Program;
use App\Role;
use App\SchoolSection;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class EditUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.edit_user');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     *  show the edit user page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditUserPage()
    {
        $profile = '';
        $success_alert = '';
        return view('account_management.edit_user_search', compact('profile', 'success_alert'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editUser(Request $request)
    {
        //set the validation rules
        $profile = '';
        $this->validate($request,
            ['first-name' => 'required',
                'last-name' => 'required',
                'job-title' => 'required',
                'job-type' => 'required',
                'mobile-phone' => 'required',
                'email-address' => 'required',
                'program' => 'required',
                'section' => 'required',
                'department' => 'required',
                'address' => 'required',
                'office-address' => 'required',
                'role' => 'required',
            ]);
        //request all the data from the user end
        $data = $request->all();
        try {
            User::massUpdateUserInfoByEmail($data['email-address'], $data);
            $user = User::searchUserByEmail($data['email-address']);
            if (User::phoneExist($data['mobile-phone']) && $data['mobile-phone'] != $user->phone_number) {
                $phone_exist = '<div class="alert alert-danger"><ul><li>' . trans('account_management/edit_user.phone_exist', ['phone' => $data['mobile-phone']]) . '</li></ul></div>';
                return redirect()->back()->with(['status' => $phone_exist]);
            }
            $profile .= '<div class="card profile"><div class="profile__img">' .
                ' <img src="' . asset($user->profile) . '" alt=""><a href="" class="zmdi zmdi-camera profile__img__edit"></a></div>' .
                '<div class="profile__info"><p style="color: #0D0A0A;"><b>' . trans('profile.position') . '</b></p>' .
                '<ul class="icon-list">' .
                '<li><i class="zmdi zmdi-graduation-cap"></i>' . $user->position . '</li>' .
                '<li><i class="zmdi zmdi-phone"></i>' . $user->phone_number . '</li>' .
                '<li><i class="zmdi zmdi-email"></i>' . $user->email . '</li>' .
                '<li><i class="zmdi zmdi-my-location"></i>' . $user->office_address . '</li>' .
                '</ul></div></div><div class="toolbar"><nav class="toolbar__nav"><h6 class="active">' . trans('profile.general_info') . '</h6></nav>' .
                '<div class="actions">' .
                '<i class="actions__item " data-ma-action="toolbar-search-open"></i></div><div class="toolbar__search"><input type="text" placeholder="Search...">' .
                '<i class="toolbar__search__close zmdi zmdi-long-arrow-left" data-ma-action="toolbar-search-close"></i></div></div>' .
                '<div class="card"><div class="card-body"><h4 class="card-body__title mb-4">' . trans('profile.about_caption', ['name' => strtok(ucfirst(strtolower($user->full_name)), ' ') . ' ' . ucfirst(strtolower(strtok(' ')))]) . '</h4>' .
                '<p>' . trans('profile.about_text', ['name' => strtok(ucfirst(strtolower($user->full_name)), ' ') . ' ' . ucfirst(strtolower(strtok(' '))), 'post' => $user->type . ' ' . $user->position, 'school' => trans('settings/setting.school_name')]) . '</p>' .
                '<br><h4 class="card-body__title mb-4">' . trans('profile.contact_caption') . '</h4>' .
                '<ul class="icon-list">' .
                ' <li><i class="zmdi zmdi-phone"></i>' . $user->phone_number . '</li>' .
                '<li><i class="zmdi zmdi-email"></i>' . $user->email . '</li>' .
                '<li><i class="zmdi zmdi-my-location"></i>' . $user->address . '</li>' .
                ' </ul><br><br><h4 class="card-body__title mb-4">' . trans('profile.school_info') . '</h4>' .
                '<ul class="icon-list">' .
                '<li><i class="zmdi zmdi-pin"></i>' . SchoolSection::getSectionNameByCode($user->sections_section_code) . '</li>' .
                '<li><i class="zmdi zmdi-pin-drop"></i>' . Program::getCycleNameByCode($user->programs_program_code) . '</li>' .
                '<li><i class="zmdi zmdi-graduation-cap"></i> ' . $user->position . '</li>' .
                '<li><i class="zmdi zmdi-my-location"></i>' . $user->office_address . '</li>' .
                '<li><i class="zmdi zmdi-map"></i>' . Department::getDepartmentNameById($user->departments_department_id) . '</li>' .
                '<li><i class="zmdi zmdi-account-calendar"></i>' . $user->type . '</li>' .
                '<li><i class="zmdi zmdi-nature-people"></i>' . Role::getRoleNameById($user->roles_role_id) . '</li>' .
                '</ul></div></div>';

            $success_alert = '<div class="alert alert-success">' . trans('account_management/edit_user.edit_user_success', ['email' => $user->email]) . '</div>';

            User::recordUserActions(3,$user->full_name);
            return view('account_management.edit_user_search', compact('profile', 'success_alert'));
        } catch (\Exception $exception) {
            $user = User::searchUserByEmail($data['email-address']);
            $failure_alert = '<div class="alert alert-danger">' . trans('account_management/edit_user.edit_user_failure', ['email' => $user->email]) . '</div>';
            return redirect()->back()->with(['status' => $failure_alert]);
        }
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
        if (empty($user) || $user->academic_state == 0) {
            $warning_alert = '<div class="alert alert-warning">' . trans('account_management/edit_user.user_not_found', ['email' => $email]) . '</div>';
            return redirect()->back()->with(['status' => $warning_alert]);
        }

        return view('account_management.edit_user', compact('user'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectHacker()
    {
        return redirect()->back();
    }
}
