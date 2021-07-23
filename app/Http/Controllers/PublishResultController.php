<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Evaluation;
use App\Notification;
use App\PublishStatus;
use App\Setting;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class PublishResultController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.publish_result');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPublishResultPage()
    {
        $sequence = Setting::getSequence();
        $academicYear = Setting::getAcademicYear();

        $unsubmitted_subjects = Subject::getSubjectsNotSubmitted($sequence->sequence_id, $academicYear);
        $unsubmitted_subjects = $unsubmitted_subjects->concat(Subject::getSubjectWithFullMarksNotSubmitted($sequence->sequence_id, $academicYear));

        if ($unsubmitted_subjects->isEmpty()) {
            $publish_result_exception_list = '<div class="profile__img" style="position: relative;left: 27%">' .
                '<img src="' . asset(trans('img/img.book_logo')) . '" alt="" height="300px;" width="500px">' .
                '<img src="' . asset(trans('img/img.student')) . '" alt="" height="300px;" width="500px">' .
                '</div><br>';
        } else {
            $publish_result_exception_list = View::make('result_management.publish_result_exception_list', compact('unsubmitted_subjects'));
        }
        return view('result_management.publish_result', compact('publish_result_exception_list'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publishResults()
    {
        $sequence = Setting::getSequence();
        $academicYear = Setting::getAcademicYear();

        try {
            if (Setting::hasPublishDatePass(Auth::user()->sections_section_code)) {
                $subjects_scores = Subject::getSubjectScores($sequence->sequence_id, $academicYear);

                $subjectIds = $subjects_scores->unique(trans('database/table.subjects_subject_id'))->pluck(trans('database/table.subjects_subject_id'))->toArray();
                $studentIds = $subjects_scores->unique(trans('database/table.students_student_id'))->pluck(trans('database/table.students_student_id'))->toArray();

                foreach ($subjectIds as $subjectId) {
                    $studentIds = $subjects_scores->where(trans('database/table.subjects_subject_id'), $subjectId)->unique(trans('database/table.students_student_id'))->pluck(trans('database/table.students_student_id'))->toArray();
                    foreach ($studentIds as $studentId) {
                        $totalStudentScore = 0;
                        $totalNumberOccurrance = 0;
                        $finalMark = 0;
                        $subjects_score = $subjects_scores->where(trans('database/table.subjects_subject_id'), $subjectId)->where(trans('database/table.students_student_id'), $studentId);

                        foreach ($subjects_score as $score) {
                            $totalStudentScore += $score->subject_score;
                            $totalNumberOccurrance++;
                        }

                        $finalMark = $totalStudentScore / $totalNumberOccurrance;
                        Subject::updateOrCreateFinalMarks($studentId, $sequence->sequence_id, $academicYear, $subjectId, $finalMark);

                        PublishStatus::updateOrCreate($sequence->sequence_id, $academicYear);
                    }
                }
                $state = $this->calculateAverages($sequence, $academicYear);
                if ($state == 1) {
                    return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/publish_result.publish_result_success', ['sequence' => $sequence->sequence_name, 'year' => $academicYear]))]);
                } else {
                    return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/publish_result.average_failure'))]);
                }
            } else {
                return redirect()->back()->with(['status' => Setting::getAlertWarning(trans('result_management/publish_result.publish_date_alert'))]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/publish_result.publish_result_failure'))]);
        }
    }

    /**
     * @param $sequence
     * @param $academicYear
     * @return int
     */
    private function calculateAverages($sequence, $academicYear)
    {

        try {
            Evaluation::calculateAverages($sequence, $academicYear);
            return 1;
        } catch (\Exception $e) {
            return 0;
        }

    }


    /**
     * @param $sid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showNotificationPage($sid)
    {
        $sequence = Setting::getSequence();
        $academicYear = Setting::getAcademicYear();

        try {
            $subjectId = Encrypter::decrypt($sid);
            $subject = Subject::find($subjectId);
            $teachers = Subject::getTeachersWithMarksNotSubmitted($subjectId, $sequence->sequence_id, $academicYear);
            $teacherIds = $teachers->unique(trans('database/table.user_id'))->pluck(trans('database/table.user_id'))->toArray();
            if (Session::has('pr_teacher_ids')) {
                Session::forget('pr_teacher_ids');
            }
            Session::put('pr_teacher_ids', $teacherIds);
            return view('result_management.notify', compact('teachers', 'subject'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function notify(Request $request)
    {
        $this->validate($request, ['subject' => 'required', 'body' => 'required']);
        $academicYear = Setting::getAcademicYear();


        if (Session::has('pr_teacher_ids')) {
            $teacherIds = Session::get('pr_teacher_ids');
        } else {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/publish_result.notify_failure'))]);
        }
        try {
            $notication = $request->all();
            $userId = Auth::user()->user_id;

            $resource = collect([]);

            //create same nofications for each lecturer taking this course
            foreach ($teacherIds as $teacherId) {
                $resource = $resource->push([trans('database/table.notification_subject') => $notication['subject'],
                    trans('database/table.notification_body') => $notication['body'],
                    trans('database/table.users_user_id') => $teacherId,
                    trans('database/table.notifier_id') => $userId,
                    trans('database/table.academic_year') => $academicYear,
                    trans('database/table.state') => 0
                ]);
            }

            Notification::saveNotifications($resource->toArray());
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/publish_result.notify_success'))]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/publish_result.notify_failure'))]);
        }
    }

}
