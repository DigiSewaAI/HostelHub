<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

Route::middleware(['auth', CheckRole::class . ':admin'])
     ->name('admin.')
     ->group(function () {
          Route::get('dashboard', [DashboardController::class, 'index'])
               ->name('dashboard');

          Route::resource('students', StudentController::class);
          Route::get('students/export', [StudentController::class, 'export'])
               ->name('students.export')
               ->middleware('permission:export-students');

          Route::resource('rooms', RoomController::class);
          Route::resource('meals', MealController::class);
          Route::resource('gallery', GalleryController::class);

          Route::put('gallery/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
               ->name('gallery.toggle-featured');

          Route::put('gallery/{gallery}/toggle-status', [GalleryController::class, 'toggleActive'])
               ->name('gallery.toggle-status');

          Route::resource('contacts', ContactController::class)
               ->only(['index', 'show', 'destroy']);
     });
