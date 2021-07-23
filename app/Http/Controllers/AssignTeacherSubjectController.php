<?php

namespace App\Http\Controllers;

use App\AcademicLevel;
use App\Encrypter;
use App\Setting;
use App\Subject;
use App\TeacherHandler;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AssignTeacherSubjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_teacher_subject');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    public function showAssignSubjectPage()
    {
        return view('subject_management.assign_subject_page');
    }

    /**
     * @param $tId
     * @param $cCode
     * @param $state
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadSubjects($tId, $cCode, $state)
    {
        $teacherId = Encrypter::decrypt($tId);
        $classCode = Encrypter::decrypt($cCode);
        if ($state == 0) {
            $teacher = User::find($teacherId);
            $data = $this->getTeacherSubject($teacher, $classCode);
            return response()->json(['teacher_subject_list' => $data['subject_list'], 'class_subject_list' => $data['class_list'], 'teacher_name' => trans('subject_management/assign_subject.t_list_title', ['teacher' => $teacher->full_name])]);
        } elseif ($state == 1) {

            $data = $this->getClassSubjects($classCode, $teacherId);
            $className = AcademicLevel::getClassNameByCode($classCode);

            return response()->json(['class_subject_list' => $data['class_list'], 'teacher_subject_list' => $data['subject_list'], 'class_name' => trans('subject_management/assign_subject.list_title', ['class' => $className])]);
        } else {
            return response()->json([], 202);
        }
    }

    /**
     * @param $teacher
     * @param $classCode
     * @return mixed
     */
    private function getTeacherSubject($teacher, $classCode)
    {
        if (Session::has('teacher_a_id')) {
            Session::forget('teacher_a_id');
        }
        Session::put('teacher_a_id', $teacher->user_id);

        $academicYear = Setting::getAcademicYear();
        $sectionCode = Auth::user()->sections_section_code;
        $subjects = TeacherHandler::getTeacherSubjectsPerSection($academicYear, $teacher->user_id, $sectionCode);

        if ($classCode != 0) {
            $class_subjects = Subject::getClassSubjectsByCode($classCode, $sectionCode);
            if ($class_subjects->isNotEmpty()) {
                $subject_ids = $subjects->unique(trans('database/table.subject_id'))->pluck(trans('database/table.subject_id'))->toArray();
                if (!empty($subject_ids)) {
                    $class_subjects = $class_subjects->whereNotIn(trans('database/table.subject_id'), $subject_ids);
                }
            }
        } else {
            $class_subjects = [];
        }
        $subject_list = '';
        foreach ($subjects as $subject) {
            $subject_list .= '<div class="' . $subject->subject_id . '"><a  class="listview__item role_func" style="padding:1px 1px 1px 1px;margin: 0;" onclick="swapFunctionality(' . $subject->subject_id . ')" id ="' . $subject->subject_id . '">' .
                '<div class="listview__content">
                            <div class="listview__heading p-3">' . $subject->subject_title . '-' . (AcademicLevel::getClassNameByCode($subject->classes_class_code)) . '</div>' .
                '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;"></div>';

        }

        $class_list = '';
        foreach ($class_subjects as $sub) {
            $class_list .= '<div class="' . $sub->subject_id . '"><a  class="listview__item bg-light system_func" style="padding:1px 1px 1px 1px;margin: 0;" onclick="swapFunctionality(' . $sub->subject_id . ')" id ="' . $sub->subject_id . '">' .
                '<div class="listview__content">
                            <div class="listview__heading p-3">' . $sub->subject_title . '</div>' .
                '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;"></div>';

        }

        $list_container['subject_list'] = $subject_list;
        $list_container['class_list'] = $class_list;

        return $list_container;
    }

    /**
     * @param $classCode
     * @param $teacherId
     * @return mixed
     */
    private function getClassSubjects($classCode, $teacherId)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $subjects = Subject::getClassSubjectsByCode($classCode, $sectionCode);
        $academicYear = Setting::getAcademicYear();
        if ($teacherId != 0) {
            $teacher_subjects = TeacherHandler::getTeacherSubjectsPerSection($academicYear, $teacherId, $sectionCode);
            if ($teacher_subjects->isNotEmpty()) {
                $teacher_subject_ids = $teacher_subjects->unique(trans('database/table.subject_id'))->pluck(trans('database/table.subject_id'))->toArray();
                if (!empty($teacher_subject_ids)) {
                    $subjects = $subjects->whereNotIn(trans('database/table.subject_id'), $teacher_subject_ids);
                }
            }
        } else {
            $teacher_subjects = [];
        }
        $class_list = '';
        foreach ($subjects as $subject) {
            $class_list .= '<div class="' . $subject->subject_id . '"><a  class="listview__item bg-light system_func" style="padding:1px 1px 1px 1px;margin: 0;" onclick="swapFunctionality(' . $subject->subject_id . ')" id ="' . $subject->subject_id . '">' .
                '<div class="listview__content">
                            <div class="listview__heading p-3">' . $subject->subject_title . '</div>' .
                '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;"></div>';

        }

        $subject_list = '';
        foreach ($teacher_subjects as $sub) {
            $subject_list .= '<div class="' . $sub->subject_id . '"><a  class="listview__item role_func" style="padding:1px 1px 1px 1px;margin: 0;" onclick="swapFunctionality(' . $sub->subject_id . ')" id ="' . $sub->subject_id . '">' .
                '<div class="listview__content">
                            <div class="listview__heading p-3">' . $sub->subject_title . '-' . (AcademicLevel::getClassNameByCode($sub->classes_class_code)) . '</div>' .
                '</div><i class="form-group__bar"></i></a><hr style="padding: 1px 1px 1px 1px;margin: 0;"></div>';

        }

        $list_container['subject_list'] = $subject_list;
        $list_container['class_list'] = $class_list;
        return $list_container;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function assignSubjects(Request $request)
    {
        $this->validate($request, ['ids' => 'required']);
        if (Session::has('teacher_a_id')) {
            $teacherId = Session::get('teacher_a_id');
        } else {
            return redirect()->back();
        }
        $data = $request->all();
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;
        try {
            //remove all pre-existing privileges from this role
            if (!empty($data['ids'])) {
                $this->deleteOrCreateSubjectAssignment($teacherId, $data['ids'], $userId, $academicYear, $sequence->sequence_id, $sectionCode);
                return response()->json(['response' => 'success'], 200);
            }
            return response()->json(['response' => 'failure'], 202);
        } catch (Exception $e) {
            return response()->json(['response' => 'failure'], 202);
        }
    }

    /**
     * @param $teacherId
     * @param $subjectIds
     * @param $userId
     * @param $academicYear
     * @param $sequenceId
     * @param $sectionCode
     * @return bool
     */
    private function deleteOrCreateSubjectAssignment($teacherId, $subjectIds, $userId, $academicYear, $sequenceId, $sectionCode)
    {
        DB::table(trans('database/table.teacher_teaches_subject'))
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->delete();

        $resource = collect([]);
        foreach ($subjectIds as $subjectId) {
            $resource = $resource->push([
                trans('database/table.assignee_id') => $userId,
                trans('database/table.academic_year') => $academicYear,
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.sequences_sequence_id') => $sequenceId,
                trans('database/table.subjects_subject_id') => $subjectId,
                trans('database/table.users_user_id') => $teacherId
            ]);
        }

        $state = DB::table(trans('database/table.teacher_teaches_subject'))
            ->insert($resource->toArray());

        return $state;
    }
}
