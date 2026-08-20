<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin System Analysis</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2d2b; font-size: 11px; line-height: 1.35; }
        h1 { font-size: 23px; color: #236158; margin: 0; }
        h2 { font-size: 15px; color: #236158; margin: 0 0 9px; }
        .muted { color: #66736f; }
        .header { border-bottom: 3px solid #2f7d72; padding-bottom: 12px; margin-bottom: 16px; }
        .section { margin-bottom: 16px; page-break-inside: avoid; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d9e4dd; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #edf5f0; color: #236158; }
        .grid td { width: 16.6%; }
        .metric { font-size: 19px; font-weight: bold; color: #1f2d2b; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .positive { background: #dceee8; color: #236158; }
        .negative, .emergency { background: #f6dfda; color: #a65344; }
        .neutral { background: #e2eef0; color: #315f65; }
        .bar-wrap { height: 9px; background: #edf5f0; border-radius: 8px; overflow: hidden; margin-top: 3px; }
        .bar { height: 9px; background: #2f7d72; }
        .warning { background: #fff7e7; border: 1px solid #efd7a7; padding: 9px; border-radius: 6px; }
        .footer { border-top: 1px solid #d9e4dd; padding-top: 8px; font-size: 9px; color: #66736f; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin System Analysis Report</h1>
        <p class="muted">
            Generated on {{ $generatedAt->format('d M Y, h:i A') }}.
        </p>
        <p class="muted">
            This report summarizes system activity, mood trends, and referral status for administrative review.
        </p>
    </div>

    <div class="section">
        <h2>System Summary</h2>
        <table class="grid">
            <tr>
                <td><div class="muted">Users</div><div class="metric">{{ $totalUsers }}</div></td>
                <td><div class="muted">Students</div><div class="metric">{{ $totalStudents }}</div></td>
                <td><div class="muted">Admins</div><div class="metric">{{ $totalAdmins }}</div></td>
                <td><div class="muted">Chat Sessions</div><div class="metric">{{ $totalChatSessions }}</div></td>
                <td><div class="muted">Messages</div><div class="metric">{{ $totalMessages }}</div></td>
                <td><div class="muted">Avg Mood</div><div class="metric">{{ $averageMoodScore ?: '-' }}/10</div></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Mood Analysis</h2>
        <table>
            <thead>
                <tr>
                    <th>Mood</th>
                    <th>Count</th>
                    <th>Percentage</th>
                    <th>Visual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($moodCounts as $mood => $data)
                    <tr>
                        <td><span class="badge {{ $mood }}">{{ ucfirst($mood) }}</span></td>
                        <td>{{ $data['count'] }}</td>
                        <td>{{ $data['percentage'] }}%</td>
                        <td>
                            <div class="bar-wrap">
                                <div class="bar" style="width: {{ $data['percentage'] }}%;"></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Risk and Referral Analysis</h2>
        <div class="warning">
            Emergency mood records: <strong>{{ $highRiskMoods }}</strong>.
            Pending referrals: <strong>{{ $referralCounts['pending'] }}</strong>.
            Urgent assessment flags: <strong>{{ $urgentAssessments }}</strong>.
            Pending assessment reviews: <strong>{{ $pendingAssessmentReviews }}</strong>.
            Review urgent cases regularly and follow institutional safety procedures.
        </div>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Pending</th>
                    <th>Contacted</th>
                    <th>Completed</th>
                    <th>Cancelled</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $referralCounts['pending'] }}</td>
                    <td>{{ $referralCounts['contacted'] }}</td>
                    <td>{{ $referralCounts['completed'] }}</td>
                    <td>{{ $referralCounts['cancelled'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Recent Wellbeing Assessments</h2>
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Matric No</th>
                    <th>Score</th>
                    <th>Level</th>
                    <th>AI Priority</th>
                    <th>Review Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAssessments as $assessment)
                    <tr>
                        <td>{{ $assessment->user?->name }}<br><span class="muted">{{ $assessment->user?->email }}</span></td>
                        <td>{{ $assessment->user?->studentProfile?->matric_no ?? '-' }}</td>
                        <td>{{ $assessment->total_score }}</td>
                        <td>{{ $assessment->wellbeing_level }}</td>
                        <td>{{ $assessment->ai_priority_level ?? 'Pending' }}</td>
                        <td>{{ ucfirst($assessment->review_status) }}</td>
                        <td>{{ $assessment->created_at?->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7">No wellbeing assessments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Recent Referral Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Matric No</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Requested</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentReferrals as $referral)
                    <tr>
                        <td>{{ $referral->user?->name }}<br><span class="muted">{{ $referral->user?->email }}</span></td>
                        <td>{{ $referral->user?->studentProfile?->matric_no ?? '-' }}</td>
                        <td>{{ $referral->service?->service_name }}</td>
                        <td>{{ ucfirst($referral->status) }}</td>
                        <td>{{ $referral->created_at?->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No referral requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Student Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Matric No</th>
                    <th>Program</th>
                    <th>Faculty</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->studentProfile?->matric_no ?? '-' }}</td>
                        <td>{{ $student->studentProfile?->program ?? '-' }}</td>
                        <td>{{ $student->studentProfile?->faculty ?? '-' }}</td>
                        <td>{{ $student->created_at?->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No students found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Smart Mental Health Chatbot with Sentiment Analysis. Administrative data should be handled privately and used only for support decisions.
    </div>
</body>
</html>
