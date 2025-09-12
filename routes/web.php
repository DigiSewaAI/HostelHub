<?php

use App\Http\Controllers\{
    Admin\ContactController,
    Admin\DashboardController,
    Admin\GalleryController,
    Admin\HostelController as AdminHostelController,
    Admin\MealController,
    Admin\MealMenuController as AdminMealMenuController,
    Admin\ReportController,
    Admin\ReviewController,
    Admin\RoomController,
    Admin\StudentController,
    Admin\SettingsController,
    Owner\HostelController as OwnerHostelController,
    Owner\MealMenuController as OwnerMealMenuController,
    Frontend\GalleryController as FrontendGalleryController,
    Frontend\PublicContactController,
    Frontend\PublicController,
    Frontend\PricingController,
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

    Route::get('/testimonials', [PublicController::class, 'testimonials'])->name('testimonials');
    Route::get('/reviews', [PublicController::class, 'reviews'])->name('reviews');

    // Legal pages routes
    Route::get('/privacy-policy', [PublicController::class, 'privacy'])->name('privacy');
    Route::get('/terms-of-service', [PublicController::class, 'terms'])->name('terms');
    Route::get('/cookies', [PublicController::class, 'cookies'])->name('cookies');

    Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
    Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

    // Room search functionality (NOT room management)
    Route::get('/rooms', [PublicController::class, 'roomSearch'])->name('rooms.search');
    Route::post('/rooms/search', [PublicController::class, 'searchRooms'])->name('rooms.search.post');

    // Demo route
    Route::get('/demo', function () {
        return view('pages.demo');
    })->name('demo');

    // Newsletter subscription route
    Route::post('/newsletter/subscribe', [PublicController::class, 'subscribeNewsletter'])
        ->name('newsletter.subscribe');
});

/*|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Organization Registration
    Route::get('/register/organization', [RegistrationController::class, 'show'])->name('register.organization');
    Route::post('/register/organization', [RegistrationController::class, 'store'])->name('register.organization.store');

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

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('hostel_manager')) {
        return redirect()->route('owner.dashboard');
    } elseif ($user->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }

    return redirect('/');
})->middleware('auth')->name('dashboard');

/*|--------------------------------------------------------------------------
| Unified Role-Based Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

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

        // Reviews
        Route::resource('reviews', ReviewController::class);

        // Rooms
        Route::resource('rooms', RoomController::class);
        Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');
    });

    // Students routes - separated for clarity
    Route::middleware('role:admin')->group(function () {
        Route::resource('students', StudentController::class)->names('admin.students');
    });

    Route::middleware('role:hostel_manager')->group(function () {
        Route::resource('students', StudentController::class)->names('owner.students');
    });

    // Hostels - Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('hostels', AdminHostelController::class);
        Route::get('hostels/{hostel}/availability', [AdminHostelController::class, 'showAvailability'])->name('admin.hostels.availability');
        Route::put('hostels/{hostel}/availability', [AdminHostelController::class, 'updateAvailability'])->name('admin.hostels.availability.update');
    });

    // Owner specific routes
    Route::middleware('role:hostel_manager')->prefix('owner')->name('owner.')->group(function () {
        Route::resource('meal-menus', OwnerMealMenuController::class);
        Route::resource('hostels', OwnerHostelController::class)->only(['index', 'edit', 'update']);

        // Add owner student routes with proper naming
        Route::resource('students', StudentController::class)->names([
            'index' => 'students.index',
            'create' => 'students.create',
            'store' => 'students.store',
            'show' => 'students.show',
            'edit' => 'students.edit',
            'update' => 'students.update',
            'destroy' => 'students.destroy'
        ]);
    });

    // Meal Menus - Admin can also view (optional)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('meal-menus', AdminMealMenuController::class)->only(['index', 'show']);
    });

    // Settings - admin only
    Route::middleware('role:admin')->group(function () {
        // Corrected settings routes - remove duplicate definition
        Route::resource('settings', SettingsController::class)->names('admin.settings');
        Route::post('settings/bulk-update', [SettingsController::class, 'bulkUpdate'])->name('admin.settings.bulk-update');

        // Reports - Add all necessary report routes
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

        // Room viewing for students (read-only)
        Route::get('/student/rooms', [RoomController::class, 'studentIndex'])->name('student.rooms.index');
        Route::get('/student/rooms/{room}', [RoomController::class, 'studentShow'])->name('student.rooms.show');

        // Bookings
        Route::get('/my-bookings', [RoomController::class, 'myBookings'])->name('bookings.my');
    });

    // Payments - accessible by all authenticated users with appropriate permissions
    Route::middleware('role:admin,hostel_manager')->group(function () {
        Route::resource('payments', PaymentController::class)->except(['edit', 'update']);
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });

    // Student payment viewing (read-only)
    Route::middleware('role:student')->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    });

    // Khalti callback route (publicly accessible)
    Route::post('payments/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');

    // Common authenticated routes
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Subscription Management
    Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/start-trial', [SubscriptionController::class, 'startTrial'])->name('subscription.start-trial');

    // Onboarding
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding/step/{step}', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::post('/onboarding/skip/{step}', [OnboardingController::class, 'skip'])->name('onboarding.skip');

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
}
