<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HostelController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\ContactController as PublicContactController;
use App\Http\Controllers\GalleryController as PublicGalleryController;
use App\Http\Controllers\MealController as PublicMealController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController as PublicRoomController;
use App\Http\Controllers\StudentController as PublicStudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| सबै routes "web" middleware मा हुन्छन्।
|
*/

// 🌐 सार्वजनिक पृष्ठहरू (Public Website Routes)
Route::middleware('web')->group(function () {
    // मुख्य पृष्ठहरू
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/about', [PublicController::class, 'about'])->name('about');
    Route::get('/features', [PublicController::class, 'features'])->name('features');
    Route::get('/pricing', [PublicController::class, 'pricing'])->name('pricing');

    // कोठा बुकिङ्ग सम्बन्धी
    Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms');
    Route::get('/rooms/{room}', [PublicRoomController::class, 'show'])->name('rooms.show');
    Route::get('/rooms/availability', [PublicRoomController::class, 'checkAvailability'])->name('rooms.availability');
    Route::post('/booking/search', [PublicRoomController::class, 'search'])->name('booking.search');

    // भोजन सम्बन्धी
    Route::get('/meals', [PublicMealController::class, 'publicIndex'])->name('meals');
    Route::get('/meals/menu', [PublicMealController::class, 'menu'])->name('meals.menu');

    // ग्यालरी सम्बन्धी
    Route::get('/gallery', [PublicGalleryController::class, 'publicIndex'])->name('gallery');

    // विद्यार्थी सम्बन्धी
    Route::get('/students', [PublicStudentController::class, 'index'])->name('students');

    // सम्पर्क सम्बन्धी
    Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
    Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

    // 🔐 प्रमाणीकरण (Authentication)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/signup', [AuthController::class, 'showRegistrationForm'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // पासवर्ड रिसेट
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// 👤 प्रोफाइल र व्यक्तिगत पृष्ठहरू
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // व्यक्तिगत विद्यार्थी र कोठा
    Route::get('/my-students', [PublicStudentController::class, 'myStudents'])->name('students.my');
    Route::get('/my-booking', [PublicRoomController::class, 'myBookings'])->name('bookings.my');

    // भुक्तानी
    Route::resource('payments', PaymentController::class)->only(['index', 'show', 'create', 'store']);
    Route::post('payments/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');
});

// 👑 प्रशासन पृष्ठहरू (Admin Routes)
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // छात्रावास प्रबन्धन
        Route::resource('hostels', HostelController::class);
        Route::post('hostels/{hostel}/availability', [HostelController::class, 'updateAvailability'])
             ->name('hostels.availability');

        // कोठा प्रबन्धन
        Route::resource('rooms', RoomController::class);
        Route::get('rooms/availability', [RoomController::class, 'availability'])
             ->name('rooms.availability');

        // विद्यार्थी प्रबन्धन
        Route::resource('students', AdminStudentController::class);

        // भोजन प्रबन्धन
        Route::resource('meals', MealController::class);

        // सम्पर्क प्रबन्धन
        Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'destroy']);

        // भुक्तानी प्रबन्धन
        Route::resource('payments', PaymentController::class)->except(['create', 'store']);
        Route::post('payments/{payment}/status', [PaymentController::class, 'updateStatus'])
             ->name('payments.updateStatus');
    });

// 🚪 Dashboard Redirect
Route::middleware(['auth', 'verified'])
    ->get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
