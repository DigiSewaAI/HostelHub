@extends('layouts.public')

@push('head')
@vite(['resources/css/public-themes.css'])
<style>
    :root {
        --theme-color: {{ $hostel->theme_color ?? '#3b82f6' }};
        --primary-color: {{ $hostel->theme_color ?? '#3b82f6' }};
    }
    
    .modern-theme {
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    }
    
    .hero-gradient {
        background: linear-gradient(135deg, var(--theme-color) 0%, #7c3aed 100%);
    }
    
    /* STRICT LOGO SIZE ENFORCEMENT */
    .logo-container {
        width: 60px !important;
        height: 60px !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        border: 3px solid white !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        background: white !important;
        flex-shrink: 0 !important;
        position: relative !important;
    }
    
    .logo-container img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        object-position: center !important;
        max-width: 100% !important;
        max-height: 100% !important;
        display: block !important;
    }
    
    /* Header Layout */
    .header-content {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
    }
    
    .hostel-info {
        flex: 1;
    }
    
    .hostel-name {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 6px;
        line-height: 1.2;
        color: white;
    }
    
    .hostel-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 8px;
    }
    
    .meta-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        background: rgba(255, 255, 255, 0.2);
        padding: 3px 8px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
        color: white;
    }
    
    .availability-badge {
        background: rgba(34, 197, 94, 0.9);
        color: white;
        font-weight: 600;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        padding: 12px 8px;
        text-align: center;
        color: white;
    }
    
    .stat-number {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    
    .stat-label {
        font-size: 11px;
        opacity: 0.9;
    }
    
    /* Social Media & CTA */
    .header-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: flex-end;
    }
    
    .social-media-compact {
        display: flex;
        gap: 5px;
    }
    
    .social-icon-compact {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 13px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .social-icon-compact:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.2);
    }
    
    .phone-cta {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 8px 12px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .phone-cta:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }
    
    /* Main Content Sections */
    .section-title {
        position: relative;
        padding-bottom: 10px;
        margin-bottom: 16px;
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 35px;
        height: 3px;
        background: linear-gradient(to right, var(--theme-color), #7c3aed);
        border-radius: 2px;
    }
    
    .content-card {
        background: white;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    
    /* 🎨 ENHANCED GALLERY SECTION - VERTICAL AUTO SCROLL */
    .gallery-vertical-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        height: 600px; /* Fixed height for vertical scroll */
        overflow: hidden;
        position: relative;
    }
    
    .gallery-vertical-scroll {
        height: 100%;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .gallery-vertical-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .gallery-vertical-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .gallery-vertical-scroll::-webkit-scrollbar-thumb {
        background: var(--theme-color);
        border-radius: 10px;
    }
    
    .gallery-vertical-grid {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .gallery-vertical-item {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        background: white;
        border: 1px solid #e2e8f0;
    }
    
    .gallery-vertical-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .gallery-vertical-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .gallery-placeholder-vertical {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #64748b;
    }
    
    .view-gallery-btn {
        background: linear-gradient(135deg, var(--theme-color), #7c3aed);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
    }
    
    .view-gallery-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
    }
    
    /* Auto-scroll animation */
    @keyframes autoScroll {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(calc(-100% + 600px));
        }
    }
    
    .gallery-auto-scroll {
        animation: autoScroll 30s linear infinite;
    }
    
    .gallery-auto-scroll:hover {
        animation-play-state: paused;
    }
    
    /* FIXED: Facilities Grid - Proper styling */
    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 10px;
    }
    
    .facility-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        word-break: break-word;
    }
    
    .facility-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: var(--theme-color);
    }
    
    .facility-icon {
        width: 32px;
        height: 32px;
        background: var(--theme-color);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    /* 🆕 HORIZONTAL REVIEWS GRID - 3 PER ROW */
    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }
    
    .review-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #f1f5f9;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .review-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .review-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(to bottom, var(--theme-color), #7c3aed);
        border-radius: 0 2px 2px 0;
    }
    
    .review-header {
        display: flex;
        justify-content: between;
        align-items: flex-start;
        margin-bottom: 10px;
    }
    
    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }
    
    .reviewer-avatar {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--theme-color), #7c3aed);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 12px;
    }
    
    .reviewer-details h4 {
        font-weight: 600;
        margin-bottom: 2px;
        color: #1f2937;
        font-size: 14px;
    }
    
    .review-date {
        color: #6b7280;
        font-size: 11px;
    }
    
    /* Sidebar Cards */
    .sidebar-card {
        background: white;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
        margin-bottom: 16px;
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 8px;
        border-left: 3px solid var(--theme-color);
    }
    
    .contact-icon {
        width: 30px;
        height: 30px;
        background: var(--theme-color);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        flex-shrink: 0;
    }
    
    .contact-details {
        flex: 1;
    }
    
    .contact-label {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 2px;
    }
    
    .contact-value {
        font-weight: 600;
        color: #1f2937;
        font-size: 13px;
    }
    
    /* Quick Actions Buttons */
    .quick-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        text-align: center;
        width: 100%;
    }
    
    .quick-action-btn.primary {
        background: linear-gradient(135deg, #3b82f6, #7c3aed);
        color: white;
    }
    
    .quick-action-btn.secondary {
        background: linear-gradient(135deg, #4b5563, #374151);
        color: white;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    /* Trust Badges */
    .trust-badges {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    
    .trust-badge {
        text-align: center;
        padding: 12px 8px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    
    .trust-icon {
        width: 32px;
        height: 32px;
        background: var(--theme-color);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        margin: 0 auto 6px;
    }
    
    /* Availability Card */
    .availability-card {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-radius: 14px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .availability-card h4 {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 14px;
    }
    
    .availability-card p {
        font-size: 11px;
        margin-bottom: 8px;
        opacity: 0.9;
    }
    
    .book-now-btn {
        background: white;
        color: #059669;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 11px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .book-now-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Additional Sidebar Sections */
    .feature-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px;
        background: #f0f9ff;
        border-radius: 6px;
        margin-bottom: 6px;
    }
    
    .feature-badge i {
        color: var(--theme-color);
        font-size: 12px;
    }
    
    .feature-badge span {
        font-size: 11px;
        color: #1e40af;
        font-weight: 500;
    }
    
    /* GOOGLE MAP STYLES */
    .map-container {
        width: 100%;
        height: 200px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* 🆕 HORIZONTAL LAYOUT FOR MAP AND TRUST BADGES */
    .horizontal-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 20px;
    }
    
    .horizontal-card {
        background: white;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
    }
    
    /* FLOATING ACTIONS - ONLY WHATSAPP (NO PHONE) */
    .floating-actions {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .floating-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .floating-btn.whatsapp {
        background: #25d366;
    }
    
    .floating-btn:hover {
        transform: scale(1.1);
    }
    
    /* 🚨 FIX: Contact Card Overlap & Layout Issues */
    .third-column { 
        z-index: 10; 
        position: relative; 
    }

    .gallery-vertical-container { 
        z-index: 5; 
        position: relative; 
    }

    /* 🚨 FIXED: Remove ALL sticky behavior from sidebar cards */
    .sidebar-card {
        position: relative !important;
        top: auto !important;
        z-index: auto !important;
    }

    /* Ensure normal layout flow */
    .third-column {
        position: relative;
        z-index: auto;
        height: auto !important;
    }

    /* Remove any sticky positioning */
    .sticky {
        position: relative !important;
    }

    /* Layout Fix */
    @media (min-width: 1024px) {
        .third-column { 
            margin-left: 0 !important; 
        }
        
        .gallery-vertical-container { 
            margin-right: 0 !important; 
        }
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .three-column-layout {
            grid-template-columns: 1fr;
        }
        
        .third-column {
            order: 3;
        }
        
        .horizontal-cards {
            grid-template-columns: 1fr;
        }
        
        .reviews-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .gallery-vertical-container {
            height: 500px;
        }
    }
    
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .header-actions {
            width: 100%;
            align-items: stretch;
        }
        
        .phone-cta {
            justify-content: center;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .facilities-grid {
            grid-template-columns: 1fr;
        }
        
        .trust-badges {
            grid-template-columns: 1fr;
        }
        
        .reviews-grid {
            grid-template-columns: 1fr;
        }
        
        .gallery-vertical-container {
            height: 400px;
        }
        
        /* Even smaller logo on mobile */
        .logo-container {
            width: 50px !important;
            height: 50px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="modern-theme">
    <!-- Fixed Hero Section with Strict Logo Size -->
    <section class="hero-gradient text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Row -->
            <div class="header-content">
                <!-- Logo and Basic Info - STRICT SIZE ENFORCED -->
                <div class="flex items-start gap-3 flex-1">
                    <div class="relative">
                        <!-- 🚨 FIXED: Logo Display with Better Error Handling -->
                        <div class="logo-container">
                            @if($logo)
                                <img src="{{ $logo }}" alt="{{ $hostel->name }}" 
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                     style="width: 100% !important; height: 100% !important; object-fit: cover !important;">
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center" style="display: none;">
                                    <i class="fas fa-building text-white text-lg"></i>
                                </div>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <i class="fas fa-building text-white text-lg"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="hostel-info">
                        <h1 class="hostel-name nepali">{{ $hostel->name }}</h1>
                        
                        <div class="hostel-meta">
                            <div class="meta-badge">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                                <span class="nepali">{{ $hostel->city ?? 'काठमाडौं' }}</span>
                            </div>
                            
                            @if($reviewCount > 0 && $avgRating > 0)
                                <div class="meta-badge">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-yellow-300' : 'text-white/60' }} text-xs"></i>
                                        @endfor
                                    </div>
                                    <span class="font-bold">{{ number_format($avgRating, 1) }}</span>
                                    <span class="nepali">({{ $reviewCount }})</span>
                                </div>
                            @endif
                            
                            @if($hostel->available_rooms > 0)
                                <div class="meta-badge availability-badge">
                                    <i class="fas fa-bed text-xs"></i>
                                    <span class="nepali">{{ $hostel->available_rooms }} कोठा उपलब्ध</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Social Media and Phone - KEEP PHONE IN HEADER -->
                <div class="header-actions">
                    <div class="text-right">
                        <p class="text-white/80 nepali text-xs mb-1">हामीलाई फलो गर्नुहोस्</p>
                        <div class="social-media-compact">
                            @if($hostel->facebook_url)
                                <a href="{{ $hostel->facebook_url }}" target="_blank" class="social-icon-compact facebook-bg">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            
                            @if($hostel->instagram_url)
                                <a href="{{ $hostel->instagram_url }}" target="_blank" class="social-icon-compact instagram-bg">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                            
                            @if($hostel->whatsapp_number)
                                <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="social-icon-compact whatsapp-bg">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    @if($hostel->contact_phone)
                        <a href="tel:{{ $hostel->contact_phone }}" class="phone-cta nepali">
                            <i class="fas fa-phone text-green-300"></i>
                            अहिले फोन गर्नुहोस्
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $hostel->total_rooms ?? 0 }}</div>
                    <div class="stat-label nepali">कुल कोठा</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $hostel->available_rooms ?? 0 }}</div>
                    <div class="stat-label nepali">उपलब्ध कोठा</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $studentCount }}</div>
                    <div class="stat-label nepali">विद्यार्थी</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $reviewCount }}</div>
                    <div class="stat-label nepali">समीक्षाहरू</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 🎨 PERFECT 3-COLUMN LAYOUT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="three-column-layout grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- 🏠 LEFT COLUMN - Main Content -->
            <div class="space-y-6">
                <!-- About Section -->
                <section class="content-card">
                    <h2 class="section-title nepali">हाम्रो बारेमा</h2>
                    @if($hostel->description)
                        <p class="text-gray-700 leading-relaxed nepali whitespace-pre-line text-sm">
                            {{ $hostel->description }}
                        </p>
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <i class="fas fa-file-alt text-3xl mb-2 opacity-30"></i>
                            <p class="nepali italic text-sm">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
                        </div>
                    @endif
                </section>

                <!-- Contact Form Section -->
                <section class="content-card">
                    <h2 class="section-title nepali">सम्पर्क गर्नुहोस्</h2>
                    @include('public.hostels.partials.contact-form')
                </section>

                <!-- 🆕 HORIZONTAL CARDS - MAP AND TRUST BADGES -->
                <div class="horizontal-cards">
                    <!-- Map Card -->
                    <div class="horizontal-card">
                        <h3 class="text-base font-bold text-gray-900 nepali mb-3 flex items-center gap-2">
                            <i class="fas fa-map-marked-alt text-blue-600 text-sm"></i>
                            स्थान
                        </h3>
                        <div class="map-container">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.456434106934!2d85.3162222753375!3d27.70291137618537!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1966a3e8e80d%3A0x9b9e75c292c2a5e8!2sKalikasthan%2C%20Kathmandu%2044600!5e0!3m2!1sen!2snp!4v1699876543210!5m2!1sen!2snp" 
                                width="100%" 
                                height="200" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="https://maps.google.com/?q=Kalikasthan,+Kathmandu" 
                               target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium nepali inline-flex items-center gap-1">
                                <i class="fas fa-directions"></i>
                                गूगल म्यापमा हेर्नुहोस्
                            </a>
                        </div>
                    </div>

                    <!-- Trust Badges Card -->
                    <div class="horizontal-card">
                        <h3 class="text-base font-bold text-gray-900 nepali mb-3">विश्वसनीय होस्टल</h3>
                        <div class="trust-badges">
                            <div class="trust-badge">
                                <div class="trust-icon">
                                    <i class="fas fa-shield-check"></i>
                                </div>
                                <h4 class="font-bold text-gray-900 nepali text-xs mb-1">सत्यापित</h4>
                                <p class="text-gray-600 nepali text-xs">प्रमाणित होस्टल</p>
                            </div>
                            
                            <div class="trust-badge">
                                <div class="trust-icon">
                                    <i class="fas fa-award"></i>
                                </div>
                                <h4 class="font-bold text-gray-900 nepali text-xs mb-1">प्रमाणित</h4>
                                <p class="text-gray-600 nepali text-xs">गुणस्तरीय सेवा</p>
                            </div>
                            
                            <div class="trust-badge">
                                <div class="trust-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="font-bold text-gray-900 nepali text-xs mb-1">रेटेड</h4>
                                <p class="text-gray-600 nepali text-xs">उत्कृष्ट समीक्षा</p>
                            </div>
                            
                            <div class="trust-badge">
                                <div class="trust-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h4 class="font-bold text-gray-900 nepali text-xs mb-1">२४/७</h4>
                                <p class="text-gray-600 nepali text-xs">समर्थन उपलब्ध</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🖼️ MIDDLE COLUMN - VERTICAL AUTO-SCROLL GALLERY -->
            <div class="space-y-6">
                <!-- Vertical Auto-Scroll Gallery -->
                <section class="gallery-vertical-container">
                    <h2 class="section-title nepali">हाम्रो ग्यालरी</h2>
                    <p class="text-gray-600 text-center nepali text-sm mb-4">हाम्रो होस्टलको सुन्दर तस्बिरहरू र भिडियोहरू हेर्नुहोस्</p>
                    
                    <div class="gallery-vertical-scroll gallery-auto-scroll">
                        <div class="gallery-vertical-grid">
                            @php
                                $galleries = $hostel->activeGalleries ?? collect();
                            @endphp
                            
                            @if($galleries->count() > 0)
                                @foreach($galleries as $gallery)
                                <div class="gallery-vertical-item group">
                                    @if($gallery->media_type === 'image')
                                        <img src="{{ $gallery->thumbnail_url }}" 
                                             alt="{{ $gallery->title }}"
                                             class="w-full h-48 object-cover">
                                    @elseif($gallery->media_type === 'external_video')
                                        <div class="gallery-placeholder-vertical">
                                            <i class="fab fa-youtube text-3xl"></i>
                                            <span class="nepali text-sm mt-2">YouTube भिडियो</span>
                                        </div>
                                    @else
                                        <div class="gallery-placeholder-vertical">
                                            <i class="fas fa-video text-3xl"></i>
                                            <span class="nepali text-sm mt-2">भिडियो</span>
                                        </div>
                                    @endif
                                    
                                    <div class="p-3">
                                        <h4 class="font-semibold text-gray-900 nepali text-sm mb-1">{{ $gallery->title }}</h4>
                                        @if($gallery->description)
                                            <p class="text-gray-600 nepali text-xs">{{ Str::limit($gallery->description, 80) }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <!-- Placeholder for empty gallery -->
                                <div class="gallery-vertical-item">
                                    <div class="gallery-placeholder-vertical">
                                        <i class="fas fa-images text-3xl"></i>
                                        <span class="nepali text-sm mt-2">तस्बिरहरू थपिने...</span>
                                    </div>
                                </div>
                                <div class="gallery-vertical-item">
                                    <div class="gallery-placeholder-vertical">
                                        <i class="fas fa-bed text-3xl"></i>
                                        <span class="nepali text-sm mt-2">कोठाका तस्बिरहरू</span>
                                    </div>
                                </div>
                                <div class="gallery-vertical-item">
                                    <div class="gallery-placeholder-vertical">
                                        <i class="fas fa-utensils text-3xl"></i>
                                        <span class="nepali text-sm mt-2">खानाको परिवेश</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="#" class="view-gallery-btn nepali">
                            <i class="fas fa-images"></i>
                            पूरै ग्यालरी हेर्नुहोस्
                        </a>
                    </div>
                </section>
            </div>

            <!-- 📋 RIGHT COLUMN - Sidebar Content -->
            <div class="third-column space-y-6">
                <!-- 🚨 FIXED: Contact Information WITHOUT Sticky -->
                <div class="sidebar-card">
                    <h3 class="text-lg font-bold text-gray-900 nepali mb-3 flex items-center gap-2">
                        <i class="fas fa-address-card text-blue-600 text-sm"></i>
                        सम्पर्क जानकारी
                    </h3>
                    <div class="space-y-2">
                        @if($hostel->contact_person)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label nepali">सम्पर्क व्यक्ति</div>
                                    <div class="contact-value nepali">{{ $hostel->contact_person }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->contact_phone)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label nepali">फोन नम्बर</div>
                                    <a href="tel:{{ $hostel->contact_phone }}" class="contact-value hover:text-blue-600 transition-colors">
                                        {{ $hostel->contact_phone }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->contact_email)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label nepali">इमेल ठेगाना</div>
                                    <a href="mailto:{{ $hostel->contact_email }}" class="contact-value hover:text-blue-600 transition-colors text-xs">
                                        {{ $hostel->contact_email }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->address)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label nepali">ठेगाना</div>
                                    <div class="contact-value nepali text-xs">{{ $hostel->address }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="text-base font-bold text-gray-900 nepali mb-2">द्रुत कार्यहरू</h4>
                        <div class="space-y-2">
                            <a href="#contact-form" 
                               class="quick-action-btn primary nepali">
                                <i class="fas fa-envelope text-xs"></i>
                                <span>सन्देश पठाउनुहोस्</span>
                            </a>
                            
                            <a href="{{ route('hostels.index') }}" 
                               class="quick-action-btn secondary nepali">
                                <i class="fas fa-building text-xs"></i>
                                <span>अन्य होस्टलहरू</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 🆕 AVAILABILITY CARD - SECOND LAST POSITION -->
                @if($hostel->available_rooms > 0)
                    <div class="availability-card">
                        <i class="fas fa-bed text-xl mb-2"></i>
                        <h4 class="nepali">कोठा उपलब्ध!</h4>
                        <p class="nepali">अहिले {{ $hostel->available_rooms }} कोठा खाली छन्</p>
                        <a href="#contact-form" class="book-now-btn nepali">
                            अहिले बुक गर्नुहोस्
                        </a>
                    </div>
                @else
                    <div class="sidebar-card bg-gray-600 text-white text-center">
                        <i class="fas fa-bed text-xl mb-2"></i>
                        <h4 class="font-bold nepali mb-1 text-sm">सबै कोठा भरिएको</h4>
                        <p class="nepali text-xs">अहिले कुनै कोठा उपलब्ध छैन</p>
                    </div>
                @endif

                <!-- 🆕 FIXED: FACILITIES SECTION - BELOW AVAILABILITY -->
                @if(!empty($facilities) && count($facilities) > 0)
                    <section class="sidebar-card">
                        <h2 class="text-lg font-bold text-gray-900 nepali mb-3 flex items-center gap-2">
                            <i class="fas fa-list-check text-blue-600 text-sm"></i>
                            हाम्रा सुविधाहरू
                        </h2>
                        <div class="facilities-grid">
                            @foreach($facilities as $facility)
                                @if(!empty(trim($facility)) && !in_array(trim($facility), ['[', ']', '"']))
                                    <div class="facility-item">
                                        <div class="facility-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span class="nepali font-medium text-gray-800 text-sm">
                                            {{ trim($facility) }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- 🆕 SPECIAL FEATURES - AT THE BOTTOM -->
                <div class="sidebar-card">
                    <h3 class="text-base font-bold text-gray-900 nepali mb-3">विशेष सुविधाहरू</h3>
                    <div class="space-y-1">
                        <div class="feature-badge">
                            <i class="fas fa-shield-alt"></i>
                            <span class="nepali">२४/७ सुरक्षा</span>
                        </div>
                        <div class="feature-badge">
                            <i class="fas fa-wifi"></i>
                            <span class="nepali">नि:शुल्क WiFi</span>
                        </div>
                        <div class="feature-badge">
                            <i class="fas fa-utensils"></i>
                            <span class="nepali">स्वच्छ खाना</span>
                        </div>
                        <div class="feature-badge">
                            <i class="fas fa-fan"></i>
                            <span class="nepali">२४/७ बिजुली</span>
                        </div>
                        <div class="feature-badge">
                            <i class="fas fa-tint"></i>
                            <span class="nepali">२४/७ पानी</span>
                        </div>
                        <div class="feature-badge">
                            <i class="fas fa-soap"></i>
                            <span class="nepali">लण्ड्री सेवा</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🆕 HORIZONTAL REVIEWS SECTION - BOTTOM OF PAGE -->
        <section class="content-card mt-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="section-title nepali">विद्यार्थी समीक्षाहरू</h2>
                <div class="bg-purple-100 text-purple-800 px-4 py-2 rounded-full">
                    <span class="nepali font-semibold">{{ $reviewCount }} समीक्षाहरू</span>
                </div>
            </div>

            @if($reviewCount > 0)
                <div class="reviews-grid">
                    @foreach($reviews as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        {{ substr($review->student->user->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div class="reviewer-details">
                                        <h4 class="nepali">{{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}</h4>
                                        <div class="flex items-center gap-2">
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                                @endfor
                                            </div>
                                            <span class="review-date">{{ $review->created_at->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-gray-700 nepali mb-3 text-sm">{{ $review->comment }}</p>
                            
                            @if($review->reply)
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded-lg">
                                    <div class="flex items-start space-x-2">
                                        <i class="fas fa-reply text-blue-500 mt-0.5 text-xs"></i>
                                        <div>
                                            <strong class="text-blue-800 nepali text-xs">होस्टलको जवाफ:</strong>
                                            <p class="text-blue-700 mt-1 nepali text-xs">{{ $review->reply }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($reviews->hasPages())
                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comment-slash text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-600 nepali mb-2">अहिलेसम्म कुनै समीक्षा छैन</h3>
                    <p class="text-gray-500 nepali mb-4">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
                    <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors nepali">
                        <i class="fas fa-pen mr-2"></i>पहिलो समीक्षा लेख्नुहोस्
                    </button>
                </div>
            @endif
        </section>
    </div>

    <!-- FLOATING ACTIONS - ONLY WHATSAPP (NO PHONE) -->
    <div class="floating-actions">
        @if($hostel->whatsapp_number)
            <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="floating-btn whatsapp">
                <i class="fab fa-whatsapp"></i>
            </a>
        @endif
    </div>
</div>

<script>
// Auto-scroll functionality for gallery
document.addEventListener('DOMContentLoaded', function() {
    const galleryScroll = document.querySelector('.gallery-vertical-scroll');
    if (galleryScroll) {
        let scrollPosition = 0;
        const scrollSpeed = 0.5; // pixels per frame
        
        function autoScroll() {
            scrollPosition += scrollSpeed;
            if (scrollPosition >= galleryScroll.scrollHeight - galleryScroll.clientHeight) {
                scrollPosition = 0;
            }
            galleryScroll.scrollTop = scrollPosition;
            requestAnimationFrame(autoScroll);
        }
        
        // Start auto-scroll
        autoScroll();
    }
});
</script>
@endsection