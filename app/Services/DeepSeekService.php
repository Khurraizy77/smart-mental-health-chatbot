<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    public function chat(array $messages, int $maxTokens = 500, float $temperature = 0.4, bool $json = false): ?string
    {
        $apiKey = config('services.deepseek.key');
        $baseUrl = rtrim((string) config('services.deepseek.url'), '/');
        $model = config('services.deepseek.model');

        if (blank($apiKey)) {
            Log::warning('DeepSeek API key is missing.');

            return null;
        }

        try {
            $payload = [
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
                'stream' => false,
            ];

            if ($json) {
                $payload['response_format'] = ['type' => 'json_object'];
            }

            $response = Http::withToken($apiKey)
                ->withUserAgent('SmartMentalHealthChatbot/1.0')
                ->acceptJson()
                ->asJson()
                ->connectTimeout(10)
                ->timeout(25)
                ->retry(2, 700)
                ->post($baseUrl.'/chat/completions', $payload);
        } catch (\Throwable $exception) {
            Log::warning('DeepSeek request failed.', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }

        if ($response->failed()) {
            Log::warning('DeepSeek returned an error response.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        return trim((string) data_get($response->json(), 'choices.0.message.content')) ?: null;
    }
}
