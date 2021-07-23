<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SchoolSection extends Model
{
    /**
     * @var string
     */
    protected $primaryKey ='section_id';

    /**
     * @var string
     */
    protected $table = 'sections';

    /**
     * get a section name by code
     * @param $sectionCode
     * @return |null
     */
    public static function getSectionNameByCode($sectionCode){
        try{
            $section = self::where(trans('database/table.section_code'),$sectionCode)->get()->first();
            return $section->section_name;
        }catch (\Exception $e){
            return null;
        }
    }

    /**
     * Get the select list of all sections
     * for a particular section
     * @return string
     */
    public static function getSectionsList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $sections = self::where(trans('database/table.section_code'), $section_code)->get();
        foreach ($sections as $section) {
            $res .= '<option value="' . $section->section_code . '">' . $section->section_name . '</option>';
        }

        return $res;
    }
}
