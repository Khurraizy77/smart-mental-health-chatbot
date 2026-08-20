<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recommendation extends Model
{
    protected $primaryKey = 'recommendation_id';

    protected $fillable = [
        'sentiment_type',
        'recommendation_text'
    ];

    public function userRecommendations(): HasMany
    {
        return $this->hasMany(UserRecommendation::class, 'recommendation_id', 'recommendation_id');
    }
}
