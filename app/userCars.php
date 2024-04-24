<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userCars extends Model{
   protected $table = "user_cars";
    
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

    public function type(){
        return $this->belongsTo('App\carTypes','car_type_id','id');
    } 

}
