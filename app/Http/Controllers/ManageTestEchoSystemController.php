<?php

namespace App\Http\Controllers;

use App\AcademicLevel;
use App\Encrypter;
use App\Series;
use App\Setting;
use App\Subject;
use App\TeacherHandler;
use App\TestManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ManageTestEchoSystemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_subject_test');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTeacherSubjectListPage()
    {
        $teacherId = Auth::user()->user_id;
        $academicYear = Setting::getAcademicYear();

        $teacherSubjects = TeacherHandler::getTeacherSubjectsPerAcademicYear($academicYear, $teacherId);
        if ($teacherSubjects->isEmpty()) {
            $subject_list = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/manage_test.no_assigned_subject_alert') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
        } else {
            $list_title = '';
            $subject_list = View::make('subject_management.subject_list', compact('teacherSubjects', 'list_title'));
        }

        return view('subject_management.teacher_subjects', compact('subject_list'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    /*   public function getTeacherSubjectList(Request $request)
       {
           $this->validate($request, ['class-code' => 'required', 'program-code' => 'required']);

           $data = $request->all();

           $teacherId = Auth::user()->user_id;
           $academicYear = Setting::getAcademicYear();

           $teacherSubjects = TeacherHandler::getTeacherSubjectsPerClass($academicYear, $teacherId, $data['class-code'], $data['program-code']);
           if ($teacherSubjects->isEmpty()) {
               $subject_list = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/manage_test.no_assigned_subject_alert') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                   </button></div>';
           } else {
               $list_title = trans('subject_management/manage_test.assigned_list_per_class',['class' => AcademicLevel::getClassNameByCode($data['class-code'])]);
               $subject_list = View::make('subject_management.subject_list', compact('teacherSubjects','list_title'));
           }

           return view('subject_management.teacher_subjects', compact('subject_list'));

       }*/

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showManageTestPage($id)
    {
        $subjectId = Encrypter::decrypt($id);
        $teacherId = Auth::user()->user_id;
        $academicYear = Setting::getAcademicYear();
        if (TeacherHandler::isNotTeacherAssignedSubject($teacherId, $subjectId, $academicYear)) {
            return redirect()->back();
        }

        $subject = Subject::find($subjectId);

        if (empty($subject)) {
            return redirect()->back();
        }

        $sequence = Setting::getSequence();
        $sequenceSubjectTests = Subject::getSubjectTestBySequence($subjectId, $sequence->sequence_id, $teacherId, $academicYear);
        $subjectTests = Subject::getSubjectTestBySequenceDifference($subjectId, $sequence->sequence_id, $teacherId, $academicYear);
        if (Session::has('test_functionality_subject_id')) {
            Session::forget('test_functionality_subject_id');
        }
        Session::put('test_functionality_subject_id', $id);

        if ($sequenceSubjectTests->isEmpty()) {
            $test_list = '<div class="alert alert-dismissible alert-info">' . trans('subject_management/manage_test.no_test_created', ['class' => AcademicLevel::getClassNameByCode($subject->classes_class_code), 'year' => Setting::getAcademicYear(), 'sequence' => $sequence->sequence_name, 'subject' => $subject->subject_title]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
        } else {
            $test_list = View::make('subject_management.test_list', compact('subjectTests',  'subject', 'sequenceSubjectTests', 'sequence'));
        }

        return view('subject_management.manage_test', compact('test_list', 'subject'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createTest(Request $request)
    {
        $this->validate($request, ['test-name' => 'required', 'test-weight' => 'required', 'test-code' => 'required']);

        $data = $request->all();
        $subjectId = Encrypter::decrypt(Session::get('test_functionality_subject_id'));
        $teacherId = Auth::user()->user_id;
        $sectionCode = Auth::user()->sections_section_code;
        $sequence = Setting::getSequence();
        $academicYear = Setting::getAcademicYear();

        if (empty($subjectId)) {
            Redirect::to(trans('settings/routes.manage_subject_test'));
        }


        //cancel operation if mark submission date line has passed or the marks have already been submitted for this current sequence
        if (Setting::hasPublishDatePass($sectionCode) || Setting::hasPublishDatePass($sectionCode) || (Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.marks_submitted') || Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.general_marks_submitted'))) {
            Redirect::to(trans('settings/routes.manage_subject_test') . '/' . Encrypter::encrypt($subjectId));

        }
        if (TestManager::testCodeExist($data['test-code'], $sequence->sequence_id, $academicYear, $subjectId, $teacherId)) {
            $test_exist = '<div class="alert alert-dismissible alert-warning">' . trans('subject_management/manage_test.test_exist', ['testCode' => $data['test-code']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
            return \redirect()->back()->with(['status' => $test_exist]);
        }

        TestManager::create([
            trans('database/table.test_name') => $data['test-name'],
            trans('database/table.test_weight') => $data['test-weight'],
            trans('database/table.test_code') => $data['test-code'],
            trans('database/table.subjects_subject_id') => $subjectId,
            trans('database/table.academic_year') => $academicYear,
            trans('database/table.sequences_sequence_id') => $sequence->sequence_id,
            trans('database/table.users_user_id') => $teacherId,
        ]);

        $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('subject_management/manage_test.success_alert', ['testCode' => $data['test-code']]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
        return redirect()->back()->with(['status' => $success_alert]);
    }

    /**
     * @param $tid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMarkEntryPage($tid)
    {
        $testId = Encrypter::decrypt($tid);
        $subjectId = Encrypter::decrypt(Session::get('test_functionality_subject_id'));
        if (empty($subjectId)) {
            Redirect::to(trans('settings/routes.manage_subject_test'));
        }

        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $teacherId = Auth::user()->user_id;
        $sectionCode = Auth::user()->sections_section_code;


        //cancel operation if mark submission date line has passed or the marks have already been submitted for this current sequence
        if (Setting::hasPublishDatePass($sectionCode) || (Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.marks_submitted') || Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.general_marks_submitted'))) {
            Redirect::to(trans('settings/routes.manage_subject_test') . '/' . Encrypter::encrypt($subjectId));

        }

        //if this test is one that belongs to this subject
        if (!TestManager::isSubjectTest($subjectId, $testId, $academicYear, $sequence->sequence_id, $teacherId)) {
            Redirect::to(trans('settings/routes.manage_subject_test') . '/' . Encrypter::encrypt($subjectId));
        }

        if (Session::has('test_id')) {
            Session::forget('test_id');
        }
        Session::put('test_id', $tid);
        $subject = Subject::find($subjectId);
        $test = TestManager::find($testId);

        if ($subject->programs_program_code == trans('settings/setting.al')) {

            // $subjectSeries = Subject::getSubjectSeriesCodesById($subjectId);
            $series = Subject::getSubjectSeriesCodesById($subjectId);
            $seriesCodes = $series->unique(trans('database/table.series_series_code'))->pluck(trans('database/table.series_series_code'))->toArray();

            if (empty($seriesCodes)) {
                $students = AcademicLevel::getStudentsByClassCode($subject->classes_class_code, $academicYear, $sectionCode);
                $studentIds = $students->unique(trans('database/table.student_id'))->pluck(trans('database/table.student_id'))->toArray();
                $testScores = TestManager::getStudentTestScoresByIds($studentIds, $testId);

                $mark_entry_list = View::make('subject_management.mark_entry_list', compact('students', 'subject', 'testScores', 'test'));
            } else {

                $students = Series::batchGetStudentsBySeriesCodes($seriesCodes, Setting::getAcademicYear(), Auth::user()->sections_section_code);
                $studentIds = $students->unique(trans('database/table.student_id'))->pluck(trans('database/table.student_id'))->sort()->toArray();
                $testScores = TestManager::getStudentTestScoresByIds($studentIds, $test->test_id);
                $seriesData = Series::batchGetStudentSeriesDataByCodes($seriesCodes, Setting::getAcademicYear(), Auth::user()->sections_section_code);
                $mark_entry_list = View::make('subject_management.series_mark_entry_list', compact('subject', 'test', 'seriesCodes', 'students', 'seriesData', 'testScores'));
            }
        } else {
            $students = AcademicLevel::getStudentsByClassCode($subject->classes_class_code, $academicYear, $sectionCode);
            $studentIds = $students->unique(trans('database/table.student_id'))->pluck(trans('database/table.student_id'))->toArray();
            $testScores = TestManager::getStudentTestScoresByIds($studentIds, $testId);
            $mark_entry_list = View::make('subject_management.mark_entry_list', compact('students', 'subject', 'testScores', 'test'));
        }
        return view('subject_management.mark_entry_general', compact('subject', 'test', 'mark_entry_list'));
    }

    /**
     * @param $tid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCSVMarkEntryPage($tid)
    {
        $testId = Encrypter::decrypt($tid);
        $subjectId = Encrypter::decrypt(Session::get('test_functionality_subject_id'));
        if (empty($subjectId)) {
            Redirect::to(trans('settings/routes.manage_subject_test'));
        }
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $teacherId = Auth::user()->user_id;
        $sectionCode = Auth::user()->sections_section_code;


        //if this test is one that belongs to this subject
        if (TestManager::isSubjectTest($subjectId, $testId, $academicYear, $sequence->sequence_id, $teacherId)) {
            Redirect::to(trans('settings/routes.manage_subject_test') . '/' . Encrypter::encrypt($subjectId));
        }

        //cancel operation if mark submission date line has passed or the marks have already been submitted for this current sequence
        if (Setting::hasPublishDatePass($sectionCode) || (Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.marks_submitted') || Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.general_marks_submitted'))) {
            Redirect::to(trans('settings/routes.manage_subject_test') . '/' . Encrypter::encrypt($subjectId));

        }
        Session::put('test_id', $tid);
        $subject = Subject::find($subjectId);
        $test = TestManager::find($testId);

        return view('subject_management.csv_mark_entry', compact('subject', 'test'));
    }

    /**
     * Download public exam
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generateStudentList()
    {
        try {
            $subjectId = Encrypter::decrypt(Session::get('test_functionality_subject_id'));
            $testId = Encrypter::decrypt(Session::get('test_id'));

            if (empty($subjectId) || empty($testId)) {
                Redirect::to(trans('settings/routes.manage_subject_test'));
            }

            $subject = Subject::find($subjectId);
            $test = TestManager::find($testId);
            $academicYear = Setting::getAcademicYear();
            $sectionCode = Auth::user()->sections_section_code;

            $fp = fopen(public_path('student_list/generate_list.csv'), 'a+');

            if ($fp == null) {
                return \redirect()->back();
            }


            $student_list_title = trans('subject_management/manage_test.student_list_title', ['year' => $academicYear, 'class' => AcademicLevel::getClassNameByCode($subject->classes_class_code), 'subject' => $subject->subject_title, 'test' => $test->test_name]);
            $csv_general_title = [0 => '', 1 => $student_list_title, 3 => ''];
            $csv_list_title = [0 => trans('subject_management/manage_test.matricule_f'), 1 => trans('subject_management/manage_test.name'), 3 => trans('subject_management/manage_test.marks')];
            $new_line = [0 => ' ', 1 => ' ', 2 => ' '];

            $students = Subject::getSubjectStudentsByModel($subject, $academicYear, $sectionCode);

            fputcsv($fp, $csv_general_title);
            fputcsv($fp, $csv_list_title);

            foreach ($students as $student) {
                $csv_mark_entry = [0 => $student->matricule, 1 => $student->full_name];
                fputcsv($fp, $csv_mark_entry);
            }

            fclose($fp);

            return response()->download(public_path('student_list/generate_list.csv'))->deleteFileAfterSend();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadCSVMarks(Request $request)
    {
        $this->validate($request, ['marks-field' => 'required']);

        if ($request->file('marks-field')->isValid()) {
            try {

                $file = $request->file('marks-field');
                if ($file->getClientOriginalExtension() != 'csv') {
                    $csv_validity_alert = '<div class="alert alert-dismissible alert-warning">' . trans('subject_management/manage_test.csv_validity_text') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                    return redirect()->back()->with(['status' => $csv_validity_alert]);
                }

                $student_marks = 'student_marks' . '_' . time() . '.' . $file->getClientOriginalExtension();
                $request->file('marks-field')->move(public_path('student_list'), $student_marks);//upload and move the file to the public directory

                //open the file for read operation
                $fp = fopen(public_path('student_list/' . $student_marks), 'r');
                if ($fp == null) {
                    $csv_exception = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/manage_test.csv_exception') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                    return redirect()->back()->with(['status' => $csv_exception]);
                }

                //the the students that offer this subject should incase the lecturer didnot use the template
                $subjectId = Encrypter::decrypt(Session::get('test_functionality_subject_id'));
                $testId = Encrypter::decrypt(Session::get('test_id'));
                if (empty($subjectId) || empty($testId)) {
                    Redirect::to(trans('settings/routes.manage_subject_test'));
                }

                $sectionCode = Auth::user()->sections_section_code;
                $academicYear = Setting::getAcademicYear();
                $sequence = Setting::getSequence();


                $subject = Subject::find($subjectId);
                $test = TestManager::find($subjectId);

                $students = Subject::getSubjectStudentsByModel($subject, $academicYear, $sectionCode);

                while (!feof($fp)) {
                    $row = fgetcsv($fp, 1000);


                    if (!empty($row[0]) && ($row[0] != trans('subject_management/manage_test.matricule_f'))) {
                        $matricule = trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0]));
                        $studentData = $students->where(trans('database/table.matricule'), $matricule)->first();

                        if (!empty($studentData)) {
                            if ($row[2] < 0) {
                                $row[2] = 0;
                            }
                            if ($row[2] <= $test->test_weight) {
                                TestManager::updateOrCreate($studentData->student_id, $testId, $sequence->sequence_id, $academicYear, $subjectId, $row[2]);
                            }
                        }
                    }
                }
                fclose($fp);
                //delete the file after read operation
                unlink(public_path('student_list/' . $student_marks));

                $marks_entered_success = '<div class="alert alert-dismissible alert-success">' . trans('subject_management/manage_test.mark_entry_success') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

                return Redirect::to(trans('settings/routes.manage_subject_test') . trans('settings/routes.mark_entry') . '/' . Encrypter::encrypt($testId))->with(['status' => $marks_entered_success]);


            } catch (\Exception $e) {
                $csv_exception = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/manage_test.csv_exception') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                 </button></div>';
                return redirect()->back()->with(['status' => $csv_exception]);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveMarks(Request $request)
    {
        try {
        $marks = $request->all();
        $subjectId = Encrypter::decrypt(Session::get('test_functionality_subject_id'));
        if (empty($subjectId)) {
            Redirect::to(trans('settings/routes.manage_subject_test'));
        }
        $teacherId = Auth::user()->user_id;
        $sectionCode = Auth::user()->sections_section_code;
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();

        //cancel operation if mark submission date line has passed or the marks have already been submitted for this current sequence
        if (Setting::hasPublishDatePass($sectionCode) || (Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.marks_submitted') || Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.general_marks_submitted'))) {
            Redirect::to(trans('settings/routes.manage_subject_test') . '/' . Encrypter::encrypt($subjectId));

        }
        $testId = Encrypter::decrypt(Session::get('test_id'));
        if (empty($marks) || empty($testId)) {
            return \redirect()->back();
        }
        $subject = Subject::find($subjectId);
        $test = TestManager::find($subjectId);

        $students = Subject::getSubjectStudentsByModel($subject, $academicYear, $sectionCode);


        foreach ($students as $student) {
            if (array_key_exists($student->matricule, $marks)) {
                if ($marks[$student->matricule] < 0) {
                    $marks[$student->matricule] = 0;
                }
                if ($marks[$student->matricule] <= $test->test_weight) {
                    TestManager::updateOrCreate($student->student_id, $testId, $sequence->sequence_id, $academicYear, $subjectId, $marks[$student->matricule]);
                }
            }
        }

        $marks_entered_success = '<div class="alert alert-dismissible alert-success">' . trans('subject_management/manage_test.marks_entered_success') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';


        return Redirect::to(trans('settings/routes.manage_subject_test') . trans('settings/routes.mark_entry') . '/' . Encrypter::encrypt($testId))->with(['status' => $marks_entered_success]);

        } catch (\Exception $e) {
             $marks_entered_failure = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/manage_test.marks_entered_failure') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                 </button></div>';

             return \redirect()->back()->with(['status' => $marks_entered_failure]);
         }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitMarks()
    {
        try {
            $subjectId = Encrypter::decrypt(Session::get('test_functionality_subject_id'));
            if (empty($subjectId)) {
                $marks_submission_error = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/manage_test.marks_submission_error') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';


                return \redirect()->back()->with(['status' => $marks_submission_error]);
            }
            $teacherId = Auth::user()->user_id;
            $sectionCode = Auth::user()->sections_section_code;
            $subject = Subject::find($subjectId);

            $academicYear = Setting::getAcademicYear();
            $sequence = Setting::getSequence();

            //cancel operation if mark submission date line has passed or the marks have already been submitted for this current sequence
            if (Setting::hasPublishDatePass($sectionCode) || (Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.marks_submitted') || Subject::getSubjectMarkEntryState($subjectId, $teacherId, $sequence->sequence_id, $academicYear) == trans('subject_management/manage_test.general_marks_submitted'))) {
                Redirect::to(trans('settings/routes.manage_subject_test') . '/' . Encrypter::encrypt($subjectId));

            }
            $sequenceSubjectTests = Subject::getSubjectTestBySequence($subjectId, $sequence->sequence_id, $teacherId, $academicYear);
            $testScores = Subject::getSubjectTestScoresBySequence($subjectId, $sequence->sequence_id, $teacherId, $academicYear);
            $students = Subject::getSubjectStudentsByModel($subject, $academicYear, $sectionCode);

            //sum all the students test marks,convert it to the subject's weight and  put in the subjects_has_scores table
            foreach ($students as $student) {
                $studentScores = $testScores->where(trans('database/table.students_student_id'), $student->student_id);
                $totalTestScore = 0;
                $totalTestWeight = 0;
                if ($studentScores->isNotEmpty()) {
                    foreach ($studentScores as $studentScore) {
                        $test = $sequenceSubjectTests->where(trans('database/table.test_id'), $studentScore->tests_test_id)->first();
                        if (!empty($test)) {
                            $totalTestScore += $studentScore->test_score;
                            $totalTestWeight += $test->test_weight;
                        }
                    }
                    if ($totalTestWeight == 0) {
                        $totalTestWeight = 1;
                    }
                    $totalMark = ($totalTestScore / $totalTestWeight) * $subject->subject_weight;
                    Subject::updateOrCreate($student->student_id, $sequence->sequence_id, $academicYear, $subjectId, $totalMark, $teacherId);

                }
            }

            $marks_submission_success = '<div class="alert alert-dismissible alert-success">' . trans('subject_management/manage_test.marks_submission_success') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
            return \redirect()->back()->with(['status' => $marks_submission_success]);
        } catch (\Exception $e) {
            $marks_submission_error = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/manage_test.marks_submission_error') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
            return \redirect()->back()->with(['status' => $marks_submission_error]);
        }
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showViewFinalResultPage()
    {
        $result_list = '';
        $teacherId = Auth::user()->user_id;
        $academicYear = Setting::getAcademicYear();

        //cancel operation if mark submission date line has passed or the marks have already been submitted for this current sequence
        if (Subject::hasMarkEntryByTeacherId($teacherId, $academicYear)) {
            Redirect::to(trans('settings/routes.result_list'));
        }
        return view('subject_management.subject_result', compact('result_list'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getFinalResult(Request $request)
    {
        $this->validate($request, ['subject-id' => 'required', 'sequence-id' => 'required']);
        $data = $request->all();

        $teacherId = Auth::user()->user_id;
        $sectionCode = Auth::user()->sections_section_code;
        $subject = Subject::find($data['subject-id']);
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();

        //cancel operation if mark submission date line has passed or the marks have already been submitted for this current sequence
        if (Subject::hasMarkEntryByTeacherId($teacherId, $academicYear)) {
            Redirect::to(trans('settings/routes.result_list'));
        }
        $subject = Subject::find($data['subject-id']);

        $students = Subject::getSubjectStudentsByModel($subject, $academicYear, $sectionCode);
        $results = Subject::getSubjectScoresBySequence($data['subject-id'], $data['sequence-id'], $teacherId, $academicYear);
        $result_list = View::make('subject_management.subject_result_table', compact('students', 'results', 'subject'));
        return view('subject_management.subject_result', compact('result_list'));
    }
}
