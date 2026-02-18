<?php

use App\Http\Controllers\{
    Frontend\GalleryController as FrontendGalleryController,
    Frontend\PublicContactController,
    Frontend\PublicController,
    Frontend\PricingController,
    Frontend\MealGalleryController,
    Frontend\ReviewController as FrontendReviewController,
    Owner\OwnerPublicPageController,
    BookingController,
    RegistrationController,
    WelcomeController
};

/*|--------------------------------------------------------------------------
| Public Routes (Marketing Site - Homepage, Features, Pricing, etc.)
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'web'], function () {
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/about', [PublicController::class, 'about'])->name('about');
    Route::get('/features', [PublicController::class, 'features'])->name('features');
    Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');

    /*|--------------------------------------------------------------------------
    | ✅ MAIN GALLERY SYSTEM ROUTES - SIMPLE AND WORKING
    |--------------------------------------------------------------------------*/

    // Main gallery page with tabs - 100% WORKING ROUTE
    Route::get('/gallery', [App\Http\Controllers\Frontend\GalleryController::class, 'index'])->name('gallery.index');

    // Gallery with specific tab
    Route::get('/gallery/{tab}', [App\Http\Controllers\Frontend\GalleryController::class, 'index'])
        ->where('tab', 'photos|videos|virtual-tours')
        ->name('gallery.tab');

    // Gallery filtering AJAX endpoint
    Route::post('/gallery/filter', [App\Http\Controllers\Frontend\GalleryController::class, 'filteredGalleries'])->name('gallery.filter');

    // Individual hostel gallery
    Route::get('/gallery/hostel/{slug}', [App\Http\Controllers\Frontend\GalleryController::class, 'hostelGallery'])->name('gallery.hostel');

    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

    // ✅ FIXED: Hostel listing routes - SEPARATE ROUTES FOR DIFFERENT NAMES
    Route::get('/hostels', [PublicController::class, 'allHostels'])->name('all.hostels');
    Route::get('/hostels-list', [PublicController::class, 'allHostels'])->name('hostels.index');

    Route::get('/hostels/{slug}', [PublicController::class, 'hostelShow'])->name('hostels.show');

    // ✅ FIXED: Single search route for hostel search functionality (GET method)
    Route::get('/search', [PublicController::class, 'search'])->name('search');

    /*|--------------------------------------------------------------------------
    | ✅ HOSTEL SPECIFIC GALLERY ROUTES - For hostel public pages
    |--------------------------------------------------------------------------*/

    // Hostel's public page gallery section (lightbox/slider)
    Route::get('/hostel/{slug}/gallery', [PublicController::class, 'hostelGallery'])->name('hostel.gallery');

    // Hostel's individual room gallery page
    Route::get('/hostel/{slug}/room/{roomId}/gallery', [PublicController::class, 'hostelRoomGallery'])->name('hostel.room.gallery');

    // Hostel's individual full gallery page
    Route::get('/hostel/{slug}/full-gallery', [PublicController::class, 'hostelFullGallery'])->name('hostel.full-gallery');

    // Hostel's video gallery
    Route::get('/hostel/{slug}/videos', [PublicController::class, 'hostelVideos'])->name('hostel.videos');

    // ✅ Meal gallery route
    Route::get('/menu-gallery', [MealGalleryController::class, 'index'])->name('menu-gallery');

    // ✅ FIXED: Booking routes for new booking system
    // Guest booking routes - NEW SYSTEM
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // ✅ FIXED: Booking success route with controller method (NEW SYSTEM)
    Route::get('/booking/success/{id}', [PublicController::class, 'bookingSuccess'])->name('booking.success');

    // Old booking routes for backward compatibility
    Route::get('/hostel/{slug}/book', [PublicController::class, 'bookForm'])->name('hostel.book');
    Route::post('/hostel/{slug}/book', [PublicController::class, 'storeBooking'])->name('hostel.book.store');

    // ✅ CRITICAL FIX: Method name corrected from 'bookSuccess' to 'bookingSuccess'
    Route::get('/booking-request/success/{bookingRequest}', [PublicController::class, 'bookingSuccess'])->name('hostel.book.success');

    // Keep old route for backward compatibility but redirect to new one
    Route::get('/hostel/{slug}/book-room', function ($slug) {
        return redirect()->route('hostel.book', $slug);
    })->name('hostel.book-room');

    // Hostel contact route
    Route::post('/hostels/{hostel}/contact', [PublicController::class, 'hostelContact'])->name('hostel.contact');

    // Hostel preview route
    Route::get('/preview/{slug}', [OwnerPublicPageController::class, 'preview'])->name('hostels.preview');

    /*|--------------------------------------------------------------------------
    | ✅ GALLERY API ROUTES
    |--------------------------------------------------------------------------*/
    Route::prefix('api/gallery')->group(function () {
        Route::get('/data', [App\Http\Controllers\Frontend\GalleryController::class, 'getGalleryData']);
        Route::get('/categories', [App\Http\Controllers\Frontend\GalleryController::class, 'getCategories']);
        Route::get('/stats', [App\Http\Controllers\Frontend\GalleryController::class, 'getStats']);
        Route::get('/featured', [App\Http\Controllers\Frontend\GalleryController::class, 'getFeaturedGalleries']);
        Route::get('/videos', [App\Http\Controllers\Frontend\GalleryController::class, 'getVideos']);
        Route::get('/{id}/hd', [App\Http\Controllers\Frontend\GalleryController::class, 'getHdImage']);
        Route::get('/hostel/{slug}', [App\Http\Controllers\Frontend\GalleryController::class, 'getHostelGalleryData']);
    });

    // Public testimonials
    // Public testimonials
    Route::get('/testimonials', [PublicController::class, 'testimonials'])->name('testimonials');
    Route::redirect('/reviews', '/testimonials', 301);

    // ✅ Step 2.1: Platform review submission (POST)
    Route::post('/reviews/platform', [App\Http\Controllers\Frontend\ReviewController::class, 'storePlatform'])
        ->name('reviews.platform.store')
        ->middleware('throttle:5,1'); // प्रति IP 5 पटक मात्र


    // Legal pages routes
    Route::get('/privacy-policy', [PublicController::class, 'privacy'])->name('privacy');
    Route::get('/terms-of-service', [PublicController::class, 'terms'])->name('terms');
    Route::get('/cookies', [PublicController::class, 'cookies'])->name('cookies');

    // Contact routes
    Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');
    Route::post('/contact', [PublicContactController::class, 'store'])->name('contact.store');

    // Room search functionality (if needed separately from general search)
    Route::get('/rooms', [PublicController::class, 'roomSearch'])->name('rooms.search');
    Route::get('/hostels/search', [PublicController::class, 'hostelSearch'])->name('hostels.search');

    // Demo route
    Route::get('/demo', function () {
        return view('frontend.pages.demo');
    })->name('demo');

    // Newsletter subscription
    Route::post('/newsletter/subscribe', [PublicController::class, 'subscribeNewsletter'])
        ->name('newsletter.subscribe');

    // ✅ NEW: API Routes for Dynamic Room Loading and Availability Checks
    Route::get('/api/hostel/{slug}/rooms', [PublicController::class, 'getHostelRooms'])->name('api.hostel.rooms');

    // ✅ NEW: Booking Controller API Routes for Room Availability
    Route::get('/api/hostel/{hostel}/available-rooms', [BookingController::class, 'getAvailableRooms'])
        ->name('hostel.available-rooms');
    Route::get('/api/room/{room}/check-availability', [BookingController::class, 'checkRoomAvailability'])
        ->name('room.check-availability');

    // ✅ NEW: Guest booking success page route
    Route::get('/booking/guest-success/{id}', [BookingController::class, 'guestBookingSuccess'])
        ->name('booking.guest-success');

    // ✅ NEW: Convert guest booking to student booking
    Route::post('/booking/{id}/convert-to-student', [BookingController::class, 'convertToStudentBooking'])
        ->name('booking.convert-to-student');

    // ✅ NEW: Homepage gallery section API
    Route::get('/api/homepage/gallery', [PublicController::class, 'getHomepageGallery'])->name('api.homepage.gallery');

    // ✅ NEW: Download gallery item
    Route::get('/gallery/{id}/download', [App\Http\Controllers\Frontend\GalleryController::class, 'download'])->name('gallery.download');

    // ✅ NEW: Share gallery item
    Route::get('/gallery/{id}/share', [App\Http\Controllers\Frontend\GalleryController::class, 'share'])->name('gallery.share');
});

/*|--------------------------------------------------------------------------
| Organization Registration Routes (Accessible by all)
|--------------------------------------------------------------------------*/
Route::get('/register/organization/{plan?}', [RegistrationController::class, 'show'])->name('register.organization');
Route::post('/register/organization', [RegistrationController::class, 'store'])->name('register.organization.store');

/*|--------------------------------------------------------------------------
| Clean File - No Duplicate Routes
|--------------------------------------------------------------------------*/