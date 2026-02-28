<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Frontend\PublicController;
use App\Http\Controllers\Admin\HostelController as AdminHostelController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Owner\OwnerRoomIssuesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Student\StudentReviewController;
use App\Http\Controllers\Student\StudentCircularController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Network\ProfileController;
use App\Http\Controllers\Network\MessageController;
use App\Http\Controllers\Network\BroadcastController;
use App\Http\Controllers\Network\MarketplaceController;
use App\Http\Controllers\Network\DirectoryController;


// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}

// ✅ FIX: Add this simple health check at TOP of web.php
Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'time' => now()]);
});

Route::get('/', function () {
    return 'HostelHub is running! Go to /health for status.';
});

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

// ✅ Hostel Contact Form Route
Route::post('/hostels/{hostel}/contact', [\App\Http\Controllers\Frontend\PublicController::class, 'hostelContact'])->name('hostels.contact');

// ✅ Booking routes
Route::get('/book-all/{slug}', [BookingController::class, 'createFromGalleryAllRooms'])->name('hostel.book.all.rooms');
Route::get('/book/{slug}', [BookingController::class, 'createFromGallery'])->name('hostel.book.from.gallery');
Route::get('/booking-success/{id}', [PublicController::class, 'bookingSuccessNew'])->name('frontend.booking.success');

// ✅ Basic pages
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])->name('login');
Route::get('/register', [RegisterController::class, 'showUserRegistrationForm'])->name('register');

/*|--------------------------------------------------------------------------
| Load Modular Route Files
|--------------------------------------------------------------------------*/
require __DIR__ . '/public.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/shared.php';

// web.php मा TOP मा (auth routes भन्दा पहिले)
Route::post('/reset-password-temp', function (Illuminate\Http\Request $request) {
    // Direct call to the controller
    return app()->make(\App\Http\Controllers\Auth\NewPasswordController::class)->store($request);
})->name('password.reset.temp');

// ✅ Authenticated user routes

// ✅ Fetch latest notifications (API for bell dropdown)
Route::middleware('auth')->get('/notifications', function () {
    return auth()->user()
        ->notifications()
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($notification) {
            return [
                'id' => $notification->id,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at->toIso8601String(),
            ];
        });
})->name('notifications.api');

// ✅ Notification mark as read (सबै प्रमाणित प्रयोगकर्ताका लागि)
Route::post('/notifications/{id}/mark-as-read', function (Request $request, $id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return response()->json(['success' => true]);
})->name('notifications.mark-as-read');
Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');

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

        // ✅ Network Moderation Routes
        Route::get('/moderation', [ModerationController::class, 'index'])->name('admin.moderation.index');
        Route::post('/moderation/broadcast/{id}/approve', [ModerationController::class, 'approveBroadcast'])->name('admin.moderation.broadcast.approve');
        Route::post('/moderation/broadcast/{id}/reject', [ModerationController::class, 'rejectBroadcast'])->name('admin.moderation.broadcast.reject');
        Route::post('/moderation/listing/{id}/approve', [ModerationController::class, 'approveListing'])->name('admin.moderation.listing.approve');
        Route::post('/moderation/listing/{id}/reject', [ModerationController::class, 'rejectListing'])->name('admin.moderation.listing.reject');

        // Admin booking management routes
        Route::get('/bookings/pending', [BookingController::class, 'pendingApprovals'])->name('admin.bookings.pending');
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
        Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');
    });

// ✅ Owner routes
Route::prefix('owner')
    ->middleware(['auth', 'hasOrganization', 'role:owner,hostel_manager'])  // 'owner' middleware हटाउनुहोस्
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

        // Owner message routes
        Route::get('/hostels/{hostel}/messages', [\App\Http\Controllers\Owner\HostelController::class, 'messages'])
            ->name('owner.hostel.messages');

        Route::post('/messages/{message}/mark-read', [\App\Http\Controllers\Owner\HostelController::class, 'markAsRead'])
            ->name('owner.messages.mark-read');

        // ✅ NEW: Owner room issues management routes (यसरी update गर्नुहोस्)
        Route::get('/room-issues', [\App\Http\Controllers\Owner\OwnerRoomIssuesController::class, 'index'])->name('owner.room-issues.index');
        Route::get('/room-issues/{id}', [\App\Http\Controllers\Owner\OwnerRoomIssuesController::class, 'show'])->name('owner.room-issues.show');
        Route::patch('/room-issues/{id}', [\App\Http\Controllers\Owner\OwnerRoomIssuesController::class, 'update'])->name('owner.room-issues.update');
        Route::delete('/room-issues/{id}', [\App\Http\Controllers\Owner\OwnerRoomIssuesController::class, 'destroy'])->name('owner.room-issues.destroy');
        Route::get('/room-issues/stats', [\App\Http\Controllers\Owner\OwnerRoomIssuesController::class, 'getStats'])->name('owner.room-issues.stats');
    });

