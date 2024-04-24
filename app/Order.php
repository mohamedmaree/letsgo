<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $table = 'orders';
    
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

    public function captain(){
        return $this->belongsTo('App\User','captain_id','id');
    }     

    public function car(){
        return $this->belongsTo('App\userCars','car_id','id');
    } 

    public function cartype(){
        return $this->belongsTo('App\carTypes','car_type_id','id');
    } 
    
    public function users(){
        return $this->hasMany('App\orderUsers','order_id','id');
    } 

    public function country(){
        return $this->belongsTo('App\Country','country_id','id');
    } 

    public function city(){
        return $this->belongsTo('App\City','city_id','id');
    } 

    public function externalApp(){
        return $this->belongsTo('App\externalAppTokens','external_app_id','id');
    }     

}
