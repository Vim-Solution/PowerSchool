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

class viewSubjectController extends Controller
{
    //
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.view_subject');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     */
    public function viewSubjectRequestPage()
    {
        $subject_list = '';
        return view('subject_management.view_subject', compact('subject_list'));
    }

    public function getSubjectListFromClassCode(Request $request){
        $this->validate($request, [
            'class-code' => 'required',
            'academic-year' => 'required'
        ]);

        $classCode = $request->get('class-code');
        $class = AcademicLevel::getClassByCode($classCode);
        $academic_year = $request->get('academic-year');

        $subjects = Subject::getClassSubjects($classCode, $academic_year);


        $subject_list = View::make('subject_management.batch_subject_list', compact('subjects','class','academic_year'));

        return view('subject_management.view_subject', compact('subject_list'));



    }
}
