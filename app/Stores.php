<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model{
   protected $table = "stores";

    public function products(){
        return $this->hasMany('App\Product','store_id','id');
    } 

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

    public function parent(){
        return $this->belongsTo('App\Stores','parent_id','id');
    } 

    public function branches(){
        return $this->hasMany('App\Stores','parent_id','id');
    }  
    public function menues(){
        return $this->hasMany('App\storeMenus','store_id','id');
    }        
}
