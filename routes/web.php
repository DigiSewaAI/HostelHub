<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Frontend\PublicController;


// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}

// ✅ NEW: Gallery booking route for ALL ROOMS (big button at bottom)
Route::get('/book-all/{slug}', [BookingController::class, 'createFromGalleryAllRooms'])->name('hostel.book.all.rooms');

// ✅ EXISTING: Gallery booking route for SPECIFIC ROOM (small buttons and modal)
Route::get('/book/{slug}', [BookingController::class, 'createFromGallery'])->name('hostel.book.from.gallery');

// ✅ FIXED: Add the missing booking store route for NEW booking system
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

// ✅ FIXED: Add public guest success route (moved from auth middleware)
Route::get('/booking/guest-success/{id}', [BookingController::class, 'guestBookingSuccess'])->name('booking.guest-success');

Route::get('/booking-success/{id}', [PublicController::class, 'bookingSuccess'])
    ->name('frontend.booking.success');

// ✅ UPDATED: Test Email Routes with room_id fix
Route::get('/test-email-system', function () {
    try {
        // Test booking create गर्छौं
        $hostel = \App\Models\Hostel::first();

        if (!$hostel) {
            // यदि hostel छैन भने test hostel create गर्छौं
            $hostel = \App\Models\Hostel::create([
                'name' => 'Test Hostel',
                'address' => 'Test Address, Kathmandu',
                'contact_phone' => '9800000000',
                'description' => 'Test Hostel for email testing',
                'status' => 'active',
                'owner_id' => 1,
                'organization_id' => 1,
            ]);
        }

        // Get or create a room for this hostel
        $room = \App\Models\Room::where('hostel_id', $hostel->id)->first();
        if (!$room) {
            $room = \App\Models\Room::create([
                'hostel_id' => $hostel->id,
                'room_number' => '101',
                'capacity' => 2,
                'price_per_bed' => 2500.00,
                'status' => 'available',
                'description' => 'Test Room'
            ]);
        }

        $booking = \App\Models\Booking::create([
            'hostel_id' => $hostel->id,
            'room_id' => $room->id, // ✅ FIXED: room_id added
            'check_in_date' => now()->addDays(7),
            'check_out_date' => now()->addDays(14),
            'guest_name' => 'Ashish Regmi',
            'guest_email' => 'regmiashish629@gmail.com',
            'guest_phone' => '9803640099',
            'is_guest_booking' => true,
            'status' => 'pending',
            'booking_date' => now(),
            'amount' => 5000.00,
            'payment_status' => 'pending'
        ]);

        // Test email send गर्छौं
        \App\Jobs\SendBookingEmail::dispatch($booking, true);

        return response()->json([
            'success' => true,
            'message' => '✅ Test email sent successfully!',
            'booking_id' => $booking->id,
            'room_id' => $booking->room_id,
            'email_sent_to' => $booking->guest_email,
            'hostel_name' => $hostel->name,
            'queue_status' => 'Job dispatched to queue'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'message' => '❌ Email sending failed'
        ], 500);
    }
});

// ✅ NEW: Simple Email Test Route
Route::get('/test-email-simple', function () {
    try {
        \Mail::send('emails.booking-created', [
            'booking' => (object)[
                'id' => 999,
                'getCustomerName' => fn() => 'Ashish Regmi',
                'getCustomerEmail' => fn() => 'regmiashish629@gmail.com',
                'hostel' => (object)['name' => 'Test Hostel'],
                'check_in_date' => now()->addDays(7),
                'status' => 'pending',
                'amount' => 5000,
                'room' => null
            ],
            'isGuest' => true,
            'status' => null
        ], function ($message) {
            $message->to('regmiashish629@gmail.com')
                ->subject('🧪 Test Email - HostelHub');
        });

        return "✅ Simple email test completed! Check regmiashish629@gmail.com";
    } catch (\Exception $e) {
        return "❌ Simple email failed: " . $e->getMessage();
    }
});

// ✅ NEW: Queue Status Check Route
Route::get('/queue-status', function () {
    $failedJobs = \Illuminate\Support\Facades\DB::table('failed_jobs')->count();
    $pendingJobs = \Illuminate\Support\Facades\DB::table('jobs')->count();

    return response()->json([
        'failed_jobs' => $failedJobs,
        'pending_jobs' => $pendingJobs,
        'queue_worker' => 'Check if php artisan queue:work is running',
        'timestamp' => now()
    ]);
});

