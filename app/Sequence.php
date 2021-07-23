<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\AutH;
use Illuminate\Support\Facades\DB;

class Sequence extends Model
{

    protected $guarded = ['sequence_id'];


    protected $primaryKey = 'sequence_id';

    /**
     * get a sequence model by id
     * @param $sid
     * @return mixed
     */
    public static function getSequenceById($sid)
    {
        $sequence = self::where(trans('database/table.sequence_id'), $sid)->first();

        return $sequence;
    }


    /**
     * Get a sequence list
     * @return string
     */
    public static function getSequenceList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        if ($section_code == trans('database/table.bilingual')) {
            $sequences = self::get();
        } else {
            $sequences = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        }
        foreach ($sequences as $sequence) {
            $res .= '<option value="' . $sequence->sequence_code . '">' . $sequence->sequence_name . '</option>';
        }

        return $res;
    }

    /**
     * @param $sequenceCode
     * @return |null
     */
    public static function getSequenceIdByCode($sequenceCode)
    {
        $res = null;
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        if ($sectionCode == trans('database/table.bilingual')) {
            $sequence = self::where(trans('database/table.sequence_code'), $sequenceCode)->first();
        } else {
            $sequence = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.sequence_code'), $sequenceCode)->first();
        }
        if (!empty($sequence)) {
            $res = $sequence->sequence_id;
        }
        return $res;
    }

    /**
     * @param $sequenceCode
     * @return |null
     */
    public static function getSequenceNameById($sequenceId)
    {
        $res = null;
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        if ($sectionCode == trans('database/table.bilingual')) {
            $sequence = self::where(trans('database/table.sequence_id'), $sequenceId)->first();
        } else {
            $sequence = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.sequence_id'), $sequenceId)->first();
        }
        if (!empty($sequence)) {
            $res = $sequence->sequence_name;
        }
        return $res;
    }

    /**
     * @param $sectionCode
     * @param $sequenceName
     * @return bool
     */
    public static function sequenceNameExist($sequenceName, $sectionCode)
    {
        $sequence = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.sequence_name'), $sequenceName)->first();
        if (!empty($sequence)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $sequenceCode
     * @return bool
     */
    public static function sequenceCodeExist($sequenceCode, $sectionCode)
    {
        $sequence = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.sequence_code'), $sequenceCode)->first();
        if (!empty($sequence)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $sequenceName
     * @return bool
     */
    public static function sequenceNameExistById($sequenceName, $sectionCode, $sid)
    {
        $sequence = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.sequence_id'), '!=', $sid)->where(trans('database/table.sequence_name'), $sequenceName)->first();
        if (!empty($sequence)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $sequenceCode
     * @return bool
     */
    public static function sequenceCodeExistById($sequenceCode, $sectionCode, $sid)
    {
        $sequence = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.sequence_id'), '!=', $sid)->where(trans('database/table.sequence_code'), $sequenceCode)->first();
        if (!empty($sequence)) {
            return true;
        }
        return false;
    }

    /**
     * @param $teacherId
     * @param $academicYear
     * @return string
     */
    public static function getSequenceListByTeacherId($teacherId, $academicYear)
    {
        $sequence_intermediary = DB::table(trans('database/table.subjects_has_scores'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.users_user_id'), $teacherId)
            ->get();
        $sequenceIds = $sequence_intermediary->unique(trans('database/table.sequences_sequence_id'))->pluck(trans('database/table.sequences_sequence_id'))->toArray();
        $sequences = self::whereIn(trans('database/table.sequence_id'), $sequenceIds)->get();


        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';

        foreach ($sequences as $sequence) {
            $res .= '<option value="' . $sequence->sequence_id . '">' . $sequence->sequence_name . '</option>';
        }

        return $res;
    }


    /**
     * @param $sequenceId
     * @param $classCode
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getClassSequenceAverages($sequenceId, $classCode, $academicYear)
    {
        $averages = DB::table(trans('database/table.sequence_averages'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.classes_class_code'), $classCode)
            ->where(trans('database/table.state'),1)
            ->get();

        return $averages;

    }

    /**
     *  State : 1 is for delete
     *  State : 2 is for edit
     */

    /**
     * @param $state
     * @param $sequenceName
     * @return int
     */
    public static function recordSequenceActions($state, $sequenceName)
    {
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $sectionCode = Auth::user()->sections_section_code;
        DB::table(trans('database/table.audit_manage_sequence_actions'))
            ->insert([
                trans('database/table.sequences_sequence_name') => $sequence->sequence_name,
                trans('database/table.sequence_name') => $sequenceName,
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
    public static function getSequenceActionsForAuditing($state,$academicYear){
        $sectionCode = Auth::user()->sections_section_code;
        $audit = DB::table(trans('database/table.audit_manage_sequence_actions'))
            ->where(trans('database/table.sections_section_code'),$sectionCode)
            ->where(trans('database/table.academic_year'),$academicYear)
            ->where(trans('database/table.state'),$state)
            ->get();
        return $audit;
    }

}
