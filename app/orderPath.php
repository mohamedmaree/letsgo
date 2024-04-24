<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderPath extends Model{

     protected $table = 'order_paths';

    public function order(){
        return $this->belongsTo('App\Order','order_id','id');
    } 

    public function captain(){
        return $this->belongsTo('App\User','captain_id','id');
    } 
}
