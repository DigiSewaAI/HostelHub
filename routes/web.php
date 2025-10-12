<?php

use App\Http\Controllers\{
    Admin\ContactController,
    Admin\DashboardController,
    Admin\GalleryController,
    Admin\HostelController as AdminHostelController,
    Admin\MealController,
    Admin\MealMenuController as AdminMealMenuController,
    Admin\ReportController,
    Admin\ReviewController as AdminReviewController,
    Admin\RoomController,
    Admin\StudentController,
    Admin\SettingsController,
    Admin\PaymentController as AdminPaymentController,
    Owner\HostelController as OwnerHostelController,
    Frontend\GalleryController as FrontendGalleryController,
    Frontend\PublicContactController,
    Frontend\PublicController,
    Frontend\PricingController,
    Frontend\ReviewController as FrontendReviewController,
    Auth\AuthenticatedSessionController,
    Auth\ConfirmablePasswordController,
    Auth\EmailVerificationNotificationController,
    Auth\EmailVerificationPromptController,
    Auth\NewPasswordController,
    Auth\PasswordController,
    Auth\PasswordResetLinkController,
    Auth\RegisteredUserController,
    Auth\VerifyEmailController,
    RegistrationController,
    SubscriptionController,
    OnboardingController,
    ProfileController as PublicProfileController,
    BookingController,
    PaymentController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}

/*|--------------------------------------------------------------------------
| Public Routes (Marketing Site - Homepage, Features, Pricing, etc.)
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/about', [PublicController::class, 'about'])->name('about');
    Route::get('/features', [PublicController::class, 'features'])->name('features');
    Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');
    Route::get('/gallery', [FrontendGalleryController::class, 'index'])->name('gallery');
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

    // Gallery API Routes
    Route::get('/api/gallery/data', [FrontendGalleryController::class, 'getGalleryData']);
    Route::get('/api/gallery/categories', [FrontendGalleryController::class, 'getGalleryCategories']);
    Route::get('/api/gallery/stats', [FrontendGalleryController::class, 'getGalleryStats']);

    // Use Frontend ReviewController for public testimonials
    Route::get('/reviews', [FrontendReviewController::class, 'index'])->name('reviews');
    Route::get('/testimonials', [FrontendReviewController::class, 'index'])->name('testimonials');

    // Legal pages routes
    Route::get('/privacy-policy', [PublicController::class, 'privacy'])->name('privacy');
    Route::get('/terms-of-service', [PublicController::class, 'terms'])->name('terms');
    Route::get('/cookies', [PublicController::class, 'cookies'])->name('cookies');

    Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
    Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

    // Room search functionality
    Route::get('/rooms', [PublicController::class, 'roomSearch'])->name('rooms.search');
    Route::post('/rooms/search', [PublicController::class, 'searchRooms'])->name('rooms.search.post');

    // Demo route
    Route::get('/demo', function () {
        return view('frontend.pages.demo');
    })->name('demo');

    // Newsletter subscription route
    Route::post('/newsletter/subscribe', [PublicController::class, 'subscribeNewsletter'])
        ->name('newsletter.subscribe');
});

/*|--------------------------------------------------------------------------
| Organization Registration Routes (Accessible by all)
|--------------------------------------------------------------------------
*/
Route::get('/register/organization/{plan?}', [RegistrationController::class, 'show'])->name('register.organization');
Route::post('/register/organization', [RegistrationController::class, 'store'])->name('register.organization.store');

