<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AdminMessage;

class AdminConversation extends Model
{
    use SoftDeletes;

    protected $table = 'admin_conversations';


    public function messages()
    {
        return $this->hasMany(\App\AdminMessage::class);
    }

    // the client
    public function firstuser()
    {
        return $this->hasMany(\App\User::class, 'user1');
    }

    // the admin
    public function seconduser()
    {
        return $this->belongsTo(\App\User::class, 'user2');
    }

    public function adminMessages()
    {
        return $this->hasMany(AdminMessage::class);
    }

}
