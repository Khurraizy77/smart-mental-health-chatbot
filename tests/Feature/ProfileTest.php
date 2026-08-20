<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response
        ->assertOk()
        ->assertSee('Profile Information')
        ->assertSee('Email')
        ->assertSee('Save');
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertNotSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('student profile details can be updated except name', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'role' => 'student',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Changed Name',
            'email' => 'student@example.com',
            'matric_no' => 'B032399999',
            'program' => 'Software Engineering',
            'faculty' => 'FTMK',
            'year_of_study' => 3,
            'phone_number' => '0123456789',
            'gender' => 'Female',
            'date_of_birth' => '2001-04-12',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Original Name', $user->name);
    $this->assertSame('student@example.com', $user->email);
    $this->assertSame('B032399999', $user->studentProfile->matric_no);
    $this->assertSame('Female', $user->studentProfile->gender);
});

test('student profile rejects invalid matric number and phone number', function () {
    $user = User::factory()->create([
        'role' => 'student',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'email' => 'student@example.com',
            'matric_no' => 'B0323101711',
            'phone_number' => '111111111111',
        ]);

    $response->assertSessionHasErrors(['matric_no', 'phone_number']);
});

test('admin profile details can be updated except name', function () {
    $user = User::factory()->create([
        'name' => 'Original Admin',
        'role' => 'admin',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Changed Admin',
            'email' => 'admin@example.com',
            'staff_no' => 'ADM001',
            'department' => 'Student Affairs',
            'position' => 'Counselling Coordinator',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Original Admin', $user->name);
    $this->assertSame('admin@example.com', $user->email);
    $this->assertSame('ADM001', $user->adminProfile->staff_no);
    $this->assertSame('Student Affairs', $user->adminProfile->department);
    $this->assertSame('Counselling Coordinator', $user->adminProfile->position);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
