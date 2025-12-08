@extends('layouts.frontend')

@section('page-title', 'ग्यालरी - HostelHub')

@push('styles')
@vite(['resources/css/gallery.css'])
<style>

/* 🚨 EXTREME FIX: Remove all extra spacing */
body .gallery-page-wrapper,
body .gallery-content-wrapper {
    margin: 0 !important;
    padding: 0 !important;
}

/* 🚨 CRITICAL: Force gallery header to top */
.gallery-header {
    position: relative !important;
    top: 0 !important;
    transform: none !important;
    animation: none !important;
}

/* 🚨 FIX: Remove any transform or translate */
.gallery-header {
    transform: translateY(0) !important;
    animation: none !important;
}

/* 🚨 FIX: Ensure no extra margin from parent */
#main, main, .main-content, .content {
    margin: 0 !important;
    padding: 0 !important;
}

/* 🚨 FIX: Gallery page specific - complete reset */
.gallery-page-main {
    margin: 0 !important;
    padding: 0 !important;
    min-height: 0 !important;
}

/* 🚨 FIX: Header height calculation */
.gallery-header {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* 🚨 CRITICAL: Force hide all spinners by default */
.gallery-loading,
.videos-loading,
.videos-placeholder .spinner,
.loading-spinner {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

/* Only show when explicitly triggered */
.gallery-loading:not(.hidden),
.videos-loading:not(.hidden) {
    display: block;
    visibility: visible;
    opacity: 1;
}

/* Ensure videos placeholder is hidden when videos exist */
.videos-grid:has(.video-card) .videos-placeholder {
    display: none !important;
}

/* FIX: Make sure videos grid shows properly */
.videos-grid {
    position: relative;
    min-height: 200px;
}

/* FIX: Smooth transitions for placeholder */
.videos-placeholder {
    transition: opacity 0.3s ease, height 0.3s ease;
}

/* Mobile fix */
@media (max-width: 768px) {
    .gallery-header {
        margin: 0 auto 1rem auto !important;
        padding: 1.5rem 1rem !important;
    }
}

    /* 🚨 CRITICAL: Gallery page specific fixes */
    .gallery-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    .gallery-content-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }
    
    /* Remove any duplicate header protection */
    .page-header {
        display: none !important;
    }
    
    /* 🚨 UPDATED: Gallery Header - EXACT SAME AS FEATURES PAGE */
    .gallery-header {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2.5rem 1.5rem; /* 🚨 Features जस्तै बनाउनुहोस् */
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 1000px;
        width: 90%;
        
        /* 🚨 CRITICAL FIX: Match Features Page spacing exactly */
        margin: calc(var(--header-height, 70px) + 0.9rem) auto 1.5rem auto !important;
    }
    
    .gallery-header h1 {
        font-size: 2.5rem; /* 🚨 Features जस्तै बनाउनुहोस् */
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem; /* 🚨 Features जस्तै बनाउनुहोस् */
        line-height: 1.2;
    }
    
    .gallery-header p {
        font-size: 1.125rem; /* 🚨 Features जस्तै बनाउनुहोस् */
        color: rgba(255, 255, 255, 0.9);
        max-width: 800px;
        margin: 0 auto 0.75rem auto; /* 🚨 Features जस्तै बनाउनुहोस् */
        line-height: 1.6; /* 🚨 Features जस्तै बनाउनुहोस् */
    }

    /* 🆕 Meal Gallery Button Styles - Adjusted for increased spacing */
    .meal-gallery-button-container {
        text-align: center;
        margin: 0.75rem 0 0 0; /* 🚨 Features जस्तै बनाउनुहोस् */
    }
    
    .meal-gallery-button {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 0.6rem 1.25rem; /* 🚨 थोरै बढाउनुहोस् */
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s ease;
        font-size: 0.9rem; /* 🚨 थोरै बढाउनुहोस् */
    }
    
    .meal-gallery-button:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* 🆕 Tabs Interface - Adjusted spacing */
    .gallery-tabs-container {
        max-width: 1200px;
        margin: 0 auto 1.25rem auto;
        width: 95%;
    }

    .gallery-tabs {
        display: flex;
        gap: 0.4rem;
        margin-bottom: 1.25rem;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.4rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .tab-btn {
        padding: 0.6rem 1.25rem;
        background: transparent;
        border: 2px solid #e5e7eb;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: var(--gallery-dark);
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .tab-btn:hover {
        border-color: var(--gallery-secondary);
        color: var(--gallery-secondary);
        transform: translateY(-2px);
    }

    .tab-btn.active {
        background: var(--gallery-primary);
        border-color: var(--gallery-primary);
        color: white;
        box-shadow: 0 5px 15px rgba(30, 58, 138, 0.3);
    }

    /* 🚨 FIXED: Badge visibility - Always visible */
    .tab-badge {
        background: rgba(0, 0, 0, 0.7); /* Dark background for inactive state */
        color: white !important;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.3);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
    }

    /* Active state badge */
    .tab-btn.active .tab-badge {
        background: rgba(255, 255, 255, 0.95) !important;
        color: #1e293b !important;
        border: 1px solid rgba(30, 41, 59, 0.2) !important;
        font-weight: 700 !important;
        text-shadow: none;
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .tab-content.active {
        display: block;
    }

    /* Video Card Specific Styles */
    .video-card {
        position: relative;
        border-radius: var(--gallery-radius);
        overflow: hidden;
        background: white;
        box-shadow: var(--gallery-shadow);
        transition: var(--gallery-transition);
        cursor: pointer;
    }

    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
    }

    .video-thumbnail {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio for videos */
        overflow: hidden;
        background: #000;
    }

    .video-thumbnail img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .video-card:hover .video-thumbnail img {
        transform: scale(1.05);
    }

    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--gallery-primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        z-index: 2;
    }

    .video-card:hover .play-button {
        background: white;
        transform: translate(-50%, -50%) scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
    }

    .video-duration {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }

    .video-badge-360 {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(14, 165, 233, 0.9);
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .video-info {
        padding: 1rem;
    }

    .video-info h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--gallery-dark);
        line-height: 1.3;
    }

    .video-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
        font-size: 0.85rem;
        color: #666;
    }

    .video-hostel-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gallery-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.8rem; /* FIX #4: Reduced from 0.9rem */
        transition: all 0.3s ease;
        padding: 0.3rem 0; /* FIX #4: Reduced from 0.5rem 0 */
    }

    .video-hostel-link:hover {
        color: var(--gallery-secondary);
        gap: 0.7rem;
    }

    .video-resolution {
        background: var(--gallery-light);
        color: var(--gallery-dark);
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Video Categories Filter */
    .video-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .video-category-btn {
        padding: 0.5rem 1rem;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        color: #4b5563;
    }

    .video-category-btn:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }

    .video-category-btn.active {
        background: var(--gallery-primary);
        color: white;
        border-color: var(--gallery-primary);
    }

    /* HD Image Indicator */
    .hd-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.8);
        color: #10b981;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* Enhanced Hostel Link - FIX #2: Remove text truncation */
    .hostel-link-enhanced {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(30, 58, 138, 0.9);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(4px);
        white-space: normal; /* FIX #2: Allow text to wrap */
        max-width: none; /* FIX #2: Remove max-width limit */
    }

    .hostel-link-enhanced:hover {
        background: rgba(30, 58, 138, 1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        color: white;
    }

    /* FIX #4: Reduced button size */
    .quick-view-btn {
        background: rgba(255, 255, 255, 0.9);
        color: var(--gallery-dark);
        border: none;
        padding: 0.3rem 0.8rem; /* FIX #4: Reduced from 0.6rem 1.2rem */
        border-radius: 4px;
        font-size: 0.75rem; /* FIX #4: Reduced from 0.875rem */
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        margin-top: 0.5rem;
    }

    .quick-view-btn:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Enhanced Loading State for Videos */
    .videos-placeholder {
        text-align: center;
        padding: 3rem;
        grid-column: 1 / -1;
    }

    .videos-placeholder .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--gallery-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    .videos-placeholder.hidden {
        display: none !important;
    }

    /* 🚨 UPDATED GALLERY CTA SECTION - EXACT SAME AS FEATURES PAGE */
    .gallery-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.25rem 1.5rem 1.75rem 1.5rem;
        margin-top: 0.75rem;
    }

    .gallery-cta-section {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2rem 1.75rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
    }

    .gallery-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
    }

    .gallery-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    /* DEMO BUTTON (Orange Gradient) - EXACT SAME AS FEATURES */
    .gallery-demo-button {
        background: linear-gradient(135deg, #FF6B6B, #FF8E53);
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1rem;
        text-align: center;
    }

    .gallery-demo-button:hover {
        background: linear-gradient(135deg, #FF5252, #FF7A3D);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
        color: white;
    }

    /* PRIMARY TRIAL BUTTON (White Background) - EXACT SAME AS FEATURES */
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1rem;
        text-align: center;
    }

    .gallery-trial-button:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: #001F5B;
    }

    /* OUTLINE PRICING BUTTON - EXACT SAME AS FEATURES */
    .gallery-outline-button {
        background: transparent;
        border: 2px solid white;
        color: white;
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
        text-align: center;
    }

    .gallery-outline-button:hover {
        background: white;
        color: #001F5B;
        transform: translateY(-2px);
    }

    .gallery-cta-buttons-container {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        width: 100%;
        flex-wrap: wrap;
    }

    /* Loading states */
    .gallery-outline-button.loading,
    .gallery-trial-button.loading,
    .gallery-demo-button.loading {
        position: relative;
        color: transparent;
    }

    .gallery-outline-button.loading::after,
    .gallery-trial-button.loading::after,
    .gallery-demo-button.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin: -10px 0 0 -10px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }

    .gallery-trial-button.loading::after {
        border: 2px solid rgba(0,31,91,0.3);
        border-top-color: #001F5B;
    }

    .gallery-demo-button.loading::after {
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Mobile adjustments - EXACT SAME AS FEATURES PAGE */
    @media (max-width: 768px) {
        .gallery-tabs {
            gap: 0.3rem;
            justify-content: center;
        }

        .tab-btn {
            padding: 0.5rem 0.9rem;
            font-size: 0.85rem;
        }

        .video-categories {
            justify-content: center;
        }

        .video-category-btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        .play-button {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }

        .video-info h3 {
            font-size: 1rem;
        }
        
        /* Gallery Header Mobile - SAME SPACING AS FEATURES PAGE */
        .gallery-header {
            padding: 1.75rem 1rem; /* 🚨 Features जस्तै बनाउनुहोस् */
            margin: calc(var(--header-height, 70px) + 0.25rem) auto 1rem auto !important; /* 🚨 Features जस्तै बनाउनुहोस् */
        }
        
        .gallery-header h1 {
            font-size: 2rem; /* 🚨 Features जस्तै बनाउनुहोस् */
            margin-bottom: 0.5rem; /* 🚨 Features जस्तै बनाउनुहोस् */
        }
        
        .gallery-header p {
            font-size: 1rem; /* 🚨 Features जस्तै बनाउनुहोस् */
            margin-bottom: 0.5rem; /* 🚨 Features जस्तै बनाउनुहोस् */
        }
        
        .meal-gallery-button {
            padding: 0.5rem 1rem; /* 🚨 थोरै बढाउनुहोस् */
            font-size: 0.85rem; /* 🚨 थोरै बढाउनुहोस् */
            gap: 0.3rem;
        }
        
        /* CTA Mobile adjustments */
        .gallery-cta-wrapper {
            padding: 1rem 1rem 1.25rem 1rem;
        }
        
        .gallery-cta-section {
            padding: 1.75rem 1.25rem;
        }
        
        .gallery-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .gallery-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .gallery-cta-buttons-container {
            margin-top: 0.75rem;
            flex-direction: column;
            gap: 0.75rem;
        }

        .gallery-demo-button,
        .gallery-trial-button,
        .gallery-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 160px;
            width: 100%;
            max-width: 250px;
        }
        
        /* Mobile badge visibility fix */
        .tab-badge {
            background: rgba(0, 0, 0, 0.8) !important;
            color: white !important;
        }
        
        .tab-btn.active .tab-badge {
            background: rgba(255, 255, 255, 0.95) !important;
            color: #1e293b !important;
        }
    }

    @media (max-width: 480px) {
        .gallery-header {
            padding: 1.5rem 0.75rem; /* 🚨 Features जस्तै बनाउनुहोस् */
            margin: calc(var(--header-height, 70px) + 0.1rem) auto 0.75rem auto !important; /* 🚨 Features जस्तै बनाउनुहोस् */
        }
        
        .gallery-header h1 {
            font-size: 1.75rem; /* 🚨 Features जस्तै बनाउनुहोस् */
        }
        
        .gallery-header p {
            font-size: 0.9rem; /* 🚨 Features जस्तै बनाउनुहोस् */
        }
        
        .gallery-cta-wrapper {
            padding: 0.75rem 1rem 1rem 1rem;
        }
        
        .gallery-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .gallery-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .gallery-cta-section p {
            font-size: 0.9rem;
        }
    }

    /* 🚨 NEW: Active filter indicator */
    select[data-filter-active="true"] {
        border: 2px solid var(--gallery-primary) !important;
        background-color: rgba(30, 58, 138, 0.05) !important;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1) !important;
    }

    /* 🚨 NEW: Boys/Girls filter specific styles */
    #hostelFilter option[value="boys"] {
        color: #3b82f6;
        font-weight: 600;
    }

    #hostelFilter option[value="girls"] {
        color: #ec4899;
        font-weight: 600;
    }

    #hostelFilter option[value="boys"]:checked,
    #hostelFilter option[value="boys"]:selected {
        background-color: #3b82f6;
        color: white;
    }

    #hostelFilter option[value="girls"]:checked,
    #hostelFilter option[value="girls"]:selected {
        background-color: #ec4899;
        color: white;
    }
