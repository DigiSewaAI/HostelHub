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
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
| Unified Role-Based Routes (Protected Routes)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'hasOrganization'])->group(function () {
    // Dashboard routes with unique names
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/owner/dashboard', [DashboardController::class, 'ownerDashboard'])
        ->middleware('role:hostel_manager')
        ->name('owner.dashboard');

    Route::get('/student/dashboard', [StudentController::class, 'studentDashboard'])
        ->middleware('role:student')
        ->name('student.dashboard');

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

    // Payment Routes
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

    // 🔄 NEW PAYMENT ROUTES ADDED HERE
    // Student Payment History and Receipts
    Route::middleware('role:student')->group(function () {
        Route::get('/student/payments', [PaymentController::class, 'studentPayments'])->name('student.payments.index');
        Route::get('/student/payments/{paymentId}/receipt', [PaymentController::class, 'showReceipt'])->name('student.payments.receipt');
        Route::get('/student/payments/{paymentId}/receipt/download', [PaymentController::class, 'downloadReceipt'])->name('student.payments.receipt.download');
    });

    // Owner Payment Management Routes
    Route::middleware('role:hostel_manager')->prefix('owner')->name('owner.')->group(function () {
        // Owner Payment Report
        Route::get('/payments/report', [PaymentController::class, 'ownerReport'])->name('payments.report');

        // Manual Payment Creation
        Route::post('/payments/manual', [PaymentController::class, 'createManualPayment'])->name('payments.manual');

        // Bank Transfer Approval
        Route::post('/payments/{payment}/approve', [PaymentController::class, 'approveBankTransfer'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [PaymentController::class, 'rejectBankTransfer'])->name('payments.reject');
        Route::get('/payments/{payment}/proof', [PaymentController::class, 'viewProof'])->name('payments.proof');

        // Hostel-specific bookings
        Route::get('/hostels/{hostelId}/bookings', [BookingController::class, 'hostelBookings'])->name('hostels.bookings');
    });

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('contacts', ContactController::class)->names('admin.contacts');
        Route::get('/contacts/search', [ContactController::class, 'search'])->name('admin.contacts.search');
        Route::post('/contacts/bulk-delete', [ContactController::class, 'bulkDestroy'])->name('admin.contacts.bulk-delete');
        Route::post('/contacts/{id}/update-status', [ContactController::class, 'updateStatus'])->name('admin.contacts.update-status');
        Route::get('/contacts/export/csv', [ContactController::class, 'exportCSV'])->name('admin.contacts.export-csv');

        Route::resource('galleries', GalleryController::class)->names('admin.galleries');
        Route::post('/galleries/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])
            ->name('admin.galleries.toggle-status');
        Route::post('/galleries/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
            ->name('admin.galleries.toggle-featured');

        Route::resource('meals', MealController::class)->names('admin.meals');
        Route::resource('reviews', AdminReviewController::class)->names('admin.reviews');

        Route::resource('rooms', RoomController::class)->names('admin.rooms')->except(['create', 'store']);
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('admin.rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('admin.rooms.store');
        Route::get('/rooms/search', [RoomController::class, 'search'])->name('admin.rooms.search');
        Route::post('/rooms/{room}/change-status', [RoomController::class, 'changeStatus'])->name('admin.rooms.change-status');
        Route::get('/rooms/export/csv', [RoomController::class, 'exportCSV'])->name('admin.rooms.export-csv');

        Route::resource('students', StudentController::class)->names('admin.students');
        Route::get('/students/search', [StudentController::class, 'search'])->name('admin.students.search');
        Route::get('/students/export/csv', [StudentController::class, 'exportCSV'])->name('admin.students.export');

        Route::resource('hostels', AdminHostelController::class)->names('admin.hostels');
        Route::get('hostels/{hostel}/availability', [AdminHostelController::class, 'showAvailability'])->name('admin.hostels.availability');
        Route::put('hostels/{hostel}/availability', [AdminHostelController::class, 'updateAvailability'])->name('admin.hostels.availability.update');
        Route::get('/hostels/search', [AdminHostelController::class, 'search'])->name('admin.hostels.search');

        // Admin Bank Transfer Approval Routes
        Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approveBankTransfer'])->name('admin.payments.approve');
        Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'rejectBankTransfer'])->name('admin.payments.reject');
        Route::get('/payments/{payment}/proof', [AdminPaymentController::class, 'viewProof'])->name('admin.payments.proof');
    });

    // Owner specific routes
    Route::middleware('role:hostel_manager')->prefix('owner')->name('owner.')->group(function () {
        // Owner dashboard
        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])->name('dashboard');

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

        // Gallery routes
        Route::resource('galleries', GalleryController::class);
        Route::post('/galleries/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])
            ->name('galleries.toggle-status');
        Route::post('/galleries/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
            ->name('galleries.toggle-featured');

        // Review routes
        Route::resource('reviews', AdminReviewController::class);

        // Meal routes
        Route::resource('meals', MealController::class);

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

        // Payment routes with permission check
        Route::middleware(['check.permission:payment.view'])->group(function () {
            Route::resource('payments', AdminPaymentController::class);
            Route::get('payments/search', [AdminPaymentController::class, 'search'])->name('payments.search');
            Route::post('payments/{payment}/update-status', [AdminPaymentController::class, 'updateStatus'])->name('payments.update-status');
        });

        // Student routes with plan limits
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
        Route::get('students/create', [StudentController::class, 'create'])->name('students.create')->middleware('enforce.plan.limits');
        Route::post('students', [StudentController::class, 'store'])->name('students.store')->middleware('enforce.plan.limits');
        Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::get('students/search', [StudentController::class, 'search'])->name('students.search');
        Route::get('students/export/csv', [StudentController::class, 'exportCSV'])->name('students.export-csv');

        // Contact routes
        Route::resource('contacts', ContactController::class);
        Route::get('contacts/search', [ContactController::class, 'search'])->name('contacts.search');
        Route::post('contacts/bulk-delete', [ContactController::class, 'bulkDestroy'])->name('contacts.bulk-delete');
        Route::post('contacts/{id}/update-status', [ContactController::class, 'updateStatus'])->name('contacts.update-status');
        Route::get('contacts/export/csv', [ContactController::class, 'exportCSV'])->name('contacts.export-csv');
    });

    // Meal Menus - Admin view only
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('meal-menus', AdminMealMenuController::class)->only(['index', 'show']);
        Route::get('/meal-menus/search', [AdminMealMenuController::class, 'search'])->name('meal-menus.search');
    });

    // Settings routes - Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
        Route::resource('settings', SettingsController::class)->names('admin.settings')->except(['index']);
        Route::post('settings/bulk-update', [SettingsController::class, 'bulkUpdate'])->name('admin.settings.bulk-update');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::post('/reports/monthly', [ReportController::class, 'monthlyReport'])->name('admin.reports.monthly');
        Route::post('/reports/yearly', [ReportController::class, 'yearlyReport'])->name('admin.reports.yearly');
        Route::post('/reports/custom', [ReportController::class, 'customDateReport'])->name('admin.reports.custom');
        Route::post('/reports/filter', [ReportController::class, 'filterReport'])->name('admin.reports.filter');
        Route::post('/reports/download-pdf', [ReportController::class, 'downloadPdf'])->name('admin.reports.download.pdf');
        Route::post('/reports/download-excel', [ReportController::class, 'downloadExcel'])->name('admin.reports.download.excel');

        // Payment reports
        Route::get('/payments/report', [AdminPaymentController::class, 'report'])->name('payments.report');
        Route::get('/payments/export', [AdminPaymentController::class, 'export'])->name('payments.export');

        // Payments verification
        Route::middleware(['check.permission:payment.verify'])->group(function () {
            Route::get('/payments/verification', [AdminPaymentController::class, 'verification'])->name('admin.payments.verification');
            Route::put('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('admin.payments.verify');
        });
    });

    // Student routes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
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
        Route::middleware(['check.permission:booking.create'])->group(function () {
            Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
            Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        });

        // Student profile update
        Route::patch('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');
    });

    // Payment routes for multiple roles
    Route::middleware(['check.role.or.permission:admin,owner,payment.view'])->group(function () {
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    });

    Route::middleware('role:admin,hostel_manager')->group(function () {
        Route::get('/payments/create', [AdminPaymentController::class, 'create'])->name('admin.payments.create');
        Route::post('/payments', [AdminPaymentController::class, 'store'])->name('admin.payments.store');
        Route::get('/payments/{payment}/edit', [AdminPaymentController::class, 'edit'])->name('admin.payments.edit');
        Route::put('/payments/{payment}', [AdminPaymentController::class, 'update'])->name('admin.payments.update');
        Route::delete('/payments/{payment}', [AdminPaymentController::class, 'destroy'])->name('admin.payments.destroy');
        Route::get('/payments/search', [AdminPaymentController::class, 'search'])->name('admin.payments.search');
        Route::post('/payments/{payment}/update-status', [AdminPaymentController::class, 'updateStatus'])->name('admin.payments.update-status');
    });

    // Student payment viewing
    Route::middleware('role:student')->group(function () {
        Route::get('/student-payments', [AdminPaymentController::class, 'studentIndex'])->name('student.payments.index');
        Route::get('/student-payments/{payment}', [AdminPaymentController::class, 'studentShow'])->name('student.payments.show');
        Route::get('/student-payments/search', [AdminPaymentController::class, 'studentSearch'])->name('student.payments.search');
    });

    // Khalti callback route
    Route::post('payments/khalti-callback', [AdminPaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');

    // Common authenticated routes
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

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
}
