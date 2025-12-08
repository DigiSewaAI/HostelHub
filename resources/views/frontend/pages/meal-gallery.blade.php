@extends('layouts.frontend')

@push('styles')
<!-- 🚨 ADD MAIN CONTENT RESET - EXACTLY SAME AS FEATURES PAGE -->
<style>
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .gallery-page-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }
</style>
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

<style>
/* Fix Header Overlap */
.meal-hero {
    padding: 120px 0 60px;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

/* 🚨 UPDATED: HERO SECTION - EXACT SAME SPACING AS FEATURES PAGE */
.gallery-hero {
    text-align: center;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 2.5rem 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    max-width: 1000px;
    width: 90%;
    
    /* 🚨 EXACT SAME MARGIN AS FEATURES PAGE HEADER */
    margin: calc(var(--header-height, 70px) + 0.9rem) auto 1.5rem auto !important;
}

.gallery-hero h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.75rem;
}

.gallery-hero p {
    font-size: 1.125rem;
    color: rgba(255, 255, 255, 0.9);
    max-width: 700px;
    margin: 0 auto 0.75rem auto;
    line-height: 1.6;
}

/* Enhanced Filters */
.meal-filters {
    background: #f8f9fa;
    padding: 2rem 0;
    border-bottom: 1px solid #e9ecef;
}

.filter-tabs {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 12px 24px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    text-decoration: none;
    color: #6c757d;
    transition: all 0.3s ease;
    font-weight: 600;
}

.filter-tab:hover,
.filter-tab.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(30, 58, 138, 0.3);
}

/* ✅ FIXED: Compact Statistics Cards */
.gallery-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.stat-item.compact {
    text-align: center;
    padding: 1.5rem 1rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(30, 58, 138, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    min-height: auto;
}

.stat-item.compact i {
    font-size: 1.8rem;
    opacity: 0.9;
    flex-shrink: 0;
}

.stat-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-item.compact .stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-item.compact .stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* ✅ FIXED: Enhanced Food Cards with Better Spacing */
.enhanced-food-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 1.5rem;
    margin-bottom: 4rem;
}

.enhanced-food-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
}

.enhanced-food-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.card-image-container {
    position: relative;
    height: 220px; /* ✅ Reduced from 250px */
    overflow: hidden;
}

.food-image-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
}

.food-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.enhanced-food-card:hover .food-image {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding: 1.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.enhanced-food-card:hover .image-overlay {
    opacity: 1;
}

.meal-badge {
    background: rgba(255,255,255,0.9);
    padding: 6px 12px;
    border-radius: 16px;
    color: #333;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.85rem;
}

.quick-view-btn {
    background: var(--primary);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.quick-view-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

.type-indicator {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    padding: 6px 12px;
    border-radius: 16px;
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
}

.type-indicator.breakfast {
    background: linear-gradient(135deg, #FFD700, #FFA500);
}

.type-indicator.lunch {
    background: linear-gradient(135deg, #32CD32, #228B22);
}

.type-indicator.dinner {
    background: linear-gradient(135deg, #8A2BE2, #4B0082);
}

/* ✅ FIXED: Reduced Card Content Padding */
.card-content {
    padding: 1.25rem; /* Reduced from 1.5rem */
}

.meal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.meal-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
    line-height: 1.3;
    flex: 1;
}

.meal-meta {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    color: #6c757d;
    font-size: 0.85rem;
}

.meal-items {
    margin-bottom: 1.25rem;
}

.items-title {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.4rem;
    font-size: 0.95rem;
}

.items-list {
    color: #6c757d;
    line-height: 1.4;
    margin: 0;
    font-size: 0.9rem;
}

/* ✅ FIXED: Single Share Button (Removed Like Button) */
.meal-actions {
    display: flex;
    gap: 0.5rem;
    border-top: 1px solid #e9ecef;
    padding-top: 1rem;
}

.action-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 8px;
    border: 1px solid #e9ecef;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
}

.action-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: translateY(-1px);
}

/* ✅ NEW: Share Modal Styles */
.share-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.share-modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.share-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.share-modal-header h3 {
    margin: 0;
    color: #2d3748;
    font-size: 1.2rem;
}

.share-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6c757d;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.share-modal-close:hover {
    background: #f8f9fa;
    color: #2d3748;
}

.share-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    padding: 1.5rem;
}

.share-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 2px solid #e9ecef;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #6c757d;
}

