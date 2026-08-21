<?php

use App\Http\Controllers\MoodController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AdminReferralController;
use App\Http\Controllers\CounselingServiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WellbeingAssessmentController;
use App\Http\Controllers\AdminAssessmentController;
use App\Http\Controllers\WellbeingGuideController;


Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('student.dashboard');
});

Route::view('/about', 'about')->name('about');

Route::middleware(['auth'])->group(function () {

    Route::get('/mood', [MoodController::class, 'index']);
    Route::get('/wellbeing-guide', [WellbeingGuideController::class, 'index'])
        ->name('wellbeing.guide');

});

Route::middleware(['auth', 'student'])->group(function () {

    Route::get('/reports/user-analysis.pdf', [ReportController::class, 'userAnalysis'])
        ->name('reports.user');
    Route::get('/counseling-services', [CounselingServiceController::class, 'index'])
        ->name('counseling.index');
    Route::post('/referrals', [CounselingServiceController::class, 'requestReferral'])
        ->name('referrals.store');
    Route::post('/wellbeing-assessments', [WellbeingAssessmentController::class, 'store'])
        ->name('assessments.store');
    Route::get('/wellbeing-assessments/{assessment}', [WellbeingAssessmentController::class, 'show'])
        ->name('assessments.show');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    Route::post('/chat/new', [ChatController::class, 'createSession'])->name('chat.new');

    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send.latest');

    Route::get('/chat/{session}', [ChatController::class, 'show'])->name('chat.show');

    Route::get('/chat/{session}/send', fn ($session) => redirect()->route('chat.show', $session));

    Route::post('/chat/{session}/send', [ChatController::class, 'sendMessage'])->name('chat.send');

});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/admin/students', [AdminStudentController::class, 'index'])
        ->name('admin.students.index');
    Route::get('/admin/students/{student}/edit', [AdminStudentController::class, 'edit'])
        ->name('admin.students.edit');
    Route::put('/admin/students/{student}', [AdminStudentController::class, 'update'])
        ->name('admin.students.update');
    Route::delete('/admin/students/{student}', [AdminStudentController::class, 'destroy'])
        ->name('admin.students.destroy');
    Route::get('/admin/referrals', [AdminReferralController::class, 'index'])
        ->name('admin.referrals.index');
    Route::put('/admin/referrals/{referral}', [AdminReferralController::class, 'update'])
        ->name('admin.referrals.update');
    Route::get('/admin/assessments', [AdminAssessmentController::class, 'index'])
        ->name('admin.assessments.index');
    Route::put('/admin/assessments/{assessment}', [AdminAssessmentController::class, 'update'])
        ->name('admin.assessments.update');
    Route::get('/admin/reports/analysis.pdf', [ReportController::class, 'adminAnalysis'])
        ->name('admin.reports.analysis');

});

Route::middleware(['auth', 'student'])->group(function () {

    Route::get('/student/dashboard', [DashboardController::class, 'index'])
        ->name('student.dashboard');

});

});

require __DIR__.'/auth.php';
