<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userMeta extends Model
{
   protected $table = "user_meta";
    
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

    public function reviewer(){
        return $this->belongsTo('App\User','reviewer_id','id');
    } 

    public function cartype(){
        return $this->belongsTo('App\carTypes','car_type_id','id');
    } 
    
    public function country(){
        return $this->belongsTo('App\Country','country_id','id');
    } 

    public function city(){
        return $this->belongsTo('App\City','city_id','id');
    } 

}
