@extends('layouts.frontend')

@section('page-title', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Available Rooms | HostelHub')

@section('page-header', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Available Rooms')
@section('page-description', 'हाम्रो होस्टलमा उपलब्ध कोठाहरूको विवरण र तस्वीरहरू। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।')

@push('styles')
@vite(['resources/css/gallery.css', 'resources/css/public-themes.css'])
@endpush

@section('content')
@php
    // Get gallery items from database - NOW WITH PROPER ROOM MAPPING
    $galleries = $galleries ?? collect();
    
    // 🚨 FIXED: Use the galleries that already have proper room mapping from controller
    $availableRoomGalleries = $galleries->where('media_type', 'photo');
    
    // 🚨 FIXED: Use the ACTUAL available rooms passed from controller
    $availableRooms = $availableRooms ?? collect();
    
    // Count items by category for stats
    $categoryCounts = [
        'rooms' => $availableRoomGalleries->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other', 'साझा कोठा'])->count(),
        'kitchen' => $galleries->where('category', 'kitchen')->count(),
        'facilities' => $galleries->whereIn('category', ['bathroom', 'common', 'living room', 'study room'])->count(),
        'video' => $galleries->whereIn('media_type', ['local_video', 'external_video'])->count()
    ];

    // 🚨 FIXED: Calculate counts from ACTUAL available rooms data
    $availableRoomCounts = [
        '1 seater' => $availableRooms->where('type', '1 seater')->count(),
        '2 seater' => $availableRooms->where('type', '2 seater')->count(),
        '3 seater' => $availableRooms->where('type', '3 seater')->count(),
        '4 seater' => $availableRooms->where('type', '4 seater')->count(),
        'other' => $availableRooms->whereNotIn('type', ['1 seater', '2 seater', '3 seater', '4 seater'])->count(),
    ];

    // 🚨 FIXED: Calculate ACTUAL available beds for each room type
    $availableBedsCounts = [
        '1 seater' => $availableRooms->where('type', '1 seater')->sum('actual_available_beds'),
        '2 seater' => $availableRooms->where('type', '2 seater')->sum('actual_available_beds'),
        '3 seater' => $availableRooms->where('type', '3 seater')->sum('actual_available_beds'),
        '4 seater' => $availableRooms->where('type', '4 seater')->sum('actual_available_beds'),
        'other' => $availableRooms->whereNotIn('type', ['1 seater', '2 seater', '3 seater', '4 seater'])->sum('actual_available_beds'),
    ];

    // PERMANENT FIX: Nepali room types with proper mapping
    $nepaliRoomTypes = [
        '1 seater' => '१ सिटर',
        '2 seater' => '२ सिटर', 
        '3 seater' => '३ सिटर',
        '4 seater' => '४ सिटर',
        'other' => 'साझा कोठा',
        'साझा कोठा' => 'साझा कोठा'
    ];
    
    // 🚨 FIXED: Updated condition to show available rooms section
    $totalAvailableRooms = array_sum($availableRoomCounts);
    $hasAvailableRooms = $totalAvailableRooms > 0 || $availableRoomGalleries->count() > 0;

    // 🚨 FIXED: Create mapping of gallery to actual room data for display
    $galleryRoomData = [];
    foreach ($availableRoomGalleries as $gallery) {
        // Use the room data that was already mapped in the controller
        $galleryRoomData[$gallery->id] = [
            'available_beds' => $gallery->actual_available_beds ?? 0,
            'room_id' => $gallery->room_id ?? null,
            'room_number' => $gallery->room_number ?? '',
            'current_occupancy' => $gallery->current_occupancy ?? 0,
            'capacity' => $gallery->capacity ?? 0
        ];
    }
@endphp

<style>
    /* 🚨 REMOVED: Duplicate header protection - Header will show normally now */
    
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
    
    /* 🚨 UPDATED: Stats Grid - Better spacing and design */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-top: 30px;
    }
    
    .stat-item {
        background: rgba(255, 255, 255, 0.15);
        padding: 25px 15px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .stat-count {
        font-size: 2.2rem;
        font-weight: bold;
        color: white;
        display: block;
        margin-bottom: 8px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .stat-label {
        color: rgba(255,255,255,0.95);
        font-size: 1rem;
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }

    .stat-subtext {
        color: rgba(255,255,255,0.8);
        font-size: 0.85rem;
        display: block;
        margin-top: 8px;
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
    
    /* 🚨 UPDATED: CTA SECTION - EXACTLY LIKE ABOUT PAGE */
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
    
    .gallery-trial-button {
        background-color: white;
        color: #001F5B;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-block;
        font-size: 1rem;
        text-align: center;
    }
    
    .gallery-trial-button:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: #001F5B;
    }
    
    .gallery-trial-button:disabled {
        background: #6c757d;
        color: white;
        cursor: not-allowed;
        transform: none;
    }

    .gallery-trial-button:disabled:hover {
        background: #6c757d;
        color: white;
        transform: none;
    }

    .gallery-cta-buttons-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
        margin-top: 1.5rem;
        width: 100%;
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
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
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
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
            gap: 12px;
        }
        
        .stat-item {
            padding: 20px 15px;
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
    }
</style>

<!-- 🚨 UPDATED: Combined Hero Section with Stats (Removed single card, integrated stats) -->
<section class="hero-stats-section">
    <div class="container">
        <div class="hero-main-content">
            <h1 class="hero-title nepali">🛏️ हाम्रो होस्टलमा उपलब्ध कोठाहरू</h1>
            <p class="hero-subtitle nepali">
                हाम्रो होस्टलमा उपलब्ध कोठाहरूको विवरण र तस्वीरहरू। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।
            </p>
        </div>
        
        <!-- 🚨 UPDATED: Stats Grid with better design -->
        <div class="stats-grid">
            @foreach($nepaliRoomTypes as $englishType => $nepaliType)
            <div class="stat-item">
                <span class="stat-count">{{ $availableRoomCounts[$englishType] ?? 0 }}</span>
                <span class="stat-label nepali">{{ $nepaliType }}</span>
                @if(isset($availableBedsCounts[$englishType]) && $availableBedsCounts[$englishType] > 0)
                    <span class="stat-subtext nepali">({{ $availableBedsCounts[$englishType] }} बेड खाली)</span>
                @else
                    <span class="stat-subtext nepali">(कुनै बेड खाली छैन)</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Available Rooms Section - UPDATED (SHOWS ALL ROOM IMAGES) -->
<section class="available-rooms-section">
    <div class="container">
        @if($hasAvailableRooms)
            <h2 class="section-title nepali">हाम्रा कोठाहरू</h2>
            <p style="text-align: center; margin-bottom: 40px; color: var(--text-dark); opacity: 0.8; max-width: 700px; margin-left: auto; margin-right: auto;" class="nepali">
                तल दिइएका कोठाहरू हाम्रो होस्टलमा उपलब्ध छन्। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।
            </p>
            
            <!-- Available Rooms Gallery - INDIVIDUAL ROOM DATA -->
<div class="gallery-grid">
    @foreach($availableRoomGalleries as $gallery)
        @php
            $roomCategory = $gallery->category;
            $roomData = $galleryRoomData[$gallery->id] ?? null;
            $availableBeds = $roomData['available_beds'] ?? 0;
            $roomId = $roomData['room_id'] ?? null;
            $roomNumber = $roomData['room_number'] ?? '';
            $currentOccupancy = $roomData['current_occupancy'] ?? 0;
            $capacity = $roomData['capacity'] ?? 0;
            
            // 🚨 FIXED: Handle both English and Nepali room types
            $displayRoomType = $nepaliRoomTypes[$roomCategory] ?? $roomCategory;
        @endphp
        
        <div class="gallery-item">
            <img src="{{ $gallery->thumbnail_url ?? $gallery->media_url }}" 
                 alt="{{ $gallery->title }}" 
                 onerror="this.src='{{ asset('images/default-room.jpg') }}'">
            
            <div class="room-type-badge nepali">
                {{ $displayRoomType }}
            </div>
            
            <!-- 🚨 FIXED: Show ACTUAL available beds for THIS specific room -->
            <div class="available-badge nepali">
                @if($availableBeds > 0)
                    {{ $availableBeds }} बेड खाली
                @else
                    उपलब्ध छैन
                @endif
            </div>
            
            <!-- 🚨 FIXED: Book Now button with CORRECT room data -->
            @if($roomId && $availableBeds > 0)
                <a href="{{ route('hostel.book.from.gallery', ['slug' => $hostel->slug, 'room_id' => $roomId]) }}" class="book-now-btn nepali">
                    बुक गर्नुहोस्
                </a>
            @else
                <button class="book-now-btn nepali" style="background: #6c757d; cursor: not-allowed;" disabled>
                    उपलब्ध छैन
                </button>
            @endif
            
            <div class="gallery-overlay">
                <h3 class="gallery-title nepali">{{ $gallery->title }}</h3>
                <p class="nepali">{{ $gallery->description }}</p>
                <p class="nepali" style="font-size: 0.9rem; margin-top: 5px;">
                    कोठा: {{ $roomNumber }} | क्षमता: {{ $capacity }} | अहिले: {{ $currentOccupancy }} जना
                </p>
                <button class="btn btn-primary" 
                        style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" 
                        onclick="openRoomModal('{{ $gallery->id }}')">
                    विस्तृत हेर्नुहोस्
                </button>
            </div>
        </div>
    @endforeach
</div>
            
            <!-- Navigation Buttons -->
            <div class="view-more">
                <a href="{{ route('hostel.full-gallery', $hostel->slug) }}" class="btn btn-outline nepali" 
                   style="border-color: var(--primary); color: var(--primary);">
                    पूरा ग्यालरी हेर्नुहोस्
                </a>
                <!-- 🚨 FIXED: Big booking button uses ALL ROOMS route -->
                <a href="{{ route('hostel.book.all.rooms', ['slug' => $hostel->slug]) }}" class="btn btn-primary nepali">
                    अहिले बुक गर्नुहोस्
                </a>
            </div>
            
        @else
            <!-- 🚨 FIXED: No Available Rooms Message - Button color fixed -->
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

<!-- 🚨 UPDATED: CTA SECTION - EXACTLY LIKE ABOUT PAGE -->
<div class="gallery-cta-wrapper">
    <section class="gallery-cta-section">
        <h2 class="nepali">हामीलाई सम्पर्क गर्नुहोस्</h2>
        <p class="nepali">हामी तपाईंलाई सहयोग गर्न तत्पर छौं</p>
        <a href="mailto:support@hostelhub.com" class="gallery-contact-email nepali">support@hostelhub.com</a>
        <div class="gallery-cta-buttons-container">
            @auth
                @php
                    $organizationId = session('current_organization_id');
                    $hasSubscription = false;
                    
                    if ($organizationId) {
                        try {
                            $organization = \App\Models\Organization::with('subscription')->find($organizationId);
                            $hasSubscription = $organization->subscription ?? false;
                        } catch (Exception $e) {
                            $hasSubscription = false;
                        }
                    }
                @endphp
                
                @if($hasSubscription)
                    <button class="gallery-trial-button nepali" disabled>
                        तपाईंसँग पहिले नै सदस्यता छ
                    </button>
                @else
                    <form action="{{ route('subscription.start-trial') }}" method="POST" class="trial-form" style="display: inline;">
                        @csrf
                        <button type="submit" class="gallery-trial-button nepali">७ दिन निःशुल्क परीक्षण सुरु गर्नुहोस्</button>
                    </form>
                @endif
            @else
                <a href="{{ route('register.organization', ['plan' => 'starter']) }}" class="gallery-trial-button nepali">७ दिन निःशुल्क परीक्षण सुरु गर्नुहोस्</a>
            @endauth
        </div>
    </section>
</div>

<!-- 🚨 FIXED: Room Detail Modal - Compact and better layout -->
<div class="gallery-modal" id="roomModal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeModal()">&times;</button>
        <div class="modal-image-container">
            <img id="modalRoomImage" src="" alt="">
        </div>
        <div class="modal-caption">
            <h3 id="modalRoomTitle" class="nepali"></h3>
            <p id="modalRoomDescription" class="nepali"></p>
            <div id="modalRoomDetails" class="modal-room-details nepali"></div>
            <!-- 🚨 FIXED: Modal book button with CORRECT route -->
            <a href="#" id="modalBookButton" class="modal-book-button nepali">
                यो कोठा बुक गर्नुहोस्
            </a>
        </div>
    </div>
</div>

<script>
    // Room gallery data - FIXED: Use ACTUAL room data
const roomGalleryData = {
    @foreach($availableRoomGalleries as $gallery)
    '{{ $gallery->id }}': {
        title: `{{ addslashes($gallery->title) }}`,
        description: `{{ addslashes($gallery->description) }}`,
        media_url: `{{ $gallery->media_url }}`,
        room_type: `{{ $gallery->category }}`,
        available_count: {{ $galleryRoomData[$gallery->id]['available_beds'] ?? 0 }},
        available_beds: {{ $galleryRoomData[$gallery->id]['available_beds'] ?? 0 }},
        current_occupancy: {{ $galleryRoomData[$gallery->id]['current_occupancy'] ?? 0 }},
        capacity: {{ $galleryRoomData[$gallery->id]['capacity'] ?? 0 }},
        room_number: `{{ $galleryRoomData[$gallery->id]['room_number'] ?? '' }}`,
        nepali_type: `{{ $nepaliRoomTypes[$gallery->category] ?? $gallery->category }}`,
        room_id: `{{ $galleryRoomData[$gallery->id]['room_id'] ?? '' }}`
    }@if(!$loop->last),@endif
    @endforeach
};

    // FIXED: Modal open function with better error handling
    function openRoomModal(galleryId) {
        console.log('Opening modal for gallery ID:', galleryId); // Debug log
        
        const room = roomGalleryData[galleryId];
        if (!room) {
            console.error('Room data not found for ID:', galleryId);
            return;
        }

        const modal = document.getElementById('roomModal');
        const modalImage = document.getElementById('modalRoomImage');
        const modalTitle = document.getElementById('modalRoomTitle');
        const modalDescription = document.getElementById('modalRoomDescription');
        const modalDetails = document.getElementById('modalRoomDetails');
        const modalBookButton = document.getElementById('modalBookButton');

        // Set modal content
        modalImage.src = room.media_url;
        modalImage.alt = room.title;
        modalTitle.textContent = room.title;
        modalDescription.textContent = room.description;
        
        // Room details with Nepali text - FIXED: Show individual room data
const detailsHtml = `
    <strong>कोठाको प्रकार:</strong> ${room.nepali_type}<br>
    <strong>कोठा नम्बर:</strong> ${room.room_number}<br>
    <strong>क्षमता:</strong> ${room.capacity} बेड<br>
    <strong>अहिलेको बसोबास:</strong> ${room.current_occupancy} जना<br>
    <strong>खाली बेड:</strong> ${room.available_beds} वटा
`;
        modalDetails.innerHTML = detailsHtml;
        
        // 🚨 FIXED: Book button link with CORRECT route
        if (room.room_id) {
            modalBookButton.href = "{{ route('hostel.book.from.gallery', ['slug' => $hostel->slug, 'room_id' => '']) }}" + room.room_id;
        } else {
            // 🚨 FIXED: Use ALL ROOMS booking when no specific room
            modalBookButton.href = "{{ route('hostel.book.all.rooms', ['slug' => $hostel->slug]) }}";
        }

        // Show modal with animation
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
        
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }
    
    // FIXED: Better modal close function
    function closeModal() {
        const modal = document.getElementById('roomModal');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    // FIXED: Close modal when clicking outside the content
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('roomModal');
        const modalContent = document.querySelector('.modal-content');
        
        if (event.target === modal) {
            closeModal();
        }
    });
    
    // FIXED: Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // FIXED: Add error handling for images
    document.addEventListener('DOMContentLoaded', function() {
        // Handle broken images in gallery
        const galleryImages = document.querySelectorAll('.gallery-item img');
        galleryImages.forEach(img => {
            img.addEventListener('error', function() {
                this.src = '{{ asset("images/default-room.jpg") }}';
            });
        });

        // Handle broken images in modal
        const modalImage = document.getElementById('modalRoomImage');
        modalImage.addEventListener('error', function() {
            this.src = '{{ asset("images/default-room.jpg") }}';
        });

        // Simple gallery item hover effect
        const galleryItems = document.querySelectorAll('.gallery-item');
        
        galleryItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection