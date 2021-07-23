<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TeacherHandler extends Model
{

    protected $guarded = 'id';

    protected $table = 'teacher_teaches_subject';


    /**
     * @param $academicYear
     * @param $teacherId
     * @return mixed
     */
    public static function getTeacherSubjectsPerAcademicYear($academicYear, $teacherId)
    {

        $data = self::where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->get();
        if (empty($data)) {
            return $data;
        }

        $subjectsIds = $data->pluck(trans('database/table.subjects_subject_id'))->toArray();
        $subjects = Subject::whereIn(trans('database/table.subject_id'), $subjectsIds)->get();
        return $subjects;
    }

    /**
     * @param $academicYear
     * @param $teacherId
     * @return mixed
     */
    public static function getTeacherSubjectsPerSection($academicYear, $teacherId,$sectionCode)
    {
        $data = self::where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->get();
        if (empty($data)) {
            return $data;
        }
        $subjectsIds = $data->pluck(trans('database/table.subjects_subject_id'))->toArray();
        $subjects = Subject::whereIn(trans('database/table.subject_id'), $subjectsIds)->where(trans('database/table.sections_section_code'), $sectionCode)->get();
        return $subjects;
    }

    /**
     * @param $academicYear
     * @param $teacherId
     * @param $userCode
     * @param $programCode
     * @return mixed
     */
    public static function getTeacherSubjectsPerClass($academicYear, $teacherId, $userCode, $programCode)
    {

        $data = self::where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->get();
        if (empty($data)) {
            return $data;
        }

        $subjectsIds = $data->pluck(trans('database/table.subjects_subject_id'))->toArray();
        $subjects = Subject::whereIn(trans('database/table.subject_id'), $subjectsIds)
            ->where(trans('database/table.programs_program_code'), $programCode)
            ->where(trans('database/table.useres_user_code'), $userCode)
            ->get();
        return $subjects;
    }

    /**
     * @param $teacherId
     * @param $subjectId
     * @param $academicYear
     * @return bool
     */
    public static function isNotTeacherAssignedSubject($teacherId, $subjectId, $academicYear)
    {
        $resource = self:: where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->where(trans('database/table.subjects_subject_id'), $subjectId)
            ->first();
        if (empty($resource)) {
            return true;
        }
        return false;
    }
}
