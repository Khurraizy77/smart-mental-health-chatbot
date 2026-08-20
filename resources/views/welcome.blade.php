<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Smart Mental Health Chatbot with Sentiment Analysis</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
</head>

<body class="wb-body">

<nav class="navbar navbar-expand-lg wb-nav sticky-top">
    <div class="container">
        <a class="navbar-brand wb-brand" href="/">
            Smart Mental Health Chatbot
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/chat">Chat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mood">Mood Tracking</a>
                </li>
                @auth
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-wb-primary btn-sm" href="/chat">Open Support Chat</a>
                    </li>
                @else
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-wb-primary btn-sm" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<section class="wb-hero">
    <div class="container py-5">
        <img src="/images/smart-mental-health-logo.svg"
             alt="Smart Mental Health Chatbot"
             class="wb-logo-wordmark mb-4 bg-white rounded-3 p-2">

        <span class="wb-badge wb-badge-positive d-inline-flex align-items-center gap-2 mb-4">
            <i class="bi bi-heart-pulse"></i>
            Student wellbeing support
        </span>

        <h1 class="display-3 mb-4">
            Smart Mental Health Chatbot with Sentiment Analysis
        </h1>

        <p class="lead mb-4">
            A gentle space for students to talk, track emotional patterns, and find small next steps when life feels heavy.
        </p>

        <div class="d-flex flex-wrap gap-3">
            <a href="/chat" class="btn btn-wb-primary btn-lg">
                <i class="bi bi-chat-heart me-2"></i>
                Start Chat
            </a>

            <a href="/mood" class="btn btn-light btn-lg">
                <i class="bi bi-activity me-2"></i>
                View Mood
            </a>
        </div>
    </div>
</section>

<section class="wb-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="wb-panel h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5 class="fw-bold">Supportive Chat</h5>
                    <p class="wb-muted mb-0">
                        Short, caring responses that help students slow down and name what they are feeling.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wb-panel h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <h5 class="fw-bold">Mood Awareness</h5>
                    <p class="wb-muted mb-0">
                        Simple tracking helps show emotional patterns without making the experience feel complicated.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wb-panel h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold">Safety First</h5>
                    <p class="wb-muted mb-0">
                        The system highlights emergency language and encourages real-world support when risk appears.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
