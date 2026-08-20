<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $request->user()->load(['studentProfile', 'adminProfile']);

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->fill([
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === 'student') {
            $user->studentProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'matric_no' => $validated['matric_no'] ?? null,
                    'program' => $validated['program'] ?? null,
                    'faculty' => $validated['faculty'] ?? null,
                    'year_of_study' => $validated['year_of_study'] ?? null,
                    'phone_number' => $validated['phone_number'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                ]
            );
        }

        if ($user->role === 'admin') {
            $user->adminProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'staff_no' => $validated['staff_no'] ?? null,
                    'department' => $validated['department'] ?? null,
                    'position' => $validated['position'] ?? null,
                ]
            );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
