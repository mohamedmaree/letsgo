<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chargeCardsUsers extends Model{

   protected $table = 'charge_cards_users';

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 
    
}
