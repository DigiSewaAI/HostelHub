<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HostelController as AdminHostelController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\MealMenuController as AdminMealMenuController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CircularController as AdminCircularController;
use App\Http\Controllers\Admin\OrganizationRequestController;
use App\Http\Controllers\Admin\DocumentController;


/*|--------------------------------------------------------------------------
| Admin Routes - ONLY for admin role
|--------------------------------------------------------------------------
*/

// ✅ FIXED: Remove duplicate middleware since it's already applied in web.php
Route::name('admin.')  // ✅ KEEP only the name prefix
    ->group(function () {

        // Admin Dashboard with cache management
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::post('/dashboard/clear-cache', [DashboardController::class, 'clearCache'])->name('dashboard.clear-cache');

        // ✅ NEW: Contact counts API route for real-time notifications
        Route::get('/dashboard/contacts-count', [DashboardController::class, 'getContactCounts'])->name('dashboard.contacts-count');

        // ✅ NEW: Organization Request Management Routes
        Route::prefix('organization-requests')->name('organization-requests.')->group(function () {
            Route::get('/', [OrganizationRequestController::class, 'index'])->name('index');
            Route::get('/{organizationRequest}', [OrganizationRequestController::class, 'show'])->name('show');
            Route::post('/{organizationRequest}/approve', [OrganizationRequestController::class, 'approve'])->name('approve');
            Route::post('/{organizationRequest}/reject', [OrganizationRequestController::class, 'reject'])->name('reject');
        });

        // Admin Resources - Contact Routes
        Route::resource('contacts', ContactController::class);
        Route::get('/contacts/search', [ContactController::class, 'search'])->name('contacts.search');
        // ✅ ADDED: Contact filter route for dashboard features
        Route::get('/contacts/filter', [ContactController::class, 'getFilteredContacts'])->name('contacts.filter');
        Route::post('/contacts/bulk-delete', [ContactController::class, 'bulkDestroy'])->name('contacts.bulk-delete');

        // ✅ CRITICAL FIX: Correct route name from update-status to updateStatus
        Route::post('/contacts/{id}/update-status', [ContactController::class, 'updateStatus'])->name('contacts.update-status');

        Route::get('/contacts/export/csv', [ContactController::class, 'exportCSV'])->name('contacts.export-csv');

        // ✅ NEW: Missing Contact Routes for Dashboard Features
        Route::post('/contacts/{contact}/mark-read', [ContactController::class, 'markAsRead'])->name('contacts.mark-read');
        Route::post('/contacts/{contact}/mark-unread', [ContactController::class, 'markAsUnread'])->name('contacts.mark-unread');
        Route::post('/contacts/bulk-action', [ContactController::class, 'bulkAction'])->name('contacts.bulk-action');

        Route::resource('galleries', GalleryController::class);
        Route::post('/galleries/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])
            ->name('galleries.toggle-status');
        Route::post('/galleries/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
            ->name('galleries.toggle-featured');

        // Meal Routes
        Route::resource('meals', MealController::class);
        // ✅ FIXED: Corrected meal search route to use MealController instead of ContactController
        Route::get('/meals/search', [MealController::class, 'search'])->name('meals.search');

        Route::resource('reviews', AdminReviewController::class);

        // Room Routes
        Route::resource('rooms', RoomController::class);
        Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');
        Route::post('/rooms/{room}/change-status', [RoomController::class, 'changeStatus'])->name('rooms.change-status');
        Route::get('/rooms/export/csv', [RoomController::class, 'exportCSV'])->name('rooms.export-csv');

        // Student Routes
        Route::resource('students', StudentController::class);
        Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
        Route::get('/students/export/csv', [StudentController::class, 'exportCSV'])->name('students.export');

        // ✅ CRITICAL FIX: Added missing bulk operations route for students
        Route::post('/students/bulk-operations', [StudentController::class, 'bulkOperations'])->name('students.bulk-operations');

        // ✅ UPDATED: Hostel Routes with publish/unpublish
        Route::resource('hostels', AdminHostelController::class);
        Route::get('hostels/{hostel}/availability', [AdminHostelController::class, 'showAvailability'])->name('hostels.availability');
        Route::put('hostels/{hostel}/availability', [AdminHostelController::class, 'updateAvailability'])->name('hostels.availability.update');
        Route::get('/hostels/search', [AdminHostelController::class, 'search'])->name('hostels.search');

        // ✅ NEW: Admin Hostel Publish/Unpublish Routes
        Route::post('/hostels/{hostel}/publish', [AdminHostelController::class, 'publish'])->name('hostels.publish');
        Route::post('/hostels/{hostel}/unpublish', [AdminHostelController::class, 'unpublish'])->name('hostels.unpublish');

        // ✅ NOTE: Featured Hostels Routes have been moved to web.php to bypass hasOrganization middleware
        // They are now defined directly in the admin group in web.php

        // Routes for fixing hostel room counts
        Route::get('/hostels/fix-room-counts', [AdminHostelController::class, 'fixRoomCounts'])->name('hostels.fix-room-counts');
        Route::post('/hostels/update-all-counts', [AdminHostelController::class, 'updateAllRoomCounts'])->name('hostels.update-all-counts');

        // ✅ NEW: Bulk operations route for hostels
        Route::post('/hostels/bulk-operations', [AdminHostelController::class, 'bulkOperations'])->name('hostels.bulk-operations');

        // ✅ NEW: Export route for hostels
        Route::get('/hostels/export', [AdminHostelController::class, 'export'])->name('hostels.export');

        // Settings Routes
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

        // Direct settings route for compatibility
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

        // ✅ FIXED: Admin Circular Routes with enhanced functionality
        Route::prefix('circulars')->name('circulars.')->group(function () {
            Route::get('/', [AdminCircularController::class, 'index'])->name('index');
            Route::get('/create', [AdminCircularController::class, 'create'])->name('create');
            Route::post('/', [AdminCircularController::class, 'store'])->name('store');
            Route::get('/{circular}', [AdminCircularController::class, 'show'])->name('show');
            Route::get('/{circular}/edit', [AdminCircularController::class, 'edit'])->name('edit');
            Route::put('/{circular}', [AdminCircularController::class, 'update'])->name('update');
            Route::delete('/{circular}', [AdminCircularController::class, 'destroy'])->name('destroy');

            // Circular publishing and analytics
            Route::post('/{circular}/publish', [AdminCircularController::class, 'publish'])->name('publish');
            Route::get('/analytics', [AdminCircularController::class, 'analytics'])->name('analytics');
            Route::get('/{circular}/analytics', [AdminCircularController::class, 'singleAnalytics'])->name('analytics.single');
            Route::post('/{circular}/mark-read', [AdminCircularController::class, 'markAsRead'])->name('mark-read');

            // ✅ ADDED: Enhanced circular functionality for admin
            Route::get('/{circular}/recipients', [AdminCircularController::class, 'recipients'])->name('recipients');
            Route::post('/{circular}/resend', [AdminCircularController::class, 'resend'])->name('resend');
            Route::post('/{circular}/duplicate', [AdminCircularController::class, 'duplicate'])->name('duplicate');

            // ✅ CRITICAL FIX: Added missing bulk operations route for circulars
            Route::post('/bulk-operations', [AdminCircularController::class, 'bulkOperations'])->name('bulk-operations');

            // Template management
            Route::get('/templates', [AdminCircularController::class, 'templates'])->name('templates');
            Route::post('/templates', [AdminCircularController::class, 'storeTemplate'])->name('templates.store');
            Route::delete('/templates/{template}', [AdminCircularController::class, 'destroyTemplate'])->name('templates.destroy');

            // ✅ ADDED: Global circular sending capability for admin
            Route::get('/global/create', [AdminCircularController::class, 'createGlobal'])->name('global.create');
            Route::post('/global/send', [AdminCircularController::class, 'sendGlobal'])->name('send-global');

            // ✅ ADDED: Circular categories and priority management
            Route::get('/categories', [AdminCircularController::class, 'categories'])->name('categories');
            Route::post('/categories', [AdminCircularController::class, 'storeCategory'])->name('categories.store');
            Route::delete('/categories/{category}', [AdminCircularController::class, 'destroyCategory'])->name('categories.destroy');
        });

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

        // ✅ FIXED: Complete Document Management Routes for Admin
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('index');
            Route::get('/create', [DocumentController::class, 'create'])->name('create');
            Route::post('/', [DocumentController::class, 'store'])->name('store');
            Route::get('/search', [DocumentController::class, 'search'])->name('search');
            Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
            Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
            Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
            Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
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

        // ✅ CRITICAL FIX: Added fallback bulk operations route for other resources
        Route::post('/bulk-operations', [DashboardController::class, 'bulkOperations'])->name('bulk-operations');
    });
