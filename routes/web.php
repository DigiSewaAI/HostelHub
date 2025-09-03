<?php

use App\Http\Controllers\{
    Admin\ContactsController,
    Admin\DashboardController,
    Admin\GalleriesController,
    Admin\HostelsController,
    Admin\MealsController,
    Admin\PaymentsController,
    Admin\ReviewsController,
    Admin\RoomsController,
    Admin\StudentsController,
    Owner\ContactsController as OwnerContactsController,
    Owner\DashboardController as OwnerDashboardController,
    Owner\GalleriesController as OwnerGalleriesController,
    Owner\HostelController,
    Owner\MealMenusController,
    Owner\PaymentsController as OwnerPaymentsController,
    Owner\ReviewsController as OwnerReviewsController,
    Owner\RoomsController as OwnerRoomsController,
    Owner\StudentsController as OwnerStudentsController,
    Student\DashboardController as StudentDashboardController,
    Student\MealMenusController as StudentMealMenusController,
    Student\PaymentsController as StudentPaymentsController,
    Student\ProfileController as StudentProfileController,
    Frontend\GalleryController,
    Frontend\PublicContactController,
    Frontend\PublicController,
    Frontend\PricingController,
    Frontend\StudentController as PublicStudentController,
    Frontend\RoomController as PublicRoomController,
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
| Public Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'web'], function () {
    // Updated to use correct controller references
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/about', [PublicController::class, 'about'])->name('about');
    Route::get('/features', [PublicController::class, 'features'])->name('features');
    Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

    // Updated route for testimonials (previously reviews)
    Route::get('/testimonials', [PublicController::class, 'testimonials'])->name('testimonials');

    Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
    Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

    // Public room and student routes
    Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms.index');
    Route::get('/students', [PublicStudentController::class, 'index'])->name('students.index');

    // Public Routes भित्र, अन्य routes सँगै
    Route::get('/demo', function () {
        return view('pages.demo');
    })->name('demo');
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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // FIXED: Consistent plural naming for all resources to avoid route name issues
    Route::resource('contacts', ContactsController::class);
    Route::resource('galleries', GalleriesController::class); // Uses admin.galleries.* routes
    Route::resource('hostels', HostelsController::class);
    Route::resource('meals', MealsController::class);
    Route::resource('payments', PaymentsController::class);
    Route::resource('reviews', ReviewsController::class);
    Route::resource('rooms', RoomsController::class);
    Route::resource('students', StudentsController::class);
});

/*|--------------------------------------------------------------------------
| Owner Routes (Hostel Managers)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:hostel_manager'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // FIXED: Consistent plural naming for all resources to avoid route name issues
    Route::resource('contacts', OwnerContactsController::class);
    Route::resource('galleries', OwnerGalleriesController::class); // Uses owner.galleries.* routes
    Route::resource('hostels', HostelController::class);
    Route::resource('meal-menus', MealMenusController::class);
    Route::resource('payments', OwnerPaymentsController::class);
    Route::resource('reviews', OwnerReviewsController::class);
    Route::resource('rooms', OwnerRoomsController::class);
    Route::resource('students', OwnerStudentsController::class);
});

/*|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile');
    Route::get('/payments', [StudentPaymentsController::class, 'index'])->name('payments');
    Route::resource('meal-menus', StudentMealMenusController::class)->only(['index', 'show']);
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
    Route::get('/my-students', [PublicStudentController::class, 'myStudents'])->name('students.my');
    Route::get('/my-bookings', [PublicRoomController::class, 'myBookings'])->name('bookings.my');

    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'show', 'create', 'store']);
    Route::post('payments/khalti-callback', [PaymentController::class, 'khaltiCallback'])->name('payments.khalti.callback');
});

/*|--------------------------------------------------------------------------
| Redirect Old Admin URLs
|--------------------------------------------------------------------------
*/
// Admin gallery -> galleries redirect (plural form)
Route::redirect('/admin/gallery', '/admin/galleries', 301);
Route::redirect('/admin/gallery/{any?}', '/admin/galleries/{any?}', 301)
    ->where('any', '.*');

// Admin meal-menus -> meals redirect
Route::redirect('/admin/meal-menus', '/admin/meals', 301);
Route::redirect('/admin/meal-menus/{any?}', '/admin/meals/{any?}', 301)
    ->where('any', '.*');

// Admin contact -> contacts redirect
Route::redirect('/admin/contact', '/admin/contacts', 301);
Route::redirect('/admin/contact/{any?}', '/admin/contacts/{any?}', 301)
    ->where('any', '.*');

// Admin payment -> payments redirect
Route::redirect('/admin/payment', '/admin/payments', 301);
Route::redirect('/admin/payment/{any?}', '/admin/payments/{any?}', 301)
    ->where('any', '.*');

// Admin review -> reviews redirect
Route::redirect('/admin/review', '/admin/reviews', 301);
Route::redirect('/admin/review/{any?}', '/admin/reviews/{any?}', 301)
    ->where('any', '.*');

// Add this route in your web.php
Route::post('/search', [PublicController::class, 'search'])->name('search');

/*|--------------------------------------------------------------------------
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
