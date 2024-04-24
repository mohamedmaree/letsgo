<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userAvailableTimes extends Model{
   protected $table = "user_available_times";
    
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 
}