/*|--------------------------------------------------------------------------
| Emergency Routes - ADD THESE AT THE TOP (500 Error Fix)
|--------------------------------------------------------------------------*/
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showUserRegistrationForm'])->name('register');

// ✅ FIXED: Search route completely removed from here (moved to public.php)
// No duplicate routes - search route is properly handled in public.php

/*|--------------------------------------------------------------------------
| Load Modular Route Files - FIXED ORDER with Proper Middleware
|--------------------------------------------------------------------------
*/
require __DIR__ . '/public.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/shared.php';

// ✅ UPDATED: Welcome and Student Registration Routes - COMPREHENSIVE UPDATE
Route::middleware(['auth'])->group(function () {
    // Welcome page with booking summary
    Route::get('/welcome', [WelcomeController::class, 'showWelcome'])->name('user.welcome');

    // Convert guest booking to student booking
    Route::post('/convert-booking/{bookingId}', [WelcomeController::class, 'convertGuestBooking'])->name('user.convert-booking');

    // Complete student registration
    Route::post('/complete-student-registration', [WelcomeController::class, 'completeStudentRegistration'])->name('user.complete-registration');

    // Quick student registration
    Route::post('/student/quick-register', [WelcomeController::class, 'quickStudentRegistration'])->name('student.quick-register');

    // Check pending bookings (API)
    Route::get('/check-pending-bookings', [WelcomeController::class, 'checkPendingBookings'])->name('user.check-pending');

    // Booking statistics (API)
    Route::get('/booking/stats', [WelcomeController::class, 'getBookingStats'])->name('booking.stats');

    // ✅ REMOVED: Guest booking success page moved to public routes above
    // Route::get('/booking/guest-success/{id}', [BookingController::class, 'guestBookingSuccess'])->name('booking.guest.success');

    // ✅ NEW: Convert guest booking to student booking
    Route::post('/booking/convert/{id}', [BookingController::class, 'convertToStudentBooking'])->name('booking.convert.to-student');

    // ✅ NEW: User bookings management
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// ✅ FIXED: Admin routes with proper middleware order
Route::prefix('admin')
    ->middleware(['auth', 'hasOrganization', 'role:admin'])
    ->group(function () {
        require __DIR__ . '/admin.php';

        // ✅ NEW: Admin booking management routes
        Route::get('/bookings/pending', [BookingController::class, 'pendingApprovals'])->name('admin.bookings.pending');
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
        Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');
    });

// ✅ FIXED: Owner routes with proper middleware order
Route::prefix('owner')
    ->middleware(['auth', 'hasOrganization', 'role:owner,hostel_manager'])
    ->group(function () {
        require __DIR__ . '/owner.php';

        // ✅ ADDED: Form reset route for circular form
        Route::post('/clear-form-flag', function () {
            session()->forget(['clear_form', 'success']);
            return response()->json(['success' => true]);
        })->name('owner.clear.form.flag');

        // ✅ NEW: Owner booking management routes
        Route::get('/bookings/pending', [BookingController::class, 'pendingApprovals'])->name('owner.bookings.pending');
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('owner.bookings.approve');
        Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('owner.bookings.reject');
        Route::get('/hostel/{hostelId}/bookings', [BookingController::class, 'hostelBookings'])->name('owner.hostel.bookings');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('owner.bookings.create');
    });

// ✅ FIXED: Student routes with proper middleware order
Route::prefix('student')
    ->middleware(['auth', 'role:student'])
    ->group(function () {
        require __DIR__ . '/student.php';

        // ✅ NEW: Student specific booking routes
        Route::get('/bookings', [BookingController::class, 'index'])->name('student.bookings.index');
        Route::get('/bookings/my', [BookingController::class, 'myBookings'])->name('student.bookings.my');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('student.bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('student.bookings.store');
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('student.bookings.show');
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('student.bookings.cancel');
    });

/*|--------------------------------------------------------------------------
| Development Routes (Conditionally Loaded)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    require __DIR__ . '/dev.php';
}

/*|--------------------------------------------------------------------------
| Debug Routes (For Development Only - Remove in Production)
|--------------------------------------------------------------------------*/
Route::middleware(['auth'])->group(function () {
    // Debug route for document issues
    Route::get('/debug-owner-documents', function () {
        $user = Auth::user();

        echo "<h3>User Info:</h3>";
        echo "User ID: " . $user->id . "<br>";
        echo "Name: " . $user->name . "<br>";
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "<br>";

        echo "<h3>Organization Info:</h3>";
        $organization = $user->organizations()->wherePivot('role', 'owner')->first();
        if ($organization) {
            echo "Organization ID: " . $organization->id . "<br>";
            echo "Organization Name: " . $organization->name . "<br>";

            echo "<h3>Hostels in Organization:</h3>";
            $hostels = $organization->hostels;
            foreach ($hostels as $hostel) {
                echo "Hostel ID: " . $hostel->id . " - " . $hostel->name . "<br>";
            }

            $hostelIds = $hostels->pluck('id');
            echo "<h3>Documents in these hostels:</h3>";
            $documents = \App\Models\Document::whereIn('hostel_id', $hostelIds)->get();
            foreach ($documents as $doc) {
                echo "Document ID: " . $doc->id . " - Student: " . ($doc->student->user->name ?? 'N/A') . " - Hostel ID: " . $doc->hostel_id . "<br>";
            }

            echo "<h3>Total documents found: " . $documents->count() . "</h3>";
        } else {
            echo "No organization found!";
        }

        return '';
    });

    // Debug route for document controller logic
    Route::get('/debug-documents-controller', function () {
        $user = Auth::user();

        echo "<h3>Testing DocumentController Logic</h3>";
        echo "User: " . $user->name . " (ID: " . $user->id . ")<br>";
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "<br><br>";

        // DocumentController जस्तै logic
        $query = \App\Models\Document::with(['student.user', 'hostel', 'uploader']);

        if ($user->hasRole('admin')) {
            echo "Admin access<br>";
            $documents = $query->latest();
        } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            echo "Owner access - EMERGENCY MODE: Showing all documents<br>";
            $documents = $query->latest();
        } else {
            echo "Student access<br>";
            $student = $user->student;
            if ($student) {
                $documents = $query->where('student_id', $student->id)->latest();
            } else {
                $documents = $query->where('id', 0);
            }
        }

        $documents = $documents->get();

        echo "<h4>Documents Found:</h4>";
        foreach ($documents as $doc) {
            echo "ID: " . $doc->id . " | ";
            echo "Name: " . $doc->original_name . " | ";
            echo "Hostel: " . ($doc->hostel->name ?? 'N/A') . " | ";
            echo "Student: " . ($doc->student->user->name ?? 'N/A') . "<br>";
        }

        echo "<br><h4>Total: " . $documents->count() . " documents</h4>";

        return '';
    });

    // Debug route for document upload issues
    Route::get('/debug-document-upload', function () {
        $user = Auth::user();

        echo "<h3>Debug Document Upload</h3>";
        echo "User: " . $user->name . "<br>";
        echo "Organization: " . ($user->organizations()->first()->name ?? 'None') . "<br>";

        $students = \App\Models\Student::where('organization_id', $user->organizations()->first()->id ?? 0)->get();
        echo "Students in organization: " . $students->count() . "<br>";

        foreach ($students as $student) {
            echo "Student: " . ($student->user->name ?? 'N/A') . " (ID: " . $student->id . ")<br>";
        }

        return '';
    });

    // ✅ ADDED: Circular System Debug Routes
    Route::get('/debug-circular-system', function () {
        $user = Auth::user();

        echo "<h3>Circular System Debug</h3>";
        echo "User: " . $user->name . " (ID: " . $user->id . ")<br>";
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "<br><br>";

        // Test circular access
        $circulars = \App\Models\Circular::with(['organization', 'recipients'])->get();

        echo "<h4>All Circulars in System:</h4>";
        foreach ($circulars as $circular) {
            echo "Circular ID: " . $circular->id . " | ";
            echo "Title: " . $circular->title . " | ";
            echo "Organization: " . ($circular->organization->name ?? 'N/A') . " | ";
            echo "Recipients: " . $circular->recipients->count() . "<br>";
        }

        echo "<h4>User's Organization:</h4>";
        $organization = $user->organizations()->first();
        if ($organization) {
            echo "Organization ID: " . $organization->id . " - " . $organization->name . "<br>";

            echo "<h4>Circulars in User's Organization:</h4>";
            $orgCirculars = \App\Models\Circular::where('organization_id', $organization->id)->get();
            foreach ($orgCirculars as $circular) {
                echo "Circular ID: " . $circular->id . " - " . $circular->title . "<br>";
            }
        }

        return '';
    });

    // ✅ ADDED: Circular Permission Debug Route
    Route::get('/debug-circular-permissions', function () {
        $user = Auth::user();

        echo "<h3>Circular Permissions Debug</h3>";
        echo "User: " . $user->name . "<br>";
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "<br><br>";

        // Test Gates
        echo "<h4>Gate Permissions:</h4>";
        echo "access_circulars: " . (Gate::allows('access_circulars') ? 'YES' : 'NO') . "<br>";
        echo "create_circulars: " . (Gate::allows('create_circulars') ? 'YES' : 'NO') . "<br>";
        echo "edit_circulars: " . (Gate::allows('edit_circulars') ? 'YES' : 'NO') . "<br>";
        echo "delete_circulars: " . (Gate::allows('delete_circulars') ? 'YES' : 'NO') . "<br>";
        echo "publish_circulars: " . (Gate::allows('publish_circulars') ? 'YES' : 'NO') . "<br>";
        echo "view_circulars_analytics: " . (Gate::allows('view_circulars_analytics') ? 'YES' : 'NO') . "<br>";

        return '';
    });

    // ✅ NEW: Booking System Debug Route
    Route::get('/debug-booking-system', function () {
        $user = Auth::user();

        echo "<h3>Booking System Debug</h3>";
        echo "User: " . $user->name . " (ID: " . $user->id . ")<br>";
        echo "Email: " . $user->email . "<br>";
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "<br><br>";

        // Check guest bookings for this email
        $guestBookings = \App\Models\Booking::where('guest_email', $user->email)->get();
        echo "<h4>Guest Bookings for this email:</h4>";
        foreach ($guestBookings as $booking) {
            echo "Booking ID: " . $booking->id . " | ";
            echo "Hostel: " . ($booking->hostel->name ?? 'N/A') . " | ";
            echo "Status: " . $booking->status . " | ";
            echo "Guest: " . ($booking->is_guest_booking ? 'YES' : 'NO') . " | ";
            echo "User ID: " . ($booking->user_id ?? 'NULL') . "<br>";
        }

        echo "<h4>Student Profile:</h4>";
        $student = $user->student;
        if ($student) {
            echo "Student ID: " . $student->id . "<br>";
            echo "Hostel ID: " . ($student->hostel_id ?? 'NULL') . "<br>";
            echo "Room ID: " . ($student->room_id ?? 'NULL') . "<br>";
        } else {
            echo "No student profile<br>";
        }

        return '';
    });
});

// Temporary test route for document upload
Route::get('/test-document-fix', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head><title>Test Document Fix</title></head>
    <body style="padding:20px;">
        <h2>Test Document Upload - FILE REQUIRED</h2>
        <form action="' . route('owner.documents.store') . '" method="POST" enctype="multipart/form-data">
            ' . csrf_field() . '
            <div style="margin:10px 0;">
                <label>Student ID:</label>
                <input type="number" name="student_id" value="7" required>
            </div>
            <div style="margin:10px 0;">
                <label>Document Type:</label>
                <input type="text" name="document_type" value="admission_form" required>
            </div>
            <div style="margin:10px 0;">
                <label>Title:</label>
                <input type="text" name="title" value="Test Document" required>
            </div>
            <div style="margin:10px 0;">
                <label>File: *</label>
                <input type="file" name="file_path" required>
                <br><small>कृपया एउटा PDF, JPG, वा PNG file छनौट गर्नुहोस्</small>
            </div>
            <div style="margin:10px 0;">
                <label>Issue Date:</label>
                <input type="date" name="issue_date" value="' . date('Y-m-d') . '" required>
            </div>
            <div style="margin:10px 0;">
                <label>Status:</label>
                <select name="status">
                    <option value="active">Active</option>
                </select>
            </div>
            <button type="submit" style="padding:10px 20px; background:blue; color:white; border:none;">Submit Test</button>
        </form>
        
        <script>
            document.querySelector("form").addEventListener("submit", function(e) {
                const fileInput = document.querySelector("input[name=\'file_path\']");
                if (!fileInput.files.length) {
                    alert("कृपया FILE छनौट गर्नुहोस्!");
                    e.preventDefault();
                }
            });
        </script>
    </body>
    </html>
    ';
});

// ✅ ADDED: Circular Test Route
Route::get('/test-circular-fix', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head><title>Test Circular System</title></head>
    <body style="padding:20px;">
        <h2>Test Circular System Fix</h2>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Owner Circular Routes Test:</h3>
            <a href="' . route('owner.circulars.index') . '" style="display:inline-block; padding:10px; background:green; color:white; margin:5px;">Owner Circulars</a>
            <a href="' . route('owner.circulars.create') . '" style="display:inline-block; padding:10px; background:blue; color:white; margin:5px;">Create Circular</a>
        </div>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Student Circular Routes Test:</h3>
            <a href="' . route('student.circulars.index') . '" style="display:inline-block; padding:10px; background:green; color:white; margin:5px;">Student Circulars</a>
        </div>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Debug Routes:</h3>
            <a href="/debug-circular-system" style="display:inline-block; padding:10px; background:orange; color:white; margin:5px;">Circular System Debug</a>
            <a href="/debug-circular-permissions" style="display:inline-block; padding:10px; background:orange; color:white; margin:5px;">Circular Permissions Debug</a>
            <a href="/debug-booking-system" style="display:inline-block; padding:10px; background:orange; color:white; margin:5px;">Booking System Debug</a>
        </div>
    </body>
    </html>
    ';
});

