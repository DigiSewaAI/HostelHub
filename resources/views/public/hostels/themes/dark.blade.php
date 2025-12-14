{{-- resources/views/public/hostels/themes/dark.blade.php --}}

@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@push('head')
<style>
    /* MINIMAL CRITICAL CSS - Optimized for performance */
    :root {
        --theme-color: {{ $hostel->theme_color ?? '#00D4FF' }};
        --neon-cyan: #00D4FF;
        --neon-pink: #FF00FF;
        --neon-green: #00FF88;
        --neon-purple: #9D00FF;
        --neon-orange: #FF6B00;
        --dark-1: #0A0A0A;
        --dark-2: #111111;
        --dark-3: #1A1A1A;
        --dark-4: #222222;
        --text-primary: #FFFFFF;
        --text-secondary: #B0B0B0;
    }

    /* Dark Theme - Optimized */
    .dark-body {
        background: var(--dark-1);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Mangal', sans-serif;
        color: var(--text-primary);
        min-height: 100vh;
        overflow-x: hidden;
        margin: 0;
        padding: 0;
    }

    /* Simple Background */
    .matrix-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--dark-1);
        z-index: -1;
    }

    /* Cyber Container */
    .cyber-container {
        background: rgba(17, 17, 17, 0.95);
        border: 1px solid var(--neon-cyan);
        position: relative;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    /* Cyber Header */
    .cyber-header {
        background: linear-gradient(135deg, 
            rgba(0, 212, 255, 0.1) 0%, 
            rgba(157, 0, 255, 0.1) 50%, 
            rgba(255, 0, 255, 0.1) 100%);
        padding: 2rem 0;
        position: relative;
        margin-bottom: 2rem;
        border-bottom: 2px solid var(--neon-cyan);
    }

    /* Cyber Logo */
    .cyber-logo {
        width: 100px;
        height: 100px;
        border: 2px solid var(--neon-cyan);
        border-radius: 50%;
        overflow: hidden;
        background: var(--dark-2);
        margin: 0 auto 1rem;
        position: relative;
    }

    .cyber-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    /* Cyber Typography */
    .cyber-title {
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 0.5rem;
        color: var(--neon-cyan);
    }

    .cyber-subtitle {
        font-size: 1.1rem;
        color: var(--text-secondary);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    /* Cyber Stats */
    .cyber-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .cyber-stat {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-green);
        padding: 1rem;
        text-align: center;
        position: relative;
    }

    .cyber-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--neon-cyan);
        display: block;
        margin-bottom: 0.25rem;
    }

    .cyber-label {
        font-size: 0.85rem;
        color: var(--text-secondary);
        text-transform: uppercase;
    }

    /* Cyber Buttons */
    .cyber-btn {
        background: transparent;
        color: var(--neon-cyan);
        border: 2px solid var(--neon-cyan);
        padding: 0.8rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        position: relative;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0.25rem;
        cursor: pointer;
    }

    .cyber-btn:hover {
        background: rgba(0, 212, 255, 0.1);
        transform: translateY(-2px);
    }

    .btn-cyber-pink {
        border-color: var(--neon-pink);
        color: var(--neon-pink);
    }

    .btn-cyber-pink:hover {
        background: rgba(255, 0, 255, 0.1);
    }

    .btn-cyber-green {
        border-color: var(--neon-green);
        color: var(--neon-green);
    }

    .btn-cyber-green:hover {
        background: rgba(0, 255, 136, 0.1);
    }

    /* Section Headers */
    .cyber-section-header {
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
        padding: 1rem 0;
    }

    .cyber-section-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--neon-cyan);
        margin-bottom: 0.5rem;
        position: relative;
        display: inline-block;
    }

    /* ✅ FIXED: Gallery Section - IMAGES ONLY */
    .cyber-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .cyber-gallery-item {
        position: relative;
        border: 1px solid var(--neon-cyan);
        overflow: hidden;
        aspect-ratio: 4/3;
        transition: all 0.2s ease;
        background: var(--dark-2);
        cursor: pointer;
    }

    .cyber-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .cyber-gallery-item:hover {
        transform: translateY(-3px);
        border-color: var(--neon-pink);
    }

    /* Gallery button section - WITH ADDED TEXT */
    .gallery-button-section {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--neon-cyan);
        position: relative;
    }

    /* THIS IS THE ADDED TEXT LINE */
    .gallery-note-text {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 1rem;
        text-align: center;
        font-style: italic;
    }

    /* ✅ FIXED: Facilities Section - SMALLER BOXES */
    .cyber-facilities {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .cyber-facility {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-green);
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
        min-height: 60px;
    }

    .cyber-facility:hover {
        transform: translateX(3px);
        border-color: var(--neon-cyan);
    }

    .cyber-facility-icon {
        width: 40px;
        height: 40px;
        background: var(--neon-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-1);
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* ✅ FIXED: Location Section with Map */
    .location-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 1rem;
    }

    @media (min-width: 768px) {
        .location-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .location-map-container {
        height: 250px;
        background: var(--dark-3);
        border: 1px solid var(--neon-cyan);
        overflow: hidden;
        position: relative;
    }

    .location-map {
        width: 100%;
        height: 100%;
        border: none;
        filter: invert(90%) hue-rotate(180deg) contrast(85%);
    }

    /* ✅ FIXED: Reviews Section with Carousel */
    .cyber-reviews-container {
        position: relative;
        overflow: hidden;
        padding: 1rem;
    }

    .cyber-reviews-track {
        display: flex;
        transition: transform 0.5s ease;
        gap: 1.5rem;
    }

    .cyber-review {
        min-width: 100%;
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-purple);
        padding: 1.5rem;
        position: relative;
        flex-shrink: 0;
    }

    .review-controls {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    /* Cyber Contact */
    .cyber-contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .cyber-contact-card {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-cyan);
        padding: 1.5rem;
        text-align: center;
        transition: all 0.2s ease;
    }

    .cyber-contact-card:hover {
        transform: translateY(-3px);
    }

    /* Cyber Form */
    .cyber-form {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-pink);
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .cyber-form-input {
        background: transparent;
        border: 1px solid var(--neon-cyan);
        padding: 0.8rem;
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: all 0.2s ease;
        width: 100%;
        margin-bottom: 1rem;
    }

    .cyber-form-input:focus {
        outline: none;
        border-color: var(--neon-pink);
    }

    .cyber-form-textarea {
        background: transparent;
        border: 1px solid var(--neon-cyan);
        padding: 0.8rem;
        color: var(--text-primary);
        font-size: 0.95rem;
        width: 100%;
        min-height: 100px;
        resize: vertical;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .cyber-form-textarea:focus {
        outline: none;
        border-color: var(--neon-pink);
    }

    /* Action Buttons */
    .cyber-action-buttons {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin: 2rem 0;
        flex-wrap: wrap;
    }

    /* WhatsApp Floating Button */
    .cyber-whatsapp-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        width: 50px;
        height: 50px;
        background: #25D366;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        box-shadow: 0 2px 10px rgba(37, 211, 102, 0.3);
        transition: all 0.2s ease;
        border: none;
    }

    .cyber-whatsapp-btn:hover {
        transform: scale(1.1);
    }

    /* Nepali Font */
    .nepali-font {
        font-family: 'Mangal', 'Arial', sans-serif;
        line-height: 1.5;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .cyber-title {
            font-size: 2rem;
        }
        
        .cyber-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .cyber-section-title {
            font-size: 1.75rem;
        }
        
        .cyber-action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .cyber-btn {
            width: 100%;
            max-width: 280px;
            justify-content: center;
        }
        
        .cyber-gallery {
            grid-template-columns: 1fr;
        }

        .cyber-facilities {
            grid-template-columns: 1fr;
        }

        .cyber-contact-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Container */
    .container-1200 {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Utility Classes */
    .text-center {
        text-align: center;
    }
    
    .mb-1 { margin-bottom: 0.5rem; }
    .mb-2 { margin-bottom: 1rem; }
    .mb-3 { margin-bottom: 1.5rem; }
</style>

<!-- Add Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="dark-body">
    <!-- Background -->
    <div class="matrix-bg"></div>

    <!-- Cyber Header -->
    <header class="cyber-header">
        <div class="container-1200">
            <!-- Preview Alert -->
            @if(isset($preview) && $preview)
            <div class="cyber-container mb-2">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; padding: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 25px; height: 25px; background: var(--neon-orange); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-eye" style="color: var(--dark-1); font-size: 0.8rem;"></i>
                        </div>
                        <span class="nepali-font" style="color: var(--text-primary); font-size: 0.9rem;">यो पूर्वावलोकन मोडमा हो</span>
                    </div>
                    <a href="{{ route('owner.public-page.edit') }}" class="cyber-btn btn-cyber-pink" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                        <i class="fas fa-edit"></i>
                        <span class="nepali-font">सम्पादन गर्नुहोस्</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Logo and Title -->
            <div class="text-center mb-2">
                <div class="cyber-logo">
                    @if($hostel->logo_url)
                        <img src="{{ $hostel->logo_url }}" alt="{{ $hostel->name }}" loading="lazy">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--neon-cyan), var(--neon-pink)); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building" style="color: var(--dark-1); font-size: 1.5rem;"></i>
                        </div>
                    @endif
                </div>
                
                <h1 class="cyber-title nepali-font">{{ $hostel->name }}</h1>
                <p class="cyber-subtitle nepali-font">
                    @if($hostel->city)
                    <i class="fas fa-map-marker-alt" style="color: var(--neon-cyan); margin-right: 0.25rem;"></i>{{ $hostel->city }}
                    @endif
                    
                    @if($reviewCount > 0 && $avgRating > 0)
                    <span style="margin-left: 0.5rem;">
                        <i class="fas fa-star" style="color: var(--neon-orange);"></i>
                        {{ number_format($avgRating, 1) }} ({{ $reviewCount }} समीक्षा)
                    </span>
                    @endif
                </p>
            </div>

            <!-- Cyber Stats -->
            <div class="cyber-stats">
                <div class="cyber-stat">
                    <span class="cyber-number">{{ $hostel->total_rooms ?? 0 }}</span>
                    <span class="cyber-label nepali-font">कुल कोठा</span>
                </div>
                <div class="cyber-stat">
                    <span class="cyber-number">{{ $hostel->available_rooms ?? 0 }}</span>
                    <span class="cyber-label nepali-font">उपलब्ध कोठा</span>
                </div>
                <div class="cyber-stat">
                    <span class="cyber-number">{{ $studentCount }}</span>
                    <span class="cyber-label nepali-font">विद्यार्थी</span>
                </div>
                <div class="cyber-stat">
                    <span class="cyber-number">{{ $reviewCount }}</span>
                    <span class="cyber-label nepali-font">समीक्षा</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-1200">
        <!-- Action Buttons -->
        <div class="cyber-action-buttons">
            @if($hostel->contact_phone)
            <a href="tel:{{ $hostel->contact_phone }}" class="cyber-btn btn-cyber-green">
                <i class="fas fa-phone"></i>
                <span class="nepali-font">फोन गर्नुहोस्</span>
            </a>
            @endif
            <a href="{{ route('hostels.index') }}" class="cyber-btn">
                <i class="fas fa-building"></i>
                <span class="nepali-font">हाम्रा अन्य होस्टलहरू</span>
            </a>
            <a href="#reviews" class="cyber-btn btn-cyber-pink">
                <i class="fas fa-star"></i>
                <span class="nepali-font">समीक्षा लेख्नुहोस्</span>
            </a>
        </div>

        <!-- About Section -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">हाम्रो बारेमा</h2>
            </div>
            <div class="nepali-font" style="padding: 1rem; color: var(--text-secondary); font-size: 1rem;">
                @if($hostel->description)
                    {{ $hostel->description }}
                @else
                    <div class="text-center" style="padding: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: var(--dark-3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; border: 1px solid var(--neon-cyan);">
                            <i class="fas fa-file-alt" style="font-size: 1.5rem; color: var(--neon-cyan);"></i>
                        </div>
                        <p style="font-style: italic;">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- ✅ FIXED: Gallery Section - IMAGES ONLY -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">हाम्रो ग्यालरी</h2>
                <p class="cyber-subtitle nepali-font" style="color: var(--text-secondary);">हाम्रो होस्टलको तस्बिरहरू</p>
            </div>
            
            @php
                $galleries = collect();
                
                if(method_exists($hostel, 'galleries')) {
                    $galleries = $hostel->galleries()->where('is_active', 1)->get();
                }
                
                if($galleries->isEmpty() && isset($hostel->id)) {
                    $galleries = \App\Models\Gallery::where('hostel_id', $hostel->id)
                                                    ->where('is_active', 1)
                                                    ->get();
                }
                
                // STRICT FILTERING: ONLY IMAGES (exclude all videos)
                $imageGalleries = $galleries->filter(function($gallery) {
                    // Check if it's an image by file extension or media type
                    $url = $gallery->media_url ?? $gallery->media_path ?? $gallery->thumbnail_url ?? '';
                    
                    // Check for common video extensions
                    $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'flv', 'wmv', 'm4v', 'mpg', 'mpeg'];
                    $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                    
                    // Also check if title contains video-related words
                    $title = strtolower($gallery->title ?? '');
                    $videoKeywords = ['video', 'tour', 'memory', 'celebration', 'testing'];
                    
                    $isVideo = false;
                    
                    // Check by extension
                    if (in_array($extension, $videoExtensions)) {
                        $isVideo = true;
                    }
                    
                    // Check by title keywords
                    foreach ($videoKeywords as $keyword) {
                        if (str_contains($title, $keyword)) {
                            $isVideo = true;
                            break;
                        }
                    }
                    
                    // Return only if NOT a video
                    return !$isVideo;
                });
                
                $displayGalleries = $imageGalleries->take(6);
            @endphp
            
            @if($displayGalleries->count() > 0)
                <div class="cyber-gallery">
                    @foreach($displayGalleries as $gallery)
                        @php
                            $imageUrl = asset('images/default-room.png');
                            
                            if(!empty($gallery->media_url)) {
                                $imageUrl = $gallery->media_url;
                            }
                            elseif(!empty($gallery->media_path)) {
                                if(filter_var($gallery->media_path, FILTER_VALIDATE_URL)) {
                                    $imageUrl = $gallery->media_path;
                                }
                                else {
                                    $imageUrl = Storage::disk('public')->url($gallery->media_path);
                                }
                            }
                            elseif(!empty($gallery->thumbnail_url)) {
                                $imageUrl = $gallery->thumbnail_url;
                            }
                        @endphp
                        
                        <div class="cyber-gallery-item" onclick="openImageModal('{{ $imageUrl }}')">
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $gallery->title }}"
                                 loading="lazy"
                                 onerror="this.src='{{ asset('images/default-room.png') }}'">
                        </div>
                    @endforeach
                </div>
            @else
                <div class="cyber-gallery">
                    @for($i = 0; $i < 3; $i++)
                        <div class="cyber-gallery-item">
                            <div style="width: 100%; height: 100%; background: var(--dark-2); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="font-size: 1.5rem; color: var(--neon-cyan);"></i>
                            </div>
                        </div>
                    @endfor
                </div>
            @endif

            @if($galleries->count() > 0 && $hostel->slug)
                <div class="gallery-button-section">
                    <p class="gallery-note-text nepali-font">
                        हाम्रा अन्य फोटो र भिडियोको लागि
                    </p>
                    
                    <a href="{{ route('hostels.full.gallery', $hostel->slug) }}" 
                       class="cyber-btn btn-cyber-green"
                       style="padding: 0.6rem 2rem;">
                        <i class="fas fa-images"></i>
                        <span class="nepali-font">सबै ग्यालरी हेर्नुहोस्</span>
                    </a>
                </div>
            @endif
        </section>

        <!-- ✅ FIXED: Facilities Section - SMALLER CARDS -->
        @if(!empty($facilities) && count($facilities) > 0)
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">सुविधाहरू</h2>
            </div>
            <div class="cyber-facilities">
                @foreach($facilities as $facility)
                    @if(trim($facility))
                    <div class="cyber-facility">
                        <div class="cyber-facility-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="nepali-font" style="color: var(--text-primary); font-size: 0.9rem;">{{ trim($facility) }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </section>
        @endif

        <!-- ✅ FIXED: Location Section with Google Map -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">हाम्रो स्थान</h2>
            </div>
            <div class="location-grid">
                <div>
                    <h3 class="nepali-font mb-1" style="color: var(--text-primary); font-size: 1.2rem;">ठेगाना विवरण</h3>
                    @if($hostel->address)
                        <p class="nepali-font mb-2" style="color: var(--text-secondary); line-height: 1.5;">{{ $hostel->address }}</p>
                    @endif
                    
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($hostel->address) }}" 
                       target="_blank" 
                       class="cyber-btn btn-cyber-green"
                       style="width: 100%; text-align: center;">
                        <i class="fas fa-directions"></i>
                        <span class="nepali-font">नक्सामा दिशा निर्देशन</span>
                    </a>
                </div>
                
                <div class="location-map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.8340378072015!2d85.3171482753358!3d27.69389037618937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1965f5ec93a7%3A0xf2a74108721b8b9e!2sKalikasthan%20Mandir!5e0!3m2!1sen!2snp!4v1699876543210!5m2!1sen!2snp" 
                        class="location-map"
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Location Map">
                    </iframe>
                </div>
            </div>
        </section>

        <!-- ✅ FIXED: Reviews Section with Carousel -->
        <section class="cyber-container" id="reviews">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">विद्यार्थी समीक्षाहरू</h2>
            </div>
            
            @if($reviewCount > 0)
                <div class="cyber-reviews-container">
                    <div class="cyber-reviews-track" id="reviewsTrack">
                        @foreach($reviews as $review)
                        <div class="cyber-review">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; flex-wrap: wrap;">
                                <div class="nepali-font" style="font-weight: 600; color: var(--text-primary);">
                                    {{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}
                                </div>
                                <div style="color: var(--text-secondary); font-size: 0.85rem;">
                                    {{ $review->created_at->format('Y-m-d') }}
                                </div>
                            </div>
                            <div style="color: var(--neon-orange); margin-bottom: 1rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? '' : 'far' }}"></i>
                                @endfor
                                <span style="margin-left: 0.5rem; color: var(--neon-orange); font-weight: 600;">{{ $review->rating }}/5</span>
                            </div>
                            <div class="nepali-font" style="color: var(--text-secondary); line-height: 1.5;">
                                {{ $review->comment }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($reviewCount > 1)
                    <div class="review-controls">
                        <button class="cyber-btn prev-review" onclick="prevReview()">
                            <i class="fas fa-chevron-left"></i>
                            <span class="nepali-font">अघिल्लो</span>
                        </button>
                        <button class="cyber-btn next-review" onclick="nextReview()">
                            <span class="nepali-font">अर्को</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    @endif
                </div>
            @else
                <div class="text-center" style="padding: 1.5rem;">
                    <div style="width: 70px; height: 70px; background: var(--dark-3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; border: 1px solid var(--neon-cyan);">
                        <i class="fas fa-comment-slash" style="font-size: 1.5rem; color: var(--neon-cyan);"></i>
                    </div>
                    <h3 class="nepali-font mb-1" style="color: var(--text-primary); font-size: 1.2rem;">अहिलेसम्म कुनै समीक्षा छैन</h3>
                    <p class="nepali-font" style="color: var(--text-secondary); font-size: 0.95rem;">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
                </div>
            @endif
        </section>

        <!-- Contact Information -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">सम्पर्क जानकारी</h2>
            </div>
            <div class="cyber-contact-grid">
                @if($hostel->contact_person)
                <div class="cyber-contact-card">
                    <div style="width: 50px; height: 50px; background: var(--neon-cyan); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; color: var(--dark-1); font-size: 1.25rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="nepali-font mb-1" style="color: var(--text-primary); font-size: 1rem;">सम्पर्क व्यक्ति</h3>
                    <p style="color: var(--text-secondary); font-size: 0.95rem;">{{ $hostel->contact_person }}</p>
                </div>
                @endif
                
                @if($hostel->contact_phone)
                <div class="cyber-contact-card">
                    <div style="width: 50px; height: 50px; background: var(--neon-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; color: var(--dark-1); font-size: 1.25rem;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 class="nepali-font mb-1" style="color: var(--text-primary); font-size: 1rem;">फोन नम्बर</h3>
                    <a href="tel:{{ $hostel->contact_phone }}" style="color: var(--text-secondary); font-size: 0.95rem; text-decoration: none;">
                        {{ $hostel->contact_phone }}
                    </a>
                </div>
                @endif
                
                @if($hostel->contact_email)
                <div class="cyber-contact-card">
                    <div style="width: 50px; height: 50px; background: var(--neon-pink); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; color: var(--dark-1); font-size: 1.25rem;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="nepali-font mb-1" style="color: var(--text-primary); font-size: 1rem;">इमेल</h3>
                    <a href="mailto:{{ $hostel->contact_email }}" style="color: var(--text-secondary); font-size: 0.95rem; text-decoration: none;">
                        {{ $hostel->contact_email }}
                    </a>
                </div>
                @endif
                
                @if($hostel->address)
                <div class="cyber-contact-card">
                    <div style="width: 50px; height: 50px; background: var(--neon-purple); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; color: var(--dark-1); font-size: 1.25rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="nepali-font mb-1" style="color: var(--text-primary); font-size: 1rem;">ठेगाना</h3>
                    <p class="nepali-font" style="color: var(--text-secondary); font-size: 0.95rem;">{{ $hostel->address }}</p>
                </div>
                @endif
            </div>
        </section>

        <!-- Contact Form -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">सम्पर्क फर्म</h2>
            </div>
            <div class="cyber-form">
                <form action="{{ route('hostel.contact', $hostel->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="name" required placeholder="तपाईंको नाम" class="cyber-form-input nepali-font">
                        <input type="email" name="email" required placeholder="इमेल ठेगाना" class="cyber-form-input">
                    </div>
                    <textarea name="message" required placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..." class="cyber-form-textarea nepali-font"></textarea>
                    <div class="text-center">
                        <button type="submit" class="cyber-btn btn-cyber-green">
                            <i class="fas fa-paper-plane"></i>
                            <span class="nepali-font">सन्देश पठाउनुहोस्</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<!-- WhatsApp Floating Button -->
@if($hostel->whatsapp_number)
    <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="cyber-whatsapp-btn" aria-label="WhatsApp मा सन्देश पठाउनुहोस्">
        <i class="fab fa-whatsapp"></i>
    </a>
@endif

<!-- Simple Image Modal -->
<div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 9999; align-items: center; justify-content: center; padding: 15px;">
    <div style="position: relative; max-width: 90%; max-height: 90%;">
        <button onclick="closeImageModal()" style="position: absolute; top: -40px; right: 0; background: var(--neon-cyan); color: var(--dark-1); border: none; width: 35px; height: 35px; font-size: 1.25rem; cursor: pointer; z-index: 10; border-radius: 50%;">&times;</button>
        <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 100%; display: block;">
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dark theme page loaded successfully');
    
    // Initialize reviews carousel
    initReviewsCarousel();
    
    // Simple image error handling
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function() {
            if (!this.src.includes('default-room.png')) {
                this.src = '{{ asset("images/default-room.png") }}';
            }
        });
    });
});

