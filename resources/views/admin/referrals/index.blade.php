<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Referrals - Smart Mental Health Chatbot</title>

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

        <div class="ms-auto">
            <a href="/admin/dashboard" class="btn btn-wb-outline">Back to Dashboard</a>
        </div>
    </div>
</nav>

<main class="wb-section">
    <div class="container">
        <div class="mb-5">
            <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                <i class="bi bi-clipboard-heart"></i>
                Referral records
            </span>

            <h1 class="fw-bold mb-2">Manage Referrals</h1>

            <p class="lead wb-muted mb-0">
                Review counselling requests and update their status.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="wb-card p-4">
            <div class="table-responsive">
                <table class="table wb-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Matric No</th>
                            <th>Service</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referrals as $referral)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $referral->user?->name }}</div>
                                    <div class="small wb-muted">{{ $referral->user?->email }}</div>
                                </td>
                                <td>{{ $referral->user?->studentProfile?->matric_no ?? '-' }}</td>
                                <td>{{ $referral->service?->service_name }}</td>
                                <td>{{ $referral->notes ?: '-' }}</td>
                                <td>
                                    <form method="POST"
                                          action="{{ route('admin.referrals.update', $referral) }}"
                                          class="d-flex gap-2">
                                        @csrf
                                        @method('PUT')

                                        <select name="status" class="form-select form-select-sm">
                                            @foreach(['pending', 'contacted', 'completed', 'cancelled'] as $status)
                                                <option value="{{ $status }}" @selected($referral->status === $status)>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn btn-sm btn-wb-primary">
                                            Save
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $referral->created_at?->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center wb-muted py-4">
                                    No referral requests yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $referrals->links() }}
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
