<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Series extends Model
{

    protected $guarded = ['series_id'];

    protected $primaryKey = 'series_id';


    protected $table = 'series';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany('App\Subject', trans('database/table.series_has_students'), trans('database/table.series_series_code'), trans('database/table.matricule'))->withPivot([trans('database/table.sections_section_code'), trans('database/table.academic_year'), trans('database/table.classes_class_code')]);
    }

    /**
     * @param $data
     * @return bool
     */
    public static function batchStudentSeriesSave($data)
    {
        $res = DB::table(trans('database/table.series_has_students'))
            ->insert($data);
        return $res;
    }

    public static function batchSubjectSeriesSave($data)
    {
        $res = DB::table(trans('database/table.series_has_subjects'))
            ->insert($data);
        return $res;
    }

    public static function batchStudentSeriesUpdate($matricule,$data)
    {
        $res = DB::table(trans('database/table.series_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->update($data);
        return $res;
    }

    /**
     * @param $data
     * @return bool
     */
    public static function updateStudentSeriesSave($matricule, $seriesCode)
    {
        $res = DB::table(trans('database/table.series_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->update([trans('database/table.series_series_code') => $seriesCode]);
        return $res;
    }

    /**
     * @param $subjectId
     * @param $oldSeriesCode
     * @param $newSeriesCode
     * @return int
     */
    public static function updateSubjectSeriesSave($subjectCode, $oldSeriesCode, $newSeriesCode)
    {
        $res = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subjectCode)
            ->where(trans('database/table.series_series_code'), $oldSeriesCode)
            ->update([trans('database/table.series_series_code') => $newSeriesCode]);
        return $res;
    }

    /**
     * @param $sequenceId
     * @param $seriesFrom
     * @param $seriesTo
     * @param $studentId
     * @param $academicYear
     * @param $sectionCode
     * @return bool
     */
    public static function saveStudentSeriesChanges($sequenceId, $seriesFrom, $seriesTo, $studentId, $academicYear, $sectionCode)
    {
        $res = DB::table(trans('database/table.student_series_changes'))
            ->insert([
                trans('database/table.students_student_id') => $studentId,
                trans('database/table.sequences_sequence_id') => $sequenceId,
                trans('database/table.academic_year') => $academicYear,
                trans('database/table.series_code') => $seriesFrom,
                trans('database/table.series_series_code') => $seriesTo,
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => Auth::user()->user_id
            ]);

        return $res;
    }

    /**
     * @param $sequenceId
     * @param $seriesFrom
     * @param $seriesTo
     * @param $subjectId
     * @param $academicYear
     * @param $sectionCode
     * @return bool
     */
    public static function saveSubjectSeriesChanges($sequenceId, $seriesFrom, $seriesTo, $subjectCode, $academicYear, $sectionCode)
    {
        $res = DB::table(trans('database/table.subject_series_changes'))
            ->insert([
                trans('database/table.subjects_subject_code') => $subjectCode,
                trans('database/table.sequences_sequence_id') => $sequenceId,
                trans('database/table.academic_year') => $academicYear,
                trans('database/table.series_code') => $seriesFrom,
                trans('database/table.series_series_code') => $seriesTo,
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => Auth::user()->user_id
            ]);

        return $res;
    }

    /**
     * @param $seriesCode
     * @return |null
     */
    public static function getDBSeriesNameByCode($seriesCode)
    {
        $series = self::where(trans('database/table.series_code'), $seriesCode)
            ->first();

        if (empty($series)) {
            return null;
        }
        return $series->series_name;
    }

    /**
     * @return string
     */
    public static function getSeriesList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $res .= '<option value="s1">' . trans('settings/setting.s1') . '</option>';
        $res .= '<option value="s2">' . trans('settings/setting.s2') . '</option>';
        $res .= '<option value="s3">' . trans('settings/setting.s3') . '</option>';
        $res .= '<option value="s4">' . trans('settings/setting.s4') . '</option>';
        $res .= '<option value="s5">' . trans('settings/setting.s5') . '</option>';
        $res .= '<option value="s6">' . trans('settings/setting.s6') . '</option>';
        $res .= '<option value="s7">' . trans('settings/setting.s7') . '</option>';
        $res .= '<option value="s8">' . trans('settings/setting.s8') . '</option>';
        $res .= '<option value="a1">' . trans('settings/setting.a1') . '</option>';
        $res .= '<option value="a2">' . trans('settings/setting.a2') . '</option>';
        $res .= '<option value="a3">' . trans('settings/setting.a3') . '</option>';
        $res .= '<option value="a4">' . trans('settings/setting.a4') . '</option>';
        $res .= '<option value="a5">' . trans('settings/setting.a5') . '</option>';

        return $res;
    }


    /**
     * @param $seriesCode
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function getSeriesNameByCode($seriesCode)
    {
        $res = '';

        switch ($seriesCode) {
            case  's1':
                $res = trans('settings/setting.s1');
                break;
            case  's2':
                $res = trans('settings/setting.s2');
                break;
            case  's3':
                $res = trans('settings/setting.s3');
                break;
            case  's4':
                $res = trans('settings/setting.s4');
                break;
            case  's5':
                $res = trans('settings/setting.s5');
                break;
            case  's6':
                $res = trans('settings/setting.s6');
                break;
            case  's7':
                $res = trans('settings/setting.s7');
                break;
            case  's8':
                $res = trans('settings/setting.s8');
                break;
            case  'a1':
                $res = trans('settings/setting.a1');
                break;
            case  'a2':
                $res = trans('settings/setting.a2');
                break;
            case  'a3':
                $res = trans('settings/setting.a3');
                break;
            case  'a4':
                $res = trans('settings/setting.a4');
                break;
            case  'a5':
                $res = trans('settings/setting.a5');
                break;
            case  'a6':
                $res = trans('settings/setting.a6');
                break;
        }

        return $res;
    }

    /**
     * Get the select list of all roles
     * for a particular section
     * @return string
     */
    public static function getSeriesDBList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $seriess = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        foreach ($seriess as $series) {
            $res .= '<option value="' . $series->series_code . '">' . $series->series_name . '</option>';
        }

        return $res;
    }


    /**
     * @param $sectionCode
     * @param $seriesName
     * @return bool
     */
    public static function seriesNameExist($seriesName, $sectionCode)
    {
        $series = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.series_name'), $seriesName)->first();
        if (!empty($series)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $seriesCode
     * @return bool
     */
    public static function seriesCodeExist($seriesCode, $sectionCode)
    {
        $series = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.series_code'), $seriesCode)->first();
        if (!empty($series)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $seriesName
     * @return bool
     */
    public static function seriesNameExistById($seriesName, $sectionCode, $sid)
    {
        $series = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.series_id'), '!=', $sid)->where(trans('database/table.series_name'), $seriesName)->first();
        if (!empty($series)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sectionCode
     * @param $seriesCode
     * @return bool
     */
    public static function seriesCodeExistById($seriesCode, $sectionCode, $sid)
    {
        $series = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.series_id'), '!=', $sid)->where(trans('database/table.series_code'), $seriesCode)->first();
        if (!empty($series)) {
            return true;
        }
        return false;
    }


    /**
     * @param $matricule
     * @return bool
     */
    public static function checkStudentSeriesExistanceByMatricule($matricule)
    {
        $series = DB::table(trans('database/table.series_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->first();

        if (empty($series)) {
            return false;
        }
        return true;
    }


    /**
     * @param $subjectId
     * @return bool
     */
    public static function checkSubjectSeriesExistanceById($subjectCode, $seriesCode)
    {
        $series = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.series_series_code'), $seriesCode)
            ->where(trans('database/table.subjects_subject_code'), $subjectCode)
            ->first();

        if (empty($series)) {
            return false;
        }
        return true;
    }

    /**
     * @param $seriesCodes
     * @param $academicYear
     * @param $sectionCode
     * @return mixed
     */
    public static function batchGetStudentsBySeriesCodes($seriesCodes, $academicYear, $sectionCode)
    {
        $resource = DB::table(trans('database/table.series_has_students'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->whereIn(trans('database/table.series_series_code'), $seriesCodes)
            ->get();
        $matricules = $resource->unique(trans('database/table.matricule'))->pluck(trans('database/table.matricule'))->toArray();
        $students = Student::whereIn(trans('database/table.matricule'), $matricules)->get();
        return $students;
    }

    /**
     * @param $seriesCodes
     * @param $academicYear
     * @param $sectionCode
     * @return \Illuminate\Support\Collection
     */
    public static function batchGetStudentSeriesDataByCodes($seriesCodes, $academicYear, $sectionCode)
    {
        $resource = DB::table(trans('database/table.series_has_students'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->whereIn(trans('database/table.series_series_code'), $seriesCodes)
            ->get();
        return $resource;
    }

    public static function batchGetStudentsBySeriesCode($seriesCode, $academicYear, $sectionCode)
    {
        $resource = DB::table(trans('database/table.series_has_students'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.series_series_code'), $seriesCode)
            ->get();
        $matricules = $resource->unique('matricule')->pluck(trans('database/table.matricule'))->toArray();
        $students = Student::whereIn(trans('database/table.matricule'), $matricules)->get();
        return $students;
    }

    /*
    this check the existence of a series and subject in the subject table
    */

    public static function checkSeriesSubjectExistence($seriesCode,$subject_code)
    {
        if (DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subject_code)
            ->where(trans('database/table.series_series_code'),$seriesCode)
            ->exists()) {
            return true;
        }

        return false;

    }

    /*
    this check the existence of a series and subject in the series has subject table
    */

    public static function checkSeriesExistence($series_code, $subject_code)
    {
        if (DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subject_code)
            ->where(trans('database/table.series_series_code'), $series_code)
            ->exists())
            return true;

        return false;

    }

    public static function searchSeriesBySubjectCode($subject_code)
    {
        /*$subjectSectionCode = Auth::user()->sections_section_code;
        $res = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.subjects_subject_code'), $subject_code)
            ->where(trans('database/table.sections_section_code'), $subjectSectionCode)
            ->get();

        foreach ($res as $req){
            $series_name[] = Series::getSeriesNameBySeriesCode($req->series_series_code);
        }

        return $series_name;*/

    }

    public static function getSeriesListName($subject_id)
    {

        $section_code = Auth::user()->sections_section_code;
        $series = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.sections_section_code'), $section_code)
            ->where(trans('database/table.subjects_subject_code'), $subject_id)->get();

        return $series;
    }

    public static function getSeriesNameBySeriesCode($series_code)
    {
        $serie = DB::table(trans('database/table.series'))
            ->where(trans('database/table.series_code'), $series_code)
            ->first();
        if (empty($serie)) {
            return $serie;
        }

        return $serie->series_name;
    }


    /**
     * @param $seriesCode
     * @return bool
     */
    public static function checkSeriesDistributedExistance($seriesCode){
      $series = DB::table(trans('database/table.series_has_students'))
           ->where(trans('database/table.series_series_code'),$seriesCode)
           ->first();

      if(!empty($series)){
          return true;
      }
        $series = DB::table(trans('database/table.series_has_subjects'))
            ->where(trans('database/table.series_series_code'),$seriesCode)
            ->first();

        if(!empty($series)){
            return true;
        }

        return false;
    }
}

