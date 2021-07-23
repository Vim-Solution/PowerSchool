<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Role extends Model
{

    use SoftDeletes;
    /**
     * @var array
     */
    protected $guarded = ['role_id'];
    /**
     * @var
     *
     */
    protected $primaryKey = 'role_id';

    /**
     * @var
     */
    protected $table = 'roles';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function privileges()
    {
        return $this->belongsToMany('App\Privilege', trans('database/table.roles_has_privileges'), trans('database/table.roles_role_id'), trans('database/table.privileges_privilege_id'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User', trans('database/table.roles_role_id'));
    }


    /**
     * Added by Eng Ewang  Clarkson
     * 18/06/2019
     * get the privileges categorized
     * @param $roleId
     * @return mixed
     */
    public static function getCategorizedPrivileges($roleId)
    {
        $res['categories'] = collect([]);
        $res['privileges'] = collect([]);
        $res = '';
        $categories = collect([]);
        $role = DB::table(trans('database/table.roles'))
            ->where(trans('database/table.role_id'), $roleId)
            ->where(trans('database/table.state'), 1)
            ->first();
        if (!empty($role)) {
            $privilegeIds = DB::table(trans('database/table.roles_has_privileges'))
                ->where(trans('database/table.roles_role_id'), $roleId)
                ->pluck(trans('database/table.privileges_privilege_id'))
                ->toArray();

            $privileges = Privilege::whereIn(trans('database/table.privilege_id'), $privilegeIds)->where(trans('database/table.state'), 1)->get()->sortBy(trans('database/table.privilege_name'));
            if ($privileges->isNotEmpty()) {
                $categoryIds = $privileges->unique(trans('database/table.categories_category_id'))->pluck(trans('database/table.categories_category_id'));

                $categories = Category::whereIn(trans('database/table.category_id'), $categoryIds)->get()->sortBy(trans('database/table.category_name'));
            }
            if (($categories->count() <= 2) && ($privileges->count() < 7)) {
                foreach ($privileges as $privilege) {

                    $res .= '<li id="' . str_replace(' ', '_', trans('authorization/privilege.' . $privilege->privilege_name)) . '" ><a href="' . trans('settings/routes.' . $privilege->privilege_url) . '"><i class="' . trans('authorization/privilege.' . $privilege->privilege_icon) . '"></i> ' . trans('authorization/privilege.' . $privilege->privilege_name) . '</a></li>';

                }
            } else {
                foreach ($categories as $category) {
                    $res .= '<li class="navigation__sub" id="' . str_replace(' ', '_', trans('authorization/category.' . $category->category_name)) . '"><a href=""><i class="' . trans('authorization/category.' . $category->icon) . '"></i>' . trans('authorization/category.' . $category->category_name) . '</a>';
                    $res .= '<ul>';
                    $privs = $privileges->where(trans('database/table.categories_category_id'), $category->category_id)->sortBy(trans('database/table.privilege_name'));
                    foreach ($privs as $privilege) {

                        $res .= '<li id="' . str_replace(' ', '_', trans('authorization/privilege.' . $privilege->privilege_name)) . '"><a href="' . trans('settings/routes.' . $privilege->privilege_url) . '"><i class="' . trans('authorization/privilege.' . $privilege->privilege_icon) . '"></i> ' . trans('authorization/privilege.' . $privilege->privilege_name) . '</a></li>';

                    }
                    $res .= '</ul></li>';
                }
                return $res;
            }
        }
        return $res;
    }

    /**
     * Get a role's name by id
     * @param $id
     * @return |null
     */
    public static function getRoleNameById($id)
    {
        try {
            $role = self::find($id);
            return $role->role_name;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the select list of all roles
     * for a particular section
     * @return string
     */
    public static function getRolesList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $roles = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        foreach ($roles as $role) {
            $res .= '<option value="' . $role->role_id . '">' . $role->role_name . '</option>';
        }

        return $res;
    }


    /**
     * Get the select list of all roles
     * for a particular section
     * @return string
     */
    public static function getEncryptedRolesList()
    {
        $res = '<option selected disabled>' . trans('general.nothing_selected') . '</option>';
        $section_code = Auth::user()->sections_section_code;
        $roles = self::where(trans('database/table.sections_section_code'), $section_code)->get();
        foreach ($roles as $role) {
            $res .= '<option value="' . Encrypter::encrypt($role->role_id) . '">' . $role->role_name . '</option>';
        }

        return $res;
    }
    /**
     * Get the list of roles and the actions permitted over the role
     * @return string
     */
    public static function getRoleTodoList()
    {
        $res = '<div class="listview listview--bordered">';
        $section_code = Auth::user()->sections_section_code;
        $roles = self::where(trans('database/table.sections_section_code'), $section_code)->where(trans('database/table.state'), 1)->get();
        foreach ($roles as $role) {
            $res .= '<div class="listview__item">' .
                '<div class="checkbox checkbox--char todo__item">' .
                '<label class="checkbox__char bg-blue" for="custom-checkbox-1">' . $role->role_name[0] . '</label>' .
                ' <div class="listview__content">' .
                '<div class="listview__heading">' . $role->role_name . '</div>' .
                ' </div>' .

                '<div class="listview__attrs">' .
                ' <span>#' . $role->description . '</span>' . self::getContactList($role->role_id);
            $res .= '</div>' .
                '</div>' .
                '<div class="actions listview__actions">' .
                '<div class="dropdown actions__item">' .
                '<i class="zmdi zmdi-more-vert" data-toggle="dropdown"></i>' .
                '<div class="dropdown-menu dropdown-menu-right">' .
                '<a class="dropdown-item" href="' . trans('settings/routes.manage_role') . trans('settings/routes.edit') . '/' . Encrypter::encrypt($role->role_id) . '">' . trans('actions/action.edit') . '</a>' .
                '<a class="dropdown-item" href="' . trans('settings/routes.manage_role') . trans('settings/routes.delete') . '/' . Encrypter::encrypt($role->role_id) . '">' . trans('actions/action.delete') . '</a>' .
                '</div>' .
                '</div>' .
                '</div>' .
                '</div>';

        }
        $res .= '</div>';
        return $res;
    }

    /**
     * Get the users who have the role with role id
     * @param $roleId
     * @return string
     */
    public static function getContactList($roleId)
    {
        $users = User::where(trans('database/table.roles_role_id'), $roleId)->get();
        $res = '';
        if ($users->isNotEmpty()) {
            $res .= '<div class="widget-signups"><div class="widget-signups__list">';
            foreach ($users as $user) {
                if ($user->profile == trans('img/img.default_profile')) {
                    $res .= '<a data-toggle = "tooltip" title = "' . $user->full_name . '" ><div class="avatar-char color-red" >' . $user->full_name[0] . '</div></a >';
                } else {
                    $res .= '<a data-toggle = "tooltip" title = "' . $user->full_name . '" ><img class="avatar-img" src = "' . asset($user->profile) . '" alt = "" ></a >';
                }
            }
            $res .= '</div></div>';
        }
        return $res;
    }

    /**
     * Update role name and role description using an array
     * @param $rid
     * @param $data
     * @return int
     */
    public static function updateRoleInformationById($rid, $data)
    {
        $resource = DB::table(trans('database/table.roles'))
            ->where(trans('database/table.role_id'), $rid)
            ->update([
                trans('database/table.role_name') => $data['role-name'],
                trans('database/table.description') => $data['role-description']
            ]);
        return $resource;
    }

    /**
     * @param $sectionCode
     * @param $rid
     * @return bool
     */
    public static function isSectionRole($sectionCode, $rid)
    {
        $resource = DB::table(trans('database/table.roles'))
            ->where(trans('database/table.role_id'), $rid)
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->get();
        if ($resource->isEmpty()) {
            return false;
        }

        return true;
    }
    /**
     * assign /revoke a role from a set of users
     * @param $users
     * @param $roleId
     * @return int
     */
    public static function setUserRole($usersIds, $roleId)
    {
        $res = DB::table(trans('database/table.users'))
            ->whereIn(trans('database/table.user_id'), $usersIds)
            ->update([trans('database/table.roles_role_id') => $roleId]);

        return $res;
    }

    /**
     * @param $roleId
     * @return bool
     */
    public static function isAssignedToStaff($roleId){
        $res = DB::table(trans('database/table.roles_has_privileges'))
                    ->where(trans('database/table.roles_role_id'),$roleId)
                    ->get();
        if($res->isNotEmpty()){
            return true;
        }

        return false;
    }
}
