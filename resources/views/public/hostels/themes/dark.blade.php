{{-- resources/views/public/hostels/themes/dark.blade.php --}}

@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@push('head')
{{-- Remove Vite for now to prevent loading issues --}}
{{-- @vite(['resources/css/public-themes.css']) --}}
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
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-primary);
        min-height: 100vh;
        overflow-x: hidden;
        margin: 0;
        padding: 0;
    }

    /* Simple Matrix Background - Less resource intensive */
    .matrix-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 80%, rgba(0, 212, 255, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 0, 255, 0.05) 0%, transparent 50%);
        z-index: -1;
    }

    /* Cyber Container - Simplified */
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
        overflow: hidden;
        margin-bottom: 2rem;
        border-bottom: 2px solid var(--neon-cyan);
    }

    /* Cyber Logo */
    .cyber-logo {
        width: 120px;
        height: 120px;
        border: 2px solid var(--neon-cyan);
        border-radius: 50%;
        overflow: hidden;
        background: var(--dark-2);
        margin: 0 auto 1.5rem;
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
        font-size: 3rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 0.5rem;
        color: var(--neon-cyan);
        text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
    }

    .cyber-subtitle {
        font-size: 1.2rem;
        color: var(--text-secondary);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    /* Cyber Stats */
    .cyber-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin: 2rem 0;
    }

    .cyber-stat {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-green);
        padding: 1.5rem;
        text-align: center;
        position: relative;
    }

    .cyber-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--neon-cyan);
        display: block;
        margin-bottom: 0.5rem;
    }

    .cyber-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        text-transform: uppercase;
    }

    /* Cyber Buttons */
    .cyber-btn {
        background: transparent;
        color: var(--neon-cyan);
        border: 2px solid var(--neon-cyan);
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0.5rem;
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
        margin-bottom: 2rem;
        position: relative;
        padding: 1.5rem 0;
    }

    .cyber-section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--neon-cyan);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        position: relative;
        display: inline-block;
    }

    .cyber-section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 2px;
        background: var(--neon-cyan);
    }

    /* ✅ FIXED: Cyber Gallery - Images only */
    .cyber-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .cyber-gallery-item {
        position: relative;
        border: 2px solid var(--neon-cyan);
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
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 212, 255, 0.3);
    }

    /* Gallery button section - WITH ADDED TEXT */
    .gallery-button-section {
        text-align: center;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid var(--neon-cyan);
        position: relative;
    }

    .gallery-button-section::before {
        content: '';
        position: absolute;
        top: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 2px;
        background: var(--neon-cyan);
    }

    /* THIS IS THE ADDED TEXT LINE */
    .gallery-note-text {
        color: var(--text-secondary);
        font-size: 1.1rem;
        margin-bottom: 1rem;
        text-align: center;
        font-style: italic;
    }

    /* Cyber Facilities */
    .cyber-facilities {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .cyber-facility {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-green);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s ease;
    }

    .cyber-facility:hover {
        transform: translateX(5px);
    }

    .cyber-facility-icon {
        width: 50px;
        height: 50px;
        background: var(--neon-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-1);
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    /* Cyber Reviews */
    .cyber-review {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-purple);
        padding: 2rem;
        position: relative;
        margin-bottom: 1rem;
    }

    /* Cyber Contact */
    .cyber-contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .cyber-contact-card {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-cyan);
        padding: 2rem;
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
        padding: 2rem;
        margin-top: 1.5rem;
    }

    .cyber-form-input {
        background: transparent;
        border: 1px solid var(--neon-cyan);
        border-radius: 0;
        padding: 1rem;
        color: var(--text-primary);
        font-size: 1rem;
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
        border-radius: 0;
        padding: 1rem;
        color: var(--text-primary);
        font-size: 1rem;
        width: 100%;
        min-height: 120px;
        resize: vertical;
        margin-bottom: 1.5rem;
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
        gap: 1rem;
        margin: 3rem 0;
        flex-wrap: wrap;
    }

    /* WhatsApp Floating Button */
    .cyber-whatsapp-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        width: 60px;
        height: 60px;
        background: #25D366;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        transition: all 0.2s ease;
        border: none;
    }

    .cyber-whatsapp-btn:hover {
        transform: scale(1.1);
    }

    /* Nepali Font */
    .nepali-font {
        font-family: 'Mangal', 'Arial', sans-serif;
        line-height: 1.6;
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
            font-size: 2rem;
        }
        
        .cyber-action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .cyber-btn {
            width: 100%;
            max-width: 300px;
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
        padding: 0 20px;
    }
