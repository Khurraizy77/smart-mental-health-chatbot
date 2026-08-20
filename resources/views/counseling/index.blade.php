<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Counselling Services - Smart Mental Health Chatbot</title>

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
            <a href="/dashboard" class="btn btn-wb-outline">Dashboard</a>
            <a href="/chat" class="btn btn-wb-primary">Open Chat</a>
        </div>
    </div>
</nav>

<main class="wb-section">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <span class="wb-badge wb-badge-neutral d-inline-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-heart-pulse"></i>
                    Support services
                </span>

                <h1 class="fw-bold mb-2">Counselling Services</h1>

                <p class="lead wb-muted mb-0">
                    Complete a wellbeing assessment, review the AI support summary, and request counselling when you need help beyond chat.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-warning">
                <strong>Request not submitted.</strong>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <div class="wb-card p-4 p-md-5 mb-4">
            <div class="row g-4">
                <div class="col-lg-4">
                    <span class="wb-badge wb-badge-positive d-inline-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-clipboard2-pulse"></i>
                        Wellbeing assessment
                    </span>

                    <h2 class="h3 fw-bold mb-3">Counsellor Wellbeing Assessment Form</h2>

                    <p class="wb-muted">
                        Answer based on how you have felt recently. The AI result is only for wellbeing support and self-reflection, not a medical diagnosis.
                    </p>

                    <div class="wb-recommendation-card mb-3">
                        <h3 class="h6 fw-bold mb-2">Answer scale</h3>
                        <p class="small wb-muted mb-0">
                            Never = 0, Rarely = 1, Sometimes = 2, Often = 3, Almost always = 4.
                        </p>
                    </div>

                    <div class="alert alert-warning mb-0">
                        If you may hurt yourself or someone else, contact emergency services, a nearby hospital, campus support, or someone trusted immediately.
                    </div>

                    <div class="mt-3 d-grid gap-2">
                        <div class="wb-recommendation-item">
                            <i class="bi bi-telephone"></i>
                            <span><strong>Emergency:</strong> 999</span>
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

                <div class="col-lg-8">
                    <form method="POST" action="{{ route('assessments.store') }}" class="d-grid gap-4">
                        @csrf

                        <div class="d-grid gap-3">
                            @foreach($questions as $key => $question)
                                <div class="p-3 border rounded-3 bg-white">
                                    <label class="form-label fw-semibold" for="answer-{{ $key }}">
                                        {{ $loop->iteration }}. {{ $question }}
                                    </label>

                                    <select id="answer-{{ $key }}"
                                            name="answers[{{ $key }}]"
                                            class="form-select"
                                            required>
                                        <option value="">Choose one</option>
                                        <option value="0" @selected(old("answers.$key") === '0')>Never</option>
                                        <option value="1" @selected(old("answers.$key") === '1')>Rarely</option>
                                        <option value="2" @selected(old("answers.$key") === '2')>Sometimes</option>
                                        <option value="3" @selected(old("answers.$key") === '3')>Often</option>
                                        <option value="4" @selected(old("answers.$key") === '4')>Almost always</option>
                                    </select>

                                    @error("answers.$key")
                                        <div class="small text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <label class="form-label fw-semibold" for="stress_reason">
                                What is the main reason you feel stressed or need support?
                            </label>
                            <textarea id="stress_reason"
                                      name="stress_reason"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Example: assignment pressure, family problem, relationship issue, financial stress">{{ old('stress_reason') }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="preferred_support">
                                    Preferred support
                                </label>
                                <select id="preferred_support"
                                        name="preferred_support"
                                        class="form-select">
                                    <option value="">No preference</option>
                                    @foreach(['Chat support', 'Counselling session', 'Stress management tips', 'Emergency support'] as $support)
                                        <option value="{{ $support }}" @selected(old('preferred_support') === $support)>
                                            {{ $support }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="urgent_support">
                                    Do you need urgent support now?
                                </label>
                                <select id="urgent_support"
                                        name="urgent_support"
                                        class="form-select"
                                        required>
                                    <option value="no" @selected(old('urgent_support', 'no') === 'no')>No</option>
                                    <option value="yes" @selected(old('urgent_support') === 'yes')>Yes</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-wb-primary btn-lg w-100">
                            <i class="bi bi-stars me-2"></i>
                            Submit Assessment
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="wb-card mb-5 p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                <div>
                    <h2 class="h5 fw-bold mb-1">Your Recent Assessments</h2>
                    <p class="wb-muted mb-0">Review your saved wellbeing assessment results and AI support summaries.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table wb-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Level</th>
                            <th>Score</th>
                            <th>Priority</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assessments as $assessment)
                            <tr>
                                <td>{{ $assessment->created_at?->format('d M Y, h:i A') }}</td>
                                <td>{{ $assessment->wellbeing_level }}</td>
                                <td>{{ $assessment->total_score }}</td>
                                <td>
                                    <span class="wb-badge {{ $assessment->priorityBadgeClass() }}">
                                        {{ $assessment->ai_priority_level ?? 'Pending AI' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('assessments.show', $assessment) }}" class="btn btn-sm btn-wb-outline w-100">
                                        View Result
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center wb-muted py-4">
                                    No wellbeing assessments yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-4" id="support-services">
            <h2 class="h4 fw-bold mb-2">Request Counselling Support</h2>
            <p class="wb-muted mb-0">Choose a support option and send a referral request when you want the support team to follow up.</p>
        </div>

        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-lg-4">
                    <div class="wb-card h-100 p-4">
                        <div class="wb-icon mb-3">
                            <i class="bi bi-life-preserver"></i>
                        </div>

                        <h2 class="h5 fw-bold mb-2">{{ $service->service_name }}</h2>

                        <p class="wb-muted">{{ $service->description }}</p>

                        <p class="small fw-semibold mb-3">
                            {{ $service->contact_info }}
                        </p>

                        @if($service->counsellor_email)
                            <div class="wb-recommendation-item mb-3">
                                <i class="bi bi-envelope-check"></i>
                                <span>
                                    Request will be emailed to
                                    <strong>{{ $service->counsellor_email }}</strong>
                                </span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('referrals.store') }}">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $service->service_id }}">

                            <div class="mb-3">
                                <label class="form-label" for="notes-{{ $service->service_id }}">Notes</label>
                                <textarea id="notes-{{ $service->service_id }}"
                                          name="notes"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Optional detail for the support team"></textarea>
                            </div>

                            <button type="submit" class="btn btn-wb-primary w-100">
                                <i class="bi bi-send me-2"></i>
                                Request Referral
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="wb-card mt-5 p-4">
            <h2 class="h5 fw-bold mb-4">Your Referral Requests</h2>

            <div class="table-responsive">
                <table class="table wb-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referrals as $referral)
                            <tr>
                                <td>{{ $referral->service?->service_name }}</td>
                                <td>
                                    <span class="wb-badge wb-badge-neutral">
                                        {{ ucfirst($referral->status) }}
                                    </span>
                                </td>
                                <td>{{ $referral->created_at?->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center wb-muted py-4">
                                    No referral requests yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
