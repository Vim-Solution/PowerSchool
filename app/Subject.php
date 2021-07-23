<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Subject extends Model
{
    protected $guarded = ['subject_id'];
    protected $primaryKey = 'subject_id';

    /**
     * @param $subjectId
     * @return |null
     */
    public static function getSubjectSeriesListById($subjectId)
    {

        $subject = self::find($subjectId);
        $resource = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subject->subject_code)
            ->get();
        if ($resource->isEmpty()) {
            return null;
        }
        $series_codes = Series::whereIn(trans('database/table.series_code'), $resource->pluck(trans('database/table.series_series_code'))->toArray())->get();
        $list = '<ul>';
        foreach ($series_codes as $series_code) {
            $list .= '<li>' . $series_code->series_code . '</li>';
        }
        $list .= '</ul>';
        return $list;
    }

    /**
     * @param $subjectId
     * @return \Illuminate\Support\Collection
     */
    public static function getSubjectSeriesCodesById($subjectId)
    {

        $subject = Subject::find($subjectId);
        $resource = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subject->subject_code)
            ->get();
        return $resource;
    }

    /**
     * @param $subjectId
     * @return mixed
     */
    public static function getSubjectSeriesById($subjectId)
    {
        $subject = Subject::find($subjectId);

        $resource = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subject->subject_code)
            ->get();
        $series = Series::whereIn(trans('database/table.series_code'), $resource->pluck(trans('database/table.series_series_code'))->toArray())->get();

        return $series;
    }

    /**
     * @param $subjectId
     * @return mixed|null
     */
    public static function getSubjectSeriesCodeById($subjectId)
    {

        $subject = Subject::find($subjectId);
        $resource = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subject->subject_code)
            ->first();
        if (empty($resource)) {
            return null;
        }
        return $resource->series_series_code;
    }

    /**
     * @param $subjectName
     * @param $classCode
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function getSubjectByName($subjectName, $classCode)
    {
        $subject = DB::table(trans('database/table.subjects'))
            ->where(trans('database/table.subject_title'), $subjectName)
            ->where(trans('database/table.classes_class_code'), $classCode)
            ->first();

        return $subject;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tests()
    {
        return $this->hasMany('App\TestManager', trans('database/table.subjects_subject_id'));
    }

    /**
     * @param $subjectId
     * @param $sequenceId
     * @param $teacherId
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getSubjectTestBySequence($subjectId, $sequenceId, $teacherId, $academicYear)
    {
        $test_list = DB::table(trans('database/table.tests'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->get();
        return $test_list;
    }

    /**
     * @param $subjectId
     * @param $sequenceId
     * @param $teacherId
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     *
     */
    public static function getSubjectTestScoresBySequence($subjectId, $sequenceId, $teacherId, $academicYear)
    {
        $test_list = DB::table(trans('database/table.tests_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->get();
        return $test_list;
    }

    /**
     * @param $subjectId
     * @param $sequenceId
     * @param $teacherId
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getSubjectScoresBySequence($subjectId, $sequenceId, $teacherId, $academicYear)
    {
        $scores = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->get();

        return $scores;
    }

    /**
     * @param $subjectId
     * @param $sequenceId
     * @param $teacherId
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getSubjectTestBySequenceDifference($subjectId, $sequenceId, $teacherId, $academicYear)
    {
        $test_list = DB::table(trans('database/table.tests'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), '!=', $sequenceId)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->get();

        return $test_list;
    }

    /**
     * @param $subjectId
     * @param $teacherId
     * @param $sequenceId
     * @param $academicYear
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function getSubjectMarkEntryState($subjectId, $teacherId, $sequenceId, $academicYear)
    {

        /**
         * This order should not be tempered with
         */
        //check whether a teacher offering this subject has submitted all his student's result
        $teacher_submission = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->first();

        if (!empty($teacher_submission)) {
            if ($teacher_submission->submission_state == 1) {
                return trans('subject_management/manage_test.marks_submitted');
            } elseif ($teacher_submission->submission_state == 0) {
                return trans('subject_management/manage_test.marks_entered');
            }
        }
        //check whether the marks for atleast one test under a given course has been submitted
        $teacher_test_state = DB::table(trans('database/table.tests_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->first();

        if (!empty($teacher_test_state)) {
            return trans('subject_management/manage_test.marks_being_entered');
        }

        return trans('subject_management/manage_test.marks_not_entered');
    }

    /**
     * @param $teacherId
     * @param $sequenceId
     * @param $academicYear
     * @return bool
     */
    public static function hasMarkEntryByTeacherId($teacherId, $academicYear)
    {
        $teacher_submission = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->get();

        if ($teacher_submission->isNotEmpty()) {
            return true;
        }

        return false;
    }

    /**
     * @param $subject
     * @param $academicYear
     * @param $sectionCode
     * @return mixed
     */
    public static function getSubjectStudentsByModel($subject, $academicYear, $sectionCode)
    {

        if ($subject->programs_program_code == trans('settings/setting.al')) {
            // $subjectSeries = Subject::getSubjectSeriesCodesById($subjectId);
            $series = Subject::getSubjectSeriesCodesById($subject->subject_id);
            $seriesCodes = $series->unique(trans('database/table.series_series_code'))->pluck(trans('database/table.series_series_code'))->toArray();

            if (empty($seriesCodes)) {
                $students = AcademicLevel::getStudentsByClassCode($subject->classes_class_code, $academicYear, $sectionCode);
            } else {
                $students = Series::batchGetStudentsBySeriesCodes($seriesCodes, $academicYear, $sectionCode);
            }
        } else {
            $students = AcademicLevel::getStudentsByClassCode($subject->classes_class_code, $academicYear, $sectionCode);
        }

        return $students;
    }

    /**
     * @param $studentId
     * @param $sequenceId
     * @param $academicYear
     * @param $subjectId
     * @param $score
     * @param $teacherId
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function updateOrCreate($studentId, $sequenceId, $academicYear, $subjectId, $score, $teacherId)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $marks = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->where(trans('database/table.students_student_id'), $studentId)
            ->first();
        if (empty($marks)) {
            DB::table(trans('database/table.subjects_has_scores'))
                ->insert([trans('database/table.sections_section_code') => $sectionCode, trans('database/table.academic_year') => $academicYear,
                    trans('database/table.sequences_sequence_id') => $sequenceId, trans('database/table.subjects_subject_id') => $subjectId,
                    trans('database/table.students_student_id') => $studentId, trans('database/table.subject_score') => $score,
                    trans('database/table.users_user_id') => $teacherId, trans('database/table.submission_state') => 1
                ]);
        } else {
            DB::table(trans('database/table.subjects_has_scores'))
                ->where(trans('database/table.sections_section_code'), $sectionCode)
                ->where(trans('database/table.academic_year'), $academicYear)
                ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
                ->where(trans('database/table.subjects_subject_id'), $subjectId)
                ->where(trans('database/table.students_student_id'), $studentId)
                ->update([trans('database/table.subject_score') => $score, trans('database/table.users_user_id') => $teacherId,
                    trans('database/table.submission_state') => 1]);

        }
        return $marks;
    }


    /**
     * @param $teacherId
     * @param $sequenceId
     * @param $academicYear
     * @return string
     */
    public static function getSubjectListByTeacherId($teacherId, $academicYear)
    {
        $subject_intermediary = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->get();
        $subjectIds = $subject_intermediary->unique(trans('database/table.subjects_subject_id'))->pluck(trans('database/table.subjects_subject_id'))->toArray();

        $subjects = self::whereIn(trans('database/table.subject_id'), $subjectIds)->get();


        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';

        foreach ($subjects as $subject) {
            $res .= '<option value="' . $subject->subject_id . '">' . $subject->subject_title . '-' . AcademicLevel::getClassNameByCode($subject->classes_class_code) . '</option>';
        }

        return $res;
    }

    /**
     * @param $subjectId
     * @param $sequenceId
     * @param $teacherId
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getSubjectScores($sequenceId, $academicYear)
    {
        $scores = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->get();

        return $scores;
    }

    /**
     * @param $sequenceId
     * @return bool
     */
    public static function sequenceScoresExistance($sequenceId)
    {
        $scores = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->first();

        if (empty($scores)) {
            return false;
        }
        return true;
    }

    /**
     * @param $sequenceId
     * @param $studentId
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getFinalMarks($sequenceId, $studentId, $academicYear)
    {
        $scores = DB::table(trans('database/table.final_marks'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.students_student_id'), $studentId)
            ->get();

        return $scores;
    }

    /**
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getAllFinalMarks($academicYear){

        $scores = DB::table(trans('database/table.final_marks'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->get();
        return $scores;
    }


    /**
     * @param $sequenceId
     * @param $academicYear
     * @return mixed
     */
    public static function getSubjectsNotSubmitted($sequenceId, $academicYear)
    {
        $subjects = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->get();
        $subjectsIds = $subjects->unique(trans('database/table.subjects_subject_id'))->pluck(trans('database/table.subjects_subject_id'))->toArray();
        $subjects_not_submitted = Subject::whereNotIn(trans('database/table.subject_id'), $subjectsIds)->get();

        return $subjects_not_submitted;
    }


    /**
     * @param $sequenceId
     * @param $academicYear
     * @return mixed
     */
    public static function getSubjectWithFullMarksNotSubmitted($sequenceId, $academicYear)
    {
        $subjects = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->get();

        $subjectIds = $subjects->unique(trans('database/table.subjects_subject_id'))->pluck(trans('database/table.subjects_subject_id'))->toArray();
        $subjects_not_fully_submitted = [];

        foreach ($subjectIds as $subjectId) {
            $teachers = self::getSubjectTeachers($subjectId, $academicYear);
            $total_submitted = $subjects->where(trans('database/table.subjects_subject_id'), $subjectId)->count();
            $total_exist = $teachers->count();

            if ($total_submitted < $total_exist) {
                $subjects_not_fully_submitted[] = $subjectId;
            }
        }

        $subjects_n_s = Subject::whereIn(trans('database/table.subject_id'), $subjects_not_fully_submitted)->get();

        return $subjects_n_s;
    }


    /**
     * @param $subjectId
     * @param $academicYear
     * @return mixed
     */
    public static function getSubjectTeachers($subjectId, $academicYear)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $subjects_has_teachers = DB::table(trans('database/table.teacher_teaches_subject'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->get();
        $teachersIds = $subjects_has_teachers->pluck(trans('database/table.users_user_id'))->toArray();
        $teachers = User::whereIn(trans('database/table.user_id'), $teachersIds)->where(trans('database/table.academic_state'), 1)->get();

        return $teachers;
    }


    /**
     * @param $subjectId
     * @param $academicYear
     * @return |null
     */
    public static function getSubjectTeachersList($subjectId, $academicYear)
    {
        $sectionCode = Auth::user()->sections_section_code;

        $subjects_has_teachers = DB::table(trans('database/table.teacher_teaches_subject'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->get();
        $teachersIds = $subjects_has_teachers->pluck(trans('database/table.users_user_id'))->toArray();
        $teachers = User::whereIn(trans('database/table.user_id'), $teachersIds)->where(trans('database/table.academic_state'), 1)->get();

        if (empty($teachers)) {
            return null;
        }
        $res = '<ul>';
        foreach ($teachers as $teacher) {

            $res .= '<li>' . $teacher->full_name . '</li>';
        }
        $res .= '</ul>';
        return $res;
    }

    /**
     * @param $sequenceId
     * @param $academicYear
     * @return mixed
     */
    public static function getTeachersWithMarksNotSubmitted($subjectId, $sequenceId, $academicYear)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $subjects_has_scores = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->get();
        $teacherIds = $subjects_has_scores->unique(trans('database/table.users_user_id'))->pluck(trans('database/table.users_user_id'))->toArray();

        $subjectTeachers = self::getSubjectTeachers($subjectId, $academicYear);
        $n_s_teachers = $subjectTeachers->whereNotIn(trans('database/table.user_id'), $teacherIds);

        return $n_s_teachers;
    }

    /**
     * @param $subjectId
     * @param $sequenceId
     * @param $academicYear
     * @return string|null
     */
    public static function getTeachersListWithMarksNotSubmitted($subjectId, $sequenceId, $academicYear)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $shs = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->get();
        $teacherIds = $shs->unique(trans('database/table.users_user_id'))->pluck(trans('database/table.users_user_id'))->toArray();

        $subjectTeachers = self::getSubjectTeachers($subjectId, $academicYear);
        $n_s_teachers = $subjectTeachers->whereNotIn(trans('database/table.user_id'), $teacherIds);

        if (empty($n_s_teachers)) {
            return null;
        }
        $res = '<ul>';
        foreach ($n_s_teachers as $teacher) {

            $res .= '<li>' . $teacher->full_name . '</li>';
        }
        $res .= '</ul>';

        return $res;
    }

    /**
     * @param $studentId
     * @param $sequenceId
     * @param $academicYear
     * @param $subjectId
     * @param $score
     * @return int
     */
    public static function updateOrCreateFinalMarks($studentId, $sequenceId, $academicYear, $subjectId, $finalMark)
    {
        $sectionCode = Auth::user()->sections_section_code;
        DB::table(trans('database/table.final_marks'))
            ->updateOrInsert([trans('database/table.sections_section_code') => $sectionCode, trans('database/table.academic_year') => $academicYear, trans('database/table.sequences_sequence_id') => $sequenceId, trans('database/table.subjects_subject_id') => $subjectId, trans('database/table.students_student_id') => $studentId],
                [trans('database/table.subject_score') => $finalMark]
            );
        return 0;
    }

    /**
     * @param $classCode
     * @param $sectionCode
     * @return mixed
     */
    public static function getClassSubjectsByCode($classCode, $sectionCode)
    {
        if ($sectionCode == trans('database/table.bilingual')) {
            $subjects = self::where(trans('database/table.classes_class_code'), $classCode)->get();
        } else {
            $subjects = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.classes_class_code'), $classCode)->get();
        }
        return $subjects;
    }


    public function teachers()
    {
        $this->belongsToMany('App\User', trans('database/table.teacher_teaches_subject'), trans('database/table.subjects_subject_id'), trans('database/table.users_user_id'));
    }


    /**
     * Get all subject with subject_title $subject_title
     * @param $subject_title
     * @return \Illuminate\Support\Collection
     */
    public static function getSubjectBySubjectsTitle($subject_title)
    {
        $res = DB::table(trans('database/table.subjects'))
            ->where(trans('database/table.subject_title'), $subject_title)
            ->get();

        return $res;
    }

    /**
     * Get a subject with subject_title $subject_title
     * @param $subject_title
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */

    public static function getSubjectBySubjectTitle($subject_title)
    {
        $res = DB::table(trans('database/table.subjects'))
            ->where(trans('database/table.subject_title'), $subject_title)
            ->first();

        return $res;
    }


    /**
     * @param $subject_code
     * @return Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public static function getSubjectClassNameBySubject_code($subject_code)
    {
        $academic_year = Setting::getAcademicYear();
        $res = DB::table(trans('database/table.classes_has_subjects'))
            ->where(trans('database/table.subject_code'), $subject_code)
            ->where(trans('database/table.academic_year'), $academic_year)
            ->where(trans('database/table.subject_code'), $subject_code)
            ->first();
        if (empty($res)) {
            return $res;
        }

        $subjectClassName = AcademicLevel::getClassNameByCode($res->classes_class_code);

        return $subjectClassName;
    }

    /* Get list of subject per class from subject table to display all necessary information */
    public static function getClassSubjects($class_code, $academicyear)
    {
        $subjectSectionCode = Auth::user()->sections_section_code;
        $subject_list = self::where(trans('database/table.sections_section_code'), $subjectSectionCode)
            ->where(trans('database/table.academic_year'), $academicyear)
            ->where(trans('database/table.classes_class_code'), $class_code)
            ->get();

        return $subject_list;
    }

    /**
     * @param $subject_code
     * @return mixed
     */
    public static function getSubjectBySubject_code($subject_code)
    {
        $subject = self::where(trans('database/table.subject_code'), $subject_code)->first();
        return $subject;
    }

    /**
     * @param $data
     * @return bool
     */
    public static function batchSubjectSave($data)
    {
        $res = DB::table(trans('database/table.subjects'))
            ->insert($data);

        return $res;
    }

    public static function subjectExist($subject_code)
    {
        if (DB::table(trans('database/table.subjects'))->where(trans('database/table.subject_code'), $subject_code)->exists()) {
            return true;
        }

        return false;
    }


    /**
     * @param $subject_code
     * @param $data
     * @return int
     */

    public static function massUpdateSubjectInfoBySubjectCode($subject_code, $data)
    {
        $subjectList = collect([]);
        $seriesSubjectList = collect([]);
        if (!empty($data['series-code'])) {
            foreach ($data['series-code'] as $serie) {
                if (Series::checkSeriesSubjectExistence($serie, $data['subject-code'])) {
                    $record = DB::table(trans('database/table.subjects'))->where(trans('database/table.subject_code'), $subject_code)
                        ->update([
                            trans('database/table.subject_title') => $data['subject-title'],
                            trans('database/table.state') => $data['state'],
                            trans('database/table.coefficient') => $data['coefficient'],
                            trans('database/table.classes_class_code') => $data['class-code'],
                            trans('database/table.subject_weight') => $data['subject-weight'],
                            trans('database/table.academic_year') => $data['academic-year'],
                            trans('database/table.programs_program_code') => $data['program'],
                            trans('database/table.sections_section_code') => Auth::user()->sections_section_code,
                            trans('database/table.departments_department_id') => Auth::user()->departments_department_id,
                            trans('database/table.users_user_id') => Auth::user()->user_id,
                        ]);

                    $subjectClassList = DB::table(trans('database/table.classes_has_subjects'))->where(trans('database/table.subjects_subject_code'), $subject_code)
                        ->update([
                            trans('database/table.classes_class_code') => $data['class-code'],
                            trans('database/table.academic_year') => $data['academic-year'],
                            trans('database/table.sections_section_code') => Auth::user()->sections_section_code,
                            trans('database/table.users_user_id') => Auth::user()->user_id,
                        ]);

                    $seriesSubjectList = DB::table(trans('database/table.series_has_subjects'))->where(trans('database/table.subjects_subject_code'), $subject_code)
                        ->update([
                            trans('database/table.classes_class_code') => $data['class-code'],
                            trans('database/table.series_series_code') => $serie,
                            trans('database/table.academic_year') => $data['academic-year'],
                            trans('database/table.sections_section_code') => Auth::user()->sections_section_code,
                            trans('database/table.users_user_id') => Auth::user()->user_id,
                        ]);
                } elseif (!Series::checkSeriesSubjectExistence($serie, $data['subject-code'])) {

                    Subject::create([
                        trans('database/table.subject_title') => $data['subject-title'],
                        trans('database/table.subject_code') => $data['subject-code'],
                        trans('database/table.state') => $data['state'],
                        trans('database/table.coefficient') => $data['coefficient'],
                        trans('database/table.classes_class_code') => $data['class-code'],
                        trans('database/table.subject_weight') => $data['subject-weight'],
                        trans('database/table.academic_year') => $data['academic-year'],
                        trans('database/table.programs_program_code') => $data['program'],
                        trans('database/table.sections_section_code') => Auth::user()->sections_section_code,
                        trans('database/table.departments_department_id') => Auth::user()->departments_department_id,
                        trans('database/table.users_user_id') => Auth::user()->user_id,
                    ]);

                    $subjectClassList = DB::table(trans('database/table.classes_has_subjects'))->where(trans('database/table.subjects_subject_code'), $subject_code)
                        ->update([
                            trans('database/table.classes_class_code') => $data['class-code'],
                            trans('database/table.academic_year') => $data['academic-year'],
                            trans('database/table.sections_section_code') => Auth::user()->sections_section_code,
                            trans('database/table.users_user_id') => Auth::user()->user_id,
                        ]);

                    DB::table(trans('database/table.series_has_subjects'))->insert([
                        trans('database/table.subjects_subject_code') => $data['subject-code'],
                        trans('database/table.classes_class_code') => $data['class-code'],
                        trans('database/table.series_series_code') => $serie,
                        trans('database/table.academic_year') => $data['academic-year'],
                        trans('database/table.sections_section_code') => Auth::user()->sections_section_code,
                        trans('database/table.users_user_id') => Auth::user()->user_id,
                    ]);
                }
            }
        } else {
            $record = DB::table(trans('database/table.subjects'))->where(trans('database/table.subject_code'), $subject_code)
                ->update([
                    trans('database/table.subject_title') => $data['subject-title'],
                    trans('database/table.state') => $data['state'],
                    trans('database/table.coefficient') => $data['coefficient'],
                    trans('database/table.classes_class_code') => $data['class-code'],
                    trans('database/table.subject_weight') => $data['subject-weight'],
                    trans('database/table.academic_year') => $data['academic-year'],
                    trans('database/table.programs_program_code') => $data['program'],
                    trans('database/table.sections_section_code') => Auth::user()->sections_section_code,
                    trans('database/table.departments_department_id') => Auth::user()->departments_department_id,
                    trans('database/table.users_user_id') => Auth::user()->user_id,
                ]);

            $subjectClassList = DB::table(trans('database/table.classes_has_subjects'))->where(trans('database/table.subjects_subject_code'), $subject_code)
                ->update([
                    trans('database/table.classes_class_code') => $data['class-code'],
                    trans('database/table.academic_year') => $data['academic-year'],
                    trans('database/table.sections_section_code') => Auth::user()->sections_section_code,
                    trans('database/table.users_user_id') => Auth::user()->user_id,
                ]);
        }

        return $record;
    }

    /**
     * @param $subject_code
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function searchSubjectBySubjectCode($subject_code)
    {
        $subjectSectionCode = Auth::user()->sections_section_code;
        $res = DB::table(trans('database/table.subjects'))
            ->where(trans('database/table.sections_section_code'), $subjectSectionCode)
            ->where(trans('database/table.subject_code'), $subject_code)
            ->first();
        return $res;
    }

    /**
     *  State : 1 is for delete
     *  State : 2 is for edit
     *
     */
    /**
     * @param $state
     * @param $subjectName
     * @return int
     */
    public static function recordSubjectActions($state, $subjectName)
    {
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $sectionCode = Auth::user()->sections_section_code;
        DB::table(trans('database/table.audit_subject_actions'))
            ->insert([
                trans('database/table.sequences_sequence_name') => $sequence->sequence_name,
                trans('database/table.subjects_subject_name') => $subjectName,
                trans('database/table.users_user_id') => Auth::user()->user_id,
                trans('database/table.academic_year') => $academicYear,
                trans('database/table.state') => $state,
                trans('database/table.sections_section_code') => $sectionCode
            ]);
        return 0;

    }

    /**
     * Audit
     * @param $state
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getSubjectActionsForAuditing($state,$academicYear){
        $sectionCode = Auth::user()->sections_section_code;
        $audit = DB::table(trans('database/table.audit_subject_actions'))
            ->where(trans('database/table.sections_section_code'),$sectionCode)
            ->where(trans('database/table.academic_year'),$academicYear)
            ->where(trans('database/table.state'),$state)
            ->get();
        return $audit;
    }

    /**
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getSubjectSeriesChanges($academicYear){
        $sectionCode = Auth::user()->sections_section_code;
        $audit = DB::table(trans('database/table.subject_series_changes'))
            ->where(trans('database/table.sections_section_code'),$sectionCode)
            ->where(trans('database/table.academic_year'),$academicYear)
            ->get();
        return $audit;
    }

    /**
     * @param $subjectId
     * @return mixed|null
     */
    public static function getSubjectTitleById($subjectId){

        $subject = DB::table(trans('database/table.subjects'))
            ->where(trans('database/table.subject_id'),$subjectId)
            ->first();

        if(empty($subject)){
            return trans('general.subject_delete');
        }

        return $subject->subject_title;
    }
}
