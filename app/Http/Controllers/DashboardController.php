<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\Message;
use App\Models\MoodTracking;
use App\Models\WellbeingAssessment;
use App\Services\DeepSeekService;
use App\Services\WellbeingRecommendationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct(
        private readonly WellbeingRecommendationService $recommendationService,
        private readonly DeepSeekService $deepSeek
    )
    {
    }

    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $sessionIds = ChatSession::where('user_id', Auth::id())
            ->pluck('session_id');

        $totalChats = $sessionIds->count();
        $totalMessages = Message::whereIn('session_id', $sessionIds)->count();

        $positiveMood = MoodTracking::where('user_id', Auth::id())
            ->where('mood_type', 'positive')
            ->count();

        $negativeMood = MoodTracking::where('user_id', Auth::id())
            ->where('mood_type', 'negative')
            ->count();

        $latestMood = MoodTracking::where('user_id', Auth::id())
            ->latest()
            ->value('mood_type') ?? 'neutral';

        $recommendations = $this->recommendationService
            ->assignedForUser(Auth::id(), $latestMood)
            ->pluck('recommendation_text')
            ->all();

        $aiSuggestion = $this->aiSuggestion(
            $latestMood,
            $positiveMood,
            $negativeMood,
            $totalMessages
        );

        $latestAssessment = WellbeingAssessment::where('user_id', Auth::id())
            ->latest()
            ->first();

        $moodTrend = MoodTracking::where('user_id', Auth::id())
            ->latest()
            ->limit(14)
            ->get(['date', 'mood_type', 'mood_score', 'created_at'])
            ->reverse()
            ->values()
            ->map(fn (MoodTracking $mood) => [
                'date' => $mood->date
                    ? \Carbon\Carbon::parse($mood->date)->format('d M')
                    : ($mood->created_at?->format('d M') ?? null),
                'score' => $mood->mood_score,
                'mood' => $mood->mood_type,
            ]);

        $assessmentHistory = WellbeingAssessment::where('user_id', Auth::id())
            ->latest()
            ->limit(8)
            ->get(['id', 'total_score', 'wellbeing_level', 'ai_priority_level', 'created_at'])
            ->reverse()
            ->values()
            ->map(fn (WellbeingAssessment $assessment) => [
                'date' => $assessment->created_at?->format('d M'),
                'score' => $assessment->total_score,
                'level' => $assessment->wellbeing_level,
                'priority' => $assessment->ai_priority_level ?? 'Pending',
            ]);

        $needsAttention = $latestMood === 'emergency'
            || $negativeMood >= max(3, $positiveMood * 2)
            || in_array($latestAssessment?->ai_priority_level, ['High', 'Urgent'], true);

        return view('dashboard', [
            'totalMessages' => $totalMessages,
            'totalChats' => $totalChats,
            'positiveMood' => $positiveMood,
            'negativeMood' => $negativeMood,
            'recommendationCount' => count($recommendations),
            'latestMood' => $latestMood,
            'recommendations' => $recommendations,
            'aiSuggestion' => $aiSuggestion,
            'latestAssessment' => $latestAssessment,
            'assessmentHistory' => $assessmentHistory,
            'moodTrend' => $moodTrend,
            'needsAttention' => $needsAttention,
        ]);
    }

    private function aiSuggestion(
        string $latestMood,
        int $positiveMood,
        int $negativeMood,
        int $totalMessages
    ): string {
        if (app()->environment('testing')) {
            return $this->fallbackAiSuggestion($latestMood);
        }

        try {
            $suggestion = $this->deepSeek->chat([
                [
                    'role' => 'system',
                    'content' => 'You are a supportive student wellbeing assistant. Give one short, practical suggestion. Do not diagnose. If safety risk is possible, encourage urgent real-world support.',
                ],
                [
                    'role' => 'user',
                    'content' => "Latest mood: {$latestMood}. Positive records: {$positiveMood}. Difficult records: {$negativeMood}. Total chat messages: {$totalMessages}. Give one personalized wellbeing suggestion in 1-2 sentences.",
                ],
            ], maxTokens: 90, temperature: 0.6);

            if ($suggestion) {
                return $suggestion;
            }

            Log::warning('Dashboard AI suggestion failed.', [
                'reason' => 'No content returned from DeepSeek.',
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Dashboard AI suggestion request failed.', [
                'message' => $exception->getMessage(),
            ]);
        }

        return $this->fallbackAiSuggestion($latestMood);
    }

    private function fallbackAiSuggestion(string $latestMood): string
    {
        return match ($latestMood) {
            'positive' => 'Your recent mood looks lighter. Keep one helpful routine going today, even if it is small.',
            'negative' => 'Your recent mood looks difficult. Choose one 10-minute task, then pause and message someone safe if you need support.',
            'emergency' => 'Your latest mood needs urgent attention. Stay near someone trusted and contact emergency services, a hospital, or campus support immediately.',
            default => 'Start with one gentle check-in: name what you feel, take five slow breaths, and choose one small next step.',
        };
    }
}