/*|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.submit');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.submit');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.reset.store');

    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

/*|--------------------------------------------------------------------------
| Global Dashboard Redirect (Role-based)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!session('current_organization_id')) {
        $orgUser = DB::table('organization_user')->where('user_id', $user->id)->first();
        if ($orgUser) {
            session(['current_organization_id' => $orgUser->organization_id]);
        } else {
            if (!$user->hasRole('admin')) {
                return redirect()->route('register.organization');
            }
        }
    }

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('hostel_manager')) {
        return redirect()->route('owner.dashboard');
    } elseif ($user->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }

    return redirect('/');
})->middleware(['auth'])->name('dashboard');

/*|--------------------------------------------------------------------------
| STEP 1: Debug Routes - Permission Check
|--------------------------------------------------------------------------
*/
Route::get('/debug-permissions', function () {
    $user = auth()->user();

    if (!$user) {
        return "No user logged in";
    }

    return [
        'user_id' => $user->id,
        'user_name' => $user->name,
        'email' => $user->email,
        'roles' => $user->getRoleNames(),
        'permissions' => $user->getAllPermissions()->pluck('name'),
        'can_payment_view' => $user->can('payment.view') ? 'YES' : 'NO',
        'is_admin' => $user->hasRole('admin') ? 'YES' : 'NO',
        'is_hostel_manager' => $user->hasRole('hostel_manager') ? 'YES' : 'NO'
    ];
})->middleware('auth');

Route::get('/assign-role/{role}', function ($role) {
    $user = auth()->user();

    if (!$user) {
        return "No user logged in";
    }

    // Valid roles check
    $validRoles = ['admin', 'hostel_manager', 'student'];
    if (!in_array($role, $validRoles)) {
        return "Invalid role. Use: " . implode(', ', $validRoles);
    }

    $user->syncRoles([$role]);

    return [
        'message' => "Role {$role} assigned to user {$user->name}",
        'current_roles' => $user->getRoleNames(),
        'permissions' => $user->getAllPermissions()->pluck('name')
    ];
})->middleware('auth');

