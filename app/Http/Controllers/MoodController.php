<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodTracking;
use App\Services\WellbeingRecommendationService;
use Illuminate\Support\Facades\Auth;

class MoodController extends Controller
{
    public function __construct(private readonly WellbeingRecommendationService $recommendationService)
    {
    }

    public function index()
    {
        $moods = MoodTracking::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        $latestMood = $moods->first()?->mood_type ?? 'neutral';
        $recommendations = $this->recommendationService
            ->assignedForUser(Auth::id(), $latestMood)
            ->values()
            ->map(fn ($recommendation, int $index) => [
                'title' => 'Suggested step ' . ($index + 1),
                'text' => $recommendation->recommendation_text,
            ])
            ->all();

        return view('mood.index', compact('moods', 'latestMood', 'recommendations'));
    }
}
