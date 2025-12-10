<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PricingController;
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

// ✅ FIXED: CORRECT GALLERY ROUTES - Added at top as requested
Route::get('/gallery', [App\Http\Controllers\Frontend\GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{tab}', [App\Http\Controllers\Frontend\GalleryController::class, 'index'])
    ->where('tab', 'photos|videos|virtual-tours')
    ->name('gallery.tab');

// ✅ FIXED: Gallery and Booking Routes for Public Hostel Pages
Route::get('/hostels/{slug}/full-gallery', [\App\Http\Controllers\Frontend\PublicController::class, 'hostelFullGallery'])
    ->name('hostels.full.gallery');

Route::get('/hostels/{slug}/book', [\App\Http\Controllers\BookingController::class, 'createFromGallery'])
    ->name('hostels.book');

// ✅ Booking routes
Route::get('/book-all/{slug}', [BookingController::class, 'createFromGalleryAllRooms'])->name('hostel.book.all.rooms');
Route::get('/book/{slug}', [BookingController::class, 'createFromGallery'])->name('hostel.book.from.gallery');
Route::get('/booking-success/{id}', [PublicController::class, 'bookingSuccessNew'])->name('frontend.booking.success');

// ✅ Basic pages
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

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

        // ✅ CORRECTED: Single hostel publish/unpublish routes
        Route::post('/hostels/{hostel}/publish', [AdminHostelController::class, 'publishSingle'])
            ->name('admin.hostels.publish.single');

        Route::post('/hostels/{hostel}/unpublish', [AdminHostelController::class, 'unpublishSingle'])
            ->name('admin.hostels.unpublish.single');

        // ✅ NEW: Bulk operations route for hostel management
        Route::post('/hostels/bulk-operations', [AdminHostelController::class, 'bulkOperations'])
            ->name('admin.hostels.bulk-operations');

        // ✅ EMERGENCY WORKING ROUTES - GUARANTEED SOLUTION
        Route::post('/hostels/{id}/publish-now', function ($id) {
            $hostel = \App\Models\Hostel::findOrFail($id);
            $hostel->update([
                'is_published' => true,
                'published_at' => now()
            ]);
            return redirect()->route('admin.hostels.index')->with('success', 'होस्टल प्रकाशित गरियो!');
        })->name('admin.hostels.publish.now');

        Route::post('/hostels/{id}/unpublish-now', function ($id) {
            $hostel = \App\Models\Hostel::findOrFail($id);
            $hostel->update([
                'is_published' => false,
                'published_at' => null
            ]);
            return redirect()->route('admin.hostels.index')->with('success', 'होस्टल अप्रकाशित गरियो!');
        })->name('admin.hostels.unpublish.now');

        // 🎨 NEW: Gallery Cache Management Routes (Step 8)
        Route::get('/gallery/refresh-cache', function () {
            Artisan::call('gallery:refresh');
            return back()->with('success', 'Gallery cache refreshed successfully!');
        })->name('gallery.refresh.cache');

        Route::get('/gallery/stats', [\App\Http\Controllers\Frontend\PublicController::class, 'getGalleryStats'])
            ->name('gallery.stats');

        require __DIR__ . '/admin.php';

        // Admin booking management routes
        Route::get('/bookings/pending', [BookingController::class, 'pendingApprovals'])->name('admin.bookings.pending');
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
        Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');

        // ✅ NEW: Admin gallery management routes
        Route::get('/galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'index'])->name('admin.galleries.index');
        Route::get('/galleries/create', [\App\Http\Controllers\Admin\GalleryController::class, 'create'])->name('admin.galleries.create');
        Route::post('/galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('admin.galleries.store');
        Route::get('/galleries/{gallery}/edit', [\App\Http\Controllers\Admin\GalleryController::class, 'edit'])->name('admin.galleries.edit');
        Route::put('/galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'update'])->name('admin.galleries.update');
        Route::delete('/galleries/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('admin.galleries.destroy');
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

        // ✅ NEW: Owner gallery management routes
        Route::get('/galleries', [\App\Http\Controllers\Owner\GalleryController::class, 'index'])->name('owner.galleries.index');
        Route::get('/galleries/create', [\App\Http\Controllers\Owner\GalleryController::class, 'create'])->name('owner.galleries.create');
        Route::post('/galleries', [\App\Http\Controllers\Owner\GalleryController::class, 'store'])->name('owner.galleries.store');
        Route::get('/galleries/{gallery}/edit', [\App\Http\Controllers\Owner\GalleryController::class, 'edit'])->name('owner.galleries.edit');
        Route::put('/galleries/{gallery}', [\App\Http\Controllers\Owner\GalleryController::class, 'update'])->name('owner.galleries.update');
        Route::delete('/galleries/{gallery}', [\App\Http\Controllers\Owner\GalleryController::class, 'destroy'])->name('owner.galleries.destroy');
        Route::get('/hostel/{hostel}/galleries', [\App\Http\Controllers\Owner\GalleryController::class, 'hostelGalleries'])->name('owner.hostel.galleries');
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

/*|--------------------------------------------------------------------------
| Development Routes (Conditionally Loaded)
|--------------------------------------------------------------------------*/
if (app()->environment('local')) {
    require __DIR__ . '/dev.php';
}

// ✅ ABSOLUTE GUARANTEED SOLUTION - EMERGENCY BACKUP ROUTES
Route::get('/force-publish/{id}', function ($id) {
    $user = Auth::user();
    if (!$user || !$user->hasRole('admin')) {
        return redirect('/login')->with('error', 'Unauthorized');
    }

    $hostel = \App\Models\Hostel::find($id);
    if (!$hostel) {
        return redirect('/admin/hostels')->with('error', 'होस्टल फेला परेन');
    }

    $hostel->update([
        'is_published' => true,
        'published_at' => now()
    ]);

    return redirect('/admin/hostels')->with('success', 'होस्टल प्रकाशित गरियो!');
})->middleware(['auth', 'role:admin']);

Route::get('/force-unpublish/{id}', function ($id) {
    $user = Auth::user();
    if (!$user || !$user->hasRole('admin')) {
        return redirect('/login')->with('error', 'Unauthorized');
    }

    $hostel = \App\Models\Hostel::find($id);
    if (!$hostel) {
        return redirect('/admin/hostels')->with('error', 'होस्टल फेला परेन');
    }

    $hostel->update([
        'is_published' => false,
        'published_at' => null
    ]);

    return redirect('/admin/hostels')->with('success', 'होस्टल अप्रकाशित गरियो!');
})->middleware(['auth', 'role:admin']);

// Temporary route थप्नुहोस् routes/web.php मा:
Route::get('/debug-hostels', function () {
    $hostels = \App\Models\Hostel::where('is_published', true)->get();

    $result = [];
    foreach ($hostels as $hostel) {
        $result[] = [
            'id' => $hostel->id,
            'name' => $hostel->name,
            'gender' => $hostel->gender,
            'gender_detected' => $hostel->gender_detected,
            'is_published' => $hostel->is_published
        ];
    }

    return response()->json($result);
});

// Temporary route थप्नुहोस् routes/web.php मा:
Route::get('/verify-boys', function () {
    $hostels = \App\Models\Hostel::where('is_published', true)->get();

    $boys = [];
    foreach ($hostels as $hostel) {
        if ($hostel->gender_detected === 'boys') {
            $boys[] = [
                'id' => $hostel->id,
                'name' => $hostel->name,
                'gender_column' => $hostel->gender,
                'gender_detected' => $hostel->gender_detected
            ];
        }
    }

    return response()->json([
        'boys_hostels_count' => count($boys),
        'boys_hostels' => $boys,
        'all_published' => $hostels->count()
    ]);
});

// ✅ EMERGENCY BACKUP ROUTES - GUARANTEED WORKING
Route::get('/view/{slug}/full-gallery', function ($slug) {
    return redirect()->route('hostels.full.gallery', $slug);
})->name('emergency.full.gallery');

Route::get('/book-now/{slug}', function ($slug) {
    return redirect()->route('hostels.book', $slug);
})->name('emergency.book.now');

// Add these route definitions
Route::get('/direct-gallery/{slug}', function ($slug) {
    return redirect()->route('hostels.full.gallery', $slug);
})->name('direct.hostel.gallery');

Route::get('/direct-book/{slug}', function ($slug) {
    return redirect()->route('hostels.book', $slug);
})->name('direct.book.now');
