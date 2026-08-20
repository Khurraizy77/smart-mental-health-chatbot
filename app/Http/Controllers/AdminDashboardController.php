<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\MoodTracking;
use App\Models\Referral;
use App\Models\WellbeingAssessment;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
{
    $userCounts = User::select('role', DB::raw('COUNT(*) as total'))
        ->groupBy('role')
        ->pluck('total', 'role');

    $totalUsers = $userCounts->sum();
    $totalStudents = (int) ($userCounts['student'] ?? 0);
    $totalAdmins = (int) ($userCounts['admin'] ?? 0);
    $totalMessages = Message::count();

    $moodCounts = MoodTracking::select('mood_type', DB::raw('COUNT(*) as total'))
        ->groupBy('mood_type')
        ->pluck('total', 'mood_type');

    $positiveMood = (int) ($moodCounts['positive'] ?? 0);
    $negativeMood = (int) ($moodCounts['negative'] ?? 0);
    $neutralMood = (int) ($moodCounts['neutral'] ?? 0);
    $emergencyMood = (int) ($moodCounts['emergency'] ?? 0);

    $referralStatusCounts = Referral::select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

    $pendingReferrals = (int) ($referralStatusCounts['pending'] ?? 0);

    $assessmentPriorityCounts = WellbeingAssessment::select('ai_priority_level', DB::raw('COUNT(*) as total'))
        ->groupBy('ai_priority_level')
        ->pluck('total', 'ai_priority_level');

    $urgentAssessments = (int) ($assessmentPriorityCounts['Urgent'] ?? 0);
    $pendingAssessmentReviews = WellbeingAssessment::whereIn('review_status', ['pending', 'flagged'])->count();

    $facultyMoodSummary = DB::table('mood_tracking')
        ->join('users', 'mood_tracking.user_id', '=', 'users.id')
        ->leftJoin('students', 'users.id', '=', 'students.user_id')
        ->where('users.role', 'student')
        ->selectRaw("
            COALESCE(students.faculty, 'Unknown') AS faculty,
            COUNT(*) AS total_records,
            ROUND(AVG(mood_tracking.mood_score), 1) AS average_score,
            SUM(CASE WHEN mood_tracking.mood_type = 'emergency' THEN 1 ELSE 0 END) AS emergency_count,
            SUM(CASE WHEN mood_tracking.mood_type = 'negative' THEN 1 ELSE 0 END) AS negative_count
        ")
        ->groupByRaw("COALESCE(students.faculty, 'Unknown')")
        ->orderByDesc('emergency_count')
        ->orderByDesc('negative_count')
        ->limit(5)
        ->get();

    $recentHighRiskMoods = DB::table('mood_tracking')
        ->join('users', 'mood_tracking.user_id', '=', 'users.id')
        ->leftJoin('students', 'users.id', '=', 'students.user_id')
        ->where('mood_tracking.mood_type', 'emergency')
        ->select([
            'users.name',
            'users.email',
            'students.matric_no',
            'mood_tracking.date',
            'mood_tracking.created_at',
        ])
        ->latest('mood_tracking.created_at')
        ->limit(5)
        ->get();

    $recentReferrals = Referral::with(['user.studentProfile', 'service'])
        ->latest()
        ->limit(5)
        ->get();

    $studentsNeedingAttention = User::query()
        ->with('studentProfile')
        ->where('role', 'student')
        ->where(function ($query): void {
            $query
                ->whereHas('moodTrackings', fn ($moodQuery) => $moodQuery->where('mood_type', 'emergency'))
                ->orWhereHas('wellbeingAssessments', fn ($assessmentQuery) => $assessmentQuery->whereIn('ai_priority_level', ['High', 'Urgent']));
        })
        ->latest()
        ->limit(6)
        ->get();

    return view('admin.dashboard', compact(
        'totalUsers',
        'totalStudents',
        'totalAdmins',
        'totalMessages',
        'positiveMood',
        'negativeMood',
        'neutralMood',
        'emergencyMood',
        'pendingReferrals',
        'urgentAssessments',
        'pendingAssessmentReviews',
        'referralStatusCounts',
        'facultyMoodSummary',
        'recentHighRiskMoods',
        'recentReferrals',
        'studentsNeedingAttention'
    ));
    }
}
