<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - Smart Mental Health Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="wb-body">

<nav class="navbar navbar-expand-lg wb-nav sticky-top">
    <div class="container">
        <a class="navbar-brand wb-brand" href="/admin/dashboard">
            Admin Dashboard
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
                    <a class="nav-link active" href="/admin/dashboard">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('chat.index') }}">Chatbot</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mood">Mood Tracking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wellbeing.guide') }}">Guide</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/profile">Profile</a>
                </li>
                <li class="nav-item ms-lg-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-wb-outline btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="wb-section">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-5">
            <div>
                <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-bar-chart"></i>
                    Monitoring
                </span>

                <h1 class="fw-bold mb-2">
                    Admin Analytics Dashboard
                </h1>

                <p class="lead wb-muted mb-0">
                    Monitor student wellbeing trends and manage student records.
                </p>
            </div>

            <div>
                <a href="{{ route('admin.students.index') }}"
                   class="btn btn-wb-primary me-2 mb-2">
                    <i class="bi bi-people me-2"></i>
                    Manage Students
                </a>

                <a href="{{ route('admin.referrals.index') }}"
                   class="btn btn-wb-outline me-2 mb-2">
                    <i class="bi bi-clipboard-heart me-2"></i>
                    Referrals
                </a>

                <a href="{{ route('admin.assessments.index') }}"
                   class="btn btn-wb-outline me-2 mb-2">
                    <i class="bi bi-clipboard2-pulse me-2"></i>
                    Assessments
                </a>

                <a href="{{ route('admin.reports.analysis') }}"
                   class="btn btn-wb-outline me-2 mb-2">
                    <i class="bi bi-file-earmark-pdf me-2"></i>
                    PDF Report
                </a>

                <a href="/profile"
                   class="btn btn-wb-outline me-2 mb-2">
                    Profile
                </a>

                <a href="{{ route('chat.index') }}"
                   class="btn btn-wb-outline me-2 mb-2">
                    <i class="bi bi-chat-heart me-2"></i>
                    Chatbot
                </a>

                <a href="/mood"
                   class="btn btn-wb-outline mb-2">
                    <i class="bi bi-activity me-2"></i>
                    Mood Tracking
                </a>

                <a href="{{ route('wellbeing.guide') }}"
                   class="btn btn-wb-outline mb-2">
                    <i class="bi bi-compass me-2"></i>
                    Wellbeing Guide
                </a>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4 col-xl-2">
                <div class="wb-card wb-stat h-100 p-4">
                    <p class="wb-muted mb-1">Total Users</p>
                    <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
                    <p class="small wb-muted mb-0">{{ $totalStudents }} students &middot; {{ $totalAdmins }} admins</p>
                </div>
            </div>

            <div class="col-md-4 col-xl-2">
                <div class="wb-card wb-stat neutral h-100 p-4">
                    <p class="wb-muted mb-1">Messages</p>
                    <h2 class="fw-bold mb-0">{{ $totalMessages }}</h2>
                </div>
            </div>

            <div class="col-md-4 col-xl-2">
                <div class="wb-card wb-stat positive h-100 p-4">
                    <p class="wb-muted mb-1">Positive</p>
                    <h2 class="fw-bold mb-0">{{ $positiveMood }}</h2>
                </div>
            </div>

            <div class="col-md-4 col-xl-2">
                <div class="wb-card wb-stat negative h-100 p-4">
                    <p class="wb-muted mb-1">Difficult</p>
                    <h2 class="fw-bold mb-0">{{ $negativeMood }}</h2>
                </div>
            </div>

            <div class="col-md-4 col-xl-2">
                <div class="wb-card wb-stat neutral h-100 p-4">
                    <p class="wb-muted mb-1">Neutral</p>
                    <h2 class="fw-bold mb-0">{{ $neutralMood }}</h2>
                </div>
            </div>

            <div class="col-md-4 col-xl-2">
                <div class="wb-card wb-stat warning h-100 p-4">
                    <p class="wb-muted mb-1">Emergency</p>
                    <h2 class="fw-bold mb-0">{{ $emergencyMood }}</h2>
                </div>
            </div>
        </div>

        <div class="wb-card p-4 mb-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h2 class="h5 fw-bold mb-1">Pending Reviews</h2>
                    <p class="wb-muted mb-0">
                        {{ $pendingReferrals }} referral request{{ $pendingReferrals === 1 ? '' : 's' }} and
                        {{ $pendingAssessmentReviews }} assessment review{{ $pendingAssessmentReviews === 1 ? '' : 's' }} need attention.
                        Urgent assessment flags: <strong>{{ $urgentAssessments }}</strong>.
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.referrals.index') }}" class="btn btn-wb-primary">
                        Review Referrals
                    </a>
                    <a href="{{ route('admin.assessments.index') }}" class="btn btn-wb-outline">
                        Review Assessments
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-1">Students Needing Attention</h5>
                            <p class="wb-muted mb-0">Students with emergency mood records or high assessment priority.</p>
                        </div>
                        <a href="{{ route('admin.assessments.index') }}" class="btn btn-wb-outline btn-sm">
                            Review
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table wb-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Matric No</th>
                                    <th>Faculty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentsNeedingAttention as $student)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <div class="small wb-muted">{{ $student->email }}</div>
                                        </td>
                                        <td>{{ $student->studentProfile?->matric_no ?? '-' }}</td>
                                        <td>{{ $student->studentProfile?->faculty ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center wb-muted py-4">
                                            No high-risk student records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-1">Latest Counselling Referrals</h5>
                            <p class="wb-muted mb-0">Recent student requests and current admin status.</p>
                        </div>
                        <a href="{{ route('admin.referrals.index') }}" class="btn btn-wb-outline btn-sm">
                            Manage
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table wb-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReferrals as $referral)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $referral->user?->name ?? '-' }}</div>
                                            <div class="small wb-muted">{{ $referral->user?->studentProfile?->matric_no ?? '-' }}</div>
                                        </td>
                                        <td>{{ $referral->service?->service_name ?? 'Counselling support' }}</td>
                                        <td>
                                            <span class="wb-badge wb-badge-neutral">{{ ucfirst($referral->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center wb-muted py-4">
                                            No counselling referrals yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <h5 class="fw-bold mb-4">Faculty Mood Risk Summary</h5>

                    <div class="table-responsive">
                        <table class="table wb-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Faculty</th>
                                    <th>Records</th>
                                    <th>Avg Score</th>
                                    <th>Emergency</th>
                                    <th>Difficult</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($facultyMoodSummary as $summary)
                                    <tr>
                                        <td class="fw-semibold">{{ $summary->faculty }}</td>
                                        <td>{{ $summary->total_records }}</td>
                                        <td>{{ $summary->average_score ?? '-' }}</td>
                                        <td>{{ $summary->emergency_count }}</td>
                                        <td>{{ $summary->negative_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center wb-muted py-4">
                                            No mood records available yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wb-card h-100 p-4">
                    <h5 class="fw-bold mb-4">Recent High-Risk Mood Records</h5>

                    <div class="table-responsive">
                        <table class="table wb-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Matric No</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentHighRiskMoods as $mood)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $mood->name }}</div>
                                            <div class="small wb-muted">{{ $mood->email }}</div>
                                        </td>
                                        <td>{{ $mood->matric_no ?? '-' }}</td>
                                        <td>{{ $mood->date ?? \Illuminate\Support\Carbon::parse($mood->created_at)->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center wb-muted py-4">
                                            No emergency mood records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            @foreach(['pending', 'contacted', 'completed', 'cancelled'] as $status)
                <div class="col-md-3">
                    <div class="wb-card wb-stat h-100 p-4">
                        <p class="wb-muted mb-1">{{ ucfirst($status) }} Referrals</p>
                        <h2 class="fw-bold mb-0">{{ $referralStatusCounts[$status] ?? 0 }}</h2>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="wb-card p-4 mb-5">
            <h5 class="fw-bold mb-3">Database Programming Evidence</h5>
            <p class="wb-muted mb-3">
                The system includes database triggers for audit logging and stored procedures for summary analysis on MySQL/MariaDB deployments.
            </p>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="wb-recommendation-item">
                        <i class="bi bi-database-check"></i>
                        <span>Triggers: mood record audit and referral request audit</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="wb-recommendation-item">
                        <i class="bi bi-diagram-3"></i>
                        <span>Stored procedures: user mood summary and admin system summary</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="wb-card p-4">
            <h5 class="fw-bold mb-4">
                Mood Distribution Analysis
            </h5>

            <canvas id="moodChart"></canvas>
        </div>
    </div>
</main>

<script>
const ctx = document.getElementById('moodChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Positive', 'Difficult', 'Neutral', 'Emergency'],
        datasets: [{
            data: [
                {{ $positiveMood }},
                {{ $negativeMood }},
                {{ $neutralMood }},
                {{ $emergencyMood }}
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
