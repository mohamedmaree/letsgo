<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{

    protected $table = 'product';

    public function store(){
        return $this->belongsTo('App\Stores','store_id','id');
    } 

    public function menuCategory(){
        return $this->belongsTo('App\menuCategories','menu_category_id','id');
    }        
}
