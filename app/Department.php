<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Department extends Model
{

    protected $primaryKey = 'department_id';

    /**
     * @param $id
     * @return |null
     */
    public static function getDepartmentNameById($id)
    {
        try {
            $department = self::find($id);
            return $department->department_name;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the select list of all programs
     * for a particular section
     * @return string
     */
    public static function getDepartmentsList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $departments = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        foreach ($departments as $department) {
            $res .= '<option value="' . $department->department_id . '">' . $department->department_name . '</option>';
        }

        return $res;
    }
}
