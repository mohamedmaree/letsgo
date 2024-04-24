<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prices extends Model{

	protected $table = 'prices';

    public function cartype(){
        return $this->belongsTo('App\carTypes','car_type_id','id');
    } 

}
