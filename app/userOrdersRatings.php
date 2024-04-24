<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userOrdersRatings extends Model{
   protected $table = "user_orders_ratings";
   
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

}
