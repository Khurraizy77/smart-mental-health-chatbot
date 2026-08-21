<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us - Smart Mental Health Chatbot</title>

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
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('about') }}">About Us</a>
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

<main class="wb-section">
    <div class="container">
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-7">
                <span class="wb-badge wb-badge-positive d-inline-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-heart-pulse"></i>
                    About the project
                </span>

                <h1 class="display-5 fw-bold mb-3">
                    A supportive digital space for student wellbeing.
                </h1>

                <p class="lead wb-muted mb-0">
                    Smart Mental Health Chatbot is a final year project created to help students reflect on their emotions, track mood patterns, and request counselling support when they need help beyond a chatbot.
                </p>
            </div>

            <div class="col-lg-5">
                <div class="wb-card p-4 text-center">
                    <img src="/images/smart-mental-health-logo.svg"
                         class="wb-about-logo mb-3"
                         alt="Smart Mental Health Chatbot logo">
                    <h2 class="h4 fw-bold mb-2">Created by Khurraizy Khuzainol</h2>
                    <p class="wb-muted mb-0">
                        Built as a student-centred wellbeing system with AI chat, sentiment analysis, mood tracking, reports, and counselling referral support.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="wb-panel h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-chat-heart"></i>
                    </div>
                    <h2 class="h5 fw-bold">Supportive Chat</h2>
                    <p class="wb-muted mb-0">
                        Students can write what they feel and receive calm, supportive guidance that encourages reflection and healthy coping steps.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wb-panel h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-activity"></i>
                    </div>
                    <h2 class="h5 fw-bold">Mood Tracking</h2>
                    <p class="wb-muted mb-0">
                        Mood and sentiment records help students and administrators understand wellbeing patterns through simple dashboards and PDF reports.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wb-panel h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-person-hearts"></i>
                    </div>
                    <h2 class="h5 fw-bold">Counselling Support</h2>
                    <p class="wb-muted mb-0">
                        Students can submit counselling requests so a counsellor or administrator can review and follow up with appropriate support.
                    </p>
                </div>
            </div>
        </div>

        <div class="wb-card mt-5 p-4 p-md-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3">Important note</h2>
                    <p class="wb-muted mb-0">
                        This system supports self-reflection and student wellbeing awareness. It does not replace professional diagnosis, medical treatment, emergency services, or counselling from qualified professionals.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="/chat" class="btn btn-wb-primary mb-2">Open Chat</a>
                    <a href="/dashboard" class="btn btn-wb-outline mb-2">Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</main>

@include('components.site-footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
