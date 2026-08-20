<?php

use App\Models\CounselingService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counseling_services', function (Blueprint $table) {
            $table->id('service_id');
            $table->string('service_name');
            $table->string('contact_info');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->id('referral_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('counseling_services', 'service_id')->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        foreach ($this->defaultServices() as $service) {
            CounselingService::firstOrCreate(
                ['service_name' => $service['service_name']],
                $service
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('counseling_services');
    }

    private function defaultServices(): array
    {
        return [
            [
                'service_name' => 'University Counselling Unit',
                'contact_info' => 'Contact your university counselling office',
                'description' => 'Book a private session with a counsellor for emotional support, stress, study pressure, or personal concerns.',
            ],
            [
                'service_name' => 'Emergency Hospital Support',
                'contact_info' => 'Use the dashboard hospital finder or call local emergency services',
                'description' => 'For urgent risk of suicide or self-harm, seek immediate in-person help from a hospital or emergency service.',
            ],
            [
                'service_name' => 'Trusted Person Check-In',
                'contact_info' => 'Friend, family member, lecturer, or campus staff',
                'description' => 'Ask someone safe to stay with you or help you contact support when things feel too heavy.',
            ],
        ];
    }
};
