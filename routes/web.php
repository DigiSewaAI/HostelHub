<?php

use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    NewPasswordController,
    PasswordController,
    PasswordResetLinkController,
    RegisteredUserController,
    VerifyEmailController
};
use App\Http\Controllers\{
    ContactController as PublicContactController,
    GalleryController as PublicGalleryController,
    MealController as PublicMealController,
    PaymentController,
    PublicController,
    ProfileController,
    RoomController as PublicRoomController,
    StudentController as PublicStudentController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/features', [PublicController::class, 'features'])->name('features');
Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');
Route::get('/pricing', [PublicController::class, 'pricing'])->name('pricing');
Route::get('/reviews', [PublicController::class, 'reviews'])->name('reviews');

// Room booking
Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms');
Route::get('/rooms/{room}', [PublicRoomController::class, 'show'])->name('rooms.show');
Route::get('/rooms/availability', [PublicRoomController::class, 'checkAvailability'])->name('rooms.availability');
Route::post('/booking/search', [PublicRoomController::class, 'search'])->name('booking.search');

// Meals
Route::get('/meals', [PublicMealController::class, 'publicIndex'])->name('meals');
Route::get('/meals/menu', [PublicMealController::class, 'menu'])->name('meals.menu');

// Gallery - Updated to use 'gallery.public' name
Route::get('/gallery', [PublicGalleryController::class, 'publicIndex'])->name('gallery.public');

// Students
Route::get('/students', [PublicStudentController::class, 'index'])->name('students');

// Contact
Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.submit');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.reset.store');

    Route::get('/verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role_id) {
            1 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('hostel.manager.dashboard'),
            3 => redirect()->route('student.dashboard'),
            default => redirect('/')->with('error', 'अमान्य प्रयोगकर्ता भूमिका'),
        };
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::get('/my-students', [PublicStudentController::class, 'myStudents'])->name('students.my');
    Route::get('/my-bookings', [PublicRoomController::class, 'myBookings'])->name('bookings.my');

    Route::resource('payments', PaymentController::class)
        ->only(['index', 'show', 'create', 'store'])
        ->names('user.payments');

    Route::post('payments/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Hostel Manager Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:hostel_manager'])
    ->prefix('hostel-manager')
    ->name('hostel.manager.')
    ->group(function () {
        Route::get('dashboard', fn() => view('hostel-manager.dashboard'))->name('dashboard');

        Route::resource('hostels', \App\Http\Controllers\Admin\HostelController::class)->only(['show', 'edit', 'update']);
        Route::post('hostels/{hostel}/availability', [\App\Http\Controllers\Admin\HostelController::class, 'updateAvailability'])->name('hostels.availability');

        Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);
        Route::get('rooms/availability', [\App\Http\Controllers\Admin\RoomController::class, 'availability'])->name('rooms.availability');

        Route::resource('students', \App\Http\Controllers\Admin\StudentController::class)->only(['index', 'show']);
    });

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('dashboard', fn() => view('student.dashboard'))->name('dashboard');

        Route::get('hostels', [\App\Http\Controllers\Admin\HostelController::class, 'index'])->name('hostels.index');
        Route::get('hostels/{hostel}', [\App\Http\Controllers\Admin\HostelController::class, 'show'])->name('hostels.show');

        Route::get('rooms', [PublicRoomController::class, 'index'])->name('rooms.index');
        Route::get('rooms/{room}', [PublicRoomController::class, 'show'])->name('rooms.show');

        Route::get('my-bookings', [PublicRoomController::class, 'myBookings'])->name('bookings.my');
        Route::get('booking/search', [PublicRoomController::class, 'search'])->name('booking.search');

        Route::get('profile', [PublicStudentController::class, 'myProfile'])->name('profile');
        Route::patch('profile', [PublicStudentController::class, 'updateProfile'])->name('profile.update');

        Route::resource('payments', PaymentController::class)->only(['index', 'show', 'create', 'store'])->names('student.payments');
        Route::post('payments/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');
    });

/*
|--------------------------------------------------------------------------
| Dev-Only GET Logout
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->middleware('auth');
}
