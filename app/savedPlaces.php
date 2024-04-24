<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class savedPlaces extends Model{
  
   protected $table = "saved_places";
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 
}
