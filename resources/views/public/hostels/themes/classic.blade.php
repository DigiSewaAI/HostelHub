{{-- resources/views/public/hostels/themes/classic.blade.php --}}

@extends('layouts.public')

@push('head')
<style>
    :root {
        --theme-color: {{ $hostel->theme_color ?? '#8B4513' }};
        --primary-color: {{ $hostel->theme_color ?? '#8B4513' }};
        --gold-color: #D4AF37;
        --dark-brown: #654321;
        --cream-color: #F5F5DC;
        --light-beige: #F8F4E9;
        --deep-red: #8B0000;
    }

    /* Classic Theme - Completely Different from Modern */
    .classic-body {
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 400 400"><defs><pattern id="classicPattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><rect fill="%23F8F4E9" width="100" height="100"/><path fill="%23D4AF3720" d="M0,0 L100,0 L100,100 L0,100 Z M10,10 L90,10 L90,90 L10,90 Z M20,20 L80,20 L80,80 L20,80 Z"/></pattern></defs><rect fill="url(%23classicPattern)" width="400" height="400"/></svg>') fixed;
        font-family: 'Georgia', 'Times New Roman', serif;
        color: #5C4033;
        line-height: 1.8;
    }

    .classic-main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Traditional Header - UPDATED: Logo on left */
    .classic-header {
        background: linear-gradient(135deg, var(--deep-red) 0%, var(--dark-brown) 100%);
        border-bottom: 12px double var(--gold-color);
        position: relative;
        padding: 2rem 0;
        margin-bottom: 3rem;
    }

    .classic-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="%23ffffff15"><path d="M20,20 C40,10 60,10 80,20 C90,40 90,60 80,80 C60,90 40,90 20,80 C10,60 10,40 20,20 Z"/></svg>');
        background-size: 150px;
    }

    /* UPDATED: Header with logo on left */
    .classic-header-content {
        display: flex;
        align-items: center;
        gap: 3rem;
        position: relative;
        z-index: 2;
    }

    .classic-logo-main {
        width: 140px;
        height: 140px;
        border: 6px solid var(--gold-color);
        border-radius: 50%;
        overflow: hidden;
        background: white;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        flex-shrink: 0;
    }

    .classic-logo-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .classic-title {
        color: white;
        flex: 1;
    }

    .classic-title h1 {
        font-size: 2.8rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
        font-family: 'Georgia', serif;
    }

    .classic-title .location {
        font-size: 1.2rem;
        background: rgba(212, 175, 55, 0.9);
        padding: 0.4rem 1.5rem;
        border-radius: 25px;
        display: inline-block;
        margin-bottom: 1rem;
    }

    /* Traditional Stats */
    .classic-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 1.5rem 0;
        position: relative;
        z-index: 2;
    }

    .classic-stat-item {
        text-align: center;
        background: rgba(255, 255, 255, 0.95);
        padding: 1rem;
        border-radius: 12px;
        border: 3px solid var(--gold-color);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        min-width: 120px;
    }

    .classic-stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: var(--deep-red);
        display: block;
    }

    .classic-stat-label {
        font-size: 1rem;
        color: var(--dark-brown);
        font-weight: 500;
    }

    /* UPDATED: Clear Golden Social Media Text */
    .classic-social-container {
        text-align: center;
        margin: 1.5rem 0;
        position: relative;
        z-index: 2;
    }

    .classic-social-title {
        font-size: 1.3rem;
        color: var(--gold-color);
        margin-bottom: 1rem;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        background: linear-gradient(45deg, #FFD700, #FFEC8B);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .classic-social-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .classic-social-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        border: 3px solid white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        font-size: 1.2rem;
    }

    .classic-social-btn:hover {
        transform: scale(1.1) rotate(5deg);
        border-color: var(--gold-color);
    }

    /* Traditional Sections */
    .classic-section {
        background: rgba(255, 255, 255, 0.92);
        border: 5px double var(--dark-brown);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        position: relative;
    }

    .classic-section::before {
        content: '✦';
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--light-beige);
        color: var(--gold-color);
        font-size: 1.8rem;
        padding: 0 15px;
    }

    .classic-section-title {
        text-align: center;
        font-size: 2rem;
        font-weight: bold;
        color: var(--deep-red);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--gold-color);
        font-family: 'Georgia', serif;
    }

    /* About Section */
    .classic-about-content {
        font-size: 1.1rem;
        line-height: 1.9;
        text-align: justify;
        color: #5C4033;
    }

    /* UPDATED: Gallery Section - Added proper styling */
    .classic-gallery {
        margin: 2rem 0;
    }

    .classic-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .classic-gallery-item {
        border: 4px solid var(--gold-color);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        background: white;
    }

    .classic-gallery-item:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 35px rgba(0,0,0,0.3);
    }

    .classic-gallery-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .classic-gallery-item:hover img {
        transform: scale(1.1);
    }

    /* Facilities Grid */
    .classic-facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.2rem;
        margin-top: 1.5rem;
    }

    .classic-facility-item {
        background: linear-gradient(135deg, var(--light-beige) 0%, #FFF8E1 100%);
        padding: 1.2rem;
        border-radius: 10px;
        border-left: 5px solid var(--gold-color);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.3s ease;
    }

    .classic-facility-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .classic-facility-icon {
        width: 45px;
        height: 45px;
        background: var(--gold-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* UPDATED: Reviews Style - Fixed overlapping */
    .classic-review-item {
        background: linear-gradient(135deg, #FFF8E1 0%, #FFECB3 100%);
        border: 2px solid var(--gold-color);
        border-radius: 15px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .classic-review-item::before {
        content: '"';
        position: absolute;
        top: -15px;
        left: 25px;
        font-size: 3rem;
        color: var(--gold-color);
        background: var(--light-beige);
        padding: 0 12px;
        z-index: 1;
    }

    .classic-review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .classic-reviewer {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--deep-red);
    }

    .classic-review-date {
        color: #7D5D3B;
        font-style: italic;
        font-size: 0.9rem;
    }

    .classic-review-rating {
        color: var(--gold-color);
        margin: 0.5rem 0;
        position: relative;
        z-index: 2;
    }

    .classic-review-content {
        font-size: 1rem;
        line-height: 1.7;
        color: #5C4033;
        position: relative;
        z-index: 2;
    }

    /* UPDATED: Contact Section - Smaller cards in one line */
    .classic-contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .classic-contact-item {
        background: linear-gradient(135deg, var(--light-beige) 0%, #FFF8E1 100%);
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        border: 2px solid var(--gold-color);
        transition: all 0.3s ease;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .classic-contact-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .classic-contact-icon {
        width: 60px;
        height: 60px;
        background: var(--deep-red);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin: 0 auto 0.8rem;
        border: 2px solid var(--gold-color);
    }

    /* UPDATED: Contact Form Styling */
    .classic-contact-form {
        background: linear-gradient(135deg, var(--light-beige) 0%, #FFF8E1 100%);
        border: 3px solid var(--gold-color);
        border-radius: 15px;
        padding: 2rem;
        margin-top: 2rem;
    }

    .classic-form-title {
        text-align: center;
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--deep-red);
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 2px solid var(--gold-color);
    }

    .classic-form-input {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid var(--gold-color);
        border-radius: 8px;
        padding: 0.8rem 1rem;
        font-size: 1rem;
        color: var(--dark-brown);
        width: 100%;
        margin-bottom: 1rem;
        font-family: 'Georgia', serif;
    }

    .classic-form-input:focus {
        outline: none;
        border-color: var(--deep-red);
        box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.1);
    }

    .classic-form-textarea {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid var(--gold-color);
        border-radius: 8px;
        padding: 0.8rem 1rem;
        font-size: 1rem;
        color: var(--dark-brown);
        width: 100%;
        margin-bottom: 1.5rem;
        font-family: 'Georgia', serif;
        resize: vertical;
        min-height: 120px;
    }

    .classic-form-button {
        background: linear-gradient(135deg, var(--deep-red) 0%, var(--dark-brown) 100%);
        color: white;
        border: 2px solid var(--gold-color);
        border-radius: 8px;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Georgia', serif;
    }

    .classic-form-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 0, 0, 0.3);
    }

    /* Action Buttons */
    .classic-action-buttons {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin: 2.5rem 0;
        flex-wrap: wrap;
    }

    .classic-action-btn {
        background: linear-gradient(135deg, var(--deep-red) 0%, var(--dark-brown) 100%);
        color: white;
        padding: 1rem 2rem;
        border: 3px solid var(--gold-color);
        border-radius: 40px;
        text-decoration: none;
        font-size: 1.1rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(139, 0, 0, 0.3);
    }

    .classic-action-btn:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 8px 25px rgba(139, 0, 0, 0.4);
        color: white;
    }

    .classic-phone-btn {
        background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
        border-color: #25d366;
    }

    /* Traditional Decorations */
    .classic-divider {
        text-align: center;
        margin: 2.5rem 0;
        position: relative;
    }

    .classic-divider::before {
        content: '❖ ❖ ❖';
        color: var(--gold-color);
        font-size: 1.8rem;
        letter-spacing: 0.8rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .classic-header-content {
            flex-direction: column;
            text-align: center;
            gap: 1.5rem;
        }
        
        .classic-logo-main {
            width: 120px;
            height: 120px;
        }
        
        .classic-title h1 {
            font-size: 2.2rem;
        }
        
        .classic-stats {
            flex-direction: column;
            gap: 1rem;
            align-items: center;
        }
        
        .classic-stat-item {
            min-width: 180px;
        }
        
        .classic-section {
            padding: 2rem 1rem;
        }
        
        .classic-action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .classic-action-btn {
            width: 100%;
            max-width: 280px;
            justify-content: center;
        }

        .classic-contact-grid {
            grid-template-columns: 1fr;
        }

        .classic-gallery-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
    }

    .nepali-font {
        font-family: 'Mangal', 'Arial', sans-serif;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<div class="classic-body">
    <!-- Traditional Header Section -->
    <header class="classic-header">
        <div class="classic-main-container">
            <!-- Preview Alert -->
            @if(isset($preview) && $preview)
            <div style="background: rgba(255,255,255,0.95); border: 3px solid var(--gold-color); border-radius: 15px; padding: 1.2rem; margin-bottom: 1.5rem; text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <div style="width: 35px; height: 35px; background: #FEF3C7; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-eye" style="color: #D97706;"></i>
                    </div>
                    <span style="font-size: 1.1rem; font-weight: bold; color: #92400E;" class="nepali-font">यो पूर्वावलोकन मोडमा हो</span>
                    <a href="{{ route('owner.public-page.edit') }}" 
                       style="background: var(--deep-red); color: white; padding: 0.4rem 1.2rem; border-radius: 20px; text-decoration: none; border: 2px solid var(--gold-color); font-size: 0.9rem;" class="nepali-font">
                        <i class="fas fa-edit mr-1"></i>सम्पादन गर्नुहोस्
                    </a>
                </div>
            </div>
            @endif

            <!-- UPDATED: Header with Logo on Left -->
            <div class="classic-header-content">
                <!-- Logo on Left -->
                <div class="classic-logo-main">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $hostel->name }}">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--gold-color) 0%, var(--deep-red) 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building" style="color: white; font-size: 2.5rem;"></i>
                        </div>
                    @endif
                </div>

                <!-- Title and Info on Right -->
                <div class="classic-title">
                    <h1 class="nepali-font">{{ $hostel->name }}</h1>
                    @if($hostel->city)
                    <div class="location nepali-font">
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $hostel->city }}
                    </div>
                    @endif

                    <!-- Rating -->
                    @if($reviewCount > 0 && $avgRating > 0)
                    <div style="background: rgba(255,255,255,0.9); padding: 0.6rem 1.5rem; border-radius: 25px; display: inline-block;">
                        <div style="color: var(--gold-color); margin-bottom: 0.3rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($avgRating) ? '' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <span style="color: var(--deep-red); font-weight: bold; font-size: 1rem;">
                            {{ number_format($avgRating, 1) }} ({{ $reviewCount }} समीक्षा)
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Traditional Stats -->
            <div class="classic-stats">
                <div class="classic-stat-item">
                    <span class="classic-stat-number">{{ $hostel->total_rooms ?? 0 }}</span>
                    <span class="classic-stat-label nepali-font">कुल कोठा</span>
                </div>
                <div class="classic-stat-item">
                    <span class="classic-stat-number">{{ $hostel->available_rooms ?? 0 }}</span>
                    <span class="classic-stat-label nepali-font">उपलब्ध कोठा</span>
                </div>
                <div class="classic-stat-item">
                    <span class="classic-stat-number">{{ $studentCount }}</span>
                    <span class="classic-stat-label nepali-font">विद्यार्थी</span>
                </div>
                <div class="classic-stat-item">
                    <span class="classic-stat-number">{{ $reviewCount }}</span>
                    <span class="classic-stat-label nepali-font">समीक्षा</span>
                </div>
            </div>

            <!-- UPDATED: Clear Golden Social Media -->
            <div class="classic-social-container">
                <div class="classic-social-title nepali-font">हामीलाई सामाजिक संजालमा पछ्याउनुहोस्</div>
                <div class="classic-social-buttons">
                    @if($hostel->facebook_url)
                        <a href="{{ $hostel->facebook_url }}" target="_blank" class="classic-social-btn" style="background: #1877f2;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if($hostel->instagram_url)
                        <a href="{{ $hostel->instagram_url }}" target="_blank" class="classic-social-btn" style="background: #e4405f;">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if($hostel->whatsapp_number)
                        <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="classic-social-btn" style="background: #25d366;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="classic-main-container">
        <!-- UPDATED: Action Buttons with Nepali Text -->
        <div class="classic-action-buttons">
            @if($hostel->contact_phone)
            <a href="tel:{{ $hostel->contact_phone }}" class="classic-action-btn classic-phone-btn">
                <i class="fas fa-phone"></i>
                <span class="nepali-font">फोन गर्नुहोस्</span>
            </a>
            @endif
            <a href="{{ route('hostels.index') }}" class="classic-action-btn">
                <i class="fas fa-building"></i>
                <span class="nepali-font">हाम्रा अन्य होस्टलहरू</span>
            </a>
            <a href="#reviews" class="classic-action-btn">
                <i class="fas fa-star"></i>
                <span class="nepali-font">समीक्षा लेख्नुहोस्</span>
            </a>
        </div>

        <!-- About Section -->
        <section class="classic-section">
            <h2 class="classic-section-title nepali-font">हाम्रो बारेमा</h2>
            <div class="classic-about-content nepali-font">
                @if($hostel->description)
                    {{ $hostel->description }}
                @else
                    <div style="text-align: center; padding: 2rem; color: #7D5D3B;">
                        <i class="fas fa-file-alt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p style="font-size: 1.2rem; font-style: italic;">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- UPDATED: Gallery Section - IMMEDIATELY AFTER ABOUT SECTION -->
        <section class="classic-section">
            <h2 class="classic-section-title nepali-font">ग्यालरी</h2>
            <div class="classic-gallery">
                @if(isset($hostel->images) && count($hostel->images) > 0)
                    <div class="classic-gallery-grid">
                        @foreach($hostel->images as $image)
                        <div class="classic-gallery-item">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $hostel->name }} - Image {{ $loop->iteration }}">
                        </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem; color: #7D5D3B;">
                        <div style="width: 100px; height: 100px; background: var(--light-beige); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 3px solid var(--gold-color);">
                            <i class="fas fa-images" style="font-size: 2.5rem; color: var(--gold-color);"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; color: var(--deep-red); margin-bottom: 0.8rem;" class="nepali-font">कुनै तस्बिर उपलब्ध छैन</h3>
                        <p style="font-size: 1.1rem; color: #7D5D3B;" class="nepali-font">यस होस्टलको ग्यालरी तस्बिरहरू चाँहि उपलब्ध छैनन्।</p>
                    </div>
                @endif
            </div>
        </section>

        <div class="classic-divider"></div>

        <!-- Facilities Section -->
        @if(!empty($facilities) && count($facilities) > 0)
        <section class="classic-section">
            <h2 class="classic-section-title nepali-font">सुविधाहरू</h2>
            <div class="classic-facilities-grid">
                @foreach($facilities as $facility)
                    @if(trim($facility))
                    <div class="classic-facility-item">
                        <div class="classic-facility-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="nepali-font" style="font-size: 1rem; font-weight: 500;">{{ trim($facility) }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </section>
        @endif

        <div class="classic-divider"></div>

        <!-- UPDATED: Reviews Section - Fixed overlapping -->
        <section class="classic-section" id="reviews">
            <h2 class="classic-section-title nepali-font">विद्यार्थी समीक्षाहरू</h2>
            
            @if($reviewCount > 0)
                <div>
                    @foreach($reviews as $review)
                    <div class="classic-review-item">
                        <div class="classic-review-header">
                            <div class="classic-reviewer nepali-font">{{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}</div>
                            <div class="classic-review-date">{{ $review->created_at->format('Y-m-d') }}</div>
                        </div>
                        <div class="classic-review-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                            @endfor
                            <span style="color: var(--deep-red); font-weight: bold; margin-left: 0.8rem;">{{ $review->rating }}/5</span>
                        </div>
                        <div class="classic-review-content nepali-font">{{ $review->comment }}</div>
                        
                        @if($review->reply)
                        <div style="background: rgba(212, 175, 55, 0.1); border-left: 4px solid var(--gold-color); padding: 1rem; margin-top: 1rem; border-radius: 8px;">
                            <div style="display: flex; align-items: start; gap: 0.8rem;">
                                <i class="fas fa-reply" style="color: var(--deep-red); margin-top: 0.2rem;"></i>
                                <div>
                                    <strong style="color: var(--deep-red); font-size: 0.9rem;" class="nepali-font">होस्टलको जवाफ:</strong>
                                    <p style="color: #5C4033; margin-top: 0.5rem; font-size: 0.9rem;" class="nepali-font">{{ $review->reply }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if($reviews->hasPages())
                    <div style="margin-top: 1.5rem; text-align: center;">
                        {{ $reviews->links() }}
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 2rem;">
                    <div style="width: 80px; height: 80px; background: var(--light-beige); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px solid var(--gold-color);">
                        <i class="fas fa-comment-slash" style="font-size: 2rem; color: #7D5D3B;"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; color: var(--deep-red); margin-bottom: 0.8rem;" class="nepali-font">अहिलेसम्म कुनै समीक्षा छैन</h3>
                    <p style="font-size: 1.1rem; color: #7D5D3B;" class="nepali-font">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
                </div>
            @endif
        </section>

        <div class="classic-divider"></div>

        <!-- UPDATED: Contact Information - Smaller cards in one line -->
        <section class="classic-section">
            <h2 class="classic-section-title nepali-font">सम्पर्क जानकारी</h2>
            <div class="classic-contact-grid">
                @if($hostel->contact_person)
                <div class="classic-contact-item">
                    <div class="classic-contact-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="nepali-font" style="color: var(--deep-red); margin-bottom: 0.5rem; font-size: 1rem;">सम्पर्क व्यक्ति</h3>
                    <p class="nepali-font" style="font-size: 0.9rem; font-weight: 500;">{{ $hostel->contact_person }}</p>
                </div>
                @endif
                
                @if($hostel->contact_phone)
                <div class="classic-contact-item">
                    <div class="classic-contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 class="nepali-font" style="color: var(--deep-red); margin-bottom: 0.5rem; font-size: 1rem;">फोन नम्बर</h3>
                    <a href="tel:{{ $hostel->contact_phone }}" style="font-size: 0.9rem; font-weight: 500; color: var(--dark-brown); text-decoration: none;">
                        {{ $hostel->contact_phone }}
                    </a>
                </div>
                @endif
                
                @if($hostel->contact_email)
                <div class="classic-contact-item">
                    <div class="classic-contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="nepali-font" style="color: var(--deep-red); margin-bottom: 0.5rem; font-size: 1rem;">इमेल</h3>
                    <a href="mailto:{{ $hostel->contact_email }}" style="font-size: 0.9rem; font-weight: 500; color: var(--dark-brown); text-decoration: none;">
                        {{ $hostel->contact_email }}
                    </a>
                </div>
                @endif
                
                @if($hostel->address)
                <div class="classic-contact-item">
                    <div class="classic-contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="nepali-font" style="color: var(--deep-red); margin-bottom: 0.5rem; font-size: 1rem;">ठेगाना</h3>
                    <p class="nepali-font" style="font-size: 0.9rem; font-weight: 500;">{{ $hostel->address }}</p>
                </div>
                @endif
            </div>
        </section>

        <!-- UPDATED: Contact Form Section with matching theme -->
        <section class="classic-section">
            <h2 class="classic-section-title nepali-font">सम्पर्क फर्म</h2>
            <div class="classic-contact-form">
                <form action="{{ route('hostel.contact', $hostel->id) }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <input type="text" name="name" required placeholder="तपाईंको नाम" 
                                   class="classic-form-input nepali-font">
                        </div>
                        <div>
                            <input type="email" name="email" required placeholder="इमेल ठेगाना"
                                   class="classic-form-input">
                        </div>
                    </div>
                    <div>
                        <textarea name="message" required placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..."
                                  class="classic-form-textarea nepali-font"></textarea>
                    </div>
                    <div style="text-align: center;">
                        <button type="submit" class="classic-form-button nepali-font">
                            सन्देश पठाउनुहोस्
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<!-- Add Font Awesome -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
@endsection