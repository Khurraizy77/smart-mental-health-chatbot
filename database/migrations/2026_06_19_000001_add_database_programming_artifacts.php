<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_activity_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('activity_type');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_mood_tracking_after_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_referrals_after_insert');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_user_mood_summary');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_system_summary');

        DB::unprepared("
            CREATE TRIGGER trg_mood_tracking_after_insert
            AFTER INSERT ON mood_tracking
            FOR EACH ROW
            INSERT INTO system_activity_logs (user_id, activity_type, description, created_at, updated_at)
            VALUES (
                NEW.user_id,
                'mood_recorded',
                CONCAT('Mood recorded: ', NEW.mood_type),
                NOW(),
                NOW()
            )
        ");

        DB::unprepared("
            CREATE TRIGGER trg_referrals_after_insert
            AFTER INSERT ON referrals
            FOR EACH ROW
            INSERT INTO system_activity_logs (user_id, activity_type, description, created_at, updated_at)
            VALUES (
                NEW.user_id,
                'referral_requested',
                CONCAT('Referral requested for service ID ', NEW.service_id),
                NOW(),
                NOW()
            )
        ");

        DB::unprepared("
            CREATE PROCEDURE sp_user_mood_summary(IN target_user_id BIGINT)
            BEGIN
                SELECT
                    mood_type,
                    COUNT(*) AS total_records,
                    ROUND(AVG(mood_score), 2) AS average_score,
                    MAX(date) AS latest_mood_date
                FROM mood_tracking
                WHERE user_id = target_user_id
                GROUP BY mood_type
                ORDER BY total_records DESC;
            END
        ");

        DB::unprepared("
            CREATE PROCEDURE sp_admin_system_summary()
            BEGIN
                SELECT
                    (SELECT COUNT(*) FROM users WHERE role = 'student') AS total_students,
                    (SELECT COUNT(*) FROM users WHERE role = 'admin') AS total_admins,
                    (SELECT COUNT(*) FROM chat_sessions) AS total_chat_sessions,
                    (SELECT COUNT(*) FROM messages) AS total_messages,
                    (SELECT COUNT(*) FROM mood_tracking WHERE mood_type = 'emergency') AS emergency_moods,
                    (SELECT COUNT(*) FROM referrals WHERE status = 'pending') AS pending_referrals;
            END
        ");
    }

    public function down(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_system_summary');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_user_mood_summary');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_referrals_after_insert');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_mood_tracking_after_insert');
        }

        Schema::dropIfExists('system_activity_logs');
    }
};
