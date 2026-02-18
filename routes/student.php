<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Frontend\PublicController;
use App\Http\Controllers\Student\StudentReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Student\CircularController as StudentCircularController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\WelcomeController;

/*|--------------------------------------------------------------------------
| Student Routes - ONLY for student role
|--------------------------------------------------------------------------
*/

Route::prefix('student')
    ->middleware(['auth', 'role:student'])
    ->name('student.')
    ->group(function () {

        // ✅ Student Dashboard – Protected with hostel assignment check
        Route::get('/dashboard', [StudentController::class, 'dashboard'])
            ->name('dashboard');

        // Student Profile
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
        Route::patch('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');

        // Student's Room Routes
        Route::get('/my-room', [StudentController::class, 'myRoom'])->name('my-room');
        Route::post('/report-room-issue', [StudentController::class, 'reportRoomIssue'])->name('report-room-issue');

        // Payments routes
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentController::class, 'studentPayments'])->name('index');
            Route::get('/{paymentId}/receipt', [PaymentController::class, 'showReceipt'])->name('receipt');
            Route::get('/{paymentId}/receipt/download', [PaymentController::class, 'downloadReceipt'])->name('receipt.download');
            Route::get('/{paymentId}/receipt/pdf', [PaymentController::class, 'downloadReceipt'])->name('receipt.pdf');
            Route::get('/receipt/show/{userId}', [PaymentController::class, 'studentPayments'])->name('receipt.show');
        });

        // Meal menus routes
        Route::get('/meal-menus', [StudentController::class, 'mealMenus'])->name('meal-menus');
        Route::get('/meal-menus/{mealMenu}', [StudentController::class, 'showMealMenu'])->name('meal-menus.show');

        // Room viewing
        Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');

        // Hostel routes
        Route::get('/hostel/search', [PublicController::class, 'hostelSearch'])->name('hostel.search');
        Route::get('/hostel/join', [PublicController::class, 'hostelJoin'])->name('hostel.join');
        Route::post('/hostel/{hostel}/join', [PublicController::class, 'joinHostel'])->name('hostel.join.submit');

        // Student Review Routes
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

        // Bookings routes
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

        // Student Circular Routes
        Route::prefix('circulars')->name('circulars.')->group(function () {
            Route::get('/', [StudentCircularController::class, 'index'])->name('index');
            Route::get('/{circular}', [StudentCircularController::class, 'show'])->name('show');
            Route::post('/{circular}/mark-read', [StudentCircularController::class, 'markAsRead'])->name('mark-read');
            Route::get('/{circular}/download', [StudentCircularController::class, 'download'])->name('download');
            Route::get('/unread/count', [StudentCircularController::class, 'unreadCount'])->name('unread.count');
            Route::post('/bulk-mark-read', [StudentCircularController::class, 'bulkMarkAsRead'])->name('bulk-mark-read');
            Route::get('/filter/priority/{priority}', [StudentCircularController::class, 'filterByPriority'])->name('filter.priority');
            Route::get('/search', [StudentCircularController::class, 'search'])->name('search');
            Route::get('/category/{category}', [StudentCircularController::class, 'filterByCategory'])->name('filter.category');
        });

        // Student Document Management Routes
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('index');
            Route::get('/create', [DocumentController::class, 'create'])->name('create');
            Route::post('/', [DocumentController::class, 'store'])->name('store');
            Route::get('/search', [DocumentController::class, 'search'])->name('search');
            Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
            Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
            Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
            Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
            Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
        });

        // Additional student routes
        Route::get('/gallery', [StudentController::class, 'gallery'])->name('gallery');
        Route::get('/events', [StudentController::class, 'events'])->name('events');
        Route::get('/notifications', [StudentController::class, 'notifications'])->name('notifications');
        Route::post('/maintenance-request', [StudentController::class, 'submitMaintenance'])->name('maintenance.submit');

        // Circular notifications
        Route::get('/circulars/notifications/latest', [StudentCircularController::class, 'getLatestCirculars'])->name('circulars.notifications.latest');
        Route::post('/circulars/notifications/mark-all-read', [StudentCircularController::class, 'markAllAsRead'])->name('circulars.notifications.mark-all-read');

        // ✅ Student Welcome Page (accessible without hostel assignment)
        Route::get('/welcome', [WelcomeController::class, 'showWelcome'])->name('welcome');

        // Bank transfer request (fallback)
        Route::get('/payment/bank-transfer-request', function () {
            return redirect()->route('student.payments.index')
                ->with('info', 'बैंक हस्तान्तरणको सुविधा चाँडै उपलब्ध हुनेछ।');
        })->name('payment.bank-transfer-request');
    });

// Student Notification Routes
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Student\NotificationController::class, 'index'])->name('index');
    Route::post('/mark-all-read', [\App\Http\Controllers\Student\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/unread-count', [\App\Http\Controllers\Student\NotificationController::class, 'unreadCount'])->name('unread-count');
});
