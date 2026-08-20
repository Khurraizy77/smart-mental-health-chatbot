<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Smart Mental Health Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
</head>

<body class="wb-body">

<main class="wb-auth-shell">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <a href="/" class="text-decoration-none d-inline-block mb-4">
                    <img src="/images/smart-mental-health-logo.svg"
                         alt="Smart Mental Health Chatbot"
                         class="wb-logo-wordmark">
                </a>

                <h1 class="display-5 fw-bold mb-3">
                    Welcome back.
                </h1>

                <p class="lead wb-muted mb-0">
                    Continue to your wellbeing dashboard, chat support, and mood history.
                </p>
            </div>

            <div class="col-lg-5 ms-lg-auto">
                <div class="wb-card p-4 p-md-5">
                    <div class="mb-4">
                        <div class="wb-icon mb-3">
                            <i class="bi bi-person-heart"></i>
                        </div>

                        <h2 class="fw-bold mb-2">
                            Login
                        </h2>

                        <p class="wb-muted mb-0">
                            Use your account to continue.
                        </p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(session('google_error'))
                        <div class="alert alert-warning">
                            {{ session('google_error') }}
                        </div>
                    @endif

                    <a href="{{ route('google.redirect') }}"
                       class="btn btn-wb-outline btn-lg w-100 mb-4">
                        <i class="bi bi-google me-2"></i>
                        Continue with Google
                    </a>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <hr class="flex-grow-1">
                        <span class="small wb-muted">or use email</span>
                        <hr class="flex-grow-1">
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required
                                   autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="remember"
                                       name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            <a href="{{ route('password.request') }}"
                               class="small text-decoration-none">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit"
                                class="btn btn-wb-primary btn-lg w-100">
                            Login
                        </button>

                        <div class="text-center mt-4">
                            <a href="{{ route('register') }}"
                               class="text-decoration-none">
                                Create an account
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
