<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wellbeing Guide - Smart Mental Health Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
</head>

<body class="wb-body">

<nav class="navbar navbar-expand-lg wb-nav sticky-top">
    <div class="container">
        <a class="navbar-brand wb-brand" href="/dashboard">
            Smart Mental Health Chatbot
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
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
                    <a class="nav-link active" href="{{ route('wellbeing.guide') }}">Guide</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About Us</a>
                </li>
                @if(Auth::user()->role === 'student')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('counseling.index') }}">Counselling</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<main class="wb-section">
    <div class="container">
        <div class="row align-items-center g-4 mb-5">
            <div class="col-lg-7">
                <span class="wb-badge wb-badge-positive d-inline-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-compass"></i>
                    Wellbeing tutorial
                </span>

                <h1 class="fw-bold mb-3">
                    Practical steps for stress, tension, and difficult feelings.
                </h1>

                <p class="lead wb-muted mb-0">
                    Use this page when you need a simple next step. It is for support and self-reflection, not a diagnosis or replacement for a counsellor.
                </p>
            </div>

            <div class="col-lg-5">
                <div class="wb-card wb-ai-card p-4">
                    <h2 class="h5 fw-bold mb-3">Start here if you feel overwhelmed</h2>
                    <ol class="wb-guide-list mb-4">
                        <li>Put both feet on the floor.</li>
                        <li>Take one slow breath out.</li>
                        <li>Name what you feel without judging it.</li>
                        <li>Choose one small action from this page.</li>
                    </ol>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="/chat" class="btn btn-wb-primary">
                            <i class="bi bi-chat-heart me-2"></i>
                            Open Chat
                        </a>
                        @if(Auth::user()->role === 'student')
                            <a href="{{ route('counseling.index') }}" class="btn btn-wb-outline">
                                Counselling
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="wb-card p-4 mb-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <span class="wb-badge wb-badge-emergency d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        Safety first
                    </span>

                    <h2 class="h4 fw-bold mb-2">If you may hurt yourself or someone else</h2>
                    <p class="wb-muted mb-0">
                        Do not wait for a chatbot response. Move near a trusted person, contact emergency help, or go to a nearby hospital immediately.
                    </p>
                </div>

                <div class="col-lg-4">
                    <div class="d-grid gap-2">
                        <div class="wb-recommendation-item">
                            <i class="bi bi-telephone"></i>
                            <span><strong>Malaysia Emergency:</strong> 999</span>
                        </div>
                        <div class="wb-recommendation-item">
                            <i class="bi bi-heart-pulse"></i>
                            <span><strong>Talian Kasih:</strong> 15999</span>
                        </div>
                        <div class="wb-recommendation-item">
                            <i class="bi bi-chat-heart"></i>
                            <span><strong>Befrienders KL:</strong> 03-7627 2929</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h2 class="h4 fw-bold mb-2">Quick calming steps</h2>
            <p class="wb-muted mb-0">Pick one. You do not need to do everything at once.</p>
        </div>

        <div class="row g-4 mb-5">
            @foreach($quickSteps as $step)
                <div class="col-md-6 col-xl-3">
                    <div class="wb-card h-100 p-4">
                        <div class="wb-icon mb-3">
                            <i class="bi {{ $step['icon'] }}"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">{{ $step['title'] }}</h3>
                        <p class="wb-muted mb-0">{{ $step['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mb-4">
            <h2 class="h4 fw-bold mb-2">Choose what fits your situation</h2>
            <p class="wb-muted mb-0">These are small coping actions for common student wellbeing problems.</p>
        </div>

        <div class="row g-4">
            @foreach($situations as $situation)
                <div class="col-lg-6">
                    <div class="wb-card h-100 p-4">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="wb-icon">
                                <i class="bi {{ $situation['icon'] }}"></i>
                            </div>
                            <div>
                                <span class="wb-badge {{ $situation['badge'] }} d-inline-flex align-items-center gap-2 mb-2">
                                    {{ $situation['label'] }}
                                </span>
                                <p class="wb-muted mb-0">{{ $situation['description'] }}</p>
                            </div>
                        </div>

                        <ol class="wb-guide-list mb-0">
                            @foreach($situation['steps'] as $step)
                                <li>{{ $step }}</li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="wb-panel mt-5 p-4 p-md-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3">When to ask for more help</h2>
                    <p class="wb-muted mb-3">
                        Ask for counselling or trusted support if difficult feelings last many days, affect sleep or study, make daily tasks hard, or make you feel unsafe.
                    </p>
                    <p class="small wb-muted mb-0">
                        This guide uses general wellbeing strategies inspired by public mental health guidance from WHO, CDC, and NIMH.
                    </p>
                </div>

                <div class="col-lg-4 text-lg-end">
                    @if(Auth::user()->role === 'student')
                        <a href="{{ route('counseling.index') }}" class="btn btn-wb-primary mb-2">
                            Request Counselling
                        </a>
                    @endif
                    <a href="/dashboard#hospital-support" class="btn btn-wb-outline mb-2">
                        Find Hospital
                    </a>
                    <a href="/chat" class="btn btn-wb-outline mb-2">
                        Talk Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

@include('components.site-footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
