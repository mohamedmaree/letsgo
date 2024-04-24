<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Authentication  extends Model
{
    protected $table = 'authentication';
    
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 
    

}
