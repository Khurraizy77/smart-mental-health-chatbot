<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mood Tracking - Smart Mental Health Chatbot</title>

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
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/chat">Chat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/mood">Mood Tracking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@php
    $positive = $moods->where('mood_type', 'positive')->count();
    $negative = $moods->where('mood_type', 'negative')->count();
    $neutral = $moods->where('mood_type', 'neutral')->count();
    $emergency = $moods->where('mood_type', 'emergency')->count();
@endphp

<main class="wb-section">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
            <div>
                <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-activity"></i>
                    Mood history
                </span>

                <h1 class="fw-bold mb-2">
                    Mood Tracking
                </h1>

                <p class="lead wb-muted mb-0">
                    A simple overview of the emotional tone detected from your chats.
                </p>
            </div>

            <a href="/chat" class="btn btn-wb-primary">
                <i class="bi bi-chat-heart me-2"></i>
                Back to Chat
            </a>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="wb-card wb-stat h-100 p-4">
                    <p class="wb-muted mb-1">Total Records</p>
                    <h2 class="fw-bold mb-0">{{ $moods->count() }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="wb-card wb-stat positive h-100 p-4">
                    <p class="wb-muted mb-1">Positive</p>
                    <h2 class="fw-bold mb-0">{{ $positive }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="wb-card wb-stat negative h-100 p-4">
                    <p class="wb-muted mb-1">Difficult</p>
                    <h2 class="fw-bold mb-0">{{ $negative }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="wb-card wb-stat neutral h-100 p-4">
                    <p class="wb-muted mb-1">Neutral</p>
                    <h2 class="fw-bold mb-0">{{ $neutral }}</h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="wb-card p-4 h-100">
                    <h5 class="fw-bold mb-4">Mood Distribution</h5>
                    <canvas id="moodChart"></canvas>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="wb-card p-4 h-100">
                    <h5 class="fw-bold mb-4">Mood History</h5>

                    <div class="table-responsive">
                        <table class="table wb-table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mood</th>
                                    <th>Score</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($moods as $index => $mood)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($mood->mood_type == 'positive')
                                                <span class="wb-badge wb-badge-positive">Positive</span>
                                            @elseif($mood->mood_type == 'negative')
                                                <span class="wb-badge wb-badge-negative">Difficult</span>
                                            @elseif($mood->mood_type == 'emergency')
                                                <span class="wb-badge wb-badge-emergency">Emergency</span>
                                            @else
                                                <span class="wb-badge wb-badge-neutral">Neutral</span>
                                            @endif
                                        </td>
                                        <td>{{ $mood->mood_score ?? '-' }}/10</td>
                                        <td>{{ $mood->date?->format('d M Y') ?? $mood->created_at?->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center wb-muted py-4">
                                            No mood records yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="wb-card mt-5 p-4 p-md-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                <div>
                    <span class="wb-badge wb-badge-positive d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-heart-pulse"></i>
                        Recommendations
                    </span>

                    <h2 class="h4 fw-bold mb-2">
                        Helpful steps for your latest mood: {{ ucfirst($latestMood ?? 'neutral') }}
                    </h2>

                    <p class="wb-muted mb-0">
                        These are small suggestions, not a diagnosis. If you feel unsafe, contact emergency support or someone you trust immediately.
                    </p>
                </div>

                @if(Auth::user()->role === 'student')
                    <a href="{{ route('counseling.index') }}" class="btn btn-wb-outline align-self-start">
                        Counselling Support
                    </a>
                @endif
            </div>

            <div class="row g-3">
                @foreach($recommendations as $recommendation)
                    <div class="col-md-4">
                        <div class="wb-recommendation-card h-100">
                            <div class="wb-icon mb-3">
                                <i class="bi bi-stars"></i>
                            </div>

                            <h5 class="fw-bold">
                                {{ $recommendation['title'] }}
                            </h5>

                            <p class="wb-muted mb-0">
                                {{ $recommendation['text'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>

@include('components.site-footer')

<script>
const ctx = document.getElementById('moodChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Positive', 'Difficult', 'Neutral', 'Emergency'],
        datasets: [{
            data: [
                {{ $positive }},
                {{ $negative }},
                {{ $neutral }},
                {{ $emergency }}
            ],
            backgroundColor: ['#2f7d72', '#c96f5d', '#4d9aa2', '#d8a85f'],
            borderColor: '#ffffff',
            borderWidth: 3
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
