<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {

            $table->id('message_id');

            $table->foreignId('session_id')
                  ->constrained('chat_sessions', 'session_id')
                  ->onDelete('cascade');

            $table->string('sender_type');

            $table->text('message_text');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};