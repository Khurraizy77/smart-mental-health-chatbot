<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wellbeing Assessment - Smart Mental Health Chatbot</title>

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

        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('counseling.index') }}" class="btn btn-wb-outline">Take New Assessment</a>
            <a href="/dashboard" class="btn btn-wb-primary">Dashboard</a>
        </div>
    </div>
</nav>

<main class="wb-section">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @if($assessment->urgent_support)
            <div class="alert alert-danger p-4">
                <h2 class="h5 fw-bold">Urgent support guidance</h2>
                <p class="mb-0">
                    Your assessment indicates urgent support may be needed. Please contact emergency services, a nearby hospital, campus support, a qualified counsellor, or someone trusted immediately. In Malaysia, call 999 for emergencies.
                </p>
            </div>
        @endif

        <div class="wb-card p-4 p-md-5 mb-4">
            <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                <i class="bi bi-clipboard2-pulse"></i>
                Your wellbeing assessment
            </span>

            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <h1 class="fw-bold mb-4">Assessment Result</h1>

                    <div class="d-grid gap-3">
                        <div class="wb-recommendation-item">
                            <i class="bi bi-heart-pulse"></i>
                            <span><strong>Wellbeing Level:</strong> {{ $assessment->wellbeing_level }}</span>
                        </div>

                        <div class="wb-recommendation-item">
                            <i class="bi bi-speedometer2"></i>
                            <span><strong>Assessment Score:</strong> {{ $assessment->total_score }}</span>
                        </div>

                        <div class="wb-recommendation-item">
                            <i class="bi bi-flag"></i>
                            <span>
                                <strong>Priority Level:</strong>
                                <span class="wb-badge {{ $assessment->priorityBadgeClass() }}">
                                    {{ $assessment->ai_priority_level ?? 'Pending AI analysis' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <h2 class="h4 fw-bold mb-3">AI Wellbeing Summary</h2>

                    <p class="lead">
                        {{ $assessment->ai_wellbeing_summary ?? 'AI analysis is temporarily unavailable. Your assessment has still been saved for your reflection and for counsellor/admin review.' }}
                    </p>

                    <p class="small wb-muted mb-0">
                        This AI-generated result is for wellbeing support and self-reflection only. It is not a medical diagnosis and does not replace assessment or treatment by a qualified mental health professional.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <h2 class="h5 fw-bold mb-3">What You May Be Experiencing</h2>

                    <div class="d-grid gap-3">
                        @forelse(($assessment->ai_main_concerns ?? []) as $concern)
                            <div class="wb-recommendation-item">
                                <i class="bi bi-check2-circle"></i>
                                <span>{{ $concern }}</span>
                            </div>
                        @empty
                            <p class="wb-muted mb-0">No AI concern list is available yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <h2 class="h5 fw-bold mb-3">Possible Stress Factors</h2>

                    <div class="d-grid gap-3">
                        @forelse(($assessment->ai_stress_factors ?? []) as $factor)
                            <div class="wb-recommendation-item">
                                <i class="bi bi-arrow-right-circle"></i>
                                <span>{{ $factor }}</span>
                            </div>
                        @empty
                            <p class="wb-muted mb-0">{{ $assessment->stress_reason ?: 'No stress reason was provided.' }}</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <h2 class="h5 fw-bold mb-3">AI Suggestions</h2>

                    <div class="d-grid gap-3">
                        @forelse(($assessment->ai_suggestions ?? []) as $suggestion)
                            <div class="wb-recommendation-item">
                                <i class="bi bi-lightbulb"></i>
                                <span>{{ $suggestion }}</span>
                            </div>
                        @empty
                            <p class="wb-muted mb-0">Try one grounding step: breathe slowly, name five things you can see, and speak to someone safe if the feeling continues.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <h2 class="h5 fw-bold mb-3">Recommended Support</h2>

                    <p>{{ $assessment->ai_recommended_support ?? 'Consider using counselling support if the concern continues or affects your daily activities.' }}</p>

                    <h3 class="h6 fw-bold mt-4">Counselling</h3>
                    <p class="wb-muted">{{ $assessment->ai_counselling_recommendation ?? 'You may request counselling support from the services page.' }}</p>

                    <div class="alert {{ $assessment->ai_priority_level === 'Urgent' ? 'alert-danger' : 'alert-warning' }}">
                        {{ $assessment->supportGuidance() }}
                    </div>

                    <a href="{{ route('counseling.index') }}#support-services" class="btn btn-wb-primary">
                        Request Counselling
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
