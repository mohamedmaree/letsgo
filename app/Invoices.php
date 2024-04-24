<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model{
    protected $table = 'invoices';

    public function coupon(){
        return $this->belongsTo('App\Coupons','coupon_id','id');
    } 

}
