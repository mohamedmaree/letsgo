<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model{
   protected $table = 'tickets';

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function order(){
        return $this->belongsTo('App\Order','order_id','id');
    }    
}
