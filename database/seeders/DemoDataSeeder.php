<?php

namespace Database\Seeders;

use App\Models\ChatSession;
use App\Models\CounselingService;
use App\Models\Message;
use App\Models\MoodTracking;
use App\Models\Referral;
use App\Models\SentimentAnalysis;
use App\Models\User;
use App\Models\WellbeingAssessment;
use App\Services\WellbeingRecommendationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        app(WellbeingRecommendationService::class)->ensureDefaults();

        $admin = User::firstOrCreate(
            ['email' => 'admin@smartheal.test'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $admin->adminProfile()->firstOrCreate([
            'user_id' => $admin->id,
        ], [
            'staff_no' => 'ADM001',
            'department' => 'Student Affairs',
            'position' => 'Counselling Coordinator',
        ]);

        $students = [
            [
                'name' => 'Ahmad Maslan',
                'email' => 'ahmad@student.test',
                'matric_no' => 'B032310177',
                'program' => 'BITD',
                'faculty' => 'FTMK',
                'moods' => ['neutral', 'negative', 'negative', 'emergency'],
                'assessment_priority' => 'Urgent',
                'assessment_score' => 31,
            ],
            [
                'name' => 'Siti Khadijah',
                'email' => 'siti@student.test',
                'matric_no' => 'B032310178',
                'program' => 'BITM',
                'faculty' => 'FTMK',
                'moods' => ['positive', 'neutral', 'positive', 'neutral'],
                'assessment_priority' => 'Normal',
                'assessment_score' => 9,
            ],
            [
                'name' => 'Nur Aina',
                'email' => 'aina@student.test',
                'matric_no' => 'B032310179',
                'program' => 'BITS',
                'faculty' => 'FTMK',
                'moods' => ['neutral', 'negative', 'neutral', 'negative'],
                'assessment_priority' => 'High',
                'assessment_score' => 27,
            ],
        ];

        $service = CounselingService::updateOrCreate(
            ['service_name' => 'University Counselling Unit'],
            [
                'contact_info' => 'Student Affairs Office',
                'counsellor_email' => env('COUNSELLOR_EMAIL', 'counsellor@example.com'),
                'description' => 'Private counselling support for students who need emotional or academic wellbeing support.',
            ]
        );

        CounselingService::updateOrCreate(
            ['service_name' => 'Counsellor Appointment Support'],
            [
                'contact_info' => env('COUNSELLOR_EMAIL', 'counsellor@example.com'),
                'counsellor_email' => env('COUNSELLOR_EMAIL', 'counsellor@example.com'),
                'description' => 'Send a counselling request directly to an assigned counsellor so they can review your details and arrange an appointment.',
            ]
        );

        foreach ($students as $index => $studentData) {
            $student = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'email_verified_at' => now(),
                ]
            );

            $student->studentProfile()->firstOrCreate([
                'user_id' => $student->id,
            ], [
                'matric_no' => $studentData['matric_no'],
                'program' => $studentData['program'],
                'faculty' => $studentData['faculty'],
                'year_of_study' => 3,
                'phone_number' => '0112345678' . $index,
                'gender' => $index === 1 ? 'Female' : 'Male',
                'date_of_birth' => now()->subYears(22)->toDateString(),
            ]);

            $session = ChatSession::firstOrCreate([
                'user_id' => $student->id,
                'start_time' => now()->subDays(4),
            ]);

            $studentMessage = Message::firstOrCreate([
                'session_id' => $session->session_id,
                'sender_type' => 'student',
                'message_text' => $studentData['moods'][3] === 'emergency'
                    ? 'I feel overwhelmed and I need urgent support.'
                    : 'I want to understand my mood pattern this week.',
            ]);

            SentimentAnalysis::firstOrCreate([
                'message_id' => $studentMessage->message_id,
            ], [
                'sentiment_type' => $studentData['moods'][3],
                'confidence_score' => $studentData['moods'][3] === 'emergency' ? 1 : 0.9,
            ]);

            Message::firstOrCreate([
                'session_id' => $session->session_id,
                'sender_type' => 'chatbot',
                'message_text' => 'Thank you for sharing. Try one small grounding step and consider counselling support if this continues.',
            ]);

            foreach ($studentData['moods'] as $daysAgo => $mood) {
                MoodTracking::firstOrCreate([
                    'user_id' => $student->id,
                    'date' => now()->subDays(3 - $daysAgo)->toDateString(),
                    'mood_type' => $mood,
                ], [
                    'overall_sentiment' => $mood,
                    'mood_score' => app(WellbeingRecommendationService::class)->scoreForMood($mood),
                ]);

                app(WellbeingRecommendationService::class)->assignForMood($student->id, $mood);
            }

            WellbeingAssessment::firstOrCreate([
                'user_id' => $student->id,
                'total_score' => $studentData['assessment_score'],
            ], [
                'answers' => [
                    'sleep' => 3,
                    'study_pressure' => 4,
                    'focus' => 3,
                    'mood' => 4,
                    'support' => 3,
                    'relaxing' => 4,
                    'daily_tasks' => 3,
                    'coping' => 4,
                ],
                'wellbeing_level' => $studentData['assessment_priority'] === 'Urgent'
                    ? 'Urgent Support Needed'
                    : ($studentData['assessment_priority'] === 'High' ? 'High Stress' : 'Stable / Low Stress'),
                'stress_reason' => 'Demo data for presentation and dashboard analysis.',
                'preferred_support' => 'Counselling session',
                'urgent_support' => $studentData['assessment_priority'] === 'Urgent',
                'ai_wellbeing_summary' => 'Demo analysis: responses suggest this student may benefit from supportive follow-up.',
                'ai_main_concerns' => ['Study pressure', 'Emotional load'],
                'ai_stress_factors' => ['Assignments', 'Sleep quality'],
                'ai_suggestions' => ['Use one short grounding exercise.', 'Speak with a counsellor if symptoms continue.'],
                'ai_recommended_support' => 'Counselling support is recommended when stress affects daily activities.',
                'ai_counselling_recommendation' => 'A counselling check-in can help clarify next steps.',
                'ai_priority_level' => $studentData['assessment_priority'],
                'ai_generated_at' => now(),
                'review_status' => in_array($studentData['assessment_priority'], ['High', 'Urgent'], true) ? 'flagged' : 'pending',
            ]);

            if ($studentData['assessment_priority'] !== 'Normal') {
                Referral::firstOrCreate([
                    'user_id' => $student->id,
                    'service_id' => $service->service_id,
                ], [
                    'status' => 'pending',
                    'notes' => 'Demo referral created from high wellbeing priority.',
                ]);
            }
        }
    }
}