.share-option:hover {
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.share-option i {
    font-size: 1.5rem;
}

.share-option span {
    font-size: 0.9rem;
    font-weight: 600;
}

/* Empty State */
.no-meals-found {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 2rem;
}

.empty-state i {
    font-size: 3rem;
    color: #e9ecef;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.3rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6c757d;
    opacity: 0.8;
    font-size: 0.95rem;
}

/* Hostel Slider Styles */
.hostels-slider-container {
    position: relative;
    padding: 0 3rem;
}

.hostel-slide-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

/* Swiper Navigation */
.swiper-button-next,
.swiper-button-prev {
    color: var(--primary);
    background: rgba(255,255,255,0.9);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.swiper-button-next:after,
.swiper-button-prev:after {
    font-size: 1rem;
}

.swiper-pagination-bullet-active {
    background: var(--primary);
}

/* 🚨 MOBILE ADJUSTMENTS - EXACT SAME AS FEATURES PAGE */
@media (max-width: 768px) {
    .gallery-hero {
        margin: calc(60px + 0.25rem) auto 1rem auto !important;
        padding: 1.75rem 1rem;
        width: calc(100% - 2rem);
    }
    
    .gallery-hero h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .gallery-hero p {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .search-container {
        flex-direction: column;
        border-radius: 15px;
        padding: 0;
    }
    
    .search-input {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .search-btn {
        width: 100%;
        border-radius: 0 0 15px 15px;
    }
    
    .filter-tabs {
        flex-direction: column;
        align-items: center;
    }
    
    .filter-tab {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }
    
    .enhanced-food-grid {
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    
    .gallery-stats {
        grid-template-columns: 1fr;
        max-width: 300px;
    }
    
    .stat-item.compact {
        padding: 1.25rem 1rem;
    }
    
    .hostels-slider-container {
        padding: 0 1rem;
    }
    
    .hostel-logo {
        width: 60px !important;
        height: 60px !important;
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        display: none;
    }

    .share-options {
        grid-template-columns: 1fr;
    }

    /* Responsive adjustments for CTA */
    [style*="font-size: 2.5rem"] { font-size: 2rem !important; }
    [style*="font-size: 1.875rem"] { font-size: 1.5rem !important; }
    [style*="font-size: 1.25rem"] { font-size: 1.125rem !important; }
    [style*="font-size: 1.125rem"] { font-size: 1rem !important; }
}

@media (max-width: 480px) {
    .gallery-hero h1 {
        font-size: 1.75rem;
    }
    
    .card-content {
        padding: 1rem;
    }
    
    .meal-header {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .hostel-logo {
        width: 50px !important;
        height: 50px !important;
    }
    
    .hostel-info h4 {
        font-size: 1rem !important;
    }
    
    .share-modal-content {
        margin: 20% auto;
        width: 95%;
    }
}
</style>
@endpush

@section('content')

<div class="gallery-page-wrapper">
    <!-- 🚨 UPDATED: Hero Section - EXACT SAME SPACING AS FEATURES PAGE -->
    <section class="gallery-hero">
        <h1 class="nepali hero-title">विभिन्न होस्टलहरूको खानाको ग्यालरी</h1>
        <p class="nepali hero-subtitle">ताजा, स्वस्थ र स्वादिष्ट खानाको अनुभव</p>
        <div class="search-bar">
            <form action="{{ route('menu-gallery') }}" method="GET">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" placeholder="खाना वा होस्टलको नामले खोज्नुहोस्..." 
                           class="nepali search-input" value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        खोज्नुहोस्
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Enhanced Filters -->
    <section class="meal-filters">
        <div class="container">
            <div class="filters-container">
                <div class="filter-tabs">
                    <a href="{{ route('menu-gallery', ['type' => 'all']) }}" 
                       class="filter-tab {{ !request('type') || request('type') == 'all' ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        <span class="nepali">सबै खाना</span>
                    </a>
                    <a href="{{ route('menu-gallery', ['type' => 'breakfast']) }}" 
                       class="filter-tab {{ request('type') == 'breakfast' ? 'active' : '' }}">
                        <i class="fas fa-sun"></i>
                        <span class="nepali">विहानको खाना</span>
                    </a>
                    <a href="{{ route('menu-gallery', ['type' => 'lunch']) }}" 
                       class="filter-tab {{ request('type') == 'lunch' ? 'active' : '' }}">
                        <i class="fas fa-utensils"></i>
                        <span class="nepali">दिउसोको खाना</span>
                    </a>
                    <a href="{{ route('menu-gallery', ['type' => 'dinner']) }}" 
                       class="filter-tab {{ request('type') == 'dinner' ? 'active' : '' }}">
                        <i class="fas fa-moon"></i>
                        <span class="nepali">बेलुकाको खाना</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Food Gallery -->
    <section class="enhanced-food-gallery">
        <div class="container">
            <!-- ✅ FIXED: Compact Statistics Cards -->
            <div class="gallery-stats">
                <div class="stat-item compact">
                    <i class="fas fa-utensils"></i>
                    <div class="stat-content">
                        <span class="stat-number">{{ $mealMenus->count() }}</span>
                        <span class="nepali stat-label">खानाका प्रकार</span>
                    </div>
                </div>
                <div class="stat-item compact">
                    <i class="fas fa-hotel"></i>
                    <div class="stat-content">
                        <span class="stat-number">{{ $featuredHostels->count() }}</span>
                        <span class="nepali stat-label">होस्टलहरू</span>
                    </div>
                </div>
            </div>

            <div class="enhanced-food-grid">
                @forelse($mealMenus as $menu)
                <div class="enhanced-food-card">
                    <div class="card-image-container">
                        <div class="food-image-wrapper">
                            @if($menu->image)
                                <!-- ✅ FIXED: Reduced image height -->
                                <img src="{{ asset('storage/'.$menu->image) }}" 
                                     alt="{{ $menu->description }}" 
                                     class="food-image"
                                     style="width: 100%; height: 220px; object-fit: cover;">
                            @else
                                <img src="https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                                     alt="{{ $menu->description }}" 
                                     class="food-image"
                                     style="width: 100%; height: 220px; object-fit: cover;">
                            @endif
                            <div class="image-overlay">
                                <div class="meal-badge">
                                    <i class="fas fa-clock"></i>
                                    <span class="nepali">
                                        @if($menu->meal_type == 'breakfast')
                                            ७:०० - ९:०० बिहान
                                        @elseif($menu->meal_type == 'lunch')
                                            १२:०० - २:०० दिउँसो
                                        @else
                                            ६:०० - ८:०० बेलुका
                                        @endif
                                    </span>
                                </div>
                                <button class="quick-view-btn" data-meal-id="{{ $menu->id }}">
                                    <i class="fas fa-eye"></i>
                                    <span class="nepali">हेर्नुहोस्</span>
                                </button>
                            </div>
                        </div>
                        <div class="type-indicator {{ $menu->meal_type }}">
                            @if($menu->meal_type == 'breakfast')
                                <i class="fas fa-sun"></i>
                                <span class="nepali">विहानको खाना</span>
                            @elseif($menu->meal_type == 'lunch')
                                <i class="fas fa-utensils"></i>
                                <span class="nepali">दिउसोको खाना</span>
                            @else
                                <i class="fas fa-moon"></i>
                                <span class="nepali">बेलुकाको खाना</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <div class="meal-header">
                            <h3 class="nepali meal-title">{{ $menu->description }}</h3>
                            <!-- ❌ REMOVED: Hardcoded star ratings -->
                        </div>
                        
                        <div class="meal-meta">
                            <div class="meta-item">
                                <i class="fas fa-hotel"></i>
                                <span class="nepali">{{ $menu->hostel->name ?? 'होस्टल' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span class="nepali">
                                    @php
                                        $days = [
                                            'sunday' => 'आइतबार',
                                            'monday' => 'सोमबार', 
                                            'tuesday' => 'मङ्गलबार',
                                            'wednesday' => 'बुधबार',
                                            'thursday' => 'बिहिबार',
                                            'friday' => 'शुक्रबार',
                                            'saturday' => 'शनिबार'
                                        ];
                                    @endphp
                                    {{ $days[strtolower($menu->day_of_week)] ?? $menu->day_of_week }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="meal-items">
                            <h4 class="nepali items-title">खानाका वस्तुहरू:</h4>
                            <p class="nepali items-list">
                                {{ $menu->formatted_items ?? $menu->description }}
                            </p>
                        </div>
                        
                        <!-- ✅ FIXED: Removed Like button, Enhanced Share button -->
                        <div class="meal-actions">
                            <button class="action-btn share-btn" data-meal-id="{{ $menu->id }}" data-meal-description="{{ $menu->description }}">
                                <i class="fas fa-share-alt"></i>
                                <span class="nepali">सेयर गर्नुहोस्</span>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="no-meals-found">
                    <div class="empty-state">
                        <i class="fas fa-utensils"></i>
                        <h3 class="nepali">कुनै खानाको मेनु फेला परेन</h3>
                        <p class="nepali">कृपया फिल्टर परिवर्तन गर्नुहोस् वा पछि फेरि प्रयास गर्नुहोस्</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Hostel Slider Section -->
    <section class="featured-hostels-slider" style="background: #f8f9fa; padding: 4rem 0;">
        <div class="container">
            <h2 class="section-title nepali" style="text-align: center; font-size: 2.5rem; font-weight: 700; color: #2d3748; margin-bottom: 3rem;">
                खाना पोस्ट गर्ने होस्टलहरू
            </h2>
            
            @if($featuredHostels->count() > 0)
            <div class="hostels-slider-container" style="position: relative; padding: 0 3rem;">
                <div class="swiper hostels-slider">
                    <div class="swiper-wrapper">
                        @foreach($featuredHostels as $hostel)
                        <div class="swiper-slide">
                            <div class="hostel-slide-card" style="
                                background: white;
                                border-radius: 15px;
                                padding: 1.5rem;
                                text-align: center;
                                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                                transition: all 0.3s ease;
                                height: 100%;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                            ">
                                <div class="hostel-logo" style="
                                    width: 80px;
                                    height: 80px;
                                    border-radius: 50%;
                                    overflow: hidden;
                                    margin: 0 auto 1rem;
                                    border: 3px solid var(--primary);
                                    padding: 3px;
                                    background: white;
                                ">
                                    <img src="{{ $hostel->logo_path ? asset('storage/'.$hostel->logo_path) : 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80' }}" 
                                         alt="{{ $hostel->name }}" 
                                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                                
                                <div class="hostel-info" style="text-align: center;">
                                    <h4 class="nepali" style="font-size: 1.1rem; font-weight: 700; color: #2d3748; margin-bottom: 0.5rem;">
                                        {{ $hostel->name }}
                                    </h4>
                                    <p class="nepali hostel-location" style="
                                        color: #6c757d;
                                        font-size: 0.85rem;
                                        margin-bottom: 0;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        gap: 0.3rem;
                                    ">
                                        <i class="fas fa-map-marker-alt" style="font-size: 0.8rem;"></i>
                                        {{ $hostel->city ?? 'काठमाडौं' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Navigation buttons -->
                    <div class="swiper-button-next" style="
                        color: var(--primary);
                        background: rgba(255,255,255,0.9);
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    "></div>
                    <div class="swiper-button-prev" style="
                        color: var(--primary);
                        background: rgba(255,255,255,0.9);
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    "></div>
                    
                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            @else
            <div class="no-hostels" style="text-align: center; padding: 2rem;">
                <p class="nepali" style="color: #6c757d; font-size: 1.1rem;">
                    अहिले कुनै होस्टलले खानाको मेनु पोस्ट गरेको छैन।
                </p>
            </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" style="padding: 4rem 0; background: white;">
        <div class="container">
            <div style="
                text-align: center;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                color: white;
                padding: 3rem 2rem;
                border-radius: 1rem;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
                max-width: 800px;
                margin: 0 auto;
            ">
                <h2 class="nepali" style="font-size: 1.875rem; font-weight: bold; margin-bottom: 1rem;">
                    के तपाईं पनि आफ्नो होस्टलका खानाहरू यसरी सार्वजनिक देखाउन चाहनुहुन्छ?
                </h2>
                <p class="nepali" style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9;">
                    HostelHub मा आफ्नो होस्टल दर्ता गर्नुहोस् र आफ्ना स्वादिष्ट खानाहरू सबैलाई देखाउनुहोस्
                </p>
                
                <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
                    <a href="/register/organization" class="nepali" style="
                        background-color: white;
                        color: #001F5B;
                        font-weight: 600;
                        padding: 0.75rem 2rem;
                        border-radius: 0.5rem;
                        text-decoration: none;
                        min-width: 180px;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                        transition: all 0.3s ease;
                    " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.transform='translateY(-2px)';"
                       onmouseout="this.style.backgroundColor='white'; this.style.transform='none';">
                        Hostel Owner बन्नुहोस्
                    </a>
                    <a href="/features" class="nepali" style="
                        border: 2px solid white;
                        color: white;
                        font-weight: 600;
                        padding: 0.75rem 2rem;
                        border-radius: 0.5rem;
                        text-decoration: none;
                        min-width: 180px;
                        background-color: transparent;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.backgroundColor='white'; this.style.color='#001F5B'; this.style.transform='translateY(-2px)';"
                       onmouseout="this.style.backgroundColor='transparent'; this.style.color='white'; this.style.transform='none';">
                        विशेषताहरू हेर्नुहोस्
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ NEW: Share Modal -->
    <div id="shareModal" class="share-modal">
        <div class="share-modal-content">
            <div class="share-modal-header">
                <h3 class="nepali">सेयर गर्नुहोस्</h3>
                <button class="share-modal-close">&times;</button>
            </div>
            <div class="share-options">
                <button class="share-option" data-platform="facebook">
                    <i class="fab fa-facebook"></i>
                    <span class="nepali">Facebook</span>
                </button>
                <button class="share-option" data-platform="twitter">
                    <i class="fab fa-twitter"></i>
                    <span class="nepali">Twitter</span>
                </button>
                <button class="share-option" data-platform="whatsapp">
                    <i class="fab fa-whatsapp"></i>
                    <span class="nepali">WhatsApp</span>
                </button>
                <button class="share-option" data-platform="copy">
                    <i class="fas fa-link"></i>
                    <span class="nepali">लिङ्क कपी गर्नुहोस्</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Swiper JS -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Hostel Slider
    const hostelSwiper = new Swiper('.hostels-slider', {
        slidesPerView: 2,
        spaceBetween: 20,
        loop: {{ $featuredHostels->count() > 1 ? 'true' : 'false' }},
        autoplay: {{ $featuredHostels->count() > 1 ? '{
            delay: 3000,
            disableOnInteraction: false,
        }' : 'false' }},
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 25,
            },
            1024: {
                slidesPerView: 6,
                spaceBetween: 30,
            },
        },
    });

    // ✅ NEW: Share Functionality
    const shareModal = document.getElementById('shareModal');
    const shareButtons = document.querySelectorAll('.share-btn');
    const closeModal = document.querySelector('.share-modal-close');
    const shareOptions = document.querySelectorAll('.share-option');
    
    let currentShareUrl = '';
    let currentShareText = '';

    // Open share modal
    shareButtons.forEach(button => {
        button.addEventListener('click', function() {
            const mealId = this.dataset.mealId;
            const mealDescription = this.dataset.mealDescription;
            
            // Generate share URL (using current page URL for now)
            currentShareUrl = window.location.href;
            currentShareText = `HostelHub मा यो खाना हेर्नुहोस्: ${mealDescription}`;
            
            // Show modal
            shareModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    });

    // Close modal
    closeModal.addEventListener('click', function() {
        shareModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === shareModal) {
            shareModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Handle share options
    shareOptions.forEach(option => {
        option.addEventListener('click', function() {
            const platform = this.dataset.platform;
            
            switch(platform) {
                case 'facebook':
                    shareOnFacebook();
                    break;
                case 'twitter':
                    shareOnTwitter();
                    break;
                case 'whatsapp':
                    shareOnWhatsApp();
                    break;
                case 'copy':
                    copyLink();
                    break;
            }
            
            // Close modal after sharing
            setTimeout(() => {
                shareModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }, 500);
        });
    });

    // Share functions
    function shareOnFacebook() {
        const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentShareUrl)}`;
        window.open(url, '_blank', 'width=600,height=400');
    }

    function shareOnTwitter() {
        const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(currentShareText)}&url=${encodeURIComponent(currentShareUrl)}`;
        window.open(url, '_blank', 'width=600,height=400');
    }

    function shareOnWhatsApp() {
        const url = `https://wa.me/?text=${encodeURIComponent(currentShareText + ' ' + currentShareUrl)}`;
        window.open(url, '_blank', 'width=600,height=400');
    }

    function copyLink() {
        navigator.clipboard.writeText(currentShareUrl).then(() => {
            // Show success message
            const originalText = document.querySelector('[data-platform="copy"] span').textContent;
            document.querySelector('[data-platform="copy"] span').textContent = 'लिङ्क कपी भयो!';
            
            setTimeout(() => {
                document.querySelector('[data-platform="copy"] span').textContent = originalText;
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('लिङ्क कपी गर्न असफल भयो। कृपया हातले कपी गर्नुहोस्।');
        });
    }

    // Web Share API (for modern browsers)
    function shareWithWebAPI(mealDescription) {
        if (navigator.share) {
            navigator.share({
                title: 'HostelHub - खानाको ग्यालरी',
                text: mealDescription,
                url: window.location.href,
            })
            .then(() => console.log('Successful share'))
            .catch((error) => console.log('Error sharing:', error));
        } else {
            // Fallback to modal
            console.log('Web Share API not supported');
        }
    }
});
</script>
@endpush