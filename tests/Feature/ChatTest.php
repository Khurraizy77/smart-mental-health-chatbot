<?php

use App\Models\ChatSession;
use App\Models\Message;
use App\Models\User;
use App\Services\DeepSeekService;

class UnavailableDeepSeekServiceForChatTest extends DeepSeekService
{
    public function chat(array $messages, int $maxTokens = 500, float $temperature = 0.4, bool $json = false): ?string
    {
        return null;
    }
}

test('chat stores a fallback bot reply when deepseek is unavailable', function () {
    $this->app->instance(DeepSeekService::class, new UnavailableDeepSeekServiceForChatTest());

    $student = User::factory()->create([
        'role' => 'student',
    ]);

    $session = ChatSession::create([
        'user_id' => $student->id,
    ]);

    $this->actingAs($student)
        ->post(route('chat.send', $session), [
            'message_text' => 'I feel overwhelmed with my assignment today',
        ])
        ->assertRedirect(route('chat.show', $session));

    expect(Message::where('session_id', $session->session_id)->count())->toBe(2);

    $botMessage = Message::where('session_id', $session->session_id)
        ->where('sender_type', 'chatbot')
        ->first();

    expect($botMessage)->not->toBeNull()
        ->and($botMessage->message_text)->toContain('study load')
        ->and($botMessage->message_text)->toContain('one small task');
});
