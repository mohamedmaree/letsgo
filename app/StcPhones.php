<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StcPhones extends Model{
   protected $table = "stc_phones";

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 
      
}
