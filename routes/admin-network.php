<?php
// routes/admin-network.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminNetworkDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminBroadcastController;
use App\Http\Controllers\Admin\AdminMarketplaceController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminReportController;

Route::prefix('network')->name('network.')->group(function () {
    // Dashboard
    Route::get('/', [AdminNetworkDashboardController::class, 'index'])->name('dashboard');

    // Profiles
    Route::resource('profiles', AdminProfileController::class)->except(['create', 'store', 'edit', 'update'])->parameters(['profiles' => 'profile']);
    Route::post('profiles/{profile}/trust', [AdminProfileController::class, 'updateTrust'])->name('profiles.trust');
    Route::delete('profiles/{profile}/remove', [AdminProfileController::class, 'removeFromNetwork'])->name('profiles.remove');

    // Broadcasts
    Route::resource('broadcasts', AdminBroadcastController::class)->only(['index', 'show']);
    Route::post('broadcasts/{broadcast}/approve', [AdminBroadcastController::class, 'approve'])->name('broadcasts.approve');
    Route::post('broadcasts/{broadcast}/reject', [AdminBroadcastController::class, 'reject'])->name('broadcasts.reject');

    // Marketplace
    Route::get('marketplace', [AdminMarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('marketplace/{listing}', [AdminMarketplaceController::class, 'show'])->name('marketplace.show');
    Route::get('marketplace/{listing}/edit', [AdminMarketplaceController::class, 'edit'])->name('marketplace.edit');
    Route::put('marketplace/{listing}', [AdminMarketplaceController::class, 'update'])->name('marketplace.update');
    Route::post('marketplace/{listing}/approve', [AdminMarketplaceController::class, 'approve'])->name('marketplace.approve');
    Route::post('marketplace/{listing}/reject', [AdminMarketplaceController::class, 'reject'])->name('marketplace.reject');
    Route::delete('marketplace/{listing}', [AdminMarketplaceController::class, 'destroy'])->name('marketplace.destroy');  // ✅ थपियो
    Route::post('marketplace/bulk-action', [AdminMarketplaceController::class, 'bulkAction'])->name('marketplace.bulk-action');  // ✅ थपियो

    Route::resource('messages', AdminMessageController::class)->only(['index', 'show']);
    // थपियो: म्यासेज थ्रेड ब्लक/हटाउने रुट
    Route::delete('messages/{thread}/block', [AdminMessageController::class, 'blockSender'])->name('messages.block');

    // Reports
    Route::resource('reports', AdminReportController::class)->only(['index', 'show']);
    Route::post('reports/{report}/review', [AdminReportController::class, 'markReviewed'])->name('reports.review');
    Route::post('reports/{report}/dismiss', [AdminReportController::class, 'dismiss'])->name('reports.dismiss');
});
