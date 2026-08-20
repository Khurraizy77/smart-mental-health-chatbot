<?php

namespace App\Services;

use App\Models\WellbeingAssessment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class WellbeingAssessmentAnalysisService
{
    private const PRIORITY_LEVELS = ['Normal', 'Moderate', 'High', 'Urgent'];

    public function __construct(private readonly DeepSeekService $deepSeek)
    {
    }

    public function analyse(WellbeingAssessment $assessment): ?array
    {
        if ($assessment->urgent_support) {
            return $this->urgentResult($assessment);
        }

        $content = $this->deepSeek->chat([
            [
                'role' => 'system',
                'content' => $this->systemPrompt(),
            ],
            [
                'role' => 'user',
                'content' => json_encode([
                    'assessment_answers' => $assessment->answers,
                    'total_score' => $assessment->total_score,
                    'preliminary_wellbeing_level' => $assessment->wellbeing_level,
                    'stated_stress_reason' => $assessment->stress_reason,
                    'preferred_support' => $assessment->preferred_support,
                    'urgent_support' => $assessment->urgent_support ? 'Yes' : 'No',
                ], JSON_PRETTY_PRINT),
            ],
        ], maxTokens: 700, json: true);

        if (! $content) {
            return null;
        }

        try {
            $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            Log::warning('DeepSeek assessment response was not valid JSON.', [
                'message' => $exception->getMessage(),
                'content' => $content,
            ]);

            return null;
        }

        return $this->normalise($decoded, $assessment);
    }

    public function urgentResult(WellbeingAssessment $assessment): array
    {
        return [
            'ai_wellbeing_summary' => 'Your answers indicate that urgent support may be needed. Please contact emergency services, a qualified counsellor, campus support, or someone trusted immediately.',
            'ai_main_concerns' => ['Immediate safety concern', 'Need for real-world support'],
            'ai_stress_factors' => array_filter([$assessment->stress_reason ?: 'Urgent support selected']),
            'ai_suggestions' => [
                'Stay near another person or contact someone trusted now.',
                'Move away from anything that could be used for self-harm.',
                'Contact emergency services, a nearby hospital, campus security, or university counselling support immediately.',
            ],
            'ai_recommended_support' => 'Immediate professional or emergency support is recommended. Do not rely only on this system if you feel unsafe.',
            'ai_counselling_recommendation' => 'Request counselling support and seek urgent help from a qualified professional or emergency service.',
            'ai_priority_level' => 'Urgent',
            'ai_generated_at' => now(),
            'review_status' => 'flagged',
        ];
    }

    private function normalise(array $data, WellbeingAssessment $assessment): array
    {
        $priority = (string) Arr::get($data, 'priority_level', $this->priorityFromScore($assessment->total_score));

        if (! in_array($priority, self::PRIORITY_LEVELS, true)) {
            $priority = $this->priorityFromScore($assessment->total_score);
        }

        return [
            'ai_wellbeing_summary' => $this->safeText(Arr::get($data, 'wellbeing_summary'))
                ?: "Your responses suggest a {$assessment->wellbeing_level} level. This result is for self-reflection and support planning.",
            'ai_main_concerns' => $this->safeList(Arr::get($data, 'main_concerns')),
            'ai_stress_factors' => $this->safeList(Arr::get($data, 'stress_factors')),
            'ai_suggestions' => $this->safeList(Arr::get($data, 'suggestions')),
            'ai_recommended_support' => $this->safeText(Arr::get($data, 'recommended_support'))
                ?: 'Use the support option that feels practical today, and speak with a qualified counsellor if the concern continues.',
            'ai_counselling_recommendation' => $this->safeText(Arr::get($data, 'counselling_recommendation'))
                ?: 'Consider contacting university counselling services if this affects your daily routine.',
            'ai_priority_level' => $priority,
            'ai_generated_at' => now(),
            'review_status' => $priority === 'High' ? 'flagged' : 'pending',
        ];
    }

    private function safeList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return collect($value)
            ->map(fn ($item) => $this->safeText($item))
            ->filter()
            ->take(6)
            ->values()
            ->all();
    }

    private function safeText(mixed $value): ?string
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return null;
        }

        $text = trim((string) $value);

        $blocked = [
            '/\byou have depression\b/i' => 'your responses suggest low mood or emotional difficulty',
            '/\byou have anxiety disorder\b/i' => 'your responses suggest worry or stress',
            '/\byou are depressed\b/i' => 'you may be experiencing low mood',
            '/\byou are anxious\b/i' => 'you may be experiencing worry or stress',
            '/\bdiagnosed with\b/i' => 'showing signs of',
        ];

        foreach ($blocked as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text) ?? $text;
        }

        return $text === '' ? null : $text;
    }

    private function priorityFromScore(int $score): string
    {
        return match (true) {
            $score >= 29 => 'High',
            $score >= 21 => 'Moderate',
            default => 'Normal',
        };
    }

    private function systemPrompt(): string
    {
        return <<<'PROMPT'
You are an AI wellbeing support assistant for a university student support system.

Analyse a student's self-reported wellbeing assessment and provide a supportive, non-diagnostic interpretation.

Rules:
- You must NOT diagnose mental health conditions.
- Do not say the student has depression, anxiety disorder, or any other illness.
- Use cautious language such as "Your responses suggest", "You may be experiencing", "You reported", and "It may be helpful to".
- Base your response only on the assessment data provided.
- If serious emotional distress is indicated, recommend speaking with a qualified counsellor or mental health professional.
- Always remind the student that the result is not a medical diagnosis.

Return only valid JSON with this exact structure:
{
  "wellbeing_summary": "...",
  "main_concerns": ["..."],
  "stress_factors": ["..."],
  "suggestions": ["..."],
  "recommended_support": "...",
  "counselling_recommendation": "...",
  "priority_level": "Normal"
}

Allowed priority_level values: Normal, Moderate, High, Urgent.
PROMPT;
    }
}