</style>
@endpush

@section('content')

<!-- Updated Hero Section - EXACT SAME SPACING AS FEATURES PAGE -->
<div class="gallery-header">
    <h1 class="nepali">हाम्रो ग्यालरी</h1>
    <p class="nepali">हाम्रा विभिन्न होस्टलहरूको कोठा, सुविधा र आवासीय क्षेत्रहरूको वास्तविक झलकहरू अन्वेषण गर्नुहोस्</p>
    
    <!-- 🆕 Meal Gallery Button -->
    <div class="meal-gallery-button-container">
        <a href="{{ route('menu-gallery') }}" class="meal-gallery-button nepali">
            <i class="fas fa-utensils"></i>
            🍛 खानाको ग्यालरी हेर्नुहोस्
        </a>
    </div>
</div>

<div class="gallery-content-wrapper">
    <!-- Tabs Interface -->
    <div class="gallery-tabs-container">
        <div class="gallery-tabs">
            <a href="{{ route('gallery.index', ['tab' => 'photos']) }}" 
               class="tab-btn nepali {{ $tab === 'photos' ? 'active' : '' }}"
               onclick="return handleTabClick(event, 'photos')">
                <i class="fas fa-images"></i>
                तस्बिरहरू
                <span class="tab-badge">{{ $metrics['total_photos'] ?? '150+' }}</span>
            </a>
            <a href="{{ route('gallery.index', ['tab' => 'videos']) }}" 
               class="tab-btn nepali {{ $tab === 'videos' ? 'active' : '' }}"
               onclick="return handleTabClick(event, 'videos')">
                <i class="fas fa-video"></i>
                भिडियोहरू
                <!-- FIX #1: Video badge color -->
                <span class="tab-badge">{{ $metrics['total_videos'] ?? '25+' }}</span>
            </a>
            <a href="{{ route('gallery.index', ['tab' => 'virtual-tours']) }}" 
               class="tab-btn nepali {{ $tab === 'virtual-tours' ? 'active' : '' }}"
               onclick="return handleTabClick(event, 'virtual-tours')">
                <i class="fas fa-360-degrees"></i>
                भर्चुअल टुर
            </a>
        </div>
    </div>

    <!-- Enhanced Filters Section with Hostel Filter -->
    <section class="gallery-filters">
        <div class="filter-container">
            <div class="filter-header">
                <h2 class="nepali">ग्यालरी फिल्टर गर्नुहोस्</h2>
                <p class="nepali">तपाईंले हेर्न चाहनुभएको विशेष प्रकारको मिडिया वा होस्टल चयन गर्नुहोस्</p>
            </div>
            
            <!-- Hostel Filter -->
