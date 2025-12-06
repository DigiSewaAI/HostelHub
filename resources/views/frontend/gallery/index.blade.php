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

    .tab-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
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
        font-size: 0.9rem;
        transition: all 0.3s ease;
        padding: 0.5rem 0;
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

    /* Enhanced Hostel Link */
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
    }

    .hostel-link-enhanced:hover {
        background: rgba(30, 58, 138, 1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        color: white;
    }

    /* Video Loading State */
    .videos-loading {
        text-align: center;
        padding: 3rem;
        grid-column: 1 / -1;
    }

    .videos-loading .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--gallery-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
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
               class="tab-btn nepali {{ $tab === 'photos' ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                तस्बिरहरू
                <span class="tab-badge">{{ $metrics['total_photos'] ?? '150+' }}</span>
            </a>
            <a href="{{ route('gallery.index', ['tab' => 'videos']) }}" 
               class="tab-btn nepali {{ $tab === 'videos' ? 'active' : '' }}">
                <i class="fas fa-video"></i>
                भिडियोहरू
                <span class="tab-badge">{{ $metrics['total_videos'] ?? '25+' }}</span>
            </a>
            <a href="{{ route('gallery.index', ['tab' => 'virtual-tours']) }}" 
               class="tab-btn nepali {{ $tab === 'virtual-tours' ? 'active' : '' }}">
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
                <select id="hostelFilter" class="form-control">
                    <option value="">सबै होस्टलहरू</option>
                    @foreach($hostels as $hostel)
                        <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Video Categories Filter (Only for videos tab) -->
            @if($tab === 'videos')
            <div class="video-categories-filter">
                <label class="nepali" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                    <i class="fas fa-filter"></i> भिडियो प्रकार:
                </label>
                <div class="video-categories">
                    <button class="video-category-btn active" data-category="all">सबै</button>
                    @foreach($videoCategories as $key => $name)
                        <button class="video-category-btn" data-category="{{ $key }}">{{ $name }}</button>
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
                        <button class="filter-btn active nepali" data-filter="all">सबै भिडियो</button>
                        <button class="filter-btn nepali" data-filter="होस्टल टुर">होस्टल टुर</button>
                        <button class="filter-btn nepali" data-filter="कोठा टुर">कोठा टुर</button>
                        <button class="filter-btn nepali" data-filter="विद्यार्थी जीवन">विद्यार्थी जीवन</button>
                        <button class="filter-btn nepali" data-filter="भर्चुअल टुर">भर्चुअल टुर</button>
                        <button class="filter-btn nepali" data-filter="विद्यार्थी अनुभव">विद्यार्थी अनुभव</button>
                        <button class="filter-btn nepali" data-filter="सुविधाहरू">सुविधाहरू</button>
                    @endif
                </div>
                
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input nepali" placeholder="कोठा, सुविधा, होस्टल वा स्थान खोज्नुहोस्...">
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ UPDATED: Main Gallery Section with Tabs Content -->
    <section class="gallery-main">
        <div class="gallery-container">
            <!-- Photos Tab Content -->
            <div class="tab-content {{ $tab === 'photos' ? 'active' : '' }}" id="photos-tab">
                <div class="gallery-grid">
                    @forelse($galleries as $gallery)
                    @php
                        $isArray = is_array($gallery);
                        $mediaType = $isArray ? $gallery['media_type'] : $gallery->media_type;
                        $categoryNepali = $isArray ? $gallery['category_nepali'] : $gallery->category_nepali;
                        $title = $isArray ? $gallery['title'] : $gallery->title;
                        $description = $isArray ? $gallery['description'] : $gallery->description;
                        $createdAt = $isArray ? \Carbon\Carbon::parse($gallery['created_at']) : $gallery->created_at;
                        $hostelName = $isArray ? $gallery['hostel_name'] : $gallery->hostel_name;
                        $hostelId = $isArray ? $gallery['hostel_id'] : $gallery->hostel_id;
                        $hostelSlug = $isArray ? ($gallery['hostel_slug'] ?? '') : ($gallery->hostel->slug ?? '');
                        $room = $isArray ? ($gallery['room'] ?? null) : $gallery->room;
                        $roomNumber = $room ? ($isArray ? $room['room_number'] : $room->room_number) : '';
                        $thumbnailUrl = $isArray ? ($gallery['thumbnail_url'] ?? $gallery['media_url']) : ($gallery->thumbnail_url ?? $gallery->media_url);
                        $mediaUrl = $isArray ? $gallery['media_url'] : $gallery->media_url;
                        $hdAvailable = $isArray ? ($gallery['is_hd_available'] ?? false) : ($gallery->is_hd_available ?? false);
                        $hdUrl = $isArray ? ($gallery['hd_url'] ?? $mediaUrl) : ($gallery->hd_image_url ?? $mediaUrl);
                        $youtubeEmbedUrl = $isArray ? ($gallery['youtube_embed_url'] ?? '') : $gallery->youtube_embed_url;
                    @endphp
                    
                    @if($mediaType === 'photo')
                    <div class="gallery-item" 
                         data-category="{{ $categoryNepali }}"
                         data-title="{{ $title }}"
                         data-description="{{ $description }}"
                         data-date="{{ $createdAt->format('Y-m-d') }}"
                         data-hostel="{{ $hostelName }}"
                         data-hostel-id="{{ $hostelId }}"
                         data-hostel-slug="{{ $hostelSlug }}"
                         data-room-number="{{ $roomNumber }}"
                         data-media-type="{{ $mediaType }}"
                         data-media-url="{{ $mediaUrl }}"
                         data-hd-url="{{ $hdUrl }}"
                         data-hd-available="{{ $hdAvailable ? 'true' : 'false' }}"
                         data-id="{{ $isArray ? $gallery['id'] : $gallery->id }}">

                        <!-- Image Container with Fixed Aspect Ratio -->
                        <div class="gallery-media-container">
                            <!-- Enhanced Hostel Link -->
                            @if($hostelSlug)
                            <a href="{{ route('hostels.show', $hostelSlug) }}" 
                               class="hostel-link-enhanced" 
                               title="{{ $hostelName }} मा जानुहोस्">
                                <i class="fas fa-external-link-alt"></i>
                                <span class="nepali">{{ Str::limit($hostelName, 15) }}</span>
                            </a>
                            @endif

                            <!-- HD Badge -->
                            @if($hdAvailable)
                            <div class="hd-badge" title="HD उपलब्ध">
                                <i class="fas fa-hd"></i>
                                <span>HD</span>
                            </div>
                            @endif

                            <!-- Room Number Badge -->
                            @if($roomNumber)
                            <div class="room-number-badge" title="कोठा नम्बर: {{ $roomNumber }}">
                                <i class="fas fa-door-open"></i>
                                <span class="nepali">कोठा {{ $roomNumber }}</span>
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
                                <!-- View Details Button -->
                                @if($hostelSlug)
                                <a href="{{ route('hostels.show', $hostelSlug) }}" 
                                   class="quick-view-btn nepali" 
                                   style="position: relative; transform: none; left: 0; bottom: 0; margin-top: 0.5rem;">
                                    <i class="fas fa-info-circle"></i>
                                    होस्टल विवरण हेर्नुहोस्
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="no-results">
                        <i class="fas fa-images"></i>
                        <h3 class="nepali">कुनै तस्बिर फेला परेन</h3>
                        <p class="nepali">हाल कुनै तस्बिर उपलब्ध छैन। कृपया पछि फर्कनुहोस्।</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Videos Tab Content -->
            <div class="tab-content {{ $tab === 'videos' ? 'active' : '' }}" id="videos-tab">
                <div class="gallery-grid videos-grid">
                    @forelse($galleries as $gallery)
                    @php
                        $isArray = is_array($gallery);
                        $mediaType = $isArray ? $gallery['media_type'] : $gallery->media_type;
                        $categoryNepali = $isArray ? $gallery['category_nepali'] : $gallery->category_nepali;
                        $title = $isArray ? $gallery['title'] : $gallery->title;
                        $description = $isArray ? $gallery['description'] : $gallery->description;
                        $createdAt = $isArray ? \Carbon\Carbon::parse($gallery['created_at']) : $gallery->created_at;
                        $hostelName = $isArray ? $gallery['hostel_name'] : $gallery->hostel_name;
                        $hostelId = $isArray ? $gallery['hostel_id'] : $gallery->hostel_id;
                        $hostelSlug = $isArray ? ($gallery['hostel_slug'] ?? '') : ($gallery->hostel->slug ?? '');
                        $room = $isArray ? ($gallery['room'] ?? null) : $gallery->room;
                        $roomNumber = $room ? ($isArray ? $room['room_number'] : $room->room_number) : '';
                        $thumbnailUrl = $isArray ? ($gallery['thumbnail_url'] ?? $gallery['media_url']) : ($gallery->thumbnail_url ?? $gallery->media_url);
                        $mediaUrl = $isArray ? $gallery['media_url'] : $gallery->media_url;
                        $youtubeEmbedUrl = $isArray ? ($gallery['youtube_embed_url'] ?? '') : $gallery->youtube_embed_url;
                        $videoDuration = $isArray ? ($gallery['video_duration'] ?? '') : ($gallery->video_duration_formatted ?? '');
                        $videoResolution = $isArray ? ($gallery['video_resolution'] ?? '') : ($gallery->video_resolution ?? '');
                        $is360Video = $isArray ? ($gallery['is_360_video'] ?? false) : ($gallery->is_360_video ?? false);
                    @endphp
                    
                    @if(in_array($mediaType, ['external_video', 'local_video']))
                    <div class="video-card" 
                         data-category="{{ $categoryNepali }}"
                         data-title="{{ $title }}"
                         data-description="{{ $description }}"
                         data-date="{{ $createdAt->format('Y-m-d') }}"
                         data-hostel="{{ $hostelName }}"
                         data-hostel-id="{{ $hostelId }}"
                         data-hostel-slug="{{ $hostelSlug }}"
                         data-room-number="{{ $roomNumber }}"
                         data-media-type="{{ $mediaType }}"
                         @if($mediaType === 'external_video' && $youtubeEmbedUrl)
                         data-youtube-embed="{{ $youtubeEmbedUrl }}"
                         @elseif($mediaType === 'local_video')
                         data-video-url="{{ $mediaUrl }}"
                         @endif
                         data-video-duration="{{ $videoDuration }}"
                         data-video-resolution="{{ $videoResolution }}"
                         data-is-360="{{ $is360Video ? 'true' : 'false' }}">

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
                                <span class="nepali">{{ Str::limit($hostelName, 12) }}</span>
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
                    @endif
                    @empty
                    <div class="no-results">
                        <i class="fas fa-video"></i>
                        <h3 class="nepali">कुनै भिडियो फेला परेन</h3>
                        <p class="nepali">हाल कुनै भिडियो उपलब्ध छैन। कृपया पछि फर्कनुहोस्।</p>
                    </div>
                    @endforelse
                </div>

                <!-- Videos Loading State -->
                <div class="videos-loading" style="display: none;">
                    <div class="spinner"></div>
                    <p class="nepali">भिडियोहरू लोड हुँदैछ...</p>
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
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let currentTab = '{{ $tab }}';
    let videoPage = 1;
    let isLoadingVideos = false;
    let hasMoreVideos = true;
    
    // Tab switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.classList.contains('active')) {
                e.preventDefault();
                return;
            }
            
            // Remove active class from all tabs
            tabBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Update current tab
            const newTab = this.getAttribute('href').includes('videos') ? 'videos' : 
                          this.getAttribute('href').includes('virtual-tours') ? 'virtual-tours' : 'photos';
            currentTab = newTab;
        });
    });

    // Enhanced gallery filtering with HD support
    const galleryItems = document.querySelectorAll('.gallery-item');
    const videoCards = document.querySelectorAll('.video-card');
    
    function filterContent() {
        const selectedHostelId = document.getElementById('hostelFilter')?.value || '';
        const searchTerm = document.querySelector('.search-input').value.toLowerCase().trim();
        const activeFilter = document.querySelector('.filter-btn.active')?.getAttribute('data-filter') || 'all';
        
        let visibleCount = 0;
        
        // Filter based on current tab
        if (currentTab === 'photos') {
            galleryItems.forEach(item => {
                const itemHostelId = item.getAttribute('data-hostel-id');
                const title = item.getAttribute('data-title').toLowerCase();
                const description = item.getAttribute('data-description').toLowerCase();
                const category = item.getAttribute('data-category');
                const hostel = item.getAttribute('data-hostel').toLowerCase();
                const roomNumber = item.getAttribute('data-room-number').toLowerCase();
                
                // Apply filters
                const hostelMatch = !selectedHostelId || itemHostelId === selectedHostelId;
                const searchMatch = !searchTerm || 
                    title.includes(searchTerm) || 
                    description.includes(searchTerm) || 
                    category.includes(searchTerm) ||
                    hostel.includes(searchTerm) ||
                    roomNumber.includes(searchTerm);
                const filterMatch = activeFilter === 'all' || category === activeFilter;
                
                if (hostelMatch && searchMatch && filterMatch) {
                    item.style.display = 'block';
                    visibleCount++;
                    // Animate appearance
                    item.style.animation = 'fadeIn 0.5s ease-out';
                } else {
                    item.style.display = 'none';
                }
            });
        } else if (currentTab === 'videos') {
            videoCards.forEach(card => {
                const itemHostelId = card.getAttribute('data-hostel-id');
                const title = card.getAttribute('data-title').toLowerCase();
                const description = card.getAttribute('data-description').toLowerCase();
                const category = card.getAttribute('data-category');
                const hostel = card.getAttribute('data-hostel').toLowerCase();
                
                // Apply filters
                const hostelMatch = !selectedHostelId || itemHostelId === selectedHostelId;
                const searchMatch = !searchTerm || 
                    title.includes(searchTerm) || 
                    description.includes(searchTerm) || 
                    category.includes(searchTerm) ||
                    hostel.includes(searchTerm);
                const filterMatch = activeFilter === 'all' || category === activeFilter;
                
                if (hostelMatch && searchMatch && filterMatch) {
                    card.style.display = 'block';
                    visibleCount++;
                    // Animate appearance
                    card.style.animation = 'fadeIn 0.5s ease-out';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // Show/hide no results message
        const noResults = document.querySelector('.no-results.hidden');
        if (noResults) {
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
        
        return visibleCount;
    }

    // HD Image Loading for Modal
    function loadHdImage(imageId, imgElement) {
        fetch(`/api/gallery/${imageId}/hd`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.hd_url) {
                    // Create a new image to preload
                    const hdImage = new Image();
                    hdImage.src = data.hd_url;
                    
                    hdImage.onload = function() {
                        // Replace with HD image
                        imgElement.src = data.hd_url;
                        imgElement.classList.add('hd-loaded');
                        
                        // Add subtle zoom effect
                        imgElement.style.transition = 'transform 0.3s ease';
                        imgElement.style.transform = 'scale(1.02)';
                        
                        setTimeout(() => {
                            imgElement.style.transform = 'scale(1)';
                        }, 300);
                    };
                }
            })
            .catch(error => {
                console.error('Error loading HD image:', error);
            });
    }

    // Enhanced Modal with Video Support
    const modal = document.querySelector('.gallery-modal');
    const modalTitle = modal.querySelector('.modal-title');
    const modalDescription = modal.querySelector('.modal-description');
    const modalCategory = modal.querySelector('.modal-category');
    const modalDate = modal.querySelector('.modal-date');
    const modalHostel = modal.querySelector('.modal-hostel');
    const modalRoom = modal.querySelector('.modal-room');
    const modalContent = modal.querySelector('.modal-content');
    const modalHostelLink = modal.querySelector('.modal-hostel-link');
    const modalPrev = modal.querySelector('.modal-prev');
    const modalNext = modal.querySelector('.modal-next');
    
    let currentIndex = 0;
    let currentItems = [];
    
    // Open modal with enhanced features
    function openEnhancedModal(item, index) {
        currentIndex = index;
        
        const title = item.getAttribute('data-title');
        const description = item.getAttribute('data-description');
        const category = item.getAttribute('data-category');
        const date = item.getAttribute('data-date');
        const hostel = item.getAttribute('data-hostel');
        const roomNumber = item.getAttribute('data-room-number');
        const hostelSlug = item.getAttribute('data-hostel-slug');
        const mediaType = item.getAttribute('data-media-type');
        const youtubeEmbed = item.getAttribute('data-youtube-embed');
        const videoUrl = item.getAttribute('data-video-url');
        const hdAvailable = item.getAttribute('data-hd-available') === 'true';
        const hdUrl = item.getAttribute('data-hd-url');
        const mediaUrl = item.getAttribute('data-media-url');
        const is360Video = item.getAttribute('data-is-360') === 'true';
        
        // Set modal content
        modalTitle.textContent = title;
        modalDescription.textContent = description;
        modalCategory.textContent = category;
        modalDate.textContent = date;
        modalHostel.textContent = hostel;
        modalRoom.textContent = roomNumber ? `कोठा: ${roomNumber}` : '';
        
        // Set hostel link
        if (hostelSlug) {
            modalHostelLink.href = `/hostels/${hostelSlug}`;
            modalHostelLink.style.display = 'inline-flex';
        } else {
            modalHostelLink.style.display = 'none';
        }
        
        // Clear previous content
        modalContent.innerHTML = '';
        
        // Add media based on type
        if (mediaType === 'photo') {
            const img = document.createElement('img');
            img.src = mediaUrl;
            img.alt = title;
            img.className = 'modal-image';
            img.style.width = '100%';
            img.style.height = 'auto';
            img.style.display = 'block';
            
            // Load HD image if available
            if (hdAvailable && hdUrl) {
                img.dataset.hdUrl = hdUrl;
                img.addEventListener('load', function() {
                    // Preload HD version
                    const hdImage = new Image();
                    hdImage.src = hdUrl;
                    hdImage.onload = function() {
                        // Show HD badge
                        const hdBadge = document.createElement('div');
                        hdBadge.className = 'modal-hd-badge';
                        hdBadge.innerHTML = '<i class="fas fa-hd"></i> HD';
                        hdBadge.style.position = 'absolute';
                        hdBadge.style.top = '10px';
                        hdBadge.style.right = '10px';
                        hdBadge.style.background = 'rgba(0,0,0,0.7)';
                        hdBadge.style.color = '#10b981';
                        hdBadge.style.padding = '0.5rem 0.8rem';
                        hdBadge.style.borderRadius = '4px';
                        hdBadge.style.fontSize = '0.9rem';
                        hdBadge.style.fontWeight = '600';
                        hdBadge.style.zIndex = '10';
                        modalContent.appendChild(hdBadge);
                    };
                });
            }
            
            modalContent.appendChild(img);
            
        } else if (mediaType === 'external_video' && youtubeEmbed) {
            const iframe = document.createElement('iframe');
            iframe.src = youtubeEmbed + (youtubeEmbed.includes('?') ? '&' : '?') + 
                        'autoplay=1&rel=0&controls=1&showinfo=0';
            iframe.width = '100%';
            iframe.height = '500';
            iframe.allowFullscreen = true;
            iframe.style.border = 'none';
            iframe.className = 'modal-video';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            
            // Add 360 badge if applicable
            if (is360Video) {
                const badge = document.createElement('div');
                badge.className = 'modal-360-badge';
                badge.innerHTML = '<i class="fas fa-360-degrees"></i> 360° VIEW';
                badge.style.position = 'absolute';
                badge.style.top = '10px';
                badge.style.left = '10px';
                badge.style.background = 'rgba(14, 165, 233, 0.9)';
                badge.style.color = 'white';
                badge.style.padding = '0.5rem 0.8rem';
                badge.style.borderRadius = '4px';
                badge.style.fontSize = '0.9rem';
                badge.style.fontWeight = '600';
                badge.style.zIndex = '10';
                modalContent.appendChild(badge);
            }
            
            modalContent.appendChild(iframe);
            
        } else if (mediaType === 'local_video' && videoUrl) {
            const videoContainer = document.createElement('div');
            videoContainer.className = 'modal-local-video';
            videoContainer.style.position = 'relative';
            videoContainer.style.width = '100%';
            
            const video = document.createElement('video');
            video.src = videoUrl;
            video.controls = true;
            video.autoplay = true;
            video.style.width = '100%';
            video.style.height = 'auto';
            video.style.maxHeight = '70vh';
            video.style.display = 'block';
            
            videoContainer.appendChild(video);
            modalContent.appendChild(videoContainer);
        }
        
        // Show modal with animation
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Add fade-in animation
        modalContent.style.animation = 'fadeIn 0.3s ease-out';
        
        // Update current items array
        if (currentTab === 'photos') {
            currentItems = Array.from(document.querySelectorAll('.gallery-item[style*="block"]'));
        } else if (currentTab === 'videos') {
            currentItems = Array.from(document.querySelectorAll('.video-card[style*="block"]'));
        }
        
        // Update navigation buttons
        updateNavigation();
    }

    // Update navigation buttons state
    function updateNavigation() {
        if (currentIndex <= 0) {
            modalPrev.style.opacity = '0.5';
            modalPrev.style.cursor = 'not-allowed';
        } else {
            modalPrev.style.opacity = '1';
            modalPrev.style.cursor = 'pointer';
        }
        
        if (currentIndex >= currentItems.length - 1) {
            modalNext.style.opacity = '0.5';
            modalNext.style.cursor = 'not-allowed';
        } else {
            modalNext.style.opacity = '1';
            modalNext.style.cursor = 'pointer';
        }
    }

    // Navigation functions
    function navigateModal(direction) {
        const newIndex = currentIndex + direction;
        
        if (newIndex >= 0 && newIndex < currentItems.length) {
            // Close current modal
            modal.classList.remove('active');
            
            // Small delay before opening next item
            setTimeout(() => {
                openEnhancedModal(currentItems[newIndex], newIndex);
            }, 50);
        }
    }

    // Event Listeners
    modalPrev.addEventListener('click', () => navigateModal(-1));
    modalNext.addEventListener('click', () => navigateModal(1));
    
    // Close modal
    modal.querySelector('.modal-close').addEventListener('click', function() {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
        
        // Stop video playback
        const iframe = modalContent.querySelector('iframe');
        if (iframe) {
            iframe.src = iframe.src.replace('autoplay=1', 'autoplay=0');
        }
        
        const video = modalContent.querySelector('video');
        if (video) {
            video.pause();
        }
    });
    
    // Close modal on outside click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal.classList.contains('active')) {
            switch(e.key) {
                case 'Escape':
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                    break;
                case 'ArrowLeft':
                    navigateModal(-1);
                    break;
                case 'ArrowRight':
                    navigateModal(1);
                    break;
            }
        }
    });
    
    // Gallery item click events
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            openEnhancedModal(this, index);
        });
    });
    
    // Video card click events
    videoCards.forEach((card, index) => {
        card.addEventListener('click', function() {
            openEnhancedModal(this, index);
        });
    });

    // Video category filtering
    const videoCategoryBtns = document.querySelectorAll('.video-category-btn');
    videoCategoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            videoCategoryBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Apply filters
            filterContent();
        });
    });

    // Initialize filters
    filterContent();
    
    // Event listeners for filters
    const hostelFilter = document.getElementById('hostelFilter');
    const searchInput = document.querySelector('.search-input');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    if (hostelFilter) {
        hostelFilter.addEventListener('change', filterContent);
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterContent);
    }
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            // Apply filters
            filterContent();
        });
    });

    // Load more videos function
    async function loadMoreVideos() {
        if (isLoadingVideos || !hasMoreVideos) return;
        
        isLoadingVideos = true;
        const loadingEl = document.querySelector('.videos-loading');
        if (loadingEl) loadingEl.style.display = 'block';
        
        try {
            const response = await fetch(`/api/gallery/videos?page=${videoPage + 1}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.videos.length > 0) {
                videoPage++;
                
                // Append new videos to the grid
                const videosGrid = document.querySelector('.videos-grid');
                
                data.videos.forEach(video => {
                    const videoCard = createVideoCard(video);
                    videosGrid.appendChild(videoCard);
                });
                
                // Update hasMoreVideos flag
                hasMoreVideos = videoPage < data.pagination.last_page;
                
                // Re-attach event listeners
                attachVideoCardEvents();
            } else {
                hasMoreVideos = false;
            }
        } catch (error) {
            console.error('Error loading more videos:', error);
        } finally {
            isLoadingVideos = false;
            if (loadingEl) loadingEl.style.display = 'none';
        }
    }

    // Create video card element
    function createVideoCard(video) {
        const div = document.createElement('div');
        div.className = 'video-card';
        div.setAttribute('data-category', video.category_nepali || video.category);
        div.setAttribute('data-title', video.title);
        div.setAttribute('data-description', video.description);
        div.setAttribute('data-date', video.created_at);
        div.setAttribute('data-hostel', video.hostel_name);
        div.setAttribute('data-hostel-id', video.hostel_id);
        div.setAttribute('data-hostel-slug', video.hostel_slug);
        div.setAttribute('data-room-number', video.room_number || '');
        div.setAttribute('data-media-type', video.media_type);
        div.setAttribute('data-youtube-embed', video.youtube_embed_url || '');
        div.setAttribute('data-video-url', video.media_url || '');
        div.setAttribute('data-video-duration', video.video_duration || '');
        div.setAttribute('data-video-resolution', video.video_resolution || '');
        div.setAttribute('data-is-360', video.is_360_video || false);
        
        div.innerHTML = `
            <div class="video-thumbnail">
                <img src="${video.thumbnail_url}" alt="${video.title}" loading="lazy">
                <div class="play-button">
                    <i class="fas fa-play"></i>
                </div>
                ${video.video_duration ? `<div class="video-duration">${video.video_duration}</div>` : ''}
                ${video.is_360_video ? `
                    <div class="video-badge-360">
                        <i class="fas fa-360-degrees"></i>
                        360°
                    </div>
                ` : ''}
                ${video.hostel_slug ? `
                    <a href="/hostels/${video.hostel_slug}" 
                       class="hostel-link-enhanced" 
                       style="top: auto; bottom: 10px; left: 10px;"
                       title="${video.hostel_name} मा जानुहोस्">
                        <i class="fas fa-external-link-alt"></i>
                        <span class="nepali">${video.hostel_name.substring(0, 12)}${video.hostel_name.length > 12 ? '...' : ''}</span>
                    </a>
                ` : ''}
            </div>
            <div class="video-info">
                <h3 class="nepali">${video.title}</h3>
                <div class="video-meta">
                    <span class="nepali">${video.category_nepali || video.category}</span>
                    ${video.video_resolution ? `<span class="video-resolution">${video.video_resolution}</span>` : ''}
                </div>
                <p class="video-description nepali">${video.description.substring(0, 80)}${video.description.length > 80 ? '...' : ''}</p>
                ${video.hostel_slug ? `
                    <a href="/hostels/${video.hostel_slug}" class="video-hostel-link nepali">
                        <i class="fas fa-building"></i>
                        ${video.hostel_name} को विवरण हेर्नुहोस्
                    </a>
                ` : ''}
            </div>
        `;
        
        return div;
    }

    // Attach events to video cards
    function attachVideoCardEvents() {
        const newVideoCards = document.querySelectorAll('.video-card');
        newVideoCards.forEach((card, index) => {
            card.addEventListener('click', function() {
                openEnhancedModal(this, index);
            });
        });
    }

    // Infinite scroll for videos
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (currentTab !== 'videos' || isLoadingVideos || !hasMoreVideos) return;
        
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            const scrollPosition = window.innerHeight + window.scrollY;
            const pageHeight = document.documentElement.scrollHeight;
            
            if (scrollPosition >= pageHeight - 500) {
                loadMoreVideos();
            }
        }, 200);
    });

    // Handle trial form submission
    const trialForm = document.querySelector('.gallery-cta-section form');
    if (trialForm) {
        trialForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            
            // Show loading state
            button.classList.add('loading');
            button.disabled = true;
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'निःशुल्क परीक्षण सफलतापूर्वक सुरु गरियो');
                        window.location.reload();
                    }
                } else {
                    throw new Error(data.message || 'अज्ञात त्रुटि');
                }
            } catch (error) {
                alert('त्रुटि: ' + error.message);
                button.classList.remove('loading');
                button.textContent = originalText;
                button.disabled = false;
            }
        });
    }
});
</script>
@endpush