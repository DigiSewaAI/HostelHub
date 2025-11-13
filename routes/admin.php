<?php

use App\Http\Controllers\Admin\{
    ContactController,
    DashboardController,
    GalleryController,
    HostelController as AdminHostelController,
    MealController,
    MealMenuController as AdminMealMenuController,
    ReportController,
    ReviewController as AdminReviewController,
    RoomController,
    StudentController,
    SettingsController,
    PaymentController as AdminPaymentController,
    CircularController as AdminCircularController
};
use App\Http\Controllers\DocumentController;

/*|--------------------------------------------------------------------------
| Admin Routes - ONLY for admin role
|--------------------------------------------------------------------------
*/

// ✅ REMOVE the prefix from here - prefix is now in web.php
Route::middleware(['auth', 'hasOrganization', 'role:admin'])
    ->name('admin.')  // ✅ KEEP only the name prefix
    ->group(function () {

        // Admin Dashboard with cache management
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::post('/dashboard/clear-cache', [DashboardController::class, 'clearCache'])->name('dashboard.clear-cache');

        // ✅ NEW: Contact counts API route for real-time notifications
        Route::get('/dashboard/contacts-count', [DashboardController::class, 'getContactCounts'])->name('dashboard.contacts-count');

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
        Route::get('/meals/search', [ContactController::class, 'search'])->name('meals.search');

        Route::resource('reviews', AdminReviewController::class);

        // Room Routes
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

        // Circular Routes
        Route::resource('circulars', AdminCircularController::class);
        Route::post('/circulars/{circular}/publish', [AdminCircularController::class, 'publish'])->name('circulars.publish');
        Route::get('/circulars/analytics', [AdminCircularController::class, 'analytics'])->name('circulars.analytics');
        Route::get('/circulars/{circular}/analytics', [AdminCircularController::class, 'analytics'])->name('circulars.analytics.single');
        Route::post('/circulars/{circular}/mark-read', [AdminCircularController::class, 'markAsRead'])->name('circulars.mark-read');
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
    });
