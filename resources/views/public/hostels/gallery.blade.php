@extends('layouts.frontend')

@section('page-title', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Available Rooms Gallery | HostelHub')
@section('page-header', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Available Rooms Gallery')
@section('page-description', ($hostel->name ?? 'होस्टल') . ' मा उपलब्ध कोठाहरूको विवरण र तस्वीरहरू। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।')

@push('styles')
@vite(['resources/css/gallery.css', 'resources/css/public-themes.css'])
@endpush

@section('content')

<!-- ✅ NEW: Temporary helper function for Railway -->
@php
function railway_media_url($path) {
    if (!$path) return asset('images/no-image.png');
    
    // Remove storage/ or public/ prefixes
    $path = str_replace(['storage/', 'public/'], '', $path);
    
    // For Railway - direct path
    return '/media/' . ltrim($path, '/');
}
@endphp

@php
    $rooms = $rooms ?? collect();
    $availableRoomCounts = [];
    $availableBedsCounts = [];

    foreach ($rooms as $room) {
        $type = $room->type;
        
        if (!isset($availableRoomCounts[$type])) {
            $availableRoomCounts[$type] = 0;
            $availableBedsCounts[$type] = 0;
        }
        
        $availableRoomCounts[$type]++;
        $availableBedsCounts[$type] += $room->available_beds;
    }

    $categoryCounts = [
        'rooms' => $galleries->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other', 'साझा कोठा'])->count(),
        'kitchen' => $galleries->where('category', 'kitchen')->count(),
        'facilities' => $galleries->whereIn('category', ['bathroom', 'common', 'living room', 'study room'])->count(),
        'video' => $galleries->whereIn('media_type', ['local_video', 'external_video'])->count()
    ];

    $nepaliRoomTypes = [
        '1 seater' => '१ सिटर',
        '2 seater' => '२ सिटर', 
        '3 seater' => '३ सिटर',
        '4 seater' => '४ सिटर',
        'other' => 'साझा कोठा',
        'साझा कोठा' => 'साझा कोठा',
        'single' => '१ सिटर',
        'double' => '२ सिटर',
        'triple' => '३ सिटर', 
        'quad' => '४ सिटर',
        'shared' => 'साझा कोठा'
    ];
    
    $hasRooms = $rooms->count() > 0;

    // Hostel image for HERO section background
    $hostelBgImage = asset('images/default-hostel-bg.jpg');
    
    // Try to get hostel's main image
    if ($hostel->image) {
        $hostelBgImage = railway_media_url($hostel->image);
    }
    // Try from hostel images
    elseif (isset($hostel->images) && $hostel->images->count() > 0) {
        foreach ($hostel->images as $img) {
            if ($img->file_path) {
                $hostelBgImage = railway_media_url($img->file_path);
                break;
            }
        }
    }
    // Try from gallery images
    elseif (isset($galleries) && $galleries->count() > 0) {
        foreach ($galleries as $gallery) {
            if ($gallery->file_path) {
                $hostelBgImage = railway_media_url($gallery->file_path);
                break;
            }
        }
    }

    function getRoomImageUrl($room) {
        if (method_exists($room, 'getImageUrlAttribute') && $room->image_url) {
            return railway_media_url($room->image_url);
        }
        
        if ($room->image) {
            return railway_media_url($room->image);
        }
        
        return asset('images/default-room.jpg');
    }

    function roomHasImage($room) {
        if (method_exists($room, 'getHasImageAttribute')) {
            return $room->has_image;
        }
        
        return $room->image && \Storage::disk('public')->exists($room->image);
    }
    
    function routeExists($name) {
        try {
            return \Route::has($name);
        } catch (\Exception $e) {
            return false;
        }
    }
@endphp

<style>
    /* Gallery Specific Styles */
    .gallery-section {
        padding: 80px 0 60px;
        margin-top: 0 !important;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.2rem;
        color: var(--text-dark);
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--secondary);
    }
    
    .gallery-filters {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 40px;
    }
    
    .filter-btn {
        padding: 10px 24px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        color: var(--text-dark);
    }
    
    .filter-btn.active, .filter-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 50px;
    }
    
    .gallery-item {
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 280px;
        background: var(--light-bg);
    }
    
    .gallery-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .gallery-item img, .gallery-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .gallery-item:hover img, .gallery-item:hover video {
        transform: scale(1.05);
    }
    
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        color: white;
        padding: 25px 20px;
        transform: translateY(100%);
        transition: transform 0.3s;
    }
    
    .gallery-item:hover .gallery-overlay {
        transform: translateY(0);
    }
    
    .gallery-title {
        font-size: 1.3rem;
        margin-bottom: 8px;
        font-weight: 600;
    }
    
    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--accent);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }

    .book-now-btn {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: var(--primary);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        z-index: 3;
        transition: background 0.3s;
    }

    .book-now-btn:hover {
        background: var(--secondary);
        color: white;
    }
    
    .view-more {
        text-align: center;
        margin-top: 40px;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    /* 🚨 UPDATED: HERO SECTION - FIXED STATS GRID */
    .hero-stats-section {
        color: white;
        padding: 100px 0 40px;
        margin-top: 0;
        position: relative;
        min-height: 450px;
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: #1a1a2e;
    }
    
    .hero-stats-section .container {
        position: relative;
        z-index: 2;
    }
    
    .hero-main-content {
        text-align: center;
        margin-bottom: 40px;
        padding: 0 20px;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7);
        line-height: 1.3;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        font-weight: 500;
    }
    
    /* ✅ FIXED: 6 Stats in One Line */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(130px, 1fr));
        gap: 12px;
        margin-top: 30px;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
        padding: 0 10px;
    }
    
    .stat-item {
        background: rgba(255, 255, 255, 0.2);
        padding: 16px 8px;
        border-radius: 10px;
        transition: all 0.3s ease;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.25);
        height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        min-width: 0;
        overflow: hidden;
    }

    .stat-item:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .stat-item.active {
        background: rgba(255, 255, 255, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
    }
    
    .stat-count {
        font-size: 1.8rem;
        font-weight: bold;
        color: white;
        display: block;
        margin-bottom: 6px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        line-height: 1;
    }
    
    .stat-label {
        color: rgba(255,255,255,0.95);
        font-size: 0.85rem;
        font-weight: 700;
        display: block;
        margin-bottom: 4px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat-subtext {
        color: rgba(255,255,255,0.85);
        font-size: 0.7rem;
        display: block;
        margin-top: 3px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Available Rooms Specific Styles */
    .available-rooms-section {
        padding: 60px 0 40px;
        background: var(--bg-light);
    }
    
    .room-type-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }
    
    .available-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #10b981;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }
    
    .full-gallery-cta {
        text-align: center;
        margin-top: 60px;
        padding: 40px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .status-available {
        background: #10b981 !important;
    }
    .status-occupied {
        background: #ef4444 !important;
    }
    .status-partially_available {
        background: #f59e0b !important;
    }
    .status-maintenance {
        background: #6b7280 !important;
    }

    .no-rooms-message {
        text-align: center;
        padding: 80px 20px;
        background: #f8f9fa;
        border-radius: 15px;
        margin: 40px 0;
    }
    
    .no-rooms-icon {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 20px;
    }
    
    .no-rooms-message .btn-outline {
        border-color: var(--primary);
        color: var(--primary) !important;
        background: transparent;
    }
    
    .no-rooms-message .btn-outline:hover {
        background: var(--primary);
        color: white !important;
    }
    
    .gallery-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 1100;
        justify-content: center;
        align-items: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-content {
        max-width: 800px;
        max-height: 85vh;
        width: 90%;
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
    }
    
    .modal-image-container {
        flex: 1;
        background: #000;
        display: flex;
        justify-content: center;
        align-items: center;
        max-height: 65vh;
        min-height: 50vh;
        overflow: hidden;
    }
    
    .modal-image-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .close-modal {
        position: absolute;
        top: 15px;
        right: 15px;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.7);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        transition: background 0.3s;
        border: none;
    }
    
    .close-modal:hover {
        background: rgba(0, 0, 0, 0.9);
    }
    
    .modal-caption {
        background: white;
        color: #333;
        padding: 20px;
        border-top: 1px solid #e5e7eb;
        max-height: 30vh;
        overflow-y: auto;
    }
    
    .modal-caption h3 {
        color: var(--text-dark);
        margin-bottom: 8px;
        font-size: 1.3rem;
        font-weight: 600;
    }
    
    .modal-caption p {
        color: var(--text-dark);
        margin-bottom: 12px;
        line-height: 1.4;
        font-size: 0.95rem;
    }
    
    .modal-room-details {
        color: var(--text-dark);
        margin-bottom: 15px;
        line-height: 1.5;
        background: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
        border-left: 4px solid var(--primary);
        font-size: 0.9rem;
    }
    
    .modal-book-button {
        display: block;
        width: 100%;
        padding: 10px 20px;
        background: var(--primary);
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        margin-top: 10px;
    }
    
    .modal-book-button:hover {
        background: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    /* 🚨 UPDATED: Room Gallery CTA - CLEAN */
    .gallery-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 2rem 1.5rem 8rem 1.5rem;
        margin-top: 4rem;
        background: transparent !important;
        position: relative;
        z-index: 100;
    }
    
    .gallery-cta-section {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 3rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        z-index: 101;
    }
    
    .gallery-cta-section h2 {
        font-size: 1.875rem;
        font-weight: bold;
        margin-bottom: 1rem;
        color: white;
    }
    
    .gallery-cta-section p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    
    .gallery-contact-email {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 20px 0;
        display: block;
        color: #ffffff;
        text-decoration: underline;
    }
    
    /* ✅ UPDATED: Attractive Book Now Button */
    .gallery-trial-button {
        background: linear-gradient(135deg, #FF6B35, #FF8B3D) !important;
        color: white !important;
        font-weight: 700;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        text-decoration: none;
        min-width: 200px;
        box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
        transition: all 0.4s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .gallery-trial-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.7s ease;
        z-index: -1;
    }
    
    .gallery-trial-button:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(255, 107, 53, 0.6);
        color: white !important;
    }
    
    .gallery-trial-button:hover::before {
        left: 100%;
    }
    
    .gallery-trial-button:active {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(255, 107, 53, 0.5);
    }
    
    .gallery-trial-button i {
        font-size: 1.2rem;
        filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
    }
    
    .gallery-trial-button:disabled {
        background: #6c757d !important;
        color: white !important;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3) !important;
    }

    .gallery-trial-button:disabled:hover {
        background: #6c757d !important;
        color: white !important;
        transform: none !important;
        box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3) !important;
    }

    .gallery-cta-buttons-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
        margin-top: 1.5rem;
        width: 100%;
    }

    .gallery-cta-section .gallery-outline-button {
        background-color: transparent;
        color: white;
        border: 2px solid white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    .gallery-cta-section .gallery-outline-button:hover {
        background-color: white;
        color: var(--primary);
        transform: translateY(-2px);
    }
    
    .image-fallback {
        width: 100%;
        height: 100%;
        background: var(--light-bg);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-dark);
        opacity: 0.7;
    }
    
    .image-fallback i {
        font-size: 3rem;
        margin-bottom: 10px;
        opacity: 0.5;
    }
    
    .gallery-item img.image-error {
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Responsive Design for Stats */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, minmax(140px, 1fr));
            max-width: 800px;
        }
    }
    
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .gallery-item {
            height: 240px;
        }
        
        .gallery-section {
            padding: 60px 0 40px;
        }
        
        .hero-stats-section {
            padding: 80px 0 30px;
            min-height: 450px;
        }
        
        .hero-title {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .view-more {
            flex-direction: column;
            align-items: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            max-width: 500px;
        }
        
        .stat-item {
            height: 95px;
            padding: 14px 8px;
        }
        
        .stat-count {
            font-size: 1.6rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
        }
        
        .stat-subtext {
            font-size: 0.65rem;
        }

        .available-rooms-section {
            padding: 50px 0 30px;
        }
        
        .modal-content {
            width: 95%;
            max-height: 90vh;
        }
        
        .modal-image-container {
            max-height: 55vh;
        }
        
        .modal-caption {
            padding: 15px;
            max-height: 35vh;
        }
        
        .modal-caption h3 {
            font-size: 1.2rem;
            margin-bottom: 6px;
        }
        
        .modal-caption p {
            font-size: 0.9rem;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .modal-room-details {
            padding: 10px;
            font-size: 0.85rem;
            margin-bottom: 12px;
        }
        
        .modal-book-button {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        
        .gallery-cta-wrapper {
            padding: 2rem 1rem 6rem 1rem;
        }
        
        .gallery-cta-section {
            padding: 2.5rem 1.5rem;
        }
        
        .gallery-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .gallery-cta-section p {
            font-size: 1.125rem;
        }
        
        .gallery-contact-email {
            font-size: 1.1rem;
        }
        
        .gallery-trial-button {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            min-width: 180px;
        }
        
        .gallery-cta-buttons-container {
            flex-direction: column;
            gap: 1rem;
        }
        
        .gallery-cta-section .gallery-trial-button,
        .gallery-cta-section .gallery-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 180px;
            width: 100%;
            max-width: 250px;
        }
    }
    
    @media (max-width: 480px) {
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .gallery-item {
            height: 220px;
        }
        
        .gallery-filters {
            gap: 8px;
        }
        
        .filter-btn {
            padding: 8px 12px;
            font-size: 0.8rem;
        }
        
        .hero-stats-section {
            padding: 70px 0 20px;
            min-height: 400px;
        }
        
        .hero-title {
            font-size: 1.6rem;
        }
        
        .hero-subtitle {
            font-size: 0.95rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 8px;
            max-width: 300px;
        }
        
        .stat-item {
            padding: 10px 6px;
            height: 85px;
        }
        
        .stat-count {
            font-size: 1.5rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
        }
        
        .stat-subtext {
            font-size: 0.7rem;
        }
        
        .modal-content {
            width: 98%;
            max-height: 95vh;
        }
        
        .modal-image-container {
            max-height: 50vh;
        }
        
        .modal-caption {
            padding: 12px;
            max-height: 45vh;
        }
        
        .modal-caption h3 {
            font-size: 1.1rem;
        }
        
        .modal-caption p {
            font-size: 0.85rem;
        }
        
        .modal-room-details {
            font-size: 0.8rem;
            padding: 8px;
        }
        
        .modal-book-button {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
        
        .gallery-cta-wrapper {
            padding: 1.5rem 1rem 5rem 1rem;
        }
        
        .gallery-cta-section {
            padding: 2rem 1rem;
        }
        
        .gallery-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .gallery-cta-section p {
            font-size: 1rem;
        }
        
        .gallery-contact-email {
            font-size: 1rem;
        }
        
        .gallery-trial-button {
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            min-width: 160px;
        }
    }
</style>

<!-- 🚨 UPDATED: HERO SECTION - LIGHT OVERLAY -->
<section class="hero-stats-section" 
         style="background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.4)), 
                url('{{ $hostelBgImage }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;">
    <div class="container">
        <div class="hero-main-content">
            <!-- ✅ FIXED: होस्टलको नाम केवल TOP मा (1/4) -->
            <h1 class="hero-title nepali">🛏️ {{ $hostel->name ?? 'होस्टल' }} मा उपलब्ध कोठाहरू</h1>
            <p class="hero-subtitle nepali">
                यस होस्टलमा उपलब्ध कोठाहरूको विवरण र तस्वीरहरू। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।
            </p>
        </div>
        
        <!-- ✅ FIXED: 6 Stats in One Line -->
        <div class="stats-grid">
            @php
                $totalAvailableBeds = $rooms->sum('available_beds');
            @endphp
            <!-- All rooms stat -->
            <div class="stat-item active" data-room-type="all">
                <span class="stat-count">{{ $rooms->count() }}</span>
                <span class="stat-label nepali">सबै कोठाहरू</span>
                <span class="stat-subtext nepali">({{ $totalAvailableBeds }} बेड खाली)</span>
            </div>

            @foreach($availableRoomCounts as $englishType => $roomCount)
                @if($roomCount > 0)
                    <div class="stat-item" data-room-type="{{ $englishType }}">
                        <span class="stat-count">{{ $roomCount }}</span>
                        <span class="stat-label nepali">{{ $nepaliRoomTypes[$englishType] ?? $englishType }}</span>
                        @if(isset($availableBedsCounts[$englishType]) && $availableBedsCounts[$englishType] > 0)
                            <span class="stat-subtext nepali">({{ $availableBedsCounts[$englishType] }} बेड खाली)</span>
                        @else
                            <span class="stat-subtext nepali">(कुनै बेड खाली छैन)</span>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<!-- Available Rooms Section -->
<section class="available-rooms-section">
    <div class="container">
        @if($hasRooms)
            <!-- ✅ FIXED: होस्टलको नाम सेक्सन टाइटलमा (2/4) -->
            <h2 class="section-title nepali">{{ $hostel->name ?? 'होस्टल' }} का कोठाहरू</h2>
            <p style="text-align: center; margin-bottom: 40px; color: var(--text-dark); opacity: 0.8; max-width: 700px; margin-left: auto; margin-right: auto;" class="nepali">
                तल दिइएका कोठाहरू यस होस्टलमा उपलब्ध छन्। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।
            </p>
            
            <div class="gallery-grid" id="roomsGrid">
                @foreach($rooms as $room)
                    @php
                        $availableBeds = $room->available_beds;
                        $roomId = $room->id;
                        $roomNumber = $room->room_number;
                        $currentOccupancy = $room->current_occupancy;
                        $capacity = $room->capacity;
                        
                        $displayRoomType = $nepaliRoomTypes[$room->type] ?? $room->type;
                        
                        if ($room->status === 'maintenance') {
                            $statusClass = 'status-maintenance';
                            $statusText = 'मर्मतमा';
                            $showAvailableBeds = false;
                        } elseif ($currentOccupancy >= $capacity) {
                            $statusClass = 'status-occupied';
                            $statusText = 'व्यस्त';
                            $showAvailableBeds = false;
                        } elseif ($currentOccupancy > 0) {
                            $statusClass = 'status-partially_available';
                            $statusText = $availableBeds . ' बेड खाली';
                            $showAvailableBeds = true;
                        } else {
                            $statusClass = 'status-available';
                            $statusText = $availableBeds . ' बेड खाली';
                            $showAvailableBeds = true;
                        }
                    @endphp
                    
                    <div class="gallery-item" data-room-type="{{ $room->type }}">
                        @if($room->has_image)
                            <img src="{{ getRoomImageUrl($room) }}" 
     alt="कोठा {{ $room->room_number }}" 
     loading="lazy"
     onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.classList.add('image-error');">

                        @else
                            <div class="image-fallback">
                                <i class="fas fa-bed"></i>
                                <span class="nepali">कोठा {{ $room->room_number }}</span>
                            </div>
                        @endif
                        
                        <div class="room-type-badge nepali">
                            {{ $displayRoomType }}
                        </div>
                        
                        <div class="available-badge nepali {{ $statusClass }}">
                            {{ $statusText }}
                        </div>
                        
                        @if($availableBeds > 0 && $room->status !== 'maintenance' && $currentOccupancy < $capacity)
                            <a href="{{ route('hostel.book.from.gallery', ['slug' => $hostel->slug, 'room_id' => $roomId]) }}" class="book-now-btn nepali">
                                बुक गर्नुहोस्
                            </a>
                        @else
                            <button class="book-now-btn nepali" style="background: #6c757d; cursor: not-allowed;" disabled>
                                {{ $statusText }}
                            </button>
                        @endif
                        
                        <div class="gallery-overlay">
                            <!-- ✅ FIXED: होस्टलको नाम छोटो रूपमा (3/4) -->
                            <p class="nepali" style="font-size: 0.8rem; color: rgba(255,255,255,0.8); margin-bottom: 5px;">
                                {{ $hostel->name ?? 'होस्टल' }}
                            </p>
                            <h3 class="gallery-title nepali">कोठा {{ $roomNumber }}</h3>
                            <p class="nepali">प्रकार: {{ $displayRoomType }}</p>
                            <p class="nepali" style="font-size: 0.9rem; margin-top: 5px;">
                                कोठा: {{ $roomNumber }} | क्षमता: {{ $capacity }} | अहिले: {{ $currentOccupancy }} जना
                            </p>
                            <p class="nepali" style="font-size: 0.9rem; margin-top: 5px;">
                                मूल्य: रु {{ number_format($room->price, 2) }}/महिना
                            </p>
                            <button class="btn btn-primary view-details-btn" 
                                    style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" 
                                    data-room-id="{{ $room->id }}">
                                विस्तृत हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- 🚨 NEW: खानाको ग्यालरी बटन सेक्सन (रूम इमेजहरूको अन्त्यमा) -->
            <div style="text-align: center; margin: 4rem 0 2rem 0;">
                <a href="{{ route('hostel.full-gallery', $hostel->slug) }}#meal-gallery" 
                   class="gallery-trial-button nepali" 
                   style="background: linear-gradient(135deg, #10b981, #34d399) !important; min-width: 280px;">
                    <i class="fas fa-utensils"></i> यस होस्टलको खानाको ग्यालरी हेर्नुहोस्
                </a>
            </div>
            
        @else
            <!-- No Rooms Message -->
            <div class="no-rooms-message">
                <div class="no-rooms-icon">🏠</div>
                <h3 class="nepali" style="color: var(--text-dark); margin-bottom: 15px;">यस होस्टलमा हाल कुनै कोठा उपलब्ध छैन</h3>
                <p class="nepali" style="color: var(--text-dark); opacity: 0.8; margin-bottom: 25px;">
                    माफ गर्नुहोस्, हाल यस होस्टलमा कुनै कोठा उपलब्ध छैन।<br>
                    कृपया पछि फेरी जाँच गर्नुहोस् वा हाम्रो अन्य होस्टलहरू हेर्नुहोस्।
                </p>
                <div class="view-more">
                    <a href="{{ route('hostels.index') }}" class="btn btn-primary nepali">
                        अन्य होस्टलहरू हेर्नुहोस्
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline nepali">
                        सम्पर्क गर्नुहोस्
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- 🚨 UPDATED: Room Gallery CTA - CLEAN -->
<div class="gallery-cta-wrapper">
    <section class="gallery-cta-section">
        <!-- ✅ FIXED: होस्टलको नाम CTA मा छैन -->
        <h2 class="nepali">यस होस्टलमा कोठा बुक गर्न चाहनुहुन्छ?</h2>
        <p class="nepali">अहिले नै कोठा सुरक्षित गर्नुहोस् वा थप जानकारी लिनुहोस्</p>
        
        <div class="gallery-cta-buttons-container">
            <!-- ✅ FIXED: Buttons मा होस्टलको नाम छैन -->
            <a href="{{ route('hostel.book.all.rooms', ['slug' => $hostel->slug]) }}" 
               class="gallery-trial-button nepali">
                <i class="fas fa-calendar-check"></i> बुक गर्नुहोस्
            </a>
            
            <a href="{{ route('hostel.full-gallery', $hostel->slug) }}" 
               class="gallery-outline-button nepali">
                <i class="fas fa-images"></i> पूरा ग्यालरी हेर्नुहोस्
            </a>
            
            @if(routeExists('hostels.show'))
                <a href="{{ route('hostels.show', $hostel->slug) }}" 
                   class="gallery-outline-button nepali">
                    <i class="fas fa-external-link-alt"></i> पृष्ठ भ्रमण गर्नुहोस्
                </a>
            @else
                <a href="/hostel/{{ $hostel->slug }}" 
                   class="gallery-outline-button nepali">
                    <i class="fas fa-external-link-alt"></i> पृष्ठ भ्रमण गर्नुहोस्
                </a>
            @endif
        </div>
        
        <!-- Contact info -->
        <div style="margin-top: 1.5rem; color: rgba(255,255,255,0.8);">
            <!-- ✅ FIXED: होस्टलको नाम contact info मा (4/4) -->
            <p class="nepali" style="margin-bottom: 0.5rem; font-weight: 600;">
                <i class="fas fa-building"></i> {{ $hostel->name ?? 'होस्टल' }}
            </p>
            
            <p class="nepali" style="margin-bottom: 0.5rem;">
                <i class="fas fa-phone"></i> प्रत्यक्ष कल: {{ $hostel->contact_phone ?? 'उपलब्ध छैन' }}
            </p>
            
            @if($hostel->contact_email)
            <p class="nepali" style="margin-bottom: 0.5rem;">
                <i class="fas fa-envelope"></i> इमेल: {{ $hostel->contact_email }}
            </p>
            @endif
            
            <p class="nepali">
                <i class="fas fa-clock"></i> २४/७ सम्पर्क सेवा उपलब्ध
            </p>
        </div>
    </section>
</div>

<!-- ✅ FIXED: Room Detail Modal -->
<div class="gallery-modal" id="roomModal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeModal()">&times;</button>
        <div class="modal-image-container">
            <img id="modalRoomImage" src="" alt="" onerror="this.onerror=null; this.src='{{ asset('images/default-room.jpg') }}';">
        </div>
        <div class="modal-caption">
            <h3 id="modalRoomTitle" class="nepali"></h3>
            <p id="modalRoomDescription" class="nepali"></p>
            <div id="modalRoomDetails" class="modal-room-details nepali"></div>
            <a href="#" id="modalBookButton" class="modal-book-button nepali">
                यो कोठा बुक गर्नुहोस्
            </a>
        </div>
    </div>
</div>

<script>
    // ✅ FIXED: Page Load Complete Function
    (function() {
        // Mark page as interactive immediately
        document.body.classList.add('page-interactive');
        
        // Track loaded images
        let imagesLoaded = 0;
        const totalImages = document.querySelectorAll('img').length;
        
        // Function to mark page as fully loaded
        function markPageAsFullyLoaded() {
            console.log('✅ Page fully loaded');
            document.body.classList.add('page-loaded');
            document.body.classList.remove('page-loading');
            
            // Dispatch event for any listeners
            window.dispatchEvent(new Event('pageFullyLoaded'));
        }
        
        // Handle image loading
        document.querySelectorAll('img').forEach(img => {
            if (img.complete) {
                imagesLoaded++;
            } else {
                img.addEventListener('load', function() {
                    imagesLoaded++;
                    if (imagesLoaded >= totalImages - 1) { // -1 for safety
                        markPageAsFullyLoaded();
                    }
                });
                
                img.addEventListener('error', function() {
                    imagesLoaded++; // Count error as loaded
                    if (imagesLoaded >= totalImages - 1) {
                        markPageAsFullyLoaded();
                    }
                });
            }
        });
        
        // Fallback: mark page loaded after 3 seconds max
        setTimeout(markPageAsFullyLoaded, 3000);
        
        // If no images, mark loaded immediately
        if (totalImages === 0) {
            setTimeout(markPageAsFullyLoaded, 500);
        }
    })();

    // ✅ FIXED: Room data
    const roomData = {
        @foreach($rooms as $room)
        '{{ $room->id }}': {
            title: `कोठा {{ $room->room_number }}`,
            description: `{{ $room->description ?? 'कोठा विवरण उपलब्ध छैन' }}`,
            media_url: `{{ getRoomImageUrl($room) }}`,
            room_type: `{{ $room->type }}`,
            available_beds: {{ $room->available_beds }},
            current_occupancy: {{ $room->current_occupancy }},
            capacity: {{ $room->capacity }},
            room_number: `{{ $room->room_number }}`,
            price: {{ $room->price }},
            status: `{{ $room->status }}`,
            nepali_type: `{{ $nepaliRoomTypes[$room->type] ?? $room->type }}`,
            room_id: `{{ $room->id }}`,
            hostel_name: `{{ $hostel->name ?? 'होस्टल' }}`
        }@if(!$loop->last),@endif
        @endforeach
    };

    function openRoomModal(roomId) {
        console.log('Opening modal for room ID:', roomId);
        
        const room = roomData[roomId];
        if (!room) {
            console.error('Room data not found for ID:', roomId);
            alert('Room data not found!');
            return;
        }

        const modal = document.getElementById('roomModal');
        const modalImage = document.getElementById('modalRoomImage');
        const modalTitle = document.getElementById('modalRoomTitle');
        const modalDescription = document.getElementById('modalRoomDescription');
        const modalDetails = document.getElementById('modalRoomDetails');
        const modalBookButton = document.getElementById('modalBookButton');

        modalImage.src = room.media_url;
        modalImage.alt = room.title;
        modalTitle.textContent = room.hostel_name + ' - ' + room.title;
        modalDescription.textContent = room.description;
        
        const detailsHtml = `
            <strong>होस्टल:</strong> ${room.hostel_name}<br>
            <strong>कोठाको प्रकार:</strong> ${room.nepali_type}<br>
            <strong>कोठा नम्बर:</strong> ${room.room_number}<br>
            <strong>क्षमता:</strong> ${room.capacity} बेड<br>
            <strong>अहिलेको बसोबास:</strong> ${room.current_occupancy} जना<br>
            <strong>खाली बेड:</strong> ${room.available_beds} वटा<br>
            <strong>मूल्य:</strong> रु ${room.price.toLocaleString()}/महिना<br>
            <strong>स्थिति:</strong> ${room.available_beds > 0 ? 'उपलब्ध' : 'व्यस्त'}
        `;
        modalDetails.innerHTML = detailsHtml;
        
        if (room.available_beds > 0 && room.status !== 'maintenance') {
            modalBookButton.href = "{{ route('hostel.book.from.gallery', ['slug' => $hostel->slug, 'room_id' => '']) }}" + room.room_id;
            modalBookButton.style.display = 'block';
            modalBookButton.textContent = 'यो कोठा बुक गर्नुहोस्';
        } else {
            modalBookButton.style.display = 'none';
        }

        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        const modal = document.getElementById('roomModal');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('roomModal');
        if (event.target === modal) {
            closeModal();
        }
    });
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    function filterRoomsByType(roomType) {
        const allRooms = document.querySelectorAll('.gallery-item');
        
        if (roomType === 'all') {
            allRooms.forEach(room => {
                room.style.display = 'block';
            });
        } else {
            allRooms.forEach(room => {
                if (room.getAttribute('data-room-type') === roomType) {
                    room.style.display = 'block';
                } else {
                    room.style.display = 'none';
                }
            });
        }
    }

    // Initialize after page is loaded
    window.addEventListener('pageFullyLoaded', function() {
        console.log('Initializing gallery functionality...');
        
        const statItems = document.querySelectorAll('.stat-item');
        
        statItems.forEach(item => {
            item.addEventListener('click', function() {
                statItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                
                const roomType = this.getAttribute('data-room-type');
                filterRoomsByType(roomType);
            });
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('view-details-btn')) {
                const roomId = e.target.getAttribute('data-room-id');
                openRoomModal(roomId);
            }
        });

        // Handle image errors
        const galleryImages = document.querySelectorAll('.gallery-item img');
        galleryImages.forEach(img => {
            img.addEventListener('error', function() {
                this.src = '{{ asset("images/default-room.jpg") }}';
                this.classList.add('image-error');
            });
        });

        const modalImage = document.getElementById('modalRoomImage');
        if (modalImage) {
            modalImage.addEventListener('error', function() {
                this.src = '{{ asset("images/default-room.jpg") }}';
            });
        }
        
        console.log('Gallery functionality initialized');
    });
    
    // Fallback initialization
    document.addEventListener('DOMContentLoaded', function() {
        // If pageFullyLoaded hasn't fired in 2 seconds, initialize anyway
        setTimeout(function() {
            if (!document.body.classList.contains('page-loaded')) {
                console.log('Fallback initialization');
                window.dispatchEvent(new Event('pageFullyLoaded'));
            }
        }, 2000);
    });
</script>
@endsection