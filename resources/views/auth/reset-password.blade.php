<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password - Smart Mental Health Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
</head>

<body class="wb-body">

<main class="wb-auth-shell">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <a href="/" class="text-decoration-none d-block mb-4">
                    <img src="/images/smart-mental-health-logo.svg"
                         alt="Smart Mental Health Chatbot"
                         class="wb-logo-wordmark mx-auto">
                </a>

                <div class="wb-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="wb-icon mx-auto mb-3">
                            <i class="bi bi-key"></i>
                        </div>

                        <h2 class="fw-bold">
                            Reset Password
                        </h2>

                        <p class="wb-muted mb-0">
                            Choose a new password for your account.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ request()->route('token') }}">

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', request('email')) }}"
                                   required
                                   autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">New Password</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required
                                   autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control"
                                   required
                                   autocomplete="new-password">
                        </div>

                        <button class="btn btn-wb-primary btn-lg w-100">
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
