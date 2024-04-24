<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profits extends Model{
    protected $table = 'profits';

    public function provider(){
        return $this->belongsTo('App\User','user_id','id');
    }      
}
