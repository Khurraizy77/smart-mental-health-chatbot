<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (blank(config('services.google.client_id')) || blank(config('services.google.client_secret'))) {
            return redirect()
                ->route('login')
                ->with('google_error', 'Google sign-in is not configured yet. Please add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in the .env file.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = $this->getGoogleUser();
        } catch (Throwable $exception) {
            Log::warning('Google sign-in callback failed.', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('login')
                ->with('google_error', $this->googleFailureMessage($exception));
        }

        $email = $this->googleEmail($googleUser);

        if ($email === null) {
            return redirect()
                ->route('login')
                ->with('google_error', 'Google did not return an email address for this account.');
        }

        $googleId = (string) $googleUser->getId();

        $user = User::query()
            ->where('email', $email)
            ->when($googleId !== '', function ($query) use ($googleId): void {
                $query->orWhere('google_id', $googleId);
            })
            ->first();

        if ($user) {
            $user->forceFill([
                'google_id' => $googleId,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: Str::before($email, '@'),
                'email' => $email,
                'google_id' => $googleId,
                'password' => Hash::make(Str::random(40)),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }

        if ($user->role === 'admin') {
            $user->adminProfile()->firstOrCreate([
                'user_id' => $user->id,
            ]);
        } else {
            $user->studentProfile()->firstOrCreate([
                'user_id' => $user->id,
            ]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        $dashboardRoute = $user->role === 'admin' ? 'admin.dashboard' : 'student.dashboard';

        return redirect()->intended(route($dashboardRoute, absolute: false));
    }

    private function getGoogleUser()
    {
        try {
            return $this->googleProvider()->user();
        } catch (InvalidStateException $exception) {
            Log::warning('Google sign-in state validation failed. Retrying callback statelessly.', [
                'message' => $exception->getMessage(),
            ]);

            return $this->googleProvider()->stateless()->user();
        }
    }

    private function googleEmail($googleUser): ?string
    {
        $email = Str::lower(trim((string) $googleUser->getEmail()));

        return $email === '' ? null : $email;
    }

    private function googleProvider()
    {
        /** @var AbstractProvider $provider */
        $provider = Socialite::driver('google');

        return $provider;
    }

    private function googleFailureMessage(Throwable $exception): string
    {
        if (! app()->environment('local')) {
            return 'Google sign-in failed. Please try again or use email login.';
        }

        return 'Google sign-in failed: '.$exception->getMessage();
    }
}
