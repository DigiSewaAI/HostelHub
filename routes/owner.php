<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerDashboardController;

// FIXED: Use correct role name mapping
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
});
