<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userDevices extends Model{
  
   protected $table = "user_devices";
    
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

}
