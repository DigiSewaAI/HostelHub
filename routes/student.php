<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentDashboardController;

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});
