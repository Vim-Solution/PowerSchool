<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AcademicLevel extends Model
{

    protected $guarded = ['class_id'];
    /**
     * @var string
     */
    protected $primaryKey = 'class_id';
    /**
     * @var string
     */
    protected $table = 'classes';

    /**
     * get a classname by code
     * @param $classCode
     * @return Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public static function getClassNameByCode($classCode)
    {

        if (strcmp(trim($classCode),trans('general.university_code')) ==0) {
            return trans('general.university');
        } elseif ($classCode == trans('general.none')) {
            return trans('general.none');
        }

        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        $class = DB::table(trans('database/table.classes'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.class_code'), $classCode)
            ->first();

        if (empty($class)) {
            return null;
        }

        return $class->class_name;
    }

    /**
     * @param $cid
     * @return mixed|null
     */
    public static function getClassNameById($cid)
    {

        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        $class = DB::table(trans('database/table.classes'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.class_id'), $cid)
            ->first();
        if (empty($class)) {
            return null;
        }

        return $class->class_name;
    }

    /**
     * @param $cid
     * @return mixed|null
     */
    public static function getClassById($cid)
    {

        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        $class = DB::table(trans('database/table.classes'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.class_id'), $cid)
            ->first();
        if (empty($class)) {
            return null;
        }

        return $class;
    }

    /**
     * get a classname by code
     * @param $classCode
     * @return Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public static function getClassByCode($classCode)
    {
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        $class = DB::table(trans('database/table.classes'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.class_code'), $classCode)
            ->first();

        return $class;
    }

    /**
     * @return string
     */
    public static function getClassList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $classes = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        foreach ($classes as $class) {
            $res .= '<option value="' . $class->class_code . '">' . $class->class_name . '</option>';
        }

        return $res;
    }

    /**
     * @return string
     */
    public static function getPromotionClassList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $res .= '<option>' . trans('general.university') . '</option>';
        $res .= '<option>' . trans('general.none') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $classes = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        foreach ($classes as $class) {
            $res .= '<option value="' . $class->class_code . '">' . $class->class_name . '</option>';
        }

        return $res;
    }

    /**
     * @return string
     */
    public static function getEncodedClassList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $classes = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        foreach ($classes as $class) {
            $res .= '<option value="' . Encrypter::encrypt($class->class_code) . '">' . $class->class_name . '</option>';
        }

        return $res;
    }

    /**
     * @return string
     */
    public static function getSecondCycleClassList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $classes = self::where(trans('database/table.sections_section_code'), $section_code)->where(trans('database/table.programs_program_code'), trans('settings/setting.al'))->get();
        foreach ($classes as $class) {
            $res .= '<option value="' . $class->class_code . '">' . $class->class_name . '</option>';
        }

        return $res;
    }


    /**
     * @return string
     */
    public static function getFirstCycleClassList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $classes = self::where(trans('database/table.sections_section_code'), $section_code)->where(trans('database/table.programs_program_code'), trans('settings/setting.ol'))->get();
        foreach ($classes as $class) {
            $res .= '<option value="' . $class->class_code . '">' . $class->class_name . '</option>';
        }

        return $res;
    }


    /**
     * @param $data
     * @return bool
     */
    public static function batchStudentLevelSave($data)
    {
        $res = DB::table(trans('database/table.classes_has_students'))
            ->insert($data);
        return $res;
    }

    /**
     * @param $data
     * @return bool
     */
    public static function batchStudentLevelUpdate($matricule, $data)
    {
        $res = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->update($data);
        return $res;
    }


    /**
     * @param $classCode
     * @return |null
     */
    public static function getSequenceIdByCode($classCode)
    {
        $res = null;
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        $class = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.class_code'), $classCode)->first();
        if (!empty($class)) {
            $res = $class->class_id;
        }
        return $res;
    }

    /**
     * @param $sectionCode
     * @param $className
     * @return bool
     */
    public static function classNameExist($className, $sectionCode)
    {
        $class = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.class_name'), $className)->first();
        if (!empty($class)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $classCode
     * @return bool
     */
    public static function classCodeExist($classCode, $sectionCode)
    {
        $class = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.class_code'), $classCode)->first();
        if (!empty($class)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $className
     * @return bool
     */
    public static function classNameExistById($className, $sectionCode, $sid)
    {
        $class = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.class_id'), '!=', $sid)->where(trans('database/table.class_name'), $className)->first();
        if (!empty($class)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $classCode
     * @return bool
     */
    public static function classCodeExistById($classCode, $sectionCode, $sid)
    {
        $class = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.class_id'), '!=', $sid)->where(trans('database/table.class_code'), $classCode)->first();
        if (!empty($class)) {
            return true;
        }
        return false;
    }

    /**
     * @param $classCode
     * @param $academicYear
     * @param $sectionCode
     * @return mixed
     */
    public static function getStudentsByClassCode($classCode, $academicYear, $sectionCode)
    {
        $resource = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.classes_class_code'), $classCode)
            ->get();
        $maticules = $resource->unique(trans('database/table.matricule'))->pluck(trans('database/table.matricule'))->toArray();

        $students = Student::whereIn(trans('database/table.matricule'), $maticules)->get();

        return $students;
    }

    /**
     * @param $sectionCode
     * @return \Illuminate\Support\Collection
     */
    public static function getClassStudentsPivot($sectionCode)
    {
        $res = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->get();
        return $res;
    }

    /**
     * @param $matricule
     * @param $academicYear
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function promotionClass($matricule, $academicYear)
    {
        $class = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.matricule'), $matricule)->first();
        return $class;
    }


    /**
     * @param $matricule
     * @param $classCode
     * @param $academicYear
     * @param int $promotionState
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function updateOrCreate($matricule, $classCode, $academicYear,$promotionState=0)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $class = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($class)) {
            DB::table(trans('database/table.classes_has_students'))
                ->insert([trans('database/table.sections_section_code') => $sectionCode, trans('database/table.academic_year') => $academicYear,
                    trans('database/table.matricule') => $matricule, trans('database/table.classes_class_code') => $classCode,
                    trans('database/table.promotion_state') => $promotionState
                ]);
        } else {
            DB::table(trans('database/table.classes_has_students'))
                ->where(trans('database/table.sections_section_code'), $sectionCode)
                ->where(trans('database/table.academic_year'), $academicYear)
                ->where(trans('database/table.matricule'), $matricule)
                ->update([trans('database/table.classes_class_code') => $classCode, trans('database/table.promotion_state') => $promotionState]);
        }

        return $class;
    }

    /**
     * @param $sectionCode
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getSchoolClassStudentRecords($sectionCode, $academicYear)
    {
        $class_student_records = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->get();

        return $class_student_records;
    }

    /**
     * @param $data
     * @return bool
     */
    public static function batchSubjectLevelSave($data)
    {
        $res = DB::table(trans('database/table.classes_has_subjects'))
            ->insert($data);
        return $res;
    }

    public static function getClassListFromClassCode($class_code, $academic_year)
    {
        $section_code = Auth::user()->sections_section_code;
        $mat_table = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.sections_section_code'), $section_code)
            ->where(trans('database/table.academic_year'), $academic_year)
            ->where(trans('database/table.classes_class_code'), $class_code)
            ->get()->unique(trans('database/table.matricule'));

        return $mat_table;

    }

    public static function getAcademicYearList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $academic_years = DB::table(trans('database/table.academic_years'))->get();
        foreach ($academic_years as $academic_yr) {
            $res .= '<option value="' . $academic_yr->academic_year . '">' . $academic_yr->academic_year . '</option>';
        }

        return $res;
    }

    /*
    second cycle infos
    */

    public static function getClassListSecondCycle()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $classes = self::where(trans('database/table.sections_section_code'), $section_code)
            ->where(trans('database/table.programs_program_code'), "al")
            ->get();
        foreach ($classes as $class) {
            $res .= '<option value="' . $class->class_code . '">' . $class->class_name . '</option>';
        }

        return $res;
    }


    /**
     * @param $classCode
     * @return bool
     */
    public static function classExistanceAndDistribution($classCode)
    {

        $classlist = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.classes_class_code'), $classCode)
            ->first();

        if (!empty($classlist)) {
            return true;
        }

        $classlist = Subject::where(trans('database/table.classes_class_code'), $classCode)
            ->get();
        if ($classlist->isNotEmpty()) {
            return true;
        }

        $classes = self::where(trans('database/table.next_promotion_class'), $classCode)->get();

        if ($classes->isNotEmpty()) {
            return true;
        }

        return false;
    }


    /**
     *  State : 1 is for delete
     *  State : 2 is for edit
     */
    public static function recordClassActions($state, $className)
    {
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $sectionCode = Auth::user()->sections_section_code;
        DB::table(trans('database/table.audit_academic_level_actions'))
            ->insert([
                trans('database/table.sequences_sequence_name') => $sequence->sequence_name,
                trans('database/table.classes_class_name') => $className,
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
    public static function getClassActionsForAuditing($state,$academicYear){
        $sectionCode = Auth::user()->sections_section_code;
        $audit = DB::table(trans('database/table.audit_academic_level_actions'))
            ->where(trans('database/table.sections_section_code'),$sectionCode)
            ->where(trans('database/table.academic_year'),$academicYear)
            ->where(trans('database/table.state'),$state)
            ->get();
        return $audit;
    }

}
