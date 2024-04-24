<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contact_us';
        protected $fillable = [
        'name', 'email', 'message'
    ];

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }  

    public function order(){
        return $this->belongsTo('App\Order','order_id','id');
    }  

    public function conversation(){
        return $this->belongsTo('App\Conversation','conversation_id','id');
    }  

}