// ✅ Network Features Routes (Owner/User Network)

Route::middleware(['auth', \App\Http\Middleware\EnsureEligibleForNetwork::class])
    ->prefix('network')
    ->name('network.')
    ->group(function () {


        // Profile (read‑only)
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

        // Messages
        Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{thread}', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{thread}/archive', [MessageController::class, 'archive'])->name('messages.archive');
        Route::post('/messages', [MessageController::class, 'store'])
            ->middleware('throttle:30,1')
            ->name('messages.store');

        // Broadcast
        Route::get('/broadcast/create', [BroadcastController::class, 'create'])->name('broadcast.create');
        Route::post('/broadcast', [BroadcastController::class, 'store'])->name('broadcast.store');
        Route::get('/broadcast', [BroadcastController::class, 'index'])->name('broadcast.index');

        // Marketplace
        Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
        Route::get('/marketplace/create', [MarketplaceController::class, 'create'])->name('marketplace.create');
        Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store');
        Route::get('/marketplace/{listing:slug}', [MarketplaceController::class, 'show'])->name('marketplace.show');
        Route::post('/marketplace/{listing}/contact', [MarketplaceController::class, 'contact'])->name('marketplace.contact');
        Route::get('/marketplace/{listing}/edit', [MarketplaceController::class, 'edit'])->name('marketplace.edit');
        Route::put('/marketplace/{listing}', [MarketplaceController::class, 'update'])->name('marketplace.update');
        Route::delete('/marketplace/{listing}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');


        // Directory
        Route::get('/directory', [DirectoryController::class, 'index'])->name('directory.index');
        Route::get('/messages/create', [App\Http\Controllers\Network\MessageController::class, 'create'])->name('messages.create');
    });

// Public Bazar
Route::prefix('bazar')->name('public.bazar.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\BazarController::class, 'index'])->name('index');
    Route::get('/{slug}', [App\Http\Controllers\Public\BazarController::class, 'show'])->name('show');
});

// ✅ Student routes - सरल र सही version
// केवल student.php file include गर्ने
require __DIR__ . '/student.php';

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

