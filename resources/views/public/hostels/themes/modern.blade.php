@extends('layouts.frontend')

@section('page-title', $hostel->name . ' - Modern Hostel')
@section('page-description', $hostel->description ? \Illuminate\Support\Str::limit($hostel->description, 160) : 'आधुनिक होस्टल अनुभव')

@push('styles')
<style>
/* ===========================================
   MODERN THEME - SCOPED STYLES ONLY FOR THIS PAGE
   यसले अरु pages लाई असर गर्दैन
   =========================================== */

/* 🎯 MODERN THEME CONTAINER */
.modern-theme-page {
    /* Theme variables */
    --primary-color: {{ $hostel->theme_color ?? '#3b82f6' }};
    --primary-gradient: linear-gradient(135deg, var(--primary-color) 0%, #7c3aed 100%);
    --sidebar-width: 320px;
    --card-radius: 16px;
}

/* 🎯 LOGO FIX - HEADER & FOOTER */
.modern-theme-page header .logo-image img {
    height: 40px !important;
    width: auto !important;
}

.modern-theme-page .logo-text h1 {
    font-size: 1.1rem !important;
}

.modern-theme-page .logo-text span.nepali {
    font-size: 0.6rem !important;
}

/* ===========================================
   🚨 HERO SECTION FIX - COMPULSORY
   =========================================== */

/* 1. REMOVE ALL TOP SPACE */
.modern-theme-page .hero-section {
    padding-top: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
    left: 0 !important;
    right: 0 !important;
}

/* 2. FORCE HERO TO LEFT SIDE */
.modern-theme-page .hero-section .modern-container {
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}

/* 3. HERO HEADER FIX - NO SPACE LEFT/RIGHT */
.modern-theme-page .modern-hero-header {
    padding: 12px 20px !important;
    margin: 0 !important;
    gap: 15px !important;
    align-items: center !important;
    width: 100% !important;
    max-width: 100% !important;
}

/* 4. LOGO SECTION FIX */
.modern-theme-page .modern-hero-header > .flex.items-start {
    margin-left: 0 !important;
    padding-left: 0 !important;
    flex: 1 !important;
    min-width: 0 !important;
}

/* 5. REMOVE ANY LEFT MARGIN FROM LOGO CONTAINER */
.modern-theme-page .modern-logo-container {
    margin-left: 0 !important;
    margin-right: 10px !important;
}

/* 6. HOSTEL INFO FIX */
.modern-theme-page .modern-hero-header .flex-1 {
    margin-left: 0 !important;
    padding-left: 0 !important;
}

/* 7. SOCIAL & PHONE FIX */
.modern-theme-page .modern-hero-header > .flex.flex-col.items-end {
    margin-right: 0 !important;
    padding-right: 0 !important;
    flex-shrink: 0 !important;
}

/* 8. STATS GRID FIX */
.modern-theme-page .modern-stats-grid {
    padding: 0 20px !important;
    margin-top: 15px !important;
    margin-bottom: 15px !important;
}

/* 9. HEADER संग DIRECT जोडिने */
.modern-theme-page {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* 10. Remove any parent margins */
.modern-theme-page .modern-theme {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* 11. MOBILE FIX */
@media (max-width: 768px) {
    .modern-theme-page .hero-section {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .modern-theme-page .modern-hero-header {
        padding: 10px 15px !important;
        gap: 8px !important;
    }
    
    .modern-theme-page .modern-stats-grid {
        padding: 0 15px !important;
        margin-top: 10px !important;
    }
}

/* 🎯 LAYOUT CONTAINER */
.modern-theme-page .modern-container {
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 0 20px !important;
}

@media (min-width: 768px) {
    .modern-theme-page .modern-container {
        padding: 0 30px !important;
    }
}

@media (min-width: 1024px) {
    .modern-theme-page .modern-container {
        padding: 0 40px !important;
    }
}

/* 🎯 3-COLUMN GRID SYSTEM */
.modern-theme-page .modern-layout-grid {
    display: grid !important;
    grid-template-columns: 1.8fr 1.5fr 1.2fr !important;
    gap: 24px !important;
    align-items: start !important;
}

/* Tablet Layout */
@media (max-width: 1024px) and (min-width: 768px) {
    .modern-theme-page .modern-layout-grid {
        grid-template-columns: 1.5fr 1.2fr !important;
        gap: 20px !important;
    }
    
    .modern-theme-page .modern-layout-grid > *:nth-child(2) {
        grid-column: span 2 !important;
    }
}

/* Mobile Layout */
@media (max-width: 768px) {
    .modern-theme-page .modern-layout-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
}

/* 🎯 HERO SECTION */
.modern-theme-page .hero-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, #7c3aed 100%);
    color: white;
    padding: 2rem 0 !important;
    position: relative;
    overflow: hidden;
}

.modern-theme-page .hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.3;
}

/* 🎯 LOGO CONTAINER IN HERO */
.modern-theme-page .modern-logo-container {
    width: 80px !important;
    height: 80px !important;
    border-radius: 16px !important;
    overflow: hidden !important;
    border: 4px solid white !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
    background: white !important;
    flex-shrink: 0 !important;
}

.modern-theme-page .modern-logo-container img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}

/* 🎯 CARD SYSTEM */
.modern-theme-page .modern-card {
    background: white !important;
    border-radius: var(--card-radius) !important;
    padding: 24px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
    border: 1px solid #f1f5f9 !important;
    margin-bottom: 24px !important;
    transition: all 0.3s ease !important;
}

.modern-theme-page .modern-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
}

