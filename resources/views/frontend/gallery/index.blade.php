@extends('layouts.frontend')

@section('page-title', 'ग्यालरी - HostelHub')

@push('styles')
@vite(['resources/css/gallery.css'])
<style>
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
    
    /* 🚨 UPDATED: Gallery Header - MOVED UP CLOSER TO TOP */
    .gallery-header {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2.5rem 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 1000px;
        width: 90%;
        
        /* 🚨 CHANGED: Reduced top spacing significantly */
        margin: calc(var(--header-height, 70px) + 0.9rem) auto 1.5rem auto !important;
        
    }
    
    .gallery-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem; /* Reduced from 1rem */
    }
    
    .gallery-header p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 800px;
        margin: 0 auto 0.75rem auto; /* Reduced bottom margin */
    }

    /* 🆕 Meal Gallery Button Styles - Spacing reduced */
    .meal-gallery-button-container {
        text-align: center;
        margin: 0.75rem 0 0 0; /* Reduced from 1.5rem */
    }
    
    .meal-gallery-button {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    
    .meal-gallery-button:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* 🚨 IMPORTANT: Override main content spacing for gallery page only */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    /* Gallery filters को spacing पनि घटाउने */
    .gallery-filters {
        padding-top: 0.5rem !important; /* Reduced from 1rem */
        max-width: 1200px;
        margin: 0 auto 1.5rem auto; /* Reduced bottom margin */
        width: 95%;
    }

    .hostel-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 31, 91, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        z-index: 10;
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .hostel-badge i {
        font-size: 0.7rem;
    }

    .hostel-filter {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .gallery-item {
        position: relative;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .room-number-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(34, 197, 94, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 500;
        z-index: 10;
        backdrop-filter: blur(4px);
    }

    .video-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .hidden {
        display: none !important;
    }

    /* 🚨 CTA Section Button Loading Styles */
    .gallery-trial-button.loading {
        position: relative;
        color: transparent;
    }
    
    .gallery-trial-button.loading::after {
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
    
    .gallery-trial-button[disabled] {
        background: rgba(255, 255, 255, 0.3);
        color: rgba(255, 255, 255, 0.7);
        cursor: not-allowed;
        transform: none !important;
    }
    
    .gallery-trial-button[disabled]:hover {
        background: rgba(255, 255, 255, 0.3);
        color: rgba(255, 255, 255, 0.7);
        transform: none !important;
        box-shadow: none !important;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Mobile adjustments for tighter spacing */
    @media (max-width: 768px) {
        .gallery-header {
            margin: calc(60px + 0.25rem) auto 1rem auto !important; /* Even tighter on mobile */
            padding: 1.75rem 1rem; /* Reduced padding */
            width: calc(100% - 2rem);
        }
        
        .gallery-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .gallery-header p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .meal-gallery-button {
            padding: 0.6rem 1.25rem;
            font-size: 0.9rem;
        }
        
        .meal-gallery-button-container {
            margin: 0.5rem 0 0 0; /* Even tighter on mobile */
        }
        
        .hostel-badge {
            font-size: 0.7rem;
            padding: 3px 6px;
        }
        
        .room-number-badge {
            font-size: 0.6rem;
            padding: 2px 6px;
        }

        .video-badge {
            padding: 6px 12px;
            font-size: 0.7rem;
        }

        .gallery-filters {
            padding-top: 0.25rem !important;
            margin: 0 auto 1rem auto;
        }
    }

    /* Modal Styles */
    .gallery-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .gallery-modal.active {
        display: flex;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        backdrop-filter: blur(10px);
    }

    .modal-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        backdrop-filter: blur(10px);
    }

    .modal-prev {
        left: 20px;
    }

    .modal-next {
        right: 20px;
    }

    .modal-content {
        max-width: 90%;
        max-height: 80%;
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    .modal-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 1rem;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }

    .gallery-modal.active .modal-info {
        transform: translateY(0);
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #001f5b;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    .gallery-main {
        padding-top: 0 !important;
        max-width: 1200px;
        margin: 0 auto;
        width: 95%;
    }

    /* 🚨 CTA SECTION - FIXED BUTTONS */
    .gallery-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1rem;
    }

    .gallery-cta-section {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2.5rem 2rem;
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

    .gallery-cta-buttons-container {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        width: 100%;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .gallery-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .gallery-cta-section {
            padding: 2rem 1.5rem;
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
        }
    }

    @media (max-width: 480px) {
        .gallery-cta-wrapper {
            padding: 0.75rem 1rem 1.25rem 1rem;
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

    /* 🆕 Stats section spacing adjustment */
    .gallery-stats {
        margin: 1.5rem auto 2.5rem auto; /* Adjusted spacing */
        max-width: 1200px;
        width: 95%;
    }

    @media (max-width: 768px) {
        .gallery-stats {
            margin: 1rem auto 2rem auto;
        }
    }
</style>
@endpush

@section('content')

<!-- Updated Hero Section - MOVED UP CLOSER TO TOP -->
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

            <div class="filter-controls">
                <div class="filter-categories">
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
                    <button class="filter-btn nepali" data-filter="भिडियो टुर">भिडियो टुर</button>
                </div>
                
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input nepali" placeholder="कोठा, सुविधा, होस्टल वा स्थान खोज्नुहोस्...">
                </div>
            </div>
        </div>
    </section>

    <!-- Main Gallery Section WITH HOSTEL BADGES -->
    <section class="gallery-main">
        <div class="gallery-container">
            <!-- Gallery Grid -->
            <div class="gallery-grid">
                @forelse($galleries as $gallery)
                @php
                    // Check if $gallery is array or object and access accordingly
                    $isArray = is_array($gallery);
                    $mediaType = $isArray ? $gallery['media_type'] : $gallery->media_type;
                    $categoryNepali = $isArray ? $gallery['category_nepali'] : $gallery->category_nepali;
                    $title = $isArray ? $gallery['title'] : $gallery->title;
                    $description = $isArray ? $gallery['description'] : $gallery->description;
                    $createdAt = $isArray ? \Carbon\Carbon::parse($gallery['created_at']) : $gallery->created_at;
                    $hostelName = $isArray ? $gallery['hostel_name'] : $gallery->hostel_name;
                    $hostelId = $isArray ? $gallery['hostel_id'] : $gallery->hostel_id;
                    $room = $isArray ? ($gallery['room'] ?? null) : $gallery->room;
                    $roomNumber = $room ? ($isArray ? $room['room_number'] : $room->room_number) : '';
                    $thumbnailUrl = $isArray ? ($gallery['thumbnail_url'] ?? $gallery['media_url']) : ($gallery->thumbnail_url ?? $gallery->media_url);
                    $mediaUrl = $isArray ? $gallery['media_url'] : $gallery->media_url;
                    $youtubeEmbedUrl = $isArray ? ($gallery['youtube_embed_url'] ?? '') : $gallery->youtube_embed_url;
                @endphp
                
                <div class="gallery-item {{ $mediaType }}" 
                     data-category="{{ $categoryNepali }}"
                     data-title="{{ $title }}"
                     data-description="{{ $description }}"
                     data-date="{{ $createdAt->format('Y-m-d') }}"
                     data-hostel="{{ $hostelName }}"
                     data-hostel-id="{{ $hostelId }}"
                     data-room-number="{{ $roomNumber }}"
                     data-media-type="{{ $mediaType }}"
                     @if($mediaType === 'external_video' && $youtubeEmbedUrl)
                     data-youtube-embed="{{ $youtubeEmbedUrl }}"
                     @endif>

                    <!-- Hostel Badge -->
                    <div class="hostel-badge" title="{{ $hostelName }}">
                        <i class="fas fa-building"></i>
                        <span class="nepali">{{ Str::limit($hostelName, 20) }}</span>
                    </div>

                    <!-- Room Number Badge -->
                    @if($roomNumber)
                    <div class="room-number-badge" title="कोठा नम्बर: {{ $roomNumber }}">
                        <i class="fas fa-door-open"></i>
                        <span class="nepali">कोठा {{ $roomNumber }}</span>
                    </div>
                    @endif

                    @if($mediaType === 'photo')
                        <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" class="gallery-media" loading="lazy">
                    @elseif($mediaType === 'external_video')
                        <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" class="gallery-media" loading="lazy">
                        <div class="video-badge">
                            <i class="fas fa-play"></i>
                            <span>भिडियो</span>
                        </div>
                    @elseif($mediaType === 'local_video')
                        <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" class="gallery-media" loading="lazy">
                        <div class="video-badge">
                            <i class="fas fa-play"></i>
                            <span>भिडियो</span>
                        </div>
                    @endif

                    <div class="media-overlay">
                        <div class="media-title nepali">{{ $title }}</div>
                        <div class="media-description nepali">{{ $description }}</div>
                        <div class="media-meta">
                            <span class="media-category nepali">{{ $categoryNepali }}</span>
                            <span class="media-date">{{ $createdAt->format('Y-m-d') }}</span>
                            @if($roomNumber)
                            <span class="room-number nepali">कोठा: {{ $roomNumber }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="no-results">
                    <i class="fas fa-images" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <h3 class="nepali">कुनै ग्यालरी आइटम फेला परेन</h3>
                    <p class="nepali">हाल कुनै ग्यालरी आइटम उपलब्ध छैन। कृपया पछि फर्कनुहोस्।</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination - FIXED: Check if galleries has items -->
            @if(count($galleries) > 0)
            <div class="pagination-container">
                @if(method_exists($galleries, 'links'))
                    {{ $galleries->links() }}
                @else
                    <!-- Simple pagination for arrays -->
                    <div class="pagination-info">
                        <p class="nepali">पृष्ठ 1 मा {{ count($galleries) }} वटा ग्यालरी आइटम</p>
                    </div>
                @endif
            </div>
            @endif

            <!-- No Results Message -->
            <div class="no-results hidden">
                <i class="fas fa-search" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
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
                <div class="stat-number">{{ is_countable($cities) ? count($cities) : 0 }}</div>
                <div class="stat-label nepali">शहरहरूमा उपलब्ध</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">98%</div>
                <div class="stat-label nepali">सन्तुष्टि दर</div>
            </div>
        </div>
    </section>

    <!-- 🚨 FIXED CTA SECTION - CORRECT HOSTEL REGISTRATION -->
    <div class="gallery-cta-wrapper">
        <section class="gallery-cta-section">
            <h2 class="nepali">तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
            <p class="nepali">७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्</p>
            <div class="gallery-cta-buttons-container">
                <a href="{{ route('demo') }}" class="gallery-trial-button" style="background: transparent; border: 2px solid white; color: white;">डेमो हेर्नुहोस्</a>
                
                @auth
                    @php
                        $organizationId = session('current_organization_id');
                        $hasSubscription = false;
                        
                        if ($organizationId) {
                            $organization = \App\Models\Organization::with('subscription')->find($organizationId);
                            $hasSubscription = $organization->subscription ?? false;
                        }
                    @endphp
                    
                    @if($hasSubscription)
                        <button class="gallery-trial-button" disabled>
                            तपाईंसँग पहिले नै सदस्यता छ
                        </button>
                    @else
                        <form action="{{ route('subscription.start-trial') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="gallery-trial-button">
                                निःशुल्क साइन अप गर्नुहोस्
                            </button>
                        </form>
                    @endif
                @else
                    <!-- 🚨 CORRECT ROUTE FOR HOSTEL REGISTRATION -->
                    <a href="{{ url('/register/organization/starter') }}" 
                       class="gallery-trial-button">
                        निःशुल्क साइन अप गर्नुहोस्
                    </a>
                @endauth
            </div>
        </section>
    </div>
</div>

<!-- Modal -->
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
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/gallery.js'])
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hostel filter functionality
    const hostelFilter = document.getElementById('hostelFilter');
    const galleryItems = document.querySelectorAll('.gallery-item');
    const noResults = document.querySelector('.no-results');
    let currentIndex = 0;
    let visibleItems = Array.from(galleryItems);
    
    function filterGalleries() {
        const selectedHostelId = hostelFilter.value;
        const searchTerm = document.querySelector('.search-input').value.toLowerCase();
        const activeCategory = document.querySelector('.filter-btn.active').getAttribute('data-filter');
        
        let visibleCount = 0;
        
        galleryItems.forEach(item => {
            const itemHostelId = item.getAttribute('data-hostel-id');
            const title = item.getAttribute('data-title').toLowerCase();
            const description = item.getAttribute('data-description').toLowerCase();
            const category = item.getAttribute('data-category');
            const hostel = item.getAttribute('data-hostel').toLowerCase();
            const roomNumber = item.getAttribute('data-room-number').toLowerCase();
            
            // Hostel filter
            const hostelMatch = !selectedHostelId || itemHostelId === selectedHostelId;
            
            // Search filter
            const searchMatch = !searchTerm || 
                title.includes(searchTerm) || 
                description.includes(searchTerm) || 
                category.includes(searchTerm) ||
                hostel.includes(searchTerm) ||
                roomNumber.includes(searchTerm);
            
            // Category filter
            const categoryMatch = activeCategory === 'all' || category === activeCategory;
            
            if (hostelMatch && searchMatch && categoryMatch) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update visibleItems array with currently visible items
        visibleItems = Array.from(galleryItems).filter(item => item.style.display !== 'none');
        
        // Show/hide no results message
        const noResultsElement = document.querySelector('.no-results.hidden');
        if (visibleCount === 0) {
            noResultsElement.classList.remove('hidden');
        } else {
            noResultsElement.classList.add('hidden');
        }
    }
    
    // Hostel filter event
    hostelFilter.addEventListener('change', filterGalleries);
    
    // Search input event
    const searchInput = document.querySelector('.search-input');
    searchInput.addEventListener('input', filterGalleries);
    
    // Category filter events
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            // Apply filters
            filterGalleries();
        });
    });

    // Enhanced modal functionality with hostel info
    const modal = document.querySelector('.gallery-modal');
    const modalTitle = modal.querySelector('.modal-title');
    const modalDescription = modal.querySelector('.modal-description');
    const modalCategory = modal.querySelector('.modal-category');
    const modalDate = modal.querySelector('.modal-date');
    const modalHostel = modal.querySelector('.modal-hostel');
    const modalRoom = modal.querySelector('.modal-room');
    const modalContent = modal.querySelector('.modal-content');
    
    // Gallery item click event
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            currentIndex = visibleItems.indexOf(this);
            openModal(this);
        });
    });
    
    function openModal(item) {
        const title = item.getAttribute('data-title');
        const description = item.getAttribute('data-description');
        const category = item.getAttribute('data-category');
        const date = item.getAttribute('data-date');
        const hostel = item.getAttribute('data-hostel');
        const roomNumber = item.getAttribute('data-room-number');
        const mediaType = item.getAttribute('data-media-type');
        const youtubeEmbed = item.getAttribute('data-youtube-embed');
        
        // Set modal content
        modalTitle.textContent = title;
        modalDescription.textContent = description;
        modalCategory.textContent = category;
        modalDate.textContent = date;
        modalHostel.textContent = hostel;
        modalRoom.textContent = roomNumber ? `कोठा: ${roomNumber}` : '';
        
        // Clear previous content
        modalContent.innerHTML = '';
        
        // Add media based on type
        if (mediaType === 'photo') {
            const img = document.createElement('img');
            img.src = item.querySelector('img').src;
            img.alt = title;
            img.style.width = '100%';
            img.style.height = 'auto';
            img.style.display = 'block';
            modalContent.appendChild(img);
        } else if (mediaType === 'external_video' && youtubeEmbed) {
            const iframe = document.createElement('iframe');
            iframe.src = youtubeEmbed;
            iframe.width = '100%';
            iframe.height = '400';
            iframe.allowFullscreen = true;
            iframe.style.border = 'none';
            modalContent.appendChild(iframe);
        } else if (mediaType === 'local_video') {
            const video = document.createElement('video');
            video.src = item.querySelector('source')?.src || '#';
            video.controls = true;
            video.style.width = '100%';
            video.style.height = 'auto';
            modalContent.appendChild(video);
        }
        
        // Show modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Close modal
    modal.querySelector('.modal-close').addEventListener('click', function() {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });
    
    // Close modal when clicking outside content
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
    
    // Navigation between gallery items in modal
    modal.querySelector('.modal-next').addEventListener('click', function() {
        if (currentIndex < visibleItems.length - 1) {
            currentIndex++;
            openModal(visibleItems[currentIndex]);
        } else {
            currentIndex = 0;
            openModal(visibleItems[currentIndex]);
        }
    });
    
    modal.querySelector('.modal-prev').addEventListener('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            openModal(visibleItems[currentIndex]);
        } else {
            currentIndex = visibleItems.length - 1;
            openModal(visibleItems[currentIndex]);
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal.classList.contains('active')) {
            if (e.key === 'ArrowRight') {
                modal.querySelector('.modal-next').click();
            } else if (e.key === 'ArrowLeft') {
                modal.querySelector('.modal-prev').click();
            } else if (e.key === 'Escape') {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }
    });

    // Handle trial form submission on gallery page
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
                        // Show success message
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

    // Initialize filters
    filterGalleries();
});
</script>
@endpush