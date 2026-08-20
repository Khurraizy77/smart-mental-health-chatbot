<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\Message;
use App\Models\MoodTracking;
use App\Models\Referral;
use App\Models\User;
use App\Models\WellbeingAssessment;
use App\Services\WellbeingRecommendationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct(private readonly WellbeingRecommendationService $recommendations)
    {
    }

    public function userAnalysis()
    {
        $user = Auth::user()->load('studentProfile');
        $sessionIds = ChatSession::where('user_id', $user->id)->pluck('session_id');

        $moods = MoodTracking::where('user_id', $user->id)->latest()->get();
        $moodCounts = $this->moodCounts($moods);
        $latestMood = $moods->first()?->mood_type ?? 'neutral';
        $recommendations = $this->recommendations
            ->assignedForUser($user->id, $latestMood)
            ->pluck('recommendation_text');

        $report = [
            'generatedAt' => now(),
            'user' => $user,
            'totalChats' => ChatSession::where('user_id', $user->id)->count(),
            'totalMessages' => Message::whereIn('session_id', $sessionIds)->count(),
            'studentMessages' => Message::whereIn('session_id', $sessionIds)->where('sender_type', 'student')->count(),
            'botMessages' => Message::whereIn('session_id', $sessionIds)->where('sender_type', 'chatbot')->count(),
            'moods' => $moods,
            'moodCounts' => $moodCounts,
            'latestMood' => $latestMood,
            'averageMoodScore' => round((float) $moods->avg('mood_score'), 1),
            'recommendations' => $recommendations,
            'referrals' => Referral::with('service')->where('user_id', $user->id)->latest()->get(),
            'latestAssessment' => WellbeingAssessment::where('user_id', $user->id)->latest()->first(),
        ];

        return Pdf::loadView('reports.user-analysis', $report)
            ->setPaper('a4')
            ->download('user-wellbeing-analysis.pdf');
    }

    public function adminAnalysis()
    {
        $moods = MoodTracking::latest()->get();
        $students = User::with('studentProfile')->where('role', 'student')->latest()->get();
        $referrals = Referral::with(['user.studentProfile', 'service'])->latest()->get();
        $assessments = WellbeingAssessment::with('user.studentProfile')->latest()->get();

        $report = [
            'generatedAt' => now(),
            'totalUsers' => User::count(),
            'totalStudents' => $students->count(),
            'totalAdmins' => User::where('role', 'admin')->count(),
            'totalMessages' => Message::count(),
            'totalChatSessions' => ChatSession::count(),
            'moodCounts' => $this->moodCounts($moods),
            'averageMoodScore' => round((float) $moods->avg('mood_score'), 1),
            'referralCounts' => [
                'pending' => $referrals->where('status', 'pending')->count(),
                'contacted' => $referrals->where('status', 'contacted')->count(),
                'completed' => $referrals->where('status', 'completed')->count(),
                'cancelled' => $referrals->where('status', 'cancelled')->count(),
            ],
            'students' => $students,
            'recentReferrals' => $referrals->take(10),
            'highRiskMoods' => $moods->where('mood_type', 'emergency')->count(),
            'urgentAssessments' => $assessments->where('ai_priority_level', 'Urgent')->count(),
            'pendingAssessmentReviews' => $assessments->whereIn('review_status', ['pending', 'flagged'])->count(),
            'recentAssessments' => $assessments->take(10),
        ];

        return Pdf::loadView('reports.admin-analysis', $report)
            ->setPaper('a4', 'landscape')
            ->download('admin-system-analysis.pdf');
    }

    private function moodCounts($moods): array
    {
        $total = max($moods->count(), 1);

        return collect(['positive', 'negative', 'neutral', 'emergency'])
            ->mapWithKeys(function (string $mood) use ($moods, $total) {
                $count = $moods->where('mood_type', $mood)->count();

                return [
                    $mood => [
                        'count' => $count,
                        'percentage' => round(($count / $total) * 100, 1),
                    ],
                ];
            })
            ->all();
    }
}
