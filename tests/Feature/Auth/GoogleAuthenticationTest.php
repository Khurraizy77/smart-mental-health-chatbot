<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

function fakeGoogleUser(string $id, string $email, string $name): object
{
    return new class($id, $email, $name) {
        public function __construct(
            private readonly string $id,
            private readonly string $email,
            private readonly string $name,
        ) {
        }

        public function getId(): string
        {
            return $this->id;
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function getName(): string
        {
            return $this->name;
        }
    };
}

test('google callback creates a student account', function () {
    $provider = Mockery::mock();
    $provider->shouldReceive('user')
        ->once()
        ->andReturn(fakeGoogleUser('google-123', 'student@example.com', 'Google Student'));

    Socialite::shouldReceive('driver')
        ->with('google')
        ->once()
        ->andReturn($provider);

    $response = $this->get(route('google.callback'));

    $user = User::where('email', 'student@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->role)->toBe('student');
    expect($user->google_id)->toBe('google-123');
    expect($user->studentProfile)->not->toBeNull();
    expect(Auth::id())->toBe($user->id);

    $response->assertRedirect(route('student.dashboard', absolute: false));
});

test('google redirect shows setup error when credentials are missing', function () {
    config([
        'services.google.client_id' => null,
        'services.google.client_secret' => null,
    ]);

    $this->get(route('google.redirect'))
        ->assertRedirect(route('login'))
        ->assertSessionHas('google_error');
});

test('google callback merges an existing email account', function () {
    $existing = User::factory()->create([
        'email' => 'existing@example.com',
        'google_id' => null,
        'role' => 'student',
    ]);

    $provider = Mockery::mock();
    $provider->shouldReceive('user')
        ->once()
        ->andReturn(fakeGoogleUser('google-456', 'existing@example.com', 'Existing Student'));

    Socialite::shouldReceive('driver')
        ->with('google')
        ->once()
        ->andReturn($provider);

    $response = $this->get(route('google.callback'));

    $existing->refresh();

    expect(User::where('email', 'existing@example.com')->count())->toBe(1);
    expect($existing->google_id)->toBe('google-456');
    expect($existing->role)->toBe('student');
    expect($existing->studentProfile)->not->toBeNull();
    expect(Auth::id())->toBe($existing->id);

    $response->assertRedirect(route('student.dashboard', absolute: false));
});

test('google callback keeps an existing admin account role', function () {
    $existing = User::factory()->create([
        'email' => 'admin@example.com',
        'google_id' => null,
        'role' => 'admin',
    ]);

    $provider = Mockery::mock();
    $provider->shouldReceive('user')
        ->once()
        ->andReturn(fakeGoogleUser('google-admin-789', 'admin@example.com', 'Existing Admin'));

    Socialite::shouldReceive('driver')
        ->with('google')
        ->once()
        ->andReturn($provider);

    $response = $this->get(route('google.callback'));

    $existing->refresh();

    expect(User::where('email', 'admin@example.com')->count())->toBe(1);
    expect($existing->google_id)->toBe('google-admin-789');
    expect($existing->role)->toBe('admin');
    expect($existing->adminProfile)->not->toBeNull();
    expect(Auth::id())->toBe($existing->id);

    $response->assertRedirect(route('admin.dashboard', absolute: false));
});
