@extends('layouts.frontend')

@section('page-title', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Full Gallery | HostelHub')

@section('page-header', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Full Gallery')
@section('page-description', 'हाम्रो होस्टलको सम्पूर्ण सुविधाहरू, कोठाहरू, र भिडियोहरूको दृश्यात्मक अनुभव')

@section('content')
@php
    // PERMANENT FIX: Nepali room types
    $nepaliRoomTypes = [
        '1 seater' => '१ सिटर',
        '2 seater' => '२ सिटर', 
        '3 seater' => '३ सिटर',
        '4 seater' => '४ सिटर',
        'other' => 'अन्य (५+ सिटर)'
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
@endphp

<style>
    /* Gallery Specific Styles */
    .gallery-section {
        padding: 80px 0 60px;
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
    
    .gallery-tabs {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 40px;
        border-bottom: 2px solid var(--border);
        padding-bottom: 20px;
    }
    
    .tab-btn {
        padding: 12px 28px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        color: var(--text-dark);
        font-size: 1rem;
    }
    
    .tab-btn.active, .tab-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
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
    
    .view-more {
        text-align: center;
        margin-top: 40px;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    /* Gallery Categories - Fixed Top Alignment */
    .gallery-categories {
        background: var(--bg-light);
        padding: 60px 0 80px;
        margin-top: 0;
        min-height: auto;
        display: block;
    }
    
    .main-description {
        text-align: center;
        margin: 0 auto 60px;
        color: var(--text-dark);
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.3;
        max-width: 900px;
        padding: 40px 20px 0;
    }
    
    .category-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 25px;
        margin-top: 0;
        align-items: stretch;
    }
    
    .category-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        text-align: center;
        padding: 30px 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .category-icon {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: var(--primary);
    }
    
    .category-title {
        font-size: 1.3rem;
        margin-bottom: 12px;
        color: var(--text-dark);
        font-weight: 600;
    }
    
    .category-count {
        background: var(--primary);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 12px;
    }
    
    /* Meal Gallery Styles */
    .meal-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .meal-item-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .meal-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .meal-image {
        position: relative;
        height: 200px;
        overflow: hidden;
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
        background: var(--accent);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .meal-content {
        padding: 20px;
    }
    
    .meal-content h4 {
        font-size: 1.3rem;
        margin-bottom: 8px;
        color: var(--text-dark);
        font-weight: 600;
    }
    
    .meal-day {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1rem;
    }
    
    .meal-items {
        color: var(--text-dark);
        margin-bottom: 15px;
        line-height: 1.5;
    }
    
    .meal-time {
        color: var(--text-light);
        font-size: 0.9rem;
        padding-top: 10px;
        border-top: 1px solid var(--border);
    }
    
    .no-meals {
        text-align: center;
        padding: 60px 20px;
        grid-column: 1 / -1;
    }
    
    .no-meals p {
        font-size: 1.1rem;
        color: var(--text-light);
    }
    
    /* Modal Styles */
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
    }
    
    .modal-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        background: black;
    }
    
    .modal-content img, .modal-content video {
        width: 100%;
        height: auto;
        display: block;
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
    }
    
    .close-modal:hover {
        background: rgba(0, 0, 0, 0.9);
    }
    
    .modal-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 20px;
        transform: translateY(100%);
        transition: transform 0.3s;
    }
    
    .modal-content:hover .modal-caption {
        transform: translateY(0);
    }

    /* Hidden items for view more functionality */
    .gallery-item.hidden-item {
        display: none;
    }

    .view-more-items .gallery-item {
        display: block !important;
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .category-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .meal-gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }
    
    @media (max-width: 992px) {
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
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
        
        .category-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .gallery-section {
            padding: 60px 0 40px;
        }
        
        .gallery-categories {
            padding: 40px 0 60px;
        }
        
        .view-more {
            flex-direction: column;
            align-items: center;
        }
        
        .main-description {
            font-size: 1.6rem;
            margin-bottom: 40px;
            padding: 20px 15px 0;
        }
        
        .category-icon {
            font-size: 2.2rem;
        }
        
        .category-title {
            font-size: 1.2rem;
        }
        
        .gallery-tabs {
            flex-direction: column;
            align-items: center;
        }
        
        .tab-btn {
            width: 100%;
            max-width: 300px;
            text-align: center;
        }
        
        .meal-gallery-grid {
            grid-template-columns: 1fr;
            gap: 20px;
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
        
        .main-description {
            font-size: 1.4rem;
            padding: 15px 10px 0;
        }
        
        .gallery-categories {
            padding: 30px 0 40px;
        }
        
        .category-card {
            padding: 25px 15px;
        }
        
        .category-icon {
            font-size: 2rem;
        }
        
        .meal-content {
            padding: 15px;
        }
        
        .meal-content h4 {
            font-size: 1.2rem;
        }
    }
</style>

<!-- Gallery Categories -->
<section class="gallery-categories">
    <div class="container">
        <div class="main-description nepali">
            हाम्रो होस्टलको सुन्दर वातावरण र उत्कृष्ट सुविधाहरूको दृश्यात्मक अनुभव
        </div>
        
        <div class="category-grid">
            <div class="category-card">
                <div class="category-icon">🛏️</div>
                <h3 class="category-title nepali">कोठाहरू</h3>
                <p class="nepali">१, २, ३, ४ र अन्य सिटर कोठाहरू</p>
                <span class="category-count nepali">{{ $categoryCounts['rooms'] }} फोटोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🍳</div>
                <h3 class="category-title nepali">किचन</h3>
                <p class="nepali">आधुनिक किचन सुविधा</p>
                <span class="category-count nepali">{{ $categoryCounts['kitchen'] }} फोटोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🚽</div>
                <h3 class="category-title nepali">अन्य सुविधाहरू</h3>
                <p class="nepali">अध्ययन क्षेत्र, शौचालय, र अन्य</p>
                <span class="category-count nepali">{{ $categoryCounts['facilities'] }} फोटोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🎬</div>
                <h3 class="category-title nepali">भिडियो टुर</h3>
                <p class="nepali">होस्टलको पूर्ण टुर</p>
                <span class="category-count nepali">{{ $categoryCounts['video'] }} भिडियोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🍽️</div>
                <h3 class="category-title nepali">खानाको मेनु</h3>
                <p class="nepali">दैनिक खानाको विवरण</p>
                <span class="category-count nepali">{{ $categoryCounts['meal'] }} मेनुहरू</span>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <h2 class="section-title nepali">हाम्रो होस्टल ग्यालरी</h2>
        <p style="text-align: center; margin-bottom: 40px; color: var(--text-dark); opacity: 0.8; max-width: 700px; margin-left: auto; margin-right: auto;" class="nepali">
            विभिन्न कोठा र सुविधाहरूको विस्तृत दृश्यहरू
        </p>
        
        <!-- Gallery Tabs -->
        <div class="gallery-tabs">
            <button class="tab-btn active nepali" data-tab="photo-gallery">तस्बिरहरू</button>
            <button class="tab-btn nepali" data-tab="video-gallery">भिडियोहरू</button>
            <button class="tab-btn nepali" data-tab="meal-gallery">खानाको ग्यालरी</button>
        </div>
        
        <!-- Photo Gallery Tab -->
        <div class="tab-content active" id="photo-gallery">
            <!-- PERMANENT FIX: Updated filters with all room types -->
            <div class="gallery-filters">
                <button class="filter-btn active nepali" data-filter="all">सबै</button>
                <button class="filter-btn nepali" data-filter="1-seater">१ सिटर</button>
                <button class="filter-btn nepali" data-filter="2-seater">२ सिटर</button>
                <button class="filter-btn nepali" data-filter="3-seater">३ सिटर</button>
                <button class="filter-btn nepali" data-filter="4-seater">४ सिटर</button>
                <button class="filter-btn nepali" data-filter="other">अन्य</button>
                <button class="filter-btn nepali" data-filter="facilities">सुविधाहरू</button>
            </div>
            
            <div class="gallery-grid" id="mainGallery">
                @php
                    $displayedItems = 0;
                    $maxInitialDisplay = 8;
                @endphp
                
                @foreach($activeGalleries->whereIn('media_type', ['photo']) as $gallery)
                    @php
                        // PERMANENT FIX: Determine category for filtering with all room types
                        $filterCategory = '';
                        if (in_array($gallery->category, ['1 seater', '2 seater', '3 seater', '4 seater', 'other'])) {
                            $filterCategory = str_replace(' ', '-', $gallery->category);
                        } else {
                            $filterCategory = 'facilities';
                        }

                        $displayedItems++;
                        $isHidden = $displayedItems > $maxInitialDisplay;
                    @endphp

                    <div class="gallery-item {{ $isHidden ? 'hidden-item' : '' }}" 
                         data-category="{{ $filterCategory }}"
                         data-gallery-id="{{ $gallery->id }}">
                        
                        <img src="{{ $gallery->thumbnail_url ?? $gallery->media_url }}" alt="{{ $gallery->title }}" loading="lazy">

                        @if($gallery->is_featured)
                            <div class="featured-badge nepali">Featured</div>
                        @endif

                        <div class="gallery-overlay">
                            <h3 class="gallery-title nepali">{{ $gallery->title }}</h3>
                            <p class="nepali">{{ $gallery->description }}</p>
                            <button class="btn btn-primary" 
                                    style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" 
                                    onclick="openModal('{{ $gallery->id }}', '{{ $gallery->media_type }}')">
                                विस्तृत हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                @endforeach

                @if($activeGalleries->whereIn('media_type', ['photo'])->count() === 0)
                    <div class="text-center py-12 col-span-full">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-images text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-600 nepali mb-2">कुनै तस्बिरहरू छैनन्</h3>
                        <p class="text-gray-500 nepali">यस होस्टलको तस्बिर ग्यालरी चाँहि उपलब्ध छैन।</p>
                    </div>
                @endif
            </div>
            
            @if($activeGalleries->whereIn('media_type', ['photo'])->count() > $maxInitialDisplay)
                <div class="view-more">
                    <button class="btn btn-outline nepali" 
                            style="border-color: var(--primary); color: var(--primary);"
                            onclick="showMoreGallery()">
                        थप तस्बिर हेर्नुहोस्
                    </button>
                    <a href="{{ route('contact') }}" class="btn btn-primary nepali">सम्पर्क गर्नुहोस्</a>
                </div>
            @endif
        </div>
        
        <!-- Video Gallery Tab -->
        <div class="tab-content" id="video-gallery">
            <div class="gallery-grid">
                @foreach($activeGalleries->whereIn('media_type', ['local_video', 'external_video']) as $gallery)
                    <div class="gallery-item" data-gallery-id="{{ $gallery->id }}">
                        
                        @if($gallery->media_type === 'local_video')
                            <img src="{{ $gallery->thumbnail_url ?? asset('images/video-default.jpg') }}" alt="{{ $gallery->title }}" loading="lazy">
                        @elseif($gallery->media_type === 'external_video')
                            <img src="{{ $gallery->thumbnail_url ?? asset('images/video-default.jpg') }}" alt="{{ $gallery->title }}" loading="lazy">
                        @endif

                        @if($gallery->is_featured)
                            <div class="featured-badge nepali">Featured</div>
                        @endif

                        <div class="gallery-overlay">
                            <h3 class="gallery-title nepali">{{ $gallery->title }}</h3>
                            <p class="nepali">{{ $gallery->description }}</p>
                            <button class="btn btn-primary" 
                                    style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" 
                                    onclick="openModal('{{ $gallery->id }}', '{{ $gallery->media_type }}')">
                                भिडियो हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                @endforeach

                @if($activeGalleries->whereIn('media_type', ['local_video', 'external_video'])->count() === 0)
                    <div class="text-center py-12 col-span-full">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-video text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-600 nepali mb-2">कुनै भिडियोहरू छैनन्</h3>
                        <p class="text-gray-500 nepali">यस होस्टलको भिडियो ग्यालरी चाँहि उपलब्ध छैन।</p>
                    </div>
                @endif
            </div>
            
            @if($activeGalleries->whereIn('media_type', ['local_video', 'external_video'])->count() > 0)
                <div class="view-more">
                    <a href="{{ route('contact') }}" class="btn btn-primary nepali">सम्पर्क गर्नुहोस्</a>
                </div>
            @endif
        </div>
        
        <!-- Meal Gallery Tab -->
        <div class="tab-content" id="meal-gallery">
            <div class="meal-gallery-grid">
                @if(isset($mealMenus) && $mealMenus->count() > 0)
                    @foreach($mealMenus as $menu)
                    <div class="meal-item-card">
                        <div class="meal-image">
                            @if($menu->image)
                                <img src="{{ asset('storage/'.$menu->image) }}" 
                                     alt="{{ $menu->description }}" 
                                     style="width:100%; height:200px; object-fit:cover;">
                            @else
                                <img src="https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                                     alt="{{ $menu->description }}"
                                     style="width:100%; height:200px; object-fit:cover;">
                            @endif
                            <span class="meal-type-badge">
                                @if($menu->meal_type == 'breakfast')
                                    विहानको खाना
                                @elseif($menu->meal_type == 'lunch')
                                    दिउसोको खाना
                                @else
                                    बेलुकाको खाना
                                @endif
                            </span>
                        </div>
                        <div class="meal-content">
                            <h4 class="nepali">
                                @if($menu->meal_type == 'breakfast')
                                    विहानको खाना
                                @elseif($menu->meal_type == 'lunch')
                                    दिउसोको खाना
                                @else
                                    बेलुकाको खाना
                                @endif
                            </h4>
                            <p class="meal-day nepali">{{ $menu->day_of_week }}</p>
                            <p class="meal-items nepali">
                                {{ $menu->formatted_items ?? $menu->description }}
                            </p>
                            <div class="meal-time nepali">
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
                    <div class="no-meals">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-utensils text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-600 nepali mb-2">कुनै खानाको मेनु छैन</h3>
                        <p class="text-gray-500 nepali">यस होस्टलको लागि कुनै खानाको मेनु उपलब्ध छैन।</p>
                    </div>
                @endif
            </div>
            
            @if(isset($mealMenus) && $mealMenus->count() > 0)
                <div class="view-more">
                    <a href="{{ route('contact') }}" class="btn btn-primary nepali">सम्पर्क गर्नुहोस्</a>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Modals -->
<div class="gallery-modal" id="imageModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <img id="modalImage" src="" alt="">
        <div class="modal-caption">
            <h3 id="modalTitle" class="nepali"></h3>
            <p id="modalDescription" class="nepali"></p>
        </div>
    </div>
</div>

<div class="gallery-modal" id="videoModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <video id="modalVideo" controls>
            <source src="" type="video/mp4">
            तपाईंको ब्राउजरले भिडियो सपोर्ट गर्दैन।
        </video>
        <div class="modal-caption">
            <h3 id="videoTitle" class="nepali"></h3>
            <p id="videoDescription" class="nepali"></p>
        </div>
    </div>
</div>

<div class="gallery-modal" id="youtubeModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <iframe id="modalYouTube" width="100%" height="400" frameborder="0" allowfullscreen></iframe>
        <div class="modal-caption">
            <h3 id="youtubeTitle" class="nepali"></h3>
            <p id="youtubeDescription" class="nepali"></p>
        </div>
    </div>
</div>

<script>
    // Gallery data from backend
    const galleryData = {
        @foreach($activeGalleries as $gallery)
        '{{ $gallery->id }}': {
            title: '{{ addslashes($gallery->title) }}',
            description: '{{ addslashes($gallery->description) }}',
            media_type: '{{ $gallery->media_type }}',
            media_url: '{{ $gallery->media_type === 'external_video' ? addslashes($gallery->external_link) : addslashes($gallery->media_url) }}',
            thumbnail_url: '{{ addslashes($gallery->thumbnail_url) }}',
            youtube_embed_url: '{{ addslashes($gallery->youtube_embed_url) }}'
        },
        @endforeach
    };

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
            });
        });

        // Gallery Filter Functionality (for photo tab only)
        const filterButtons = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('#photo-gallery .gallery-item');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');
                
                const filterValue = button.getAttribute('data-filter');
                
                galleryItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
    
    // Show More Gallery Functionality
    function showMoreGallery() {
        const hiddenItems = document.querySelectorAll('#photo-gallery .gallery-item.hidden-item');
        hiddenItems.forEach(item => {
            item.classList.remove('hidden-item');
        });
        
        // Hide the show more button
        document.querySelector('#photo-gallery .view-more').style.display = 'none';
    }
    
    // Modal Functionality with Dynamic Content
    function openModal(galleryId, mediaType) {
        const gallery = galleryData[galleryId];
        if (!gallery) return;

        if (mediaType === 'photo') {
            document.getElementById('imageModal').style.display = 'flex';
            document.getElementById('modalImage').src = gallery.media_url;
            document.getElementById('modalTitle').textContent = gallery.title;
            document.getElementById('modalDescription').textContent = gallery.description;
        } else if (mediaType === 'local_video') {
            document.getElementById('videoModal').style.display = 'flex';
            document.getElementById('modalVideo').src = gallery.media_url;
            document.getElementById('videoTitle').textContent = gallery.title;
            document.getElementById('videoDescription').textContent = gallery.description;
        } else if (mediaType === 'external_video') {
            document.getElementById('youtubeModal').style.display = 'flex';
            document.getElementById('modalYouTube').src = gallery.youtube_embed_url || gallery.media_url;
            document.getElementById('youtubeTitle').textContent = gallery.title;
            document.getElementById('youtubeDescription').textContent = gallery.description;
        }
    }
    
    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.getElementById('videoModal').style.display = 'none';
        document.getElementById('youtubeModal').style.display = 'none';
        
        // Pause video when closing modal
        const video = document.getElementById('modalVideo');
        if (video) {
            video.pause();
        }
        
        // Reset YouTube iframe
        const youtubeIframe = document.getElementById('modalYouTube');
        if (youtubeIframe) {
            youtubeIframe.src = '';
        }
    }
    
    // Close modal when clicking outside the content
    window.addEventListener('click', function(event) {
        const imageModal = document.getElementById('imageModal');
        const videoModal = document.getElementById('videoModal');
        const youtubeModal = document.getElementById('youtubeModal');
        
        if (event.target === imageModal) {
            closeModal();
        }
        
        if (event.target === videoModal) {
            closeModal();
        }

        if (event.target === youtubeModal) {
            closeModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection