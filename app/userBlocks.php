<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userBlocks extends Model{
  
   protected $table = "user_blocks";
    
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    } 

}
