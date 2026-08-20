<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('student_profiles') && ! Schema::hasTable('students')) {
            Schema::rename('student_profiles', 'students');
        }

        if (Schema::hasTable('admin_profiles') && ! Schema::hasTable('admins')) {
            Schema::rename('admin_profiles', 'admins');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('students') && ! Schema::hasTable('student_profiles')) {
            Schema::rename('students', 'student_profiles');
        }

        if (Schema::hasTable('admins') && ! Schema::hasTable('admin_profiles')) {
            Schema::rename('admins', 'admin_profiles');
        }
    }
};
