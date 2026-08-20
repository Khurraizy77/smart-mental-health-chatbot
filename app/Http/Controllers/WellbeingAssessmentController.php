<?php

namespace App\Http\Controllers;

use App\Models\WellbeingAssessment;
use App\Services\SafetyKeywordService;
use App\Services\WellbeingAssessmentAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class WellbeingAssessmentController extends Controller
{
    private const QUESTIONS = [
        'sleep' => 'I have had difficulty sleeping or resting.',
        'study_pressure' => 'Study or assignment pressure has felt difficult to manage.',
        'focus' => 'I have had difficulty concentrating on my tasks.',
        'mood' => 'My mood has felt low, heavy, or easily upset.',
        'support' => 'I have felt alone or unsupported.',
        'relaxing' => 'I have found it difficult to relax.',
        'daily_tasks' => 'My wellbeing has affected my daily activities.',
        'coping' => 'I feel unsure how to cope with what I am facing.',
    ];

    public function __construct(
        private readonly WellbeingAssessmentAnalysisService $analysisService,
        private readonly SafetyKeywordService $safetyKeywords
    )
    {
    }

    public static function questions(): array
    {
        return self::QUESTIONS;
    }

    public function store(Request $request): RedirectResponse
    {
        $answerRules = collect(array_keys(self::QUESTIONS))
            ->mapWithKeys(fn ($key) => ["answers.{$key}" => ['required', 'integer', 'between:0,4']])
            ->all();

        $validated = $request->validate([
            ...$answerRules,
            'stress_reason' => ['nullable', 'string', 'max:1200'],
            'preferred_support' => ['nullable', 'string', 'max:120'],
            'urgent_support' => ['required', 'in:yes,no'],
        ]);

        $answers = collect($validated['answers'])
            ->map(fn ($value) => (int) $value)
            ->all();

        $totalScore = array_sum($answers);
        $urgentSupport = $validated['urgent_support'] === 'yes'
            || $this->safetyKeywords->containsEmergencyLanguage($validated['stress_reason'] ?? '');

        $assessment = WellbeingAssessment::create([
            'user_id' => Auth::id(),
            'answers' => $answers,
            'total_score' => $totalScore,
            'wellbeing_level' => $this->wellbeingLevel($totalScore, $urgentSupport),
            'stress_reason' => $validated['stress_reason'] ?? null,
            'preferred_support' => $validated['preferred_support'] ?? null,
            'urgent_support' => $urgentSupport,
            'review_status' => $urgentSupport ? 'flagged' : 'pending',
        ]);

        try {
            $analysis = $this->analysisService->analyse($assessment);

            if ($analysis) {
                $assessment->update($analysis);

                return redirect()
                    ->route('assessments.show', $assessment)
                    ->with('success', 'Your assessment has been saved and analysed.');
            }
        } catch (\Throwable $exception) {
            Log::warning('Wellbeing assessment AI analysis failed.', [
                'assessment_id' => $assessment->id,
                'message' => $exception->getMessage(),
            ]);
        }

        return redirect()
            ->route('assessments.show', $assessment)
            ->with('warning', 'Your assessment has been saved. AI analysis is temporarily unavailable. Please try again later.');
    }

    public function show(WellbeingAssessment $assessment): View
    {
        abort_unless($assessment->user_id === Auth::id(), 404);

        return view('counseling.assessment-result', [
            'assessment' => $assessment,
            'questions' => self::QUESTIONS,
        ]);
    }

    private function wellbeingLevel(int $score, bool $urgent): string
    {
        if ($urgent) {
            return 'Urgent Support Needed';
        }

        return match (true) {
            $score >= 29 => 'High Stress',
            $score >= 21 => 'Moderate Stress',
            $score >= 11 => 'Mild Stress',
            default => 'Stable / Low Stress',
        };
    }
}
