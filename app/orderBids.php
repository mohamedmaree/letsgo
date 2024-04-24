<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderBids extends Model{
     protected $table = 'order_bids';

    public function order(){
        return $this->belongsTo('App\Order','order_id','id');
    } 

    public function captain(){
        return $this->belongsTo('App\User','user_id','id');
    } 

}
