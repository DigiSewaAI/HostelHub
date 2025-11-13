<?php

use App\Http\Controllers\{
    StudentController,
    PaymentController,
    Admin\RoomController,
    Frontend\PublicController,
    Student\StudentReviewController,
    BookingController,
    Student\CircularController as StudentCircularController,
    Admin\DocumentController
};

/*|--------------------------------------------------------------------------
| Student Routes - ONLY for student role
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])
    ->name('student.')
    ->group(function () {
        // Student Dashboard
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');

        // Payments route
        Route::get('/payments', [PaymentController::class, 'studentPayments'])->name('payments.index');
        Route::get('/payments/{paymentId}/receipt', [PaymentController::class, 'showReceipt'])->name('payments.receipt');
        Route::get('/payments/{paymentId}/receipt/download', [PaymentController::class, 'downloadReceipt'])->name('payments.receipt.download');

        // Meal menus routes
        Route::get('/meal-menus', [StudentController::class, 'mealMenus'])->name('meal-menus');
        Route::get('/meal-menus/{mealMenu}', [StudentController::class, 'showMealMenu'])->name('meal-menus.show');

        // Room viewing
        Route::get('/rooms', [RoomController::class, 'studentIndex'])->name('rooms.index');
        Route::get('/rooms/{room}', [RoomController::class, 'studentShow'])->name('rooms.show');
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

        // Bookings with permission check
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

        // Student profile update
        Route::patch('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');

        // Student Circular Routes
        Route::get('/circulars', [StudentCircularController::class, 'index'])->name('circulars.index');
        Route::get('/circulars/{circular}', [StudentCircularController::class, 'show'])->name('circulars.show');
        Route::post('/circulars/{circular}/mark-read', [StudentCircularController::class, 'markAsRead'])->name('circulars.mark-read');

        // ✅ PERMANENT FIX: Complete Student Document Management Routes
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
    });
