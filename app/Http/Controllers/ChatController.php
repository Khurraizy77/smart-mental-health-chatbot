<?php

namespace App\Http\Controllers;

use App\Models\MoodTracking;
use App\Models\SentimentAnalysis;
use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\Message;
use App\Services\DeepSeekService;
use App\Services\SafetyKeywordService;
use App\Services\WellbeingRecommendationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function __construct(
        private readonly WellbeingRecommendationService $recommendations,
        private readonly DeepSeekService $deepSeek,
        private readonly SafetyKeywordService $safetyKeywords
    )
    {
    }

    public function index()
{
    $session = ChatSession::where('user_id', Auth::id())
        ->latest()
        ->first();

    if (! $session) {
        $session = ChatSession::create([
            'user_id' => Auth::id(),
        ]);
    }

    return $this->show($session);
}

public function show(ChatSession $session)
{
    $this->ensureOwnSession($session);

    $messages = Message::where('session_id', $session->session_id)
        ->orderBy('created_at', 'asc')
        ->get();

    $sessions = ChatSession::with(['messages' => fn ($query) => $query->orderBy('created_at')])
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    $activeSession = $session;

    return view(
        'chat.index',
        compact('messages', 'sessions', 'activeSession')
    );
}

public function createSession()
{
    $session = ChatSession::create([
        'user_id' => Auth::id(),
    ]);

    return redirect()->route('chat.show', $session);
}

public function sendMessage(Request $request, ?ChatSession $session = null)
{
    $request->validate([
        'message_text' => ['required', 'string', 'max:2000'],
    ]);

    if (! $session) {
        $session = ChatSession::where('user_id', Auth::id())
            ->latest()
            ->firstOrCreate([
                'user_id' => Auth::id(),
            ]);
    }

    $this->ensureOwnSession($session);

    try {
        [$sentiment, $confidence] = $this->analyzeSentiment($request->message_text);

        DB::transaction(function () use ($request, $session, $sentiment, $confidence): void {
            $message = Message::create([
                'session_id' => $session->session_id,
                'sender_type' => 'student',
                'message_text' => $request->message_text,
            ]);

            SentimentAnalysis::create([
                'message_id' => $message->message_id,
                'sentiment_type' => $sentiment,
                'confidence_score' => $confidence,
            ]);

            MoodTracking::create([
                'user_id' => Auth::id(),
                'mood_type' => $sentiment,
                'date' => now()->toDateString(),
                'overall_sentiment' => $sentiment,
                'mood_score' => $this->recommendations->scoreForMood($sentiment),
            ]);

            $this->recommendations->assignForMood(Auth::id(), $sentiment);
        });

        $botReply = $sentiment === 'emergency'
            ? $this->emergencyReply()
            : $this->deepSeekReply($session, $request->message_text, $sentiment);

        Message::create([
            'session_id' => $session->session_id,
            'sender_type' => 'chatbot',
            'message_text' => $botReply,
        ]);

        return redirect()->route('chat.show', $session);
    } catch (\Throwable $exception) {
        Log::error('Chat message failed to save.', [
            'user_id' => Auth::id(),
            'message' => $exception->getMessage(),
        ]);

        return back()
            ->withInput()
            ->withErrors([
                'message_text' => 'The message could not be sent. Please try again in a moment.',
            ]);
    }
}

private function analyzeSentiment(string $message): array
{
    $text = strtolower($message);

    if ($this->safetyKeywords->containsEmergencyLanguage($text)) {
        return ['emergency', 1.00];
    }

    if ($this->safetyKeywords->containsNegativeLanguage($text)) {
        return ['negative', 0.90];
    }

    if (
        str_contains($text, 'happy') ||
        str_contains($text, 'good') ||
        str_contains($text, 'great')
    ) {
        return ['positive', 0.90];
    }

    return ['neutral', 0.50];
}

private function ensureOwnSession(ChatSession $session): void
{
    abort_unless($session->user_id === Auth::id(), 404);
}

private function deepSeekReply(ChatSession $session, string $userMessage, string $sentiment): string
{
    $messages = Message::where('session_id', $session->session_id)
        ->latest()
        ->limit(8)
        ->get()
        ->reverse()
        ->map(fn (Message $message) => [
            'role' => $message->sender_type === 'student' ? 'user' : 'assistant',
            'content' => $message->message_text,
        ])
        ->values()
        ->all();

    array_unshift($messages, [
        'role' => 'system',
        'content' => 'You are a supportive university mental health chatbot. Be empathetic and concise. Never diagnose illnesses. Encourage healthy coping strategies and recommend contacting a counsellor, trusted person, or emergency service when safety is at risk.',
    ]);

    $reply = $this->deepSeek->chat($messages, maxTokens: 180, temperature: 0.7);

    if ($reply) {
        Log::info('DeepSeek chatbot reply generated.', [
            'user_id' => Auth::id(),
            'session_id' => $session->session_id,
            'user_message_length' => strlen($userMessage),
            'reply_length' => strlen($reply),
        ]);

        return $reply;
    }

    Log::warning('DeepSeek chatbot reply unavailable. Using fallback reply.', [
        'user_id' => Auth::id(),
        'session_id' => $session->session_id,
        'user_message_length' => strlen($userMessage),
    ]);

    return $this->fallbackReply($userMessage, $sentiment);
}

private function emergencyReply(): string
{
    return "I am really glad you told me. This needs real-world support now, not only a chatbot.\n\nPlease do these steps immediately:\n1. Move away from anything you could use to hurt yourself.\n2. Stay near a trusted person, roommate, family member, lecturer, or campus staff.\n3. Call Malaysia emergency services at 999, Talian Kasih at 15999, Befrienders KL at 03-7627 2929, or go to the nearest hospital.\n\nYou can also open the dashboard hospital map or use the counselling page after taking immediate safety steps.";
}

private function fallbackReply(string $userMessage, string $sentiment): string
{
    $text = strtolower($userMessage);

    if (str_contains($text, 'assignment') || str_contains($text, 'exam') || str_contains($text, 'study')) {
        return "It sounds like your study load is feeling heavy right now. Try breaking it into one small task you can finish in 10 minutes, then pause and take a slow breath before deciding the next step.\n\nIf this pressure keeps building, consider talking to a lecturer, friend, or counsellor so you do not have to carry it alone.";
    }

    if (str_contains($text, 'lost') || str_contains($text, 'alone') || str_contains($text, 'confused')) {
        return "Feeling lost can be really uncomfortable, especially when you are trying to handle everything at once. For now, try naming one thing that is making today difficult, then choose one small action that gives you a little stability.\n\nYou can also reach out to someone you trust or use the counselling section if you need more support.";
    }

    if ($sentiment === 'negative') {
        return "I hear that things feel difficult right now. Take a moment to slow down: breathe in for four counts, breathe out for six, and notice one thing around you that feels steady.\n\nWhen you are ready, write down the main thing that is bothering you and one small step you can take today.";
    }

    if ($sentiment === 'positive') {
        return "I'm glad there is something positive in your day. Try to notice what helped create that feeling, even if it was small, so you can repeat it when another day feels harder.";
    }

    return "Thank you for sharing that with me. Try checking in with yourself gently: what are you feeling, where do you feel it in your body, and what is one small thing that might help right now?\n\nYou can keep writing here. Short messages are okay.";
}
}
