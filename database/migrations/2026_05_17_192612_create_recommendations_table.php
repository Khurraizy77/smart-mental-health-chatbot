<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {

            $table->id('recommendation_id');

            $table->string('sentiment_type');

            $table->text('recommendation_text');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};