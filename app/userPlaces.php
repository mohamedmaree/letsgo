<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userPlaces extends Model{
    protected $table = 'user_places';

        public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 
}