/*|--------------------------------------------------------------------------
| Admin Routes Group (Consistent Prefix and Names)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'hasOrganization', 'role:admin', 'can:view-admin-dashboard'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard with cache management
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::post('/dashboard/clear-cache', [DashboardController::class, 'clearCache'])->name('dashboard.clear-cache');

        // Admin Resources
        Route::resource('contacts', ContactController::class);
        Route::get('/contacts/search', [ContactController::class, 'search'])->name('contacts.search');
        Route::post('/contacts/bulk-delete', [ContactController::class, 'bulkDestroy'])->name('contacts.bulk-delete');
        Route::post('/contacts/{id}/update-status', [ContactController::class, 'updateStatus'])->name('contacts.update-status');
        Route::get('/contacts/export/csv', [ContactController::class, 'exportCSV'])->name('contacts.export-csv');

        Route::resource('galleries', GalleryController::class);
        Route::post('/galleries/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])
            ->name('galleries.toggle-status');
        Route::post('/galleries/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
            ->name('galleries.toggle-featured');

        // Meal Routes
        Route::resource('meals', MealController::class);
        Route::get('/meals/search', [MealController::class, 'search'])->name('meals.search');

        Route::resource('reviews', AdminReviewController::class);

        Route::resource('rooms', RoomController::class)->except(['create', 'store']);
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');
        Route::post('/rooms/{room}/change-status', [RoomController::class, 'changeStatus'])->name('rooms.change-status');
        Route::get('/rooms/export/csv', [RoomController::class, 'exportCSV'])->name('rooms.export-csv');

        Route::resource('students', StudentController::class);
        Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
        Route::get('/students/export/csv', [StudentController::class, 'exportCSV'])->name('students.export');

        Route::resource('hostels', AdminHostelController::class);
        Route::get('hostels/{hostel}/availability', [AdminHostelController::class, 'showAvailability'])->name('hostels.availability');
        Route::put('hostels/{hostel}/availability', [AdminHostelController::class, 'updateAvailability'])->name('hostels.availability.update');
        Route::get('/hostels/search', [AdminHostelController::class, 'search'])->name('hostels.search');

        // Routes for fixing hostel room counts
        Route::get('/hostels/fix-room-counts', [AdminHostelController::class, 'fixRoomCounts'])->name('hostels.fix-room-counts');
        Route::post('/hostels/update-all-counts', [AdminHostelController::class, 'updateAllRoomCounts'])->name('hostels.update-all-counts');

        // Payment Routes Structure
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
            Route::get('/create', [AdminPaymentController::class, 'create'])->name('create');
            Route::post('/', [AdminPaymentController::class, 'store'])->name('store');
            Route::get('/{payment}', [AdminPaymentController::class, 'show'])->name('show');
            Route::get('/{payment}/edit', [AdminPaymentController::class, 'edit'])->name('edit');
            Route::put('/{payment}', [AdminPaymentController::class, 'update'])->name('update');
            Route::delete('/{payment}', [AdminPaymentController::class, 'destroy'])->name('destroy');
            Route::get('/search', [AdminPaymentController::class, 'search'])->name('search');
            Route::post('/{payment}/update-status', [AdminPaymentController::class, 'updateStatus'])->name('update-status');
            Route::get('/export', [AdminPaymentController::class, 'export'])->name('export');
            Route::get('/report', [AdminPaymentController::class, 'report'])->name('report');
            Route::post('/{payment}/approve-bank', [AdminPaymentController::class, 'approveBankTransfer'])->name('approve-bank');
            Route::post('/{payment}/reject-bank', [AdminPaymentController::class, 'rejectBankTransfer'])->name('reject-bank');
            Route::get('/{payment}/proof', [AdminPaymentController::class, 'viewProof'])->name('proof');

            // Payment verification - ✅ FIXED: Using payments_edit instead of payment.verify
            Route::middleware([\App\Http\Middleware\CheckPermission::class . ':payments_edit'])->group(function () {
                Route::get('/verification', [AdminPaymentController::class, 'verification'])->name('verification');
                Route::put('/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('verify');
            });
        });

        // Reports routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::post('/monthly', [ReportController::class, 'monthlyReport'])->name('monthly');
            Route::post('/yearly', [ReportController::class, 'yearlyReport'])->name('yearly');
            Route::post('/custom', [ReportController::class, 'customDateReport'])->name('custom');
            Route::post('/filter', [ReportController::class, 'filterReport'])->name('filter');
            Route::post('/download-pdf', [ReportController::class, 'downloadPdf'])->name('download.pdf');
            Route::post('/download-excel', [ReportController::class, 'downloadExcel'])->name('download.excel');
        });

        // Statistics API route
        Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');

        // Meal Menus
        Route::resource('meal-menus', AdminMealMenuController::class)->only(['index', 'show']);
        Route::get('/meal-menus/search', [AdminMealMenuController::class, 'search'])->name('meal-menus.search');

        // Settings routes
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::resource('settings', SettingsController::class)->except(['index']);
        Route::post('settings/bulk-update', [SettingsController::class, 'bulkUpdate'])->name('settings.bulk-update');
    });

/*|--------------------------------------------------------------------------
| Unified Role-Based Routes (Protected Routes for Owner and Student)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'hasOrganization'])->group(function () {

    Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])
        ->middleware(['role:student', 'can:view-student-dashboard'])
        ->name('student.dashboard');

    // Common routes for all authenticated users
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Booking routes
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/bookings/pending', [BookingController::class, 'pendingApprovals'])->name('bookings.pending');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Subscription routes
    Route::get('/subscription/limits', [SubscriptionController::class, 'showLimits'])->name('subscription.limits');
    Route::post('/subscription/purchase-extra-hostel', [SubscriptionController::class, 'purchaseExtraHostel'])->name('subscription.purchase-extra-hostel');

    // Payment Routes (Using Frontend PaymentController)
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');

    // eSewa Payment Routes
    Route::post('/payment/esewa/pay', [PaymentController::class, 'payWithEsewa'])->name('payment.esewa.pay');
    Route::post('/payment/esewa/callback', [PaymentController::class, 'verifyEsewaPayment'])->name('payment.esewa.callback');

    // Khalti Payment Routes
    Route::post('/payment/khalti/pay', [PaymentController::class, 'payWithKhalti'])->name('payment.khalti.pay');
    Route::post('/payment/khalti/callback', [PaymentController::class, 'verifyKhaltiPayment'])->name('payment.khalti.callback');

    // Bank Transfer Routes
    Route::get('/payment/bank/request', [PaymentController::class, 'bankTransferRequest'])->name('payment.bank.form');
    Route::post('/payment/bank/request', [PaymentController::class, 'storeBankTransfer'])->name('payment.bank.request');

    // Payment Success/Failure
    Route::get('/payment/success/{payment}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failure/{payment?}', [PaymentController::class, 'paymentFailure'])->name('payment.failure');

    // Payment Verification
    Route::get('/payment/verify/{paymentId}', [PaymentController::class, 'verifyPayment'])->name('payment.verify');

    // Student Payment History and Receipts
    Route::middleware('role:student')->group(function () {
        Route::get('/student/payments', [PaymentController::class, 'studentPayments'])->name('student.payments.index');
        Route::get('/student/payments/{paymentId}/receipt', [PaymentController::class, 'showReceipt'])->name('student.payments.receipt');
        Route::get('/student/payments/{paymentId}/receipt/download', [PaymentController::class, 'downloadReceipt'])->name('student.payments.receipt.download');
    });

    // 🔥 CRITICAL FIX: Owner specific routes with SIMPLIFIED middleware
    Route::middleware(['role:hostel_manager'])->prefix('owner')->name('owner.')->group(function () {
        // Owner dashboard - NOW WITH PROPER ACCESS
        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])->name('dashboard');

        // Owner Payment Management Routes (Using Frontend PaymentController)
        Route::get('/payments/report', [PaymentController::class, 'ownerReport'])->name('payments.report');
        Route::post('/payments/manual', [PaymentController::class, 'createManualPayment'])->name('payments.manual');
        Route::post('/payments/{payment}/approve', [PaymentController::class, 'approveBankTransfer'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [PaymentController::class, 'rejectBankTransfer'])->name('payments.reject');
        Route::get('/payments/{payment}/proof', [PaymentController::class, 'viewProof'])->name('payments.proof');

        // Hostel-specific bookings
        Route::get('/hostels/{hostelId}/bookings', [BookingController::class, 'hostelBookings'])->name('hostels.bookings');

        // Hostel routes with subscription limit check
        Route::get('/hostels', [OwnerHostelController::class, 'index'])->name('hostels.index');
        Route::middleware(['subscription.limit:hostel'])->group(function () {
            Route::get('/hostels/create', [OwnerHostelController::class, 'create'])->name('hostels.create');
            Route::post('/hostels', [OwnerHostelController::class, 'store'])->name('hostels.store');
        });
        Route::get('/hostels/{hostel}', [OwnerHostelController::class, 'show'])->name('hostels.show');
        Route::get('/hostels/{hostel}/edit', [OwnerHostelController::class, 'edit'])->name('hostels.edit');
        Route::put('/hostels/{hostel}', [OwnerHostelController::class, 'update'])->name('hostels.update');
        Route::delete('/hostels/{hostel}', [OwnerHostelController::class, 'destroy'])->name('hostels.destroy');

        // ✅ CRITICAL FIX: Add the missing toggle-status route
        Route::patch('/hostels/{hostel}/toggle-status', [OwnerHostelController::class, 'toggleStatus'])
            ->name('hostels.toggle-status');

        // Owner resource routes (similar to admin but for owner context)
        Route::resource('galleries', GalleryController::class);
        Route::post('/galleries/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])
            ->name('galleries.toggle-status');
        Route::post('/galleries/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
            ->name('galleries.toggle-featured');

        Route::resource('reviews', AdminReviewController::class);

        // Owner Meal Routes
        Route::resource('meals', MealController::class);
        Route::get('/meals/search', [MealController::class, 'search'])->name('meals.search');

        // Room routes with plan limits
        Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('rooms/create', [RoomController::class, 'create'])->name('rooms.create')->middleware('enforce.plan.limits');
        Route::post('rooms', [RoomController::class, 'store'])->name('rooms.store')->middleware('enforce.plan.limits');
        Route::get('rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
        Route::get('rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
        Route::get('rooms/search', [RoomController::class, 'search'])->name('rooms.search');
        Route::post('rooms/{room}/change-status', [RoomController::class, 'changeStatus'])->name('rooms.change-status');
        Route::get('rooms/export/csv', [RoomController::class, 'exportCSV'])->name('rooms.export-csv');

        // Meal menu routes
        Route::resource('meal-menus', AdminMealMenuController::class);

        // ✅ FIXED: Owner student routes with correct naming (owner.students.*)
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
        Route::get('students/create', [StudentController::class, 'create'])->name('students.create')->middleware('enforce.plan.limits');
        Route::post('students', [StudentController::class, 'store'])->name('students.store')->middleware('enforce.plan.limits');
        Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::get('students/search', [StudentController::class, 'search'])->name('students.search');
        // ✅ ADDED: Missing export route for owner students
        Route::get('students/export/csv', [StudentController::class, 'exportCSV'])->name('students.export-csv');

        // ✅ FIXED: Owner payment routes with correct permissions
        Route::middleware([\App\Http\Middleware\CheckPermission::class . ':payments_access'])->group(function () {
            Route::prefix('payments')->name('payments.')->group(function () {
                Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
                Route::get('/create', [AdminPaymentController::class, 'create'])->name('create');
                Route::post('/', [AdminPaymentController::class, 'store'])->name('store');
                Route::get('/{payment}', [AdminPaymentController::class, 'show'])->name('show');
                Route::get('/{payment}/edit', [AdminPaymentController::class, 'edit'])->name('edit');
                Route::put('/{payment}', [AdminPaymentController::class, 'update'])->name('update');
                Route::delete('/{payment}', [AdminPaymentController::class, 'destroy'])->name('destroy');
                Route::get('/search', [AdminPaymentController::class, 'search'])->name('search');
                Route::post('/{payment}/update-status', [AdminPaymentController::class, 'updateStatus'])->name('update-status');
                Route::get('/export', [AdminPaymentController::class, 'export'])->name('export');
            });
        });

        // Contact routes
        Route::resource('contacts', ContactController::class);
        Route::get('contacts/search', [ContactController::class, 'search'])->name('contacts.search');
        Route::post('contacts/bulk-delete', [ContactController::class, 'bulkDestroy'])->name('contacts.bulk-delete');
        Route::post('contacts/{id}/update-status', [ContactController::class, 'updateStatus'])->name('contacts.update-status');
        Route::get('contacts/export/csv', [ContactController::class, 'exportCSV'])->name('contacts.export-csv');
    });

    // Student routes
    Route::middleware(['role:student', 'can:view-student-dashboard'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'studentDashboard'])->name('dashboard');
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
        Route::get('/payments', [StudentController::class, 'payments'])->name('payments');
        Route::get('/meal-menus', [StudentController::class, 'mealMenus'])->name('meal-menus.index');
        Route::get('/meal-menus/{mealMenu}', [StudentController::class, 'showMealMenu'])->name('meal-menus.show');

        // Room viewing
        Route::get('/rooms', [RoomController::class, 'studentIndex'])->name('rooms.index');
        Route::get('/rooms/{room}', [RoomController::class, 'studentShow'])->name('rooms.show');
        Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');

        // Bookings with permission check
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        // ✅ FIXED: Using bookings_create instead of booking.create
        Route::middleware([\App\Http\Middleware\CheckPermission::class . ':bookings_create'])->group(function () {
            Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
            Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        });

        // Student profile update
        Route::patch('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');
    });

    // Subscription Management
    Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/start-trial', [SubscriptionController::class, 'startTrial'])->name('subscription.start-trial');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Onboarding
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding/step/{step}', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::post('/onboarding/skip/{step}', [OnboardingController::class, 'skip'])->name('onboarding.skip');
    Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');

    // Profile Management
    Route::get('/profile', [PublicProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [PublicProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [PublicProfileController::class, 'destroy'])->name('profile.destroy');

    // Password Management
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])->name('password.confirm.store');
});

/*|--------------------------------------------------------------------------
| Search route
|--------------------------------------------------------------------------
*/
Route::post('/search', [PublicController::class, 'search'])->name('search');