</style>

<!-- Add Cyber Fonts - Load asynchronously -->
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
@endpush

@section('content')
<div class="dark-body">
    <!-- Simple Matrix Background -->
    <div class="matrix-bg"></div>

    <!-- Cyber Header -->
    <header class="cyber-header">
        <div class="container-1200">
            <!-- Preview Alert -->
            @if(isset($preview) && $preview)
            <div class="cyber-container" style="margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 30px; height: 30px; background: var(--neon-orange); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-eye" style="color: var(--dark-1); font-size: 0.9rem;"></i>
                        </div>
                        <span style="color: var(--text-primary); font-weight: 600; font-size: 1rem;" class="nepali-font">यो पूर्वावलोकन मोडमा हो</span>
                    </div>
                    <a href="{{ route('owner.public-page.edit') }}" class="cyber-btn btn-cyber-pink" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;">
                        <i class="fas fa-edit"></i>
                        <span class="nepali-font">सम्पादन गर्नुहोस्</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Logo and Title -->
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div class="cyber-logo">
                    @if($hostel->logo_url)
                        <img src="{{ $hostel->logo_url }}" alt="{{ $hostel->name }}" loading="lazy">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--neon-cyan), var(--neon-pink)); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building" style="color: var(--dark-1); font-size: 2rem;"></i>
                        </div>
                    @endif
                </div>
                
                <h1 class="cyber-title nepali-font">{{ $hostel->name }}</h1>
                <p class="cyber-subtitle nepali-font">
                    @if($hostel->city)
                    <i class="fas fa-map-marker-alt" style="color: var(--neon-cyan); margin-right: 0.5rem;"></i>{{ $hostel->city }}
                    @endif
                    
                    @if($reviewCount > 0 && $avgRating > 0)
                    <span style="margin-left: 1rem;">
                        <i class="fas fa-star" style="color: var(--neon-orange;"></i>
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
            <div style="padding: 1.5rem; color: var(--text-secondary); font-size: 1.1rem;" class="nepali-font">
                @if($hostel->description)
                    {{ $hostel->description }}
                @else
                    <div style="text-align: center; padding: 2rem;">
                        <div style="width: 80px; height: 80px; background: var(--dark-3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; border: 1px solid var(--neon-cyan);">
                            <i class="fas fa-file-alt" style="font-size: 2rem; color: var(--neon-cyan);"></i>
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
                
                // FILTER ONLY IMAGES (not videos)
                $imageGalleries = $galleries->filter(function($gallery) {
                    // Check if it's an image by file extension or type
                    $url = $gallery->media_url ?? $gallery->media_path ?? '';
                    $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                    
                    return in_array($ext, $imageExtensions);
                });
                
                $displayGalleries = $imageGalleries->take(8);
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
                    @for($i = 0; $i < 4; $i++)
                        <div class="cyber-gallery-item">
                            <div style="width: 100%; height: 100%; background: var(--dark-2); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-images" style="font-size: 2rem; color: var(--neon-cyan);"></i>
                            </div>
                        </div>
                    @endfor
                </div>
            @endif

            @if($galleries->count() > 0 && $hostel->slug)
                <div class="gallery-button-section">
                    <!-- ✅ ADDED TEXT LINE AS REQUESTED -->
                    <p class="gallery-note-text nepali-font">
                        हाम्रा अन्य फोटो र भिडियोको लागि
                    </p>
                    
                    <a href="{{ route('hostels.full.gallery', $hostel->slug) }}" 
                       class="cyber-btn btn-cyber-green"
                       style="padding: 0.8rem 2.5rem;">
                        <i class="fas fa-images"></i>
                        <span class="nepali-font">सबै ग्यालरी हेर्नुहोस्</span>
                    </a>
                </div>
            @endif
        </section>

        <!-- Facilities Section -->
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
                        <span class="nepali-font" style="color: var(--text-primary); font-size: 1rem;">{{ trim($facility) }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </section>
        @endif

        <!-- Location Section -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">हाम्रो स्थान</h2>
            </div>
            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
                    <div>
                        <h3 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.3rem;" class="nepali-font">ठेगाना विवरण</h3>
                        @if($hostel->address)
                            <p style="color: var(--text-secondary); line-height: 1.6; margin-bottom: 1.5rem; font-size: 1rem;" class="nepali-font">{{ $hostel->address }}</p>
                        @endif
                        
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($hostel->address) }}" 
                               target="_blank" 
                               class="cyber-btn btn-cyber-green"
                               style="text-align: center;">
                                <i class="fas fa-directions"></i>
                                <span class="nepali-font">नक्सामा दिशा निर्देशन</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section class="cyber-container" id="reviews">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">विद्यार्थी समीक्षाहरू</h2>
            </div>
            
            @if($reviewCount > 0)
                <div style="padding: 1.5rem;">
                    @foreach($reviews as $review)
                    <div class="cyber-review" style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; flex-wrap: wrap;">
                            <div class="cyber-reviewer nepali-font" style="font-weight: 600;">{{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}</div>
                            <div class="cyber-review-date" style="color: var(--text-secondary); font-size: 0.9rem;">{{ $review->created_at->format('Y-m-d') }}</div>
                        </div>
                        <div style="color: var(--neon-orange); margin-bottom: 1rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'far' }}"></i>
                            @endfor
                            <span style="margin-left: 0.5rem; color: var(--neon-orange); font-weight: 600;">{{ $review->rating }}/5</span>
                        </div>
                        <div class="cyber-review-content nepali-font" style="color: var(--text-secondary); line-height: 1.6;">{{ $review->comment }}</div>
                    </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2rem;">
                    <div style="width: 80px; height: 80px; background: var(--dark-3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; border: 1px solid var(--neon-cyan);">
                        <i class="fas fa-comment-slash" style="font-size: 2rem; color: var(--neon-cyan);"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; color: var(--text-primary); margin-bottom: 0.5rem;" class="nepali-font">अहिलेसम्म कुनै समीक्षा छैन</h3>
                    <p style="font-size: 1rem; color: var(--text-secondary);" class="nepali-font">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
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
                    <div style="width: 60px; height: 60px; background: var(--neon-cyan); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--dark-1); font-size: 1.5rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.1rem;" class="nepali-font">सम्पर्क व्यक्ति</h3>
                    <p style="color: var(--text-secondary); font-weight: 500; font-size: 1rem;">{{ $hostel->contact_person }}</p>
                </div>
                @endif
                
                @if($hostel->contact_phone)
                <div class="cyber-contact-card">
                    <div style="width: 60px; height: 60px; background: var(--neon-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--dark-1); font-size: 1.5rem;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.1rem;" class="nepali-font">फोन नम्बर</h3>
                    <a href="tel:{{ $hostel->contact_phone }}" style="color: var(--text-secondary); font-weight: 500; font-size: 1rem; text-decoration: none;">
                        {{ $hostel->contact_phone }}
                    </a>
                </div>
                @endif
                
                @if($hostel->contact_email)
                <div class="cyber-contact-card">
                    <div style="width: 60px; height: 60px; background: var(--neon-pink); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--dark-1); font-size: 1.5rem;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.1rem;" class="nepali-font">इमेल</h3>
                    <a href="mailto:{{ $hostel->contact_email }}" style="color: var(--text-secondary); font-weight: 500; font-size: 1rem; text-decoration: none;">
                        {{ $hostel->contact_email }}
                    </a>
                </div>
                @endif
                
                @if($hostel->address)
                <div class="cyber-contact-card">
                    <div style="width: 60px; height: 60px; background: var(--neon-purple); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--dark-1); font-size: 1.5rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.1rem;" class="nepali-font">ठेगाना</h3>
                    <p style="color: var(--text-secondary); font-weight: 500; font-size: 1rem;" class="nepali-font">{{ $hostel->address }}</p>
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
                    <div style="margin-bottom: 1.5rem;">
                        <input type="text" name="name" required placeholder="तपाईंको नाम" class="cyber-form-input nepali-font">
                        <input type="email" name="email" required placeholder="इमेल ठेगाना" class="cyber-form-input">
                    </div>
                    <textarea name="message" required placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..." class="cyber-form-textarea nepali-font"></textarea>
                    <div style="text-align: center;">
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
<div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="position: relative; max-width: 90%; max-height: 90%;">
        <button onclick="closeImageModal()" style="position: absolute; top: 10px; right: 10px; background: var(--neon-cyan); color: var(--dark-1); border: none; width: 40px; height: 40px; font-size: 1.5rem; cursor: pointer; z-index: 10; border-radius: 50%;">&times;</button>
        <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 100%; display: block; margin: 0 auto;">
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dark theme page loaded successfully');
    
    // Simple image error handling
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function() {
            if (!this.src.includes('default-room.png')) {
                this.src = '{{ asset("images/default-room.png") }}';
            }
        });
    });
});

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