<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class visitedPlaces extends Model{
  
   protected $table = "visited_places";
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 
}
