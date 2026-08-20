<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // These were temporary names before the ERD-aligned tables were renamed
        // to students and admins. They are safe to remove only when both the
        // current tables already exist.
        if (Schema::hasTable('students') && Schema::hasTable('student_profiles')) {
            Schema::dropIfExists('student_profiles');
        }

        if (Schema::hasTable('admins') && Schema::hasTable('admin_profiles')) {
            Schema::dropIfExists('admin_profiles');
        }
    }

    public function down(): void
    {
        //
    }
};
