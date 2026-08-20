<?php

namespace App\Services;

use App\Models\Recommendation;
use App\Models\UserRecommendation;
use Illuminate\Support\Collection;

class WellbeingRecommendationService
{
    public function ensureDefaults(): void
    {
        foreach ($this->defaults() as $recommendation) {
            Recommendation::firstOrCreate(
                [
                    'sentiment_type' => $recommendation['sentiment_type'],
                    'recommendation_text' => $recommendation['recommendation_text'],
                ],
                $recommendation
            );
        }
    }

    public function assignForMood(int $userId, string $mood): Collection
    {
        $recommendations = $this->forMood($mood);

        foreach ($recommendations as $recommendation) {
            UserRecommendation::firstOrCreate([
                'user_id' => $userId,
                'recommendation_id' => $recommendation->recommendation_id,
            ]);
        }

        return $recommendations;
    }

    public function assignedForUser(int $userId, string $mood): Collection
    {
        $this->ensureDefaults();

        $assigned = UserRecommendation::with('recommendation')
            ->where('user_id', $userId)
            ->whereHas('recommendation', fn ($query) => $query->where('sentiment_type', $mood))
            ->latest()
            ->limit(3)
            ->get()
            ->pluck('recommendation')
            ->filter();

        return $assigned->isNotEmpty()
            ? $assigned->values()
            : $this->assignForMood($userId, $mood);
    }

    public function forMood(string $mood): Collection
    {
        $this->ensureDefaults();

        $recommendations = Recommendation::where('sentiment_type', $mood)
            ->limit(3)
            ->get();

        return $recommendations->isNotEmpty()
            ? $recommendations
            : Recommendation::where('sentiment_type', 'neutral')->limit(3)->get();
    }

    public function scoreForMood(string $mood): int
    {
        return match ($mood) {
            'positive' => 8,
            'negative' => 3,
            'emergency' => 1,
            default => 5,
        };
    }

    private function defaults(): array
    {
        return [
            ['sentiment_type' => 'positive', 'recommendation_text' => 'Write down one thing that helped you feel okay today so you can repeat it later.'],
            ['sentiment_type' => 'positive', 'recommendation_text' => 'Keep the basics steady: sleep, water, food, and one short walk.'],
            ['sentiment_type' => 'positive', 'recommendation_text' => 'Share the good moment with someone you trust, even briefly.'],
            ['sentiment_type' => 'negative', 'recommendation_text' => 'Choose one small task you can do in 10 minutes and leave the rest for later.'],
            ['sentiment_type' => 'negative', 'recommendation_text' => 'Breathe in for 4 counts, hold for 4, and breathe out for 6. Repeat five times.'],
            ['sentiment_type' => 'negative', 'recommendation_text' => 'Tell a trusted person, counsellor, or lecturer that you are struggling today.'],
            ['sentiment_type' => 'emergency', 'recommendation_text' => 'Stay near another person and move away from anything you could use to hurt yourself.'],
            ['sentiment_type' => 'emergency', 'recommendation_text' => 'Contact emergency services, a hospital, campus security, or a trusted person immediately.'],
            ['sentiment_type' => 'emergency', 'recommendation_text' => 'Use the hospital finder or counselling services page to move toward urgent in-person support.'],
            ['sentiment_type' => 'neutral', 'recommendation_text' => 'Name what you feel right now without judging it. You do not need to fix it immediately.'],
            ['sentiment_type' => 'neutral', 'recommendation_text' => 'Look for five things you can see, four you can touch, and three you can hear.'],
            ['sentiment_type' => 'neutral', 'recommendation_text' => 'Take a short walk, drink water, stretch your neck, or step away from the screen.'],
        ];
    }
}
