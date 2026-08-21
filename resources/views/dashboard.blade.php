<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Smart Mental Health Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a class="nav-link active" href="/dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/chat">Chat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mood">Mood Tracking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wellbeing.guide') }}">Guide</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About Us</a>
                </li>
                @if(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">Admin</a>
                    </li>
                @endif
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="/profile">Profile</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="wb-section">
    <div class="container">
        <div class="row align-items-center g-4 mb-5">
            <div class="col-lg-7">
                <span class="wb-badge wb-badge-positive d-inline-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-sunrise"></i>
                    Your wellbeing space
                </span>

                <h1 class="fw-bold mb-3">
                    Hi {{ Auth::user()->name }}, take one small step at a time.
                </h1>

                <p class="lead wb-muted mb-0">
                    Use the chat when you need to talk, and check your mood history when you want to understand your patterns.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end">
                <a href="/chat" class="btn btn-wb-primary btn-lg me-2 mb-2">
                    <i class="bi bi-chat-heart me-2"></i>
                    Open Chat
                </a>
                <a href="/mood" class="btn btn-wb-outline btn-lg mb-2">
                    <i class="bi bi-activity me-2"></i>
                    Mood Tracking
                </a>
                <a href="{{ route('counseling.index') }}" class="btn btn-wb-outline btn-lg mb-2">
                    <i class="bi bi-heart-pulse me-2"></i>
                    Counselling
                </a>
                <a href="{{ route('wellbeing.guide') }}" class="btn btn-wb-outline btn-lg mb-2">
                    <i class="bi bi-compass me-2"></i>
                    Wellbeing Guide
                </a>
                <a href="{{ route('reports.user') }}" class="btn btn-wb-outline btn-lg mb-2">
                    <i class="bi bi-file-earmark-pdf me-2"></i>
                    PDF Report
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="wb-card wb-stat h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <p class="wb-muted mb-1">Total Chats</p>
                    <h2 class="fw-bold mb-0">{{ $totalChats ?? 0 }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="wb-card wb-stat positive h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-emoji-smile"></i>
                    </div>
                    <p class="wb-muted mb-1">Positive Mood</p>
                    <h2 class="fw-bold mb-0">{{ $positiveMood ?? 0 }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="wb-card wb-stat negative h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-cloud-rain"></i>
                    </div>
                    <p class="wb-muted mb-1">Difficult Mood</p>
                    <h2 class="fw-bold mb-0">{{ $negativeMood ?? 0 }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="wb-card wb-stat warning h-100 p-4">
                    <div class="wb-icon mb-3">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <p class="wb-muted mb-1">Recommendations</p>
                    <h2 class="fw-bold mb-0">{{ $recommendationCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="wb-card mt-4 p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <span class="wb-badge {{ ($needsAttention ?? false) ? 'wb-badge-emergency' : 'wb-badge-positive' }} d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi {{ ($needsAttention ?? false) ? 'bi-exclamation-triangle' : 'bi-shield-check' }}"></i>
                        {{ ($needsAttention ?? false) ? 'Support recommended' : 'Steady check-in' }}
                    </span>

                    <h2 class="h4 fw-bold mb-2">
                        {{ ($needsAttention ?? false) ? 'Your recent records suggest extra support may help.' : 'Your wellbeing space is ready when you need it.' }}
                    </h2>

                    <p class="wb-muted mb-0">
                        {{ ($needsAttention ?? false)
                            ? 'Use counselling support, talk with someone trusted, or use emergency help if you feel unsafe.'
                            : 'Keep tracking small changes. Patterns become easier to notice when you check in regularly.' }}
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('counseling.index') }}" class="btn btn-wb-primary">
                        <i class="bi bi-heart-pulse me-2"></i>
                        Counselling
                    </a>
                    <a href="#hospital-support" class="btn btn-wb-outline">
                        Emergency Help
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-5">
            <div class="col-lg-5">
                <div class="wb-card wb-ai-card h-100 p-4">
                    <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-stars"></i>
                        AI suggestion
                    </span>

                    <h2 class="h4 fw-bold mb-3">
                        A small step for right now
                    </h2>

                    <p class="lead mb-4">
                        {{ $aiSuggestion ?? 'Take one slow breath and choose one small next step.' }}
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="/chat" class="btn btn-wb-primary">
                            <i class="bi bi-chat-heart me-2"></i>
                            Talk About It
                        </a>

                        <a href="/mood" class="btn btn-wb-outline">
                            View Mood
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div id="motivationCarousel"
                     class="carousel slide wb-motivation-carousel h-100"
                     data-bs-ride="carousel"
                     data-bs-interval="5200">
                    <div class="carousel-indicators">
                        <button type="button"
                                data-bs-target="#motivationCarousel"
                                data-bs-slide-to="0"
                                class="active"
                                aria-current="true"
                                aria-label="Slide 1"></button>
                        <button type="button"
                                data-bs-target="#motivationCarousel"
                                data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                        <button type="button"
                                data-bs-target="#motivationCarousel"
                                data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                    </div>

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="/images/motivation-student-reflect.png"
                                 class="d-block w-100"
                                 alt="Student reflecting calmly near a window">
                            <div class="carousel-caption">
                                <h2>One calm moment can be enough to begin.</h2>
                                <p>Pause, breathe, and let the next step be small.</p>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="/images/motivation-campus-support.png"
                                 class="d-block w-100"
                                 alt="Students walking together on campus">
                            <div class="carousel-caption">
                                <h2>You do not have to carry everything alone.</h2>
                                <p>Reach out to someone safe when the day feels heavy.</p>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="/images/motivation-mindful-pause.png"
                                 class="d-block w-100"
                                 alt="Student taking a mindful pause outdoors">
                            <div class="carousel-caption">
                                <h2>Rest is part of moving forward.</h2>
                                <p>A short reset can help your mind find room again.</p>
                            </div>
                        </div>
                    </div>

                    <button class="carousel-control-prev"
                            type="button"
                            data-bs-target="#motivationCarousel"
                            data-bs-slide="prev"
                            aria-label="Previous slide">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>

                    <button class="carousel-control-next"
                            type="button"
                            data-bs-target="#motivationCarousel"
                            data-bs-slide="next"
                            aria-label="Next slide">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="wb-card mt-5 p-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-clipboard2-pulse"></i>
                        Latest assessment
                    </span>

                    @if($latestAssessment)
                        <h2 class="h4 fw-bold mb-2">
                            {{ $latestAssessment->wellbeing_level }} &middot; Score {{ $latestAssessment->total_score }}
                        </h2>

                        <p class="wb-muted mb-3">
                            Priority: <strong>{{ $latestAssessment->ai_priority_level ?? 'Pending AI analysis' }}</strong>
                        </p>

                        <p class="mb-0">
                            {{ $latestAssessment->ai_wellbeing_summary ?? 'Your assessment has been saved. AI analysis is temporarily unavailable.' }}
                        </p>
                    @else
                        <h2 class="h4 fw-bold mb-2">No wellbeing assessment yet</h2>
                        <p class="wb-muted mb-0">
                            Take a short assessment to receive a supportive AI summary and counselling recommendation.
                        </p>
                    @endif
                </div>

                <div class="col-lg-4 text-lg-end">
                    @if($latestAssessment)
                        <a href="{{ route('assessments.show', $latestAssessment) }}" class="btn btn-wb-primary mb-2">
                            View Full Assessment
                        </a>
                    @endif
                    <a href="{{ route('counseling.index') }}" class="btn btn-wb-outline mb-2">
                        Take New Assessment
                    </a>
                    <a href="{{ route('counseling.index') }}#support-services" class="btn btn-wb-outline mb-2">
                        Request Counselling
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-lg-7">
                <div class="wb-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-graph-up"></i>
                                Assessment trend
                            </span>
                            <h2 class="h5 fw-bold mb-1">Wellbeing Score History</h2>
                            <p class="wb-muted mb-0">Track how your assessment score changes over time.</p>
                        </div>
                    </div>

                    @if(($assessmentHistory ?? collect())->isNotEmpty())
                        <canvas id="assessmentChart" height="140"></canvas>
                    @else
                        <div class="text-center wb-muted py-5">
                            No assessment trend yet. Submit two or more assessments to see your pattern.
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="wb-card h-100 p-4">
                    <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-activity"></i>
                        Mood trend
                    </span>

                    <h2 class="h5 fw-bold mb-3">Recent Mood Scores</h2>

                    @if(($moodTrend ?? collect())->isNotEmpty())
                        <canvas id="moodTrendChart" height="180"></canvas>
                    @else
                        <div class="text-center wb-muted py-5">
                            No mood trend yet. Send a chat message to create your first record.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4" id="hospital-support">
            <div class="col-lg-5">
                <div class="wb-card h-100 p-4">
                    <span class="wb-badge wb-badge-emergency d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        Emergency support
                    </span>

                    <h2 class="h5 fw-bold mb-3">If you feel unsafe right now</h2>

                    <p class="wb-muted">
                        Do not wait for an AI reply. Stay near someone trusted and contact real-world support immediately.
                    </p>

                    <div class="d-grid gap-3">
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

        <div class="row g-4 mt-5">
            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-hospital"></i>
                                Emergency help nearby
                            </span>

                            <h2 class="h4 fw-bold mb-2">
                                Find the nearest hospital
                            </h2>

                            <p class="wb-muted mb-0">
                                If you feel at risk of suicide or self-harm, do not wait. Use this map, call emergency services, or ask someone nearby to stay with you.
                            </p>
                        </div>
                    </div>

                    <div class="wb-map-frame mb-3">
                        <iframe id="hospital-map"
                                title="Hospital finder map"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps?q=hospital%20near%20me&output=embed"></iframe>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="button"
                                class="btn btn-wb-primary"
                                id="find-hospitals-button">
                            <i class="bi bi-geo-alt me-2"></i>
                            Use My Location
                        </button>

                        <a href="https://www.google.com/maps/search/hospital+near+me"
                           target="_blank"
                           rel="noopener"
                           class="btn btn-wb-outline"
                           id="open-hospitals-link">
                            Open Full Map
                        </a>
                    </div>

                    <p class="small wb-muted mt-3 mb-0" id="hospital-map-status">
                        Your location is only used in your browser to search nearby hospitals.
                    </p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <span class="wb-badge wb-badge-positive d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-lightbulb"></i>
                        Suggestions for you
                    </span>

                    <h2 class="h4 fw-bold mb-3">
                        Based on your latest mood: {{ ucfirst($latestMood ?? 'neutral') }}
                    </h2>

                    <div class="d-grid gap-3">
                        @foreach(($recommendations ?? []) as $recommendation)
                            <div class="wb-recommendation-item">
                                <i class="bi bi-check2-circle"></i>
                                <span>{{ $recommendation }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="wb-panel mt-5 p-4 p-md-5">
            <div class="row align-items-center g-4">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-3">
                        When things feel too much, start with one message.
                    </h2>

                    <p class="wb-muted mb-0">
                        The system is here to help you reflect and notice patterns. For urgent danger or thoughts of self-harm, contact someone you trust or emergency support immediately.
                    </p>
                </div>

                <div class="col-md-4 text-md-end">
                    <a href="/chat" class="btn btn-wb-primary">
                        Talk Now
                    </a>
                    <a href="{{ route('counseling.index') }}" class="btn btn-wb-outline ms-md-2 mt-2 mt-md-0">
                        Get Support
                    </a>
                    <a href="{{ route('reports.user') }}" class="btn btn-wb-outline ms-md-2 mt-2 mt-md-0">
                        Download Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

@include('components.site-footer')

<script>
const findHospitalsButton = document.getElementById('find-hospitals-button');
const hospitalMap = document.getElementById('hospital-map');
const hospitalMapStatus = document.getElementById('hospital-map-status');
const openHospitalsLink = document.getElementById('open-hospitals-link');

findHospitalsButton?.addEventListener('click', function () {
    if (!navigator.geolocation) {
        hospitalMapStatus.textContent = 'Location is not available in this browser. You can still open the full map.';
        return;
    }

    hospitalMapStatus.textContent = 'Checking your location...';

    navigator.geolocation.getCurrentPosition(function (position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const query = `hospital near ${lat},${lng}`;
        const embeddedUrl = `https://www.google.com/maps?q=${encodeURIComponent(query)}&output=embed`;
        const fullMapUrl = `https://www.google.com/maps/search/hospital/@${lat},${lng},14z`;

        hospitalMap.src = embeddedUrl;
        openHospitalsLink.href = fullMapUrl;
        hospitalMapStatus.textContent = 'Showing hospitals near your current location.';
    }, function () {
        hospitalMapStatus.textContent = 'Location permission was not allowed. You can still use the full map search.';
    }, {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 300000
    });
});

const assessmentChart = document.getElementById('assessmentChart');
const assessmentHistory = @json($assessmentHistory ?? []);
const moodTrendChart = document.getElementById('moodTrendChart');
const moodTrend = @json($moodTrend ?? []);

if (assessmentChart && assessmentHistory.length > 0) {
    new Chart(assessmentChart, {
        type: 'line',
        data: {
            labels: assessmentHistory.map(item => item.date),
            datasets: [{
                label: 'Assessment Score',
                data: assessmentHistory.map(item => item.score),
                borderColor: '#2f7d72',
                backgroundColor: 'rgba(47, 125, 114, 0.12)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#2f7d72'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: 32
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

if (moodTrendChart && moodTrend.length > 0) {
    new Chart(moodTrendChart, {
        type: 'line',
        data: {
            labels: moodTrend.map(item => item.date),
            datasets: [{
                label: 'Mood Score',
                data: moodTrend.map(item => item.score),
                borderColor: '#4d9aa2',
                backgroundColor: 'rgba(77, 154, 162, 0.14)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#4d9aa2'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    min: 0,
                    max: 10
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
