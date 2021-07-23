<?php

namespace App\Http\Controllers;

use App\Series;
use App\Setting;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddSubjectController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.add_subject');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * load the add subject page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddSubjectPage()
    {
        return view('subject_management.add_subject');
    }

    /**
     * Register a new subject
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addSubject(Request $request)
    {
        //set the validation rules
        $this->validate($request,
            [
                'subject-title' => 'required',
                'coefficient' => 'required',
                'state' => '',
                'class-code' => 'required',
                'subject-weight' => 'required',
                'subject-code' => 'required',
                'program' => 'required',
                'academic-year' => 'required',
                'series-code[]' => '',
            ]);
        //request all the data from the user end
        $data = $request->all();
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;
        $academicYear = Setting::getAcademicYear();

        //make sure DB unique keys are maintained
        if (Subject::subjectExist($data['subject-code'])) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('subject_management/add_subject.subject_exist', ['subject code' => $data['subject-code']]))]);
        }

        $data['state'] = $this->getOfferedState($data['state']);

        $subjectList = collect([]);
        $seriesSubjectList = collect([]);

        if ($data['program'] == 'al' || $data['program'] == 'alt' || $data['program'] == 'alc') {
            if (!empty($data['series-code'])) {
                $this->createSeriesData($data['series-code'], $data['subject-code'], $data['class-code'], $sectionCode, $userId, $academicYear);
            }
        }
        $this->createClassHasSubjectData($data['subject-code'], $data['class-code'], $sectionCode, $userId, $academicYear);

        Subject::create([
            trans('database/table.subject_title') => $data['subject-title'],
            trans('database/table.subject_code') => $data['subject-code'],
            trans('database/table.state') => $data['state'],
            trans('database/table.coefficient') => $data['coefficient'],
            trans('database/table.classes_class_code') => $data['class-code'],
            trans('database/table.subject_weight') => $data['subject-weight'],
            trans('database/table.academic_year') => $data['academic-year'],
            trans('database/table.programs_program_code') => $data['program'],
            trans('database/table.sections_section_code') => $sectionCode,
            trans('database/table.departments_department_id') => Auth::user()->departments_department_id,
            trans('database/table.users_user_id') => $userId,
        ]);


        //AcademicLevel::batchSubjectLevelSave($subjectList->toArray());
        Series::batchSubjectSeriesSave($seriesSubjectList->toArray());

        return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('subject_management/add_subject.add_subject_successful'))]);
    }


    /**
     * @param $seriesCodes
     * @param $subjectCode
     * @param $classCode
     * @param $sectionCode
     * @param $userId
     * @param $academicYear
     * @return int
     */
    private function createSeriesData($seriesCodes, $subjectCode, $classCode, $sectionCode, $userId, $academicYear)
    {
        $resource = collect([]);
        foreach ($seriesCodes as $seriesCode) {
            if (!Series::checkSeriesSubjectExistence($seriesCode, $subjectCode)) {
                $resource = $resource->push([trans('database/table.series_series_code') => $seriesCode,
                    trans('database/table.subjects_subject_code') => $subjectCode,
                    trans('database/table.classes_class_code') => $classCode,
                    trans('database/table.sections_section_code') => $sectionCode,
                    trans('database/table.users_user_id') => $userId,
                    trans('database/table.academic_year') => $academicYear
                ]);
            }
        }

        Series::batchSubjectSeriesSave($resource->toArray());

        return 0;
    }

    /**
     * @param $subjectCode
     * @param $classCode
     * @param $sectionCode
     * @param $userId
     * @param $academicYear
     * @return int
     */
    private function createClassHasSubjectData($subjectCode, $classCode, $sectionCode, $userId, $academicYear)
    {

        DB::table(trans('database/table.classes_has_subjects'))
            ->insert([
                trans('database/table.classes_class_code') => $classCode,
                trans('database/table.subjects_subject_code') => $subjectCode,
                trans('database/table.academic_year') => $academicYear,
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId,
            ]);
        return 0;
    }

    /**
     * @param $state
     */
    private function getOfferedState($state)
    {
        if (!empty($state)) {
            if ($state == "true") {
                $state = 1;
            } else {
                $state = 0;
            }
        } else {
            $state = 0;
        }
    }

}
