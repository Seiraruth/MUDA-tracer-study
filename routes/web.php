<?php

use App\Http\Controllers\TracerController;
use Illuminate\Support\Facades\Route;

// Public Homepage & Statistics
Route::get('/', [TracerController::class, 'index'])->name('home');

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
