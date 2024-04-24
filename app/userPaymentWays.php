<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userPaymentWays extends Model{
	
    protected $table = 'user_payment_ways';
    
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

}
