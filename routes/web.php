<?php

use App\Http\Controllers\{
    Admin\DashboardController as AdminDashboardController,
    Admin\HostelController as AdminHostelController,
    Admin\RoomController as AdminRoomController,
    Admin\StudentController as AdminStudentController,
    Admin\GalleryController as AdminGalleryController,
    Admin\MealMenuController as AdminMealMenuController,
    Admin\ReviewController as AdminReviewController,
    Auth\AuthenticatedSessionController,
    Auth\ConfirmablePasswordController,
    Auth\EmailVerificationNotificationController,
    Auth\EmailVerificationPromptController,
    Auth\NewPasswordController,
    Auth\PasswordController,
    Auth\PasswordResetLinkController,
    Auth\RegisteredUserController,
    Auth\VerifyEmailController,
    ContactController as PublicContactController,
    GalleryController as PublicGalleryController,
    MealController as PublicMealController,
    OnboardingController,
    PaymentController,
    PricingController,
    ProfileController,
    PublicController,
    RegistrationController,
    RoomController as PublicRoomController,
    StudentController as PublicStudentController,
    SubscriptionController,
    SearchController
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
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/gallery', [PublicGalleryController::class, 'publicIndex'])->name('gallery.public');
Route::get('/reviews', [PublicController::class, 'reviews'])->name('reviews');
Route::get('/privacy-policy', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
Route::get('/cookies', [PublicController::class, 'cookies'])->name('cookies');
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PublicController::class, 'robots'])->name('robots');

// Demo Page Route
Route::get('/demo', [PublicController::class, 'demo'])->name('demo');

// Search Route
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Public Meal Gallery Route
Route::get('/pages/meal-gallery', function () {
    $mealMenus = \App\Models\MealMenu::with('hostel')->where('is_active', true)->get();
    return view('pages.meal-gallery', compact('mealMenus'));
})->name('meal.gallery');

// Gallery API Routes
Route::get('/api/gallery/data', [PublicGalleryController::class, 'getGalleryData']);
Route::get('/api/gallery/categories', [PublicGalleryController::class, 'getGalleryCategories']);
Route::get('/api/gallery/stats', [PublicGalleryController::class, 'getGalleryStats']);

/*
|--------------------------------------------------------------------------
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
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.reset.store');

    // Email Verification
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
| Global Dashboard Redirect (Role-based)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('hostel_manager')) {
        return redirect()->route('hostel.manager.dashboard');
    } elseif ($user->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }

    return redirect('/');
})->middleware('auth')->name('dashboard'); // Removed EnsureOrgContext and EnsureSubscriptionActive

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () { // Removed EnsureOrgContext
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Subscription Management
    Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/start-trial', [SubscriptionController::class, 'startTrial'])->name('subscription.start-trial');

    // Active Subscription Routes
    Route::middleware([])->group(function () { // Removed EnsureSubscriptionActive
        // Onboarding
        Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
        Route::post('/onboarding/step/{step}', [OnboardingController::class, 'store'])->name('onboarding.store');
        Route::post('/onboarding/skip/{step}', [OnboardingController::class, 'skip'])->name('onboarding.skip');

        // Profile Management
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Password Management
        Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
        Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

        // User Features
        Route::get('/my-students', [PublicStudentController::class, 'myStudents'])->name('students.my');
        Route::get('/my-bookings', [PublicRoomController::class, 'myBookings'])->name('bookings.my');

        // Payments
        Route::resource('payments', PaymentController::class)->only(['index', 'show', 'create', 'store']);
        Route::post('payments/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');
    });
});

/*
|--------------------------------------------------------------------------
| Role-based Routes
|--------------------------------------------------------------------------
*/

// Hostel Manager Routes
Route::middleware(['auth', 'role:hostel_manager']) // Removed EnsureOrgContext and EnsureSubscriptionActive
    ->prefix('owner')
    ->name('hostel.manager.')
    ->group(function () {
        Route::get('dashboard', function () {
            return view('hostel-manager.dashboard');
        })->name('dashboard');

        Route::resource('hostels', AdminHostelController::class)->only(['show', 'edit', 'update']);
        Route::post('hostels/{hostel}/availability', [AdminHostelController::class, 'updateAvailability'])->name('hostels.availability');

        // Rooms: Ensure all actions including 'show'
        Route::resource('rooms', AdminRoomController::class);
        Route::get('rooms/availability', [AdminRoomController::class, 'availability'])->name('rooms.availability');

        Route::resource('students', AdminStudentController::class)->only(['index', 'show']);

        // Reviews Management for Hostel Manager
        Route::resource('reviews', AdminReviewController::class);
    });

// Student Routes
Route::middleware(['auth', 'role:student']) // Removed EnsureOrgContext and EnsureSubscriptionActive
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('dashboard', function () {
            return view('student.dashboard');
        })->name('dashboard');

        Route::get('hostels', [AdminHostelController::class, 'index'])->name('hostels.index');
        Route::get('hostels/{hostel}', [AdminHostelController::class, 'show'])->name('hostels.show');

        // Public Room Routes for Students
        Route::get('rooms', [PublicRoomController::class, 'index'])->name('rooms.index');
        Route::get('rooms/{room}', [PublicRoomController::class, 'show'])->name('rooms.show');
        Route::get('my-bookings', [PublicRoomController::class, 'myBookings'])->name('bookings.my');
        Route::get('booking/search', [PublicRoomController::class, 'search'])->name('booking.search');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin & Hostel Manager)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,hostel_manager']) // Removed EnsureOrgContext and EnsureSubscriptionActive
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Gallery Routes
        Route::resource('gallery', AdminGalleryController::class);
        Route::get('gallery/stream/{gallery}', [AdminGalleryController::class, 'streamVideo'])->name('gallery.stream');

        // Meal Menu Routes
        Route::resource('meal-menus', AdminMealMenuController::class);

        // Hostel Routes
        Route::resource('hostels', AdminHostelController::class);

        // Room Routes
        Route::resource('rooms', AdminRoomController::class);

        // Student Routes
        Route::resource('students', AdminStudentController::class);

        // Review Routes
        Route::resource('reviews', AdminReviewController::class);
    });

/*
|--------------------------------------------------------------------------
| Development Routes
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
