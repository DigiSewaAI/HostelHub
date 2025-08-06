<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\ContactController as PublicContactController;
use App\Http\Controllers\GalleryController as PublicGalleryController;
use App\Http\Controllers\MealController as PublicMealController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RoomController as PublicRoomController;
use App\Http\Controllers\StudentController as PublicStudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public Website Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms');
Route::get('/rooms/{room}', [PublicRoomController::class, 'show'])->name('rooms.show');
Route::get('/meals', [PublicMealController::class, 'publicIndex'])->name('meals');
Route::get('/gallery', [PublicGalleryController::class, 'publicIndex'])->name('gallery');
Route::get('/gallery/{gallery}', [PublicGalleryController::class, 'publicShow'])->name('gallery.show');
Route::get('/students', [PublicStudentController::class, 'index'])->name('students'); // Public student route
Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

// Auth & Profile Routes (Default Laravel)
require __DIR__.'/auth.php';

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard Redirect (Public -> Admin)
Route::middleware(['auth', 'verified', 'role:admin'])->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

// Admin routes with proper namespace, prefix, and middleware
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard route
        Route::get('/dashboard', [DashboardController::class, 'index'])
             ->name('dashboard');

        // Student Management (CORRECTED ORDER)
        Route::get('students/export', [AdminStudentController::class, 'export'])
             ->name('students.export')
             ->middleware('permission:export-students');

        Route::resource('students', AdminStudentController::class)
             ->except(['create', 'store', 'update', 'destroy']) // Public has index, admin handles CRUD
             ->middleware([
                 'permission:manage-students'
             ]);

        // Room Management
        Route::resource('rooms', RoomController::class)
             ->middleware([
                 'permission:manage-rooms'
             ]);

        // Meal Management
        Route::resource('meals', MealController::class)
             ->middleware([
                 'permission:manage-meals'
             ]);

        // Gallery Management
        Route::resource('gallery', GalleryController::class)
             ->except('show') // Public show route exists
             ->middleware([
                 'permission:manage-gallery'
             ]);

        // Contact Management
        Route::resource('contacts', ContactController::class)
             ->only(['index', 'show', 'destroy'])
             ->middleware([
                 'permission:manage-contacts'
             ]);
    });
