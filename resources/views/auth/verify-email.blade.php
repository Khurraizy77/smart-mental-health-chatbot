<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify Email - Smart Mental Health Chatbot</title>

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

                <div class="wb-card p-4 p-md-5 text-center">
                    <div class="wb-icon mx-auto mb-3">
                        <i class="bi bi-patch-check"></i>
                    </div>

                    <h2 class="fw-bold mb-3">
                        Verify Your Email
                    </h2>

                    <p class="wb-muted">
                        Please verify your email before continuing.
                    </p>

                    @if(session('status') == 'verification-link-sent')
                        <div class="alert alert-success">
                            A new verification link has been sent.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <button class="btn btn-wb-primary">
                            Resend Verification Email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
