<?php

use Illuminate\Foundation\Inspiring;
use App\Services\DeepSeekService;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('deepseek:test', function (DeepSeekService $deepSeek) {
    $key = config('services.deepseek.key');
    $url = rtrim((string) config('services.deepseek.url'), '/');
    $model = config('services.deepseek.model');

    $this->line('DeepSeek URL: '.$url);
    $this->line('DeepSeek model: '.$model);
    $this->line('DeepSeek key: '.(blank($key) ? 'missing' : 'set'));

    if (blank($key)) {
        $this->error('DEEPSEEK_API_KEY is missing in .env.');

        return self::FAILURE;
    }

    $reply = $deepSeek->chat([
        [
            'role' => 'user',
            'content' => 'Reply with OK only.',
        ],
    ], maxTokens: 8);

    if (! $reply) {
        $this->error('DeepSeek returned no reply. Check storage/logs/laravel.log for the exact HTTP or cURL error.');
        return self::FAILURE;
    }

    $this->line('HTTP status: 200');
    $this->info('DeepSeek reply: '.$reply);

    return self::SUCCESS;
})->purpose('Test the configured DeepSeek chat API connection');
