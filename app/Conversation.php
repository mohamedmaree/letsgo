<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model{

 protected $table = 'conversations';
    public function messages()
    {
        return $this->hasMany('App\Message');
    }
    public function firstuser()
    {
        return $this->belongsTo('App\User','user1');
    }
    public function seconduser()
    {
        return $this->belongsTo('App\User','user2');
    }
}
