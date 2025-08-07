<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
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

// 🌐 Public Website Routes (सार्वजनिक पृष्ठहरू)
Route::middleware('web')->group(function () {
    // Home and main pages
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/about', [PublicController::class, 'about'])->name('about');
    Route::get('/features', [PublicController::class, 'features'])->name('features');
    Route::get('/pricing', [PublicController::class, 'pricing'])->name('pricing');

    // Room booking and management
    Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms');
    Route::get('/rooms/{room}', [PublicRoomController::class, 'show'])->name('rooms.show');
    Route::get('/rooms/availability', [PublicRoomController::class, 'checkAvailability'])->name('rooms.availability');

    // Meal management
    Route::get('/meals', [PublicMealController::class, 'publicIndex'])->name('meals');
    Route::get('/meals/menu', [PublicMealController::class, 'menu'])->name('meals.menu');

    // Gallery
    Route::get('/gallery', [PublicGalleryController::class, 'publicIndex'])->name('gallery');
    Route::get('/gallery/{gallery}', [PublicGalleryController::class, 'publicShow'])->name('gallery.show');

    // Student information
    Route::get('/students', [PublicStudentController::class, 'index'])->name('students');
    Route::get('/students/{student}', [PublicStudentController::class, 'show'])->name('students.show');

    // Contact
    Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
    Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

    // Newsletter subscription
    Route::post('/newsletter/subscribe', [PublicController::class, 'subscribeNewsletter'])
         ->name('newsletter.subscribe');

    // Booking search
    Route::post('/booking/search', [PublicRoomController::class, 'search'])
         ->name('booking.search');

    // 🔐 Authentication Routes (प्रमाणीकरण मार्गहरू)
    Route::get('/signup', [AuthController::class, 'showRegistrationForm'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->name('signup.post');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Password reset routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
         ->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
         ->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
         ->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
         ->name('password.update');

    // Email verification routes
    Route::get('/verify-email', [AuthController::class, 'showVerifyEmail'])
         ->middleware('auth')
         ->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
         ->middleware(['auth', 'signed', 'throttle:6,1'])
         ->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
         ->middleware(['auth', 'throttle:6,1'])
         ->name('verification.send');
});

// 👤 User Profile Routes (प्रोफाइल पृष्ठहरू)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student management for authenticated users
    Route::get('/my-students', [PublicStudentController::class, 'myStudents'])->name('students.my');
    Route::get('/my-booking', [PublicRoomController::class, 'myBookings'])->name('bookings.my');
});

// 🚪 Dashboard Redirect (सार्वजनिक dashboard लाई admin मा redirect गर्ने)
Route::middleware(['auth', 'verified'])
    ->get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

// 👑 Admin Routes (प्रशासन पृष्ठहरू)
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // 📊 Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
             ->name('dashboard');

        // 👨‍🎓 Student Management
        Route::get('students/export', [AdminStudentController::class, 'export'])
             ->name('students.export')
             ->middleware('permission:export-students');
        Route::post('students/import', [AdminStudentController::class, 'import'])
             ->name('students.import')
             ->middleware('permission:import-students');
        Route::resource('students', AdminStudentController::class)
             ->middleware('permission:manage-students');

        // 🛏️ Room Management
        Route::get('rooms/availability', [RoomController::class, 'availability'])
             ->name('rooms.availability')
             ->middleware('permission:manage-rooms');
        Route::resource('rooms', RoomController::class)
             ->middleware('permission:manage-rooms');

        // 🍽️ Meal Management
        Route::resource('meals', MealController::class)
             ->middleware('permission:manage-meals');
        Route::get('meals/report', [MealController::class, 'report'])
             ->name('meals.report')
             ->middleware('permission:generate-meal-reports');

        // 🖼️ Gallery Management
        Route::resource('gallery', GalleryController::class)
             ->except('show') // Public show route already exists
             ->middleware('permission:manage-gallery');

        // 📬 Contact Management (Admin side)
        Route::resource('contacts', AdminContactController::class)
             ->only(['index', 'show', 'destroy'])
             ->middleware('permission:manage-contacts');

        // ⚙️ System Settings
        Route::get('settings', [DashboardController::class, 'settings'])
             ->name('settings')
             ->middleware('permission:manage-settings');
        Route::post('settings', [DashboardController::class, 'updateSettings'])
             ->name('settings.update')
             ->middleware('permission:manage-settings');

        // 📊 Reports
        Route::get('reports/occupancy', [DashboardController::class, 'occupancyReport'])
             ->name('reports.occupancy')
             ->middleware('permission:generate-reports');
        Route::get('reports/financial', [DashboardController::class, 'financialReport'])
             ->name('reports.financial')
             ->middleware('permission:generate-reports');
    });

// 🌐 API Routes for AJAX requests
Route::middleware(['auth', 'api'])->group(function () {
    Route::get('/api/rooms/availability', [PublicRoomController::class, 'apiAvailability']);
    Route::get('/api/students/search', [PublicStudentController::class, 'apiSearch']);
    Route::post('/api/bookings/create', [PublicRoomController::class, 'apiCreateBooking']);
});
