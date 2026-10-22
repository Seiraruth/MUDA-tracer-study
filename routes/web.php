<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AlumniController as AdminAlumniController;
use App\Http\Controllers\Admin\TracerResponsesController as AdminTracerResponsesController;
use App\Http\Controllers\Admin\JobPostingsController as AdminJobPostingsController;
use App\Http\Controllers\TracerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Fallback login named route
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

// Public Homepage & Statistics
Route::get('/', [TracerController::class, 'index'])->name('home');

// Public Statistics Page (renders the Blade frontend; backend data wiring can be connected later)
Route::view('/statistik', 'statistik')->name('statistik');

// Alumni Identity Login
Route::get('/alumni/login', [TracerController::class, 'showLogin'])->name('alumni.login');
Route::post('/alumni/login', [TracerController::class, 'processLogin'])->name('alumni.login.process');

// Alumni Questionnaire (Session Protected)
Route::get('/alumni/kuesioner', [TracerController::class, 'showKuesioner'])->name('tracer.kuesioner');
Route::post('/alumni/kuesioner', [TracerController::class, 'storeKuesioner'])->name('tracer.kuesioner.store');

// Success & Info Pages
Route::get('/alumni/sukses', [TracerController::class, 'success'])->name('tracer.sukses');
Route::get('/alumni/logout', [TracerController::class, 'logout'])->name('alumni.logout');

// Job Postings (BKK)
Route::get('/loker', [TracerController::class, 'loker'])->name('loker.index');

Route::get('/vue-test', function () {
    return Inertia::render('VueTest');
});

// Admin Authentication & Vue Dashboard
Route::prefix('admin')->group(function () {
    // Auth
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected Admin Panel
    Route::middleware('auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');

        // Data Alumni
        Route::get('/alumni', [AdminAlumniController::class, 'index'])->name('admin.alumni.index');
        Route::get('/alumni/export', [AdminAlumniController::class, 'export'])->name('admin.alumni.export');
        Route::get('/alumni/template', [AdminAlumniController::class, 'template'])->name('admin.alumni.template');
        Route::post('/alumni/import', [AdminAlumniController::class, 'import'])->name('admin.alumni.import');
        Route::post('/alumni', [AdminAlumniController::class, 'store'])->name('admin.alumni.store');
        Route::put('/alumni/{alumni}', [AdminAlumniController::class, 'update'])->name('admin.alumni.update');
        Route::delete('/alumni/{alumni}', [AdminAlumniController::class, 'destroy'])->name('admin.alumni.destroy');

        // Hasil Tracer Study
        Route::get('/tracer-responses', [AdminTracerResponsesController::class, 'index'])->name('admin.tracer.index');
        Route::delete('/tracer-responses/{tracerResponse}', [AdminTracerResponsesController::class, 'destroy'])->name('admin.tracer.destroy');

        // Lowongan Kerja BKK
        Route::get('/job-postings', [AdminJobPostingsController::class, 'index'])->name('admin.jobs.index');
        Route::post('/job-postings', [AdminJobPostingsController::class, 'store'])->name('admin.jobs.store');
        Route::put('/job-postings/{jobPosting}', [AdminJobPostingsController::class, 'update'])->name('admin.jobs.update');
        Route::delete('/job-postings/{jobPosting}', [AdminJobPostingsController::class, 'destroy'])->name('admin.jobs.destroy');
    });
});

