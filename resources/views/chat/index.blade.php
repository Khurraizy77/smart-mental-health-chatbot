<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Support Chat - Smart Mental Health Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/wellbeing-ui.css">
</head>

<body class="wb-body">

<div class="container-fluid wb-chat-layout px-0">
    <div class="row g-0 min-vh-100">
        <aside class="col-lg-3 col-xl-3 wb-sidebar">
            <div class="wb-sidebar-head">
                <a href="/dashboard" class="text-decoration-none wb-brand d-flex align-items-center gap-2 mb-3">
                    <span class="wb-chat-logo">
                        <i class="bi bi-chat-heart"></i>
                    </span>
                    <span>Smart Mental Health Chatbot</span>
                </a>

                <div class="wb-crisis-note small">
                    <div class="d-flex gap-2">
                        <i class="bi bi-exclamation-triangle mt-1"></i>
                        <span>If you may hurt yourself or someone else, contact emergency support or a trusted person now.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('chat.new') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-wb-primary w-100">
                        <i class="bi bi-plus-lg me-2"></i>
                        New Chat
                    </button>
                </form>
            </div>

            <div class="wb-sidebar-section">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="text-uppercase small fw-bold wb-muted mb-0">
                        Sessions
                    </p>

                    <span class="wb-session-count">
                        {{ $sessions->count() }}
                    </span>
                </div>

                <div class="list-group list-group-flush">
                    @forelse($sessions as $chat)
                        @php
                            $firstStudentMessage = $chat->messages->firstWhere('sender_type', 'student');
                            $sessionTitle = $firstStudentMessage
                                ? \Illuminate\Support\Str::limit($firstStudentMessage->message_text, 34)
                                : 'New conversation';
                        @endphp

                        <a href="{{ route('chat.show', $chat) }}"
                           class="list-group-item list-group-item-action wb-session-item {{ $activeSession->is($chat) ? 'active' : '' }}">
                            <span class="wb-session-icon">
                                <i class="bi bi-chat-dots"></i>
                            </span>
                            <span>
                                <span class="d-block fw-semibold wb-session-title">{{ $sessionTitle }}</span>
                                <small class="wb-muted">{{ $chat->created_at?->format('d M, h:i A') }}</small>
                            </span>
                        </a>
                    @empty
                        <div class="wb-empty-mini">
                            Your conversations will appear here.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="wb-sidebar-actions">
                <div class="d-grid gap-2">
                    <a href="/dashboard" class="btn btn-wb-outline">
                        <i class="bi bi-grid me-2"></i>
                        Dashboard
                    </a>

                    <a href="/mood" class="btn btn-wb-outline">
                        <i class="bi bi-activity me-2"></i>
                        Mood Tracking
                    </a>

                    <a href="{{ route('about') }}" class="btn btn-wb-outline">
                        <i class="bi bi-info-circle me-2"></i>
                        About Us
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-wb-danger w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </button>
                    </form>

                    <p class="small wb-muted text-center mb-0 mt-2">
                        &copy; {{ date('Y') }} Khurraizy Khuzainol
                    </p>
                </div>
            </div>
        </aside>

        <main class="col-lg-9 col-xl-9 p-0 d-flex flex-column wb-chat-main">
            <header class="wb-chat-header">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="wb-chat-avatar">
                            <i class="bi bi-chat-heart"></i>
                        </div>

                        <div>
                            <h1 class="h4 fw-bold mb-1">
                                {{ $messages->firstWhere('sender_type', 'student')
                                    ? \Illuminate\Support\Str::limit($messages->firstWhere('sender_type', 'student')->message_text, 48)
                                    : 'Support Chat' }}
                            </h1>

                            <p class="wb-muted mb-0">
                                Share what is on your mind. Short messages are okay.
                            </p>
                        </div>
                    </div>

                    <div class="d-none d-md-flex align-items-center gap-2">
                        @if(Auth::user()->role === 'student')
                            <a href="{{ route('counseling.index') }}" class="btn btn-wb-outline">
                                <i class="bi bi-heart-pulse me-2"></i>
                                Counselling
                            </a>
                        @endif

                        <a href="/dashboard" class="btn btn-wb-outline">
                            Dashboard
                        </a>
                    </div>
                </div>
            </header>

            <section class="wb-chat-box" id="chat-box">
                @forelse($messages as $message)
                    @if($message->sender_type == 'student')
                        <div class="wb-message-row wb-message-row-user">
                            <div class="wb-message-wrap">
                                <div class="wb-message-meta text-end">
                                    You
                                </div>
                                <div class="wb-message wb-message-user">
                                    {{ $message->message_text }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="wb-message-row wb-message-row-bot">
                            <div class="wb-bot-dot">
                                <i class="bi bi-heart"></i>
                            </div>
                            <div class="wb-message-wrap">
                                <div class="wb-message-meta">
                                    Support bot
                                </div>
                                <div class="wb-message wb-message-bot">
                                    {!! nl2br(e($message->message_text)) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="wb-chat-empty">
                        <div class="wb-chat-empty-icon">
                            <i class="bi bi-sunrise"></i>
                        </div>

                        <h2 class="h4 fw-bold mb-2">
                            You can begin with anything.
                        </h2>

                        <p class="wb-muted mb-4">
                            Try one sentence about what happened today, what feels heavy, or how your body feels right now.
                        </p>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <button type="button" class="wb-prompt-chip" data-prompt="I feel stressed because ">
                                    I feel stressed because...
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="wb-prompt-chip" data-prompt="Today was difficult because ">
                                    Today was difficult because...
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="wb-prompt-chip" data-prompt="I need help calming down because ">
                                    I need help calming down...
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </section>

            <footer class="wb-chat-composer">
                <form method="POST" action="{{ route('chat.send', $activeSession) }}">
                    @csrf

                    <div class="wb-composer-shell">
                        <input type="text"
                               id="message-input"
                               name="message_text"
                               class="form-control wb-chat-input"
                               placeholder="Write a message..."
                               aria-label="Type your message"
                               required>

                        <button type="submit"
                                class="btn btn-wb-primary wb-send-button"
                                aria-label="Send message">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>

                    @error('message_text')
                        <div class="alert alert-warning py-2 px-3 mt-2 mb-0">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mt-2">
                        <small class="wb-muted">
                            This chatbot supports reflection, but it is not a replacement for emergency help.
                        </small>

                        @if(Auth::user()->role === 'student')
                            <a href="{{ route('counseling.index') }}" class="small text-decoration-none">
                                Need counselling support?
                            </a>
                        @endif
                    </div>
                </form>
            </footer>
        </main>
    </div>
</div>

<script>
window.addEventListener('load', function () {
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;

    document.querySelectorAll('[data-prompt]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById('message-input');
            input.value = button.dataset.prompt;
            input.focus();
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
