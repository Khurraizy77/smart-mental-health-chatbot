<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sentiment_analysis', function (Blueprint $table) {

            $table->id('sentiment_id');

            $table->foreignId('message_id')
                  ->constrained('messages', 'message_id')
                  ->onDelete('cascade');

            $table->string('sentiment_type');

            $table->float('confidence_score');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sentiment_analysis');
    }
};