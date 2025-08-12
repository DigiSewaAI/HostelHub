<?php

use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HostelController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ContactController as PublicContactController;
use App\Http\Controllers\GalleryController as PublicGalleryController;
use App\Http\Controllers\MealController as PublicMealController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController as PublicRoomController;
use App\Http\Controllers\StudentController as PublicStudentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}

// 🌐 Public Website Routes (No Authentication Required)
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/features', [PublicController::class, 'features'])->name('features');
Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');
Route::get('/pricing', [PublicController::class, 'pricing'])->name('pricing');
Route::get('/reviews', [PublicController::class, 'reviews'])->name('reviews');

// Room Booking Routes
Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms');
Route::get('/rooms/{room}', [PublicRoomController::class, 'show'])->name('rooms.show');
Route::get('/rooms/availability', [PublicRoomController::class, 'checkAvailability'])->name('rooms.availability');
Route::post('/booking/search', [PublicRoomController::class, 'search'])->name('booking.search');

// Meal Routes
Route::get('/meals', [PublicMealController::class, 'publicIndex'])->name('meals');
Route::get('/meals/menu', [PublicMealController::class, 'menu'])->name('meals.menu');

// Gallery Routes
Route::get('/gallery', [PublicGalleryController::class, 'publicIndex'])->name('gallery');
Route::get('/gallery/category/{category}', function ($category) {
    return redirect()->route('gallery', ['category' => $category]);
})->name('gallery.category');

// Student Routes
Route::get('/students', [PublicStudentController::class, 'index'])->name('students');

// Contact Routes
Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

// 🔐 Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register.submit');

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.reset.store');

    // Email Verification Routes
    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// 👤 Authenticated User Routes (Requires Login)
Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Password Management
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // Password Confirmation
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Student & Booking Management
    Route::get('/my-students', [PublicStudentController::class, 'myStudents'])->name('students.my');
    Route::get('/my-bookings', [PublicRoomController::class, 'myBookings'])->name('bookings.my');

    // Payment Management
    Route::resource('payments', PaymentController::class)->only(['index', 'show', 'create', 'store']);
    Route::post('payments/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');

    // ✅ Secure logout route (POST only)
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// 👑 Admin Routes (Requires Admin Role)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Hostel Management
    Route::resource('hostels', HostelController::class);
    Route::post('hostels/{hostel}/availability', [HostelController::class, 'updateAvailability'])
        ->name('hostels.availability');

    // Room Management
    Route::resource('rooms', RoomController::class);
    Route::get('rooms/availability', [RoomController::class, 'availability'])
        ->name('rooms.availability');

    // Student Management
    Route::resource('students', AdminStudentController::class);

    // Meal Management
    Route::resource('meals', MealController::class);

    // Gallery Management
    Route::resource('gallery', GalleryController::class);
    Route::post('gallery/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
        ->name('gallery.toggle-featured');

    // Contact Management
    Route::resource('contacts', AdminContactController::class)
        ->only(['index', 'show', 'destroy']);

    // Payment Management
    Route::resource('payments', PaymentController::class)
        ->except(['create', 'store']);
    Route::post('payments/{payment}/status', [PaymentController::class, 'updateStatus'])
        ->name('payments.update-status');
});

// 🏨 Hostel Manager Routes (Requires Hostel Manager Role)
Route::middleware(['auth', 'role:hostel_manager'])->prefix('hostel-manager')->name('hostel.manager.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('hostel-manager.dashboard');
    })->name('dashboard');

    // Hostel Management
    Route::resource('hostels', HostelController::class)->only(['show', 'edit', 'update']);
    Route::post('hostels/{hostel}/availability', [HostelController::class, 'updateAvailability'])
        ->name('hostels.availability');

    // Room Management
    Route::resource('rooms', RoomController::class);
    Route::get('rooms/availability', [RoomController::class, 'availability'])
        ->name('rooms.availability');

    // Student Management
    Route::resource('students', AdminStudentController::class)->only(['index', 'show']);
});

// 🎓 Student Routes (Requires Student Role)
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');

    // Hostel & Room Management
    Route::get('/hostels', [HostelController::class, 'index'])->name('hostels.index');
    Route::get('/hostels/{hostel}', [HostelController::class, 'show'])->name('hostels.show');
    Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{room}', [PublicRoomController::class, 'show'])->name('rooms.show');

    // Booking Management
    Route::get('/my-bookings', [PublicRoomController::class, 'myBookings'])->name('bookings.my');
    Route::get('/booking/search', [PublicRoomController::class, 'search'])->name('booking.search');

    // Profile Management
    Route::get('/profile', [PublicStudentController::class, 'myProfile'])->name('profile');
    Route::patch('/profile', [PublicStudentController::class, 'updateProfile'])->name('profile.update');

    // Payment Management
    Route::resource('payments', PaymentController::class)->only(['index', 'show', 'create', 'store']);
    Route::post('payments/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');
});

// ✅ Optional GET logout for dev testing (remove in production)
if (app()->environment('local')) {
    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->middleware('auth');
}
