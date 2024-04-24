<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersCashBack extends Model
{
    protected $table = 'users_cash_back';
   
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

    public function cashBack(){
        return $this->belongsTo('App\CashBack','cashback_id','id');
    } 

}
