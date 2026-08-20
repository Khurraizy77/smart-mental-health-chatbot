<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentimentAnalysis extends Model
{
    protected $table = 'sentiment_analysis';

    protected $primaryKey = 'sentiment_id';

    protected $fillable = [
        'message_id',
        'sentiment_type',
        'confidence_score'
    ];
}