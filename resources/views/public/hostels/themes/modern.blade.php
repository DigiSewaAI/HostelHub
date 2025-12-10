@php 
    use Illuminate\Support\Facades\Storage;
    
    $theme = 'modern';
    $themeColor = $hostel->theme_color ?? '#3b82f6';
@endphp

@extends('layouts.public')

@section('page-title', ($hostel->name ?? 'Hostel') . ' - Modern Theme')
@section('page-description', Str::limit($hostel->description ?? 'आधुनिक होस्टल अनुभव', 160))

@push('head')
    <meta name="theme-color" content="{{ $themeColor }}">
@endpush

@push('styles')
<style>
    /* Modern Theme Variables */
    .modern-theme {
        --primary: {{ $themeColor }};
        --primary-gradient: linear-gradient(135deg, {{ $themeColor }} 0%, #7c3aed 100%);
        --sidebar-width: 340px;
        --card-radius: 16px;
        --transition: cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Modern Theme Styles */
    .modern-container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 20px !important;
        width: 100% !important;
    }
    
    @media (min-width: 768px) {
        .modern-container {
            padding: 0 30px !important;
        }
    }
    
    @media (min-width: 1024px) {
        .modern-container {
            padding: 0 40px !important;
        }
    }
    
    /* Hero Section */
    .modern-hero {
        background: linear-gradient(135deg, {{ $themeColor }} 0%, #7c3aed 100%);
        color: white;
        padding: 2rem 0;
        width: 100%;
    }
    
    /* Card Styles */
    .modern-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(229, 231, 235, 0.8);
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }
    
    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }
    
    /* Stats Grid */
    .modern-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-top: 24px;
    }
    
    @media (max-width: 768px) {
        .modern-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }
    
    .modern-stat-card {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        padding: 20px 16px;
        text-align: center;
        color: white;
        transition: all 0.3s ease;
    }
    
    /* Layout Grid */
    .modern-layout-grid {
        display: grid;
        grid-template-columns: 1.8fr 1.5fr 1.2fr;
        gap: 32px;
        align-items: start;
    }
    
    @media (max-width: 1024px) {
        .modern-layout-grid {
            grid-template-columns: 1.5fr 1.2fr;
        }
        
        .modern-layout-grid > *:nth-child(2) {
            grid-column: span 2;
        }
    }
    
    @media (max-width: 768px) {
        .modern-layout-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Floating Actions */
    .modern-floating-actions {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .floating-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.16);
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    
    .floating-btn.whatsapp {
        background: linear-gradient(135deg, #25d366, #128c7e);
    }
    
    .floating-btn.phone {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }
    
    .floating-btn.scroll-top {
        background: linear-gradient(135deg, #6b7280, #4b5563);
    }
    
    .floating-btn:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    }
    
    /* 🚨 UPDATED: Vertical Room Gallery Slider */
    .room-gallery-vertical {
        display: flex;
        flex-direction: column;
        gap: 16px;
        max-height: 600px;
        overflow-y: auto;
        padding-right: 12px;
        margin-bottom: 20px;
        scrollbar-width: thin;
        scrollbar-color: var(--primary) #f1f5f9;
        animation: slideUp 0.5s ease-out;
    }
    
    .room-gallery-vertical::-webkit-scrollbar {
        width: 6px;
    }
    
    .room-gallery-vertical::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .room-gallery-vertical::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, var(--primary), #7c3aed);
        border-radius: 10px;
    }
    
    .room-gallery-vertical::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #1d4ed8, #6d28d9);
    }
    
    .room-gallery-item {
        background: white;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease-out;
        animation-fill-mode: both;
        opacity: 0;
    }
    
    /* Staggered animation for each item */
        .room-gallery-item:nth-child(1)  { animation-delay: 0s; }
        .room-gallery-item:nth-child(2)  { animation-delay: 0.1s; }
        .room-gallery-item:nth-child(3)  { animation-delay: 0.2s; }
        .room-gallery-item:nth-child(4)  { animation-delay: 0.3s; }
        .room-gallery-item:nth-child(5)  { animation-delay: 0.4s; }
        .room-gallery-item:nth-child(6)  { animation-delay: 0.5s; }
        .room-gallery-item:nth-child(7)  { animation-delay: 0.6s; }
        .room-gallery-item:nth-child(8)  { animation-delay: 0.7s; }
        .room-gallery-item:nth-child(9)  { animation-delay: 0.8s; }
        .room-gallery-item:nth-child(10) { animation-delay: 0.9s; }
        .room-gallery-item:nth-child(11) { animation-delay: 1.0s; }
        .room-gallery-item:nth-child(12) { animation-delay: 1.1s; }
        .room-gallery-item:nth-child(13) { animation-delay: 1.2s; }
        .room-gallery-item:nth-child(14) { animation-delay: 1.3s; }
        .room-gallery-item:nth-child(15) { animation-delay: 1.4s; }

    
    .room-gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }
    
    .room-gallery-image {
        width: 120px;
        height: 120px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .room-gallery-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .room-gallery-item:hover .room-gallery-image img {
        transform: scale(1.08);
    }
    
    .room-gallery-content {
        flex: 1;
        min-width: 0;
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Auto-scroll animation container */
    .auto-scroll-container {
        position: relative;
        overflow: hidden;
        height: 600px;
        border-radius: 12px;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
    }
    
    .auto-scroll-content {
        position: absolute;
        width: 100%;
        animation: autoScroll 30s linear infinite;
        animation-play-state: running;
    }
    
    .auto-scroll-content:hover {
        animation-play-state: paused;
    }
    
    @keyframes autoScroll {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(calc(-100% + 600px));
        }
    }
    
    /* 🚨 CRITICAL FIX: Modern Theme Image Display */
    .aspect-square {
        aspect-ratio: 1 / 1;
        position: relative;
    }

    .aspect-square img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* Force image containers to show */
    .bg-gray-100 {
        background-color: #f3f4f6 !important;
    }

    /* Fix broken images */
    img[src*="undefined"],
    img[src*="null"],
    img[src=""] {
        content: url('{{ asset("images/default-room.png") }}') !important;
        opacity: 0.7 !important;
    }
</style>
@endpush

@section('content')
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
                                 alt="{{ $hostel->name }}"
                                 class="w-full h-full object-cover"
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
                    <div>
                        <a href="tel:{{ $hostel->contact_phone }}" 
                           class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl text-white font-semibold transition-all duration-300 backdrop-blur-sm">
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
                    <div class="text-2xl font-bold mb-1">{{ $hostel->total_rooms ?? 0 }}</div>
                    <div class="opacity-90 text-sm">कुल कोठा</div>
                </div>
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1">{{ $hostel->available_rooms ?? 0 }}</div>
                    <div class="opacity-90 text-sm">उपलब्ध कोठा</div>
                </div>
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1">{{ $studentCount ?? 0 }}</div>
                    <div class="opacity-90 text-sm">विद्यार्थी</div>
                </div>
                <div class="modern-stat-card">
                    <div class="text-2xl font-bold mb-1">{{ $reviewCount ?? 0 }}</div>
                    <div class="opacity-90 text-sm">समीक्षाहरू</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="modern-container py-8">
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
                                    <label class="block text-sm font-medium text-gray-700 mb-1">तपाईंको नाम</label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">इमेल ठेगाना</label>
                                    <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">सन्देश</label>
                                    <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
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
                                        referrerpolicy="no-referrer-when-downgrade">
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
                        <h3 class="text-lg font-bold mb-4">किन हामीलाई छान्ने?</h3>
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
                               class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center gap-1">
                                <i class="fas fa-external-link-alt mr-1"></i> सबै हेर्नुहोस्
                            </a>
                        @endif
                    </div>
                    
                    <!-- ✅ UPDATED: Vertical Sliding Gallery -->
                    @php
                        // Get ALL active galleries, maximum 15
                        $allGalleries = $hostel->galleries()->where('is_active', true)->take(15)->get();
                    @endphp
                    
                    @if($allGalleries->count() > 0)
                        <!-- Vertical Scroll Container -->
                        <div class="room-gallery-vertical">
                            @foreach($allGalleries as $index => $gallery)
                                <div class="room-gallery-item flex gap-4">
                                    <div class="room-gallery-image">
                                        @php
                                            // SIMPLE FIX: Direct image URL access
                                            $imgUrl = $gallery->media_url ?? asset('images/default-room.png');
                                            
                                            // If media_url is empty, try to construct from media_path
                                            if (empty($imgUrl) || str_contains($imgUrl, 'default-room.png')) {
                                                if (!empty($gallery->media_path)) {
                                                    $imgUrl = Storage::disk('public')->exists($gallery->media_path) 
                                                              ? Storage::disk('public')->url($gallery->media_path)
                                                              : asset('images/default-room.png');
                                                }
                                            }
                                        @endphp
                                        
                                        <img src="{{ $imgUrl }}" 
                                             alt="{{ $gallery->title }}"
                                             onerror="this.src='{{ asset('images/default-room.png') }}'; this.style.opacity='0.7';">
                                    </div>
                                    
                                    <div class="room-gallery-content">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="font-bold text-gray-900">{{ $gallery->title }}</h4>
                                            @if($gallery->is_featured)
                                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                                    <i class="fas fa-star mr-1"></i> फिचर्ड
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($gallery->description)
                                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($gallery->description, 80) }}</p>
                                        @endif
                                        
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                                <i class="fas fa-tag"></i>
                                                {{ $gallery->category ?? 'कोठा' }}
                                            </span>
                                            
                                            @if($gallery->room_id && $gallery->room)
                                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                                    <i class="fas fa-door-open"></i>
                                                    कोठा: {{ $gallery->room->room_number ?? 'N/A' }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-3 text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $gallery->created_at->format('Y-m-d') ?? '२०२५-०१-०१' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Gallery Counter -->
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-image mr-1"></i>
                                <span class="font-medium">{{ $allGalleries->count() }}</span> वटा तस्बिरहरू
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-mouse-pointer mr-1"></i>
                                तलबाट माथि स्क्रोल गर्नुहोस्
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-images text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-600 mb-2">कोठाको ग्यालरी उपलब्ध छैन</h3>
                            <p class="text-gray-500">यस होस्टलको कोठाको ग्यालरी सामग्री चाँहि उपलब्ध छैन।</p>
                        </div>
                    @endif
                    
                    <!-- ✅ नयाँ बटन: कोठाका फोटो र बुकिंग -->
@if($hostel->slug)
    <div class="mt-8 mb-4 pt-4">
        <a href="{{ route('hostel.gallery', $hostel->slug) }}"
           class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-700 hover:to-emerald-700 transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
            <i class="fas fa-camera mr-2"></i>
            कोठाका फोटो र बुकिंग
        </a>
        <p class="text-xs text-center text-gray-600 mt-2">
            (प्रत्येक कोठाका फोटोहरू हेरेर सिधै बुक गर्नुहोस्)
        </p>
    </div>
@endif

                    <!-- View Full Gallery Button -->
                    @if($hostel->slug && $allGalleries->count() > 0)
                        <div class="pt-6 border-t">
                            <a href="{{ route('hostels.full.gallery', $hostel->slug) }}" 
                               class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity">
                                <i class="fas fa-images mr-2"></i>
                                पुरै ग्यालरी हेर्नुहोस्
                            </a>
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
                                       class="font-medium hover:text-blue-600 transition-colors">
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
                                       class="font-medium hover:text-blue-600 transition-colors text-sm break-all">
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
                <div class="modern-card bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200">
                    <div class="p-5">
                        <div class="flex items-center justify-center gap-3 mb-3">
                            <i class="fas fa-bed text-2xl {{ $hostel->available_rooms > 0 ? 'text-green-600' : 'text-gray-500' }}"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2 text-gray-900" style="font-weight: 600;">
                            @if($hostel->available_rooms > 0)
                                कोठा उपलब्ध!
                            @else
                                सबै कोठा भरिएको
                            @endif
                        </h4>
                        
                        @if($hostel->available_rooms > 0)
                            <p class="mb-4 text-sm text-gray-800" style="font-weight: 600;">
                                अहिले {{ $hostel->available_rooms }} कोठा खाली छन्
                            </p>
                            <!-- FIXED: Button redirects to booking route -->
                            <a href="{{ route('hostels.book', $hostel->slug) ?: url('/hostels/' . $hostel->slug . '/book') }}"  
                               class="inline-block w-full text-center bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white py-3 px-6 rounded-lg font-bold transition-all duration-300 text-sm">
                                <i class="fas fa-calendar-check mr-2"></i>
                                अहिले बुक गर्नुहोस्
                            </a>
                        @else
                            <p class="text-sm mb-4 text-gray-700">अहिले कुनै कोठा उपलब्ध छैन</p>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 px-4 py-3 rounded-lg text-sm transition-colors">
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
                        
                        <div class="text-center mt-4">
                            <a href="#contact-form" 
                               class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center gap-1 text-sm">
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
                        <a href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity">
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
                    <button class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity text-sm">
                        <i class="fas fa-pen mr-2"></i>पहिलो समीक्षा लेख्नुहोस्
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Floating Actions -->
<div class="modern-floating-actions">
    @if($hostel->whatsapp_number)
        <a href="https://wa.me/{{ $hostel->whatsapp_number }}" 
           target="_blank" 
           class="floating-btn whatsapp"
           aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endif
    
    @if($hostel->contact_phone)
        <a href="tel:{{ $hostel->contact_phone }}" 
           class="floating-btn phone"
           aria-label="फोन गर्नुहोस्">
            <i class="fas fa-phone"></i>
        </a>
    @endif
    
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="floating-btn scroll-top"
            aria-label="माथि जानुहोस्">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>

<!-- JavaScript for Gallery Auto Scroll (Optional) -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fix all gallery images
    document.querySelectorAll('img[src*="thumbnail"]').forEach(img => {
        if (img.src.includes('undefined') || img.src.includes('null')) {
            img.src = '/images/default-room.png';
        }
    });
    
    // Optional: Auto-scroll effect for vertical gallery
    const galleryContainer = document.querySelector('.room-gallery-vertical');
    if (galleryContainer) {
        let isPaused = false;
        
        galleryContainer.addEventListener('mouseenter', () => {
            isPaused = true;
        });
        
        galleryContainer.addEventListener('mouseleave', () => {
            isPaused = false;
            startAutoScroll();
        });
        
        function startAutoScroll() {
            if (isPaused) return;
            
            const scrollHeight = galleryContainer.scrollHeight;
            const clientHeight = galleryContainer.clientHeight;
            
            if (scrollHeight > clientHeight) {
                galleryContainer.scrollTop += 1;
                
                if (galleryContainer.scrollTop >= (scrollHeight - clientHeight)) {
                    // Reset to top when reaching bottom
                    setTimeout(() => {
                        galleryContainer.scrollTop = 0;
                    }, 2000);
                }
                
                setTimeout(startAutoScroll, 30);
            }
        }
        
        // Start auto-scroll after 3 seconds
        setTimeout(startAutoScroll, 3000);
    }
    
    // Log current routes for debugging
    console.log('Hostel Slug:', '{{ $hostel->slug }}');
    console.log('Gallery Route:', '{{ route("hostels.full.gallery", $hostel->slug) }}');
    console.log('Book Route:', '{{ route("hostels.book", $hostel->slug) }}');
});
</script>
@endpush
@endsection