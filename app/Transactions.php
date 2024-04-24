<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model{
    protected $table = 'transactions';

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