<div class="hostel-filter">
    <label class="nepali" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
        <i class="fas fa-building"></i> होस्टल छान्नुहोस्:
    </label>
    <select id="hostelFilter" class="form-control" onchange="handleHostelFilterChange()"> <!-- 🚨 ADDED ONCHANGE -->
        <option value="">सबै होस्टलहरू</option>
        <option value="boys" {{ request('hostel_gender') == 'boys' ? 'selected' : '' }}>
            ब्वाइज होस्टलहरू
        </option>
        <option value="girls" {{ request('hostel_gender') == 'girls' ? 'selected' : '' }}>
            गर्ल्स होस्टलहरू
        </option>
        <optgroup label="विशेष होस्टलहरू">
            @foreach($hostels as $hostel)
                <option value="{{ $hostel->id }}" 
                        {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>
                    {{ $hostel->name }}
                </option>
            @endforeach
        </optgroup>
    </select>
</div>

            <!-- Video Categories Filter (Only for videos tab) -->
            @if($tab === 'videos')
                <div class="video-categories-filter">
                    <label class="nepali" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                        <i class="fas fa-filter"></i> भिडियो प्रकार:
                    </label>
                    <div class="video-categories">
                        <button class="video-category-btn {{ !request('video_category') || request('video_category') == 'all' ? 'active' : '' }}" 
                                data-category="all"
                                onclick="handleVideoCategoryClick('all')">
                            सबै
                        </button>
                        @foreach($videoCategories as $key => $name)
                            @if($key !== 'all')
                                <button class="video-category-btn {{ request('video_category') == $key ? 'active' : '' }}" 
                                        data-category="{{ $key }}"
                                        onclick="handleVideoCategoryClick('{{ $key }}')">
                                    {{ $name }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="filter-controls">
                <div class="filter-categories">
                    @if($tab === 'photos')
                        <button class="filter-btn active nepali" data-filter="all">सबै</button>
                        <button class="filter-btn nepali" data-filter="१ सिटर कोठा">१ सिटर कोठा</button>
                        <button class="filter-btn nepali" data-filter="२ सिटर कोठा">२ सिटर कोठा</button>
                        <button class="filter-btn nepali" data-filter="३ सिटर कोठा">३ सिटर कोठा</button>
                        <button class="filter-btn nepali" data-filter="४ सिटर कोठा">४ सिटर कोठा</button>
                        <button class="filter-btn nepali" data-filter="लिभिङ रूम">लिभिङ रूम</button>
                        <button class="filter-btn nepali" data-filter="बाथरूम">बाथरूम</button>
                        <button class="filter-btn nepali" data-filter="भान्सा">भान्सा</button>
                        <button class="filter-btn nepali" data-filter="अध्ययन कोठा">अध्ययन कोठा</button>
                        <button class="filter-btn nepali" data-filter="कार्यक्रम">कार्यक्रम</button>
                    @elseif($tab === 'videos')
                        <!-- FIX #3: Changed to English keys with Nepali display text -->
                        <button class="filter-btn active nepali" data-filter="all">सबै भिडियो</button>
                        <button class="filter-btn nepali" data-filter="hostel_tour">होस्टल टुर</button>
                        <button class="filter-btn nepali" data-filter="room_tour">कोठा टुर</button>
                        <button class="filter-btn nepali" data-filter="student_life">विद्यार्थी जीवन</button>
                        <button class="filter-btn nepali" data-filter="virtual_tour">भर्चुअल टुर</button>
                        <button class="filter-btn nepali" data-filter="student_experience">विद्यार्थी अनुभव</button>
                        <button class="filter-btn nepali" data-filter="facilities">सुविधाहरू</button>
                    @endif
                </div>
                
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input nepali" placeholder="कोठा, सुविधा, होस्टल वा स्थान खोज्नुहोस्...">
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ FIXED: Main Gallery Section with Tabs Content -->
    <section class="gallery-main">
        <div class="gallery-container">
            <!-- Photos Tab Content -->
            <div class="tab-content {{ $tab === 'photos' ? 'active' : '' }}" id="photos-tab">
                <div class="gallery-grid">
                    @forelse($galleries as $gallery)
                        @php
                            // Check if it's a gallery item and not pagination data
                            if (!is_object($gallery) && !is_array($gallery)) {
                                continue;
                            }
                            
                            $isArray = is_array($gallery);
                            $mediaType = $isArray ? ($gallery['media_type'] ?? null) : ($gallery->media_type ?? null);
                            
                            // Skip if not photo or if media_type is null
                            if (!$mediaType || $mediaType !== 'photo') {
                                continue;
                            }
                            
                            $categoryNepali = $isArray ? ($gallery['category_nepali'] ?? '') : ($gallery->category_nepali ?? '');
                            $title = $isArray ? ($gallery['title'] ?? '') : ($gallery->title ?? '');
                            $description = $isArray ? ($gallery['description'] ?? '') : ($gallery->description ?? '');
                            $createdAt = $isArray ? \Carbon\Carbon::parse($gallery['created_at'] ?? now()) : ($gallery->created_at ?? now());
                            $hostelName = $isArray ? ($gallery['hostel_name'] ?? '') : ($gallery->hostel_name ?? '');
                            $hostelId = $isArray ? ($gallery['hostel_id'] ?? '') : ($gallery->hostel_id ?? '');
                            $hostelSlug = $isArray ? ($gallery['hostel_slug'] ?? '') : ($gallery->hostel_slug ?? '');
                            $room = $isArray ? ($gallery['room'] ?? null) : ($gallery->room ?? null);
                            $roomNumber = $room ? ($isArray ? ($room['room_number'] ?? '') : ($room->room_number ?? '')) : '';
                            $thumbnailUrl = $isArray ? ($gallery['thumbnail_url'] ?? ($gallery['media_url'] ?? '')) : ($gallery->thumbnail_url ?? ($gallery->media_url ?? ''));
                            $mediaUrl = $isArray ? ($gallery['media_url'] ?? '') : ($gallery->media_url ?? '');
                            $hdAvailable = $isArray ? ($gallery['hd_available'] ?? false) : ($gallery->hd_available ?? false);
                            $hdUrl = $isArray ? ($gallery['hd_url'] ?? $mediaUrl) : ($gallery->hd_url ?? $mediaUrl);
                            
                            // ✅ FIXED: Simplify gender detection as per instructions
                            $hostelGender = $isArray ? 
                                ($gallery['hostel_gender'] ?? 'mixed') : 
                                ($gallery->hostel_gender ?? 'mixed');
                        @endphp
                        
                        <div class="gallery-item" 
                             data-category="{{ $categoryNepali }}"
                             data-title="{{ $title }}"
                             data-description="{{ $description }}"
                             data-date="{{ $createdAt->format('Y-m-d') }}"
                             data-hostel="{{ $hostelName }}"
                             data-hostel-id="{{ $hostelId }}"
                             data-hostel-slug="{{ $hostelSlug }}"
                             data-hostel-name="{{ strtolower($hostelName) }}"
                             data-hostel-gender="{{ $hostelGender }}"
                             data-room-number="{{ $roomNumber }}"
                             data-media-type="{{ $mediaType }}"
                             data-media-url="{{ $mediaUrl }}"
                             data-hd-url="{{ $hdUrl }}"
                             data-hd-available="{{ $hdAvailable ? 'true' : 'false' }}"
                             data-id="{{ $isArray ? ($gallery['id'] ?? '') : ($gallery->id ?? '') }}">

                            <!-- Image Container with Fixed Aspect Ratio -->
                            <div class="gallery-media-container">
                                <!-- Enhanced Hostel Link - FIX #2: Remove text truncation -->
                                @if($hostelSlug)
                                <a href="{{ route('hostels.show', $hostelSlug) }}" 
                                   class="hostel-link-enhanced" 
                                   title="{{ $hostelName }} मा जानुहोस्">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span class="nepali">{{ $hostelName }}</span>
                                </a>
                                @endif

                                <!-- HD Badge -->
                                @if($hdAvailable)
                                <div class="hd-badge" title="HD उपलब्ध">
                                    <i class="fas fa-hd"></i>
                                    <span>HD</span>
                                </div>
                                @endif

                                <img src="{{ $thumbnailUrl }}" 
                                     alt="{{ $title }}" 
                                     class="gallery-media" 
                                     loading="lazy"
                                     data-src="{{ $mediaUrl }}"
                                     data-hd-src="{{ $hdUrl }}">

                                <!-- Media Overlay -->
                                <div class="media-overlay">
                                    <div class="media-title nepali">{{ $title }}</div>
                                    <div class="media-description nepali">{{ Str::limit($description, 60) }}</div>
                                    <div class="media-meta">
                                        <span class="media-category nepali">{{ $categoryNepali }}</span>
                                        <span class="media-date">{{ $createdAt->format('Y-m-d') }}</span>
                                    </div>
                                    <!-- View Details Button - FIX #4: Reduced button size -->
                                    @if($hostelSlug)
                                    <a href="{{ route('hostels.show', $hostelSlug) }}" 
                                       class="quick-view-btn nepali">
                                        <i class="fas fa-info-circle"></i>
                                        होस्टल विवरण हेर्नुहोस्
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                    <div class="no-results">
                        <i class="fas fa-images"></i>
                        <h3 class="nepali">कुनै तस्बिर फेला परेन</h3>
                        <p class="nepali">हाल कुनै तस्बिर उपलब्ध छैन। कृपया पछि फर्कनुहोस्।</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- ✅ FIXED: Videos Tab Content - SERVER-SIDE ONLY -->
            <div class="tab-content {{ $tab === 'videos' ? 'active' : '' }}" id="videos-tab">
                <div class="gallery-grid videos-grid">
                    @if($tab === 'videos')
                        @php
                            $hasVideos = false;
                            $videoCount = 0;
                        @endphp
                        
                        @foreach($galleries as $gallery)
                            @php
                                if (!is_object($gallery) && !is_array($gallery)) continue;
                                
                                $isArray = is_array($gallery);
                                $mediaType = $isArray ? ($gallery['media_type'] ?? null) : ($gallery->media_type ?? null);
                                
                                if (!$mediaType || !in_array($mediaType, ['external_video', 'local_video'])) {
                                    continue;
                                }
                                
                                $hasVideos = true;
                                $videoCount++;
                                
                                if ($isArray) {
                                    $id = $gallery['id'] ?? '';
                                    $category = $gallery['category'] ?? '';
                                    $categoryNepali = $gallery['category_nepali'] ?? $category;
                                    $title = $gallery['title'] ?? '';
                                    $description = $gallery['description'] ?? '';
                                    $createdAt = \Carbon\Carbon::parse($gallery['created_at'] ?? now());
                                    $hostelName = $gallery['hostel_name'] ?? '';
                                    $hostelId = $gallery['hostel_id'] ?? '';
                                    $hostelSlug = $gallery['hostel_slug'] ?? '';
                                    $thumbnailUrl = $gallery['thumbnail_url'] ?? ($gallery['media_url'] ?? asset('images/video-thumbnail.jpg'));
                                    $mediaUrl = $gallery['media_url'] ?? '';
                                    $youtubeEmbedUrl = $gallery['youtube_embed_url'] ?? '';
                                    $videoDuration = $gallery['video_duration'] ?? '';
                                    $videoResolution = $gallery['video_resolution'] ?? '';
                                    $is360Video = $gallery['is_360_video'] ?? false;
                                    $hostelGender = $gallery['hostel_gender'] ?? 'mixed';
                                } else {
                                    $id = $gallery->id ?? '';
                                    $category = $gallery->category ?? '';
                                    $categoryNepali = $gallery->category_nepali ?? $category;
                                    $title = $gallery->title ?? '';
                                    $description = $gallery->description ?? '';
                                    $createdAt = $gallery->created_at;
                                    $hostelName = $gallery->hostel_name ?? '';
                                    $hostelId = $gallery->hostel_id ?? '';
                                    $hostelSlug = $gallery->hostel_slug ?? '';
                                    $thumbnailUrl = $gallery->thumbnail_url ?? ($gallery->media_url ?? asset('images/video-thumbnail.jpg'));
                                    $mediaUrl = $gallery->media_url ?? '';
                                    $youtubeEmbedUrl = $gallery->youtube_embed_url ?? '';
                                    $videoDuration = $gallery->video_duration_formatted ?? '';
                                    $videoResolution = $gallery->video_resolution ?? '';
                                    $is360Video = $gallery->is_360_video ?? false;
                                    $hostelGender = $gallery->hostel_gender ?? 'mixed';
                                }
                                
                                $videoModalUrl = '';
                                if ($mediaType === 'external_video' && $youtubeEmbedUrl) {
                                    $videoModalUrl = $youtubeEmbedUrl . '?autoplay=1&rel=0&controls=1&showinfo=0';
                                } elseif ($mediaType === 'local_video' && $mediaUrl) {
                                    $videoModalUrl = $mediaUrl;
                                }
                                
                                if (empty($thumbnailUrl) || $thumbnailUrl == asset('')) {
                                    $thumbnailUrl = asset('images/video-thumbnail.jpg');
                                }
                            @endphp
                            
                            <div class="video-card server-loaded" 
                                 data-category="{{ $category }}"
                                 data-title="{{ $title }}"
                                 data-description="{{ $description }}"
                                 data-date="{{ $createdAt->format('Y-m-d') }}"
                                 data-hostel="{{ $hostelName }}"
                                 data-hostel-id="{{ $hostelId }}"
                                 data-hostel-slug="{{ $hostelSlug }}"
                                 data-hostel-name="{{ strtolower($hostelName) }}"
                                 data-hostel-gender="{{ $hostelGender }}"
                                 data-room-number="N/A"
                                 data-media-type="{{ $mediaType }}"
                                 data-video-modal-url="{{ $videoModalUrl }}"
                                 data-video-duration="{{ $videoDuration }}"
                                 data-video-resolution="{{ $videoResolution }}"
                                 data-is-360="{{ $is360Video ? 'true' : 'false' }}"
                                 data-id="{{ $id }}">

                                <div class="video-thumbnail">
                                    <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" loading="lazy">
                                    <div class="play-button">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    
                                    @if($videoDuration)
                                    <div class="video-duration">{{ $videoDuration }}</div>
                                    @endif

                                    @if($is360Video)
                                    <div class="video-badge-360">
                                        <i class="fas fa-360-degrees"></i>
                                        360°
                                    </div>
                                    @endif

                                    @if($hostelSlug)
                                    <a href="{{ route('hostels.show', $hostelSlug) }}" 
                                       class="hostel-link-enhanced" 
                                       style="top: auto; bottom: 10px; left: 10px;"
                                       title="{{ $hostelName }} मा जानुहोस्">
                                        <i class="fas fa-external-link-alt"></i>
                                        <span class="nepali">{{ $hostelName }}</span>
                                    </a>
                                    @endif
                                </div>

                                <div class="video-info">
                                    <h3 class="nepali">{{ $title }}</h3>
                                    <div class="video-meta">
                                        <span class="nepali">{{ $categoryNepali }}</span>
                                        @if($videoResolution)
                                        <span class="video-resolution">{{ $videoResolution }}</span>
                                        @endif
                                    </div>
                                    <p class="video-description nepali">{{ Str::limit($description, 80) }}</p>
                                    @if($hostelSlug)
                                    <a href="{{ route('hostels.show', $hostelSlug) }}" class="video-hostel-link nepali">
                                        <i class="fas fa-building"></i>
                                        {{ $hostelName }} को विवरण हेर्नुहोस्
                                    </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        @if(!$hasVideos)
                        <div class="no-results">
                            <i class="fas fa-video"></i>
                            <h3 class="nepali">कुनै भिडियो फेला परेन</h3>
                            <p class="nepali">हाल कुनै भिडियो उपलब्ध छैन। कृपया पछि फर्कनुहोस्।</p>
                        </div>
                        @endif
                        
                        <!-- ✅ HIDDEN: Videos counter for JS -->
                        <div id="videos-count" data-count="{{ $videoCount }}" style="display: none;"></div>
                        
                    @else
                        <!-- Placeholder only shown when tab is inactive -->
                        <div class="videos-placeholder">
                            <div class="spinner"></div>
                            <p class="nepali">भिडियोहरू लोड हुँदैछ...</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pagination -->
            @if(count($galleries) > 0 && method_exists($galleries, 'links'))
            <div class="pagination-container">
                {{ $galleries->links() }}
            </div>
            @endif

            <!-- No Results Message -->
            <div class="no-results hidden">
                <i class="fas fa-search"></i>
                <h3 class="nepali">कुनै परिणाम फेला परेन</h3>
                <p class="nepali">तपाईंको खोजसँग मिल्ने कुनै ग्यालरी आइटम फेला परेन। कृपया अर्को खोज प्रयास गर्नुहोस्।</p>
            </div>

            <!-- Loading Indicator -->
            <div class="gallery-loading hidden">
                <div class="loading-spinner"></div>
                <p class="nepali">ग्यालरी आइटमहरू लोड हुँदैछ...</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="gallery-stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $metrics['total_students'] ?? '500+' }}</div>
                <div class="stat-label nepali">खुसी विद्यार्थीहरू</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $metrics['total_hostels'] ?? '25' }}</div>
                <div class="stat-label nepali">सहयोगी होस्टल</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $metrics['total_videos'] ?? '25+' }}</div>
                <div class="stat-label nepali">भिडियो टुरहरू</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $metrics['total_photos'] ?? '150+' }}</div>
                <div class="stat-label nepali">HD तस्बिरहरू</div>
            </div>
        </div>
    </section>

    <!-- 🚨 UPDATED GALLERY CTA SECTION - MATCHING FEATURES PAGE DESIGN -->
    <div class="gallery-cta-wrapper">
        <section class="gallery-cta-section">
            <h2 class="nepali">तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
            <p class="nepali">७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्</p>
            
            <div class="gallery-cta-buttons-container">
                <!-- BUTTON 1: DEMO (Orange Gradient with play icon) -->
                <a href="{{ route('demo') }}" class="gallery-demo-button nepali">
                    <i class="fas fa-play-circle"></i> डेमो हेर्नुहोस्
                </a>
                
                <!-- BUTTON 2: FREE TRIAL (White Background with rocket icon) -->
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
                        <button class="gallery-outline-button nepali" disabled>
                            <i class="fas fa-check-circle"></i> पहिले नै सदस्यता
                        </button>
                    @else
                        <form action="{{ route('subscription.start-trial') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="gallery-trial-button nepali">
                                <i class="fas fa-rocket"></i> निःशुल्क परीक्षण
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('register.organization', ['plan' => 'starter']) }}" 
                       class="gallery-trial-button nepali">
                        <i class="fas fa-rocket"></i> निःशुल्क परीक्षण सुरु गर्नुहोस्
                    </a>
                @endauth
                
                <!-- BUTTON 3: PRICING (Outline with tags icon) -->
                @php
                    $pricingRoute = null;
                    
                    if (Route::has('pricing')) {
                        $pricingRoute = route('pricing');
                    } elseif (Route::has('pricing.index')) {
                        $pricingRoute = route('pricing.index');
                    } elseif (Route::has('frontend.pricing')) {
                        $pricingRoute = route('frontend.pricing');
                    } elseif (Route::has('plans')) {
                        $pricingRoute = route('plans');
                    } else {
                        $pricingRoute = url('/pricing');
                    }
                @endphp
                
                <a href="{{ $pricingRoute }}" 
                   class="gallery-outline-button nepali">
                    <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                </a>
            </div>
        </section>
    </div>
