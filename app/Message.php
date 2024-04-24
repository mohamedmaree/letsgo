<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['content' ];

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
