<section class="wb-card p-4">
    <div class="d-flex align-items-start gap-3 mb-4">
        <div class="wb-icon">
            <i class="bi bi-shield-lock"></i>
        </div>

        <div>
            <h2 class="h5 fw-bold mb-1">{{ __('Update Password') }}</h2>
            <p class="wb-muted mb-0">
                {{ __('Use a long password to keep your account secure.') }}
            </p>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label class="form-label fw-semibold" for="update_password_current_password">
                {{ __('Current Password') }}
            </label>
            <input id="update_password_current_password"
                   name="current_password"
                   type="password"
                   class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif"
                   autocomplete="current-password">
            @foreach($errors->updatePassword->get('current_password') as $message)
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @endforeach
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold" for="update_password_password">
                {{ __('New Password') }}
            </label>
            <input id="update_password_password"
                   name="password"
                   type="password"
                   class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif"
                   autocomplete="new-password">
            @foreach($errors->updatePassword->get('password') as $message)
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @endforeach
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold" for="update_password_password_confirmation">
                {{ __('Confirm Password') }}
            </label>
            <input id="update_password_password_confirmation"
                   name="password_confirmation"
                   type="password"
                   class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                   autocomplete="new-password">
            @foreach($errors->updatePassword->get('password_confirmation') as $message)
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-wb-primary w-100">
            <i class="bi bi-key me-2"></i>
            {{ __('Update Password') }}
        </button>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success py-2 px-3 mt-3 mb-0">
                {{ __('Password updated.') }}
            </div>
        @endif
    </form>
</section>
