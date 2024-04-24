<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model{
  
    protected $table = 'payments';

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }  

    public function seconduser(){
        return $this->belongsTo('App\User','second_user_id','id');
    }          

    public function country(){
        return $this->belongsTo('App\Country','country_id','id');
    } 
    
}
