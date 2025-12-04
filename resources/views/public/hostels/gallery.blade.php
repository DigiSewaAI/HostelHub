@extends('layouts.frontend')

@section('page-title', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Available Rooms | HostelHub')

@section('page-header', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Available Rooms')
@section('page-description', 'हाम्रो होस्टलमा उपलब्ध कोठाहरूको विवरण र तस्वीरहरू। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।')

@push('styles')
@vite(['resources/css/gallery.css', 'resources/css/public-themes.css'])
@endpush

@section('content')
@php
    // ✅ FIXED: Use ACTUAL rooms passed from controller
    $rooms = $rooms ?? collect();
    
    // ✅ FIXED: Dynamic counting from actual rooms
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

    // Count items by category for stats
    $categoryCounts = [
        'rooms' => $galleries->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other', 'साझा कोठा'])->count(),
        'kitchen' => $galleries->where('category', 'kitchen')->count(),
        'facilities' => $galleries->whereIn('category', ['bathroom', 'common', 'living room', 'study room'])->count(),
        'video' => $galleries->whereIn('media_type', ['local_video', 'external_video'])->count()
    ];

    // PERMANENT FIX: Nepali room types with proper mapping
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
    
    // ✅ FIXED: Show available rooms section if ANY rooms exist
    $hasRooms = $rooms->count() > 0;

    // ✅ FIXED: Image URL helper that checks storage first
    function getRoomImageUrl($room) {
        // Check if room has image accessor
        if (method_exists($room, 'getImageUrlAttribute') && $room->image_url) {
            return $room->image_url;
        }
        
        // Fallback: Check storage directly
        if ($room->image && \Storage::disk('public')->exists($room->image)) {
            return \Storage::disk('public')->url($room->image);
        }
        
        // Final fallback
        return asset('images/default-room.jpg');
    }

    // ✅ FIXED: Check if room has valid image
    function roomHasImage($room) {
        if (method_exists($room, 'getHasImageAttribute')) {
            return $room->has_image;
        }
        
        return $room->image && \Storage::disk('public')->exists($room->image);
    }
    
    // ✅ ADDED: Check if route exists
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
    
    /* 🚨 UPDATED: Combined Hero Section with Stats */
    .hero-stats-section {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 100px 0 40px; /* Increased top padding for header */
        margin-top: 0;
    }
    
    .hero-main-content {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        line-height: 1.3;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }
    
    /* 🚨 UPDATED: Compact Stats Grid - Better spacing and design */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 10px;
        margin-top: 20px;
    }
    
    .stat-item {
        background: rgba(255, 255, 255, 0.15);
        padding: 12px 8px;
        border-radius: 8px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        height: 85px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        cursor: pointer;
    }

    .stat-item:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .stat-item.active {
        background: rgba(255, 255, 255, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    
    .stat-count {
        font-size: 1.8rem; /* Reduced from 2.2rem */
        font-weight: bold;
        color: white;
        display: block;
        margin-bottom: 5px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        line-height: 1;
    }
    
    .stat-label {
        color: rgba(255,255,255,0.95);
        font-size: 0.9rem; /* Reduced from 1rem */
        font-weight: 600;
        display: block;
        margin-bottom: 3px;
        line-height: 1.2;
    }

    .stat-subtext {
        color: rgba(255,255,255,0.8);
        font-size: 0.75rem; /* Reduced from 0.85rem */
        display: block;
        margin-top: 5px;
        line-height: 1.2;
    }
    
    /* Available Rooms Specific Styles - UPDATED */
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

    /* 🚨 FIXED: Room status badge colors */
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

    /* 🚨 FIXED: No Rooms Message Styles - Button color fixed */
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
    
    /* 🚨 FIXED: Contact button color in no-rooms section */
    .no-rooms-message .btn-outline {
        border-color: var(--primary);
        color: var(--primary) !important; /* Force blue color */
        background: transparent;
    }
    
    .no-rooms-message .btn-outline:hover {
        background: var(--primary);
        color: white !important;
    }
    
    /* 🚨 FIXED: Modal Styles - Compact and better layout */
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
        max-height: 85vh; /* Reduced from 90vh to 85vh */
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
        max-height: 65vh; /* Increased image space */
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
        padding: 20px; /* Reduced padding */
        border-top: 1px solid #e5e7eb;
        max-height: 30vh; /* Limit caption height */
        overflow-y: auto; /* Scroll if content is too long */
    }
    
    .modal-caption h3 {
        color: var(--text-dark);
        margin-bottom: 8px; /* Reduced margin */
        font-size: 1.3rem; /* Slightly smaller */
        font-weight: 600;
    }
    
    .modal-caption p {
        color: var(--text-dark);
        margin-bottom: 12px; /* Reduced margin */
        line-height: 1.4; /* Tighter line height */
        font-size: 0.95rem; /* Slightly smaller font */
    }
    
    .modal-room-details {
        color: var(--text-dark);
        margin-bottom: 15px; /* Reduced margin */
        line-height: 1.5;
        background: #f8f9fa;
        padding: 12px; /* Reduced padding */
        border-radius: 6px;
        border-left: 4px solid var(--primary);
        font-size: 0.9rem; /* Smaller font for details */
    }
    
    .modal-book-button {
        display: block;
        width: 100%;
        padding: 10px 20px; /* Slightly reduced padding */
        background: var(--primary);
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem; /* Slightly smaller */
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
    
    /* 🚨 UPDATED: Room Gallery CTA - STUDENT FOCUSED */
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
    
    /* ✅ UPDATED: Attractive Book Now Button with shining effect */
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

    /* Room Gallery CTA Specific Styles */
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
    
    /* ✅ ADDED: Image error handling styles */
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
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
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
            padding: 80px 0 30px; /* Adjusted for mobile */
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
        }

        .available-rooms-section {
            padding: 50px 0 30px;
        }
        
        /* Modal responsive - More compact on mobile */
        .modal-content {
            width: 95%;
            max-height: 90vh; /* Slightly smaller on mobile */
        }
        
        .modal-image-container {
            max-height: 55vh; /* More space for image on mobile */
        }
        
        .modal-caption {
            padding: 15px; /* Even more compact on mobile */
            max-height: 35vh; /* Allow more space for content on mobile */
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
        
        /* CTA Responsive */
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
        
        /* Room Gallery CTA Responsive */
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
        
        .hero-title {
            font-size: 1.8rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .hero-stats-section {
            padding: 70px 0 20px; /* Adjusted for small mobile */
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        
        .stat-item {
            padding: 10px 6px;
            height: 80px;
        }
        
        .stat-count {
            font-size: 1.5rem;
        }
        
        .stat-label {
            font-size: 0.85rem;
        }
        
        .stat-subtext {
            font-size: 0.7rem;
        }
        
        /* Modal mobile - Ultra compact */
        .modal-content {
            width: 98%;
            max-height: 95vh;
        }
        
        .modal-image-container {
            max-height: 50vh;
        }
        
        .modal-caption {
            padding: 12px;
            max-height: 45vh; /* More space for content on small screens */
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
        
        /* CTA Mobile */
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

<!-- 🚨 UPDATED: Combined Hero Section with Stats -->
<section class="hero-stats-section">
    <div class="container">
        <div class="hero-main-content">
            <h1 class="hero-title nepali">🛏️ हाम्रो होस्टलमा उपलब्ध कोठाहरू</h1>
            <p class="hero-subtitle nepali">
                हाम्रो होस्टलमा उपलब्ध कोठाहरूको विवरण र तस्वीरहरू। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।
            </p>
        </div>
        
        <!-- 🚨 UPDATED: Compact Stats Grid with REAL data -->
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

<!-- Available Rooms Section - ✅ FIXED: Shows ALL ROOMS with PROPER image handling -->
<section class="available-rooms-section">
    <div class="container">
        @if($hasRooms)
            <h2 class="section-title nepali">हाम्रा कोठाहरू</h2>
            <p style="text-align: center; margin-bottom: 40px; color: var(--text-dark); opacity: 0.8; max-width: 700px; margin-left: auto; margin-right: auto;" class="nepali">
                तल दिइएका कोठाहरू हाम्रो होस्टलमा उपलब्ध छन्। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।
            </p>
            
            <!-- ✅ FIXED: Room Gallery with PROPER image handling -->
            <div class="gallery-grid" id="roomsGrid">
                @foreach($rooms as $room)
                    @php
                        // Use room data as is from database
                        $availableBeds = $room->available_beds;
                        $roomId = $room->id;
                        $roomNumber = $room->room_number;
                        $currentOccupancy = $room->current_occupancy;
                        $capacity = $room->capacity;
                        
                        $displayRoomType = $nepaliRoomTypes[$room->type] ?? $room->type;
                        
                        // ✅ CORRECTED: Status logic based on ACTUAL occupancy
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
                        <!-- ✅ STRICT FIX: Image with proper error handling - TIMRO SYSTEM -->
                        @if($room->has_image)
                            <img src="{{ $room->image_url }}" 
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
                        
                        <!-- ✅ FIXED: Show CORRECT status -->
                        <div class="available-badge nepali {{ $statusClass }}">
                            {{ $statusText }}
                        </div>
                        
                        <!-- ✅ FIXED: Book Now button with CORRECT logic -->
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
            
            <!-- ✅ REMOVED: Duplicate buttons above CTA section -->
            
        @else
            <!-- No Rooms Message -->
            <div class="no-rooms-message">
                <div class="no-rooms-icon">🏠</div>
                <h3 class="nepali" style="color: var(--text-dark); margin-bottom: 15px;">हाल कुनै कोठा उपलब्ध छैन</h3>
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

<!-- 🚨 UPDATED: Room Gallery CTA - STUDENT FOCUSED -->
<div class="gallery-cta-wrapper">
    <section class="gallery-cta-section">
        <h2 class="nepali">कोठा बुक गर्न चाहनुहुन्छ?</h2>
        <p class="nepali">अहिले नै कोठा सुरक्षित गर्नुहोस् वा होस्टलको बारेमा थप जानकारी लिनुहोस्</p>
        
        <div class="gallery-cta-buttons-container">
            <!-- ✅ UPDATED: Attractive Book Now Button with shining effect -->
            <a href="{{ route('hostel.book.all.rooms', ['slug' => $hostel->slug]) }}" 
               class="gallery-trial-button nepali">
                <i class="fas fa-calendar-check"></i> अहिले बुक गर्नुहोस्
            </a>
            
            <!-- Gallery बटन -->
            <a href="{{ route('hostel.full-gallery', $hostel->slug) }}" 
               class="gallery-outline-button nepali" style="border-color: white; color: white;">
                <i class="fas fa-images"></i> पूरा ग्यालरी हेर्नुहोस्
            </a>
            
            <!-- 🆕 FIXED: होस्टल मुख्य पृष्ठ बटन - Using hostel.gallery route instead -->
            @if(routeExists('hostels.show'))
                <a href="{{ route('hostels.show', $hostel->slug) }}" 
                   class="gallery-outline-button nepali" style="border-color: white; color: white;">
                    <i class="fas fa-external-link-alt"></i> पृष्ठ भ्रमण गर्नुहोस्
                </a>
            @else
                <!-- Fallback: Use hostel.gallery or direct URL -->
                <a href="/hostel/{{ $hostel->slug }}" 
                   class="gallery-outline-button nepali" style="border-color: white; color: white;">
                    <i class="fas fa-external-link-alt"></i> पृष्ठ भ्रमण गर्नुहोस्
                </a>
            @endif
        </div>
        
       <!-- Contact info -->
<div style="margin-top: 1.5rem; color: rgba(255,255,255,0.8);">
    <p class="nepali" style="margin-bottom: 0.5rem;">
        <i class="fas fa-phone"></i> प्रत्यक्ष कल: {{ $hostel->contact_phone_formatted }}
    </p>
    
    @if($hostel->contact_email_formatted)
    <p class="nepali" style="margin-bottom: 0.5rem;">
        <i class="fas fa-envelope"></i> इमेल: {{ $hostel->contact_email_formatted }}
    </p>
    @endif
    
    <p class="nepali">
        <i class="fas fa-clock"></i> २४/७ सम्पर्क सेवा उपलब्ध
    </p>
</div>
    </section>
</div>

<!-- ✅ FIXED: Room Detail Modal with PROPER image handling -->
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
            <!-- ✅ FIXED: Modal book button with CORRECT route -->
            <a href="#" id="modalBookButton" class="modal-book-button nepali">
                यो कोठा बुक गर्नुहोस्
            </a>
        </div>
    </div>
</div>

<script>
    // ✅ FIXED: Room data with PROPER image handling
    const roomData = {
        @foreach($rooms as $room)
        '{{ $room->id }}': {
            title: `कोठा {{ $room->room_number }}`,
            description: `{{ $room->description ?? 'कोठा विवरण उपलब्ध छैन' }}`,
            media_url: `{{ getRoomImageUrl($room) }}`, // ✅ Use the fixed helper function
            room_type: `{{ $room->type }}`,
            available_beds: {{ $room->available_beds }},
            current_occupancy: {{ $room->current_occupancy }},
            capacity: {{ $room->capacity }},
            room_number: `{{ $room->room_number }}`,
            price: {{ $room->price }},
            status: `{{ $room->status }}`,
            nepali_type: `{{ $nepaliRoomTypes[$room->type] ?? $room->type }}`,
            room_id: `{{ $room->id }}`
        }@if(!$loop->last),@endif
        @endforeach
    };

    // ✅ FIXED: Modal open function with PROPER error handling
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

        // Debug log
        console.log('Room data:', room);
        console.log('Modal elements found:', {modal, modalImage, modalTitle, modalDescription, modalDetails, modalBookButton});

        // Set modal content
        modalImage.src = room.media_url;
        modalImage.alt = room.title;
        modalTitle.textContent = room.title;
        modalDescription.textContent = room.description;
        
        // Room details with Nepali text
        const detailsHtml = `
            <strong>कोठाको प्रकार:</strong> ${room.nepali_type}<br>
            <strong>कोठा नम्बर:</strong> ${room.room_number}<br>
            <strong>क्षमता:</strong> ${room.capacity} बेड<br>
            <strong>अहिलेको बसोबास:</strong> ${room.current_occupancy} जना<br>
            <strong>खाली बेड:</strong> ${room.available_beds} वटा<br>
            <strong>मूल्य:</strong> रु ${room.price.toLocaleString()}/महिना<br>
            <strong>स्थिति:</strong> ${room.available_beds > 0 ? 'उपलब्ध' : 'व्यस्त'}
        `;
        modalDetails.innerHTML = detailsHtml;
        
        // Book button logic
        if (room.available_beds > 0 && room.status !== 'maintenance') {
            modalBookButton.href = "{{ route('hostel.book.from.gallery', ['slug' => $hostel->slug, 'room_id' => '']) }}" + room.room_id;
            modalBookButton.style.display = 'block';
            modalBookButton.textContent = 'यो कोठा बुक गर्नुहोस्';
        } else {
            modalBookButton.style.display = 'none';
        }

        // Show modal with animation
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
        
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }
    
    // ✅ FIXED: Better modal close function
    function closeModal() {
        const modal = document.getElementById('roomModal');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    // ✅ FIXED: Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('roomModal');
        if (event.target === modal) {
            closeModal();
        }
    });
    
    // ✅ FIXED: Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // ✅ ADDED: Room filtering functionality
    function filterRoomsByType(roomType) {
        const allRooms = document.querySelectorAll('.gallery-item');
        
        if (roomType === 'all') {
            // Show all rooms
            allRooms.forEach(room => {
                room.style.display = 'block';
            });
        } else {
            // Show only rooms of selected type
            allRooms.forEach(room => {
                if (room.getAttribute('data-room-type') === roomType) {
                    room.style.display = 'block';
                } else {
                    room.style.display = 'none';
                }
            });
        }
    }

    // ✅ ADDED: Stats filtering functionality
    document.addEventListener('DOMContentLoaded', function() {
        const statItems = document.querySelectorAll('.stat-item');
        
        statItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all
                statItems.forEach(i => i.classList.remove('active'));
                // Add active to clicked
                this.classList.add('active');
                
                const roomType = this.getAttribute('data-room-type');
                filterRoomsByType(roomType);
            });
        });

        // Handle broken images
        const galleryImages = document.querySelectorAll('.gallery-item img');
        galleryImages.forEach(img => {
            img.addEventListener('error', function() {
                this.src = '{{ asset("images/default-room.jpg") }}';
                this.classList.add('image-error');
            });
        });

        const modalImage = document.getElementById('modalRoomImage');
        modalImage.addEventListener('error', function() {
            this.src = '{{ asset("images/default-room.jpg") }}';
        });

        // Debug: Test if buttons are working
        const buttons = document.querySelectorAll('.btn-primary');
        console.log('Total buttons found:', buttons.length);
        
        buttons.forEach((btn, index) => {
            btn.addEventListener('click', function(e) {
                console.log('Button clicked:', index, this.textContent);
            });
        });
    });

    // ✅ FIXED: Using data attributes for view details buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Use event delegation for view details buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('view-details-btn')) {
                const roomId = e.target.getAttribute('data-room-id');
                console.log('View details clicked for room:', roomId);
                openRoomModal(roomId);
            }
        });

        // Also add click handlers for existing buttons with onclick attributes
        const viewDetailButtons = document.querySelectorAll('.gallery-item .btn-primary');
        
        viewDetailButtons.forEach(button => {
            // Only add if it doesn't have the view-details-btn class
            if (!button.classList.contains('view-details-btn')) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Try to extract room ID from onclick attribute
                    const onclickAttr = this.getAttribute('onclick');
                    const roomId = onclickAttr?.match(/'([^']+)'/)?.[1] || 
                                   onclickAttr?.match(/"([^"]+)"/)?.[1];
                    
                    console.log('Legacy button clicked, roomId:', roomId);
                    
                    if (roomId) {
                        openRoomModal(roomId);
                    } else {
                        console.error('Could not find room ID from button:', this);
                        alert('Error: Could not load room details.');
                    }
                });
            }
        });
        
        console.log('View detail buttons initialized:', document.querySelectorAll('.view-details-btn').length);
    });
</script>
@endsection