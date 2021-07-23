<?php

namespace App\Http\Controllers;

use App\Series;
use App\Setting;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ManageStudentSeriesController extends Controller
{

    /**
     * ManageStudentSeriesController constructor.
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_student_series');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showManageStudentSeriesPage()
    {
        $student_information = '';
        return view('series_management.manage_student_series', compact('student_information'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getStudentInformation(Request $request)
    {
        $this->validate($request, ['student-matricule' => 'required']);

        $matricule = $request->get('student-matricule');
        $student = Student::getStudentByMatricule($matricule);

        if (!empty($student)) {
            $student_information = View::make('series_management.student_information', compact('student'));

            return view('series_management.manage_student_series', compact('student_information'));
        } else {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_student_series.not_a_student_alert', ['mat' => $matricule]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $failure_alert]);

        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changeSeries(Request $request)
    {
        $this->validate($request, ['student-matricule' => 'required', 'series-code']);

        $data = $request->all();
        try {
            $academicYear = Setting::getAcademicYear();
            $series_name = Student::getStudentSeriesNameByMatricule($data['student-matricule']);
            $series_code = Student::getStudentSeriesCodeByMatricule($data['student-matricule']);
            $sequence = Setting::getSequence();
            $student = Student::getStudentByMatricule($data['student-matricule']);
            $sectionCode = Auth::user()->sections_section_code;
            $userId = Auth::user()->user_id;
            $series = collect([
                trans('database/table.matricule') => trim($data['student-matricule']),
                trans('database/table.classes_class_code') => Student::getStudentClassCodeByMatricule($data['student-matricule']),
                trans('database/table.series_series_code') => $data['series-code'],
                trans('database/table.academic_year') => $academicYear,
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId
            ]);
            if (Series::checkStudentSeriesExistanceByMatricule($data['student-matricule'])) {
                Series::saveStudentSeriesChanges($sequence->sequence_id, $series_code, $data['series-code'], $student->student_id, $academicYear, $sectionCode);

                Series::updateStudentSeriesSave($data['student-matricule'], $data['series-code']);
             } else {
                Series::batchStudentSeriesSave($series->toArray());
            }
            $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('series_management/manage_student_series.success_alert', ['mat' => $data['student-matricule'], 'n_series' => $series_name, 'series' => Series::getDBSeriesNameByCode($data['series-code'])]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $success_alert]);
        } catch (\Exception $e) {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_student_series.failure_alert') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $failure_alert]);
        }
    }

}
