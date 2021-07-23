<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Term extends Model
{

    protected $guarded = ['term_id'];

    protected $primaryKey = 'term_id';

    /**
     * get a term model by id
     * @param $sid
     * @return mixed
     */
    public static function getTermById($tid)
    {
        $term = self::where(trans('database/table.term_id'), $tid)->first();

        return $term;
    }
    /**
     * get a term model by id
     * @param $sid
     * @return mixed
     */
    public static function getTermNameById($tid)
    {
        $term = self::where(trans('database/table.term_id'), $tid)->first();

        if(empty($term)){
            return null;
        }
        return $term->term_name;
    }

    /**
     * @param $sequenceId
     * @return |null
     */
    public static function getTermNameBySequenceId($sequenceId)
    {
        $sequence = Sequence::find($sequenceId);
        if(empty($sequence)){
            return null;
        }
        $term = self::where(trans('database/table.term_id'), $sequence->terms_term_id)->first();

        if(empty($term)){
            return null;
        }
        return $term->term_name;
    }

    /**
     * Get the select list of all roles
     * for a particular section
     * @return string
     */
    public static function getTermList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $terms = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        foreach ($terms as $term) {
            $res .= '<option value="' . $term->term_id . '">' . $term->term_name . '</option>';
        }

        return $res;
    }


    /**
     * @param $sectionCode
     * @param $termName
     * @return bool
     */
    public static function termNameExist($termName, $sectionCode)
    {
        $term = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.term_name'), $termName)->first();
        if (!empty($term)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $termCode
     * @return bool
     */
    public static function termCodeExist($termCode, $sectionCode)
    {
        $term = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.term_code'), $termCode)->first();
        if (!empty($term)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $termName
     * @return bool
     */
    public static function termNameExistById($termName, $sectionCode, $sid)
    {
        $term = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.term_id'), '!=', $sid)->where(trans('database/table.term_name'), $termName)->first();
        if (!empty($term)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $termCode
     * @return bool
     */
    public static function termCodeExistById($termCode, $sectionCode, $sid)
    {
        $term = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.term_id'), '!=', $sid)->where(trans('database/table.term_code'), $termCode)->first();
        if (!empty($term)) {
            return true;
        }
        return false;
    }

    /**
     * @param $termId
     * @param $classCode
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getClassTermAverages($termId,$classCode,$academicYear){
        $averages =DB::table(trans('database/table.term_averages'))
            ->where(trans('database/table.academic_year'),$academicYear)
            ->where(trans('database/table.terms_term_id'), $termId)
            ->where(trans('database/table.classes_class_code'),$classCode)
            ->where(trans('database/table.state'),1)
            ->get();

        return $averages;

    }

    /**
     *  State : 1 is for delete
     *  State : 2 is for edit
     *
     */
    public static function recordTermActions($state, $term_id)
    {
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $sectionCode = Auth::user()->sections_section_code;
        DB::table(trans('database/table.audit_manage_term_actions'))
            ->insert([
                trans('database/table.sequences_sequence_name') => $sequence->sequence_name,
                trans('database/table.terms_term_name') => $term_id,
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
    public static function getTermActionsForAuditing($state,$academicYear){
        $sectionCode = Auth::user()->sections_section_code;
        $audit = DB::table(trans('database/table.audit_manage_term_actions'))
            ->where(trans('database/table.sections_section_code'),$sectionCode)
            ->where(trans('database/table.academic_year'),$academicYear)
            ->where(trans('database/table.state'),$state)
            ->get();
        return $audit;
    }
}