.modern-theme-page .modern-card-header {
    padding-bottom: 16px !important;
    margin-bottom: 20px !important;
    border-bottom: 2px solid #f1f5f9 !important;
}

.modern-theme-page .modern-card-title {
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    color: #1f2937 !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}

/* 🎯 GALLERY */
.modern-theme-page .modern-gallery-container {
    height: 500px !important;
    min-height: 400px !important;
    max-height: 600px !important;
    display: flex !important;
    flex-direction: column !important;
}

.modern-theme-page .modern-gallery-scroll {
    height: calc(100% - 60px) !important;
    overflow-y: auto !important;
    padding-right: 8px !important;
}

/* 🎯 STATS GRID */
.modern-theme-page .modern-stats-grid {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 12px !important;
    margin-top: 20px !important;
}

.modern-theme-page .modern-stat-card {
    background: rgba(255, 255, 255, 0.15) !important;
    backdrop-filter: blur(10px) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 12px !important;
    padding: 16px 12px !important;
    text-align: center !important;
    color: white !important;
}

/* 🎯 FACILITIES GRID */
.modern-theme-page .modern-facilities-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)) !important;
    gap: 12px !important;
}

.modern-theme-page .modern-facility-item {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 12px 16px !important;
    background: #f8fafc !important;
    border-radius: 12px !important;
    border: 1px solid #e2e8f0 !important;
    transition: all 0.3s ease !important;
}

.modern-theme-page .modern-facility-item:hover {
    background: #f0f9ff !important;
    border-color: var(--primary-color) !important;
    transform: translateY(-2px) !important;
}

/* 🎯 CONTACT ITEMS */
.modern-theme-page .modern-contact-item {
    display: flex !important;
    align-items: flex-start !important;
    gap: 12px !important;
    padding: 14px !important;
    background: #f8fafc !important;
    border-radius: 12px !important;
    margin-bottom: 12px !important;
    border-left: 4px solid var(--primary-color) !important;
}

/* 🎯 BUTTONS */
.modern-theme-page .modern-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    padding: 12px 24px !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    border: none !important;
    cursor: pointer !important;
}

.modern-theme-page .modern-btn-primary {
    background: var(--primary-gradient) !important;
    color: white !important;
}

.modern-theme-page .modern-btn-primary:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3) !important;
}

/* 🎯 STICKY SIDEBAR (DESKTOP ONLY) */
@media (min-width: 1024px) {
    .modern-theme-page .modern-sticky-sidebar {
        position: sticky !important;
        top: 100px !important;
        height: fit-content !important;
        max-height: calc(100vh - 120px) !important;
        overflow-y: auto !important;
        z-index: 20 !important;
    }
}

