<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

define('MATRICULE_END_NUMBER_LENGT', 4);
define('MAX_NOTIFICATION_NO', 1);

class Student extends Model
{
    use Notifiable;

    /**
     * @var array
     */
    protected $guarded = ['student_id'];

    /**
     * @var string
     */
    protected $primaryKey = 'student_id';

    /**
     * @param $notification
     * @return mixed
     */
    public function routeNotificationForNexmo($notification)
    {
        return $this->father_address;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function series()
    {
        return $this->belongsToMany('App\Subject', trans('database/table.series_has_students'), trans('database/table.matricule'), trans('database/table.series_series_code'))->withPivot([trans('database/table.sections_section_code'), trans('database/table.academic_year'), trans('database/table.classes_class_code')]);
    }

    /**
     * Get all students with name $name
     * @param $name
     * @return \Illuminate\Support\Collection
     */
    public static function getStudentsByName($name)
    {
        $res = DB::table(trans('database/table.students'))
            ->where(trans('database/table.full_name'), $name)
            ->get();

        return $res;
    }

    /**
     * Get a student with name $name
     * @param $name
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function getStudentByName($name)
    {
        $res = DB::table(trans('database/table.students'))
            ->where(trans('database/table.full_name'), $name)
            ->first();

        return $res;
    }

    /**
     * @param $matricule
     * @return Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public static function getStudentClassNameByMatricule($matricule)
    {
        $academic_year = Setting::getAcademicYear();
        $res = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->where(trans('database/table.academic_year'), $academic_year)
            ->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($res)) {
            return $res;
        }

        $className = AcademicLevel::getClassNameByCode($res->classes_class_code);

        return $className;
    }

    /**
     * @param $matricule
     * @param $academic_year
     * @return Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public static function getStudentNextClassCodeByMatricule($matricule, $academic_year)
    {
        $res = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->where(trans('database/table.academic_year'), $academic_year)
            ->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($res)) {
            return $res;
        }

        $class = AcademicLevel::getClassByCode($res->classes_class_code);
        return $class;
    }

    /**
     * @param $matricule
     * @return Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public static function getStudentClassCodeByMatricule($matricule)
    {
        $academic_year = Setting::getAcademicYear();
        $res = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->where(trans('database/table.academic_year'), $academic_year)
            ->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($res)) {
            return $res;
        }
        return $res->classes_class_code;
    }

    /**
     * @param $matricule
     * @return Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public static function getStudentClassCodePerYear($matricule, $academic_year)
    {
        $res = DB::table(trans('database/table.classes_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->where(trans('database/table.academic_year'), $academic_year)
            ->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($res)) {
            return null;
        }
        return $res->classes_class_code;
    }

    /**
     * Get a student by his name ,cycle (program) code and parent phone
     * @param $name
     * @param $phone
     * @param $programCode
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function getStudentByParentPhone($name, $phone, $programCode)
    {
        $res = DB::table(trans('database/table.students'))
            ->where(trans('database/table.full_name'), $name)
            ->where(trans('database/table.father_address'), $phone)
            ->where(trans('database/table.programs_program_code'), $programCode)
            ->first();

        return $res;
    }

    /**
     * Check where a student already exist
     * @param $name
     * @param $phone
     * @param $programCode
     * @return bool
     */
    public static function studentAlreadyExist($name, $phone, $programCode)
    {
        $res = DB::table(trans('database/table.students'))
            ->where(trans('database/table.full_name'), $name)
            ->where(trans('database/table.father_address'), $phone)
            ->where(trans('database/table.programs_program_code'), $programCode)
            ->first();
        if (empty($res)) {
            return false;
        }

        return true;
    }


    /**
     * Check if the students credentials are valid
     * @param $matricule
     * @param $secretCode
     * @return bool
     */
    public static function credentialChecker($matricule, $secretCode)
    {

        $res = DB::table(trans('database/table.student_accounts'))
            ->where(trans('database/table.matricule'), $matricule)->first();
        if (empty($res)) {
            return false;
        }

        if (!(Encrypter::decrypt($res->secret_code) == $secretCode)) {
            return false;
        }

        return true;
    }

    /**
     * @param $matricule
     * @return mixed
     */
    public static function getStudentByMatricule($matricule)
    {
        $student = self::where(trans('database/table.matricule'), $matricule)->first();
        return $student;
    }

    /**
     * @param $matricule
     * @return mixed|null
     */
    public static function getStudentSeriesNameByMatricule($matricule)
    {
        $series = DB::table(trans('database/table.series_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($series)) {
            return null;
        }
        $series_name = Series::getDBSeriesNameByCode($series->series_series_code);
        return $series_name;
    }

    /**
     * @param $matricule
     * @return mixed|null
     */
    public static function getStudentSeriesCodeByMatricule($matricule)
    {
        $series = DB::table(trans('database/table.series_has_students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($series)) {
            return null;
        }
        return $series->series_series_code;
    }

    /**
     * @param $programCode
     * @param $sectionCode
     * @param $academic_year
     * @return string
     */
    public static function getLastMatriculeByCode($programCode, $sectionCode, $academic_year)
    {
        $matricule_initial = Setting::getMatriculeInitialByCodes($programCode, $sectionCode, $academic_year);
        $matricule_setup = $matricule_initial . substr(strtok($academic_year, '/'), -2) . strtoupper($programCode);


        $lastMatricule = self::where(trans('database/table.matricule'), 'like', ($matricule_setup . '%'))
            ->orderBy(trans('database/table.matricule'), 'desc')->first();

        if (empty($lastMatricule)) {
            return $matricule_setup;
        }

        return $lastMatricule->matricule;
    }

    /**
     * @param $data
     * @return bool
     */
    public static function batchStudentSave($data)
    {
        $res = DB::table(trans('database/table.students'))
            ->insert($data);

        return $res;
    }

    /**
     * @param $matricule
     * @param $data
     * @return int
     */
    public static function batchStudentUpdate($matricule, $data)
    {
        $res = DB::table(trans('database/table.students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->update($data);

        return $res;
    }


    /**
     * @param $data
     * @return bool
     */
    public static function batchAccountSave($data)
    {
        $res = DB::table(trans('database/table.student_accounts'))
            ->insert($data);

        return $res;
    }

    /**
     * @param $matricule
     * @param $secret
     * @return int
     */
    public static function resetSecret($matricule, $secret)
    {
        DB::table(trans('database/table.student_accounts'))
            ->updateOrInsert([trans('database/table.matricule') => $matricule],
                [trans('database/table.secret_code') => Encrypter::encrypt($secret), trans('database/table.state') => 1]
            );

        return 0;
    }


    /**
     * Generate a student's matricule by program code
     * @param $programCode
     * @param $sectionCode
     * @param $academicYear
     * @return string
     */
    public static function generateMatricule($programCode, $sectionCode, $academicYear)
    {
        $matCounter = 0;
        $lastMatricule = Student::getLastMatriculeByCode($programCode, $sectionCode, $academicYear);
        $matriculeSetup = Setting::getMatriculeSetting($programCode, $sectionCode, $academicYear);

        $matriculeSetupLength = strlen($matriculeSetup);
        if (strcasecmp($lastMatricule, $matriculeSetup) == 0) {
            $matCounter++;
        } else {
            $let = strlen($lastMatricule) - $matriculeSetupLength;
            $matCounter = substr($lastMatricule, -$let);
            $matCounter++;
        }
        $padNumber = (MATRICULE_END_NUMBER_LENGT - 1) + $matriculeSetupLength;
        $matricule = str_pad($matriculeSetup, $padNumber, '0') . $matCounter++;

        return $matricule;
    }

    /**
     * @param $matricule
     * @return bool
     */
    public static function isSuspended($matricule)
    {
        $student = DB::table(trans('database/table.students'))
            ->where(trans('database/table.matricule'), $matricule)->first();
        if (empty($student)) {
            return true;
        }

        if ($student->academic_state == 0) {
            return true;
        }

        return false;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getPortalSecretCodes()
    {
        $secret_codes = DB::table(trans('database/table.student_accounts'))
            ->get();
        return $secret_codes;
    }

    /**
     * @param $matricule
     * @return string
     */
    public static function getPortalSecretCodeByMatricule($matricule)
    {
        $secret_code = DB::table(trans('database/table.student_accounts'))
            ->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($secret_code)) {
            return null;
        }
        return Encrypter::decrypt($secret_code->secret_code);
    }

    /**
     * @param $academicYear
     * @param $sectionCode
     * @return mixed
     */
    public static function getStudentPerYear($academicYear, $sectionCode)
    {
        $year = strtok($academicYear, '/');
        $nextYear = strtok('/');
        $search_start = $year . '-08' . '-01';
        $search_end = $nextYear . '-08' . '-01';
        $students = self::whereBetween('admission_date', [$search_start, $search_end])->where(trans('database/table.sections_section_code'), $sectionCode)->get();
        return $students;
    }

    public static function getStudentNameByMatricule($matricule)
    {
        $section_code = Auth::user()->sections_section_code;
        $res = DB::table(trans('database/table.students'))
            ->where(trans('database/table.matricule'), $matricule)
            ->where(trans('database/table.sections_section_code'), $section_code)
            ->first();
        if (empty($res)) {
            return $res;
        }

        return $res->full_name;
    }

    /**
     * @param $studentId
     * @param $sequenceId
     * @param $academicYear
     * @return bool
     */
    public static function isSMSDelivered($studentId, $sequenceId, $academicYear)
    {
        $notStatus = DB::table(trans('database/table.sms_notifications'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.students_student_id'), $studentId)
            ->first();
        if (empty($notStatus)) {
            return false;
        }

        if ($notStatus->sms_count == MAX_NOTIFICATION_NO) {
            return true;
        }
        return false;
    }

    /**
     * @param $studentId
     * @param $sequenceId
     * @param $academicYear
     * @return bool
     */
    public static function getSMSDeliveryRecordCount($studentId, $sequenceId, $academicYear)
    {
        $notStatus = DB::table(trans('database/table.sms_notifications'))
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.sequences_sequence_id'), $sequenceId)
            ->where(trans('database/table.students_student_id'), $studentId)
            ->first();
        if (empty($notStatus)) {
            return 0;
        }
        return $notStatus->sms_count;
    }

    /**
     * @param $data
     * @return int
     */
    public static function saveSMSRecord($data)
    {
        $studentIds = collect($data)->unique(trans('database/table.students_student_id'))->pluck(trans('database/table.students_student_id'))->toArray();
        $student = collect($data)->first();
        if (!empty($data)) {
            DB::table(trans('database/table.sms_notifications'))
                ->where(trans('database/table.sections_section_code'), $student[trans(trans('database/table.sections_section_code'))])
                ->where(trans('database/table.academic_year'), $student[trans('database/table.academic_year')])
                ->whereIn(trans('database/table.students_student_id'), $studentIds)
                ->delete();

            DB::table(trans('database/table.sms_notifications'))
                ->insert($data);
        }
        return 0;
    }

    /*
     *  State : 1 is for suspension
     *  State : 2 is for reset
     *  state : 3 is for edit
     *  state : 4 is for password reset
     *  for add user is immediately found in the users_table as users_user_id
     */
    /**
     * @param $state
     * @param $studentName
     * @return int
     */
    public static function recordStudentActions($state, $studentName)
    {
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $sectionCode = Auth::user()->sections_section_code;
        DB::table(trans('database/table.audit_student_actions'))
            ->insert([
                trans('database/table.sequences_sequence_name') => $sequence->sequence_name,
                trans('database/table.students_student_name') => $studentName,
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
    public static function getStudentActionsForAuditing($state, $academicYear)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $audit = DB::table(trans('database/table.audit_student_actions'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->where(trans('database/table.state'), $state)
            ->get();
        return $audit;
    }

    /**
     * @param $academicYear
     * @return \Illuminate\Support\Collection
     */
    public static function getStudentSeriesChanges($academicYear)
    {
        $sectionCode = Auth::user()->sections_section_code;
        $audit = DB::table(trans('database/table.student_series_changes'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.academic_year'), $academicYear)
            ->get();
        return $audit;
    }

    /**
     * @param $subjectId
     * @return mixed|null
     */
    public static function getStudentNameById($studentId)
    {
        $student = DB::table(trans('database/table.students'))
            ->where(trans('database/table.student_id'), $studentId)
            ->first();

        if (empty($student)) {
            return trans('general.student_delete');;
        }

        return $student->full_name;
    }

}
