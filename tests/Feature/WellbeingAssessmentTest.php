<?php

use App\Mail\CounsellingReferralRequested;
use App\Models\CounselingService;
use App\Models\Referral;
use App\Models\User;
use App\Models\WellbeingAssessment;
use Illuminate\Support\Facades\Mail;

function assessmentAnswers(int $value = 2): array
{
    return [
        'sleep' => $value,
        'study_pressure' => $value,
        'focus' => $value,
        'mood' => $value,
        'support' => $value,
        'relaxing' => $value,
        'daily_tasks' => $value,
        'coping' => $value,
    ];
}

test('student can submit wellbeing assessment even when AI is unavailable', function () {
    config(['services.deepseek.key' => null]);

    $student = User::factory()->create(['role' => 'student']);

    $response = $this->actingAs($student)->post(route('assessments.store'), [
        'answers' => assessmentAnswers(2),
        'stress_reason' => 'Assignment pressure and difficulty resting.',
        'preferred_support' => 'Counselling session',
        'urgent_support' => 'no',
    ]);

    $assessment = WellbeingAssessment::where('user_id', $student->id)->first();

    expect($assessment)
        ->not->toBeNull()
        ->total_score->toBe(16)
        ->wellbeing_level->toBe('Mild Stress')
        ->ai_wellbeing_summary->toBeNull();

    $response
        ->assertRedirect(route('assessments.show', $assessment))
        ->assertSessionHas('warning');
});

test('student can view counselling assessment form', function () {
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)
        ->get(route('counseling.index'))
        ->assertOk()
        ->assertSee('Counsellor Wellbeing Assessment Form')
        ->assertSee('Submit Assessment');
});

test('student counselling request emails assigned counsellor', function () {
    Mail::fake();

    $student = User::factory()->create(['role' => 'student']);
    $student->studentProfile()->create([
        'matric_no' => 'B032310177',
        'program' => 'BITD',
        'faculty' => 'FTMK',
        'phone_number' => '01123456789',
        'gender' => 'Male',
    ]);

    $service = CounselingService::create([
        'service_name' => 'Counsellor Appointment Support',
        'contact_info' => 'appointment@university.test',
        'counsellor_email' => 'appointment@university.test',
        'description' => 'Send a request to an assigned counsellor.',
    ]);

    $response = $this->actingAs($student)->post(route('referrals.store'), [
        'service_id' => $service->service_id,
        'notes' => 'I would like to arrange a counselling appointment.',
    ]);

    $referral = Referral::where('user_id', $student->id)->first();

    expect($referral)
        ->not->toBeNull()
        ->status->toBe('pending');

    Mail::assertSent(CounsellingReferralRequested::class, function ($mail) use ($referral) {
        return $mail->hasTo('appointment@university.test')
            && $mail->referral->is($referral);
    });

    $response
        ->assertRedirect(route('counseling.index'))
        ->assertSessionHas('success');
});

test('student can view saved assessment result page', function () {
    $student = User::factory()->create(['role' => 'student']);

    $assessment = WellbeingAssessment::create([
        'user_id' => $student->id,
        'answers' => assessmentAnswers(2),
        'total_score' => 16,
        'wellbeing_level' => 'Mild Stress',
        'urgent_support' => false,
        'ai_wellbeing_summary' => 'Your responses suggest manageable but noticeable stress.',
        'ai_priority_level' => 'Normal',
        'review_status' => 'pending',
    ]);

    $this->actingAs($student)
        ->get(route('assessments.show', $assessment))
        ->assertOk()
        ->assertSee('Assessment Result')
        ->assertSee('Your responses suggest manageable but noticeable stress.');
});

test('urgent assessment is flagged without normal AI dependency', function () {
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)->post(route('assessments.store'), [
        'answers' => assessmentAnswers(4),
        'stress_reason' => 'I may hurt myself.',
        'preferred_support' => 'Emergency support',
        'urgent_support' => 'yes',
    ]);

    $assessment = WellbeingAssessment::where('user_id', $student->id)->first();

    expect($assessment)
        ->urgent_support->toBeTrue()
        ->wellbeing_level->toBe('Urgent Support Needed')
        ->ai_priority_level->toBe('Urgent')
        ->review_status->toBe('flagged');
});

test('assessment stress reason can trigger emergency handling', function () {
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)->post(route('assessments.store'), [
        'answers' => assessmentAnswers(1),
        'stress_reason' => "Sometimes I don't want to live.",
        'preferred_support' => 'Emergency support',
        'urgent_support' => 'no',
    ]);

    $assessment = WellbeingAssessment::where('user_id', $student->id)->first();

    expect($assessment)
        ->urgent_support->toBeTrue()
        ->ai_priority_level->toBe('Urgent')
        ->review_status->toBe('flagged');
});

test('admin can review wellbeing assessment notes', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $student = User::factory()->create(['role' => 'student']);

    $assessment = WellbeingAssessment::create([
        'user_id' => $student->id,
        'answers' => assessmentAnswers(3),
        'total_score' => 24,
        'wellbeing_level' => 'Moderate Stress',
        'urgent_support' => false,
        'review_status' => 'pending',
    ]);

    $this->actingAs($admin)
        ->from(route('admin.assessments.index'))
        ->put(route('admin.assessments.update', $assessment), [
        'review_status' => 'reviewed',
        'counsellor_notes' => 'Follow up during counselling session.',
    ])->assertRedirect(route('admin.assessments.index'));

    expect($assessment->fresh())
        ->review_status->toBe('reviewed')
        ->counsellor_notes->toBe('Follow up during counselling session.')
        ->reviewed_by->toBe($admin->id)
        ->reviewed_at->not->toBeNull();
});

test('admin can view assessment review page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.assessments.index'))
        ->assertOk()
        ->assertSee('Wellbeing Assessments');
});

test('admin can filter assessment review page by priority', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $urgentStudent = User::factory()->create(['role' => 'student', 'name' => 'Urgent Student']);
    $normalStudent = User::factory()->create(['role' => 'student', 'name' => 'Normal Student']);

    WellbeingAssessment::create([
        'user_id' => $urgentStudent->id,
        'answers' => assessmentAnswers(4),
        'total_score' => 32,
        'wellbeing_level' => 'Urgent Support Needed',
        'urgent_support' => true,
        'ai_priority_level' => 'Urgent',
        'review_status' => 'flagged',
    ]);

    WellbeingAssessment::create([
        'user_id' => $normalStudent->id,
        'answers' => assessmentAnswers(0),
        'total_score' => 0,
        'wellbeing_level' => 'Stable / Low Stress',
        'urgent_support' => false,
        'ai_priority_level' => 'Normal',
        'review_status' => 'pending',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.assessments.index', ['priority' => 'Urgent']))
        ->assertOk()
        ->assertSee('Urgent Student')
        ->assertDontSee('Normal Student');
});
