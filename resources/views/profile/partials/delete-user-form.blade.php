<section class="wb-card p-4">
    <div class="d-flex align-items-start gap-3 mb-4">
        <div class="wb-icon" style="background:#f6dfda;color:var(--wb-coral-dark);">
            <i class="bi bi-exclamation-triangle"></i>
        </div>

        <div>
            <h2 class="h5 fw-bold mb-1">{{ __('Delete Account') }}</h2>
            <p class="wb-muted mb-0">
                {{ __('This permanently deletes your account and related records.') }}
            </p>
        </div>
    </div>

    <form method="post"
          action="{{ route('profile.destroy') }}"
          onsubmit="return confirm('Delete your account permanently? This cannot be undone.');">
        @csrf
        @method('delete')

        <div class="mb-3">
            <label class="form-label fw-semibold" for="delete_account_password">
                {{ __('Confirm Password') }}
            </label>
            <input id="delete_account_password"
                   name="password"
                   type="password"
                   class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif"
                   placeholder="{{ __('Enter your password') }}">
            @foreach($errors->userDeletion->get('password') as $message)
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-wb-danger w-100">
            <i class="bi bi-trash me-2"></i>
            {{ __('Delete Account') }}
        </button>
    </form>
</section>
