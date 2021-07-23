<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    /**
     * @var
     *
     */
    protected $primaryKey = 'privilege_id';

    /**
     * @var
     */
    protected $table = 'privileges';

    public function roles(){
        return $this->belongsToMany('App\Role',trans('database/table.roles_has_privileges'),trans('database/table.privileges_privilege_id'),trans('database/table.roles_role_id'));
    }
}
