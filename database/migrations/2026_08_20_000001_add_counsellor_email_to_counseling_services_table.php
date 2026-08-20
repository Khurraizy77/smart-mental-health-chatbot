<?php

use App\Models\CounselingService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counseling_services', function (Blueprint $table): void {
            $table->string('counsellor_email')->nullable()->after('contact_info');
        });

        CounselingService::updateOrCreate(
            ['service_name' => 'Counsellor Appointment Support'],
            [
                'contact_info' => env('COUNSELLOR_EMAIL', 'counsellor@example.com'),
                'counsellor_email' => env('COUNSELLOR_EMAIL', 'counsellor@example.com'),
                'description' => 'Send a counselling request directly to an assigned counsellor so they can review your details and arrange an appointment.',
            ]
        );

        CounselingService::where('service_name', 'University Counselling Unit')
            ->whereNull('counsellor_email')
            ->update([
                'counsellor_email' => env('COUNSELLOR_EMAIL', 'counsellor@example.com'),
            ]);
    }

    public function down(): void
    {
        Schema::table('counseling_services', function (Blueprint $table): void {
            $table->dropColumn('counsellor_email');
        });
    }
};
