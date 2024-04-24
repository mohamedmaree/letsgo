<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usersCoupons extends Model{
    protected $table = 'users_coupons';
    
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

    public function coupon(){
        return $this->belongsTo('App\Coupons','coupon_id','id');
    } 

}
