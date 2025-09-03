<?php

use App\Http\Controllers\Admin\ContactsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleriesController;
use App\Http\Controllers\Admin\HostelsController;
use App\Http\Controllers\Admin\MealsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\RoomsController;
use App\Http\Controllers\Admin\StudentsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

// Admin Routes (Super Admin - You)
Route::middleware(['auth', CheckRole::class . ':admin'])
     ->prefix('admin')  // ✅ Required to make URLs like /admin/dashboard
     ->name('admin.')   // ✅ Ensures route names like admin.dashboard
     ->group(function () {
          // Dashboard
          Route::get('/dashboard', [DashboardController::class, 'index'])
               ->name('dashboard');

          // Students Management
          Route::resource('students', StudentsController::class);
          Route::get('students/export', [StudentsController::class, 'export'])
               ->name('students.export')
               ->middleware('permission:export-students');

          // Rooms Management
          Route::resource('rooms', RoomsController::class);

          // Meals Management
          Route::resource('meals', MealsController::class);

          // Galleries Management
          Route::resource('galleries', GalleriesController::class);

          Route::put('galleries/{gallery}/toggle-featured', [GalleriesController::class, 'toggleFeatured'])
               ->name('galleries.toggle-featured');

          Route::put('galleries/{gallery}/toggle-status', [GalleriesController::class, 'toggleActive'])
               ->name('galleries.toggle-status');

          // Contacts Management
          Route::resource('contacts', ContactsController::class)
               ->only(['index', 'show', 'destroy']);

          // Additional resources following the same pattern
          Route::resource('hostels', HostelsController::class);
          Route::resource('payments', PaymentsController::class);
          Route::resource('reviews', ReviewsController::class);
     });