/* 🎯 MOBILE RESPONSIVE FIXES */
@media (max-width: 768px) {
    .modern-theme-page .modern-stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    .modern-theme-page .modern-logo-container {
        width: 60px !important;
        height: 60px !important;
    }
    
    .modern-theme-page .modern-card {
        padding: 16px !important;
        margin-bottom: 16px !important;
    }
    
    .modern-theme-page .modern-card-title {
        font-size: 1.1rem !important;
    }
    
    .modern-theme-page .modern-gallery-container {
        height: 400px !important;
    }
}

/* 🎯 REVIEWS GRID */
.modern-theme-page .modern-reviews-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)) !important;
    gap: 20px !important;
    margin-top: 24px !important;
}

@media (max-width: 768px) {
    .modern-theme-page .modern-reviews-grid {
        grid-template-columns: 1fr !important;
    }
}

/* 🎯 HERO HEADER LAYOUT */
.modern-theme-page .modern-hero-header {
    display: flex !important;
    align-items: flex-start !important;
    justify-content: space-between !important;
    gap: 24px !important;
    margin-bottom: 24px !important;
}

@media (max-width: 768px) {
    .modern-theme-page .modern-hero-header {
        flex-direction: column !important;
        align-items: stretch !important;
    }
}

/* 🎯 SOCIAL ICONS */
.modern-theme-page .modern-social-icon {
    width: 40px !important;
    height: 40px !important;
    border-radius: 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    backdrop-filter: blur(10px) !important;
}

.modern-theme-page .modern-social-icon:hover {
    transform: translateY(-3px) !important;
    border-color: rgba(255, 255, 255, 0.4) !important;
}

/* 🎯 SCROLLBAR STYLING */
.modern-theme-page .modern-sticky-sidebar::-webkit-scrollbar,
.modern-theme-page .modern-gallery-scroll::-webkit-scrollbar {
    width: 4px !important;
}

.modern-theme-page .modern-sticky-sidebar::-webkit-scrollbar-thumb,
.modern-theme-page .modern-gallery-scroll::-webkit-scrollbar-thumb {
    background: var(--primary-color) !important;
    border-radius: 4px !important;
}

/* 🎯 PRINT STYLES */
@media print {
    .modern-theme-page .modern-no-print {
        display: none !important;
    }
}
</style>
@endpush

