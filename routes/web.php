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
    PaymentController
};
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
// Updated to accept optional plan parameter
Route::get('/register/organization/{plan?}', [RegistrationController::class, 'show'])->name('register.organization');
Route::post('/register/organization', [RegistrationController::class, 'store'])->name('register.organization.store');

/*|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // User Registration
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.submit');

    // Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.submit');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.reset.store');

    // Email Verification
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

    // FIX: Check if user has organization before redirecting
    if (!session('current_organization_id')) {
        $orgUser = DB::table('organization_user')->where('user_id', $user->id)->first();
        if ($orgUser) {
            session(['current_organization_id' => $orgUser->organization_id]);
        } else {
            return redirect()->route('register.organization');
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
    // Dashboard routes
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // Owner Dashboard Route (Moved inside hasOrganization middleware)
    Route::get('/owner/dashboard', [DashboardController::class, 'ownerDashboard'])
        ->middleware('role:hostel_manager')
        ->name('owner.dashboard');

    // Student dashboard using StudentController
    Route::get('/student/dashboard', [StudentController::class, 'studentDashboard'])
        ->middleware('role:student')
        ->name('student.dashboard');

    // Unified Contact Routes for both admin and owner
    Route::middleware('role:admin,hostel_manager')->group(function () {
        Route::resource('contacts', ContactController::class)->names('admin.contacts');
        Route::get('/contacts/search', [ContactController::class, 'search'])->name('admin.contacts.search');
        Route::post('/contacts/bulk-delete', [ContactController::class, 'bulkDestroy'])->name('admin.contacts.bulk-delete');
        Route::post('/contacts/{id}/update-status', [ContactController::class, 'updateStatus'])->name('admin.contacts.update-status');
        Route::get('/contacts/export/csv', [ContactController::class, 'exportCSV'])->name('admin.contacts.export-csv');
    });

    // Unified Gallery Routes for both admin and owner
    Route::middleware('role:admin,hostel_manager')->group(function () {
        Route::resource('galleries', GalleryController::class)->names('admin.galleries');
        Route::post('/galleries/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])
            ->name('admin.galleries.toggle-status');
        Route::post('/galleries/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
            ->name('admin.galleries.toggle-featured');
    });

    // Resource routes with role-based access control
    Route::middleware('role:admin,hostel_manager')->group(function () {
        // Meals - Accessible by both admin and owner
        Route::resource('meals', MealController::class);

        // ADMIN/OWNER ONLY: Full Reviews List
        Route::resource('reviews', AdminReviewController::class)->names([
            'index' => 'admin.reviews.index',
            'create' => 'admin.reviews.create',
            'store' => 'admin.reviews.store',
            'show' => 'admin.reviews.show',
            'edit' => 'admin.reviews.edit',
            'update' => 'admin.reviews.update',
            'destroy' => 'admin.reviews.destroy'
        ]);

        // Rooms - FIXED: Use separate routes for admin and owner to avoid conflicts
        Route::get('/rooms', [RoomController::class, 'index'])->name('admin.rooms.index');
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('admin.rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('admin.rooms.store');
        Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('admin.rooms.show');
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('admin.rooms.edit');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('admin.rooms.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('admin.rooms.destroy');

        // Room search
        Route::get('/rooms/search', [RoomController::class, 'search'])->name('admin.rooms.search');

        // Room status change
        Route::post('/rooms/{room}/change-status', [RoomController::class, 'changeStatus'])->name('admin.rooms.change-status');

        // Room export
        Route::get('/rooms/export/csv', [RoomController::class, 'exportCSV'])->name('admin.rooms.export-csv');
    });

    // Students routes - separated for clarity
    Route::middleware('role:admin,hostel_manager')->group(function () {
        Route::resource('students', StudentController::class)->names([
            'index' => 'admin.students.index',
            'create' => 'admin.students.create',
            'store' => 'admin.students.store',
            'show' => 'admin.students.show',
            'edit' => 'admin.students.edit',
            'update' => 'admin.students.update',
            'destroy' => 'admin.students.destroy'
        ]);

        // Student search
        Route::get('/students/search', [StudentController::class, 'search'])->name('admin.students.search');
    });

    // Hostels - Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('hostels', AdminHostelController::class)->names('admin.hostels');
        Route::get('hostels/{hostel}/availability', [AdminHostelController::class, 'showAvailability'])->name('admin.hostels.availability');
        Route::put('hostels/{hostel}/availability', [AdminHostelController::class, 'updateAvailability'])->name('admin.hostels.availability.update');

        // Hostel search
        Route::get('/hostels/search', [AdminHostelController::class, 'search'])->name('admin.hostels.search');
    });

    // Owner specific routes - FIXED: Add proper room routes for owner
    Route::middleware('role:hostel_manager')->prefix('owner')->name('owner.')->group(function () {
        // ✅ FIXED: Update owner hostel routes to include ALL methods
        Route::resource('hostels', OwnerHostelController::class);

        // Add gallery routes for owner
        Route::resource('galleries', GalleryController::class);
        Route::post('/galleries/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])
            ->name('galleries.toggle-status');
        Route::post('/galleries/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
            ->name('galleries.toggle-featured');

        // Add reviews routes for owner
        Route::resource('reviews', AdminReviewController::class);

        // FIXED: Add proper room routes for owner
        Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('rooms/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
        Route::get('rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

        // Add room search for owner
        Route::get('rooms/search', [RoomController::class, 'search'])->name('rooms.search');

        // Room status change for owner
        Route::post('rooms/{room}/change-status', [RoomController::class, 'changeStatus'])->name('rooms.change-status');

        // Room export for owner
        Route::get('rooms/export/csv', [RoomController::class, 'exportCSV'])->name('rooms.export-csv');

        // Add these routes for owner dashboard quick actions
        Route::get('meals', [MealController::class, 'index'])->name('meals.index');
        Route::get('meal-menus', [AdminMealMenuController::class, 'index'])->name('meal-menus.index');
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');

        // Owner student routes
        Route::resource('students', StudentController::class)->names([
            'index' => 'students.index',
            'create' => 'students.create',
            'store' => 'students.store',
            'show' => 'students.show',
            'edit' => 'students.edit',
            'update' => 'students.update',
            'destroy' => 'students.destroy'
        ]);

        // Owner student search
        Route::get('students/search', [StudentController::class, 'search'])->name('students.search');

        // Owner student export
        Route::get('students/export/csv', [StudentController::class, 'exportCSV'])->name('students.export-csv');

        // Owner contact routes
        Route::resource('contacts', ContactController::class)->names([
            'index' => 'contacts.index',
            'create' => 'contacts.create',
            'store' => 'contacts.store',
            'show' => 'contacts.show',
            'edit' => 'contacts.edit',
            'update' => 'contacts.update',
            'destroy' => 'contacts.destroy'
        ]);

        // Owner contact search
        Route::get('contacts/search', [ContactController::class, 'search'])->name('contacts.search');

        // Owner contact bulk delete
        Route::post('contacts/bulk-delete', [ContactController::class, 'bulkDestroy'])->name('contacts.bulk-delete');

        // Owner contact status update
        Route::post('contacts/{id}/update-status', [ContactController::class, 'updateStatus'])->name('contacts.update-status');

        // Owner contact export
        Route::get('contacts/export/csv', [ContactController::class, 'exportCSV'])->name('contacts.export-csv');

        // Owner payment routes
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

        // Owner payment search
        Route::get('payments/search', [PaymentController::class, 'search'])->name('payments.search');

        // Owner payment status update
        Route::post('payments/{payment}/update-status', [PaymentController::class, 'updateStatus'])->name('payments.update-status');
    });

    // Meal Menus - Admin can also view
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('meal-menus', AdminMealMenuController::class)->only(['index', 'show']);

        // Meal menu search
        Route::get('/meal-menus/search', [AdminMealMenuController::class, 'search'])->name('meal-menus.search');
    });

    // Settings - admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('settings', SettingsController::class)->names('admin.settings');
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
        Route::get('/payments/report', [ReportController::class, 'paymentReport'])->name('payments.report');
        Route::get('/payments/export', [ReportController::class, 'paymentExport'])->name('payments.export');
    });

    // Student routes
    Route::middleware('role:student')->group(function () {
        Route::get('/student/profile', [StudentController::class, 'profile'])->name('student.profile');
        Route::get('/student/payments', [StudentController::class, 'payments'])->name('student.payments');
        Route::get('/student/meal-menus', [StudentController::class, 'mealMenus'])->name('student.meal-menus.index');
        Route::get('/student/meal-menus/{mealMenu}', [StudentController::class, 'showMealMenu'])->name('student.meal-menus.show');

        // Room viewing for students (read-only) - Updated to use RoomController methods
        Route::get('/student/rooms', [RoomController::class, 'studentIndex'])->name('student.rooms.index');
        Route::get('/student/rooms/{room}', [RoomController::class, 'studentShow'])->name('student.rooms.show');

        // Student room search
        Route::get('/student/rooms/search', [RoomController::class, 'search'])->name('student.rooms.search');

        // Bookings
        Route::get('/my-bookings', [RoomController::class, 'myBookings'])->name('bookings.my');

        // Student profile update
        Route::patch('/student/profile/update', [StudentController::class, 'updateProfile'])->name('student.profile.update');
    });

    // Payments - accessible by admin and hostel_manager
    Route::middleware('role:admin,hostel_manager')->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('admin.payments.create');
        Route::post('/payments', [PaymentController::class, 'store'])->name('admin.payments.store');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('admin.payments.show');
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('admin.payments.edit');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('admin.payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('admin.payments.destroy');

        // Payment search
        Route::get('/payments/search', [PaymentController::class, 'search'])->name('admin.payments.search');

        // Payment status update
        Route::post('/payments/{payment}/update-status', [PaymentController::class, 'updateStatus'])->name('admin.payments.update-status');
    });

    // Student payment viewing (read-only)
    Route::middleware('role:student')->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('student.payments.index');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('student.payments.show');

        // Student payment search
        Route::get('/payments/search', [PaymentController::class, 'search'])->name('student.payments.search');
    });

    // Khalti callback route (publicly accessible)
    Route::post('payments/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');

    // Common authenticated routes
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Subscription Management - Moved outside the role-specific groups
    Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/start-trial', [SubscriptionController::class, 'startTrial'])->name('subscription.start-trial');

    // Subscription cancellation
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Onboarding
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding/step/{step}', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::post('/onboarding/skip/{step}', [OnboardingController::class, 'skip'])->name('onboarding.skip');

    // Onboarding completion
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

    // Test database connection
    Route::get('/test-db', function () {
        try {
            DB::connection()->getPdo();
            return 'Database connection is working.';
        } catch (\Exception $e) {
            return 'Database connection failed: ' . $e->getMessage();
        }
    });

    // Test roles and permissions
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
