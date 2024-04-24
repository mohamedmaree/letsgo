<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public function Permissions()
    {
        return $this->hasMany('App\Permission','role_id','id');
    }
    
}
