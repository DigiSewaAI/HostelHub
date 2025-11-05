@extends('layouts.frontend')

@section('page-title', 'HostelHub — होस्टल प्रबन्धन प्रणाली | Nepal')
@section('og-title', 'HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS')
@section('og-description', 'HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS')

@push('styles')
<!-- FONT AWESOME CDN - YO THAPNU PARCHA -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
@vite(['resources/css/home.css'])
<style>
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
</style>
@endpush

<!-- 🚨 CRITICAL CHANGE: Hero section outside content-container -->
@section('hero-section')
<!-- Hero Section - PERFECTED LAYOUT -->
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
                    <a href="#gallery" class="btn btn-outline nepali">खोजी सुरु गर्नुहोस्</a>
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

            <!-- Image Slider - Right Side (Perfect Horizontal Rectangle) -->
            <div class="hero-slideshow">
                <div class="swiper hero-slider">
                    <div class="swiper-wrapper">
                        @foreach($heroSliderItems as $item)
                        <div class="swiper-slide">
                            @if($item['media_type'] === 'image')
                                <img src="{{ $item['thumbnail_url'] }}" 
                                     alt="{{ $item['title'] }}" 
                                     loading="lazy"
                                     onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgZmlsbD0iIzFlM2E4YSI+PC9yZWN0Pjx0ZXh0IHg9IjQwMCIgeT0iMjI1IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IiNmZmYiPkhvc3RlbEh1YiBJbWFnZTwvdGV4dD48L3N2Zz4=';">
                            @else
                                <img src="{{ $item['thumbnail_url'] }}" 
                                     alt="{{ $item['title'] }}" 
                                     loading="lazy"
                                     onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgZmlsbD0iIzFlM2E4YSI+PC9yZWN0Pjx0ZXh0IHg9IjQwMCIgeT0iMjI1IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWtkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IiNmZmYiPlZpZGVvIFRodW1ibmFpbDwvdGV4dD48L3N2Zz4=';">
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <!-- Navigation arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- Search Widget -->
<div class="container">
    <div class="search-widget">
        <h3 class="widget-title nepali">कोठा खोजी / रिजर्भ गर्नुहोस्</h3>
        <form class="widget-form" id="booking-form" action="{{ route('search') }}" method="GET">
            @csrf
            <div class="form-group">
                <label class="nepali" for="city">स्थान / City</label>
                <select class="form-control" name="city" id="city" required aria-required="true">
                    <option value="">स्थान चयन गर्नुहोस्</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
                <div class="error-message nepali">स्थान चयन गर्नुहोस्</div>
            </div>
            <div class="form-group">
                <label class="nepali" for="hostel_id">होस्टल / Hostel</label>
                <select class="form-control" name="hostel_id" id="hostel_id">
                    <option value="">सबै होस्टल</option>
                    @foreach($hostels as $hostel)
                        <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                    @endforeach
                </select>
                <div class="error-message nepali">होस्टल चयन गर्नुहोस्</div>
            </div>
            <div class="form-group">
                <label class="nepali" for="check_in">चेक-इन मिति</label>
                <input type="date" class="form-control" name="check_in" id="check_in" required aria-required="true" min="{{ date('Y-m-d') }}">
                <div class="error-message nepali">चेक-इन मिति आवश्यक छ</div>
            </div>
            <div class="form-group">
                <label class="nepali" for="check_out">चेक-आउट मिति</label>
                <input type="date" class="form-control" name="check_out" id="check_out" required aria-required="true" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                <div class="error-message nepali">चेक-आउट मिति आवश्यक छ</div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary nepali" style="width: 100%; margin-top: 0.85rem;">खोज्नुहोस्</button>
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
                            <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgZmlsbD0iI2YwZjlmZiI+PC9yZWN0Pjx0ZXh0IHg9IjQwMCIgeT0iMjI1IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWtkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQi IGZpbGw9IiMxZjI5MzciPkltYWdlIFRodW1ibmFpbDwvdGV4dD48L3N2Zz4=';">
                        @else
                            <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" loading="lazy" class="youtube-thumbnail" data-youtube-id="{{ $item['youtube_id'] ?? '' }}" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgZmlsbD0iIzFlM2E4YSI+PC9yZWN0Pjx0ZXh0IHg9IjQwMCIgeT0iMjI1IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWtkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IiNmZmYiPlZpZGVvIFRodW1ibmFpbDwvdGV4dD48L3N2Zz4=';">
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
                <p class="step-desc nepali">हाम्रा उन्नत सुविधाहरू प्रयोग गरेर आफ्नो होस्टल व्यवसायलाई बढाउनुहोस्</p>
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
<!-- FONT AWESOME JS CDN - YO PANI THAPNU PARCHA -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@vite(['resources/js/home.js'])
@endpush