</div>

<!-- Enhanced Modal with Video Support -->
<div class="gallery-modal">
    <button class="modal-close">
        <i class="fas fa-times"></i>
    </button>
    <button class="modal-nav modal-prev">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="modal-nav modal-next">
        <i class="fas fa-chevron-right"></i>
    </button>
    
    <div class="modal-content">
        <!-- Media will be inserted here by JavaScript -->
    </div>
    
    <div class="modal-info">
        <div class="modal-title nepali"></div>
        <div class="modal-description nepali"></div>
        <div class="modal-meta">
            <span class="modal-category nepali"></span>
            <span class="modal-date"></span>
            <span class="modal-hostel nepali"></span>
            <!-- 🚨 Room number अब मोडलमा मात्र देखाइनेछ -->
            <span class="modal-room nepali"></span>
        </div>
        <div class="modal-actions">
            <a href="#" class="modal-hostel-link nepali" style="display: none;">
                <i class="fas fa-external-link-alt"></i>
                होस्टल विवरण हेर्नुहोस्
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/gallery.js'])
<script>
// Simple function to handle video modal
function openVideoModal(videoCard) {
    const modal = document.querySelector('.gallery-modal');
    const modalContent = modal.querySelector('.modal-content');
    const modalTitle = modal.querySelector('.modal-title');
    const modalDescription = modal.querySelector('.modal-description');
    
    // Get video data
    const videoModalUrl = videoCard.getAttribute('data-video-modal-url');
    const title = videoCard.getAttribute('data-title');
    const description = videoCard.getAttribute('data-description');
    
    // Set modal content
    modalTitle.textContent = title;
    modalDescription.textContent = description;
    
    // Clear and add video
    modalContent.innerHTML = '';
    
    if (videoModalUrl && videoModalUrl.includes('youtube.com')) {
        const iframe = document.createElement('iframe');
        iframe.src = videoModalUrl;
        iframe.width = '100%';
        iframe.height = '500';
        iframe.allowFullscreen = true;
        iframe.style.border = 'none';
        modalContent.appendChild(iframe);
    } else if (videoModalUrl) {
        const video = document.createElement('video');
        video.src = videoModalUrl;
        video.controls = true;
        video.autoplay = true;
        video.style.width = '100%';
        modalContent.appendChild(video);
    }
    
    // Show modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// ✅ FIXED: Videos placeholder hide logic - ENHANCED
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - checking videos placeholder');
    
    // Function to hide all spinners and placeholders
    function hideAllSpinners() {
        // Hide the videos placeholder
        const placeholder = document.querySelector('.videos-placeholder');
        if (placeholder) {
            placeholder.style.display = 'none';
            placeholder.classList.add('hidden');
        }
        
        // Hide any other loading indicators
        const spinners = document.querySelectorAll('.spinner, .gallery-loading');
        spinners.forEach(spinner => {
            spinner.style.display = 'none';
            spinner.classList.add('hidden');
        });
    }
    
    // Check if we're on videos tab
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam === 'videos') {
        console.log('On videos tab');
        
        // Get the video count from the hidden div
        const videosCountElement = document.getElementById('videos-count');
        const serverVideoCount = videosCountElement ? parseInt(videosCountElement.getAttribute('data-count')) : 0;
        const clientVideoCount = document.querySelectorAll('.video-card').length;
        
        const hasVideos = serverVideoCount > 0 || clientVideoCount > 0;
        
        console.log(`Found ${serverVideoCount} server videos and ${clientVideoCount} client video cards`);
        
        // If videos exist, IMMEDIATELY hide placeholder
        if (hasVideos) {
            console.log('Hiding videos placeholder immediately');
            hideAllSpinners();
        } else {
            // If no videos, we still hide the spinner because we show a "no results" message
            hideAllSpinners();
        }
    } else {
        // For other tabs, hide the videos placeholder (it's in the videos tab which is inactive)
        hideAllSpinners();
    }
    
    // Also check after DOM is fully ready
    setTimeout(function() {
        hideAllSpinners();
    }, 100);
});

