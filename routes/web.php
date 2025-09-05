<?php

use App\Http\Controllers\{
    Admin\ContactController,
    Admin\DashboardController,
    Admin\GalleryController,
    Admin\HostelController,
    Admin\MealController,
    Admin\PaymentController,
    Admin\ReviewController,
    Admin\RoomController,
    Admin\StudentController,
    Owner\ContactController as OwnerContactController,
    Owner\DashboardController as OwnerDashboardController,
    Owner\GalleryController as OwnerGalleryController,
    Owner\HostelController as OwnerHostelController,
    Owner\MealMenuController,
    Owner\PaymentController as OwnerPaymentController,
    Owner\ReviewController as OwnerReviewController,
    Owner\RoomController as OwnerRoomController,
    Owner\StudentController as OwnerStudentController,
    Student\DashboardController as StudentDashboardController,
    Student\MealMenuController as StudentMealMenuController,
    Student\PaymentController as StudentPaymentController,
    Student\ProfileController as StudentProfileController,
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
| Admin Routes (Super Admin - You)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Add report route for payments
    Route::get('/payment/report', [PaymentController::class, 'report'])->name('payment.report');

    // Add export route for payments
    Route::get('/payment/export', [PaymentController::class, 'export'])->name('payment.export');

    // Gallery toggle routes
    Route::post('/gallery/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])
        ->name('gallery.toggle-status');
    Route::post('/gallery/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
        ->name('gallery.toggle-featured');

    // Resource routes
    Route::resource('contact', ContactController::class);
    Route::resource('gallery', GalleryController::class);
    Route::resource('hostel', HostelController::class);
    Route::resource('meal', MealController::class);
    Route::resource('payment', PaymentController::class);
    Route::resource('review', ReviewController::class);
    Route::resource('room', RoomController::class);
    Route::resource('student', StudentController::class);
});

/*|--------------------------------------------------------------------------
| Owner Routes (Hostel Managers)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:hostel_manager'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    Route::resource('contact', OwnerContactController::class);
    Route::resource('gallery', OwnerGalleryController::class);
    Route::resource('hostel', OwnerHostelController::class);
    Route::resource('meal-menu', MealMenuController::class);
    Route::resource('payment', OwnerPaymentController::class);
    Route::resource('review', OwnerReviewController::class);
    Route::resource('room', OwnerRoomController::class);
    Route::resource('student', OwnerStudentController::class);
});

/*|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile');
    Route::get('/payment', [StudentPaymentController::class, 'index'])->name('payment');
    Route::resource('meal-menu', StudentMealMenuController::class)->only(['index', 'show']);
});

/*|--------------------------------------------------------------------------
| Authenticated Routes (Common for all authenticated users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Logout
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
| Public User Features (Accessible to all users with appropriate permissions)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // User Features
    Route::get('/my-student', [OwnerStudentController::class, 'myStudents'])->name('student.my');
    Route::get('/my-booking', [OwnerRoomController::class, 'myBookings'])->name('booking.my');

    // Payments
    Route::resource('payment', PaymentController::class)->only(['index', 'show', 'create', 'store']);
    Route::post('payment/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payment.khalti.callback');
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
}
