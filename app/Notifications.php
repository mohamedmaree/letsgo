<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['user_id', 'notifier_id', 'message','title', 'data' ,'order_status' ,'key','created_at'];

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function notifier(){
        return $this->belongsTo('App\User','notifier_id','id');
    }

}
