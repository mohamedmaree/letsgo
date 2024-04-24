<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class storeMenus extends Model{
  
 protected $table = "store_menus";
    
    public function store(){
        return $this->belongsTo('App\Stores','store_id','id');
    } 
}
