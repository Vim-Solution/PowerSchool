<?php

namespace App\Http\Controllers;

use App\Program;
use App\Series;
use App\Setting;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ManageSubjectSeriesController extends Controller
{
    /**
     * ManageSubjectSeriesController constructor.
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_subject_series');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showManageSubjectSeriesPage()
    {
        $subject_information = '';
        return view('series_management.manage_subject_series', compact('subject_information'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getSubjectInformation(Request $request)
    {
        $this->validate($request, ['subject-title' => 'required', 'class-code' => 'required']);
        $success_alert = '';
        $data = $request->all();
        $subject = Subject::getSubjectByName($data['subject-title'], $data['class-code']);
        if (!empty($subject)) {
            if (empty(Subject::getSubjectSeriesListById($subject->subject_id))) {
                $success_alert .= '<div class="alert alert-dismissible alert-info">' . trans('series_management/manage_subject_series.no_series_alert', ['name' => $subject->subject_title, 'program' => Program::getCycleNameByCode($subject->programs_program_code)]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';
            }
            $success_alert .= '<div class="alert alert-dismissible alert-success">' . trans('series_management/manage_subject_series.retrieve_success', ['name' => $subject->subject_title]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';

            $subject_information = View::make('series_management.subject_information', compact('subject', 'success_alert'));
            session(['s_class_code' => $data['class-code']]);
            return view('series_management.manage_subject_series', compact('subject_information'));
        } else {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_subject_series.no_subject_alert', ['name' => $data['subject-title']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
    public function changeSubjectSeries(Request $request)
    {
        $this->validate($request, ['subject-title' => 'required', 'new-series-code']);

        $data = $request->all();
      //  try {
            $classCode = session('s_class_code');
            $subject = Subject::getSubjectByName($data['subject-title'], $classCode);
            $academicYear = Setting::getAcademicYear();

            $series_code = Subject::getSubjectSeriesListById($subject->subject_id);
            $sequence = Setting::getSequence();
            $sectionCode = Auth::user()->sections_section_code;
            $userId = Auth::user()->user_id;
            $series = collect([
                trans('database/table.subjects_subject_code') => $subject->subject_code,
                trans('database/table.classes_class_code') => $classCode,
                trans('database/table.series_series_code') => $data['new-series-code'],
                trans('database/table.academic_year') => $academicYear,
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId
            ]);
            if ($request->has('old-series-code')) {
                if (Series::checkSubjectSeriesExistanceById($subject->subject_code, $data['old-series-code'])) {
                    Series::saveSubjectSeriesChanges($sequence->sequence_id, $data['new-series-code'], $data['new-series-code'], $subject->subject_code, $academicYear, $sectionCode);

                    Series::updateSubjectSeriesSave($subject->subject_code, $data['old-series-code'], $data['new-series-code']);
                } else {
                    Series::batchSubjectSeriesSave($series->toArray());
                }
            } else {
                $series_code = ' ';
                if (Series::checkSubjectSeriesExistanceById($subject->subject_code, $data['new-series-code'])) {
                    Series::saveSubjectSeriesChanges($sequence->sequence_id, $series_code, $data['new-series-code'], $subject->subject_code, $academicYear, $sectionCode);
                } else {
                    Series::batchSubjectSeriesSave($series->toArray());
                }
            }
            $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('series_management/manage_subject_series.success_alert', ['title' => $data['subject-title'], 'n_series' => Series::getSeriesNameByCode($series_code), 'series' => Series::getDBSeriesNameByCode($data['new-series-code'])]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $success_alert]);
       /* } catch (\Exception $e) {
            $failure_alert = '<div class="alert alert-dismissible alert-danger">' . trans('series_management/manage_subject_series.failure_alert') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';

            return redirect()->back()->with(['status' => $failure_alert]);
        }*/
    }
}
