@extends('layouts.frontend')

@section('page-title', 'HostelHub — होस्टल प्रबन्धन प्रणाली | Nepal')
@section('og-title', 'HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS')
@section('og-description', 'HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS')

@push('styles')
<!-- FONT AWESOME CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

@vite(['resources/css/home.css'])

<!-- 🚨 CRITICAL HERO FIX STYLES -->
<style>
/* 🚨 HERO SECTION PROTECTION - HIGHEST PRIORITY */
.hero {
    min-height: 100vh !important;
    padding: 0 !important;
    margin: 0 !important;
    background: linear-gradient(135deg, #1e3a8a, #0ea5e9) !important;
    position: relative !important;
    overflow: hidden !important;
    z-index: 1 !important;
    display: flex !important;
    align-items: center !important;
    width: 100vw !important;
    max-width: 100vw !important;
    left: 0 !important;
    right: 0 !important;
}

/* 🚨 OVERRIDE ANY GLOBAL STYLES THAT MIGHT HIDE HERO */
main.home-page-main {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.content-container {
    padding-top: 0 !important;
}

/* 🚨 HERO CONTENT VISIBILITY */
.hero-content {
    display: grid !important;
    grid-template-columns: 1.1fr 0.9fr !important;
    gap: 2.5rem !important;
    align-items: center !important;
    height: 100% !important;
    position: relative !important;
    z-index: 15 !important;
    width: 100% !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 2rem 1.5rem !important;
}

.hero-text {
    max-width: 100% !important;
    color: var(--text-light) !important;
    width: 100% !important;
    padding-right: 1rem !important;
}

.hero-title {
    font-size: 2.5rem !important;
    font-weight: 800 !important;
    line-height: 1.1 !important;
    margin-bottom: 1rem !important;
    color: var(--text-light) !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    text-align: left !important;
}

.hero-subtitle {
    font-size: 1.1rem !important;
    color: rgba(249, 250, 251, 0.95) !important;
    margin-bottom: 1.5rem !important;
    line-height: 1.5 !important;
    text-align: left !important;
}

/* 🚨 QUICK FIX - Search Form Alignment */
.widget-form {
    align-items: start !important;
}

.gallery-slide-container {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    height: 100%;
}

.hostel-badge-sm {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(0, 31, 91, 0.9);
    color: white;
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
    z-index: 10;
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    gap: 3px;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.hostel-badge-sm i {
    font-size: 0.6rem;
    flex-shrink: 0;
}

.room-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(34, 197, 94, 0.9);
    color: white;
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
    z-index: 10;
    backdrop-filter: blur(4px);
}

.swiper-slide {
    height: auto;
}

.gallery-swiper .swiper-slide img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

/* 🚨 HERO FULL WIDTH FIX */
.hero {
    min-height: 100vh !important;
    padding: 0 !important;
    margin: 0 !important;
    background: linear-gradient(135deg, #1e3a8a, #0ea5e9) !important;
    position: relative !important;
    overflow: hidden !important;
    z-index: 1 !important;
    display: flex !important;
    align-items: center !important;
    width: 100vw !important;
    max-width: 100vw !important;
    left: 0 !important;
    right: 0 !important;
    
    /* 🚨 CRITICAL: Remove any left spacing */
    margin-left: calc(-50vw + 50%) !important;
    margin-right: calc(-50vw + 50%) !important;
}

/* 🚨 HERO CONTENT FIX */
.hero-content {
    display: grid !important;
    grid-template-columns: 1.1fr 0.9fr !important;
    gap: 2.5rem !important;
    align-items: center !important;
    height: 100% !important;
    position: relative !important;
    z-index: 15 !important;
    width: 100% !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 2rem 1.5rem !important;
}

/* 🚨 REMOVE CONTAINER PADDING IN HERO */
.hero .container {
    padding: 0 !important;
    margin: 0 auto !important;
    width: 100% !important;
    max-width: 1200px !important;
}

/* ==================== COMPACT SEARCH WIDGET STYLES ==================== */

.compact-search-widget {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 16px;
    box-shadow: 0 15px 35px -12px rgba(0, 0, 0, 0.2), 
                0 0 0 1px rgba(14, 165, 233, 0.1);
    padding: 1.8rem;
    margin: -5rem auto 2rem !important; /* Reduced from -7rem to -5rem */
    position: relative;
    z-index: 100;
    width: 92%;
    max-width: 800px; /* Reduced from 1200px to 800px */
    border: 1px solid rgba(14, 165, 233, 0.15);
    backdrop-filter: blur(10px);
    transform: translateY(0);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.compact-search-widget:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3), 
                0 0 0 1px rgba(14, 165, 233, 0.2);
}

.compact-widget-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
    color: var(--primary);
    width: 100%;
    position: relative;
    padding-bottom: 0.8rem;
}

.compact-widget-title::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(to right, var(--primary), var(--secondary));
    border-radius: 2px;
}

