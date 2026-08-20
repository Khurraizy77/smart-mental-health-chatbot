<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodTracking extends Model
{
    protected $table = 'mood_tracking';

    protected $primaryKey = 'mood_id';

    protected $fillable = [
        'user_id',
        'mood_type',
        'date',
        'overall_sentiment',
        'mood_score',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
