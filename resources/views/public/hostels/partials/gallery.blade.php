@php
    // Filter for room galleries only
    $roomCategories = ['1 seater', '2 seater', '3 seater', '4 seater', 'other', 'साझा कोठा'];
    
    // Get room galleries only (filtered by category or room_id)
    $roomGalleries = $hostel->activeGalleries ?? collect();
    $roomGalleries = $roomGalleries->filter(function($gallery) use ($roomCategories) {
        return in_array(strtolower($gallery->category), array_map('strtolower', $roomCategories)) || 
               $gallery->room_id !== null;
    });
    
    // Get featured room galleries
    $featuredGalleries = $roomGalleries->where('is_featured', true);
    
    // Take 12-15 images for vertical slider (increased from 4)
    $verticalGalleries = $roomGalleries->take(15);
@endphp

@push('styles')
@vite(['resources/css/gallery.css', 'resources/css/public-themes.css'])
@endpush

@if($verticalGalleries->count() > 0)
<section class="gallery-section mt-10 mb-10">
    <h2 class="text-2xl font-bold text-gray-900 nepali mb-6">कोठाको ग्यालरी</h2>
    
    <!-- Featured Room Gallery Section -->
    @if($featuredGalleries->count() > 0)
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 nepali mb-4">फिचर्ड कोठा ग्यालरी</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($featuredGalleries->take(2) as $gallery)
            <div class="relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                @if($gallery->media_type === 'image')
                    <img src="{{ $gallery->thumbnail_url }}" 
                         alt="{{ $gallery->title }}"
                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                @elseif($gallery->media_type === 'external_video')
                    <div class="w-full h-48 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center relative">
                        <i class="fab fa-youtube text-white text-4xl"></i>
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                    </div>
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center relative">
                        <i class="fas fa-video text-white text-4xl"></i>
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                    </div>
                @endif
                
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4 text-white">
                    <h4 class="font-semibold text-sm nepali">{{ $gallery->title }}</h4>
                    @if($gallery->description)
                        <p class="text-xs opacity-90 nepali">{{ Str::limit($gallery->description, 50) }}</p>
                    @endif
                </div>
                
                <!-- Category Badge -->
                <div class="absolute top-2 left-2">
                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full nepali">
                        {{ $gallery->category_nepali ?? $gallery->category }}
                    </span>
                </div>
                
                @if($gallery->media_type !== 'image')
                <div class="absolute top-2 right-2">
                    <span class="bg-white bg-opacity-90 text-gray-800 text-xs px-2 py-1 rounded-full nepali">
                        {{ $gallery->media_type_nepali }}
                    </span>
                </div>
                @endif
                
                <!-- Featured Badge -->
                <div class="absolute top-12 left-2">
                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full nepali">
                        <i class="fas fa-star mr-1"></i> फिचर्ड
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Vertical Room Gallery Slider (10-15 images) -->
    <div class="room-gallery-vertical space-y-4 py-4">
        @foreach($verticalGalleries as $gallery)
        <div class="room-gallery-preview-item flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
            @if($gallery->media_type === 'image')
                <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden">
                    <img src="{{ $gallery->thumbnail_url }}" 
                         alt="{{ $gallery->title }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                </div>
            @elseif($gallery->media_type === 'external_video')
                <div class="w-24 h-24 flex-shrink-0 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                    <i class="fab fa-youtube text-white text-2xl"></i>
                </div>
            @else
                <div class="w-24 h-24 flex-shrink-0 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-video text-white text-2xl"></i>
                </div>
            @endif
            
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <h4 class="font-semibold text-gray-900 text-sm truncate nepali">{{ $gallery->title }}</h4>
                    @if($gallery->is_featured)
                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded-full nepali whitespace-nowrap flex-shrink-0">
                            <i class="fas fa-star mr-1 text-xs"></i> फिचर्ड
                        </span>
                    @endif
                </div>
                
                @if($gallery->description)
                    <p class="text-gray-600 text-xs mt-1 nepali line-clamp-2">{{ Str::limit($gallery->description, 80) }}</p>
                @endif
                
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full nepali">
                        {{ $gallery->category_nepali ?? $gallery->category }}
                    </span>
                    @if($gallery->room_id && $gallery->room)
                    <span class="text-xs text-gray-600 nepali flex items-center">
                        <i class="fas fa-door-closed mr-1 text-xs"></i> कोठा: {{ $gallery->room->room_number }}
                    </span>
                    @endif
                    <span class="text-xs text-gray-500 ml-auto">
                        <i class="far fa-clock mr-1"></i>{{ $gallery->created_at->format('Y-m-d') }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- REMOVED: Duplicate button is now only in modern.blade.php -->
    
</section>
@else
<!-- Empty State for Room Gallery -->
<section class="gallery-section mt-10 mb-10">
    <h2 class="text-2xl font-bold text-gray-900 nepali mb-6">कोठाको ग्यालरी</h2>
    <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
        <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-images text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-600 nepali mb-2">कोठाको ग्यालरी उपलब्ध छैन</h3>
        <p class="text-gray-500 nepali">यस होस्टलको कोठाको ग्यालरी सामग्री चाँहि उपलब्ध छैन।</p>
    </div>
</section>
@endif

<style>
/* Vertical Room Gallery Slider Styles */
.room-gallery-vertical {
    max-height: 550px;
    overflow-y: auto;
    padding-right: 8px;
    margin: 0 0 20px 0;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.room-gallery-vertical::-webkit-scrollbar {
    width: 6px;
}

.room-gallery-vertical::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.room-gallery-vertical::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.room-gallery-vertical::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Room Gallery Preview Item Styles */
.room-gallery-preview-item {
    transition: all 0.2s ease;
    cursor: pointer;
    background: white;
}

.room-gallery-preview-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border-color: #3b82f6;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Gallery Section Spacing */
.gallery-section {
    margin-top: 2.5rem;
    margin-bottom: 2.5rem;
    padding-top: 1rem;
    padding-bottom: 1rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .room-gallery-vertical {
        max-height: 450px;
    }
    
    .room-gallery-preview-item {
        padding: 12px;
    }
    
    .gallery-section {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
}

/* Ensure proper spacing with other sections */
.gallery-section + .reviews-section,
.gallery-section + .modern-card {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

/* Animation for smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

.group-hover\:scale-105 {
    transition: transform 0.3s ease;
}

.group-hover\:scale-110 {
    transition: transform 0.3s ease;
}

/* Fix for line height in Nepali text */
.nepali {
    line-height: 1.6;
}
</style>