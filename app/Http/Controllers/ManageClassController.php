<?php

namespace App\Http\Controllers;

use App\AcademicLevel;
use App\Encrypter;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ManageClassController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_class');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * show the page for where the exam parameters are set
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddClassPage()
    {
        $sectionCode = Auth::user()->sections_section_code;
        $classs = AcademicLevel::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        if ($classs->isEmpty()) {
            $class_list = '';
        } else {
            $sn = 1;
            $class_list = View::make('academic_setting.class_list', compact('classs', 'sn'));
        }
        return view('academic_setting.add_class', compact('class_list'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addClass(Request $request)
    {
        $this->validate($request, ['class-code' => 'required',
            'class-name' => 'required', 'program-code' => 'required', 'promotion-average' => 'required|numeric', 'p-class-code' => 'required',
        ]);

        $data = $request->all();

        //get user section code
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        try {
            //check if the class code exist
            if (AcademicLevel::classCodeExist($data['class-code'], $sectionCode)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_class.code_exist', ['code' => $data['class-code']]))]);
            }

            //check if the class name exist
            if (AcademicLevel::classNameExist($data['class-name'], $sectionCode)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_class.name_exist', ['name' => $data['class-name']]))]);
            }

            if ($data['p-class-code'] == trans('general.university')) {
                $data['p-class-code'] = trans('general.university_code');
            }

            AcademicLevel::create([trans('database/table.class_name') => $data['class-name'],
                trans('database/table.class_code') => $data['class-code'],
                trans('database/table.programs_program_code') => $data['program-code'],
                trans('database/table.annual_promotion_average') => $data['promotion-average'],
                trans('database/table.next_promotion_class') => $data['p-class-code'],
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId
            ]);


            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('academic_setting/manage_class.as_success'))]);

        } catch (Illuminate\Filesystem\FileNotFoundException $e) {

            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_class.as_failure'))]);
        }

    }


    /**
     * @param $classId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteClass($classId)
    {
        $sid = Encrypter::decrypt($classId);
        try {
            $class = AcademicLevel::find($sid);


            if (AcademicLevel::classExistanceAndDistribution($class->class_code)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_class.delete_failure', ['class' => $class->class_name]))]);
            }

            $class->delete();
            AcademicLevel::recordClassActions(1,$class->class_name);
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('academic_setting/manage_class.ds_success', ['name' => $class->class_name]))]);


        } catch (\Exception $e) {

            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_class.ds_failure', ['name' => $class->class_name]))]);

        }
    }


    /**
     * @param Request $request
     * @param $classId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editClass(Request $request, $classId)
    {
        $this->validate($request, ['class-code' => 'required',
            'class-name' => 'required', 'program-code' => 'required', 'promotion-average' => 'required|numeric', 'p-class-code' => 'required'
        ]);
        $sid = Encrypter::decrypt($classId);
        $data = $request->all();
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        try {
            //check if the class code exist
            if (AcademicLevel::classCodeExistById($data['class-code'], $sectionCode, $sid)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_class.code_exist', ['code' => $data['class-code']]))]);
            }

            //check if the class name exist
            if (AcademicLevel::classNameExistById($data['class-name'], $sectionCode, $sid)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_class.name_exist', ['name' => $data['class-name']]))]);
            }
            $class = AcademicLevel::find($sid);
            if (!AcademicLevel::classExistanceAndDistribution($class->class_code)) {
                $class->programs_program_code = $data['program-code'];
            }

            if ($data['p-class-code'] == trans('general.university')) {
                $data['p-class-code'] = trans('general.university_code');
            }

            $class->next_promotion_class = $data['p-class-code'];
            $class->class_name = strip_tags($data['class-name']);
            $class->class_code = $data['class-code'];
            $class->annual_promotion_average = $data['promotion-average'];
            $class->next_promotion_class = $data['p-class-code'];
            $class->users_user_id = $userId;
            $class->save();

            AcademicLevel::recordClassActions(2,$class->class_name);
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('academic_setting/manage_class.es_success', ['name' => $class->class_name]))]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('academic_setting/manage_class.es_failure', ['name' => $data['class-name']]))]);

        }
    }

}