/* Compact Form Grid Layout */
.compact-form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    width: 100%;
    margin-bottom: 1.2rem;
}

.compact-form-group {
    margin-bottom: 0;
    position: relative;
}

.compact-form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--primary);
    font-size: 0.9rem;
    transition: var(--transition);
}

/* Input with Icon */
.compact-form-group .input-with-icon {
    position: relative;
}

.compact-form-group .input-with-icon i {
    position: absolute;
    left: 0.8rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    font-size: 1rem;
    z-index: 2;
    transition: var(--transition);
}

.compact-form-control {
    width: 100%;
    padding: 0.8rem 0.8rem 0.8rem 2.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    font-family: 'Noto Sans Devanagari', 'Inter', sans-serif;
    height: 48px;
}

.compact-form-control:focus {
    outline: none;
    border-color: var(--secondary);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15), 
                0 4px 12px rgba(14, 165, 233, 0.1);
    background: #ffffff;
    transform: translateY(-2px);
}

.compact-form-control:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
}

/* Search Button */
.compact-search-button {
    grid-column: 1 / -1;
    display: flex;
    justify-content: center;
    margin-top: 0.5rem;
}

.compact-search-btn {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    border-radius: 10px;
    padding: 1rem 2rem;
    font-weight: 700;
    font-size: 1rem;
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 200px;
    justify-content: center;
}

.compact-search-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(14, 165, 233, 0.4);
    background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
}

.compact-search-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: 0.5s;
}

.compact-search-btn:hover::before {
    left: 100%;
}

/* Quick Info Badges */
.compact-quick-info {
    display: flex;
    justify-content: center;
    gap: 0.8rem;
    flex-wrap: wrap;
    padding-top: 1rem;
    border-top: 1px solid rgba(14, 165, 233, 0.1);
}

.quick-badge {
    background: rgba(14, 165, 233, 0.1);
    color: var(--primary);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1px solid rgba(14, 165, 233, 0.2);
    transition: all 0.3s ease;
}

