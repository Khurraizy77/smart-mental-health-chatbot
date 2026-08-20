<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mood_tracking', function (Blueprint $table) {
            $table->date('date')->nullable()->after('mood_type');
            $table->string('overall_sentiment')->nullable()->after('date');
            $table->unsignedTinyInteger('mood_score')->nullable()->after('overall_sentiment');
        });

        DB::table('mood_tracking')
            ->whereNull('date')
            ->orderBy('mood_id')
            ->chunkById(100, function ($moods): void {
                foreach ($moods as $mood) {
                    DB::table('mood_tracking')
                        ->where('mood_id', $mood->mood_id)
                        ->update([
                            'date' => substr((string) $mood->created_at, 0, 10),
                            'overall_sentiment' => $mood->mood_type,
                            'mood_score' => match ($mood->mood_type) {
                                'positive' => 8,
                                'negative' => 3,
                                'emergency' => 1,
                                default => 5,
                            },
                        ]);
                }
            }, 'mood_id');
    }

    public function down(): void
    {
        Schema::table('mood_tracking', function (Blueprint $table) {
            $table->dropColumn(['date', 'overall_sentiment', 'mood_score']);
        });
    }
};
