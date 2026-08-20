<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'matric_no' => ['nullable', 'regex:/^[A-Z][0-9]{9}$/'],
            'program' => ['nullable', 'string', 'max:255'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'year_of_study' => ['nullable', 'integer', 'min:1', 'max:10'],
            'phone_number' => ['nullable', 'regex:/^[0-9]{10,11}$/'],
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],
            'date_of_birth' => ['nullable', 'date'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'matric_no.regex' => 'The matric number must use one capital letter followed by 9 digits, for example B032310177.',
            'phone_number.regex' => 'The phone number must contain 10 to 11 digits only, for example 0115925326.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        $user->studentProfile()->create($request->only([
            'matric_no',
            'program',
            'faculty',
            'year_of_study',
            'phone_number',
            'gender',
            'date_of_birth',
        ]));

        event(new Registered($user));

        return redirect()
            ->route('login')
            ->with('status', 'Registration successful. Please log in to continue.');
    }
}
