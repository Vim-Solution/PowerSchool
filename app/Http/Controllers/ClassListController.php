<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AcademicLevel;
use App\Program;
use App\Setting;
use App\Subject;
use App\Department;
use App\User;
use App\Series;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class ClassListController extends Controller
{
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.get_class_list');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     */
    public function showClassListRequestPage()
    {
        $class_list = '';
        return view('subject_management.class_list_request', compact('class_list'));
    }

    public function getClassListFromClassCode(Request $request){
        $this->validate($request, [
            'class-code' => 'required',
            'academic-year' => 'required'
        ]);

        $classCode = $request->get('class-code');
        $academic_year = $request->get('academic-year');

        $student_lists = AcademicLevel::getClassListFromClassCode($classCode, $academic_year);

        $sn = 0;

        $class_list = View::make('subject_management.class_list', compact('student_lists','classCode','academic_year','sn'));

        return view('subject_management.class_list_request', compact('class_list'));



    }

    
}
