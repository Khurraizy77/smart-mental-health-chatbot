<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id('student_id');
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('matric_no')->nullable();
            $table->string('program')->nullable();
            $table->string('faculty')->nullable();
            $table->unsignedTinyInteger('year_of_study')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->timestamps();
        });

        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->id('admin_id');
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('staff_no')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->timestamps();
        });

        User::where('role', 'student')->each(function (User $user): void {
            $user->studentProfile()->firstOrCreate([]);
        });

        User::where('role', 'admin')->each(function (User $user): void {
            $user->adminProfile()->firstOrCreate([]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_profiles');
        Schema::dropIfExists('student_profiles');
    }
};
