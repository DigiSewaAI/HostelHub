<?php

use App\Http\Controllers\{
    BookingController,
    SubscriptionController,
    PaymentController,
    OnboardingController,
    ProfileController,
    Frontend\PublicController,
    WelcomeController // ✅ NEW: Add WelcomeController
};
use Illuminate\Support\Facades\DB;

/*|--------------------------------------------------------------------------
| Global Dashboard Redirect (Role-based)
|--------------------------------------------------------------------------*/

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!session('current_organization_id')) {
        $orgUser = DB::table('organization_user')->where('user_id', $user->id)->first();
        if ($orgUser) {
            session(['current_organization_id' => $orgUser->organization_id]);
        } else {
            // Students without organization should not be redirected to organization registration
            if (!$user->hasRole('admin') && !$user->hasRole('student')) {
                return redirect()->route('register.organization');
            }
        }
    }

    // Role-based dashboard routing with proper role checks
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
        return redirect()->route('owner.dashboard');
    } elseif ($user->hasRole('student')) {
        // Always redirect students to dashboard, let dashboard handle unconnected students
        return redirect()->route('student.dashboard');
    }

    return redirect('/');
})->middleware(['auth'])->name('dashboard');

/*|--------------------------------------------------------------------------
| Organization Selection Route - FIXED: Better logic for different user types
|--------------------------------------------------------------------------*/
Route::get('/organizations/select', function () {
    $user = auth()->user();

    if ($user->hasRole('student')) {
        // For students, they don't need an organization - redirect back with message
        return redirect()->back()->with('error', 'तपाईंलाई होस्टल बुक गर्न संस्था चयन गर्न आवश्यक छैन। सिधै बुक गर्नुहोस्।');
    } else {
        // For owners/admins, redirect to organization registration
        return redirect()->route('register.organization')
            ->with('info', 'कृपया पहिले संस्था दर्ता गर्नुहोस् वा संस्था चयन गर्नुहोस्।');
    }
})->middleware(['auth'])->name('organizations.select');

/*|--------------------------------------------------------------------------
| ✅ NEW: Welcome and Booking Routes for Authenticated Users
|--------------------------------------------------------------------------*/
Route::middleware(['auth'])->group(function () {
    // Welcome and guest booking conversion
    Route::get('/welcome', [WelcomeController::class, 'showWelcome'])->name('user.welcome');
    Route::post('/convert-booking/{id}', [WelcomeController::class, 'convertGuestBooking'])->name('user.convert-booking');

    // Student booking management
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
});

/*|--------------------------------------------------------------------------
| Unified Role-Based Routes (Protected Routes for Shared Features)
|--------------------------------------------------------------------------*/
Route::middleware(['auth', 'hasOrganization'])->group(function () {

    // ✅ UPDATED: Booking approval routes for owners
    Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('bookings.reject');

    // Keep existing booking routes
    Route::get('/bookings/pending', [BookingController::class, 'pendingApprovals'])->name('bookings.pending');

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
});

/*|--------------------------------------------------------------------------
| Subscription Management
|--------------------------------------------------------------------------*/
Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
Route::post('/subscription/start-trial', [SubscriptionController::class, 'startTrial'])->name('subscription.start-trial');
Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

/*|--------------------------------------------------------------------------
| Onboarding
|--------------------------------------------------------------------------*/
Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
Route::post('/onboarding/step/{step}', [OnboardingController::class, 'store'])->name('onboarding.store');
Route::post('/onboarding/skip/{step}', [OnboardingController::class, 'skip'])->name('onboarding.skip');
Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');

/*|--------------------------------------------------------------------------
| Profile Management
|--------------------------------------------------------------------------*/
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
