<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usersOrdersHistory extends Model{
    protected $table = 'users_orders_history';
    
    public function captain(){
        return $this->belongsTo('App\User','captain_id','id');
    } 

    public function order(){
        return $this->belongsTo('App\Order','order_id','id');
    }     
}
