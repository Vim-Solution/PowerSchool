<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Series;
use App\Setting;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class EditSubjectController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.edit_subject');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     *  show the edit Subject page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditSubjectPage()
    {
        $subject_profile = '';
        $success_alert = '';
        return view('subject_management.edit_subject_search1', compact('subject_profile', 'success_alert'));
    }

    /*
    This method searches the keyword entered to search on a subject to perform an edit/delete and redirect the results
    */

    public function showSubjectQueryPage()
    {
        $q = Input::get('q');
        $sectionCode = Auth::user()->sections_section_code;

            $subjects = DB::table(trans('database/table.subjects'))
                ->where(trans('database/table.sections_section_code'), $sectionCode)
                ->where(trans('database/table.subject_code'), 'LIKE', '%' . $q . '%')
                ->orwhere(trans('database/table.subject_title'), 'LIKE', '%' . $q . '%')
                ->orwhere(trans('database/table.classes_class_code'), 'LIKE', '%' . $q . '%')
                ->get();

        if ($subjects->count() <= 0) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('subject_management/edit_subject.search_failure'))]);
        } else {
            $edit_subject_modal = '';
            return view('subject_management.search_result', compact('subjects', 'edit_subject_modal'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editSubject(Request $request)
    {
        //set the validation rules

        $subject_profile = '';
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
                'series-code[]' => ' ',
            ]);
        //request all the data from the Subject end
        $data = $request->all();
        try {
            if ($data['state'] == "on") {
                $data['state'] = 1;
                $act_state = "Enable";
            } else {
                $data['state'] = 0;
                $act_state = "Disable";
            }
            if ($data['program'] == "al" || $data['program'] == 'alt' || $data['program'] == 'alc') {
                $series = Series::getSeriesListName($data['subject-code']);
                foreach ($series as $serie) {
                    $seriesName[] = $serie->series_series_code;
                }
                $seriescode = null;
                $subject_code = $data['subject-code'];
                $series_date = $data['series-code'];

                if (!empty($series_date)) {
                    /*Deleting unwanted Roles for edited series code both in the subject table and series has subject table */
                    foreach ($seriesName as $prevSeries) {
                        $serieschecker = 0;
                        foreach ($data['series-code'] as $newSeries) {
                            if ($prevSeries == $newSeries) {
                                $serieschecker++;
                            }
                        }
                        if ($serieschecker == 0) {
                            DB::table(trans('database/table.series_has_subjects'))->where(trans('database/table.subjects_subject_code'), $subject_code)->where(trans('database/table.series_series_code'), $prevSeries)->delete();
                        }
                    }
                }
            }

            Subject::massUpdateSubjectInfoBySubjectCode($data['subject-code'], $data);
            $subject = Subject::getSubjectBySubject_code($data['subject-code']);
             Subject::recordSubjectActions(2,$subject->subject_title);
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('subject_management/edit_subject.edit_success', ['code' => $data['subject-code']]))]);

        } catch (\Exception $exception) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('subject_management/edit_subject.edit_failure', ['code' => $data['subject-code']]))]);
        }
    }


    /**
     * Search a Subject by subject code
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function searchSubject()
    {
        $subject_code = Input::get('subject-code');
        $subject = Subject::searchSubjectBySubjectCode($subject_code);
        if (empty($subject)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('subject_management/edit_subject.subject_not_found', ['Subject Code' => $subject_code]) )]);
        }
        $seriesName[] = null;
        $series = Series::getSeriesListName($subject_code);
        foreach ($series as $serie) {
            $seriesName[] = $serie->series_series_code;
        }


        return view('subject_management.edit_subject', compact('subject', 'seriesName'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectHacker()
    {
        return redirect()->back();
    }


    /**
     * @param $subjectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editSubjectById($subjectId)
    {

        $subject_id = Encrypter::decrypt($subjectId);
        $subject = DB::table(trans('database/table.subjects'))
            ->where(trans('database/table.subject_id'), $subject_id)
            ->first();

        $seriesName[] = null;
        $series = Series::getSeriesListName($subject->subject_code);
        foreach ($series as $serie) {
            $seriesName[] = $serie->series_series_code;
        }

        return view('subject_management.edit_subject', compact('subject', 'seriesName'));
    }

    /**
     * @param $subjectId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSubjectById($subjectId)
    {
        $subject_id = Encrypter::decrypt($subjectId);
        $subject = DB::table(trans('database/table.subjects'))
            ->where(trans('database/table.subject_id'), $subject_id)
            ->first();
        if (empty($subject)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('subject_management/edit_subject.empty_subject'))]);
        }
        if ($this->checkSubjectExistance($subject_id)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('subject_management/edit_subject.subject_existance'))]);

        }
        DB::table(trans('database/table.subjects'))
            ->where(trans('database/table.subject_id'), $subject->subject_id)
            ->delete();
        DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subject->subject_code)
            ->delete();
        $series = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subject->subject_code)
            ->get();
        if (count($series) <= 0 || empty($series)) {
            DB::table(trans('database/table.classes_has_subjects'))
                ->where(trans('database/table.subjects_subject_code'), $subject->subject_code)
                ->delete();
        }

        Subject::recordSubjectActions(1,$subject->subject_title);

        $success_alert = '<div class="alert alert-success">' . 'Successfully deleted the subject ' . $subject->subject_code . '</div>';

        return redirect()->back()->with(['status' => $success_alert]);
    }

    /**
     * @param $subjectId
     * @return bool
     */
    private function checkSubjectExistance($subjectId)
    {
        $subject = Subject::find($subjectId);

        $subject_scores = DB::table(trans('database/table.tests_has_scores'))
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->first();

        if (!empty($subject_scores)) {
            return true;
        }

        return false;
    }
}
