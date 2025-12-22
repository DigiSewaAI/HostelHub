@extends('layouts.frontend')

@section('page-title', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Complete Gallery | HostelHub')

@section('page-header', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Complete Gallery')
@section('page-description', 'हाम्रो होस्टलको सम्पूर्ण सुविधाहरू, कोठाहरू, भिडियो टुर र खानाको मेनुको पूर्ण दृश्यात्मक अनुभव')

@section('content')

@php
    // PERMANENT FIX: Nepali room types
    $nepaliRoomTypes = [
        '1 seater' => '१ सिटर',
        '2 seater' => '२ सिटर', 
        '3 seater' => '३ सिटर',
        '4 seater' => '४ सिटर',
        'other' => 'अन्य (५++ सिटर)'
    ];

    // Get all active gallery items
    $galleries = $hostel->galleries ?? collect();
    $activeGalleries = $galleries->where('is_active', true);
    
    // Count items by category for stats
    $categoryCounts = [
        'rooms' => $activeGalleries->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other'])->count(),
        'kitchen' => $activeGalleries->where('category', 'kitchen')->count(),
        'facilities' => $activeGalleries->whereIn('category', ['bathroom', 'common', 'living room', 'study room'])->count(),
        'video' => $activeGalleries->whereIn('media_type', ['local_video', 'external_video'])->count(),
        'meal' => $mealMenus->count() ?? 0
    ];
    
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
@endphp

<style>
    /* 🚨 CRITICAL: Remove duplicate header protection */
    .page-header {
        display: none !important;
    }
    
    /* 🚨 UPDATED: HERO SECTION - LIKE AVAILABLE ROOMS GALLERY */
    .hero-gallery-section {
        color: white;
        padding: 100px 0 60px;
        margin-top: 0;
        position: relative;
        min-height: 500px;
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: #1a1a2e;
    }
    
    .hero-gallery-section .container {
        position: relative;
        z-index: 2;
    }
    
    .hero-main-content {
        text-align: center;
        margin-bottom: 40px;
        padding: 0 20px;
    }
    
    .hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7);
        line-height: 1.3;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        font-weight: 500;
    }
    
    /* ✅ FIXED: Gallery Stats Grid - 5 Stats in One Line */
    .gallery-stats-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(140px, 1fr));
        gap: 15px;
        margin-top: 30px;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
        padding: 0 10px;
    }
    
    .gallery-stat-item {
        background: rgba(255, 255, 255, 0.2);
        padding: 16px 10px;
        border-radius: 10px;
        transition: all 0.3s ease;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.25);
        height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        min-width: 0;
        overflow: hidden;
    }

    .gallery-stat-item:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .gallery-stat-item.active {
        background: rgba(255, 255, 255, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
    }
    
    .gallery-stat-count {
        font-size: 2rem;
        font-weight: bold;
        color: white;
        display: block;
        margin-bottom: 8px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        line-height: 1;
    }
    
    .gallery-stat-label {
        color: rgba(255,255,255,0.95);
        font-size: 0.9rem;
        font-weight: 700;
        display: block;
        margin-bottom: 5px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gallery-stat-subtext {
        color: rgba(255,255,255,0.85);
        font-size: 0.75rem;
        display: block;
        margin-top: 4px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Gallery Section */
    .gallery-section {
        padding: 60px 0 40px;
        background: var(--bg-light);
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
    
    .section-description {
        text-align: center;
        margin-bottom: 40px;
        color: var(--text-dark);
        opacity: 0.8;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    /* Gallery Tabs - IMPROVED DESIGN */
    .gallery-tabs-container {
        background: white;
        padding: 30px 0;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
    }
    
    .gallery-tabs {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 0 20px;
    }
    
    .tab-btn {
        padding: 12px 28px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .tab-btn.active, .tab-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .tab-btn i {
        font-size: 1.1rem;
    }
    
    .tab-content {
        display: none;
        animation: fadeIn 0.5s ease-in;
    }
    
    .tab-content.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Gallery Filters - MODERN DESIGN */
    .gallery-filters-container {
        background: #f8f9fa;
        padding: 25px 30px;
        border-radius: 12px;
        margin-bottom: 40px;
        border: 1px solid #e9ecef;
    }
    
    .gallery-filters {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .filter-btn {
        padding: 10px 22px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        color: var(--text-dark);
        font-size: 0.95rem;
    }
    
    .filter-btn.active, .filter-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    /* Gallery Grid - IMPROVED DESIGN */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 300px;
        background: white;
    }
    
    .gallery-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
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
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
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
    
    .gallery-description {
        font-size: 0.95rem;
        line-height: 1.5;
        opacity: 0.9;
        margin-bottom: 15px;
    }
    
    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #FF6B35, #FF8B3D);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }
    
    .category-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }
    
    .view-more {
        text-align: center;
        margin-top: 40px;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    /* Meal Gallery Styles - IMPROVED */
    .meal-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .meal-item-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .meal-item-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .meal-image {
        position: relative;
        height: 220px;
        overflow: hidden;
        cursor: pointer;
    }
    
    .meal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .meal-item-card:hover .meal-image img {
        transform: scale(1.05);
    }
    
    .meal-type-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        z-index: 3;
    }
    
    .meal-content {
        padding: 25px;
    }
    
    .meal-content h4 {
        font-size: 1.4rem;
        margin-bottom: 10px;
        color: var(--text-dark);
        font-weight: 700;
    }
    
    .meal-day {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .meal-items {
        color: var(--text-dark);
        margin-bottom: 20px;
        line-height: 1.6;
        font-size: 1rem;
    }
    
    .meal-time {
        color: var(--text-dark);
        font-size: 1rem;
        padding-top: 15px;
        border-top: 1px solid var(--border);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .meal-time i {
        color: var(--primary);
    }
    
    .no-meals, .no-content {
        text-align: center;
        padding: 60px 20px;
        grid-column: 1 / -1;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    
    .no-content-icon {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .no-content h3 {
        color: var(--text-dark);
        margin-bottom: 15px;
        font-size: 1.5rem;
    }
    
    .no-content p {
        color: var(--text-dark);
        opacity: 0.8;
        margin-bottom: 25px;
        font-size: 1.1rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }
    
    /* Modal Styles - IMPROVED */
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
    
    .gallery-modal.active {
        display: flex;
        opacity: 1;
    }
    
    .modal-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: black;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }
    
    .modal-content img, .modal-content video {
        width: 100%;
        height: auto;
        max-height: 80vh;
        display: block;
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
        width: 50px;
        height: 50px;
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
        transform: scale(1.1);
    }
    
    .modal-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.85);
        color: white;
        padding: 20px;
        transform: translateY(0);
        transition: transform 0.3s;
        max-height: 40%;
        overflow-y: auto;
        z-index: 5;
    }
    
    .modal-caption.hidden {
        transform: translateY(100%);
        opacity: 0;
        pointer-events: none;
    }
    
    .modal-caption h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .modal-caption p {
        font-size: 1rem;
        line-height: 1.5;
        opacity: 0.9;
        margin-bottom: 10px;
    }

    /* Hidden items for view more functionality */
    .gallery-item.hidden-item {
        display: none;
    }

    .view-more-items .gallery-item {
        display: block !important;
    }
    
    /* 🎥 VIDEO MODAL PLAY BUTTON STYLING */
    .video-play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 5;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .video-play-overlay.hidden {
        opacity: 0;
        pointer-events: none;
    }

    .video-play-button {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #FF6B35, #FF8B3D);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 0 10px 30px rgba(255, 107, 53, 0.5);
        transition: all 0.3s ease;
        border: 3px solid white;
    }

    .video-play-button:hover {
        transform: scale(1.1);
        box-shadow: 0 15px 40px rgba(255, 107, 53, 0.7);
    }

    .video-play-button i {
        color: white;
        font-size: 2rem;
        margin-left: 5px;
    }

    /* VIDEO MODAL CONTROLS */
    .video-controls {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
        padding: 15px 20px;
        z-index: 6;
        opacity: 1;
        transition: opacity 0.5s ease;
    }

    .video-controls.hidden {
        opacity: 0;
        pointer-events: none;
    }

    .video-controls.hover-visible {
        opacity: 1;
    }

    .video-progress-bar {
        width: 100%;
        height: 4px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
        margin-bottom: 12px;
        overflow: hidden;
    }

    .video-progress-fill {
        height: 100%;
        background: var(--primary);
        width: 0%;
        transition: width 0.1s linear;
    }

    .video-control-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .video-control-left, .video-control-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .video-control-btn {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.1rem;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    .video-control-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .video-time {
        color: white;
        font-size: 0.85rem;
        font-family: monospace;
    }

    /* AUTO-HIDE CONTROLS FOR MODALS */
    .gallery-modal:hover .video-controls:not(.hidden) {
        opacity: 1;
    }
    
    .gallery-modal:hover .modal-caption:not(.hidden) {
        opacity: 1;
        transform: translateY(0);
    }

    /* Show caption on modal hover */
    .modal-caption.hover-show {
        opacity: 0;
        transform: translateY(100%);
        transition: opacity 0.3s, transform 0.3s;
    }
    
    .gallery-modal:hover .modal-caption.hover-show {
        opacity: 1;
        transform: translateY(0);
    }

    /* RESPONSIVE VIDEO CONTROLS */
    @media (max-width: 768px) {
        .video-play-button {
            width: 70px;
            height: 70px;
        }
        
        .video-play-button i {
            font-size: 1.8rem;
        }
        
        .video-controls {
            padding: 12px 15px;
        }
        
        .modal-caption {
            padding: 15px;
            max-height: 50%;
        }
        
        .modal-caption h3 {
            font-size: 1.3rem;
        }
        
        .modal-caption p {
            font-size: 0.9rem;
        }
    }
    
    /* 🚨 UPDATED: CTA SECTION - LIKE AVAILABLE ROOMS GALLERY */
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
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 1rem;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .gallery-cta-section p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.95;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }
    
    /* 🚨 GALLERY CTA BUTTONS - SAME AS ROOM GALLERY */
    .gallery-cta-buttons-container {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        align-items: center;
        margin-top: 2rem;
        margin-bottom: 2rem;
        width: 100%;
    }
    
    /* Orange button for room availability - SAME AS ROOM GALLERY */
    .gallery-trial-button {
        background: linear-gradient(135deg, #FF6B35, #FF8B3D) !important;
        color: white !important;
        font-weight: 700;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        text-decoration: none;
        min-width: 250px;
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
    
    /* Outline buttons - SAME AS ROOM GALLERY */
    .gallery-outline-button {
        background-color: transparent;
        color: white;
        border: 2px solid white;
        font-weight: 600;
        padding: 0.9rem 2.2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 220px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        font-size: 1rem;
    }
    
    .gallery-outline-button:hover {
        background-color: white;
        color: var(--primary);
        transform: translateY(-3px);
    }
    
    /* Contact info styling */
    .gallery-contact-info {
        margin-top: 2rem;
        color: rgba(255, 255, 255, 0.9);
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .gallery-contact-info p {
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        font-size: 1.05rem;
    }
    
    .gallery-contact-info i {
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
        color: rgba(255, 255, 255, 0.8);
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .gallery-stats-grid {
            grid-template-columns: repeat(3, 1fr);
            max-width: 800px;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }
        
        .meal-gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        }
    }
    
    @media (max-width: 992px) {
        .gallery-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            max-width: 600px;
        }
        
        .hero-title {
            font-size: 2.2rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .gallery-item {
            height: 260px;
        }
        
        .hero-gallery-section {
            padding: 80px 0 40px;
            min-height: 450px;
        }
        
        .gallery-section {
            padding: 50px 0 30px;
        }
        
        .view-more {
            flex-direction: column;
            align-items: center;
        }
        
        .hero-title {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .gallery-tabs {
            flex-direction: column;
            align-items: center;
        }
        
        .tab-btn {
            width: 100%;
            max-width: 300px;
            text-align: center;
            justify-content: center;
        }
        
        .meal-gallery-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        
        .gallery-stat-item {
            height: 100px;
            padding: 14px 8px;
        }
        
        .gallery-stat-count {
            font-size: 1.7rem;
        }
        
        /* CTA Responsive */
        .gallery-cta-wrapper {
            padding: 2rem 1rem 6rem 1rem;
        }
        
        .gallery-cta-section {
            padding: 2.5rem 1.5rem;
        }
        
        .gallery-cta-section h2 {
            font-size: 1.6rem;
        }
        
        .gallery-cta-section p {
            font-size: 1.1rem;
        }
        
        /* Mobile responsive for CTA buttons */
        .gallery-cta-buttons-container {
            flex-direction: column;
            gap: 1rem;
        }
        
        .gallery-trial-button,
        .gallery-outline-button {
            padding: 0.85rem 1.8rem;
            font-size: 1rem;
            min-width: 220px;
            width: 100%;
            max-width: 300px;
        }
    }
    
    @media (max-width: 480px) {
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .gallery-item {
            height: 240px;
        }
        
        .gallery-filters {
            gap: 8px;
        }
        
        .filter-btn {
            padding: 8px 16px;
            font-size: 0.85rem;
        }
        
        .hero-gallery-section {
            padding: 70px 0 30px;
            min-height: 400px;
        }
        
        .hero-title {
            font-size: 1.7rem;
        }
        
        .hero-subtitle {
            font-size: 0.95rem;
        }

        .gallery-stats-grid {
            grid-template-columns: 1fr;
            gap: 10px;
            max-width: 300px;
        }
        
        .gallery-stat-item {
            padding: 12px 8px;
            height: 90px;
        }
        
        .gallery-stat-count {
            font-size: 1.5rem;
        }
        
        .gallery-stat-label {
            font-size: 0.85rem;
        }
        
        .gallery-stat-subtext {
            font-size: 0.7rem;
        }
        
        .meal-content {
            padding: 20px;
        }
        
        .meal-content h4 {
            font-size: 1.2rem;
        }
        
        /* CTA Mobile */
        .gallery-cta-wrapper {
            padding: 1.5rem 1rem 5rem 1rem;
        }
        
        .gallery-cta-section {
            padding: 2rem 1rem;
        }
        
        .gallery-cta-section h2 {
            font-size: 1.4rem;
        }
        
        .gallery-cta-section p {
            font-size: 1rem;
        }
        
        .gallery-trial-button,
        .gallery-outline-button {
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            min-width: 200px;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
        
        .section-description {
            font-size: 1rem;
        }
    }
</style>

<!-- 🚨 UPDATED: HERO SECTION - LIKE AVAILABLE ROOMS GALLERY -->
<section class="hero-gallery-section" 
         style="background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)), 
                url('{{ $hostelBgImage }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;">
    <div class="container">
        <div class="hero-main-content">
            <!-- ✅ FIXED: होस्टलको नाम हिरो सेक्सनमा -->
            <h1 class="hero-title nepali">🖼️ {{ $hostel->name ?? 'होस्टल' }} को पूर्ण ग्यालरी</h1>
            <p class="hero-subtitle nepali">
                यस होस्टलको सबै कोठाहरू, किचन, सुविधाहरू र भिडियोहरूको विस्तृत दृश्यात्मक अनुभव
            </p>
        </div>
        
        <!-- ✅ FIXED: Gallery Stats Grid - 5 Stats in One Line -->
        <div class="gallery-stats-grid">
            <div class="gallery-stat-item" data-category="rooms">
                <span class="gallery-stat-count">{{ $categoryCounts['rooms'] }}</span>
                <span class="gallery-stat-label nepali">कोठाहरू</span>
                <span class="gallery-stat-subtext nepali">१, २, ३, ४ र अन्य सिटर</span>
            </div>
            
            <div class="gallery-stat-item" data-category="kitchen">
                <span class="gallery-stat-count">{{ $categoryCounts['kitchen'] }}</span>
                <span class="gallery-stat-label nepali">किचन</span>
                <span class="gallery-stat-subtext nepali">आधुनिक किचन सुविधा</span>
            </div>
            
            <div class="gallery-stat-item" data-category="facilities">
                <span class="gallery-stat-count">{{ $categoryCounts['facilities'] }}</span>
                <span class="gallery-stat-label nepali">सुविधाहरू</span>
                <span class="gallery-stat-subtext nepali">अध्ययन क्षेत्र, शौचालय</span>
            </div>
            
            <div class="gallery-stat-item" data-category="video">
                <span class="gallery-stat-count">{{ $categoryCounts['video'] }}</span>
                <span class="gallery-stat-label nepali">भिडियोहरू</span>
                <span class="gallery-stat-subtext nepali">होस्टलको पूर्ण टुर</span>
            </div>
            
            <div class="gallery-stat-item" data-category="meal">
                <span class="gallery-stat-count">{{ $categoryCounts['meal'] }}</span>
                <span class="gallery-stat-label nepali">खानाको मेनु</span>
                <span class="gallery-stat-subtext nepali">दैनिक खानाको विवरण</span>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <h2 class="section-title nepali">हाम्रो होस्टल ग्यालरी</h2>
        <p class="section-description nepali">
            विभिन्न कोठा र सुविधाहरूको विस्तृत दृश्यहरू। तपाईंले चाहेको कोठाको प्रकार छनौट गरी तस्बिर हेर्नुहोस्।
        </p>
        
        <!-- Gallery Tabs Container -->
        <div class="gallery-tabs-container">
            <div class="gallery-tabs">
                <button class="tab-btn active nepali" data-tab="photo-gallery">
                    <i class="fas fa-images"></i> तस्बिरहरू
                </button>
                <button class="tab-btn nepali" data-tab="video-gallery">
                    <i class="fas fa-video"></i> भिडियोहरू
                </button>
                <button class="tab-btn nepali" data-tab="meal-gallery">
                    <i class="fas fa-utensils"></i> खानाको ग्यालरी
                </button>
            </div>
        </div>
        
        <!-- Photo Gallery Tab -->
        <div class="tab-content active" id="photo-gallery">
            <!-- Gallery Filters -->
            <div class="gallery-filters-container">
                <div class="gallery-filters">
                    <button class="filter-btn active nepali" data-filter="all">सबै</button>
                    <button class="filter-btn nepali" data-filter="1-seater">१ सिटर</button>
                    <button class="filter-btn nepali" data-filter="2-seater">२ सिटर</button>
                    <button class="filter-btn nepali" data-filter="3-seater">३ सिटर</button>
                    <button class="filter-btn nepali" data-filter="4-seater">४ सिटर</button>
                    <button class="filter-btn nepali" data-filter="other">अन्य</button>
                    <button class="filter-btn nepali" data-filter="facilities">सुविधाहरू</button>
                </div>
            </div>
            
            <div class="gallery-grid" id="mainGallery">
                @php
                    $displayedItems = 0;
                    $maxInitialDisplay = 8;
                @endphp
                
                @foreach($activeGalleries->whereIn('media_type', ['photo']) as $gallery)
                    @php
                        // Determine category for filtering with all room types
                        $filterCategory = '';
                        $categoryName = '';
                        
                        if (in_array($gallery->category, ['1 seater', '2 seater', '3 seater', '4 seater', 'other'])) {
                            $filterCategory = str_replace(' ', '-', $gallery->category);
                            $categoryName = $nepaliRoomTypes[$gallery->category] ?? $gallery->category;
                        } else {
                            $filterCategory = 'facilities';
                            $categoryName = 'सुविधा';
                        }

                        $displayedItems++;
                        $isHidden = $displayedItems > $maxInitialDisplay;
                        
                        // Get image URL using railway_media_url function
                        $imagePath = $gallery->file_path ?? '';
                        $imageUrl = $imagePath ? railway_media_url($imagePath) : asset('images/no-image.png');
                    @endphp

                    <div class="gallery-item {{ $isHidden ? 'hidden-item' : '' }}" 
                         data-category="{{ $filterCategory }}"
                         data-gallery-id="{{ $gallery->id }}">
                        
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $gallery->title }}" 
                             loading="lazy"
                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">

                        @if($gallery->is_featured)
                            <div class="featured-badge nepali">
                                <i class="fas fa-star"></i> Featured
                            </div>
                        @endif
                        
                        <div class="category-badge nepali">
                            {{ $categoryName }}
                        </div>

                        <div class="gallery-overlay">
                            <h3 class="gallery-title nepali">{{ $gallery->title }}</h3>
                            <p class="gallery-description nepali">{{ Str::limit($gallery->description, 120) }}</p>
                            <button class="btn btn-primary view-gallery-btn" 
                                    style="margin-top: 12px; padding: 10px 20px; font-size: 0.95rem;" 
                                    onclick="openGalleryModal('{{ $gallery->id }}', '{{ $gallery->media_type }}')">
                                <i class="fas fa-search-plus"></i> विस्तृत हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                @endforeach

                @if($activeGalleries->whereIn('media_type', ['photo'])->count() === 0)
                    <div class="no-content">
                        <div class="no-content-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <h3 class="nepali">कुनै तस्बिरहरू छैनन्</h3>
                        <p class="nepali">यस होस्टलको तस्बिर ग्यालरी चाँहि उपलब्ध छैन। कृपया पछि फेरी जाँच गर्नुहोस्।</p>
                    </div>
                @endif
            </div>
            
            @if($activeGalleries->whereIn('media_type', ['photo'])->count() > $maxInitialDisplay)
                <div class="view-more">
                    <button class="btn btn-primary nepali show-more-btn" 
                            style="padding: 12px 30px; font-size: 1rem;"
                            onclick="showMoreGallery()">
                        <i class="fas fa-chevron-down"></i> थप तस्बिर हेर्नुहोस्
                    </button>
                </div>
            @endif
        </div>
        
        <!-- Video Gallery Tab -->
        <div class="tab-content" id="video-gallery">
            <div class="gallery-grid">
                @foreach($activeGalleries->whereIn('media_type', ['local_video', 'external_video']) as $gallery)
                    @php
                        // Get thumbnail URL using railway_media_url function for videos
                        $thumbnailPath = $gallery->thumbnail_path ?? $gallery->file_path ?? '';
                        $thumbnailUrl = $thumbnailPath ? railway_media_url($thumbnailPath) : asset('images/video-default.jpg');
                    @endphp
                    
                    <div class="gallery-item" data-gallery-id="{{ $gallery->id }}">
                        
                        @if($gallery->media_type === 'local_video')
                            <img src="{{ $thumbnailUrl }}" 
                                 alt="{{ $gallery->title }}" 
                                 loading="lazy">
                        @elseif($gallery->media_type === 'external_video')
                            <img src="{{ $thumbnailUrl }}" 
                                 alt="{{ $gallery->title }}" 
                                 loading="lazy">
                        @endif

                        @if($gallery->is_featured)
                            <div class="featured-badge nepali">
                                <i class="fas fa-star"></i> Featured
                            </div>
                        @endif
                        
                        <div class="category-badge nepali">
                            <i class="fas fa-video"></i> भिडियो
                        </div>

                        <div class="gallery-overlay">
                            <h3 class="gallery-title nepali">{{ $gallery->title }}</h3>
                            <p class="gallery-description nepali">{{ Str::limit($gallery->description, 120) }}</p>
                            <button class="btn btn-primary view-gallery-btn" 
                                    style="margin-top: 12px; padding: 10px 20px; font-size: 0.95rem;" 
                                    onclick="openGalleryModal('{{ $gallery->id }}', '{{ $gallery->media_type }}')">
                                <i class="fas fa-play-circle" style="margin-right: 8px;"></i> भिडियो हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                @endforeach

                @if($activeGalleries->whereIn('media_type', ['local_video', 'external_video'])->count() === 0)
                    <div class="no-content">
                        <div class="no-content-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <h3 class="nepali">कुनै भिडियोहरू छैनन्</h3>
                        <p class="nepali">यस होस्टलको भिडियो ग्यालरी चाँहि उपलब्ध छैन। कृपया पछि फेरी जाँच गर्नुहोस्।</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Meal Gallery Tab -->
        <div class="tab-content" id="meal-gallery">
            <div class="meal-gallery-grid">
                @if(isset($mealMenus) && $mealMenus->count() > 0)
                    @foreach($mealMenus as $menu)
                    @php
                        // Determine Nepali meal type
                        $mealTypeNepali = '';
                        if($menu->meal_type == 'breakfast') {
                            $mealTypeNepali = 'विहानको खाना';
                        } elseif($menu->meal_type == 'lunch') {
                            $mealTypeNepali = 'दिउसोको खाना';
                        } else {
                            $mealTypeNepali = 'बेलुकाको खाना';
                        }
                        
                        // Get image URL using railway_media_url function
                        $mealImageUrl = $menu->image ? railway_media_url($menu->image) : 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                        
                        // Get meal items description
                        $mealDescription = $menu->formatted_items ?? $menu->description;
                    @endphp
                    <div class="meal-item-card">
                        <div class="meal-image" onclick="openMealImageModal('{{ $mealTypeNepali }}', '{{ $menu->day_of_week }}', '{{ addslashes($mealDescription) }}', '{{ $mealImageUrl }}')">
                            <img src="{{ $mealImageUrl }}" 
                                 alt="{{ $menu->description }}" 
                                 loading="lazy"
                                 style="cursor: pointer;">
                            <span class="meal-type-badge nepali">
                                @if($menu->meal_type == 'breakfast')
                                    <i class="fas fa-sun"></i> विहानको खाना
                                @elseif($menu->meal_type == 'lunch')
                                    <i class="fas fa-sun"></i> दिउसोको खाना
                                @else
                                    <i class="fas fa-moon"></i> बेलुकाको खाना
                                @endif
                            </span>
                        </div>
                        <div class="meal-content">
                            <h4 class="nepali">
                                {{ $mealTypeNepali }}
                            </h4>
                            <p class="meal-day nepali">
                                <i class="fas fa-calendar-day"></i> {{ $menu->day_of_week }}
                            </p>
                            <p class="meal-items nepali">
                                {{ $mealDescription }}
                            </p>
                            <div class="meal-time nepali">
                                <i class="fas fa-clock"></i>
                                @if($menu->meal_type == 'breakfast')
                                    ७:०० - ९:०० बिहान
                                @elseif($menu->meal_type == 'lunch')
                                    १२:०० - २:०० दिउँसो
                                @else
                                    ६:०० - ८:०० बेलुका
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="no-content">
                        <div class="no-content-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 class="nepali">कुनै खानाको मेनु छैन</h3>
                        <p class="nepali">यस होस्टलको लागि कुनै खानाको मेनु उपलब्ध छैन। कृपया पछि फेरी जाँच गर्नुहोस्।</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- 🚨 UPDATED: CTA SECTION - EXACTLY LIKE AVAILABLE ROOMS GALLERY -->
        <div class="gallery-cta-wrapper">
            <section class="gallery-cta-section">
                <h2 class="nepali">यस होस्टलमा कोठा बुक गर्न चाहनुहुन्छ?</h2>
                <p class="nepali">अहिले नै कोठा सुरक्षित गर्नुहोस् वा थप जानकारी लिनुहोस्</p>
                
                <div class="gallery-cta-buttons-container">
                    <!-- ✅ FIXED: कोठा बुक गर्ने बटन (Orange) -->
                    @if(isset($hostel) && $hostel)
                        @if(\Route::has('hostel.gallery'))
                            <a href="{{ route('hostel.gallery', $hostel->slug) }}" 
                               class="gallery-trial-button nepali">
                                <i class="fas fa-bed"></i> कोठा उपलब्धता हेर्नुहोस्
                            </a>
                        @else
                            <a href="/hostel/{{ $hostel->slug }}/gallery" 
                               class="gallery-trial-button nepali">
                                <i class="fas fa-bed"></i> कोठा उपलब्धता हेर्नुहोस्
                            </a>
                        @endif
                    @endif
                    
                    <!-- ✅ FIXED: पृष्ठ भ्रमण गर्ने बटन (Outline) -->
                    @if(isset($hostel) && $hostel)
                        @if(\Route::has('hostels.show'))
                            <a href="{{ route('hostels.show', $hostel->slug) }}" 
                               class="gallery-outline-button nepali">
                                <i class="fas fa-home"></i> पृष्ठ भ्रमण गर्नुहोस्
                            </a>
                        @else
                            <a href="/hostel/{{ $hostel->slug }}" 
                               class="gallery-outline-button nepali">
                                <i class="fas fa-home"></i> पृष्ठ भ्रमण गर्नुहोस्
                            </a>
                        @endif
                    @endif
                    
                    <!-- ✅ FIXED: कल गर्ने बटन (Outline) -->
                    @if($hostel->contact_phone ?? false)
                        <a href="tel:{{ $hostel->contact_phone }}" 
                           class="gallery-outline-button nepali">
                            <i class="fas fa-phone-alt"></i> कल गर्नुहोस्
                        </a>
                    @endif
                </div>
                
                <!-- Contact info -->
                <div class="gallery-contact-info">
                    @if($hostel->name ?? false)
                    <p class="nepali">
                        <i class="fas fa-building"></i> {{ $hostel->name }}
                    </p>
                    @endif
                    
                    @if($hostel->contact_phone ?? false)
                    <p class="nepali">
                        <i class="fas fa-phone"></i> प्रत्यक्ष कल: {{ $hostel->contact_phone }}
                    </p>
                    @endif
                    
                    @if($hostel->contact_email ?? false)
                    <p class="nepali">
                        <i class="fas fa-envelope"></i> इमेल: {{ $hostel->contact_email }}
                    </p>
                    @endif
                    
                    <p class="nepali">
                        <i class="fas fa-clock"></i> २४/७ सम्पर्क सेवा उपलब्ध
                    </p>
                </div>
            </section>
        </div>
    </div>
</section>

<!-- Image Modal with Auto-Hide Caption -->
<div class="gallery-modal" id="imageModal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeGalleryModal()">&times;</button>
        <img id="modalImage" src="" alt="">
        <!-- 🖼️ Image Caption (Auto-hides after delay) -->
        <div class="modal-caption hover-show" id="imageCaption">
            <h3 id="modalTitle" class="nepali"></h3>
            <p id="modalDescription" class="nepali"></p>
        </div>
    </div>
</div>

<!-- 🎥 Video Modal with Play Button -->
<div class="gallery-modal" id="videoModal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeGalleryModal()">&times;</button>
        
        <!-- 🎥 Play Button Overlay -->
        <div class="video-play-overlay" id="videoPlayOverlay">
            <div class="video-play-button" onclick="playVideo()">
                <i class="fas fa-play"></i>
            </div>
        </div>
        
        <!-- 🎥 Video Element -->
        <video id="modalVideo" preload="metadata" playsinline>
            <source src="" type="video/mp4">
            तपाईंको ब्राउजरले भिडियो सपोर्ट गर्दैन।
        </video>
        
        <!-- 🎥 Video Controls -->
        <div class="video-controls" id="videoControls">
            <div class="video-progress-bar">
                <div class="video-progress-fill" id="videoProgress"></div>
            </div>
            
            <div class="video-control-buttons">
                <div class="video-control-left">
                    <button class="video-control-btn" id="playPauseBtn" onclick="togglePlayPause()">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="video-control-btn" onclick="muteVideo()">
                        <i class="fas fa-volume-up" id="volumeIcon"></i>
                    </button>
                    <span class="video-time" id="currentTime">0:00</span>
                    <span class="video-time">/</span>
                    <span class="video-time" id="durationTime">0:00</span>
                </div>
                
                <div class="video-control-right">
                    <button class="video-control-btn" onclick="toggleFullscreen()">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- 🎥 Video Caption (Auto-hides when playing) -->
        <div class="modal-caption hover-show" id="videoCaption">
            <h3 id="videoTitle" class="nepali"></h3>
            <p id="videoDescription" class="nepali"></p>
        </div>
    </div>
</div>

<!-- YouTube Modal -->
<div class="gallery-modal" id="youtubeModal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeGalleryModal()">&times;</button>
        <iframe id="modalYouTube" width="100%" height="400" frameborder="0" allowfullscreen></iframe>
        <div class="modal-caption hover-show" id="youtubeCaption">
            <h3 id="youtubeTitle" class="nepali"></h3>
            <p id="youtubeDescription" class="nepali"></p>
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
            console.log('✅ Full Gallery Page fully loaded');
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

    // Gallery data from backend - FIXED JSON ENCODING
    const galleryData = {
        @foreach($activeGalleries as $gallery)
        '{{ $gallery->id }}': {
            title: {!! json_encode($gallery->title) !!},
            description: {!! json_encode($gallery->description) !!},
            media_type: {!! json_encode($gallery->media_type) !!},
            media_url: {!! json_encode($gallery->media_type === 'external_video' ? $gallery->external_link : railway_media_url($gallery->file_path ?? '')) !!},
            thumbnail_url: {!! json_encode($gallery->thumbnail_path ? railway_media_url($gallery->thumbnail_path) : railway_media_url($gallery->file_path ?? '')) !!},
            youtube_embed_url: {!! json_encode($gallery->youtube_embed_url) !!}
        },
        @endforeach
    };

    // Modal Variables
    let currentVideoModal = null;
    let currentVideoPlayOverlay = null;
    let currentVideoControls = null;
    let isVideoPlaying = false;
    let hideControlsTimeout = null;
    let hideCaptionTimeout = null;
    
    // Image Modal Variables
    let imageCaptionTimeout = null;
    let imageModalHover = false;
    
    // Tab Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button
                button.classList.add('active');
                
                // Show corresponding tab content
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
                
                // Update page title based on active tab
                const tabName = button.textContent.trim();
                console.log(`Switched to ${tabName} tab`);
            });
        });

        // Gallery Filter Functionality (for photo tab only)
        const filterButtons = document.querySelectorAll('#photo-gallery .filter-btn');
        const galleryItems = document.querySelectorAll('#photo-gallery .gallery-item');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');
                
                const filterValue = button.getAttribute('data-filter');
                console.log(`Filtering by: ${filterValue}`);
                
                let visibleCount = 0;
                galleryItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Show/hide "show more" button based on visible items
                const showMoreBtn = document.querySelector('#photo-gallery .show-more-btn');
                if (showMoreBtn) {
                    if (visibleCount > 8) {
                        showMoreBtn.style.display = 'block';
                    } else {
                        showMoreBtn.style.display = 'none';
                    }
                }
                
                console.log(`Visible items: ${visibleCount}`);
            });
        });
    });
    
    // Show More Gallery Functionality
    function showMoreGallery() {
        const hiddenItems = document.querySelectorAll('#photo-gallery .gallery-item.hidden-item');
        console.log(`Showing ${hiddenItems.length} more items`);
        
        hiddenItems.forEach(item => {
            item.classList.remove('hidden-item');
        });
        
        // Hide the show more button
        const showMoreBtn = document.querySelector('#photo-gallery .show-more-btn');
        if (showMoreBtn) {
            showMoreBtn.style.display = 'none';
        }
    }
    
    // ✅ FIXED: Modal Functionality with Dynamic Content
    function openGalleryModal(galleryId, mediaType) {
        console.log('Opening modal for:', galleryId, mediaType);
        
        const gallery = galleryData[galleryId];
        if (!gallery) {
            console.error(`Gallery item ${galleryId} not found`);
            return;
        }

        console.log('Gallery data:', gallery);

        // Close any open modal first
        closeGalleryModal();
        
        if (mediaType === 'photo') {
            const modal = document.getElementById('imageModal');
            if (!modal) {
                console.error('Image modal not found');
                return;
            }
            
            modal.classList.add('active');
            document.getElementById('modalImage').src = gallery.media_url;
            document.getElementById('modalTitle').textContent = gallery.title;
            document.getElementById('modalDescription').textContent = gallery.description;
            
            // Show caption initially
            showImageCaption();
            // Start timer to hide caption
            startImageCaptionTimer();
            
        } else if (mediaType === 'local_video') {
            const modal = document.getElementById('videoModal');
            if (!modal) {
                console.error('Video modal not found');
                return;
            }
            
            modal.classList.add('active');
            
            // Get video elements
            currentVideoModal = document.getElementById('modalVideo');
            currentVideoPlayOverlay = document.getElementById('videoPlayOverlay');
            currentVideoControls = document.getElementById('videoControls');
            
            if (currentVideoModal) {
                currentVideoModal.pause();
                currentVideoModal.currentTime = 0;
                currentVideoModal.src = gallery.media_url;
                currentVideoModal.load();
                
                // Setup video event listeners
                setupVideoEventListeners();
            }
            
            // Show play overlay
            if (currentVideoPlayOverlay) {
                currentVideoPlayOverlay.classList.remove('hidden');
            }
            
            // Show caption initially
            const videoCaption = document.getElementById('videoCaption');
            if (videoCaption) {
                videoCaption.classList.remove('hidden');
            }
            
            // Update video info
            document.getElementById('videoTitle').textContent = gallery.title;
            document.getElementById('videoDescription').textContent = gallery.description;
            
            // Reset progress
            const progressFill = document.getElementById('videoProgress');
            if (progressFill) {
                progressFill.style.width = '0%';
            }
            
            // Reset time displays
            document.getElementById('currentTime').textContent = '0:00';
            document.getElementById('durationTime').textContent = '0:00';
            
            // Update play button
            updatePlayPauseButton(false);
            
            // Reset volume icon
            updateVolumeIcon();
            
            // Clear any existing timeouts
            clearTimeout(hideControlsTimeout);
            clearTimeout(hideCaptionTimeout);
            
        } else if (mediaType === 'external_video') {
            const modal = document.getElementById('youtubeModal');
            if (!modal) {
                console.error('YouTube modal not found');
                return;
            }
            
            modal.classList.add('active');
            
            const embedUrl = gallery.youtube_embed_url || gallery.media_url;
            document.getElementById('modalYouTube').src = embedUrl;
            document.getElementById('youtubeTitle').textContent = gallery.title;
            document.getElementById('youtubeDescription').textContent = gallery.description;
        }
        
        document.body.style.overflow = 'hidden';
    }
    
    // ✅ NEW: Meal Image Modal Function
    function openMealImageModal(mealType, day, description, imageUrl) {
        console.log('Opening meal image modal:', mealType, day);
        
        // Close any open modal first
        closeGalleryModal();
        
        const modal = document.getElementById('imageModal');
        if (!modal) {
            console.error('Image modal not found');
            return;
        }
        
        modal.classList.add('active');
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('modalTitle').textContent = mealType + ' - ' + day;
        document.getElementById('modalDescription').textContent = description;
        
        // Show caption initially
        showImageCaption();
        // Start timer to hide caption
        startImageCaptionTimer();
        
        document.body.style.overflow = 'hidden';
    }
    
    // Setup video event listeners
    function setupVideoEventListeners() {
        if (!currentVideoModal) return;
        
        // Remove existing listeners first
        currentVideoModal.removeEventListener('play', handleVideoPlay);
        currentVideoModal.removeEventListener('pause', handleVideoPause);
        currentVideoModal.removeEventListener('timeupdate', handleVideoTimeUpdate);
        currentVideoModal.removeEventListener('loadedmetadata', handleVideoLoadedMetadata);
        currentVideoModal.removeEventListener('ended', handleVideoEnded);
        currentVideoModal.removeEventListener('volumechange', handleVolumeChange);
        
        // Add new listeners
        currentVideoModal.addEventListener('play', handleVideoPlay);
        currentVideoModal.addEventListener('pause', handleVideoPause);
        currentVideoModal.addEventListener('timeupdate', handleVideoTimeUpdate);
        currentVideoModal.addEventListener('loadedmetadata', handleVideoLoadedMetadata);
        currentVideoModal.addEventListener('ended', handleVideoEnded);
        currentVideoModal.addEventListener('volumechange', handleVolumeChange);
        
        // Touch/click events for showing controls
        currentVideoModal.addEventListener('click', togglePlayPause);
        currentVideoModal.addEventListener('dblclick', toggleFullscreen);
        
        // Show controls on hover
        const modalContent = document.querySelector('#videoModal .modal-content');
        if (modalContent) {
            modalContent.addEventListener('mousemove', function() {
                showVideoControls();
                startHideControlsTimer();
            });
        }
    }
    
    // Video event handlers
    function handleVideoPlay() {
        isVideoPlaying = true;
        updatePlayPauseButton(true);
        hidePlayOverlay();
        startHideCaptionTimer();
        startHideControlsTimer();
    }
    
    function handleVideoPause() {
        isVideoPlaying = false;
        updatePlayPauseButton(false);
        showVideoCaption();
        showVideoControls();
    }
    
    function handleVideoTimeUpdate() {
        updateVideoProgress();
    }
    
    function handleVideoLoadedMetadata() {
        updateVideoDuration();
    }
    
    function handleVideoEnded() {
        videoEnded();
    }
    
    function handleVolumeChange() {
        updateVolumeIcon();
    }
    
    // Image Modal Functions
    function showImageCaption() {
        const imageCaption = document.getElementById('imageCaption');
        if (imageCaption) {
            imageCaption.classList.remove('hidden');
        }
    }
    
    function hideImageCaption() {
        if (!imageModalHover) {
            const imageCaption = document.getElementById('imageCaption');
            if (imageCaption) {
                imageCaption.classList.add('hidden');
            }
        }
    }
    
    function startImageCaptionTimer() {
        clearTimeout(imageCaptionTimeout);
        imageCaptionTimeout = setTimeout(hideImageCaption, 5000); // Hide after 5 seconds
    }
    
    function resetImageCaptionTimer() {
        clearTimeout(imageCaptionTimeout);
        imageCaptionTimeout = setTimeout(hideImageCaption, 5000); // Hide after 5 seconds
    }
    
    // Initialize image modal event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const imageModalElement = document.getElementById('imageModal');
        if (imageModalElement) {
            const imageModalContent = imageModalElement.querySelector('.modal-content');
            
            if (imageModalContent) {
                // Show caption on hover
                imageModalContent.addEventListener('mouseenter', function() {
                    imageModalHover = true;
                    showImageCaption();
                    resetImageCaptionTimer();
                });
                
                imageModalContent.addEventListener('mouseleave', function() {
                    imageModalHover = false;
                    startImageCaptionTimer();
                });
                
                // Show caption on mousemove
                imageModalContent.addEventListener('mousemove', function() {
                    showImageCaption();
                    resetImageCaptionTimer();
                });
            }
        }
        
        // Initialize YouTube modal event listeners
        const youtubeModalElement = document.getElementById('youtubeModal');
        if (youtubeModalElement) {
            const youtubeModalContent = youtubeModalElement.querySelector('.modal-content');
            
            if (youtubeModalContent) {
                youtubeModalContent.addEventListener('mouseenter', function() {
                    showYouTubeCaption();
                });
            }
        }
        
        // Add click event to meal images
        document.querySelectorAll('.meal-image').forEach(mealImage => {
            mealImage.addEventListener('click', function(e) {
                if (e.target.classList.contains('meal-type-badge')) {
                    return; // Don't open modal when clicking on badge
                }
            });
        });
    });
    
    // YouTube Modal Functions
    function showYouTubeCaption() {
        const youtubeCaption = document.getElementById('youtubeCaption');
        if (youtubeCaption) {
            youtubeCaption.classList.remove('hidden');
        }
    }
    
    // Video Functions
    function playVideo() {
        if (currentVideoModal) {
            currentVideoModal.play().catch(function(error) {
                console.error("Video play failed:", error);
            });
        }
    }

    function togglePlayPause() {
        if (currentVideoModal) {
            if (currentVideoModal.paused) {
                playVideo();
            } else {
                currentVideoModal.pause();
            }
        }
    }

    function updatePlayPauseButton(playing) {
        const playPauseBtn = document.getElementById('playPauseBtn');
        if (playPauseBtn) {
            if (playing) {
                playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
            } else {
                playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
        }
    }

    function hidePlayOverlay() {
        if (currentVideoPlayOverlay) {
            currentVideoPlayOverlay.classList.add('hidden');
        }
    }

    function showPlayOverlay() {
        if (currentVideoPlayOverlay) {
            currentVideoPlayOverlay.classList.remove('hidden');
        }
    }

    function showVideoControls() {
        if (currentVideoControls) {
            currentVideoControls.classList.remove('hidden');
        }
    }

    function startHideControlsTimer() {
        clearTimeout(hideControlsTimeout);
        hideControlsTimeout = setTimeout(function() {
            if (isVideoPlaying && currentVideoControls) {
                currentVideoControls.classList.add('hidden');
            }
        }, 3000); // Hide after 3 seconds
    }

    function showVideoCaption() {
        const videoCaption = document.getElementById('videoCaption');
        if (videoCaption) {
            videoCaption.classList.remove('hidden');
        }
    }

    function startHideCaptionTimer() {
        clearTimeout(hideCaptionTimeout);
        hideCaptionTimeout = setTimeout(function() {
            if (isVideoPlaying) {
                const videoCaption = document.getElementById('videoCaption');
                if (videoCaption) {
                    videoCaption.classList.add('hidden');
                }
            }
        }, 5000); // Hide caption after 5 seconds of playing
    }

    function updateVideoProgress() {
        const progressFill = document.getElementById('videoProgress');
        const currentTimeEl = document.getElementById('currentTime');
        
        if (currentVideoModal && progressFill && currentTimeEl) {
            const progress = (currentVideoModal.currentTime / currentVideoModal.duration) * 100;
            progressFill.style.width = progress + '%';
            
            // Update current time display
            currentTimeEl.textContent = formatTime(currentVideoModal.currentTime);
        }
    }

    function updateVideoDuration() {
        const durationEl = document.getElementById('durationTime');
        if (currentVideoModal && durationEl) {
            durationEl.textContent = formatTime(currentVideoModal.duration);
        }
    }

    function formatTime(seconds) {
        if (isNaN(seconds)) return "0:00";
        
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return mins + ':' + (secs < 10 ? '0' : '') + secs;
    }

    function videoEnded() {
        isVideoPlaying = false;
        updatePlayPauseButton(false);
        showPlayOverlay();
        showVideoCaption();
        showVideoControls();
        
        // Reset progress
        const progressFill = document.getElementById('videoProgress');
        if (progressFill) {
            progressFill.style.width = '0%';
        }
        
        const currentTimeEl = document.getElementById('currentTime');
        if (currentTimeEl) {
            currentTimeEl.textContent = '0:00';
        }
    }

    function muteVideo() {
        if (currentVideoModal) {
            currentVideoModal.muted = !currentVideoModal.muted;
            updateVolumeIcon();
        }
    }

    function updateVolumeIcon() {
        const volumeIcon = document.getElementById('volumeIcon');
        if (currentVideoModal && volumeIcon) {
            if (currentVideoModal.muted || currentVideoModal.volume === 0) {
                volumeIcon.className = 'fas fa-volume-mute';
            } else {
                volumeIcon.className = 'fas fa-volume-up';
            }
        }
    }

    function toggleFullscreen() {
        const modalContent = document.querySelector('#videoModal .modal-content');
        if (!document.fullscreenElement && modalContent) {
            if (modalContent.requestFullscreen) {
                modalContent.requestFullscreen();
            } else if (modalContent.webkitRequestFullscreen) {
                modalContent.webkitRequestFullscreen();
            } else if (modalContent.msRequestFullscreen) {
                modalContent.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }
    
    function closeGalleryModal() {
        console.log('Closing modal');
        
        // Close all modals
        const modals = document.querySelectorAll('.gallery-modal');
        modals.forEach(modal => {
            modal.classList.remove('active');
        });
        
        // Stop and reset video
        if (currentVideoModal) {
            currentVideoModal.pause();
            currentVideoModal.currentTime = 0;
            isVideoPlaying = false;
        }
        
        // Show play overlay for next time
        if (currentVideoPlayOverlay) {
            currentVideoPlayOverlay.classList.remove('hidden');
        }
        
        // Show captions
        showVideoCaption();
        showImageCaption();
        
        // Clear timeouts
        clearTimeout(hideControlsTimeout);
        clearTimeout(hideCaptionTimeout);
        clearTimeout(imageCaptionTimeout);
        
        // Reset YouTube iframe
        const youtubeIframe = document.getElementById('modalYouTube');
        if (youtubeIframe) {
            youtubeIframe.src = '';
        }
        
        document.body.style.overflow = 'auto';
        imageModalHover = false;
    }
    
    // Close modal when clicking outside the content
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.gallery-modal.active');
        modals.forEach(modal => {
            if (event.target === modal) {
                closeGalleryModal();
            }
        });
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeGalleryModal();
        }
    });
    
    // Initialize after page is loaded
    window.addEventListener('pageFullyLoaded', function() {
        console.log('Initializing full gallery functionality...');
        
        // Add click event to view gallery buttons
        document.querySelectorAll('.view-gallery-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent event bubbling
            });
        });
        
        console.log('Full gallery functionality initialized');
    });
    
    // Fallback initialization
    document.addEventListener('DOMContentLoaded', function() {
        // If pageFullyLoaded hasn't fired in 2 seconds, initialize anyway
        setTimeout(function() {
            if (!document.body.classList.contains('page-loaded')) {
                console.log('Fallback initialization for gallery');
                window.dispatchEvent(new Event('pageFullyLoaded'));
            }
        }, 2000);
    });
</script>
@endsection