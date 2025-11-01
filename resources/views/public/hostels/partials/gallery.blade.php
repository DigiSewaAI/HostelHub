@php
    $galleries = $hostel->activeGalleries ?? collect();
    $featuredGalleries = $hostel->featuredGalleries ?? collect();
@endphp

@push('styles')
@vite(['resources/css/gallery.css', 'resources/css/public-themes.css'])
@endpush

@if($galleries->count() > 0)
<section class="gallery-section mt-8">
    <h2 class="text-2xl font-bold text-gray-900 nepali mb-6">ग्यालरी</h2>
    
    @if($featuredGalleries->count() > 0)
    <!-- Featured Gallery -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 nepali mb-4">फिचर्ड ग्यालरी</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($featuredGalleries as $gallery)
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
                
                @if($gallery->media_type !== 'image')
                <div class="absolute top-2 right-2">
                    <span class="bg-white bg-opacity-90 text-gray-800 text-xs px-2 py-1 rounded-full nepali">
                        {{ $gallery->media_type_nepali }}
                    </span>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- All Gallery Items -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($galleries as $gallery)
        <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm group relative">
            @if($gallery->media_type === 'image')
                <img src="{{ $gallery->thumbnail_url }}" 
                     alt="{{ $gallery->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @elseif($gallery->media_type === 'external_video')
                <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                    <i class="fab fa-youtube text-white text-3xl"></i>
                </div>
            @else
                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                    <i class="fas fa-video text-white text-3xl"></i>
                </div>
            @endif
            
            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                <div class="text-white text-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-4">
                    <h4 class="font-semibold text-sm mb-1 nepali">{{ $gallery->title }}</h4>
                    @if($gallery->description)
                        <p class="text-xs nepali">{{ Str::limit($gallery->description, 40) }}</p>
                    @endif
                    @if($gallery->is_featured)
                        <span class="inline-block bg-yellow-500 text-white text-xs px-2 py-1 rounded-full mt-2 nepali">फिचर्ड</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@else
<!-- Empty State -->
<section class="gallery-section mt-8">
    <h2 class="text-2xl font-bold text-gray-900 nepali mb-6">ग्यालरी</h2>
    <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
        <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-images text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-600 nepali mb-2">कुनै ग्यालरी सामग्री छैन</h3>
        <p class="text-gray-500 nepali">यस होस्टलको ग्यालरी चाँहि उपलब्ध छैन।</p>
    </div>
</section>
@endif