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
    Owner\ReviewController as OwnerReviewController,
    Owner\OwnerPublicPageController,
    Owner\GalleryController as OwnerGalleryController,
    Owner\SettingsController as OwnerSettingsController,
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
    Auth\LoginController,
    RegistrationController,
    SubscriptionController,
    OnboardingController,
    ProfileController as PublicProfileController,
    BookingController,
    PaymentController,
    DocumentController,
    Admin\CircularController as AdminCircularController,
    Owner\CircularController as OwnerCircularController,
    Student\CircularController as StudentCircularController,
    Student\StudentReviewController
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

    // ✅ ADDED: Public hostel listing routes
    Route::get('/hostels', [PublicController::class, 'hostelsIndex'])->name('hostels.index');
    Route::get('/hostels/{slug}', [PublicController::class, 'hostelShow'])->name('hostels.show');

    // ✅ CORRECTED: Public hostel gallery routes - FIXED AND UPDATED
    Route::get('/hostel/{slug}/gallery', [PublicController::class, 'hostelGallery'])->name('hostel.gallery');
    Route::get('/hostel/{slug}/full-gallery', [PublicController::class, 'hostelFullGallery'])->name('hostel.full-gallery');

    // ✅ ADDED: Missing book-room route that was causing the error
    Route::get('/hostel/{slug}/book-room', [BookingController::class, 'create'])->name('hostel.book-room');

    // ✅ ADDED: Missing hostel contact route
    Route::post('/hostels/{hostel}/contact', [PublicController::class, 'hostelContact'])->name('hostel.contact');
    // ✅ FIXED: Changed preview route to use OwnerPublicPageController
    Route::get('/preview/{slug}', [OwnerPublicPageController::class, 'preview'])->name('hostels.preview');

    // Gallery API Routes - VERIFIED: These routes are already correctly defined
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

    // ✅ ADDED: Student hostel search route (missing route)
    Route::get('/hostels/search', [PublicController::class, 'hostelSearch'])->name('hostels.search');

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
| ✅ FIXED: Authentication Routes - Using LoginController instead of AuthenticatedSessionController
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.submit');

    // ✅ FIXED: Changed to use LoginController for both GET and POST
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

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
| ✅ FIXED: Global Logout Route (Simplified - Moved outside middleware)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*|--------------------------------------------------------------------------
| Student Welcome Route (For unconnected students after registration)
|--------------------------------------------------------------------------
*/
Route::get('/student/welcome', function () {
    // Check if student already has hostel, if yes redirect to dashboard
    $student = auth()->user()->student;
    if ($student && $student->hostel_id) {
        return redirect()->route('student.dashboard');
    }
    return view('student.welcome');
})->name('student.welcome')->middleware(['auth', 'role:student']);

