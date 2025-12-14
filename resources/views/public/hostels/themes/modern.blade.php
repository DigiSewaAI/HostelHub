{{-- modern.blade.php - COMPLETE FIXED FILE WITH ALL ISSUES RESOLVED --}}
@php 
    use Illuminate\Support\Facades\Storage;
    
    $theme = 'modern';
    $themeColor = $hostel->theme_color ?? '#3b82f6';
    
    // ✅ FIXED: Structured data preparation
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Hotel',
        'name' => $hostel->name,
        'description' => $hostel->description,
        'image' => $hostel->logo_url ?? asset('images/modern-default.jpg'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $hostel->address,
            'addressLocality' => $hostel->city,
            'addressCountry' => 'NP'
        ],
        'telephone' => $hostel->contact_phone,
        'priceRange' => 'रु ' . ($hostel->min_price ?? 0) . ' - रु ' . ($hostel->max_price ?? 0),
        'checkinTime' => '14:00',
        'checkoutTime' => '12:00',
        'currenciesAccepted' => 'NPR',
        'paymentAccepted' => 'Cash, Credit Card, Mobile Payment',
        'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => $avgRating ?? 4.5,
            'reviewCount' => $reviewCount ?? 0,
            'bestRating' => '5',
            'worstRating' => '1'
        ],
        'amenityFeature' => []
    ];

    // Base amenities
    $structuredData['amenityFeature'][] = [
        '@type' => 'LocationFeatureSpecification',
        'name' => 'Modern Design',
        'value' => true
    ];
    $structuredData['amenityFeature'][] = [
        '@type' => 'LocationFeatureSpecification',
        'name' => 'High-speed WiFi',
        'value' => true
    ];

    // Facilities add gareko
    if(!empty($facilities)) {
        foreach($facilities as $facility) {
            $cleanFacility = trim($facility, ' ,"\'[]');
            if(!empty($cleanFacility)) {
                $structuredData['amenityFeature'][] = [
                    '@type' => 'LocationFeatureSpecification',
                    'name' => $cleanFacility
                ];
            }
        }
    }
@endphp

@extends('layouts.public')

@section('page-title', ($hostel->name ?? 'Hostel') . ' - Modern Theme')
@section('page-description', Str::limit($hostel->description ?? 'आधुनिक होस्टल अनुभव', 160))

