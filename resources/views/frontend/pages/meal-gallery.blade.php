@extends('layouts.frontend')

@section('content')
    <!-- Enhanced Hero Section -->
    <section class="meal-hero" style="padding-top: 120px;">
        <div class="container">
            <div class="hero-content">
                <h1 class="nepali hero-title">हाम्रो खानाको ग्यालरी</h1>
                <p class="nepali hero-subtitle">ताजा, स्वस्थ र स्वादिष्ट खानाको अनुभव</p>
                <div class="search-bar">
                    <form action="{{ route('menu-gallery') }}" method="GET">
                        <div class="search-container">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="search" placeholder="खाना वा होस्टलको नामले खोज्नुहोस्..." 
                                   class="nepali search-input" value="{{ request('search') }}">
                            <button type="submit" class="search-btn">खोज्नुहोस्</button>
                        </div>
                    </form>
                </div>
            </div>
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
            <div class="gallery-stats">
                <div class="stat-item">
                    <i class="fas fa-utensils"></i>
                    <span class="stat-number">{{ $mealMenus->count() }}</span>
                    <span class="nepali stat-label">खानाका प्रकार</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-hotel"></i>
                    <span class="stat-number">{{ $mealMenus->unique('hostel_id')->count() }}</span>
                    <span class="nepali stat-label">होस्टलहरू</span>
                </div>
            </div>

            <div class="enhanced-food-grid">
                @forelse($mealMenus as $menu)
                <div class="enhanced-food-card">
                    <div class="card-image-container">
                        <div class="food-image-wrapper">
                            @if($menu->image)
                                <img src="{{ asset('storage/'.$menu->image) }}" 
                                     alt="{{ $menu->description }}" class="food-image">
                            @else
                                <img src="https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                                     alt="{{ $menu->description }}" class="food-image">
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
                            <div class="meal-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-text">4.5</span>
                            </div>
                        </div>
                        
                        <div class="meal-meta">
                            <div class="meta-item">
                                <i class="fas fa-hotel"></i>
                                <span class="nepali">{{ $menu->hostel->name ?? 'होस्टल' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span class="nepali">{{ $menu->day_of_week }}</span>
                            </div>
                        </div>
                        
                        <div class="meal-items">
                            <h4 class="nepali items-title">खानाका वस्तुहरू:</h4>
                            <p class="nepali items-list">
                                {{ $menu->formatted_items ?? $menu->description }}
                            </p>
                        </div>
                        
                        <div class="meal-actions">
                            <button class="action-btn favorite-btn" data-meal-id="{{ $menu->id }}">
                                <i class="far fa-heart"></i>
                                <span class="nepali">मनपर्ने</span>
                            </button>
                            <button class="action-btn share-btn" data-meal-id="{{ $menu->id }}">
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

    <!-- Featured Hostels Section -->
    <section class="featured-hostels">
        <div class="container">
            <h2 class="section-title nepali">हाम्रा होस्टलहरू</h2>
            <div class="hostels-grid">
                @foreach($mealMenus->unique('hostel_id')->take(4) as $menu)
                @if($menu->hostel)
                <div class="hostel-card">
                    <div class="hostel-image">
                        <img src="{{ $menu->hostel->logo ? asset('storage/'.$menu->hostel->logo) : 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}" 
                             alt="{{ $menu->hostel->name }}">
                    </div>
                    <div class="hostel-info">
                        <h4 class="nepali">{{ $menu->hostel->name }}</h4>
                        <p class="nepali hostel-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $menu->hostel->location ?? 'काठमाडौं' }}
                        </p>
                        <div class="hostel-rating">
                            <span class="rating">4.5</span>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
/* Fix Header Overlap - Added padding top */
.meal-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 120px 0 60px; /* Increased top padding to 120px */
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.meal-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
    opacity: 0.3;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.9;
    margin-bottom: 2.5rem;
}

.search-container {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
    background: white;
    border-radius: 50px;
    padding: 5px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 20px;
    color: #667eea;
    font-size: 1.1rem;
}

/* FIX: Search input text color - changed to black */
.search-input {
    flex: 1;
    border: none;
    padding: 15px 20px 15px 50px;
    font-size: 1rem;
    background: transparent;
    outline: none;
    color: #000000; /* Changed from default to black */
}

/* FIX: Placeholder text color */
.search-input::placeholder {
    color: #666; /* Dark gray for placeholder */
    opacity: 1; /* Ensure full opacity */
}

.search-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
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
    background: #667eea;
    color: white;
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

/* Enhanced Food Gallery */
.enhanced-food-gallery {
    padding: 4rem 0;
    background: white;
}

.gallery-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.stat-item {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
}

.stat-item i {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.9;
}

.enhanced-food-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
}

.enhanced-food-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
}

.enhanced-food-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.card-image-container {
    position: relative;
    height: 250px;
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
    transform: scale(1.1);
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
    padding: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.enhanced-food-card:hover .image-overlay {
    opacity: 1;
}

.meal-badge {
    background: rgba(255,255,255,0.9);
    padding: 8px 15px;
    border-radius: 20px;
    color: #333;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quick-view-btn {
    background: #667eea;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.quick-view-btn:hover {
    background: #5a6fd8;
    transform: translateY(-2px);
}

.type-indicator {
    position: absolute;
    top: 1rem;
    left: 1rem;
    padding: 8px 15px;
    border-radius: 20px;
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
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

.card-content {
    padding: 1.5rem;
}

.meal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.meal-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
    line-height: 1.3;
}

.meal-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stars {
    color: #FFD700;
}

.rating-text {
    font-weight: 700;
    color: #2d3748;
}

.meal-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 0.9rem;
}

.meal-items {
    margin-bottom: 1.5rem;
}

.items-title {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.items-list {
    color: #6c757d;
    line-height: 1.5;
    margin: 0;
}

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
}

.action-btn:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
    transform: translateY(-2px);
}

/* Empty State */
.no-meals-found {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state i {
    font-size: 4rem;
    color: #e9ecef;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6c757d;
    opacity: 0.8;
}

/* Featured Hostels */
.featured-hostels {
    background: #f8f9fa;
    padding: 4rem 0;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 3rem;
}

.hostels-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.hostel-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.hostel-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.hostel-image {
    height: 150px;
    overflow: hidden;
}

.hostel-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hostel-info {
    padding: 1.5rem;
}

.hostel-info h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.hostel-location {
    color: #6c757d;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.hostel-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #FFD700;
    font-weight: 700;
}

/* Responsive Design */
@media (max-width: 768px) {
    .meal-hero {
        padding: 100px 0 40px; /* Adjusted for mobile */
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
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
        gap: 1.5rem;
    }
    
    .gallery-stats {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .meal-hero {
        padding: 80px 0 30px; /* Adjusted for small mobile */
    }
    
    .hero-title {
        font-size: 1.8rem;
    }
    
    .card-content {
        padding: 1rem;
    }
    
    .meal-header {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
@endpush