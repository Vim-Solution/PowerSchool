<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['user_id'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $primaryKey = 'user_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roles()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * Check if a user has access to the functionality
     * with privilege url  $url
     * @param $url
     * @return bool
     */
    public function hasPermission($url)
    {
        if ($url == '/login') {
            return true;
        }
        $role = Role::find(Auth::user()->roles_role_id);
        $locale = Auth::user()->sections_section_code;
        $privs = collect([]);
        if (!empty($role)) {
             $privs = $role->privileges()->get()->where(trans('database/table.sections_section_code'),$locale)->where(trans('database/table.state'),1);
             $privs = $privs->push($role->privileges()->get()->where(trans('database/table.sections_section_code'),trans('database/table.bilingual'))->where(trans('database/table.state'),1));

             $privs = $privs->collapse();
            $privUrls = $privs->pluck(trans('database/table.privilege_url'))->toArray();
            foreach ($privUrls as $privUrl) {
                if ((trans('settings/routes.' . $privUrl) == $url) || (strcmp(trans('settings/routes.' . $privUrl), $url) == 0))
                    return true;
            }
        }

        return false;
    }


    /**
     * @return bool
     */
    public function isHalt()
    {
        $academicState = Auth::user()->academic_state;
        if ($academicState == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check is phone number exist in the system
     * @param $phone
     * @return bool
     */
    public static function phoneExist($phone)
    {
        if (DB::table(trans('database/table.users'))->where(trans('database/table.phone_number'), $phone)->exists())
            return true;

        return false;
    }


    /**
     * Check is email address exist in the system
     * @param $phone
     * @return bool
     */
    public static function emailExist($email)
    {
        if (DB::table(trans('database/table.users'))->where(trans('database/table.email'), $email)->exists())
            return true;

        return false;
    }

    /**
     * Search a user by his or her email
     * @param $email
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function searchUserByEmail($email)
    {
        $userSectionCode = Auth::user()->sections_section_code;
        $res = DB::table(trans('database/table.users'))
            ->where(trans('database/table.email'), $email)
            ->where(trans('database/table.sections_section_code'), $userSectionCode)
            ->first();
        return $res;
    }


    /**
     * @param $email
     * @param $data
     * @return mixed
     */
    public static function massUpdateUserInfoByEmail($email, $data)
    {
        $record = DB::table(trans('database/table.users'))->where(trans('database/table.email'), $email)
            ->update([
                trans('database/table.user_name') => $data['first-name'],
                trans('database/table.full_name') => $data['first-name'] . ' ' . $data['last-name'],
                trans('database/table.phone_number') => $data['mobile-phone'],
                trans('database/table.position') => $data['job-title'],
                trans('database/table.type') => $data['job-type'],
                trans('database/table.address') => $data['address'],
                trans('database/table.office_address') => $data['office-address'],
                trans('database/table.programs_program_code') => $data['program'],
                trans('database/table.sections_section_code') => $data['section'],
                trans('database/table.departments_department_id') => $data['department'],
                trans('database/table.roles_role_id') => $data['role'],
            ]);
        return $record;
    }

    /**
     * @return string
     */
    public static function getStaffList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $res .= '<option  value="0">' . trans('general.all_users') . '</option>';

        $section_code = Auth::user()->sections_section_code;
        if ($section_code == trans('database/table.bilingual')) {
            $staff = self::where(trans('database/table.academic_state'), 1)->get();
        } else {
            $staff = self::where(trans('database/table.sections_section_code'), $section_code)->where(trans('database/table.academic_state'), 1)->get();
        }
        foreach ($staff as $staf) {
            $res .= '<option value="' . $staf->user_id . '">' . $staf->full_name . '</option>';
        }

        return $res;
    }

    /**
     * @param $sectionCode
     * @return mixed
     */
    public static function getValidUsers($sectionCode)
    {
        if ($sectionCode == trans('database/table.bilingual')) {
            $staff = self::where(trans('database/table.academic_state'), 1)->get();
        } else {
            $staff = self::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.academic_state'), 1)->get();
        }
        return $staff;

    }

    /**
     * @return string
     */
    public static function getUserList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $users = self::where(trans('database/table.sections_section_code'), $section_code)->where(trans('database/table.academic_state'), 1)->get();
        foreach ($users as $user) {
            $res .= '<option value="' . $user->user_id . '">' . $user->full_name . '</option>';
        }

        return $res;
    }


    /**
     * @return string
     */
    public static function getEncodedUserList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $users = self::where(trans('database/table.sections_section_code'), $section_code)->where(trans('database/table.academic_state'), 1)->get();
        foreach ($users as $user) {
            $res .= '<option value="' . Encrypter::encrypt($user->user_id) . '">' . $user->full_name . '</option>';
        }

        return $res;
    }

    /**
     *  State : 1 is for suspension
     *  State : 2 is for reset
     *  state : 3 is for edit
     *  state : 4 is for password reset
     *  for add user is immediately found in the users_table as users_user_id
     */
    /**
     * @param $state
     * @param $suspensioneeName
     * @return int
     */
    public static function recordUserActions($state, $suspensioneeName)
    {
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $sectionCode = Auth::user()->sections_section_code;
        DB::table(trans('database/table.audit_user_actions'))
            ->insert([
                trans('database/table.sequences_sequence_name') => $sequence->sequence_name,
                trans('database/table.suspensionee_name') => $suspensioneeName,
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
    public static function getUserActionsForAuditing($state,$academicYear){
        $sectionCode = Auth::user()->sections_section_code;
        $audit = DB::table(trans('database/table.audit_user_actions'))
            ->where(trans('database/table.sections_section_code'),$sectionCode)
            ->where(trans('database/table.academic_year'),$academicYear)
            ->where(trans('database/table.state'),$state)
            ->get();
        return $audit;
    }

    /**
     * @param $sectionCode
     * @return mixed
     */
    public static  function getUsers($sectionCode){
        $users = self::where(trans('database/table.sections_section_code'),$sectionCode)
            ->get();

        return $users;
    }

    /**
     * @param $userId
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static  function getUserNameById($userId){
        $user = self::where(trans('database/table.user_id'),$userId)
            ->first();

        if(empty($user)){
            return trans('general.user_found_error');
        }
        return $user->full_name;
    }

}
