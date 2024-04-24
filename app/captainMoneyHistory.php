<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class captainMoneyHistory extends Model
{
    protected $table = 'captain_money_histories';
    
    public function captain(){
        return $this->belongsTo('App\User','captain_id','id');
    } 
    

}
