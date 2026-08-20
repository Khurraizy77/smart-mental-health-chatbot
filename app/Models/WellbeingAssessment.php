<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WellbeingAssessment extends Model
{
    protected $fillable = [
        'user_id',
        'answers',
        'total_score',
        'wellbeing_level',
        'stress_reason',
        'preferred_support',
        'urgent_support',
        'ai_wellbeing_summary',
        'ai_main_concerns',
        'ai_stress_factors',
        'ai_suggestions',
        'ai_recommended_support',
        'ai_counselling_recommendation',
        'ai_priority_level',
        'ai_generated_at',
        'counsellor_notes',
        'review_status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'urgent_support' => 'boolean',
            'ai_main_concerns' => 'array',
            'ai_stress_factors' => 'array',
            'ai_suggestions' => 'array',
            'ai_generated_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isAiAvailable(): bool
    {
        return filled($this->ai_wellbeing_summary);
    }

    public function priorityBadgeClass(): string
    {
        return match ($this->ai_priority_level) {
            'Urgent' => 'wb-badge-emergency',
            'High' => 'wb-badge-negative',
            'Moderate' => 'wb-badge-positive',
            default => 'wb-badge-neutral',
        };
    }

    public function supportGuidance(): string
    {
        return match ($this->ai_priority_level) {
            'Urgent' => 'Please contact emergency services, a nearby hospital, campus support, or someone trusted immediately. Request counselling after taking immediate safety steps.',
            'High' => 'A counselling request is strongly recommended because your answers suggest significant stress or emotional difficulty.',
            'Moderate' => 'Counselling may be helpful if this continues, especially if stress affects your sleep, study, or daily activities.',
            'Normal' => 'Keep using self-care strategies and mood tracking. Counselling remains available if you want extra support.',
            default => 'Your assessment is saved. Counselling support is available while AI analysis is pending.',
        };
    }
}
