<?php

use App\Models\User;

test('student cannot access admin management pages', function () {
    $student = User::factory()->create([
        'role' => 'student',
    ]);

    $this->actingAs($student)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('admin can access chatbot and mood tracking', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin)
        ->get(route('chat.index'))
        ->assertOk();

    $this->actingAs($admin)
        ->get('/mood')
        ->assertOk();
});

test('admin cannot access student counselling referral page', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin)
        ->get(route('counseling.index'))
        ->assertForbidden();
});

test('admin dashboard has working navigation actions', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Chatbot')
        ->assertSee('Mood Tracking')
        ->assertSee('Logout');
});

test('generic dashboard sends admins to admin dashboard', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertRedirect(route('admin.dashboard'));
});

test('startup page sends students to student dashboard after login', function () {
    $student = User::factory()->create([
        'role' => 'student',
    ]);

    $this->actingAs($student)
        ->get('/')
        ->assertRedirect(route('student.dashboard'));
});

test('startup page sends admins to admin dashboard after login', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin)
        ->get('/')
        ->assertRedirect(route('admin.dashboard'));
});
