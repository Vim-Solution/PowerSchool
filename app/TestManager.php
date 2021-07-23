<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestManager extends Model
{
    protected $guarded = ['test_id'];

    protected $primaryKey = 'test_id';

    protected $table = 'tests';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo('App\Subject');
    }

    /**
     * @param $testCode
     * @param $sequenceId
     * @param $academicYear
     * @param $subjectId
     * @param $teacherId
     * @return bool
     */
    public static function testCodeExist($testCode, $sequenceId, $academicYear, $subjectId, $teacherId)
    {
        $test = self::where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.test_code'), $testCode)
            ->first();

        if (empty($test)) {
            return false;
        }

        return true;
    }

    /**
     * @param $subjectId
     * @param $testId
     * @param $academicYear
     * @param $sequenceId
     * @param $teacherId
     * @return bool
     */
    public static function isSubjectTest($subjectId, $testId, $academicYear, $sequenceId, $teacherId)
    {
        $test = self::where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.test_id'), $testId)
            ->first();

        if (empty($test)) {
            return false;
        }

        return true;
    }

    /**
     * @param $testId
     * @param $academicYear
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function getTestMarkEntryStateByAcademicYear($testId, $academicYear)
    {
        $test_score = DB::table(trans('database/table.tests_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.tests_test_id'), $testId)
            ->first();

        if (empty($test_score)) {
            return trans('subject_management/manage_test.marks_not_entered');
        }
        return trans('subject_management/manage_test.marks_entered');
    }

    /**
     * @param $studentIds
     * @param $testId
     * @return \Illuminate\Support\Collection
     */
    public static function getStudentTestScoresByIds($studentIds, $testId)
    {
        $resource = DB::table(trans('database/table.tests_has_scores'))
            ->where(trans('database/table.tests_test_id'), $testId)
            ->whereIn(trans('database/table.students_student_id'), $studentIds)
            ->get();

        return $resource;

    }

    /**
     * @param $studentId
     * @param $testId
     * @param $sequenceId
     * @param $academicYear
     * @param $subjectId
     * @param $score
     * @return int
     */
    public static function updateOrCreate($studentId, $testId, $sequenceId, $academicYear, $subjectId, $score)
    {
        $teacherId = Auth::user()->user_id;
        $testScore = DB::table(trans('database/table.tests_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->where(trans('database/table.students_student_id'), $studentId)
            ->where(trans('database/table.tests_test_id'), $testId)
            ->first();
        if (empty($testScore)) {
            DB::table(trans('database/table.tests_has_scores'))
                ->insert([trans('database/table.academic_year') => $academicYear, trans('database/table.sequences_sequence_id') => $sequenceId,
                    trans('database/table.subjects_subject_id') => $subjectId, trans('database/table.students_student_id') => $studentId,
                    trans('database/table.tests_test_id') => $testId, trans('database/table.test_score') => $score,
                    trans('database/table.users_user_id') => $teacherId
                ]);
        } else {
            DB::table(trans('database/table.tests_has_scores'))
                ->where(trans('database/table.academic_year'), $academicYear)
                ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
                ->where(trans('database/table.subjects_subject_id'), $subjectId)
                ->where(trans('database/table.students_student_id'), $studentId)
                ->where(trans('database/table.tests_test_id'), $testId)
                ->update([trans('database/table.test_score') => $score, trans('database/table.users_user_id') => $teacherId]);
        }
        return 0;
    }
}