// ✅ NEW: Booking System Test Route
Route::get('/test-booking-system', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head><title>Test Booking System</title></head>
    <body style="padding:20px;">
        <h2>Test New Booking System</h2>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Public Booking Routes:</h3>
            <a href="' . route('all.hostels') . '" style="display:inline-block; padding:10px; background:green; color:white; margin:5px;">View Hostels</a>
            <a href="' . route('hostel.book.from.gallery', ['slug' => 'test-hostel']) . '" style="display:inline-block; padding:10px; background:green; color:white; margin:5px;">Gallery Booking (Specific Room)</a>
            <a href="' . route('hostel.book.all.rooms', ['slug' => 'test-hostel']) . '" style="display:inline-block; padding:10px; background:green; color:white; margin:5px;">Gallery Booking (All Rooms)</a>
            <a href="' . route('booking.guest-success', ['id' => 1]) . '" style="display:inline-block; padding:10px; background:green; color:white; margin:5px;">Guest Success Page</a>
        </div>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Authenticated User Routes:</h3>
            <a href="' . route('user.welcome') . '" style="display:inline-block; padding:10px; background:blue; color:white; margin:5px;">Welcome Page</a>
            <a href="' . route('bookings.my') . '" style="display:inline-block; padding:10px; background:blue; color:white; margin:5px;">My Bookings</a>
        </div>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Owner Routes:</h3>
            <a href="' . route('owner.bookings.pending') . '" style="display:inline-block; padding:10px; background:purple; color:white; margin:5px;">Pending Bookings</a>
        </div>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Student Routes:</h3>
            <a href="' . route('student.bookings.my') . '" style="display:inline-block; padding:10px; background:teal; color:white; margin:5px;">Student Bookings</a>
        </div>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>Debug Routes:</h3>
            <a href="/debug-booking-system" style="display:inline-block; padding:10px; background:orange; color:white; margin:5px;">Booking System Debug</a>
        </div>
        
        <div style="margin:20px 0; padding:15px; border:1px solid #ccc;">
            <h3>New Booking Form Test:</h3>
            <form action="' . route('booking.store') . '" method="POST" style="border:1px solid #ddd; padding:15px;">
                ' . csrf_field() . '
                <h4>Test Booking Form (NEW SYSTEM)</h4>
                <div style="margin:10px 0;">
                    <label>Room ID:</label>
                    <input type="number" name="room_id" value="1" required>
                </div>
                <div style="margin:10px 0;">
                    <label>Check-in Date:</label>
                    <input type="date" name="check_in_date" value="' . date('Y-m-d') . '" required>
                </div>
                <div style="margin:10px 0;">
                    <label>Guest Name:</label>
                    <input type="text" name="guest_name" value="Test User" required>
                </div>
                <div style="margin:10px 0;">
                    <label>Guest Email:</label>
                    <input type="email" name="guest_email" value="test@example.com" required>
                </div>
                <div style="margin:10px 0;">
                    <label>Guest Phone:</label>
                    <input type="text" name="guest_phone" value="9800000000" required>
                </div>
                <button type="submit" style="padding:10px 20px; background:green; color:white; border:none;">Test Booking Store</button>
            </form>
        </div>
    </body>
    </html>
    ';
});
