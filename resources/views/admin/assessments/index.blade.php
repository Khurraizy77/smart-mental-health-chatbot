<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wellbeing Assessments - Smart Mental Health Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
</head>

<body class="wb-body">

<nav class="navbar navbar-expand-lg wb-nav sticky-top">
    <div class="container">
        <a class="navbar-brand wb-brand" href="/admin/dashboard">
            Admin Dashboard
        </a>

        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('admin.referrals.index') }}" class="btn btn-wb-outline">Referrals</a>
            <a href="/admin/dashboard" class="btn btn-wb-primary">Back to Dashboard</a>
        </div>
    </div>
</nav>

<main class="wb-section">
    <div class="container">
        <div class="mb-5">
            <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                <i class="bi bi-clipboard2-pulse"></i>
                Assessment review
            </span>

            <h1 class="fw-bold mb-2">Wellbeing Assessments</h1>

            <p class="lead wb-muted mb-0">
                Review student assessment results, AI support summaries, urgent flags, and counsellor notes.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="wb-card p-4 mb-4">
            <form method="GET" action="{{ route('admin.assessments.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold" for="search">Search Student</label>
                    <input type="search"
                           id="search"
                           name="search"
                           class="form-control"
                           value="{{ $filters['search'] ?? '' }}"
                           placeholder="Name, email, or matric no">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold" for="priority">AI Priority</label>
                    <select id="priority" name="priority" class="form-select">
                        <option value="">All priorities</option>
                        @foreach(['Urgent', 'High', 'Moderate', 'Normal'] as $priority)
                            <option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>
                                {{ $priority }}
                            </option>
                        @endforeach
                        <option value="pending" @selected(($filters['priority'] ?? '') === 'pending')>Pending AI</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold" for="status">Review Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(['pending', 'flagged', 'reviewed', 'closed'] as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-wb-primary w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.assessments.index') }}" class="btn btn-wb-outline">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <div class="wb-card p-4">
            <div class="table-responsive">
                <table class="table wb-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Date</th>
                            <th>Score</th>
                            <th>Level</th>
                            <th>AI Summary</th>
                            <th>Priority</th>
                            <th>Urgent</th>
                            <th>Reviewed</th>
                            <th>Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assessments as $assessment)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $assessment->user?->name }}</div>
                                    <div class="small wb-muted">{{ $assessment->user?->email }}</div>
                                    <div class="small wb-muted">{{ $assessment->user?->studentProfile?->matric_no ?? '-' }}</div>
                                </td>
                                <td>{{ $assessment->created_at?->format('d M Y') }}</td>
                                <td>{{ $assessment->total_score }}</td>
                                <td>{{ $assessment->wellbeing_level }}</td>
                                <td style="min-width: 260px;">
                                    {{ $assessment->ai_wellbeing_summary ?: 'AI analysis unavailable.' }}
                                </td>
                                <td>
                                    <span class="wb-badge {{ $assessment->priorityBadgeClass() }}">
                                        {{ $assessment->ai_priority_level ?? 'Pending' }}
                                    </span>
                                </td>
                                <td>{{ $assessment->urgent_support ? 'Yes' : 'No' }}</td>
                                <td>
                                    @if($assessment->reviewed_at)
                                        <div>{{ $assessment->reviewed_at->format('d M Y') }}</div>
                                        <div class="small wb-muted">{{ $assessment->reviewer?->name ?? 'Admin' }}</div>
                                    @else
                                        <span class="wb-muted">Not reviewed</span>
                                    @endif
                                </td>
                                <td style="min-width: 320px;">
                                    <form method="POST"
                                          action="{{ route('admin.assessments.update', $assessment) }}"
                                          class="d-grid gap-2">
                                        @csrf
                                        @method('PUT')

                                        <select name="review_status" class="form-select form-select-sm">
                                            @foreach(['pending', 'reviewed', 'flagged', 'closed'] as $status)
                                                <option value="{{ $status }}" @selected($assessment->review_status === $status)>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <textarea name="counsellor_notes"
                                                  class="form-control form-control-sm"
                                                  rows="3"
                                                  placeholder="Counsellor/admin notes">{{ $assessment->counsellor_notes }}</textarea>

                                        <button type="submit" class="btn btn-sm btn-wb-primary">
                                            Save Review
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center wb-muted py-4">
                                    No wellbeing assessments yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $assessments->links() }}
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
