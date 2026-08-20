<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    protected $primaryKey = 'session_id';

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'session_id');
    }
}