// Reviews Carousel
let currentReviewIndex = 0;
let reviewInterval;

function initReviewsCarousel() {
    const track = document.getElementById('reviewsTrack');
    if (!track) return;
    
    const reviews = track.querySelectorAll('.cyber-review');
    if (reviews.length <= 1) return;
    
    // Set initial position
    updateCarousel();
    
    // Auto slide every 5 seconds
    reviewInterval = setInterval(nextReview, 5000);
}

function updateCarousel() {
    const track = document.getElementById('reviewsTrack');
    if (!track) return;
    
    track.style.transform = `translateX(-${currentReviewIndex * 100}%)`;
}

function nextReview() {
    const track = document.getElementById('reviewsTrack');
    if (!track) return;
    
    const reviews = track.querySelectorAll('.cyber-review');
    currentReviewIndex = (currentReviewIndex + 1) % reviews.length;
    updateCarousel();
    
    // Reset auto-slide timer
    clearInterval(reviewInterval);
    reviewInterval = setInterval(nextReview, 5000);
}

function prevReview() {
    const track = document.getElementById('reviewsTrack');
    if (!track) return;
    
    const reviews = track.querySelectorAll('.cyber-review');
    currentReviewIndex = (currentReviewIndex - 1 + reviews.length) % reviews.length;
    updateCarousel();
    
    // Reset auto-slide timer
    clearInterval(reviewInterval);
    reviewInterval = setInterval(nextReview, 5000);
}

// Image Modal Functions
function openImageModal(imageUrl) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = imageUrl;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal on background click
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('imageModal');
        if (modal.style.display === 'flex') {
            closeImageModal();
        }
    }
});
</script>
@endpush
@endsection