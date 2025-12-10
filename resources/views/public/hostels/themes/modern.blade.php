@php 
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
    
    /* Vertical Room Gallery Slider */
    .room-gallery-vertical {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 550px;
        overflow-y: auto;
        padding-right: 8px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
        margin-bottom: 20px;
    }
    
    .room-gallery-vertical::-webkit-scrollbar {
        width: 6px;
    }
    
    .room-gallery-vertical::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .room-gallery-vertical::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .room-gallery-vertical::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    .room-gallery-item {
        transition: all 0.2s ease;
    }
    
    .room-gallery-item:hover {
        transform: translateY(-2px);
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
                        <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-history"></i>
                            <span>{{ $hostel->established_year ?? '२०२०' }} देखि</span>
                        </div>
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
                    
                    <!-- Vertical Room Gallery Slider -->
                    @php
                        // Get room galleries (10-15 images)
                        $roomGalleries = $hostel->activeGalleries ?? collect();
                        $roomCategories = ['1 seater', '2 seater', '3 seater', '4 seater', 'other', 'साझा कोठा'];
                        
                        $roomGalleries = $roomGalleries->filter(function($gallery) use ($roomCategories) {
                            return in_array(strtolower($gallery->category), array_map('strtolower', $roomCategories)) || 
                                   $gallery->room_id !== null;
                        })->take(15);
                    @endphp
                    
                    @if($roomGalleries->count() > 0)
                        <div class="room-gallery-vertical">
                            @foreach($roomGalleries as $gallery)
                            <div class="room-gallery-item bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                                <div class="flex gap-4 p-4">
                                    @if($gallery->media_type === 'image')
                                        <div class="w-32 h-32 flex-shrink-0 rounded-lg overflow-hidden">
                                            @php
                                                // Safe image URL generation
                                                $imageUrl = $gallery->thumbnail_url ?? asset('images/default-room.png');
                                                
                                                // Additional fallback check
                                                if (empty($imageUrl) || str_contains($imageUrl, 'default-room.png')) {
                                                    // Try to get from media_path
                                                    if (!empty($gallery->media_path)) {
                                                        if (filter_var($gallery->media_path, FILTER_VALIDATE_URL)) {
                                                            $imageUrl = $gallery->media_path;
                                                        } elseif (Storage::disk('public')->exists($gallery->media_path)) {
                                                            $imageUrl = Storage::url($gallery->media_path);
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <img src="{{ $imageUrl }}" 
                                                 alt="{{ $gallery->title }}"
                                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                                 onerror="this.src='{{ asset('images/default-room.png') }}'">
                                        </div>
                                    @elseif($gallery->media_type === 'external_video')
                                        <div class="w-32 h-32 flex-shrink-0 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                            <i class="fab fa-youtube text-white text-2xl"></i>
                                        </div>
                                    @else
                                        <div class="w-32 h-32 flex-shrink-0 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-video text-white text-2xl"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <h4 class="font-semibold text-gray-900 text-base">{{ $gallery->title }}</h4>
                                            @if($gallery->is_featured)
                                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full whitespace-nowrap">
                                                    <i class="fas fa-star mr-1 text-xs"></i> फिचर्ड
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($gallery->description)
                                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($gallery->description, 100) }}</p>
                                        @endif
                                        
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-medium">
                                                {{ $gallery->category }}
                                            </span>
                                            @if($gallery->room_id && $gallery->room)
                                            <span class="text-xs text-gray-600">
                                                <i class="fas fa-door-closed mr-1"></i> कोठा: {{ $gallery->room->room_number }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
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
                    
                    <!-- SINGLE View Full Gallery Button -->
                    @if($hostel->slug)
                        <div class="mt-8 pt-6 border-t">
                            <a href="{{ route('hostels.full.gallery', $hostel->slug) ?: url('/hostel/' . $hostel->slug . '/full-gallery') }}" 
                               class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity">
                                <i class="fas fa-images"></i>
                                कोठाको पुरै ग्यालरी हेर्नुहोस्
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

<!-- Emergency Backup Buttons (Hidden by default) -->
<div class="fixed bottom-4 left-4 z-50 hidden">
    <!-- Test links for debugging -->
    <a href="{{ route('direct.hostel.gallery', $hostel->slug) }}" 
       class="bg-red-500 text-white p-2 rounded">DEBUG: Gallery</a>
    <a href="{{ route('direct.book.now', $hostel->slug) }}" 
       class="bg-green-500 text-white p-2 rounded">DEBUG: Book Now</a>
</div>

<!-- JavaScript to fix image URLs -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fix all gallery images
    document.querySelectorAll('img[src*="thumbnail"]').forEach(img => {
        if (img.src.includes('undefined') || img.src.includes('null')) {
            img.src = '/images/default-room.png';
        }
    });
    
    // Log current routes for debugging
    console.log('Hostel Slug:', '{{ $hostel->slug }}');
    console.log('Gallery Route:', '{{ route("hostels.full.gallery", $hostel->slug) }}');
    console.log('Book Route:', '{{ route("hostels.book", $hostel->slug) }}');
});
</script>
@endpush
@endsection