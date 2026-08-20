<?php

use App\Models\User;

test('student can download wellbeing analysis pdf', function () {
    $user = User::factory()->create([
        'role' => 'student',
    ]);

    $user->studentProfile()->create([
        'matric_no' => 'B032399999',
        'program' => 'Software Engineering',
        'faculty' => 'FTMK',
        'year_of_study' => 3,
    ]);

    $response = $this->actingAs($user)->get(route('reports.user'));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});

test('admin can download system analysis pdf', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.analysis'));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});
