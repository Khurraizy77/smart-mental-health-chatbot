<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Confirm Password - Smart Mental Health Chatbot</title>

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
                            <i class="bi bi-lock"></i>
                        </div>

                        <h2 class="fw-bold">
                            Confirm Password
                        </h2>

                        <p class="wb-muted mb-0">
                            Confirm your password before continuing.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-4">
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

                        <button class="btn btn-wb-primary btn-lg w-100">
                            Confirm Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
