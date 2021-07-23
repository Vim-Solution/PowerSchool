<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{

    /**
     * @var array
     */
    protected $guarded = ['setting_id'];

    /**
     * @var string
     */
    protected $primaryKey = 'setting_id';


    /**
     * Get the current sequence of the academic _year
     * @return string
     */
    public static function getSequenceName()
    {
        $res = null;
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        if ($sectionCode == trans('database/table.bilingual')) {
            $sectionCode = App::getLocale();
        }
        $setting = self::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        if ($setting->isNotEmpty()) {
            $res = Sequence::where(trans('database/table.sequence_id'), $setting->first()->sequences_sequence_id)->first();
            if (!empty($res)) {
                $res = $res->sequence_name;
            }
        }
        return $res;
    }

    /**
     * Get the name of the current term
     * @return |null
     */
    public static function getTermName()
    {
        $res = null;
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        if ($sectionCode == trans('database/table.bilingual')) {
            $sectionCode = App::getLocale();
        }

        $setting = self::where(trans('database/table.sections_section_code'), $sectionCode)->get();

        if ($setting->isNotEmpty()) {
            $sequence = Sequence::getSequenceById($setting->first()->sequences_sequence_id);
            if (!empty($sequence)) {
                $res = Term::where(trans('database/table.term_id'), $sequence->terms_term_id)->first();
                if (!empty($res)) {
                    $res = $res->term_name;
                }
            }
        }
        return $res;
    }

    /**
     * Get the current sequence of the academic _year
     * @return string
     */
    public static function getSequence()
    {
        $res = null;
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        $setting = self::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        if ($setting->isNotEmpty()) {
            $res = Sequence::where(trans('database/table.sequence_id'), $setting->first()->sequences_sequence_id)->first();
        }
        return $res;
    }

    /**
     * Get the name of the current term
     * @return |null
     */
    public static function getTerm()
    {
        $res = null;
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        $setting = self::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        if ($setting->isNotEmpty()) {
            $res = Term::where(trans('database/table.term_id'), $setting->first()->terms_term_id)->first();
        }
        return $res;
    }

    /**
     * get the current academic year
     * @return |null
     */
    public static function getAcademicYear()
    {
        $res = null;
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        if ($sectionCode == trans('database/table.bilingual')) {
            $sectionCode = App::getLocale();
        }
        $setting = self::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        if ($setting->isNotEmpty()) {
            $res = $setting->first()->academic_year;
        }
        return $res;
    }

    /**
     *
     * @return string
     */
    public static function getAcademicSettingTitle()
    {

        $academicYear = self::getAcademicYear();
        $seqenceName = self::getSequenceName();
        $termName = self::getTermName();

        return $seqenceName . ',' . $termName . ',' . $academicYear;
    }


    /**
     * Create matricule setting
     * @param $data
     * @return bool
     */
    public static function saveMatriculeSetting($data)
    {

        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;
        $academic_year = self::getAcademicYear();
        $res = DB::table(trans('database/table.matricule_settings'))
            ->insert([trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.programs_program_code') => $data['program_code'],
                trans('database/table.academic_year') => $academic_year,
                trans('database/table.matricule_initial') => $data['matricule-initial'],
                trans('database/table.users_user_id') => $userId
            ]);

        return $res;
    }


    /**
     * Update matricule initial
     * @param $data
     * @return int
     */
    public static function updateMatriculeSetting($data)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;
        $academic_year = self::getAcademicYear();
        $res = DB::table(trans('database/table.matricule_settings'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.programs_program_code'), $data['program_code'])
            ->where(trans('database/table.academic_year'), $academic_year)
            ->update([trans('database/table.matricule_initial') => $data['matricule-initial'], trans('database/table.users_user_id') => $userId]);

        return $res;

    }

    /**
     * Check if a matricule initial is already set for section and program in an academic year
     * @param $data
     * @return bool
     */
    public static function matriculeInitialExist($data)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $academic_year = self::getAcademicYear();
        $res = DB::table(trans('database/table.matricule_settings'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.programs_program_code'), $data['program_code'])
            ->where(trans('database/table.academic_year'), $academic_year)->first();

        if (empty($res)) {
            return false;
        }

        return true;
    }

    /**
     * Get Matricule initial
     * @param $programCode
     * @param $sectionCode
     * @param $academic_year
     * @return Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public static function getMatriculeInitialByCodes($programCode, $sectionCode, $academic_year)
    {

        $res = DB::table(trans('database/table.matricule_settings'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.programs_program_code'), $programCode)
            ->where(trans('database/table.academic_year'), $academic_year)->first();

        if (empty($res)) {
            return $res;
        }

        return $res->matricule_initial;
    }


    /**
     * @param $programCode
     * @param $sectionCode
     * @param $academic_year
     * @return string
     */
    public static function getMatriculeSetting($programCode, $sectionCode, $academic_year)
    {
        $matricule_initial = Setting::getMatriculeInitialByCodes($programCode, $sectionCode, $academic_year);
        $matricule_setup = $matricule_initial . substr(strtok($academic_year, '/'), -2) . strtoupper($programCode);

        return $matricule_setup;
    }

    /**
     * @param $sectionCode
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getMatriculeSettingBySectionCode($sectionCode, $academicYear)
    {

        if ($sectionCode == trans('database/table.bilingual')) {
            $matSettings = DB::table(trans('database/table.matricule_settings'))
                ->get();
        } else {
            $matSettings = DB::table(trans('database/table.matricule_settings'))
                ->where(trans('database/table.sections_section_code'), $sectionCode)
                ->get();
        }
        return $matSettings;
    }

    /**
     * check if a public exam exist for an academic year
     * @param $programCode
     * @param $sectionCode
     * @param $academic_year
     * @return bool
     */
    public static function checkPublicExamExistance($programCode, $sectionCode, $academic_year)
    {
        if ($sectionCode == trans('database/table.bilingual')) {
            $exam_setting = DB::table(trans('database/table.exam_settings'))
                ->where(trans('database/table.programs_program_code'), $programCode)
                ->where(trans('database/table.academic_year'), $academic_year)->first();
        } else {
            $exam_setting = DB::table(trans('database/table.exam_settings'))
                ->where(trans('database/table.sections_section_code'), $sectionCode)
                ->where(trans('database/table.programs_program_code'), $programCode)
                ->where(trans('database/table.academic_year'), $academic_year)->first();

        }
        if (empty($exam_setting)) {
            return false;
        }
        return true;
    }


    /**
     * Get the exam setting of a particular cycle and section in
     * a particular academic year
     * @param $programCode
     * @param $sectionCode
     * @param $academic_year
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function getPublicExamSetting($programCode, $sectionCode, $academic_year)
    {
        if ($sectionCode == trans('database/table.bilingual')) {
            $exam_setting = DB::table(trans('database/table.exam_settings'))
                ->where(trans('database/table.programs_program_code'), $programCode)
                ->where(trans('database/table.academic_year'), $academic_year)->first();

        } else {
            $exam_setting = DB::table(trans('database/table.exam_settings'))
                ->where(trans('database/table.sections_section_code'), $sectionCode)
                ->where(trans('database/table.programs_program_code'), $programCode)
                ->where(trans('database/table.academic_year'), $academic_year)->first();
        }
        return $exam_setting;
    }


    /**
     * @param $programCode
     * @param $academic_year
     * @return bool
     */
    public static function publicExamExist($programCode, $academic_year)
    {
        $exam_setting = DB::table(trans('database/table.exam_settings'))
            ->where(trans('database/table.programs_program_code'), $programCode)
            ->where(trans('database/table.academic_year'), $academic_year)->first();
        if (empty($exam_setting)) {
            return false;
        }
        return true;
    }

    /**
     * @param $programCode
     * @param $academic_year
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function getPublicExamSettingRecord($programCode, $academic_year)
    {
        $exam_setting = DB::table(trans('database/table.exam_settings'))
            ->where(trans('database/table.programs_program_code'), $programCode)
            ->where(trans('database/table.academic_year'), $academic_year)->first();

        return $exam_setting;
    }

    /**
     * Update an existing exam setting
     * @param $programCode
     * @param $sectionCode
     * @param $academic_year
     * @param $centerNo
     * @param $exam_file_path
     * @return int
     */
    public static function updatePublicExamSetting($programCode, $sectionCode, $academic_year, $centerNo, $exam_file_path)
    {
        $res = DB::table(trans('database/table.exam_settings'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.programs_program_code'), $programCode)
            ->where(trans('database/table.academic_year'), $academic_year)
            ->update([trans('database/table.center_no') => $centerNo, trans('database/table.exam_file_path') => $exam_file_path]);

        return $res;
    }

    /**
     *
     * @param $programCode
     * @param $sectionCode
     * @param $academic_year
     * @param $centerNo
     * @param $exam_file_path
     * @return bool
     */
    public static function createPublicExamSetting($programCode, $sectionCode, $academic_year, $centerNo, $exam_file_path)
    {
        $res = DB::table(trans('database/table.exam_settings'))
            ->insert([trans('database/table.sections_section_code') => $sectionCode,
                    trans('database/table.programs_program_code') => $programCode,
                    trans('database/table.academic_year') => $academic_year,
                    trans('database/table.center_no') => $centerNo,
                    trans('database/table.exam_file_path') => $exam_file_path]
            );

        return $res;
    }

    /**
     * @param $sectionCode
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getPublicExamSettingBySectionCode($sectionCode, $academicYear)
    {
        if ($sectionCode == trans('database/table.bilingual')) {
            $examSettings = DB::table(trans('database/table.exam_settings'))
                ->where(trans('database/table.academic_year'), $academicYear)
                ->get();
        } else {
            $examSettings = DB::table(trans('database/table.exam_settings'))
                ->where(trans('database/table.sections_section_code'), $sectionCode)
                ->where(trans('database/table.academic_year'), $academicYear)
                ->get();
        }
        return $examSettings;
    }

    /**
     * @param $sectionCode
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getAcademicSettingBySectionCode($sectionCode, $academicYear)
    {

        if ($sectionCode == trans('database/table.bilingual')) {
            $academicSettings = DB::table(trans('database/table.settings'))
                ->get();
        } else {
            $academicSettings = DB::table(trans('database/table.settings'))
                ->where(trans('database/table.sections_section_code'), $sectionCode)
                ->get();
        }
        return $academicSettings;
    }

    /**
     * check if a public exam exist for an academic year
     * @param $programCode
     * @param $sectionCode
     * @param $academic_year
     * @return bool
     */
    public static function checkAcademicSettingExistance($sectionCode)
    {
        $academic_setting = DB::table(trans('database/table.settings'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)->first();

        if (empty($academic_setting)) {
            return false;
        }
        return true;
    }

    /**
     * @param $sectionCode
     * @param $academic_year
     * @param $sequeneId
     * @param $mark_submission_date
     * @return int
     */
    public static function updateAcademicSetting($sectionCode, $academic_year, $sequeneId, $mark_submission_date)
    {
        if ($sectionCode == trans('database/table.bilingual')) {
            $sectionCode == App::getLocale();
        }
        $res = DB::table(trans('database/table.settings'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->update([trans('database/table.sequences_sequence_id') => $sequeneId, trans('database/table.academic_year') => $academic_year, trans('database/table.publish_date') => $mark_submission_date]);

        return $res;
    }

    /**
     * @param $sectionCode
     * @param $academic_year
     * @param $sequenceId
     * @param $mark_submission_date
     * @return bool
     */
    public static function createAcademicSetting($sectionCode, $academic_year, $sequenceId, $mark_submission_date)
    {
        if ($sectionCode == trans('database/table.bilingual')) {
            $sectionCode == App::getLocale();
        }
        $res = DB::table(trans('database/table.settings'))
            ->insert([trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.academic_year') => $academic_year,
                trans('database/table.sequences_sequence_id') => $sequenceId,
                trans('database/table.publish_date') => $mark_submission_date
            ]);

        return $res;
    }

    /**
     * @param $eid
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function getPublicExamSettinById($eid)
    {
        $examSetting = DB::table(trans('database/table.exam_settings'))
            ->where(trans('database/table.id'), $eid)->first();

        return $examSetting;
    }


    /**
     * Get the select list of all academic years
     *
     * @return string
     */
    public static function getAcademicYearsList()
    {
        if (Auth::check()) {
            $sectionCode = Auth::user()->sections_section_code;
        } else {
            $sectionCode = App::getLocale();
        }
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $academic_years = DB::table(trans('database/table.academic_years'))->get();
        foreach ($academic_years as $academic_year) {
            $res .= '<option value="' . $academic_year->academic_year . '">' . $academic_year->academic_year . '</option>';
        }

        return $res;
    }

    /**
     * @return string
     */
    public static function getDefaultAcademicYearsList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $currentYear = 2017;//(date('Y') - 1);

        for ($i = 1; $i < 4; $i++) {
            $academic_year = $currentYear . '/' . (++$currentYear);
            $res .= '<option value="' . $academic_year . '">' . $academic_year . '</option>';
        }

        return $res;
    }


    /**
     * @return string
     */
    public static function getRegionList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $res .= '<option value="ad">' . trans('settings/setting.ad') . '</option>';
        $res .= '<option value="fn">' . trans('settings/setting.fn') . '</option>';
        $res .= '<option value="nt">' . trans('settings/setting.nt') . '</option>';
        $res .= '<option value="nw">' . trans('settings/setting.nw') . '</option>';
        $res .= '<option value="st">' . trans('settings/setting.st') . '</option>';
        $res .= '<option value="sw">' . trans('settings/setting.sw') . '</option>';
        $res .= '<option value="wt">' . trans('settings/setting.wt') . '</option>';
        $res .= '<option value="ce">' . trans('settings/setting.ce') . '</option>';
        $res .= '<option value="et">' . trans('settings/setting.et') . '</option>';
        $res .= '<option value="lt">' . trans('settings/setting.lt') . '</option>';

        return $res;
    }


    /**
     * @param $regCode
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function getRegionNameByCode($regCode)
    {

        $res = '';

        switch ($regCode) {
            case  'ad':
                $res = trans('settings/setting.ad');
                break;
            case  'fn':
                $res = trans('settings/setting.fn');
                break;
            case  'ce':
                $res = trans('settings/setting.ce');
                break;
            case  'et':
                $res = trans('settings/setting.et');
                break;
            case  'lt':
                $res = trans('settings/setting.lt');
                break;
            case  'nt':
                $res = trans('settings/setting.nt');
                break;
            case  'st':
                $res = trans('settings/setting.st');
                break;
            case  'sw':
                $res = trans('settings/setting.sw');
                break;
            case  'nw':
                $res = trans('settings/setting.nw');
                break;
            case  'wt':
                $res = trans('settings/setting.wt');
                break;
        }


        return $res;
    }

    /**
     * @param $regCode
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function getRegionCodeByName($regName)
    {

        $res = '';

        switch ($regName) {
            case  'Adamawa':
                $res = trans('settings/setting.Adamawa');
                break;
            case  'Far North':
                $res = trans('settings/setting.Far_North');
                break;
            case  'Center':
                $res = trans('settings/setting.Center');
                break;
            case  'East':
                $res = trans('settings/setting.East');
                break;
            case  'Litoral':
                $res = trans('settings/setting.Litoral');
                break;
            case  'North':
                $res = trans('settings/setting.North');
                break;
            case  'South':
                $res = trans('settings/setting.South');
                break;
            case  'South West':
                $res = trans('settings/setting.South_West');
                break;
            case  'North West':
                $res = trans('settings/setting.North_West');
                break;
            case  'West':
                $res = trans('settings/setting.West');
                break;
        }


        return $res;
    }

    /**
     * @param $sectionCode
     * @param $academicYear
     * @return bool
     */
    public static function academicYearExist($sectionCode, $academicYear)
    {
        $academic_year = DB::table(trans('database/table.academic_years'))
            //  ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)->first();

        if (empty($academic_year)) {
            return false;
        }

        return true;
    }


    /**
     * @param $sectionCode
     * @param $academicYear
     */
    public static function saveAcademicYear($sectionCode, $academicYear)
    {
        DB::table(trans('database/table.academic_years'))
            ->insert([/*trans('database/table.sections_section_code') => $sectionCode,*/ trans('database/table.academic_year') => $academicYear]);
    }

    /**
     * @param $sectionCode
     * @return bool
     */
    public static function hasPublishDatePass($sectionCode)
    {
        $setting = self::where(trans('database/table.sections_section_code'), $sectionCode)->first();

        if (empty($setting)) {
            return true;
        }

        if (strtotime(date('Y-m-d')) > strtotime($setting->publish_date)) {
            return true;
        }

        return false;
    }

    /**
     * @param $sectionCode
     * @return bool
     */
    public static function getPublishDate($sectionCode)
    {
        $setting = self::where(trans('database/table.sections_section_code'), $sectionCode)->first();

        if (empty($setting)) {
            return "Has not been set";
        }
        return $setting->publish_date;
    }


    /**
     * @param $text
     * @return string
     */
    public static function getAlertSuccess($text)
    {
        $res = '<div class="alert alert-dismissible alert-success">' . $text . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

        return $res;
    }

    /**
     * @param $text
     * @return string
     */
    public static function getAlertFailure($text)
    {
        $res = '<div class="alert alert-dismissible alert-danger">' . $text . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

        return $res;
    }

    /**
     * @param $text
     * @return string
     */
    public static function getAlertInfo($text)
    {
        $res = '<div class="alert alert-dismissible alert-info">' . $text . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

        return $res;
    }

    /**
     * @param $text
     * @return string
     */
    public static function getAlertWarning($text)
    {
        $res = '<div class="alert alert-dismissible alert-warning">' . $text . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

        return $res;
    }

    /**
     * @return string
     */
    public static function getResultTypeList()
    {

        if (trans('settings/setting.school_type') == trans('settings/setting.anglophone')) {
            $sectionCode = trans('general.english');
        } elseif (trans('settings/setting.school_type') == trans('settings/setting.francophone')) {
            $sectionCode = trans('general.fr');
        } else {
            $sectionCode = App::getLocale();
        }
        $terms = Term::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $sequences = Sequence::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $programList = Program::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        foreach ($terms as $term) {
            $seqeunceList = $sequences->where(trans('database/table.terms_term_id'), $term->term_id);
            foreach ($seqeunceList as $sequence) {
                $res .= '<option value="s-' . $sequence->sequence_id . '">' . $sequence->sequence_name . ' ' . trans('general.result') . '</option>';
            }
            $res .= '<option value="t-' . $term->term_id . '">' . $term->term_name . ' ' . trans('general.result') . '</option>';
        }

        foreach ($programList as $program) {
            $res .= '<option value="p-' . $program->program_id . '">' . $program->program_name . ' ' . trans('general.result') . '</option>';
        }
        return $res;
    }

    /**
     * @param $academicYear
     * @return string
     */
    public static function getNextAcademicYear($academicYear)
    {
        strtok($academicYear, '/');
        $nextYear = strtok('/');
        $nextAcademicYear = $nextYear . '/' . ($nextYear + 1);

        return $nextAcademicYear;
    }
    /**
     *  State : 1 is for delete
     *  State : 2 is for edit
     *
     */
    /**
     * @param $sequenceName
     * @param $ay
     * @return int
     */
    public static function recordAcademicSettingActions($sequenceName, $ay)
    {
        $academicYear = self::getAcademicYear();
        $sequence = self::getSequence();
        $sectionCode = Auth::user()->sections_section_code;
        DB::table(trans('database/table.audit_academic_setting_actions'))
            ->insert([
                trans('database/table.sequences_sequence_name') => $sequence->sequence_name,
                trans('database/table.sequence_name') => $sequenceName,
                trans('database/table.a_year') => $ay,
                trans('database/table.users_user_id') => Auth::user()->user_id,
                trans('database/table.academic_year') => $academicYear,
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
    public static function getAcademicSettingActionsForAuditing($academicYear)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $audit = DB::table(trans('database/table.audit_academic_setting_actions'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->get();
        return $audit;
    }

    /**
     * Get the select list of all programs
     * for a particular section
     * @return string
     */
    public static function getActionListThree()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $res .= '<option value="0">' . trans('actions/action.action_add') . '</option>';
        $res .= '<option value="1">' . trans('actions/action.action_delete') . '</option>';
        $res .= '<option value="2">' . trans('actions/action.action_edit') . '</option>';

        return $res;
    }

    /**
     * @param $state
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function getActionThreeInterpretationByState($state,$name)
    {

        $state_interpretation = null;
        switch ($state) {
            case 0:
                $state_interpretation = trans('actions/action.action_add_i',['name' => $name]);
                break;
            case 1:
                $state_interpretation = trans('actions/action.action_delete_i',['name' => $name]);
                break;
            case 2:
                $state_interpretation = trans('actions/action.action_edit_i',['name' => $name]);
                break;
        }

        return $state_interpretation;

    }

    /**
     * Get the select list of all programs
     * for a particular section
     * @return string
     */
    /**
     *  State : 1 is for suspension
     *  State : 2 is for reset
     *  state : 3 is for edit
     *  state : 4 is for password reset
     *  for add user is immediately found in the users_table as users_user_id
     */
    public static function getActionListMany()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $res .= '<option value="0">' . trans('actions/action.action_add') . '</option>';
        $res .= '<option value="1">' . trans('actions/action.action_suspend') . '</option>';
        $res .= '<option value="2">' . trans('actions/action.action_reset') . '</option>';
        $res .= '<option value="3">' . trans('actions/action.action_edit') . '</option>';
        $res .= '<option value="4">' . trans('actions/action.action_reset_password') . '</option>';
        $res .= '<option value="5">' . trans('actions/action.action_change_series') . '</option>';

        return $res;
    }

    /**
     * @param $state
     * @param $name
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function getActionManyInterpretationByState($state, $name)
    {

        $state_interpretation = null;
        switch ($state) {
            case 0:
                $state_interpretation = trans('actions/action.action_add_i',['name' => $name]);
                break;
            case 1:
                $state_interpretation = trans('actions/action.action_suspend_i', ['name' => $name]);
                break;
            case 2:
                $state_interpretation = trans('actions/action.action_reset_i', ['name' => $name]);
                break;
            case 3:
                $state_interpretation = trans('actions/action.action_edit_i', ['name' => $name]);
                break;
            case 4:
                $state_interpretation = trans('actions/action.action_reset_password_i', ['name' => $name]);
                break;
            case 5:
                $state_interpretation = trans('actions/action.action_change_series_i', ['name' => $name]);
                break;
        }

        return $state_interpretation;

    }


    /**
     * @param $academicYear
     * @return mixed
     */
    public static function getToAndFromAcademicYearDate($academicYear)
    {
        $year1 = strtok($academicYear, '/');
        $year2 = strtok('/');
        $from = $year1 . '-09-01';
        $to = $year2 . '-09-01';
        $data['from'] = $from;
        $data['to'] = $to;
        return $data;
    }


}