@section('content')
<body class="modern-theme-page">
<div class="modern-theme">
    <!-- ===== HERO SECTION ===== -->
    <section class="hero-section" style="padding-top: 0 !important; margin-top: 0 !important; width: 100% !important;">
        <div class="modern-container" style="padding-left: 0 !important; padding-right: 0 !important; margin: 0 !important; width: 100% !important;">
            <!-- Preview Alert -->
            @if(isset($preview) && $preview)
            <div class="modern-card mb-6 border-l-4 border-yellow-400 bg-white/10 backdrop-blur-sm" style="margin-left: 20px; margin-right: 20px;">
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
                       class="modern-btn modern-btn-primary nepali">
                        <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                    </a>
                </div>
            </div>
            @endif

            <!-- Hero Header -->
            <div class="modern-hero-header" style="padding: 12px 20px !important; margin: 0 !important; width: 100% !important;">
                <!-- Logo & Basic Info - LEFT SIDE -->
                <div class="flex items-start gap-4 md:gap-6 flex-1 min-w-0">
                    <!-- Logo Container -->
                    <div class="modern-logo-container flex-shrink-0">
                        @if($logo)
                            <img src="{{ $logo }}" 
                                 alt="{{ $hostel->name }}"
                                 onerror="this.src='{{ asset('images/default-hostel.png') }}'">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <i class="fas fa-building text-white text-xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Hostel Info -->
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl md:text-3xl font-bold nepali mb-2">{{ $hostel->name }}</h1>
                        
                        <div class="flex flex-wrap gap-2 mb-3">
                            <!-- Location -->
                            <div class="inline-flex items-center gap-1 bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                                <span class="nepali font-medium text-xs">{{ $hostel->city ?? 'काठमाडौं' }}</span>
                            </div>
                            
                            <!-- Available Rooms -->
                            @if($hostel->available_rooms > 0)
                            <div class="inline-flex items-center gap-1 bg-green-500/90 px-3 py-1 rounded-full">
                                <i class="fas fa-bed text-xs"></i>
                                <span class="nepali font-medium text-xs">{{ $hostel->available_rooms }} कोठा उपलब्ध</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Social & Contact - RIGHT SIDE -->
                <div class="flex flex-col items-end gap-3 flex-shrink-0">
                    <!-- Social Media -->
                    <div class="text-right">
                        <p class="text-white/90 nepali text-xs mb-2">हामीलाई फलो गर्नुहोस्</p>
                        <div class="flex gap-1">
                            @if($hostel->facebook_url)
                            <a href="{{ $hostel->facebook_url }}" target="_blank" 
                               class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/10 hover:bg-white/20 transition-colors">
                                <i class="fab fa-facebook-f text-white text-sm"></i>
                            </a>
                            @endif
                            
                            @if($hostel->instagram_url)
                            <a href="{{ $hostel->instagram_url }}" target="_blank" 
                               class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/10 hover:bg-white/20 transition-colors">
                                <i class="fab fa-instagram text-white text-sm"></i>
                            </a>
                            @endif
                            
                            @if($hostel->whatsapp_number)
                            <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" 
                               class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/10 hover:bg-white/20 transition-colors">
                                <i class="fab fa-whatsapp text-white text-sm"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Phone CTA -->
                    @if($hostel->contact_phone)
                    <div class="text-center">
                        <a href="tel:{{ $hostel->contact_phone }}" 
                           class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl text-white font-semibold transition-all duration-300 backdrop-blur-sm text-sm">
                            <i class="fas fa-phone text-green-300 text-sm"></i>
                            <div class="text-left">
                                <div class="nepali text-xs">फोन गर्नुहोस्</div>
                                <div class="font-bold text-sm">{{ $hostel->contact_phone }}</div>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="modern-stats-grid">
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1">{{ $hostel->total_rooms ?? 0 }}</div>
                    <div class="nepali opacity-90 text-sm">कुल कोठा</div>
                </div>
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1">{{ $hostel->available_rooms ?? 0 }}</div>
                    <div class="nepali opacity-90 text-sm">उपलब्ध कोठा</div>
                </div>
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1">{{ $studentCount }}</div>
                    <div class="nepali opacity-90 text-sm">विद्यार्थी</div>
                </div>
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1">{{ $reviewCount }}</div>
                    <div class="nepali opacity-90 text-sm">समीक्षाहरू</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="modern-container py-8">
        <div class="modern-layout-grid">
            <!-- LEFT COLUMN - Main Content -->
            <div class="space-y-8">
                <!-- About Section -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title nepali">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            हाम्रो बारेमा
                        </h2>
                        <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-history"></i>
                            <span class="nepali">{{ $hostel->established_year ?? '२०२०' }} देखि</span>
                        </div>
                    </div>
                    
                    @if($hostel->description)
                        <div class="text-gray-700 leading-relaxed nepali whitespace-pre-line text-base">
                            {{ $hostel->description }}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-file-alt text-4xl mb-3 opacity-30"></i>
                            <p class="nepali italic text-sm">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
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
                                <div class="font-bold text-gray-900 nepali text-sm">{{ $info['value'] }}</div>
                                <div class="text-gray-600 nepali text-xs mt-1">{{ $info['label'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title nepali">
                            <i class="fas fa-envelope text-blue-600"></i>
                            सम्पर्क गर्नुहोस्
                        </h2>
                    </div>
                    @include('public.hostels.partials.contact-form')
                </div>

                <!-- Map & Trust -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Map Card -->
                    <div class="modern-card">
                        <h3 class="text-lg font-bold text-gray-900 nepali mb-4 flex items-center gap-2">
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
                                        referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-map text-4xl text-gray-300 mb-3"></i>
                                        <p class="nepali text-gray-500">म्याप उपलब्ध छैन</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($hostel->address)
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt text-blue-600 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900 nepali text-sm mb-1">ठेगाना</h4>
                                    <p class="text-gray-700 nepali text-sm">{{ $hostel->address }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Trust Badges -->
                    <div class="modern-card">
                        <h3 class="text-lg font-bold text-gray-900 nepali mb-4">किन हामीलाई छान्ने?</h3>
                        <div class="space-y-4">
                            @foreach([
                                ['icon' => 'shield-check', 'color' => 'blue', 'title' => 'सत्यापित सुरक्षा', 'desc' => 'CCTV र २४/७ सुरक्षा गार्ड'],
                                ['icon' => 'award', 'color' => 'green', 'title' => 'प्रमाणित गुणस्तर', 'desc' => 'ISO मानक अनुरूप सेवा'],
                                ['icon' => 'star', 'color' => 'yellow', 'title' => 'उत्कृष्ट समीक्षा', 'desc' => '४.५+ औसत रेटिंग'],
                                ['icon' => 'clock', 'color' => 'purple', 'title' => '२४/७ समर्थन', 'desc' => 'सधैं उपलब्ध सम्पर्क']
                            ] as $badge)
                            <div class="flex items-start gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="w-10 h-10 bg-{{ $badge['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink:0">
                                    <i class="fas fa-{{ $badge['icon'] }} text-{{ $badge['color'] }}-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 nepali text-sm">{{ $badge['title'] }}</h4>
                                    <p class="text-gray-600 nepali text-xs mt-1">{{ $badge['desc'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- MIDDLE COLUMN - Gallery -->
            <div class="space-y-8">
                <!-- Gallery -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h2 class="modern-card-title nepali">
                            <i class="fas fa-images text-purple-600"></i>
                            हाम्रो ग्यालरी
                        </h2>
                        <a href="#" class="text-purple-600 hover:text-purple-800 text-sm font-medium nepali">
                            <i class="fas fa-external-link-alt mr-1"></i> सबै हेर्नुहोस्
                        </a>
                    </div>
                    
                    <div class="modern-gallery-container">
                        <p class="text-gray-600 text-center nepali text-base mb-4">
                            हाम्रो होस्टलको वातावरण र सुविधाहरूको झलक
                        </p>
                        
                        <div class="modern-gallery-scroll">
                            @php
                                $galleries = $hostel->activeGalleries ?? collect();
                            @endphp
                            
                            @if($galleries->count() > 0)
                                <div class="space-y-4">
                                    @foreach($galleries->take(4) as $gallery)
                                    <div class="group cursor-pointer">
                                        @if($gallery->media_type === 'image')
                                            <div class="relative overflow-hidden rounded-xl">
                                                <img src="{{ $gallery->thumbnail_url }}" 
                                                     alt="{{ $gallery->title }}"
                                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300"></div>
                                            </div>
                                        @elseif($gallery->media_type === 'external_video')
                                            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 h-48 flex items-center justify-center">
                                                <i class="fab fa-youtube text-white text-4xl"></i>
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300"></div>
                                            </div>
                                        @else
                                            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 h-48 flex items-center justify-center">
                                                <i class="fas fa-video text-white text-4xl"></i>
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300"></div>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <h4 class="font-semibold text-gray-900 nepali text-sm mb-1">{{ $gallery->title }}</h4>
                                            @if($gallery->description)
                                                <p class="text-gray-600 nepali text-xs">{{ Str::limit($gallery->description, 60) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Placeholder Gallery -->
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach([
                                        ['icon' => 'bed', 'label' => 'कोठा', 'color' => 'blue'],
                                        ['icon' => 'utensils', 'label' => 'खाना', 'color' => 'green'],
                                        ['icon' => 'wifi', 'label' => 'WiFi', 'color' => 'purple'],
                                        ['icon' => 'book', 'label' => 'अध्ययन', 'color' => 'orange']
                                    ] as $item)
                                    <div class="aspect-square rounded-xl bg-{{ $item['color'] }}-100 flex flex-col items-center justify-center p-4">
                                        <i class="fas fa-{{ $item['icon'] }} text-{{ $item['color'] }}-600 text-2xl mb-2"></i>
                                        <span class="nepali text-gray-700 text-sm">{{ $item['label'] }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <!-- View All Button -->
                        <div class="text-center mt-6 pt-6 border-t">
                            <a href="#" class="modern-btn modern-btn-primary nepali inline-flex items-center">
                                <i class="fas fa-images mr-2"></i>
                                पूरै ग्यालरी हेर्नुहोस्
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN - Sticky Sidebar -->
            <div class="modern-sticky-sidebar space-y-6">
                <!-- Contact Information -->
                <div class="modern-card">
                    <h3 class="text-lg font-bold text-gray-900 nepali mb-4 flex items-center gap-2">
                        <i class="fas fa-address-card text-blue-600"></i>
                        सम्पर्क जानकारी
                    </h3>
                    <div class="space-y-3">
                        @if($hostel->contact_person)
                            <div class="modern-contact-item">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink:0">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500 nepali mb-1">सम्पर्क व्यक्ति</div>
                                    <div class="font-medium text-gray-900 nepali">{{ $hostel->contact_person }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->contact_phone)
                            <div class="modern-contact-item">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink:0">
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500 nepali mb-1">फोन नम्बर</div>
                                    <a href="tel:{{ $hostel->contact_phone }}" 
                                       class="font-medium text-gray-900 hover:text-blue-600 transition-colors nepali block">
                                        {{ $hostel->contact_phone }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->contact_email)
                            <div class="modern-contact-item">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink:0">
                                    <i class="fas fa-envelope text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500 nepali mb-1">इमेल ठेगाना</div>
                                    <a href="mailto:{{ $hostel->contact_email }}" 
                                       class="font-medium text-gray-900 hover:text-blue-600 transition-colors text-sm break-all">
                                        {{ $hostel->contact_email }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->address)
                            <div class="modern-contact-item">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink:0">
                                    <i class="fas fa-map-marker-alt text-orange-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500 nepali mb-1">ठेगाना</div>
                                    <div class="font-medium text-gray-900 nepali text-sm leading-relaxed">
                                        {{ $hostel->address }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-bold text-gray-900 nepali mb-3">द्रुत कार्यहरू</h4>
                        <div class="space-y-3">
                            <a href="#contact-form" 
                               class="modern-btn modern-btn-primary w-full justify-center nepali">
                                <i class="fas fa-envelope mr-2"></i>
                                सन्देश पठाउनुहोस्
                            </a>
                            
                            <a href="{{ route('hostels.index') }}" 
                               class="modern-btn w-full justify-center bg-gray-700 text-white hover:bg-gray-800 nepali">
                                <i class="fas fa-building mr-2"></i>
                                अन्य होस्टलहरू
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Availability Card -->
                <div class="modern-card {{ $hostel->available_rooms > 0 ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-gray-600 to-gray-700' }} text-white text-center">
                    <div class="p-5">
                        <i class="fas fa-bed text-3xl mb-3"></i>
                        <h4 class="text-xl font-bold nepali mb-2">
                            @if($hostel->available_rooms > 0)
                                कोठा उपलब्ध!
                            @else
                                सबै कोठा भरिएको
                            @endif
                        </h4>
                        
                        @if($hostel->available_rooms > 0)
                            <p class="nepali opacity-90 mb-4 text-sm">अहिले {{ $hostel->available_rooms }} कोठा खाली छन्</p>
                            <a href="#contact-form" 
                               class="inline-block bg-white text-green-600 hover:bg-gray-100 py-2 px-6 rounded-lg font-bold nepali transition-all duration-300 text-sm">
                                <i class="fas fa-calendar-check mr-2"></i>
                                अहिले बुक गर्नुहोस्
                            </a>
                        @else
                            <p class="nepali text-sm opacity-90 mb-4">अहिले कुनै कोठा उपलब्ध छैन</p>
                            <button class="modern-btn bg-white/20 hover:bg-white/30 text-white border border-white/30 text-sm">
                                <i class="fas fa-bell mr-2"></i>
                                नोटिफिकेशन दर्ता
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Facilities -->
                @if(!empty($facilities) && count($facilities) > 0)
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h3 class="modern-card-title nepali">
                                <i class="fas fa-list-check text-blue-600"></i>
                                हाम्रा सुविधाहरू
                            </h3>
                        </div>
                        <div class="modern-facilities-grid">
                            @foreach($facilities as $facility)
                                @php
                                    $cleanFacility = trim($facility, ' ,"\'[]');
                                @endphp
                                @if(!empty($cleanFacility))
                                    <div class="modern-facility-item">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink:0">
                                            <i class="fas fa-check text-blue-600 text-sm"></i>
                                        </div>
                                        <span class="nepali font-medium text-gray-800 text-sm">
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
                    <h3 class="text-lg font-bold text-gray-900 nepali mb-4 flex items-center gap-2">
                        <i class="fas fa-tag text-green-600"></i>
                        मूल्य सीमा
                    </h3>
                    <div class="space-y-3">
                        @if($hostel->price_per_month)
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <span class="nepali text-gray-700 text-sm">मासिक शुल्क</span>
                                <span class="font-bold text-green-700">रु {{ number_format($hostel->price_per_month) }}</span>
                            </div>
                        @endif
                        
                        @if($hostel->security_deposit)
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="nepali text-gray-700 text-sm">सुरक्षा जमानी</span>
                                <span class="font-bold text-blue-700">रु {{ number_format($hostel->security_deposit) }}</span>
                            </div>
                        @endif
                        
                        <div class="text-center mt-4">
                            <a href="#contact-form" 
                               class="text-blue-600 hover:text-blue-800 font-medium nepali inline-flex items-center gap-1 text-sm">
                                <i class="fas fa-info-circle"></i>
                                विस्तृत मूल्य जानकारीको लागि सम्पर्क गर्नुहोस्
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="modern-card mt-8">
            <div class="modern-card-header">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h2 class="modern-card-title nepali">
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
                            <span class="nepali font-semibold">{{ $reviewCount }} समीक्षाहरू</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($reviewCount > 0)
                <div class="modern-reviews-grid">
                    @foreach($reviews->take(4) as $review)
                    <div class="modern-card hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($review->student->user->name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 nepali">{{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                            @endfor
                                        </div>
                                        <span class="text-gray-500 text-xs">{{ $review->created_at->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-gray-700 nepali mb-4 text-sm leading-relaxed">{{ $review->comment }}</p>
                        
                        @if($review->reply)
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded-lg mt-4">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-reply text-blue-500 mt-1"></i>
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

                @if($reviewCount > 4)
                    <div class="text-center mt-8">
                        <a href="#" class="modern-btn modern-btn-primary nepali">
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
                    <h3 class="text-lg font-bold text-gray-600 nepali mb-2">अहिलेसम्म कुनै समीक्षा छैन</h3>
                    <p class="text-gray-500 nepali mb-4 text-sm">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
                    <button class="modern-btn modern-btn-primary nepali text-sm">
                        <i class="fas fa-pen mr-2"></i>पहिलो समीक्षा लेख्नुहोस्
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Floating Actions -->
<div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3">
    @if($hostel->whatsapp_number)
        <a href="https://wa.me/{{ $hostel->whatsapp_number }}" 
           target="_blank" 
           class="w-12 h-12 bg-green-600 hover:bg-green-700 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300"
           aria-label="WhatsApp">
            <i class="fab fa-whatsapp text-lg"></i>
        </a>
    @endif
    
    @if($hostel->contact_phone)
        <a href="tel:{{ $hostel->contact_phone }}" 
           class="w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300"
           aria-label="फोन गर्नुहोस्">
            <i class="fas fa-phone text-lg"></i>
        </a>
    @endif
    
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="w-12 h-12 bg-gray-800 hover:bg-gray-900 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300"
            aria-label="माथि जानुहोस्">
        <i class="fas fa-arrow-up text-lg"></i>
    </button>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll gallery
    const galleryScroll = document.querySelector('.modern-gallery-scroll');
    if (galleryScroll) {
        let scrollInterval;
        let scrollDirection = 1;
        
        function startAutoScroll() {
            scrollInterval = setInterval(() => {
                if (galleryScroll.scrollTop >= (galleryScroll.scrollHeight - galleryScroll.clientHeight - 10)) {
                    scrollDirection = -1;
                } else if (galleryScroll.scrollTop <= 10) {
                    scrollDirection = 1;
                }
                
                galleryScroll.scrollTop += scrollDirection;
            }, 30);
        }
        
        function stopAutoScroll() {
            clearInterval(scrollInterval);
        }
        
        startAutoScroll();
        
        galleryScroll.addEventListener('mouseenter', stopAutoScroll);
        galleryScroll.addEventListener('mouseleave', startAutoScroll);
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
</body>
@endsection