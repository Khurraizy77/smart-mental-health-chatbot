<?php

use App\Models\User;

test('guest is redirected from wellbeing guide to login', function () {
    $this->get(route('wellbeing.guide'))
        ->assertRedirect(route('login'));
});

test('student can view wellbeing guide', function () {
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)
        ->get(route('wellbeing.guide'))
        ->assertOk()
        ->assertSee('Practical steps for stress, tension, and difficult feelings.')
        ->assertSee('Request Counselling');
});

test('admin can view wellbeing guide', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('wellbeing.guide'))
        ->assertOk()
        ->assertSee('Wellbeing tutorial')
        ->assertDontSee('Request Counselling');
});
