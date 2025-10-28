<?php

use App\Http\Controllers\{
    Owner\HostelController as OwnerHostelController,
    Owner\ReviewController as OwnerReviewController,
    Owner\OwnerPublicPageController,
    Owner\GalleryController as OwnerGalleryController,
    Owner\SettingsController as OwnerSettingsController,
    Owner\CircularController as OwnerCircularController,
    Admin\DashboardController,
    ProfileController,
    PaymentController,
    DocumentController,
    Admin\MealController,
    Admin\RoomController,
    Admin\StudentController,
    Admin\ContactController,
    Admin\MealMenuController as AdminMealMenuController,
    Admin\PaymentController as AdminPaymentController
};

/*|--------------------------------------------------------------------------
| Owner/Hostel Manager Routes - BOTH roles can access
|--------------------------------------------------------------------------
*/

// ✅ REMOVE the prefix from here - prefix is now in web.php
Route::middleware(['auth', 'hasOrganization', 'role:owner,hostel_manager'])
    ->name('owner.')  // ✅ KEEP only the name prefix
    ->group(function () {

        // Owner dashboard
        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])->name('dashboard');

        // Owner Settings Routes
        Route::get('/settings', [OwnerSettingsController::class, 'index'])->name('settings');
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [OwnerSettingsController::class, 'index'])->name('index');
            Route::post('/general', [OwnerSettingsController::class, 'updateGeneral'])->name('general.update');
            Route::post('/payment', [OwnerSettingsController::class, 'updatePayment'])->name('payment.update');
            Route::post('/notification', [OwnerSettingsController::class, 'updateNotification'])->name('notification.update');
            Route::post('/security', [OwnerSettingsController::class, 'updateSecurity'])->name('security.update');
        });

        // Owner profile routes - FIXED: Changed from PublicProfileController to ProfileController
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Owner Public Page Management Routes
        Route::prefix('public-page')->name('public-page.')->group(function () {
            Route::get('/edit', [OwnerPublicPageController::class, 'edit'])->name('edit');
            Route::post('/preview', [OwnerPublicPageController::class, 'updateAndPreview'])->name('preview');
            Route::post('/publish', [OwnerPublicPageController::class, 'publish'])->name('publish');
            Route::post('/unpublish', [OwnerPublicPageController::class, 'unpublish'])->name('unpublish');
        });

        // Owner Gallery Routes
        Route::prefix('galleries')->name('galleries.')->group(function () {
            Route::get('/', [OwnerGalleryController::class, 'index'])->name('index');
            Route::get('/create', [OwnerGalleryController::class, 'create'])->name('create');
            Route::post('/', [OwnerGalleryController::class, 'store'])->name('store');
            Route::get('/{gallery}', [OwnerGalleryController::class, 'show'])->name('show');
            Route::get('/{gallery}/edit', [OwnerGalleryController::class, 'edit'])->name('edit');
            Route::put('/{gallery}', [OwnerGalleryController::class, 'update'])->name('update');
            Route::delete('/{gallery}', [OwnerGalleryController::class, 'destroy'])->name('destroy');

            // Feature toggle routes
            Route::post('/{gallery}/toggle-featured', [OwnerGalleryController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/{gallery}/toggle-active', [OwnerGalleryController::class, 'toggleActive'])->name('toggle-active');
            Route::post('/{gallery}/toggle-status', [OwnerGalleryController::class, 'toggleStatus'])->name('toggle-status');

            // Video URL route for gallery video playback
            Route::get('/{gallery}/video', [OwnerGalleryController::class, 'getVideoUrl'])->name('video-url');
        });

        // Owner Payment Management Routes
        Route::get('/payments/report', [PaymentController::class, 'ownerReport'])->name('payments.report');
        Route::post('/payments/manual', [PaymentController::class, 'createManualPayment'])->name('payments.manual');
        Route::post('/payments/{payment}/approve', [PaymentController::class, 'approveBankTransfer'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [PaymentController::class, 'rejectBankTransfer'])->name('payments.reject');
        Route::get('/payments/{payment}/proof', [PaymentController::class, 'viewProof'])->name('payments.proof');

        // Owner Circular Routes
        Route::resource('circulars', OwnerCircularController::class);
        Route::post('/circulars/{circular}/publish', [OwnerCircularController::class, 'publish'])->name('circulars.publish');
        Route::get('/circulars/analytics', [OwnerCircularController::class, 'analytics'])->name('circulars.analytics');
        Route::get('/circulars/{circular}/analytics', [OwnerCircularController::class, 'analytics'])->name('circulars.analytics.single');
        Route::post('/circulars/{circular}/mark-read', [OwnerCircularController::class, 'markAsRead'])->name('circulars.mark-read');

        // Owner Document Management Routes
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
        Route::get('/hostels/{hostelId}/bookings', [\App\Http\Controllers\BookingController::class, 'hostelBookings'])->name('hostels.bookings');

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

        // Hostel toggle status route
        Route::patch('/hostels/{hostel}/toggle-status', [OwnerHostelController::class, 'toggleStatus'])
            ->name('hostels.toggle-status');

        // Owner reviews route
        Route::resource('reviews', OwnerReviewController::class);
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

        // Owner student routes
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
        Route::get('students/create', [StudentController::class, 'create'])->name('students.create')->middleware('enforce.plan.limits');
        Route::post('students', [StudentController::class, 'store'])->name('students.store')->middleware('enforce.plan.limits');
        Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::get('students/search', [StudentController::class, 'search'])->name('students.search');
        Route::get('students/export/csv', [StudentController::class, 'exportCSV'])->name('students.export-csv');

        // Owner payment routes with correct permissions
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