/*|--------------------------------------------------------------------------
| Development Routes - Room Counts Fix
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    // Temporary routes for fixing room counts
    Route::get('/fix-hostel-counts', function () {
        $hostels = \App\Models\Hostel::all();

        foreach ($hostels as $hostel) {
            $totalRooms = $hostel->rooms()->count();
            $availableRooms = $hostel->rooms()->where('status', 'available')->count();

            echo "Hostel: {$hostel->name} <br>";
            echo "Database - Total: {$hostel->total_rooms}, Available: {$hostel->available_rooms} <br>";
            echo "Actual - Total: {$totalRooms}, Available: {$availableRooms} <br>";

            // Auto-update
            $hostel->update([
                'total_rooms' => $totalRooms,
                'available_rooms' => $availableRooms
            ]);

            echo "<strong>UPDATED - Total: {$totalRooms}, Available: {$availableRooms}</strong> <br>";
            echo "---<br>";
        }

        return "<h3>All hostel room counts updated successfully!</h3>";
    });

    // Check duplicate hostels
    Route::get('/check-duplicates', function () {
        $duplicates = \App\Models\Hostel::select('name', DB::raw('COUNT(*) as count'))
            ->groupBy('name')
            ->having('count', '>', 1)
            ->get();

        echo "<h3>Duplicate Hostels:</h3>";
        foreach ($duplicates as $dup) {
            echo "Name: {$dup->name} - Count: {$dup->count} <br>";

            $hostels = \App\Models\Hostel::where('name', $dup->name)->get();
            foreach ($hostels as $hostel) {
                $roomCount = $hostel->rooms()->count();
                $studentCount = $hostel->students()->count();
                echo "&nbsp;&nbsp;- ID: {$hostel->id}, Rooms: {$roomCount}, Students: {$studentCount} <br>";
            }
            echo "---<br>";
        }

        return "<h3>Duplicate check completed!</h3>";
    });
}

/*|--------------------------------------------------------------------------
| Development Routes
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/test-route', function () {
        return 'Test route';
    })->name('test.route');

    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->middleware('auth');

    Route::get('/test-pdf', function () {
        $pdf = Pdf::loadHTML('<h1>Test PDF</h1><p>This is working!</p>');
        return $pdf->download('test.pdf');
    });

    Route::get('/test-db', function () {
        try {
            DB::connection()->getPdo();
            return 'Database connection is working.';
        } catch (\Exception $e) {
            return 'Database connection failed: ' . $e->getMessage();
        }
    });

    Route::get('/test-roles', function () {
        $user = auth()->user();
        if ($user) {
            return [
                'user_id' => $user->id,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name')
            ];
        }
        return 'No user logged in';
    })->middleware('auth');

    // Dashboard cache testing route
    Route::get('/test-dashboard-cache', function () {
        $userId = auth()->id();
        $cached = Cache::get('admin_dashboard_metrics_' . $userId);
        return [
            'cached' => $cached ? true : false,
            'user_id' => $userId,
            'cache_key' => 'admin_dashboard_metrics_' . $userId
        ];
    })->middleware('auth');

    // ✅ TEMPORARY TEST ROUTE - COMPLETELY OPEN
    Route::post('/test-hostel-update/{id}', function (Request $request, $id) {
        \Log::info("=== TEST UPDATE ROUTE HIT ===", [
            'hostel_id' => $id,
            'user_id' => auth()->id(),
            'all_data' => $request->all()
        ]);

        $hostel = \App\Models\Hostel::find($id);
        if ($hostel) {
            $hostel->update($request->all());
            return response()->json(['success' => true, 'message' => 'Test update successful']);
        }

        return response()->json(['success' => false, 'message' => 'Hostel not found']);
    });
}
