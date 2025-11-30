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
    Route::get('/gallery', [FrontendGalleryController::class, 'index'])->name('gallery');
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

    // ✅ FIXED: Hostel listing routes - SEPARATE ROUTES FOR DIFFERENT NAMES
    Route::get('/hostels', [PublicController::class, 'allHostels'])->name('all.hostels');
    Route::get('/hostels-list', [PublicController::class, 'allHostels'])->name('hostels.index');

    Route::get('/hostels/{slug}', [PublicController::class, 'hostelShow'])->name('hostels.show');

    // ✅ FIXED: Single search route for hostel search functionality (GET method)
    Route::get('/search', [PublicController::class, 'search'])->name('search');

    // Public hostel gallery routes
    Route::get('/hostel/{slug}/gallery', [PublicController::class, 'hostelGallery'])->name('hostel.gallery');
    Route::get('/hostel/{slug}/full-gallery', [PublicController::class, 'hostelFullGallery'])->name('hostel.full-gallery');

    // Meal gallery route
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

    // Gallery API Routes
    Route::get('/api/gallery/data', [FrontendGalleryController::class, 'getGalleryData']);
    Route::get('/api/gallery/categories', [FrontendGalleryController::class, 'getGalleryCategories']);
    Route::get('/api/gallery/stats', [FrontendGalleryController::class, 'getGalleryStats']);

    // Public testimonials
    Route::get('/reviews', [FrontendReviewController::class, 'index'])->name('reviews');
    Route::get('/testimonials', [FrontendReviewController::class, 'index'])->name('testimonials');

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
});

/*|--------------------------------------------------------------------------
| Organization Registration Routes (Accessible by all)
|--------------------------------------------------------------------------*/
Route::get('/register/organization/{plan?}', [RegistrationController::class, 'show'])->name('register.organization');
Route::post('/register/organization', [RegistrationController::class, 'store'])->name('register.organization.store');

/*|--------------------------------------------------------------------------
| Clean File - No Duplicate Routes
|--------------------------------------------------------------------------*/
