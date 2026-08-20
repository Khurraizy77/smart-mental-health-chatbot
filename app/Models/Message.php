<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $primaryKey = 'message_id';

    protected $fillable = [
        'session_id',
        'sender_type',
        'message_text'
    ];

    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'session_id');
    }
}