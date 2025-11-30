<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Frontend\PublicController;
use App\Http\Controllers\Admin\HostelController as AdminHostelController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\RoomController;

// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}

// ✅ Booking routes
Route::get('/book-all/{slug}', [BookingController::class, 'createFromGalleryAllRooms'])->name('hostel.book.all.rooms');
Route::get('/book/{slug}', [BookingController::class, 'createFromGallery'])->name('hostel.book.from.gallery');
Route::get('/booking-success/{id}', [PublicController::class, 'bookingSuccessNew'])->name('frontend.booking.success');

// ✅ Basic pages
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showUserRegistrationForm'])->name('register');

/*|--------------------------------------------------------------------------
| Load Modular Route Files
|--------------------------------------------------------------------------*/
require __DIR__ . '/public.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/shared.php';

// ✅ Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Welcome page with booking summary
    Route::get('/welcome', [WelcomeController::class, 'showWelcome'])->name('user.welcome');

    // Convert guest booking to student booking
    Route::post('/convert-guest-booking/{bookingId}', [WelcomeController::class, 'convertGuestBooking'])->name('user.convert.guest.booking');

    // Complete student registration
    Route::post('/complete-student-registration', [WelcomeController::class, 'completeStudentRegistration'])->name('user.complete-registration');

    // Quick student registration
    Route::post('/student/quick-register', [WelcomeController::class, 'quickStudentRegistration'])->name('student.quick-register');

    // Check pending bookings (API)
    Route::get('/check-pending-bookings', [WelcomeController::class, 'checkPendingBookings'])->name('user.check-pending');

    // Booking statistics (API)
    Route::get('/booking/stats', [WelcomeController::class, 'getBookingStats'])->name('booking.stats');

    // User bookings management
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// ✅ Admin routes
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Featured hostels routes
        Route::get('/hostels/featured', [AdminHostelController::class, 'featuredHostels'])->name('admin.hostels.featured');
        Route::post('/hostels/featured/update', [AdminHostelController::class, 'updateFeaturedHostels'])->name('admin.hostels.featured.update');

        // ❌ REMOVED: Duplicate routes - Use the ones below instead
        // Route::post('/hostels/{hostel}/publish', [AdminHostelController::class, 'publishSingle'])->name('admin.hostels.publish.single');
        // Route::post('/hostels/{hostel}/unpublish', [AdminHostelController::class, 'unpublishSingle'])->name('admin.hostels.unpublish.single');

        require __DIR__ . '/admin.php';

        // Admin booking management routes
        Route::get('/bookings/pending', [BookingController::class, 'pendingApprovals'])->name('admin.bookings.pending');
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
        Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');
    });

// ✅ Owner routes
Route::prefix('owner')
    ->middleware(['auth', 'hasOrganization', 'role:owner,hostel_manager'])
    ->group(function () {
        require __DIR__ . '/owner.php';

        // Form reset route for circular form
        Route::post('/clear-form-flag', function () {
            session()->forget(['clear_form', 'success']);
            return response()->json(['success' => true]);
        })->name('owner.clear.form.flag');

        // Owner booking management routes
        Route::get('/bookings/pending', [BookingController::class, 'pendingApprovals'])->name('owner.bookings.pending');
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('owner.bookings.approve');
        Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('owner.bookings.reject');
        Route::get('/hostel/{hostelId}/bookings', [BookingController::class, 'hostelBookings'])->name('owner.hostel.bookings');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('owner.bookings.create');
    });

// ✅ Student routes
Route::prefix('student')
    ->middleware(['auth', 'role:student'])
    ->group(function () {
        require __DIR__ . '/student.php';

        // Student specific booking routes
        Route::get('/bookings', [BookingController::class, 'index'])->name('student.bookings.index');
        Route::get('/bookings/my', [BookingController::class, 'myBookings'])->name('student.bookings.my');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('student.bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('student.bookings.store');
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('student.bookings.show');
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('student.bookings.cancel');
    });

// ✅ SINGLE HOSTEL PUBLISH ROUTES (CORRECTED VERSION)
Route::post('/admin/hostels/{hostel}/publish', [AdminHostelController::class, 'publishSingle'])
    ->name('admin.hostels.publish.single')
    ->middleware(['auth', 'role:admin']);

Route::post('/admin/hostels/{hostel}/unpublish', [AdminHostelController::class, 'unpublishSingle'])
    ->name('admin.hostels.unpublish.single')
    ->middleware(['auth', 'role:admin']);

/*|--------------------------------------------------------------------------
| Development Routes (Conditionally Loaded)
|--------------------------------------------------------------------------*/
if (app()->environment('local')) {
    require __DIR__ . '/dev.php';
}

// ✅ ABSOLUTE GUARANTEED SOLUTION - PUT THIS IN web.php
Route::get('/force-publish/{id}', function ($id) {
    $user = Auth::user();
    if (!$user->hasRole('admin')) return "Unauthorized";

    $hostel = \App\Models\Hostel::find($id);
    $hostel->update(['is_published' => true, 'published_at' => now()]);

    return redirect('/admin/hostels')->with('success', 'होस्टल प्रकाशित गरियो!');
});

Route::get('/force-unpublish/{id}', function ($id) {
    $user = Auth::user();
    if (!$user->hasRole('admin')) return "Unauthorized";

    $hostel = \App\Models\Hostel::find($id);
    $hostel->update(['is_published' => false, 'published_at' => null]);

    return redirect('/admin/hostels')->with('success', 'होस्टल अप्रकाशित गरियो!');
});
