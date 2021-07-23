<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Program extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'program_id';


    /**
     * Get a program's name by code
     * @param $programCode
     * @return |null
     */
    public static function getCycleNameByCode($programCode)
    {

        try {
            if (Auth::check()) {
                $sectionCode = Auth::user()->sections_section_code;
            } else {
                $sectionCode = App::getLocale();
            }
            if($sectionCode == trans('database/table.bilingual')) {
                $program = self::where(trans('database/table.program_code'), $programCode)->first();
            }else{
                $program = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.program_code'), $programCode)->first();

            }
            return $program->program_name;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get a program's name by code
     * @param $programCode
     * @return |null
     */
    public static function getProgramCodeById($programId)
    {

        try {
            if (Auth::check()) {
                $sectionCode = Auth::user()->sections_section_code;
            } else {
                $sectionCode = App::getLocale();
            }
            if($sectionCode == trans('database/table.bilingual')) {
                $program = self::where(trans('database/table.program_id'), $programId)->first();
            }else{
                $program = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.program_id'), $programId)->first();

            }
            return $program->program_code;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the select list of all programs
     * for a particular section
     * @return string
     */
    public static function getProgramsList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $programs = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        foreach ($programs as $program) {
            $res .= '<option value="' . $program->program_code . '">' . $program->program_name . '</option>';
        }

        return $res;
    }

    /**
     * Get the select list of all programs
     * for a particular section
     * @return string
     */
    public static function getOverallProgramsList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $programs = self::get();
        foreach ($programs as $program) {
            $res .= '<option value="' . $program->program_code . '">' . $program->program_name . '</option>';
        }

        return $res;
    }

    public static function getProgramsListSecondCyle()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $programs = self::where(trans('database/table.sections_section_code'), $section_code)
            ->where(trans('database/table.program_code'), "al")
            ->get();
        foreach ($programs as $program) {
            $res .= '<option value="' . $program->program_code . '">' . $program->program_name . '</option>';
        }

        return $res;
    }
}
