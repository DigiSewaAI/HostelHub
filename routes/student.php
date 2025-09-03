<?php

use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\MealMenusController;
use App\Http\Controllers\Student\PaymentsController;
use App\Http\Controllers\Student\ProfileController;
use Illuminate\Support\Facades\Route;

// Student Routes (Logged-in Students)
Route::middleware(['auth', 'role:student']) // ✅ Correct role name
    ->prefix('student')                  // ✅ Required for URL structure: /student/dashboard
    ->name('student.')                   // ✅ Ensures route names like student.dashboard
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Profile Management
        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        // Meal Menus (View Only)
        Route::resource('meal-menus', MealMenusController::class)
            ->only(['index', 'show']); // Student can only view meal menus

        // Payments
        Route::get('/payments', [PaymentsController::class, 'index'])
            ->name('payments');
        Route::get('/payments/{payment}', [PaymentsController::class, 'show'])
            ->name('payments.show');

        // Additional student features (if needed)
        Route::get('/bookings', function () {
            return view('student.bookings.index');
        })->name('bookings');

        Route::get('/notifications', function () {
            return view('student.notifications.index');
        })->name('notifications');
    });
