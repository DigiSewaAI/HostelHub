<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

// Admin routes with proper namespace, prefix, and middleware
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard route (सबैभन्दा माथि हुनुपर्छ)
        Route::get('/dashboard', [DashboardController::class, 'index'])
             ->name('dashboard');

        // Student Management
        Route::resource('students', StudentController::class);

        // Export route must come BEFORE resource route declaration
        // BUT since we're using resource(), it's better to define it AFTER but with higher priority
        Route::get('students/export', [StudentController::class, 'export'])
             ->name('students.export')
             ->middleware('permission:export-students');

        // Room Management
        Route::resource('rooms', RoomController::class);

        // Meal Management
        Route::resource('meals', MealController::class);

        // Gallery Management
        Route::resource('gallery', GalleryController::class);

        // Contact Management
        Route::resource('contacts', ContactController::class)
             ->only(['index', 'show', 'destroy']);
    });
