<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Wellbeing Analysis</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2d2b; font-size: 12px; line-height: 1.45; }
        h1, h2, h3 { margin: 0; }
        h1 { font-size: 24px; color: #236158; }
        h2 { font-size: 16px; color: #236158; margin-bottom: 10px; }
        h3 { font-size: 13px; margin-bottom: 6px; }
        .muted { color: #66736f; }
        .header { border-bottom: 3px solid #2f7d72; padding-bottom: 14px; margin-bottom: 18px; }
        .section { margin-bottom: 18px; page-break-inside: avoid; }
        .grid { width: 100%; border-collapse: collapse; }
        .grid td { width: 25%; padding: 8px; border: 1px solid #d9e4dd; vertical-align: top; }
        .metric { font-size: 22px; font-weight: bold; color: #1f2d2b; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d9e4dd; padding: 7px; text-align: left; vertical-align: top; }
        th { background: #edf5f0; color: #236158; }
        .badge { display: inline-block; padding: 3px 7px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .positive { background: #dceee8; color: #236158; }
        .negative, .emergency { background: #f6dfda; color: #a65344; }
        .neutral { background: #e2eef0; color: #315f65; }
        .bar-wrap { height: 10px; background: #edf5f0; border-radius: 8px; overflow: hidden; margin-top: 4px; }
        .bar { height: 10px; background: #2f7d72; }
        .note { background: #fff7e7; border: 1px solid #efd7a7; padding: 10px; border-radius: 6px; }
        .footer { border-top: 1px solid #d9e4dd; padding-top: 8px; font-size: 10px; color: #66736f; }
    </style>
</head>
<body>
    <div class="header">
        <h1>User Wellbeing Analysis Report</h1>
        <p class="muted">
            Generated on {{ $generatedAt->format('d M Y, h:i A') }} for {{ $user->name }}.
        </p>
        <p class="muted">
            This report summarizes app activity and mood tracking. It is not a medical diagnosis.
        </p>
    </div>

    <div class="section">
        <h2>Student Information</h2>
        <table>
            <tr>
                <th>Name</th>
                <td>{{ $user->name }}</td>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Matric No</th>
                <td>{{ $user->studentProfile?->matric_no ?? '-' }}</td>
                <th>Program</th>
                <td>{{ $user->studentProfile?->program ?? '-' }}</td>
            </tr>
            <tr>
                <th>Faculty</th>
                <td>{{ $user->studentProfile?->faculty ?? '-' }}</td>
                <th>Year of Study</th>
                <td>{{ $user->studentProfile?->year_of_study ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Activity Summary</h2>
        <table class="grid">
            <tr>
                <td><div class="muted">Chat Sessions</div><div class="metric">{{ $totalChats }}</div></td>
                <td><div class="muted">Total Messages</div><div class="metric">{{ $totalMessages }}</div></td>
                <td><div class="muted">Student Messages</div><div class="metric">{{ $studentMessages }}</div></td>
                <td><div class="muted">Average Mood Score</div><div class="metric">{{ $averageMoodScore ?: '-' }}/10</div></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Mood Distribution</h2>
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
        <h2>Current Analysis</h2>
        <div class="note">
            Latest detected mood: <strong>{{ ucfirst($latestMood) }}</strong>.
            @if($latestMood === 'emergency')
                The latest message was flagged as urgent. Contact emergency services, a hospital, campus security, or a trusted person immediately if safety is at risk.
            @elseif($latestMood === 'negative')
                Recent messages show difficult mood patterns. Consider using counselling support or speaking to a trusted person.
            @elseif($latestMood === 'positive')
                Recent mood is positive. Keep observing the habits or support that helped.
            @else
                Recent mood is neutral. Continue tracking mood to understand patterns over time.
            @endif
        </div>
    </div>

    <div class="section">
        <h2>Latest Wellbeing Assessment</h2>
        @if($latestAssessment)
            <table>
                <tr>
                    <th>Date</th>
                    <td>{{ $latestAssessment->created_at?->format('d M Y') }}</td>
                    <th>Score</th>
                    <td>{{ $latestAssessment->total_score }}</td>
                </tr>
                <tr>
                    <th>Level</th>
                    <td>{{ $latestAssessment->wellbeing_level }}</td>
                    <th>Priority</th>
                    <td>{{ $latestAssessment->ai_priority_level ?? 'Pending AI analysis' }}</td>
                </tr>
                <tr>
                    <th>AI Summary</th>
                    <td colspan="3">{{ $latestAssessment->ai_wellbeing_summary ?? 'AI analysis unavailable.' }}</td>
                </tr>
                <tr>
                    <th>Suggested Support</th>
                    <td colspan="3">{{ $latestAssessment->ai_counselling_recommendation ?? 'Consider counselling support if the concern continues.' }}</td>
                </tr>
            </table>
            <p class="muted">
                AI assessment results are for wellbeing support and self-reflection only. They are not a medical diagnosis.
            </p>
        @else
            <p>No wellbeing assessment has been submitted yet.</p>
        @endif
    </div>

    <div class="section">
        <h2>Recommendations</h2>
        <ol>
            @forelse($recommendations as $recommendation)
                <li>{{ $recommendation }}</li>
            @empty
                <li>No recommendations available yet.</li>
            @endforelse
        </ol>
    </div>

    <div class="section">
        <h2>Recent Mood Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Mood</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse($moods->take(10) as $mood)
                    <tr>
                        <td>{{ $mood->date?->format('d M Y') ?? $mood->created_at?->format('d M Y') }}</td>
                        <td>{{ ucfirst($mood->mood_type) }}</td>
                        <td>{{ $mood->mood_score ?? '-' }}/10</td>
                    </tr>
                @empty
                    <tr><td colspan="3">No mood records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Referral Requests</h2>
        <table>
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
                        <td>{{ ucfirst($referral->status) }}</td>
                        <td>{{ $referral->created_at?->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">No referral requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Smart Mental Health Chatbot with Sentiment Analysis. For urgent danger or self-harm risk, seek emergency support immediately.
    </div>
</body>
</html>
