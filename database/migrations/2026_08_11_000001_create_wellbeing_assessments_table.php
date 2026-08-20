<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wellbeing_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('answers');
            $table->unsignedTinyInteger('total_score');
            $table->string('wellbeing_level');
            $table->text('stress_reason')->nullable();
            $table->string('preferred_support')->nullable();
            $table->boolean('urgent_support')->default(false);
            $table->text('ai_wellbeing_summary')->nullable();
            $table->json('ai_main_concerns')->nullable();
            $table->json('ai_stress_factors')->nullable();
            $table->json('ai_suggestions')->nullable();
            $table->text('ai_recommended_support')->nullable();
            $table->text('ai_counselling_recommendation')->nullable();
            $table->string('ai_priority_level')->nullable();
            $table->timestamp('ai_generated_at')->nullable();
            $table->text('counsellor_notes')->nullable();
            $table->string('review_status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wellbeing_assessments');
    }
};
