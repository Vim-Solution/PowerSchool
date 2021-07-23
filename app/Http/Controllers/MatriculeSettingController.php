<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MatriculeSettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.matricule_setting');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * Show the matricule setting page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMatriculeSettingPage(){
        $sectionCode = Auth::user()->sections_section_code;
        $academicYear = Setting::getAcademicYear();
        $matSettings = Setting::getMatriculeSettingBySectionCode($sectionCode, $academicYear);
        if ($matSettings->isEmpty()) {
            $matricule_setting_list = '';
        } else {
            $matricule_setting_list  = View::make('academic_setting.matricule_setting_table',compact('matSettings'));
        }
        return view('academic_setting.matricule_setting',compact('matricule_setting_list'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setMatriculeParameters(Request $request){
        $this->validate($request, ['matricule-initial' => 'required',
                'program_code' => 'required']
        );
        $data = $request->all();


        if(Setting::matriculeInitialExist($data)){
            Setting::updateMatriculeSetting($data);

            $update_alert = '<div class="alert alert-dismissible alert-success">' . trans('general.mat_update_alert') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
     return redirect()->back()->with(['status' => $update_alert]);

        }
        Setting::saveMatriculeSetting($data);
        $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('general.mat_create_alert') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
     return redirect()->back()->with(['status' => $success_alert]);
    }
}