/*|--------------------------------------------------------------------------
| Global Dashboard Redirect (Role-based) - UPDATED WITH ROLE FIXES
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!session('current_organization_id')) {
        $orgUser = DB::table('organization_user')->where('user_id', $user->id)->first();
        if ($orgUser) {
            session(['current_organization_id' => $orgUser->organization_id]);
        } else {
            // ✅ FIXED: Students without organization should not be redirected to organization registration
            if (!$user->hasRole('admin') && !$user->hasRole('student')) {
                return redirect()->route('register.organization');
            }
        }
    }

    // ✅ UPDATED: Role-based dashboard routing with proper role checks
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
        return redirect()->route('owner.dashboard');
    } elseif ($user->hasRole('student')) {
        // ✅ FIXED: Always redirect students to dashboard, let dashboard handle unconnected students
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
        'is_hostel_manager' => $user->hasRole('hostel_manager') ? 'YES' : 'NO',
        'is_owner' => $user->hasRole('owner') ? 'YES' : 'NO'
    ];
})->middleware('auth');

Route::get('/assign-role/{role}', function ($role) {
    $user = auth()->user();

    if (!$user) {
        return "No user logged in";
    }

    // Valid roles check
    $validRoles = ['admin', 'hostel_manager', 'student', 'owner'];
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
| ✅ FIXED: Admin Routes Group - ONLY for admin role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'hasOrganization', 'role:admin'])
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

        // ✅ FIXED: Admin Room Routes - REMOVED EXCEPT AND USING FULL RESOURCE
        Route::resource('rooms', RoomController::class);
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

        // ✅ FIXED: Admin Settings Routes - COMPLETE FIX
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::get('/create', [SettingsController::class, 'create'])->name('create');
            Route::post('/', [SettingsController::class, 'store'])->name('store');
            Route::get('/{setting}', [SettingsController::class, 'show'])->name('show');
            Route::get('/{setting}/edit', [SettingsController::class, 'edit'])->name('edit');
            Route::put('/{setting}', [SettingsController::class, 'update'])->name('update');
            Route::delete('/{setting}', [SettingsController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-update', [SettingsController::class, 'bulkUpdate'])->name('bulk-update');
        });

        // ✅ ADDED: Direct settings route for compatibility
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

        // ✅ UPDATED: Admin Circular Routes - Using separate controller
        Route::resource('circulars', AdminCircularController::class);
        Route::post('/circulars/{circular}/publish', [AdminCircularController::class, 'publish'])->name('circulars.publish');
        Route::get('/circulars/analytics', [AdminCircularController::class, 'analytics'])->name('circulars.analytics');
        Route::get('/circulars/{circular}/analytics', [AdminCircularController::class, 'analytics'])->name('circulars.analytics.single');
        Route::post('/circulars/{circular}/mark-read', [AdminCircularController::class, 'markAsRead'])->name('circulars.mark-read');

        // ✅ ADDED: Circular Templates Route
        Route::get('/circulars/templates', [AdminCircularController::class, 'templates'])->name('circulars.templates');

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

            // Payment verification
            Route::middleware([\App\Http\Middleware\CheckPermission::class . ':payments_edit'])->group(function () {
                Route::get('/verification', [AdminPaymentController::class, 'verification'])->name('verification');
                Route::put('/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('verify');
            });
        });

        // ✅ ADDED: Admin Document Management Routes
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('index');
            Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
            Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
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
    });

/*|--------------------------------------------------------------------------
| ✅ FIXED: Student Routes - ONLY for student role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // 🔥 CRITICAL FIX: Changed from DashboardController to StudentController for dashboard
    Route::get('/dashboard', [\App\Http\Controllers\StudentController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');

    // ✅ FIXED: Payments route with correct naming
    Route::get('/payments', [PaymentController::class, 'studentPayments'])->name('payments.index');
    Route::get('/payments/{paymentId}/receipt', [PaymentController::class, 'showReceipt'])->name('payments.receipt');
    Route::get('/payments/{paymentId}/receipt/download', [PaymentController::class, 'downloadReceipt'])->name('payments.receipt.download');

    // ✅ FIXED: Meal menus routes - COMPATIBILITY FIX
    Route::get('/meal-menus', [StudentController::class, 'mealMenus'])->name('meal-menus');
    Route::get('/meal-menus/{mealMenu}', [StudentController::class, 'showMealMenu'])->name('meal-menus.show');

    // Room viewing
    Route::get('/rooms', [RoomController::class, 'studentIndex'])->name('rooms.index');
    Route::get('/rooms/{room}', [RoomController::class, 'studentShow'])->name('rooms.show');
    Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');

    // ✅ FIXED: Hostel routes with correct naming
    Route::get('/hostel/search', [PublicController::class, 'hostelSearch'])->name('hostel.search');
    Route::get('/hostel/join', [PublicController::class, 'hostelJoin'])->name('hostel.join');
    Route::post('/hostel/{hostel}/join', [PublicController::class, 'joinHostel'])->name('hostel.join.submit');

    // ✅ ADDED: Student Review Routes - Complete CRUD system
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [StudentReviewController::class, 'index'])->name('index');
        Route::get('/create', [StudentReviewController::class, 'create'])->name('create');
        Route::post('/', [StudentReviewController::class, 'store'])->name('store');
        Route::get('/{review}', [StudentReviewController::class, 'show'])->name('show');
        Route::get('/{review}/edit', [StudentReviewController::class, 'edit'])->name('edit');
        Route::put('/{review}', [StudentReviewController::class, 'update'])->name('update');
        Route::delete('/{review}', [StudentReviewController::class, 'destroy'])->name('destroy');
        Route::get('/hostel/{hostelId}', [StudentReviewController::class, 'hostelReviews'])->name('hostel');
    });

    // Bookings with permission check
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Student profile update
    Route::patch('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');

    // ✅ UPDATED: Student Circular Routes - Using separate controller
    Route::get('/circulars', [StudentCircularController::class, 'index'])->name('circulars.index');
    Route::get('/circulars/{circular}', [StudentCircularController::class, 'show'])->name('circulars.show');
    Route::post('/circulars/{circular}/mark-read', [StudentCircularController::class, 'markAsRead'])->name('circulars.mark-read');

    // ✅ ADDED: New student routes as requested - WITH COMPATIBILITY
    Route::get('/gallery', [StudentController::class, 'gallery'])->name('gallery');
    Route::get('/events', [StudentController::class, 'events'])->name('events');
    Route::get('/notifications', [StudentController::class, 'notifications'])->name('notifications');
    Route::post('/maintenance-request', [StudentController::class, 'submitMaintenance'])->name('maintenance.submit');
});

/*|--------------------------------------------------------------------------
| ✅ FIXED: Owner/Hostel Manager Routes - BOTH roles can access
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'hasOrganization', 'role:owner,hostel_manager'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        // Owner dashboard - NOW WITH PROPER ACCESS
        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])->name('dashboard');

        // 🔥 CRITICAL FIX: Owner Settings Routes - CORRECTED AND COMPLETE
        Route::get('/settings', [OwnerSettingsController::class, 'index'])->name('settings');

        // ✅ ADDED: Complete settings routes for owner
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [OwnerSettingsController::class, 'index'])->name('index');
            Route::post('/general', [OwnerSettingsController::class, 'updateGeneral'])->name('general.update');
            Route::post('/payment', [OwnerSettingsController::class, 'updatePayment'])->name('payment.update');
            Route::post('/notification', [OwnerSettingsController::class, 'updateNotification'])->name('notification.update');
            Route::post('/security', [OwnerSettingsController::class, 'updateSecurity'])->name('security.update');
        });

        // ✅ ADDED: Owner profile routes to fix the missing route error
        Route::get('/profile', [PublicProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile', [PublicProfileController::class, 'update'])->name('profile.update');

        // ✅ FIXED: Owner Public Page Management Routes - SINGLE ROUTE GROUP (duplicate removed)
        Route::prefix('public-page')->name('public-page.')->group(function () {
            Route::get('/edit', [OwnerPublicPageController::class, 'edit'])->name('edit');
            Route::post('/preview', [OwnerPublicPageController::class, 'updateAndPreview'])->name('preview');
            Route::post('/publish', [OwnerPublicPageController::class, 'publish'])->name('publish');
            Route::post('/unpublish', [OwnerPublicPageController::class, 'unpublish'])->name('unpublish');
        });

        // ✅ CORRECTED: Owner Gallery Routes - Fixed resource routing structure
        Route::prefix('galleries')->name('galleries.')->group(function () {
            // ✅ FIXED: Proper resource routing without conflicting names
            Route::get('/', [OwnerGalleryController::class, 'index'])->name('index');
            Route::get('/create', [OwnerGalleryController::class, 'create'])->name('create');
            Route::post('/', [OwnerGalleryController::class, 'store'])->name('store');
            Route::get('/{gallery}', [OwnerGalleryController::class, 'show'])->name('show');
            Route::get('/{gallery}/edit', [OwnerGalleryController::class, 'edit'])->name('edit');
            Route::put('/{gallery}', [OwnerGalleryController::class, 'update'])->name('update');
            Route::delete('/{gallery}', [OwnerGalleryController::class, 'destroy'])->name('destroy');

            // ✅ FIXED: Feature toggle routes - Changed to POST for consistency
            Route::post('/{gallery}/toggle-featured', [OwnerGalleryController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/{gallery}/toggle-active', [OwnerGalleryController::class, 'toggleActive'])->name('toggle-active');
            Route::post('/{gallery}/toggle-status', [OwnerGalleryController::class, 'toggleStatus'])->name('toggle-status');

            // ✅ FIXED: Video URL route for gallery video playback
            Route::get('/{gallery}/video', [OwnerGalleryController::class, 'getVideoUrl'])->name('video-url');
        });

        // Owner Payment Management Routes (Using Frontend PaymentController)
        Route::get('/payments/report', [PaymentController::class, 'ownerReport'])->name('payments.report');
        Route::post('/payments/manual', [PaymentController::class, 'createManualPayment'])->name('payments.manual');
        Route::post('/payments/{payment}/approve', [PaymentController::class, 'approveBankTransfer'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [PaymentController::class, 'rejectBankTransfer'])->name('payments.reject');
        Route::get('/payments/{payment}/proof', [PaymentController::class, 'viewProof'])->name('payments.proof');

        // ✅ UPDATED: Owner Circular Routes - Using separate controller
        Route::resource('circulars', OwnerCircularController::class);
        Route::post('/circulars/{circular}/publish', [OwnerCircularController::class, 'publish'])->name('circulars.publish');
        Route::get('/circulars/analytics', [OwnerCircularController::class, 'analytics'])->name('circulars.analytics');
        Route::get('/circulars/{circular}/analytics', [OwnerCircularController::class, 'analytics'])->name('circulars.analytics.single');
        Route::post('/circulars/{circular}/mark-read', [OwnerCircularController::class, 'markAsRead'])->name('circulars.mark-read');

        // ✅ ADDED: Owner Document Management Routes
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('index');
            Route::get('/create', [DocumentController::class, 'create'])->name('create');
            Route::post('/', [DocumentController::class, 'store'])->name('store');
            Route::get('/search', [DocumentController::class, 'search'])->name('search');
            Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
            Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
            Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
        });

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

        // ✅ FIXED: Owner reviews route - Using OwnerReviewController instead of AdminReviewController
        Route::resource('reviews', OwnerReviewController::class);

        // ✅ CRITICAL FIX: Add the missing reply route for owner reviews - FIXED ROUTE NAME
        Route::post('/reviews/{review}/reply', [OwnerReviewController::class, 'reply'])->name('reviews.reply');

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

/*|--------------------------------------------------------------------------
| Unified Role-Based Routes (Protected Routes for Shared Features)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'hasOrganization'])->group(function () {

    // Booking routes
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/bookings/pending', [BookingController::class, 'pendingApprovals'])->name('bookings.pending');
    Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('bookings.reject');

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
});

// ✅ MOVED: Subscription Management outside the hasOrganization middleware for flexibility
Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
Route::post('/subscription/start-trial', [SubscriptionController::class, 'startTrial'])->name('subscription.start-trial');
Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

// ✅ MOVED: Onboarding outside the hasOrganization middleware
Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
Route::post('/onboarding/step/{step}', [OnboardingController::class, 'store'])->name('onboarding.store');
Route::post('/onboarding/skip/{step}', [OnboardingController::class, 'skip'])->name('onboarding.skip');
Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');

// ✅ MOVED: Profile Management outside the hasOrganization middleware
Route::get('/profile', [PublicProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [PublicProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [PublicProfileController::class, 'destroy'])->name('profile.destroy');

// ✅ MOVED: Password Management outside the hasOrganization middleware
Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])->name('password.confirm.store');

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

    // ✅ ADDED: Temporary debug route for hostel preview
    Route::get('/debug-hostel/{slug}', function ($slug) {
        $hostel = \App\Models\Hostel::with(['images', 'rooms', 'mealMenus'])->where('slug', $slug)->first();

        if (!$hostel) {
            return "Hostel not found with slug: {$slug}";
        }

        return [
            'hostel_data' => [
                'id' => $hostel->id,
                'name' => $hostel->name,
                'slug' => $hostel->slug,
                'description' => $hostel->description,
                'theme_color' => $hostel->theme_color,
                'logo_path' => $hostel->logo_path,
                'is_published' => $hostel->is_published,
                'organization_id' => $hostel->organization_id,
            ],
            'relationships' => [
                'images_count' => $hostel->images->count(),
                'rooms_count' => $hostel->rooms->count(),
                'mealMenus_count' => $hostel->mealMenus->count(),
            ],
            'session_data' => [
                'current_organization_id' => session('current_organization_id'),
            ]
        ];
    });

    // ✅ ADDED: Database check and update routes for hostel data
    // Check hostel data in database
    Route::get('/check-hostel-data/{slug}', function ($slug) {
        $hostel = \App\Models\Hostel::where('slug', $slug)->first();

        if (!$hostel) {
            return "Hostel not found!";
        }

        // Check database directly
        $dbData = \DB::table('hostels')->where('slug', $slug)->first();

        return [
            'database_data' => $dbData,
            'model_data' => [
                'name' => $hostel->name,
                'description' => $hostel->description,
                'theme_color' => $hostel->theme_color,
                'logo_path' => $hostel->logo_path,
            ]
        ];
    });

    // Update hostel data temporarily
    Route::get('/update-hostel-data/{slug}', function ($slug) {
        $hostel = \App\Models\Hostel::where('slug', $slug)->first();

        if (!$hostel) {
            return "Hostel not found!";
        }

        // Update with sample data
        $hostel->update([
            'description' => 'यो Sanctuary Girls Hostel को विवरण हो। हामी विद्यार्थीहरूको लागि उत्कृष्ट र सुरक्षित बसाइ सुनिश्चित गर्दछौं।',
            'theme_color' => '#10b981', // green color
            'logo_path' => 'hostels/logos/sanctuary1.jpg'
        ]);

        return "Hostel data updated! Check: " . route('debug-hostel', $slug);
    });

    // ✅ ADDED: Debug route for admin routes testing
    Route::get('/debug-admin-routes', function () {
        $user = auth()->user();
        $routes = [
            'admin.rooms.create' => route('admin.rooms.create'),
            'admin.rooms.store' => route('admin.rooms.store'),
            'admin.rooms.index' => route('admin.rooms.index'),
        ];

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'roles' => $user->getRoleNames()->toArray(),
            ],
            'routes' => $routes,
            'route_exists' => Route::has('admin.rooms.create'),
        ];
    })->middleware(['auth', 'role:admin']);
}

/*|--------------------------------------------------------------------------
| Development Routes
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/test-route', function () {
        return 'Test route';
    })->name('test.route');

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

    // ✅ UPDATED: Debug route for gallery testing with column fix
    Route::get('/debug-gallery/{slug}', function ($slug) {
        // Check what columns exist in galleries table
        $columns = \DB::getSchemaBuilder()->getColumnListing('galleries');

        $hostel = \App\Models\Hostel::with(['galleries' => function ($query) use ($columns) {
            // Check which status column exists
            if (in_array('is_active', $columns)) {
                $query->where('is_active', true);
            } else if (in_array('status', $columns)) {
                $query->where('status', 'active');
            }
            // If neither exists, get all galleries
        }])->where('slug', $slug)->first();

        if (!$hostel) {
            return "Hostel not found with slug: {$slug}";
        }

        return [
            'galleries_table_columns' => $columns,
            'hostel' => [
                'id' => $hostel->id,
                'name' => $hostel->name,
                'slug' => $hostel->slug,
                'is_published' => $hostel->is_published,
            ],
            'galleries' => $hostel->galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'media_type' => $gallery->media_type,
                    'file_path' => $gallery->file_path,
                    'external_link' => $gallery->external_link,
                    'category' => $gallery->category,
                    'is_active' => $gallery->is_active ?? 'N/A',
                    'status' => $gallery->status ?? 'N/A',
                ];
            }),
            'gallery_count' => $hostel->galleries->count(),
            'route_exists' => \Route::has('hostel.gallery'),
            'route_url' => route('hostel.gallery', $slug),
        ];
    });

    // ✅ ADDED: Temporary route to publish hostel for testing
    Route::get('/publish-hostel/{slug}', function ($slug) {
        $hostel = \App\Models\Hostel::where('slug', $slug)->first();

        if (!$hostel) {
            return "Hostel not found with slug: {$slug}";
        }

        $hostel->update(['is_published' => true]);

        return [
            'message' => "Hostel '{$hostel->name}' has been published!",
            'hostel' => [
                'id' => $hostel->id,
                'name' => $hostel->name,
                'slug' => $hostel->slug,
                'is_published' => $hostel->is_published
            ],
            'gallery_url' => route('hostel.gallery', $slug)
        ];
    });

    // ✅ ADDED: Debug view path
    Route::get('/debug-view-path', function () {
        $viewPaths = [
            'public.hostels.gallery' => view()->exists('public.hostels.gallery'),
            'frontend.hostels.gallery' => view()->exists('frontend.hostels.gallery'),
            'public.hostels.gallery.blade' => view()->exists('public.hostels.gallery.blade'),
        ];

        return [
            'view_exists' => $viewPaths,
            'views_directory' => base_path('resources/views'),
            'public_hostels_path' => is_dir(base_path('resources/views/public/hostels')) ? 'Exists' : 'Not found',
            'gallery_file' => file_exists(base_path('resources/views/public/hostels/gallery.blade.php')) ? 'Exists' : 'Not found'
        ];
    });

    // ✅ ADDED: Check gallery table structure
    Route::get('/check-gallery-structure', function () {
        $columns = \DB::getSchemaBuilder()->getColumnListing('galleries');

        $sampleGallery = \App\Models\Gallery::first();

        return [
            'columns_in_galleries_table' => $columns,
            'sample_gallery' => $sampleGallery ? $sampleGallery->toArray() : 'No galleries found',
            'total_galleries' => \App\Models\Gallery::count()
        ];
    });

    // Temporary debug route - remove after testing
    Route::get('/debug-hostel-data/{slug}', function ($slug) {
        $hostel = \App\Models\Hostel::where('slug', $slug)->first();

        if (!$hostel) {
            return "Hostel not found";
        }

        return [
            'hostel_name' => $hostel->name,
            'logo_path' => $hostel->logo_path,
            'logo_path_raw' => $hostel->getRawOriginal('logo_path'),
            'facilities' => $hostel->facilities,
            'facilities_raw' => $hostel->getRawOriginal('facilities'),
            'facilities_type' => gettype($hostel->facilities),
            'storage_exists' => $hostel->logo_path ? \Storage::disk('public')->exists($hostel->logo_path) : false,
            'storage_files' => $hostel->logo_path ? \Storage::disk('public')->files(dirname($hostel->logo_path)) : [],
        ];
    });

    // TEMPORARY DEBUG ROUTE - Add this right after your admin routes group
    Route::get('/test-admin-rooms-create', function () {
        $user = auth()->user();

        if (!$user) {
            return "Not authenticated";
        }

        if (!$user->hasRole('admin')) {
            return "Not an admin user. Roles: " . $user->getRoleNames()->implode(', ');
        }

        // Test if we can access the controller method directly
        try {
            $controller = new \App\Http\Controllers\Admin\RoomController();
            $hostels = \App\Models\Hostel::all();
            return "Controller accessible. Hostels count: " . $hostels->count();
        } catch (\Exception $e) {
            return "Controller error: " . $e->getMessage();
        }
    })->middleware(['auth', 'hasOrganization', 'role:admin']);

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
