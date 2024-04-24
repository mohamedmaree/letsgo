<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menuCategories extends Model{

    protected $table = 'menu_categories';
    
    public function store(){
        return $this->belongsTo('App\Stores','store_id','id');
    }

    public function products(){
        return $this->hasMany('App\Product','menu_category_id','id');
    } 

}
