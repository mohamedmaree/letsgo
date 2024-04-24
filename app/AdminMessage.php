<?php

namespace App;

use App\AdminConversation;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminMessage extends Model
{
    use SoftDeletes;

    protected $fillable = ['seen'];
    
    protected $table = 'admin_messages';

    public function conversation()
    {
        return $this->belongsTo(AdminConversation::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public static function makeMessagesAsRead($messages, $reader_id)
    {
        $messages = $messages->where('user_id', $reader_id);
        foreach ($messages as $message) {
            $message->update(['seen' => 'true']);
        }
    }
}