@push('head')
{{-- ✅ FIX 1: Nepali fonts loaded via HTML link instead of CSS @import --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<meta name="theme-color" content="{{ $themeColor }}">

{{-- Modern Theme SEO --}}
@if(!isset($preview) || !$preview)
    <title>{{ $hostel->name }} - Modern Hostel | {{ $hostel->city ?? 'City' }}</title>
    <meta name="description" content="🏨 {{ Str::limit($hostel->description, 155) }} | Modern student hostel with premium amenities">
    <meta name="keywords" content="modern hostel, {{ $hostel->city }}, student accommodation, {{ $hostel->name }}, premium hostel">
    <meta name="theme-color" content="#3b82f6">
    <meta name="color-scheme" content="light dark">
    
    {{-- Open Graph --}}
    <meta property="og:title" content="🏨 {{ $hostel->name }} | Modern Hostel">
    <meta property="og:description" content="{{ Str::limit($hostel->description, 155) }}">
    <meta property="og:image" content="{{ $hostel->logo_url ?? asset('images/modern-hostel-og.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Modern Hostel">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="🏨 {{ $hostel->name }} | Modern Design">
    <meta name="twitter:description" content="{{ Str::limit($hostel->description, 155) }}">
    <meta name="twitter:image" content="{{ $hostel->logo_url ?? asset('images/modern-twitter-card.jpg') }}">
    
    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">
@else
    <title>Preview: {{ $hostel->name }} | Modern Theme</title>
    <meta name="robots" content="noindex, nofollow">
@endif

{{-- CRITICAL MODERN THEME CSS (inline for fast loading) --}}
<style>
    /* CRITICAL MODERN THEME CSS - NO @import HERE */
    :root {
        --primary: {{ $themeColor }};
        --primary-gradient: linear-gradient(135deg, {{ $themeColor }} 0%, #7c3aed 100%);
        --sidebar-width: 340px;
        --card-radius: 12px;
        --transition: cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-theme {
        font-family: 'Noto Sans Devanagari', 'Inter', 'Segoe UI', system-ui, sans-serif;
        color: #1f2937;
        line-height: 1.6;
    }

    /* Above-the-fold styling */
    .modern-container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 20px !important;
        width: 100% !important;
    }

    .modern-hero {
        background: linear-gradient(135deg, {{ $themeColor }} 0%, #7c3aed 100%);
        color: white;
        padding: 2rem 0;
        width: 100%;
    }

    .modern-card {
        background: white;
        border-radius: var(--card-radius);
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .hero-header {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .logo-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--primary);
    }

    /* Mobile-first responsive */
    @media (max-width: 768px) {
        .hero-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }
        
        .logo-container {
            width: 60px;
            height: 60px;
        }
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    /* Skip to main content link */
    .skip-link {
        position: absolute;
        top: -40px;
        left: 0;
        background: var(--primary);
        color: white;
        padding: 8px;
        z-index: 1000;
        text-decoration: none;
    }
    
    .skip-link:focus {
        top: 0;
    }

    /* Modern Stats Grid */
    .modern-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .modern-stat-card {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .modern-layout-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    @media (min-width: 1024px) {
        .modern-layout-grid {
            grid-template-columns: 1fr 1fr 300px;
        }
    }

    /* Floating Actions */
    .modern-floating-actions {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        z-index: 1000;
    }

    .floating-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .floating-btn.whatsapp {
        background: #25D366;
    }

    .floating-btn.phone {
        background: #3b82f6;
    }

    .floating-btn.scroll-top {
        background: #6b7280;
    }

    .floating-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    /* Book Now Button */
    .book-now-button {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .book-now-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }

    /* Gallery Styles */
    .room-gallery-vertical {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .room-gallery-item {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .room-gallery-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        transform: translateY(-2px);
    }

    .room-gallery-image {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .room-gallery-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .room-gallery-content {
        flex: 1;
    }

    .featured-badge {
        background: #fef3c7;
        color: #92400e;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .category-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    /* Empty States */
    .empty-gallery-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-gallery-icon {
        width: 64px;
        height: 64px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    /* Lazy Loading Images */
    .lazy-image {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lazy-image.loaded {
        opacity: 1;
    }

    /* ✅ ENHANCED: Vertical Gallery Styles */
    .room-gallery-vertical-enhanced {
        max-height: 700px;
        overflow-y: auto;
        padding-right: 10px;
        scroll-behavior: smooth;
    }

    .room-gallery-vertical-enhanced::-webkit-scrollbar {
        width: 8px;
    }

    .room-gallery-vertical-enhanced::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .room-gallery-vertical-enhanced::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 4px;
    }

    .room-gallery-vertical-enhanced::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    .room-gallery-item-enhanced {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .room-gallery-item-enhanced:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
        transform: translateY(-3px);
    }

    .room-gallery-image-enhanced {
        width: 200px;
        height: 200px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .room-gallery-image-enhanced img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .room-gallery-item-enhanced:hover .room-gallery-image-enhanced img {
        transform: scale(1.05);
    }

    .room-gallery-content-enhanced {
        flex: 1;
        min-width: 0;
    }

    .featured-badge-enhanced {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .category-badge-enhanced {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .view-details-btn {
        transition: all 0.2s ease;
    }

    .view-details-btn:hover {
        gap: 0.5rem;
    }

    /* Enhanced Empty State */
    .empty-gallery-state-enhanced {
        padding: 3rem 1.5rem;
        border-radius: 16px;
    }

    .empty-gallery-icon-enhanced {
        width: 5rem;
        height: 5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
    }

    /* Auto scroll animation */
    @keyframes pulse-glow {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }

    .auto-scroll-active {
        animation: pulse-glow 2s infinite;
    }

    /* Gallery controls */
    .gallery-controls {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
        border-radius: 0 0 12px 12px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .room-gallery-item-enhanced {
            flex-direction: column;
            gap: 1rem;
        }
        
        .room-gallery-image-enhanced {
            width: 100%;
            height: 200px;
        }
        
        .room-gallery-vertical-enhanced {
            max-height: 600px;
        }
    }
</style>

{{-- Load non-critical CSS deferred --}}
<link rel="preload" href="{{ asset('css/themes/modern.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('css/themes/modern.css') }}"></noscript>

{{-- ✅ FIXED: Structured Data for SEO --}}
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<!-- Skip to main content -->
<a href="#main-content" class="sr-only skip-link" 
   onclick="this.style.top='0'">
   Skip to main content
</a>

<div class="modern-theme">
    <!-- Hero Section -->
    <section class="modern-hero">
        <div class="modern-container">
            <!-- Preview Alert -->
            @if(isset($preview) && $preview)
            <div class="modern-card mb-6 border-l-4 border-yellow-400 bg-white/10 backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-eye text-yellow-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white nepali">पूर्वावलोकन मोड</h4>
                            <p class="text-white/80 text-sm nepali">तपाईंले यो पृष्ठ कसरी देखिन्छ हेर्दै हुनुहुन्छ</p>
                        </div>
                    </div>
                    <a href="{{ route('owner.public-page.edit') }}" 
                       class="px-4 py-2 bg-white text-indigo-900 rounded-lg font-semibold hover:bg-gray-100 transition-colors nepali">
                        <i class="fas fa-edit mr-2"></i> सम्पादन गर्नुहोस्
                    </a>
                </div>
            </div>
            @endif

            <!-- Hero Header -->
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-8">
                <!-- Logo & Basic Info -->
                <div class="flex items-start gap-4 md:gap-6 flex-1">
                    <!-- Logo Container -->
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl overflow-hidden border-4 border-white shadow-lg bg-white flex-shrink-0">
                        @if($hostel->logo_url)
                            <img src="{{ $hostel->logo_url }}" 
                                 alt="{{ $hostel->name }} - Modern Hostel Logo"
                                 class="w-full h-full object-cover"
                                 width="96"
                                 height="96"
                                 loading="eager"
                                 onerror="this.src='{{ asset('images/default-hostel.png') }}'">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <i class="fas fa-building text-white text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Hostel Info -->
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ $hostel->name }}</h1>
                        
                        <div class="flex flex-wrap gap-2 mb-3">
                            <!-- Location -->
                            <div class="inline-flex items-center gap-1 bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                                <span class="font-medium text-xs">{{ $hostel->city ?? 'काठमाडौं' }}</span>
                            </div>
                            
                            <!-- Available Rooms -->
                            @if($hostel->available_rooms > 0)
                            <div class="inline-flex items-center gap-1 bg-green-500/90 px-3 py-1 rounded-full">
                                <i class="fas fa-bed text-xs"></i>
                                <span class="font-medium text-xs">{{ $hostel->available_rooms }} कोठा उपलब्ध</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Social & Contact -->
                <div class="flex flex-col items-start md:items-end gap-3">
                    <!-- Social Media -->
                    <div>
                        <p class="text-white/90 text-sm mb-2">हामीलाई फलो गर्नुहोस्</p>
                        <div class="flex gap-1">
                            @if($hostel->facebook_url)
                            <a href="{{ $hostel->facebook_url }}" target="_blank" 
                               class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/10 hover:bg-white/20 transition-colors"
                               aria-label="Facebook">
                                <i class="fab fa-facebook-f text-white text-sm"></i>
                            </a>
                            @endif
                            
                            @if($hostel->instagram_url)
                            <a href="{{ $hostel->instagram_url }}" target="_blank" 
                               class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/10 hover:bg-white/20 transition-colors"
                               aria-label="Instagram">
                                <i class="fab fa-instagram text-white text-sm"></i>
                            </a>
                            @endif
                            
                            @if($hostel->whatsapp_number)
                            <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" 
                               class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/10 hover:bg-white/20 transition-colors"
                               aria-label="WhatsApp">
                                <i class="fab fa-whatsapp text-white text-sm"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Phone CTA -->
                    @if($hostel->contact_phone)
                    <div>
                        <a href="tel:{{ $hostel->contact_phone }}" 
                           class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl text-white font-semibold transition-all duration-300 backdrop-blur-sm"
                           aria-label="फोन गर्नुहोस्">
                            <i class="fas fa-phone text-green-300"></i>
                            <div class="text-left">
                                <div class="text-xs">फोन गर्नुहोस्</div>
                                <div class="font-bold">{{ $hostel->contact_phone }}</div>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="modern-stats-grid">
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1 stat-number">{{ $hostel->total_rooms ?? 0 }}</div>
                    <div class="opacity-90 text-sm">कुल कोठा</div>
                </div>
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1 stat-number">{{ $hostel->available_rooms ?? 0 }}</div>
                    <div class="opacity-90 text-sm">उपलब्ध कोठा</div>
                </div>
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1 stat-number">{{ $studentCount ?? 0 }}</div>
                    <div class="opacity-90 text-sm">विद्यार्थी</div>
                </div>
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1 stat-number">{{ $reviewCount ?? 0 }}</div>
                    <div class="opacity-90 text-sm">समीक्षाहरू</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main id="main-content" class="modern-container py-8">
        <div class="modern-layout-grid">
            <!-- LEFT COLUMN - Main Content -->
            <div class="space-y-8">
                <!-- About Section -->
                <div class="modern-card">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            हाम्रो बारेमा
                        </h2>
                    </div>
                    
                    @if($hostel->description)
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $hostel->description }}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-file-alt text-4xl mb-3 opacity-30"></i>
                            <p class="italic text-sm">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
                        </div>
                    @endif
                    
                    <!-- Additional Info -->
                    <div class="border-t pt-6 mt-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach([
                                ['icon' => 'user-friends', 'label' => 'विद्यार्थी क्षमता', 'value' => $hostel->max_capacity ?? '५०+'],
                                ['icon' => 'wifi', 'label' => 'WiFi गति', 'value' => '१०० Mbps'],
                                ['icon' => 'shield-alt', 'label' => 'सुरक्षा', 'value' => '२४/७'],
                                ['icon' => 'utensils', 'label' => 'भोजन', 'value' => 'समावेश']
                            ] as $info)
                            <div class="text-center p-3 bg-blue-50 rounded-xl">
                                <i class="fas fa-{{ $info['icon'] }} text-blue-600 text-lg mb-2"></i>
                                <div class="font-bold text-gray-900 text-sm">{{ $info['value'] }}</div>
                                <div class="text-gray-600 text-xs mt-1">{{ $info['label'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="modern-card">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i class="fas fa-envelope text-blue-600"></i>
                            सम्पर्क गर्नुहोस्
                        </h2>
                    </div>
                    <!-- Include Contact Form -->
                    @if(View::exists('public.hostels.partials.contact-form'))
                        @include('public.hostels.partials.contact-form')
                    @else
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-600 mb-4">सम्पर्क फर्म यहाँ हुनेछ</p>
                            <form class="space-y-4">
                                <div>
                                    <label for="contactName" class="block text-sm font-medium text-gray-700 mb-1">तपाईंको नाम</label>
                                    <input type="text" id="contactName" name="name" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           aria-required="true">
                                </div>
                                <div>
                                    <label for="contactEmail" class="block text-sm font-medium text-gray-700 mb-1">इमेल ठेगाना</label>
                                    <input type="email" id="contactEmail" name="email" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           aria-required="true">
                                </div>
                                <div>
                                    <label for="contactMessage" class="block text-sm font-medium text-gray-700 mb-1">सन्देश</label>
                                    <textarea id="contactMessage" rows="4" name="message"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                              placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..."
                                              aria-required="true"></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors book-now-button"
                                        aria-label="सन्देश पठाउनुहोस्">
                                    सन्देश पठाउनुहोस्
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Map & Trust -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Map Card -->
                    <div class="modern-card">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marked-alt text-blue-600"></i>
                            हाम्रो स्थान
                        </h3>
                        <div class="aspect-video w-full rounded-xl overflow-hidden bg-gray-100">
                            @if($hostel->google_maps_url)
                                <iframe src="{{ $hostel->google_maps_url }}" 
                                        width="100%" 
                                        height="100%" 
                                        style="border:0;" 
                                        allowfullscreen 
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        title="{{ $hostel->name }} Location Map">
                                </iframe>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-map text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500">म्याप उपलब्ध छैन</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($hostel->address)
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt text-blue-600 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm mb-1">ठेगाना</h4>
                                    <p class="text-gray-700 text-sm">{{ $hostel->address }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Trust Badges -->
                    <div class="modern-card">
                        <h3 class="text-lg font-bold mb-4">किन हामीलाई रोज्ने?</h3>
                        <div class="space-y-4">
                            @foreach([
                                ['icon' => 'shield-check', 'color' => 'blue', 'title' => 'सत्यापित सुरक्षा', 'desc' => 'CCTV र २४/७ सुरक्षा गार्ड'],
                                ['icon' => 'award', 'color' => 'green', 'title' => 'प्रमाणित गुणस्तर', 'desc' => 'ISO मानक अनुरूप सेवा'],
                                ['icon' => 'star', 'color' => 'yellow', 'title' => 'उत्कृष्ट समीक्षा', 'desc' => '४.५+ औसत रेटिंग'],
                                ['icon' => 'clock', 'color' => 'purple', 'title' => '२४/७ समर्थन', 'desc' => 'सधैं उपलब्ध सम्पर्क']
                            ] as $badge)
                            <div class="flex items-start gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="w-10 h-10 bg-{{ $badge['color'] }}-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-{{ $badge['icon'] }} text-{{ $badge['color'] }}-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $badge['title'] }}</h4>
                                    <p class="text-gray-600 text-xs mt-1">{{ $badge['desc'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- MIDDLE COLUMN - Gallery -->
            <div class="space-y-8">
                <!-- Gallery Card -->
                <div class="modern-card mb-10">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i class="fas fa-images text-purple-600"></i>
                            कोठाको ग्यालरी
                        </h2>
                        @if($hostel->slug)
                            <a href="{{ route('hostels.full.gallery', $hostel->slug) }}" 
                               class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center gap-1"
                               aria-label="सबै ग्यालरी हेर्नुहोस्">
                                <i class="fas fa-external-link-alt mr-1"></i> सबै हेर्नुहोस्
                            </a>
                        @endif
                    </div>
                    
                    <!-- ✅ ENHANCED: Vertical Sliding Gallery with 12-14 images -->
                    @php
                        // Get ALL active galleries, maximum 14 for vertical slider
                        $allGalleries = $hostel->galleries()->where('is_active', true)->take(14)->get();
                        
                        // Room categories for filtering
                        $roomCategories = ['1 seater', '2 seater', '3 seater', '4 seater', 'other', 'साझा कोठा'];
                        
                        // Filter room galleries
                        $roomGalleries = $allGalleries->filter(function($gallery) use ($roomCategories) {
                            return in_array(strtolower($gallery->category), array_map('strtolower', $roomCategories)) || 
                                   $gallery->room_id !== null;
                        })->take(14);
                    @endphp
                    
                    @if($roomGalleries->count() > 0)
                        <!-- Enhanced Vertical Scroll Container -->
                        <div class="room-gallery-vertical-enhanced" 
                             id="verticalGallery"
                             data-auto-scroll="true">
                            @foreach($roomGalleries as $index => $gallery)
                                <div class="room-gallery-item-enhanced flex gap-4 p-4 mb-3 bg-white rounded-xl border border-gray-200 hover:border-blue-400 hover:shadow-lg transition-all duration-300"
                                     role="button"
                                     tabindex="0"
                                     aria-label="View {{ $gallery->title }}"
                                     onclick="window.openModernLightbox(this, {{ $index }})"
                                     onkeydown="if(event.key === 'Enter' || event.key === ' ') { window.openModernLightbox(this, {{ $index }}); }"
                                     data-gallery-index="{{ $index }}">
                                    
                                    <!-- ✅ ENHANCED: Bigger Image Container (200x200) -->
                                    <div class="room-gallery-image-enhanced flex-shrink-0">
                                        @php
                                            // Get high-quality image URL
                                            $imgUrl = $gallery->media_url ?? asset('images/default-room.png');
                                            
                                            // Try to get original/high quality image
                                            if (!empty($gallery->media_path)) {
                                                $originalPath = $gallery->media_path;
                                                $imgUrl = Storage::disk('public')->exists($originalPath) 
                                                          ? Storage::disk('public')->url($originalPath)
                                                          : $imgUrl;
                                            }
                                            
                                            // Fallback for thumbnails
                                            if (empty($imgUrl) || str_contains($imgUrl, 'default-room.png')) {
                                                $imgUrl = $gallery->thumbnail_url ?? asset('images/default-room.png');
                                            }
                                        @endphp
                                        
                                        <!-- High-quality image with eager loading for first 3 -->
                                        <img class="gallery-item-image-enhanced rounded-lg shadow-md w-full h-full object-cover"
                                             src="{{ $imgUrl }}"
                                             data-src="{{ $imgUrl }}"
                                             alt="{{ $gallery->title }}"
                                             width="200"
                                             height="200"
                                             loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                                             onerror="this.onerror=null; this.src='{{ asset('images/default-room.png') }}'; this.classList.add('error-fallback');">
                                    </div>
                                    
                                    <!-- Enhanced Content Area -->
                                    <div class="room-gallery-content-enhanced flex-1 min-w-0">
                                        <div class="flex items-start justify-between mb-3">
                                            <h4 class="font-bold text-gray-900 text-lg gallery-item-title truncate">
                                                {{ $gallery->title }}
                                            </h4>
                                            @if($gallery->is_featured)
                                                <span class="featured-badge-enhanced bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 flex-shrink-0">
                                                    <i class="fas fa-star text-xs"></i> फिचर्ड
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($gallery->description)
                                            <p class="text-gray-700 text-sm mb-4 gallery-item-desc line-clamp-2 leading-relaxed">
                                                {{ $gallery->description }}
                                            </p>
                                        @endif
                                        
                                        <!-- Enhanced Badges -->
                                        <div class="flex flex-wrap items-center gap-3 mb-3">
                                            <!-- Room Type Badge -->
                                            <span class="category-badge-enhanced bg-blue-100 text-blue-800 px-3 py-1.5 rounded-lg text-sm font-medium flex items-center gap-2">
                                                <i class="fas fa-tag text-xs"></i>
                                                {{ $gallery->category_nepali ?? $gallery->category ?? 'कोठा' }}
                                            </span>
                                            
                                            <!-- Room Number Badge -->
                                            @if($gallery->room_id && $gallery->room)
                                                <span class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1.5 rounded-lg text-sm font-medium">
                                                    <i class="fas fa-door-open"></i>
                                                    कोठा: {{ $gallery->room->room_number ?? 'N/A' }}
                                                </span>
                                            @endif
                                            
                                            <!-- Capacity Badge -->
                                            @if(str_contains(strtolower($gallery->category ?? ''), 'seater'))
                                                @php
                                                    $capacity = explode(' ', strtolower($gallery->category))[0] ?? '1';
                                                @endphp
                                                <span class="inline-flex items-center gap-2 bg-purple-100 text-purple-800 px-3 py-1.5 rounded-lg text-sm font-medium">
                                                    <i class="fas fa-user-friends"></i>
                                                    {{ $capacity }} जना
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- ✅ FIXED: "विस्तृत हेर्नुहोस्" button REMOVED per instructions -->
                                        <div class="pt-3 border-t border-gray-100">
                                            <div class="text-xs text-gray-500 flex items-center gap-2">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $gallery->created_at->format('Y-m-d') ?? '२०२५-०१-०१' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Gallery Stats & Controls -->
                        <div class="flex flex-col sm:flex-row items-center justify-between mt-6 pt-6 border-t border-gray-200 bg-gray-50 p-4 rounded-xl">
                            <div class="flex items-center gap-4 mb-3 sm:mb-0">
                                <div class="text-sm text-gray-700">
                                    <i class="fas fa-image text-blue-600 mr-1"></i>
                                    <span class="font-bold text-lg">{{ $roomGalleries->count() }}</span> वटा कोठाका तस्बिरहरू
                                </div>
                                
                                <!-- Auto-scroll toggle -->
                                <div class="flex items-center gap-2">
                                    <button id="toggleAutoScroll" 
                                            class="text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors flex items-center gap-1"
                                            onclick="toggleAutoScroll()">
                                        <i class="fas fa-play" id="autoScrollIcon"></i>
                                        <span id="autoScrollText">स्वत: स्लाइड</span>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <!-- Scroll instructions -->
                                <div class="text-xs text-gray-600 flex items-center gap-2">
                                    <i class="fas fa-mouse-pointer text-gray-500"></i>
                                    <span>माथि तल स्क्रोल गर्नुहोस्</span>
                                </div>
                                
                                <!-- Manual scroll buttons -->
                                <div class="flex gap-2">
                                    <button onclick="scrollGallery('up')" 
                                            class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors"
                                            aria-label="माथि स्क्रोल">
                                        <i class="fas fa-chevron-up text-xs"></i>
                                    </button>
                                    <button onclick="scrollGallery('down')" 
                                            class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors"
                                            aria-label="तल स्क्रोल">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Enhanced Empty State -->
                        <div class="empty-gallery-state-enhanced text-center py-12 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl border-2 border-dashed border-gray-300">
                            <div class="empty-gallery-icon-enhanced w-20 h-20 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i class="fas fa-images text-blue-500 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">कोठाको ग्यालरी उपलब्ध छैन</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">यस होस्टलका कोठाका तस्बिरहरू चाँहि थपिएको छैन। तपाईं सिधै कोठा बुक गर्न सक्नुहुन्छ।</p>
                            
                            @if($hostel->slug)
                                <a href="{{ route('hostels.book', $hostel->slug) }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-700 hover:to-emerald-700 transition-all transform hover:-translate-y-0.5 hover:shadow-lg"
                                   aria-label="सिधै बुक गर्नुहोस्">
                                    <i class="fas fa-bed mr-2"></i>
                                    सिधै कोठा बुक गर्नुहोस्
                                </a>
                            @endif
                        </div>
                    @endif
                    
                    <!-- ✅ FIXED: Room Photos Button - Text changed to "कोठा हेर्दै बुक गर्नुहोस्" with improved subtext -->
                    @if($hostel->slug && $roomGalleries->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 gap-4">
                                <!-- ✅ FIXED: "कोठा हेर्दै बुक गर्नुहोस्" Button with updated user-friendly text -->
                                <a href="{{ route('hostel.gallery', $hostel->slug) }}"
                                   class="flex items-center justify-center gap-3 px-4 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all transform hover:-translate-y-0.5 hover:shadow-xl"
                                   aria-label="कोठा हेर्दै बुक गर्नुहोस्">
                                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-camera text-xl"></i>
                                    </div>
                                    <div class="text-left">
                                        <div class="font-bold text-lg">कोठा हेर्दै बुक गर्नुहोस्</div>
                                        <!-- ✅ FIXED: Changed from "({{ $roomGalleries->count() }} तस्बिरहरू हेर्नुहोस्)" to "(आफूलाई मनपर्ने कोठा)" -->
                                        <div class="text-sm opacity-90">(आफूलाई मनपर्ने कोठा)</div>
                                    </div>
                                    <i class="fas fa-arrow-right ml-auto"></i>
                                </a>
                                
                                <!-- ✅ FIXED: "पुरै ग्यालरी हेर्नुहोस्" Button RECOVERED -->
                                @if($hostel->slug)
                                    <div class="mt-4">
                                        <a href="{{ route('hostels.full.gallery', $hostel->slug) }}" 
                                           class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-indigo-700 transition-all transform hover:-translate-y-0.5 hover:shadow-xl full-gallery-btn"
                                           aria-label="पुरै ग्यालरी हेर्नुहोस्">
                                            <i class="fas fa-images mr-2"></i>
                                            पुरै ग्यालरी हेर्नुहोस्
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- RIGHT COLUMN - Sidebar -->
            <div class="space-y-8">
                <!-- Contact Information -->
                <div class="modern-card">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-address-card text-blue-600"></i>
                        सम्पर्क जानकारी
                    </h3>
                    <div class="space-y-3">
                        @if($hostel->contact_person)
                            <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">सम्पर्क व्यक्ति</div>
                                    <div class="font-medium">{{ $hostel->contact_person }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->contact_phone)
                            <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-content">
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">फोन नम्बर</div>
                                    <a href="tel:{{ $hostel->contact_phone }}" 
                                       class="font-medium hover:text-blue-600 transition-colors"
                                       aria-label="फोन गर्नुहोस् {{ $hostel->contact_phone }}">
                                        {{ $hostel->contact_phone }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->contact_email)
                            <div class="flex items-start gap-3 p-3 bg-purple-50 rounded-lg">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-content">
                                    <i class="fas fa-envelope text-purple-600"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">इमेल ठेगाना</div>
                                    <a href="mailto:{{ $hostel->contact_email }}" 
                                       class="font-medium hover:text-blue-600 transition-colors text-sm break-all"
                                       aria-label="इमेल पठाउनुहोस् {{ $hostel->contact_email }}">
                                        {{ $hostel->contact_email }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->address)
                            <div class="flex items-start gap-3 p-3 bg-orange-50 rounded-lg">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-content">
                                    <i class="fas fa-map-marker-alt text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">ठेगाना</div>
                                    <div class="font-medium text-sm leading-relaxed">
                                        {{ $hostel->address }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Availability Card - FIXED TEXT COLOR -->
                <div class="modern-card availability-card-{{ $hostel->available_rooms > 0 ? 'available' : 'unavailable' }}">
                    <div class="p-5">
                        <div class="flex items-center justify-center gap-3 mb-3">
                            <i class="fas fa-bed text-2xl {{ $hostel->available_rooms > 0 ? 'text-green-600' : 'text-gray-500' }}"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2 availability-text">
                            @if($hostel->available_rooms > 0)
                                कोठा उपलब्ध!
                            @else
                                सबै कोठा भरिएको
                            @endif
                        </h4>
                        
                        @if($hostel->available_rooms > 0)
                            <p class="mb-4 text-sm room-count-text">
                                अहिले {{ $hostel->available_rooms }} कोठा खाली छन्
                            </p>
                            <!-- FIXED: Button redirects to booking route -->
                            <a href="{{ route('hostels.book', $hostel->slug) ?: url('/hostels/' . $hostel->slug . '/book') }}"  
                               class="inline-block w-full text-center book-now-button"
                               aria-label="अहिले बुक गर्नुहोस्">
                                <i class="fas fa-calendar-check mr-2"></i>
                                अहिले बुक गर्नुहोस्
                            </a>
                        @else
                            <p class="text-sm mb-4 text-gray-700">अहिले कुनै कोठा उपलब्ध छैन</p>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 px-4 py-3 rounded-lg text-sm transition-colors"
                                    aria-label="नोटिफिकेशन दर्ता">
                                <i class="fas fa-bell mr-2"></i>
                                नोटिफिकेशन दर्ता
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Facilities -->
                @if(!empty($facilities) && count($facilities) > 0)
                    <div class="modern-card">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b">
                            <h3 class="text-lg font-bold flex items-center gap-2">
                                <i class="fas fa-list-check text-blue-600"></i>
                                हाम्रा सुविधाहरू
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($facilities as $facility)
                                @php
                                    $cleanFacility = trim($facility, ' ,"\'[]');
                                @endphp
                                @if(!empty($cleanFacility))
                                    <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                        <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                                            <i class="fas fa-check text-blue-600 text-xs"></i>
                                        </div>
                                        <span class="font-medium text-gray-800 text-sm">
                                            {{ $cleanFacility }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Price Range -->
                <div class="modern-card">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-tag text-green-600"></i>
                        मूल्य सीमा
                    </h3>
                    <div class="space-y-3">
                        @if($hostel->price_per_month)
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <span class="text-gray-700 text-sm">मासिक शुल्क</span>
                                <span class="font-bold text-green-700">रु {{ number_format($hostel->price_per_month) }}</span>
                            </div>
                        @endif
                        
                        @if($hostel->security_deposit)
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-gray-700 text-sm">सुरक्षा जमानी</span>
                                <span class="font-bold text-blue-700">रु {{ number_format($hostel->security_deposit) }}</span>
                            </div>
                        @endif
                        
                        <!-- ✅ FIXED: "सिधै बुक गर्नुहोस्" button MOVED here (Gallery section बाट सारिएको) -->
                        <div class="mt-6">
                            <a href="{{ route('hostels.book', $hostel->slug) }}"
                               class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-700 hover:to-emerald-700 transition-all transform hover:-translate-y-0.5 hover:shadow-xl"
                               aria-label="सिधै बुक गर्नुहोस्">
                                <i class="fas fa-calendar-check mr-2"></i>
                                सिधै बुक गर्नुहोस्
                            </a>
                            
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="#contact-form" 
                               class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center gap-1 text-sm"
                               aria-label="विस्तृत मूल्य जानकारीको लागि सम्पर्क गर्नुहोस्">
                                <i class="fas fa-info-circle"></i>
                                विस्तृत मूल्य जानकारीको लागि सम्पर्क गर्नुहोस्
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section - WITH PROPER SPACING -->
        <div class="modern-card mt-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 pb-4 border-b">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-star text-yellow-600"></i>
                    विद्यार्थी समीक्षाहरू
                </h2>
                <div class="flex items-center gap-4">
                    @if($avgRating > 0)
                    <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xl">{{ number_format($avgRating, 1) }}</span>
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="bg-purple-100 text-purple-800 px-4 py-2 rounded-full">
                        <span class="font-semibold">{{ $reviewCount ?? 0 }} समीक्षाहरू</span>
                    </div>
                </div>
            </div>

            @if($reviewCount > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    @foreach(($reviews ?? collect())->take(4) as $review)
                    <div class="modern-card hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($review->student->user->name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                            @endfor
                                        </div>
                                        <span class="text-gray-500 text-xs">{{ $review->created_at->format('Y-m-d') ?? '२०२५-०१-०१' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-gray-700 mb-4 text-sm leading-relaxed">{{ $review->comment ?? 'राम्रो सेवा' }}</p>
                    </div>
                    @endforeach
                </div>

                @if($reviewCount > 4)
                    <div class="text-center mt-8">
                        <a href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity full-gallery-btn"
                           aria-label="सबै समीक्षाहरू हेर्नुहोस्">
                            <i class="fas fa-comments mr-2"></i>
                            सबै समीक्षाहरू हेर्नुहोस् ({{ $reviewCount }})
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comment-slash text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-600 mb-2">अहिलेसम्म कुनै समीक्षा छैन</h3>
                    <p class="text-gray-500 mb-4 text-sm">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
                    <button class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity text-sm book-now-button"
                            aria-label="पहिलो समीक्षा लेख्नुहोस्">
                        <i class="fas fa-pen mr-2"></i>पहिलो समीक्षा लेख्नुहोस्
                    </button>
                </div>
            @endif
        </div>
    </main>
</div>

<!-- Floating Actions -->
<div class="modern-floating-actions">
    @if($hostel->whatsapp_number)
        <a href="https://wa.me/{{ $hostel->whatsapp_number }}" 
           target="_blank" 
           class="floating-btn whatsapp"
           data-action="whatsapp"
           data-href="https://wa.me/{{ $hostel->whatsapp_number }}"
           aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endif
    
    @if($hostel->contact_phone)
        <a href="tel:{{ $hostel->contact_phone }}" 
           class="floating-btn phone"
           data-action="phone"
           data-href="tel:{{ $hostel->contact_phone }}"
           aria-label="फोन गर्नुहोस्">
            <i class="fas fa-phone"></i>
        </a>
    @endif
    
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="floating-btn scroll-top"
            data-action="scroll-top"
            aria-label="माथि जानुहोस्">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>

{{-- ✅ FIXED: JavaScript configuration moved outside @push --}}
<script>
    // Pass configuration to external JS
    window.MODERN_THEME_CONFIG = {
        defaultImage: '{{ asset("images/default-room.png") }}',
        hostelSlug: '{{ $hostel->slug }}',
        galleryRoute: '{{ route("hostels.full.gallery", $hostel->slug) }}',
        bookRoute: '{{ route("hostels.book", $hostel->slug) }}'
    };
</script>

<!-- ✅ ENHANCED: Vertical Gallery Auto-Scroll Functionality -->
<script>
    let autoScrollInterval = null;
    let isAutoScrollEnabled = true;

    function startAutoScroll() {
        const gallery = document.getElementById('verticalGallery');
        if (!gallery || !isAutoScrollEnabled) return;
        
        autoScrollInterval = setInterval(() => {
            // Check if we've reached the bottom
            const isAtBottom = gallery.scrollHeight - gallery.scrollTop <= gallery.clientHeight + 10;
            
            if (isAtBottom) {
                // Scroll back to top
                gallery.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                // Scroll down
                gallery.scrollBy({ top: 300, behavior: 'smooth' });
            }
        }, 4000); // Scroll every 4 seconds
    }

    function stopAutoScroll() {
        if (autoScrollInterval) {
            clearInterval(autoScrollInterval);
            autoScrollInterval = null;
        }
    }

    function toggleAutoScroll() {
        isAutoScrollEnabled = !isAutoScrollEnabled;
        const toggleBtn = document.getElementById('toggleAutoScroll');
        const icon = document.getElementById('autoScrollIcon');
        const text = document.getElementById('autoScrollText');
        
        if (isAutoScrollEnabled) {
            icon.className = 'fas fa-play';
            text.textContent = 'स्वत: स्लाइड';
            toggleBtn.classList.remove('bg-red-100', 'text-red-700');
            toggleBtn.classList.add('bg-blue-100', 'text-blue-700');
            startAutoScroll();
        } else {
            icon.className = 'fas fa-pause';
            text.textContent = 'रोकिएको';
            toggleBtn.classList.remove('bg-blue-100', 'text-blue-700');
            toggleBtn.classList.add('bg-red-100', 'text-red-700');
            stopAutoScroll();
        }
    }

    function scrollGallery(direction) {
        const gallery = document.getElementById('verticalGallery');
        if (!gallery) return;
        
        const scrollAmount = 400;
        
        if (direction === 'up') {
            gallery.scrollBy({ top: -scrollAmount, behavior: 'smooth' });
        } else {
            gallery.scrollBy({ top: scrollAmount, behavior: 'smooth' });
        }
    }

    // Initialize auto-scroll when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Start auto-scroll after 2 seconds
        setTimeout(() => {
            if (document.getElementById('verticalGallery')) {
                startAutoScroll();
            }
        }, 2000);
        
        // Pause auto-scroll when user interacts
        const gallery = document.getElementById('verticalGallery');
        if (gallery) {
            gallery.addEventListener('mouseenter', stopAutoScroll);
            gallery.addEventListener('mouseleave', () => {
                if (isAutoScrollEnabled) {
                    startAutoScroll();
                }
            });
            
            gallery.addEventListener('touchstart', stopAutoScroll);
            gallery.addEventListener('touchend', () => {
                setTimeout(() => {
                    if (isAutoScrollEnabled) {
                        startAutoScroll();
                    }
                }, 3000);
            });
        }
    });

    // Lightbox function
    window.openModernLightbox = function(element, index) {
        // Your lightbox implementation here
        console.log('Opening gallery item:', index);
        // You can integrate with any lightbox library like Fancybox, Lightbox2, etc.
    };
</script>

<!-- Load modern theme JavaScript -->
<script defer src="{{ asset('js/themes/modern.js') }}"></script>
@endsection