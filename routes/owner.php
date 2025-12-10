<?php

use App\Http\Controllers\Owner\HostelController as OwnerHostelController;
use App\Http\Controllers\Owner\ReviewController as OwnerReviewController;
use App\Http\Controllers\Owner\OwnerPublicPageController;
use App\Http\Controllers\Owner\GalleryController as OwnerGalleryController;
use App\Http\Controllers\Owner\SettingsController as OwnerSettingsController;
use App\Http\Controllers\Owner\CircularController as OwnerCircularController;
use App\Http\Controllers\Owner\MealController as OwnerMealController;
use App\Http\Controllers\Owner\MealMenuController as OwnerMealMenuController;
use App\Http\Controllers\Owner\BookingRequestController as OwnerBookingRequestController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController; // ✅ FIXED: Added alias
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DocumentController;

/*|--------------------------------------------------------------------------
| Owner/Hostel Manager Routes - BOTH roles can access
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'hasOrganization', 'role:owner,hostel_manager'])
    ->name('owner.')
    ->group(function () {

        // Owner dashboard
        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])->name('dashboard');

        // ✅ ADDED: Contact counts API route for real-time notifications
        Route::get('/dashboard/contacts-count', [DashboardController::class, 'getContactCounts'])->name('dashboard.contact-counts');

        // ✅ ADDED: Booking request counts API route for real-time notifications
        Route::get('/dashboard/booking-requests-count', [OwnerBookingRequestController::class, 'getCounts'])->name('dashboard.booking-requests-count');

        // Owner Settings Routes
        Route::get('/settings', [OwnerSettingsController::class, 'index'])->name('settings');
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [OwnerSettingsController::class, 'index'])->name('index');
            Route::post('/general', [OwnerSettingsController::class, 'updateGeneral'])->name('general.update');
            Route::post('/payment', [OwnerSettingsController::class, 'updatePayment'])->name('payment.update');
            Route::post('/notification', [OwnerSettingsController::class, 'updateNotification'])->name('notification.update');
            Route::post('/security', [OwnerSettingsController::class, 'updateSecurity'])->name('security.update');
        });

        // Owner profile routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // ✅ ADDED: Complete Booking Request Management Routes
        Route::prefix('booking-requests')->name('booking-requests.')->group(function () {
            Route::get('/', [OwnerBookingRequestController::class, 'index'])->name('index');
            Route::get('/{bookingRequest}', [OwnerBookingRequestController::class, 'show'])->name('show');
            Route::post('/{bookingRequest}/approve', [OwnerBookingRequestController::class, 'approve'])->name('approve');
            Route::post('/{bookingRequest}/reject', [OwnerBookingRequestController::class, 'reject'])->name('reject');
            Route::get('/api/counts', [OwnerBookingRequestController::class, 'getCounts'])->name('counts');
        });

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

        // ✅ FIXED: Owner Payment Management Routes
        Route::prefix('payments')->name('payments.')->group(function () {
            // Payment report and manual payment
            Route::get('/report', [PaymentController::class, 'ownerReport'])->name('report');
            Route::post('/manual', [PaymentController::class, 'createManualPayment'])->name('manual');

            // Bank transfer approval routes
            Route::post('/{payment}/approve', [PaymentController::class, 'approveBankTransfer'])->name('approve');
            Route::post('/{payment}/reject', [PaymentController::class, 'rejectBankTransfer'])->name('reject');
            Route::get('/{payment}/proof', [PaymentController::class, 'viewProof'])->name('proof');

            // ✅ FIXED: Bill and Receipt Routes - CORRECTLY DEFINED
            Route::get('/student/search', [PaymentController::class, 'studentSearchForInvoice'])->name('student.search');

            // Regular payment management routes
            Route::get('/', [PaymentController::class, 'index'])->name('index');
            Route::get('/create', [PaymentController::class, 'create'])->name('create');
            Route::post('/', [PaymentController::class, 'store'])->name('store');
            Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
            Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
            Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
            Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
            Route::get('/search', [PaymentController::class, 'search'])->name('search');
            Route::post('/{payment}/update-status', [PaymentController::class, 'updateStatus'])->name('update-status');

            // Excel Export Route
            Route::Post('/export', [PaymentController::class, 'export'])->name('export');
        });

        // Hostel Logo Upload Route
        Route::post('/hostels/{hostel}/logo/upload', [PaymentController::class, 'uploadHostelLogo'])->name('hostels.logo.upload');

        // ✅ FIXED: COMPLETE Owner Circular Routes with ALL methods - CORRECTED ANALYTICS ROUTES ORDER
        Route::prefix('circulars')->name('circulars.')->group(function () {
            Route::get('/', [OwnerCircularController::class, 'index'])->name('index');
            Route::get('/create', [OwnerCircularController::class, 'create'])->name('create');
            Route::post('/', [OwnerCircularController::class, 'store'])->name('store');

            // ✅ CRITICAL FIX: Analytics routes must come BEFORE the {circular} parameter routes
            Route::get('/analytics', [OwnerCircularController::class, 'analytics'])->name('analytics');

            // Individual circular routes
            Route::get('/{circular}', [OwnerCircularController::class, 'show'])->name('show');
            Route::get('/{circular}/edit', [OwnerCircularController::class, 'edit'])->name('edit');
            Route::put('/{circular}', [OwnerCircularController::class, 'update'])->name('update');
            Route::delete('/{circular}', [OwnerCircularController::class, 'destroy'])->name('destroy');

            // Circular publishing and analytics
            Route::post('/{circular}/publish', [OwnerCircularController::class, 'publish'])->name('publish');

            // Single circular analytics
            Route::get('/{circular}/analytics', [OwnerCircularController::class, 'analyticsSingle'])->name('analytics.single');

            Route::post('/{circular}/mark-read', [OwnerCircularController::class, 'markAsRead'])->name('mark-read');

            // Template management
            Route::get('/templates', [OwnerCircularController::class, 'templates'])->name('templates');
        });

        // ✅ PERMANENT FIX: Document Management Routes for Owner
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
        Route::resource('meals', OwnerMealController::class);
        Route::get('/meals/search', [OwnerMealController::class, 'search'])->name('meals.search');

        // Owner Meal Menu Routes  
        Route::resource('meal-menus', OwnerMealMenuController::class);

        // ✅ FIXED: Room routes with correct AdminRoomController alias
        Route::get('rooms', [AdminRoomController::class, 'index'])->name('rooms.index');
        Route::get('rooms/create', [AdminRoomController::class, 'create'])->name('rooms.create')->middleware('enforce.plan.limits');
        Route::post('rooms', [AdminRoomController::class, 'store'])->name('rooms.store')->middleware('enforce.plan.limits');
        Route::get('rooms/{room}', [AdminRoomController::class, 'show'])->name('rooms.show');
        Route::get('rooms/{room}/edit', [AdminRoomController::class, 'edit'])->name('rooms.edit');
        Route::put('rooms/{room}', [AdminRoomController::class, 'update'])->name('rooms.update');
        Route::delete('rooms/{room}', [AdminRoomController::class, 'destroy'])->name('rooms.destroy');
        Route::get('rooms/search', [AdminRoomController::class, 'search'])->name('rooms.search');
        Route::post('rooms/{room}/change-status', [AdminRoomController::class, 'changeStatus'])->name('rooms.change-status');
        Route::get('rooms/export/csv', [AdminRoomController::class, 'exportCSV'])->name('rooms.export-csv');

        // ✅ FIXED: Owner student routes - simplified authorization with policy-based checks
        Route::get('students', [StudentController::class, 'index'])->name('students.index');
        Route::get('students/create', [StudentController::class, 'create'])->name('students.create')->middleware('enforce.plan.limits');
        Route::post('students', [StudentController::class, 'store'])->name('students.store')->middleware('enforce.plan.limits');

        // ✅ FIXED: Use policy-based authorization for individual student operations
        Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

        Route::get('students/search', [StudentController::class, 'search'])->name('students.search');
        Route::get('students/export/csv', [StudentController::class, 'exportCSV'])->name('students.export-csv');

        // ✅ FIXED: Contact routes with all required methods
        Route::resource('contacts', ContactController::class);
        Route::get('contacts/search', [ContactController::class, 'search'])->name('contacts.search');
        Route::post('contacts/bulk-delete', [ContactController::class, 'bulkDestroy'])->name('contacts.bulk-delete');

        // ✅ FIXED: Contact update status route - CORRECT NAME AND PARAMETER
        Route::post('contacts/{contact}/update-status', [ContactController::class, 'updateStatus'])->name('contacts.update-status');

        // ✅ FIXED: Added missing contacts destroy route inside owner group
        Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

        Route::get('contacts/export/csv', [ContactController::class, 'exportCSV'])->name('contacts.export-csv');

        // ✅ FIXED: Missing Contact Routes for Owner Dashboard Features
        Route::post('contacts/{contact}/mark-read', [ContactController::class, 'markAsRead'])->name('contacts.mark-read');
        Route::post('contacts/{contact}/mark-unread', [ContactController::class, 'markAsUnread'])->name('contacts.mark-unread');
        Route::post('contacts/bulk-action', [ContactController::class, 'bulkAction'])->name('contacts.bulk-action');

        // ✅ FIXED: Gallery Feature Toggle Routes - INSIDE OWNER GROUP
        Route::patch('/galleries/{gallery}/toggle-featured', [OwnerGalleryController::class, 'toggleFeatured'])->name('galleries.toggle-featured');
        Route::patch('/galleries/{gallery}/toggle-active', [OwnerGalleryController::class, 'toggleActive'])->name('galleries.toggle-active');
    });

// ❌ REMOVED: Duplicate routes outside the group (they were causing errors)
// Add this to the contact routes section
// Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

// Gallery Feature Toggle Routes - PATCH method प्रयोग गर्ने
// Route::patch('/galleries/{gallery}/toggle-featured', [OwnerGalleryController::class, 'toggleFeatured'])->name('galleries.toggle-featured');
// Route::patch('/galleries/{gallery}/toggle-active', [OwnerGalleryController::class, 'toggleActive'])->name('galleries.toggle-active');