// ✅ FIXED: Also check when window loads
window.addEventListener('load', function() {
    console.log('Window loaded - final check for videos placeholder');
    
    // Re-run the hide function
    const placeholder = document.querySelector('.videos-placeholder');
    if (placeholder) {
        placeholder.style.display = 'none';
        placeholder.classList.add('hidden');
    }
    
    // Hide any other loading indicators
    const spinners = document.querySelectorAll('.spinner, .gallery-loading');
    spinners.forEach(spinner => {
        spinner.style.display = 'none';
        spinner.classList.add('hidden');
    });
});

// ✅ FIXED: Tab click handler
function handleTabClick(event, tabName) {
    // Only prevent default if it's not videos tab (since videos uses server-side)
    if (tabName === 'photos') {
        event.preventDefault();
    }
    return true;
}

// ✅ FIXED: SIMPLE AND RELIABLE SERVER-SIDE HOSTEL FILTER FIX
window.handleHostelFilterChange = function() {
    console.log('Hostel filter changed');
    const hostelFilter = document.getElementById('hostelFilter');
    if (!hostelFilter) {
        console.error('Hostel filter not found');
        return false;
    }
    
    const selectedValue = hostelFilter.value;
    console.log('Selected value:', selectedValue);
    
    // Get current URL and parameters
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const params = url.searchParams;
    
    // Remove pagination when changing filters
    params.delete('page');
    
    // Clear all existing hostel filters
    params.delete('hostel_gender');
    params.delete('hostel_id');
    
    // Apply new filter
    if (selectedValue === 'boys' || selectedValue === 'girls') {
        params.set('hostel_gender', selectedValue);
        console.log('Setting hostel_gender to:', selectedValue);
    } else if (selectedValue && selectedValue !== '') {
        params.set('hostel_id', selectedValue);
        console.log('Setting hostel_id to:', selectedValue);
    }
    
    // Update tab parameter to stay on photos tab
    if (!params.has('tab')) {
        params.set('tab', 'photos');
    }
    
    // Navigate to the new URL
    console.log('Redirecting to:', url.toString());
    window.location.href = url.toString();
    return false;
};

// ✅ FIXED: Set initial value from URL when DOM loads
document.addEventListener('DOMContentLoaded', function() {
    const hostelFilter = document.getElementById('hostelFilter');
    if (hostelFilter) {
        const urlParams = new URLSearchParams(window.location.search);
        const hostelGender = urlParams.get('hostel_gender');
        const hostelId = urlParams.get('hostel_id');
        
        console.log('Current URL params:', { hostelGender, hostelId });
        
        if (hostelGender === 'boys' || hostelGender === 'girls') {
            hostelFilter.value = hostelGender;
        } else if (hostelId) {
            hostelFilter.value = hostelId;
        }
    }
});

// ✅ FIXED: Video category click handler
function handleVideoCategoryClick(category) {
    console.log('Video category clicked:', category);
    
    // Get current URL and parameters
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const params = url.searchParams;
    
    // Remove pagination when changing filters
    params.delete('page');
    
    // Update or remove video_category parameter
    if (category === 'all') {
        params.delete('video_category');
    } else {
        params.set('video_category', category);
    }
    
    // Navigate to the new URL
    console.log('Redirecting to:', url.toString());
    window.location.href = url.toString();
    return false;
}
</script>
@endpush