Route::get('/create-svg-logo/{hostelId}', function ($hostelId = 15) {
    $hostel = \App\Models\Hostel::find($hostelId);

    if (!$hostel) {
        return "Hostel not found";
    }

    $name = $hostel->name;
    $initial = strtoupper(substr(trim($name), 0, 1));
    if (empty($initial)) $initial = 'H';

    // Create SVG
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
    <svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
        <rect width="400" height="300" fill="#3b82f6" rx="20"/>
        <text x="200" y="150" font-family="Arial, sans-serif" font-size="80" 
              fill="white" text-anchor="middle" dy=".3em" font-weight="bold">
            ' . $initial . '
        </text>
        <text x="200" y="220" font-family="Arial, sans-serif" font-size="24" 
              fill="white" text-anchor="middle">
            ' . htmlspecialchars($name) . '
        </text>
    </svg>';

    // Save as SVG
    $filename = 'hostel_logos/logo_' . $hostelId . '.svg';
    $path = storage_path('app/public/' . $filename);

    // Ensure directory exists
    if (!file_exists(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }

    file_put_contents($path, $svg);

    // Update database
    $hostel->logo_path = $filename;
    $hostel->save();

    return "<h2>✅ SVG Logo Created!</h2>
            <p>Saved as: {$filename}</p>
            <div style='width: 200px; height: 150px; border: 2px solid green;'>
                " . $svg . "
            </div>
            <p><a href='/test-logo-system/{$hostelId}' target='_blank'>Test Logo System</a></p>";
});

// In routes/web.php
Route::get('/debug-logo/{hostelId}', function ($hostelId) {
    $hostel = \App\Models\Hostel::find($hostelId);

    if (!$hostel) {
        return 'Hostel not found';
    }

    $storagePath = storage_path('app/public/' . $hostel->logo_path);
    $publicPath = public_path('storage/' . $hostel->logo_path);

    return [
        'hostel' => $hostel->name,
        'logo_path_in_db' => $hostel->logo_path,
        'storage_path' => $storagePath,
        'storage_exists' => file_exists($storagePath),
        'public_path' => $publicPath,
        'public_exists' => file_exists($publicPath),
        'url' => asset('storage/' . $hostel->logo_path),
    ];
});

// web.php मा यो route थप्नुहोस्
Route::get('/debug-token', function (Request $request) {
    $email = $request->email;
    $token = $request->token;

    config(['database.default' => 'mysql']);

    // Check if token exists
    $tokenRecord = DB::table('password_reset_tokens')
        ->where('email', $email)
        ->first();

    if (!$tokenRecord) {
        return response()->json([
            'error' => 'Token not found in database',
            'email' => $email,
            'table' => 'password_reset_tokens'
        ]);
    }

    // Verify token hash
    $isValid = Hash::check($token, $tokenRecord->token);

    return response()->json([
        'email' => $email,
        'token_provided' => $token,
        'token_in_db' => $tokenRecord->token,
        'token_valid' => $isValid,
        'created_at' => $tokenRecord->created_at,
        'expires_in_minutes' => now()->diffInMinutes($tokenRecord->created_at),
        'is_expired' => now()->diffInMinutes($tokenRecord->created_at) > 60
    ]);
});

// Debug route for Railway
Route::get('/debug-env', function () {
    return [
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'app_url' => config('app.url'),
        'db_connection' => config('database.default'),
        'db_host' => config('database.connections.mysql.host'),
        'db_database' => config('database.connections.mysql.database'),
        'mail_mailer' => config('mail.default'),
        'mail_host' => config('mail.mailers.smtp.host'),
        'mail_port' => config('mail.mailers.smtp.port'),
        'mail_username' => config('mail.mailers.smtp.username'),
        'mail_password_set' => !empty(config('mail.mailers.smtp.password')),
        'filesystem_disk' => config('filesystems.default'),
        'storage_url' => Storage::disk('public')->url('test'),
    ];
});

// Test registration without email verification
Route::get('/test-register/{email}/{password}', function ($email, $password) {
    try {
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('student');

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user_id' => $user->id,
            'email' => $user->email
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// ✅ STEP 5: EMERGENCY - Direct registration route with plan parameter
Route::get('/register-org/{plan}', function ($plan) {
    // Validate plan
    $validPlans = ['starter', 'pro', 'enterprise'];
    if (!in_array($plan, $validPlans)) {
        return redirect()->route('pricing')->with('error', 'Invalid plan selected.');
    }

    // Store plan in session
    session(['registration_plan' => $plan]);

    // Redirect to registration with plan parameter
    return redirect()->route('register.organization', ['plan' => $plan]);
})->name('register.organization.direct');

// ✅ STEP 6: Test routes for registration debugging
Route::get('/test-registration/{plan}', function ($plan) {
    return response()->json([
        'plan_from_route' => $plan,
        'plan_in_session' => session('registration_plan'),
        'valid_plans' => ['starter', 'pro', 'enterprise'],
        'is_valid' => in_array($plan, ['starter', 'pro', 'enterprise'])
    ]);
});

Route::post('/test-registration-submit', function (Request $request) {
    return response()->json([
        'received_plan' => $request->plan,
        'all_data' => $request->all(),
        'session_plan' => session('registration_plan')
    ]);
});

// Media streaming route for Railway
Route::get('/media/{path}', function ($path) {
    $storagePath = 'public/' . $path;

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($path);
    $type = Storage::disk('public')->mimeType($path);

    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Content-Disposition', 'inline');
})->where('path', '.*')->name('media.stream');
