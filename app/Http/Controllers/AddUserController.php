<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

define('PASSWORD_LIMIT', 10);

class AddUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.add_user');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * load the add user page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddUserPage()
    {
        return view('account_management.add_user');
    }

    /**
     * Register a new user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addUser(Request $request)
    {
        //set the validation rules
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
        //make sure DB unique keys are maintained
        if (User::emailExist($data['email-address'])) {
            $email_exist = '<div class="alert alert-danger"><ul><li>' . trans('account_management/add_user.email_exist', ['email' => $data['email-address']]) . '</li></ul></div>';
            return redirect()->back()->with(['status' => $email_exist]);
        } elseif (User::phoneExist($data['mobile-phone'])) {
            $phone_exist = '<div class="alert alert-danger"><ul><li>' . trans('account_management/add_user.phone_exist', ['phone' => $data['mobile-phone']]) . '</li></ul></div>';
            return redirect()->back()->with(['status' => $phone_exist]);

        }
        //randomly generate a unique user password
        $password = Str::random(PASSWORD_LIMIT);

        //create the user
        User::create([
            trans('database/table.user_name') => $data['first-name'],
            trans('database/table.full_name') => $data['first-name'] . ' ' . $data['last-name'],
            trans('database/table.email') => $data['email-address'],
            trans('database/table.phone_number') => $data['mobile-phone'],
            trans('database/table.position') => $data['job-title'],
            trans('database/table.type') => $data['job-type'],
            trans('database/table.address') => $data['address'],
            trans('database/table.office_address') => $data['office-address'],
            trans('database/table.profile') => trans('img/img.default_profile'),
            trans('database/table.password') => Hash::make($password),
            trans('database/table.programs_program_code') => $data['program'],
            trans('database/table.sections_section_code') => $data['section'],
            trans('database/table.departments_department_id') => $data['department'],
            trans('database/table.roles_role_id') => $data['role'],
            trans('database/table.users_user_id') => Auth::user()->user_id,
        ]);

        //perform the next actions if the user is successfully created
        $success_alert = '<div class="alert alert-success">' . trans('account_management/add_user.add_user_successful', ['name' => $data['first-name'] . ' ' . $data['last-name'], 'email' => $data['email-address'], 'password' => $password]) . '</div>';

        return redirect()->back()->with(['status' => $success_alert]);
    }
}
