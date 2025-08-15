<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| यो section मा सबै admin panel routes राखिएको छ।
| RouteServiceProvider मा admin prefix पहिले नै define भएकाले 
| यहाँ पुन: prefix('admin') राख्न आवश्यक छैन।
|
| Middleware: auth + role:admin
| Name prefix: admin.
*/

Route::middleware(['auth', 'role:admin'])
     ->name('admin.')
     ->group(function () {

          // Dashboard
          Route::get('dashboard', [DashboardController::class, 'index'])
               ->name('dashboard'); // Route name: admin.dashboard

          // Students
          Route::resource('students', StudentController::class);
          Route::get('students/export', [StudentController::class, 'export'])
               ->name('students.export')
               ->middleware('permission:export-students');

          // Rooms
          Route::resource('rooms', RoomController::class);

          // Meals
          Route::resource('meals', MealController::class);

          // Gallery
          Route::resource('gallery', GalleryController::class);
          // Toggle featured and status actions for gallery
          Route::put('gallery/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
               ->name('gallery.toggle-featured');
          Route::put('gallery/{gallery}/toggle-status', [GalleryController::class, 'toggleActive'])
               ->name('gallery.toggle-status');

          // Contacts (Only index, show, and destroy)
          Route::resource('contacts', ContactController::class)
               ->only(['index', 'show', 'destroy']);
     });