.quick-badge:hover {
    background: rgba(14, 165, 233, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(14, 165, 233, 0.15);
}

/* ==================== HERO SLIDER STYLES FOR HOSTELS ==================== */
.slide-link {
    display: block;
    position: relative;
    height: 100%;
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.slide-link:hover {
    transform: scale(1.02);
}

.slide-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.slide-link:hover .slide-overlay {
    background: linear-gradient(transparent, rgba(0,0,0,0.9));
}

.slide-content {
    text-align: center;
}

.hostel-name {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

.hostel-location {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
}

.view-hostel-btn {
    display: inline-block;
    background: var(--primary);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-top: 0.5rem;
    transition: all 0.3s ease;
}

.slide-link:hover .view-hostel-btn {
    background: var(--secondary);
    transform: translateY(-2px);
}

/* ==================== RESPONSIVE DESIGN ==================== */

/* Tablet */
@media (max-width: 768px) {
    .compact-search-widget {
        margin: -4rem auto 1.5rem !important;
        padding: 1.5rem;
        width: 95%;
        border-radius: 14px;
    }
    
    .compact-form-grid {
        grid-template-columns: 1fr;
        gap: 0.8rem;
    }
    
    .compact-widget-title {
        font-size: 1.2rem;
        margin-bottom: 1.2rem;
    }
    
    .compact-form-control {
        padding: 0.7rem 0.7rem 0.7rem 2.3rem;
        height: 44px;
        font-size: 0.9rem;
    }
    
    .compact-form-group .input-with-icon i {
        left: 0.7rem;
        font-size: 0.9rem;
    }
    
    .compact-search-btn {
        padding: 0.9rem 1.5rem;
        font-size: 0.95rem;
        min-width: 180px;
    }
    
    .compact-quick-info {
        gap: 0.6rem;
    }
    
    .quick-badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }

    /* Mobile Hero Slider */
    .hero-slideshow {
        margin-top: 1rem;
    }
    
    .hostel-name {
        font-size: 1.1rem;
    }
    
    .hostel-location {
        font-size: 0.8rem;
    }
}

/* Mobile */
@media (max-width: 480px) {
    .compact-search-widget {
        margin: -3rem auto 1rem !important;
        padding: 1.2rem;
        border-radius: 12px;
    }
    
    .compact-widget-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    
    .compact-form-control {
        padding: 0.6rem 0.6rem 0.6rem 2.2rem;
        height: 42px;
        font-size: 0.85rem;
    }
    
    .compact-form-group .input-with-icon i {
        left: 0.6rem;
        font-size: 0.85rem;
    }
    
    .compact-search-btn {
        padding: 0.8rem 1.2rem;
        font-size: 0.9rem;
        min-width: 160px;
        gap: 0.4rem;
    }
    
    .compact-quick-info {
        gap: 0.4rem;
        padding-top: 0.8rem;
    }
    
    .quick-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Small Mobile */
@media (max-width: 360px) {
    .compact-search-widget {
        margin: -2.5rem auto 0.8rem !important;
        padding: 1rem;
    }
    
    .compact-widget-title {
        font-size: 1rem;
    }
    
    .compact-form-grid {
        gap: 0.6rem;
    }
    
    .compact-search-btn {
        padding: 0.7rem 1rem;
        font-size: 0.85rem;
        min-width: 140px;
    }
    
    .compact-quick-info {
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
    }
}

/* Animation */
@keyframes compactSlideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.compact-search-widget {
    animation: compactSlideInUp 0.6s ease-out;
}
</style>
@endpush

@section('content')
<!-- 🚨 HERO SECTION MOVED HERE - NO SEPARATE SECTION -->
<section class="hero">
    <video autoplay muted loop playsinline preload="metadata" class="hero-video">
        <source src="https://assets.mixkit.co/videos/preview/mixkit-student-studying-in-a-dorm-room-44475-large.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <div class="hero-content">
            <!-- Text Content - Left Side -->
            <div class="hero-text">
                <h1 class="hero-title nepali">HostelHub — तपाइँको होस्टल व्यवस्थापन अब सजिलो, द्रुत र भरपर्दो</h1>
                <p class="hero-subtitle nepali">विद्यार्थी व्यवस्थापन, कोठा आवंटन, भुक्तानी र भोजन प्रणाली—एकै प्लेटफर्मबाट चलाउनुहोस्। ७ दिन निःशुल्क।</p>
                
                <div class="hero-cta">
                    <a href="{{ route('demo') }}" class="btn btn-primary nepali">डेमो हेर्नुहोस्</a>
                    <a href="{{ route('hostels.index') }}" class="btn btn-outline nepali">सबै होस्टल हेर्नुहोस्</a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number count-up" id="students-counter" aria-live="polite">125</div>
                        <div class="stat-label nepali">कुल विद्यार्थीहरू</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number count-up" id="hostels-counter" aria-live="polite">24</div>
                        <div class="stat-label nepali">सहयोगी होस्टल</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number count-up" id="cities-counter" aria-live="polite">5</div>
                        <div class="stat-label nepali">शहरहरू</div>
                    </div>
                </div>
            </div>

            <!-- Image Slider - Right Side -->
            <div class="hero-slideshow">
                <div class="swiper hero-slider">
                    <div class="swiper-wrapper">
                        @if(isset($featuredHostels) && count($featuredHostels) > 0)
                            @foreach($featuredHostels as $hostel)
                            <div class="swiper-slide">
                                <a href="{{ $hostel['public_url'] ?? route('hostels.show', $hostel['slug']) }}" 
                                   class="slide-link" 
                                   title="{{ $hostel['name'] }} - {{ $hostel['city'] }}">
                                    <img src="{{ $hostel['cover_image'] }}" 
                                         alt="{{ $hostel['name'] }}" 
                                         loading="lazy"
                                         onerror="this.onerror=null;this.src='{{ asset('images/default-hostel.jpg') }}'">
                                    <div class="slide-overlay">
                                        <div class="slide-content">
                                            <h4 class="hostel-name">{{ $hostel['name'] }}</h4>
                                            <p class="hostel-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $hostel['city'] }}
                                            </p>
                                            <span class="view-hostel-btn">होस्टल हेर्नुहोस्</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        @else
                            <!-- 🚨 FALLBACK SLIDES -->
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=450&fit=crop" 
                                     alt="Comfortable Hostel Rooms" 
                                     loading="lazy">
                                <div class="slide-overlay">
                                    <div class="slide-content">
                                        <h4 class="hostel-name">HostelHub होस्टल</h4>
                                        <p class="hostel-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            काठमाडौं
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=450&fit=crop" 
                                     alt="Modern Hostel Facilities" 
                                     loading="lazy">
                                <div class="slide-overlay">
                                    <div class="slide-content">
                                        <h4 class="hostel-name">आधुनिक होस्टल</h4>
                                        <p class="hostel-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            पोखरा
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Navigation arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- COMPACT SEARCH WIDGET - UPDATED VERSION -->
<div class="container">
    <div class="compact-search-widget">
        <h3 class="compact-widget-title nepali">🔍 कोठा खोजी / रिजर्भ गर्नुहोस्</h3>
        <form class="compact-widget-form" id="booking-form" action="{{ route('search') }}" method="GET">
            
            <div class="compact-form-grid">
                <div class="compact-form-group">
                    <label class="nepali" for="city">📍 स्थान</label>
                    <div class="input-with-icon">
                        <i class="fas fa-map-marker-alt"></i>
                        <select class="compact-form-control" name="city" id="city" required aria-required="true">
                            <option value="">स्थान छान्नुहोस्</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="compact-form-group">
                    <label class="nepali" for="hostel_id">🏠 होस्टल</label>
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <select class="compact-form-control" name="hostel_id" id="hostel_id">
                            <option value="">सबै होस्टल</option>
                            @foreach($hostels as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="compact-form-group">
                    <label class="nepali" for="check_in">📅 चेक-इन</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" class="compact-form-control" name="check_in" id="check_in" required 
                               aria-required="true" min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                
                <div class="compact-form-group">
                    <label class="nepali" for="check_out">📅 चेक-आउट</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-check"></i>
                        <input type="date" class="compact-form-control" name="check_out" id="check_out" required 
                               aria-required="true" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                </div>
                
                <div class="compact-search-button">
                    <button type="submit" class="compact-search-btn nepali">
                        <i class="fas fa-search"></i>
                        कोठा खोज्नुहोस्
                    </button>
                </div>
            </div>
            
            <!-- Quick Info Badges -->
            <div class="compact-quick-info">
                <span class="quick-badge">🎓 विद्यार्थी-अनुकूल</span>
                <span class="quick-badge">🔒 सुरक्षित बुकिंग</span>
                <span class="quick-badge">💰 उचित मूल्य</span>
                <span class="quick-badge">🏠 प्रमाणित होस्टल</span>
            </div>
        </form>
    </div>
</div>

<!-- Enhanced Gallery Section with Hostel Badges -->
<section class="section gallery" id="gallery">
    <div class="container">
        <h2 class="section-title nepali">हाम्रो ग्यालरी</h2>
        <p class="section-subtitle nepali">हाम्रा होस्टलहरूको फोटो र भिडियोहरू हेर्नुहोस्</p>
        <div class="gallery-swiper swiper">
            <div class="swiper-wrapper">
                @foreach($galleryItems as $item)
                <div class="swiper-slide">
                    <div class="gallery-slide-container">
                        @if($item['media_type'] === 'image')
                            <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgZmlsbD0iI2YwZjlmZiI+PC9yZWN0Pjx0ZXh0IHg9IjQwMCIgeT0iMjI1IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWtkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IiMxZjI5MzciPkltYWdlIFRodW1ibmFpbDwvdGV4dD48L3N2Zz4=';">
                        @else
                            <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" loading="lazy" class="youtube-thumbnail" data-youtube-id="{{ $item['youtube_id'] ?? '' }}" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgZmlsbD0iIzFlM2E4YSI+PC9yZWN0Pjx0ZXh0IHg9IjQwMCIgeT0iMjI5IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWtkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IiNmZmYiPlZpZGVvIFRodW1ibmFpbDwvdGV4dD48L3N2Zz4=';">
                            <div class="video-overlay">
                                <div class="video-play-icon">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Hostel Badge for Homepage -->
                        <div class="hostel-badge-sm">
                            <i class="fas fa-building"></i>
                            <span class="nepali">{{ $item['hostel_name'] ?? 'Unknown Hostel' }}</span>
                        </div>

                        <!-- Room Badge if it's a room image -->
                        @if(isset($item['is_room_image']) && $item['is_room_image'] && isset($item['room_number']))
                            <div class="room-badge">
                                <i class="fas fa-door-open"></i>
                                <span class="nepali">कोठा {{ $item['room_number'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="gallery-button">
            <a href="{{ route('gallery') }}" class="view-gallery-btn nepali">पूरै ग्यालरी हेर्नुहोस्</a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section" id="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users" aria-hidden="true"></i>
                </div>
                <div class="stat-count count-up" id="students-counter-stat" aria-live="polite">{{ $metrics['total_students'] ?? 125 }}</div>
                <div class="stat-description nepali">खुसी विद्यार्थीहरू</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-building" aria-hidden="true"></i>
                </div>
                <div class="stat-count count-up" id="hostels-counter-stat" aria-live="polite">{{ $metrics['total_hostels'] ?? 24 }}</div>
                <div class="stat-description nepali">सहयोगी होस्टल</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                </div>
                <div class="stat-count count-up" id="cities-counter-stat" aria-live="polite">{{ $cities->count() ?? 5 }}</div>
                <div class="stat-description nepali">शहरहरूमा उपलब्ध</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section features" id="features">
    <div class="container">
        <h2 class="section-title nepali">हाम्रा प्रमुख सुविधाहरू</h2>
        <p class="section-subtitle nepali">HostelHub ले प्रदान गर्ने विशेष सुविधाहरू जसले तपाईंको होस्टल व्यवस्थापनलाई सजिलो बनाउँछ</p>
        <div class="features-grid">
            <div class="feature-card" aria-labelledby="feature1-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-users"></i>
                </div>
                <h3 id="feature1-title" class="feature-title nepali">विद्यार्थी व्यवस्थापन</h3>
                <p class="feature-desc nepali">सबै विद्यार्थी विवरण एउटै ठाउँमा प्रबन्धन गर्नुहोस्, अध्ययन स्थिति, सम्पर्क जानकारी र भुक्तानी इतिहास</p>
            </div>
            <div class="feature-card" aria-labelledby="feature2-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-bed"></i>
                </div>
                <h3 id="feature2-title" class="feature-title nepali">कोठा उपलब्धता</h3>
                <p class="feature-desc nepali">रियल-टाइम कोठा उपलब्धता देख्नुहोस्, आवंटन गर्नुहोस् र बुकिंग प्रबन्धन गर्नुहोस्</p>
            </div>
            <div class="feature-card" aria-labelledby="feature3-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3 id="feature3-title" class="feature-title nepali">भुक्तानी प्रणाली</h3>
                <p class="feature-desc nepali">स्वचालित भुक्तानी ट्र्याकिंग, बिल जनरेट गर्नुहोस्, रिमाइन्डर पठाउनुहोस् र वित्तीय विवरण हेर्नुहोस्</p>
            </div>
            <div class="feature-card" aria-labelledby="feature4-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 id="feature4-title" class="feature-title nepali">भोजन व्यवस्थापन</h3>
                <p class="feature-desc nepali">मेनु योजना बनाउनुहोस्, भोजन आदेश ट्र्याक गर्नुहोस् र खानेकुराको इन्भेन्टरी प्रबन्धन गर्नुहोस्</p>
            </div>
            <div class="feature-card" aria-labelledby="feature5-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 id="feature5-title" class="feature-title nepali">विश्लेषण र रिपोर्ट</h3>
                <p class="feature-desc nepali">होस्टलको प्रदर्शन विश्लेषण गर्नुहोस्, आगामी आवश्यकताहरूको अनुमान गर्नुहोस्</p>
            </div>
            <div class="feature-card" aria-labelledby="feature6-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 id="feature6-title" class="feature-title nepali">मोबाइल एप्प</h3>
                <p class="feature-desc nepali">होस्टल प्रबन्धन गर्नुहोस् वा विद्यार्थीहरूले आफ्नो भुक्तानी, कोठा स्थिति र भोजन अर्डर हेर्न सक्ने</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section how-it-works" id="how-it-works">
    <div class="container">
        <h2 class="section-title nepali">HostelHub कसरी काम गर्छ?</h2>
        <p class="section-subtitle nepali">हाम्रो प्रणाली प्रयोग गर्ने सजिलो ३ चरणहरू</p>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3 class="step-title nepali">खाता सिर्जना गर्नुहोस्</h3>
                <p class="step-desc nepali">निःशुल्क खाताको लागि साइन अप गर्नुहोस् र आफ्नो होस्टल विवरणहरू थप्नुहोस्</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3 class="step-title nepali">व्यवस्थापन सुरु गर्नुहोस्</h3>
                <p class="step-desc nepali">विद्यार्थीहरू थप्नुहोस्, कोठा आवंटन गर्नुहोस्, र भुक्तानीहरू ट्र्याक गर्नुहोस्</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3 class="step-title nepali">विस्तार गर्नुहोस्</h3>
                <p class="step-desc nepali">हाम्रा उन्नत सुविधाहरू प्रयोग गरेर आफ्नो होस्टल व्यसायलाई बढाउनुहोस्</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section testimonials" id="testimonials">
    <div class="container">
        <h2 class="section-title nepali" style="color: var(--text-light);">ग्राहकहरूको प्रशंसापत्रहरू</h2>
        <p class="section-subtitle" style="color: rgba(249, 250, 251, 0.9);">HostelHub प्रयोग गर्ने हाम्रा ग्राहकहरूले के भन्छन्</p>
        <div class="testimonials-grid">
            @foreach($testimonials as $testimonial)
            <div class="testimonial-card">
                <p class="testimonial-text nepali">{{ $testimonial->content }}</p>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        @if($testimonial->initials)
                            {{ $testimonial->initials }}
                        @else
                            {{ substr($testimonial->name, 0, 2) }}
                        @endif
                    </div>
                    <div class="author-info">
                        <h4>{{ $testimonial->name }}</h4>
                        <p>{{ $testimonial->position ?? 'Student' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
            
            @if(count($testimonials) === 0)
            <div class="testimonial-card">
                <p class="testimonial-text nepali">HostelHub ले हाम्रो होस्टल व्यवस्थापन धेरै सजिलो बनायो। विद्यार्थीहरूको डाटा, भुक्तानी र कोठा व्यवस्थापन एकै ठाउँमा।</p>
                <div class="testimonial-author">
                    <div class="author-avatar">RM</div>
                    <div class="author-info">
                        <h4>रमेश महर्जन</h4>
                        <p>होस्टल प्रबन्धक</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <p class="testimonial-text nepali">विद्यार्थीको रूपमा, म आफ्नो कोठा, भुक्तानी र खानाको मेनु एपबाटै हेर्न सक्छु। धन्यवाद HostelHub!</p>
                <div class="testimonial-author">
                    <div class="author-avatar">SA</div>
                    <div class="author-info">
                        <h4>सिता अर्याल</h4>
                        <p>विद्यार्थी</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Pricing Section - FINAL ENHANCED VERSION -->
<section class="section pricing" id="pricing">
    <div class="container">
        <h2 class="section-title nepali">योजना अनुसारका मूल्यहरू</h2>
        <p class="section-subtitle nepali">तपाईंको होस्टल व्यवस्थापन आवश्यकताअनुसार उपयुक्त योजना छान्नुहोस्</p>
        
        <div class="free-trial-note">
            <p class="nepali">७ दिन निःशुल्क परीक्षण | कुनै पनि क्रेडिट कार्ड आवश्यक छैन</p>
        </div>

        <div class="pricing-grid">
            <!-- सुरुवाती Plan -->
            <div class="pricing-card">
                <div class="pricing-header">
                    <h3 class="pricing-title nepali">सुरुवाती</h3>
                    <div class="pricing-price">रु. २,९९९<span class="nepali">/महिना</span></div>
                </div>
                <ul class="pricing-features">
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">५० विद्यार्थी सम्म</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">१ होस्टल सम्म</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">मूल विद्यार्थी व्यवस्थापन</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">कोठा आवंटन</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">बेसिक अग्रिम कोठा बुकिंग (manual approval)</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">भुक्तानी ट्र्याकिंग</span>
                    </li>
                </ul>
                <div class="pricing-button">
                    <a href="/register" class="pricing-btn pricing-btn-outline nepali">योजना छान्नुहोस्</a>
                </div>
            </div>

            <!-- प्रो Plan -->
            <div class="pricing-card popular">
                <div class="popular-badge nepali">लोकप्रिय</div>
                <div class="pricing-header">
                    <h3 class="pricing-title nepali">प्रो</h3>
                    <div class="pricing-price">रु. ४,९९९<span class="nepali">/महिना</span></div>
                </div>
                <ul class="pricing-features">
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">२०० विद्यार्थी सम्म</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">१ होस्टल सम्म</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">पूर्ण विद्यार्थी व्यवस्थापन</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">अग्रिम कोठा बुकिंग (auto-confirm, notifications)</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">भुक्तानी ट्र्याकिंग</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">मोबाइल एप्प</span>
                    </li>
                </ul>
                <div class="pricing-button">
                    <a href="/register" class="pricing-btn pricing-btn-primary nepali">योजना छान्नुहोस्</a>
                </div>
            </div>

            <!-- एन्टरप्राइज Plan -->
            <div class="pricing-card">
                <div class="pricing-header">
                    <h3 class="pricing-title nepali">एन्टरप्राइज</h3>
                    <div class="pricing-price">रु. ८,९९९<span class="nepali">/महिना</span></div>
                </div>
                <ul class="pricing-features">
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">असीमित विद्यार्थी</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">बहु-होस्टल व्यवस्थापन (५ होस्टल सम्म)</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">पूर्ण विद्यार्थी व्यवस्थापन</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">अग्रिम कोठा बुकिंग (auto-confirm)</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">कस्टम भुक्तानी प्रणाली</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">२४/७ समर्थन</span>
                    </li>
                    <li>
                        <i class="fas fa-info-circle"></i>
                        <span class="feature-text nepali enterprise-note">अतिरिक्त होस्टल थप्न सकिन्छ: रु. १,०००/महिना प्रति अतिरिक्त होस्टल</span>
                    </li>
                </ul>
                <div class="pricing-button">
                    <a href="/register" class="pricing-btn pricing-btn-outline nepali">योजना छान्नुहोस्</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Free Trial Section -->
<section class="free-trial" style="margin-bottom: 15px !important;">
    <div class="container">
        <div class="trial-content">
            <h2 class="trial-title nepali">७ दिनको निःशुल्क परीक्षण</h2>
            <p class="trial-subtitle nepali">हाम्रो प्रणालीको सबै सुविधाहरू निःशुल्क परीक्षण गर्नुहोस्, कुनै पनि बाध्यता बिना</p>
            <div class="trial-highlight">
                <p class="trial-highlight-text nepali">७ दिन निःशुल्क • कुनै क्रेडिट कार्ड आवश्यक छैन • कुनै पनि प्रतिबद्धता छैन !</p>
            </div>
            <div class="trial-cta">
                <a href="/register" class="btn btn-primary nepali">निःशुल्क साइन अप गर्नुहोस्</a>
                <a href="{{ route('demo') }}" class="btn btn-outline nepali" style="background: white; color: var(--primary);">डेमो हेर्नुहोस्</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚨 HERO FIXED - DIRECT IN CONTENT');
    
    // Initialize Swiper
    try {
        if (typeof Swiper !== 'undefined') {
            const heroSwiper = new Swiper('.hero-slider', {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                },
            });
            console.log('✅ Hero Swiper initialized');
        }
    } catch (e) {
        console.log('Swiper error:', e);
    }

    // 🚨 UPDATED: BETTER SEARCH FORM VALIDATION
    const searchForm = document.getElementById('booking-form');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const city = document.getElementById('city').value;
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            
            console.log('🔍 Form submission check:', { city, checkIn, checkOut });
            
            // 🚨 ONLY validate if city is empty, don't prevent for valid cases
            if (!city) {
                e.preventDefault();
                alert('कृपया स्थान (शहर) छान्नुहोस्।');
                return false;
            }
            
            // 🚨 REMOVED strict validation for dates to allow form submission
            // Let the server handle date validation
            
            console.log('✅ Form validation passed - submitting to server');
        });
    }
    
    // Dynamic hostel dropdown based on city
    const citySelect = document.getElementById('city');
    const hostelSelect = document.getElementById('hostel_id');
    
    if (citySelect && hostelSelect) {
        citySelect.addEventListener('change', function() {
            const city = this.value;
            
            // Reset hostel dropdown
            hostelSelect.innerHTML = '<option value="">सबै होस्टल</option>';
            
            if (city) {
                console.log('City selected:', city);
                // AJAX call can be added here later
            }
        });
    }

    // 🚨 DEBUG: Check if routes are working
    console.log('🔍 Search Route:', '{{ route("search") }}');
    console.log('🏠 All Hostels Route:', '{{ route("hostels.index") }}');
});
</script>

@vite(['resources/js/home.js'])
@endpush