<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Counselling Support Request</title>
</head>
<body style="font-family: Arial, sans-serif; color: #24312d; line-height: 1.6;">
    <h1 style="font-size: 22px;">New Counselling Support Request</h1>

    <p>
        A student has requested counselling support through the Smart Mental Health Chatbot system.
    </p>

    <h2 style="font-size: 18px;">Student Details</h2>
    <ul>
        <li><strong>Name:</strong> {{ $referral->user?->name ?? '-' }}</li>
        <li><strong>Email:</strong> {{ $referral->user?->email ?? '-' }}</li>
        <li><strong>Matric No:</strong> {{ $referral->user?->studentProfile?->matric_no ?? '-' }}</li>
        <li><strong>Program:</strong> {{ $referral->user?->studentProfile?->program ?? '-' }}</li>
        <li><strong>Faculty:</strong> {{ $referral->user?->studentProfile?->faculty ?? '-' }}</li>
        <li><strong>Phone:</strong> {{ $referral->user?->studentProfile?->phone_number ?? '-' }}</li>
    </ul>

    <h2 style="font-size: 18px;">Request Details</h2>
    <ul>
        <li><strong>Service:</strong> {{ $referral->service?->service_name ?? '-' }}</li>
        <li><strong>Status:</strong> {{ ucfirst($referral->status) }}</li>
        <li><strong>Requested At:</strong> {{ $referral->created_at?->format('d M Y, h:i A') }}</li>
    </ul>

    <h2 style="font-size: 18px;">Student Notes</h2>
    <p>
        {{ $referral->notes ?: 'No extra notes provided.' }}
    </p>

    <p>
        Please contact the student directly to check their condition and arrange a suitable appointment.
    </p>
</body>
</html>
