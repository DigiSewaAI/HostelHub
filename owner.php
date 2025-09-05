<?php

use App\Http\Controllers\Owner\ContactsController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\GalleriesController;
use App\Http\Controllers\Owner\HostelController;
use App\Http\Controllers\Owner\MealMenusController;
use App\Http\Controllers\Owner\PaymentsController;
use App\Http\Controllers\Owner\ReviewsController;
use App\Http\Controllers\Owner\RoomsController;
use App\Http\Controllers\Owner\StudentsController;
use Illuminate\Support\Facades\Route;

// Owner Routes (Hostel Managers)
Route::middleware(['auth', 'role:hostel_manager']) // ✅ Correct role name from handover notes
    ->prefix('owner')                           // ✅ Required for URL structure
    ->name('owner.')                            // ✅ Ensures route names like owner.dashboard
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Hostel Management
        Route::resource('hostels', HostelController::class)
            ->only(['index', 'show', 'edit', 'update']); // Owner can only manage their own hostel

        // Meal Menus Management
        Route::resource('meal-menus', MealMenusController::class);

        // Rooms Management
        Route::resource('rooms', RoomsController::class);

        // Students Management
        Route::resource('students', StudentsController::class);

        // Payments Management
        Route::resource('payments', PaymentsController::class);

        // Reviews Management
        Route::resource('reviews', ReviewsController::class)
            ->only(['index', 'show', 'update']); // Owner can respond to reviews

        // Galleries Management
        Route::resource('galleries', GalleriesController::class);

        // Contacts Management
        Route::resource('contacts', ContactsController::class)
            ->only(['index', 'show', 'destroy']); // Owner can view and delete messages
    });
