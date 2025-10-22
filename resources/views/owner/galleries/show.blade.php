@extends('layouts.owner')

@section('title', $gallery->title)

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <!-- Header with Back Button -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 nepali">{{ $gallery->title }}</h1>
            <p class="text-gray-600 nepali mt-1">ग्यालरी विवरण हेर्नुहोस्</p>
        </div>
        <a href="{{ route('owner.galleries.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-xl font-medium no-underline transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>ग्यालरी सूचीमा फर्कनुहोस्
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Media Display Section -->
        <div class="space-y-6">
            <!-- Main Media Display -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                @if($gallery->media_type === 'image')
                    <!-- Image Display -->
                    <div class="w-full h-96 flex items-center justify-center bg-gray-100">
                        <img src="{{ $gallery->media_url }}" 
                             alt="{{ $gallery->title }}"
                             class="max-w-full max-h-full object-contain rounded-lg cursor-pointer"
                             onclick="openImageModal('{{ $gallery->media_url }}')">
                    </div>
                @elseif($gallery->media_type === 'external_video')
                    <!-- YouTube Video Display -->
                    <div class="aspect-video bg-black rounded-xl overflow-hidden">
                        @if($gallery->youtube_embed_url)
                            <iframe src="{{ $gallery->youtube_embed_url }}" 
                                    class="w-full h-full"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-500 to-red-700">
                                <div class="text-center text-white">
                                    <i class="fab fa-youtube text-6xl mb-4"></i>
                                    <p class="nepali text-lg">यूट्युब भिडियो</p>
                                    <a href="{{ $gallery->external_link }}" target="_blank" 
                                       class="text-yellow-300 hover:text-yellow-200 underline mt-2 inline-block">
                                        मूल भिडियो हेर्नुहोस्
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($gallery->media_type === 'video')
                    <!-- Local Video Display -->
                    <div class="aspect-video bg-black rounded-xl overflow-hidden">
                        <video controls class="w-full h-full rounded-lg">
                            <source src="{{ $gallery->media_url }}" type="video/mp4">
                            तपाईंको ब्राउजरले भिडियो सपोर्ट गर्दैन।
                        </video>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="flex space-x-4">
                <a href="{{ route('owner.galleries.edit', $gallery) }}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-xl text-center font-medium transition-colors">
                    <i class="fas fa-edit mr-2"></i>सम्पादन गर्नुहोस्
                </a>
                
                <form action="{{ route('owner.galleries.destroy', $gallery) }}" method="POST" class="flex-1" 
                      onsubmit="return confirm('के तपाईं यो ग्यालरी आइटम मेटाउन निश्चित हुनुहुन्छ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-xl font-medium transition-colors">
                        <i class="fas fa-trash mr-2"></i>मेटाउनुहोस्
                    </button>
                </form>
            </div>
        </div>

        <!-- Details Section -->
        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">आधारभूत जानकारी</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-700 nepali font-medium">शीर्षक:</span>
                        <span class="text-gray-900 nepali">{{ $gallery->title }}</span>
                    </div>

                    <div class="flex justify-between items-start py-2 border-b border-gray-200">
                        <span class="text-gray-700 nepali font-medium">विवरण:</span>
                        <span class="text-gray-900 nepali text-right">{{ $gallery->description ?: 'कुनै विवरण छैन' }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-700 nepali font-medium">श्रेणी:</span>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm nepali">
                            {{ $gallery->category_nepali }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-700 nepali font-medium">मिडिया प्रकार:</span>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm nepali">
                            {{ $gallery->media_type_nepali }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Status Information -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">स्थिति र मेटाडाटा</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-700 nepali font-medium">फिचर्ड:</span>
                        <span class="flex items-center">
                            @if($gallery->is_featured)
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-green-700 nepali">हो</span>
                            @else
                                <i class="fas fa-times-circle text-gray-400 mr-2"></i>
                                <span class="text-gray-600 nepali">होइन</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-700 nepali font-medium">सक्रिय:</span>
                        <span class="flex items-center">
                            @if($gallery->is_active)
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-green-700 nepali">हो</span>
                            @else
                                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                <span class="text-red-700 nepali">होइन</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-700 nepali font-medium">सिर्जना मिति:</span>
                        <span class="text-gray-900 nepali">{{ $gallery->created_at->format('Y-m-d') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700 nepali font-medium">अद्यावधिक मिति:</span>
                        <span class="text-gray-900 nepali">{{ $gallery->updated_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>

            <!-- External Links (if applicable) -->
            @if($gallery->media_type === 'external_video' && $gallery->external_link)
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                <h2 class="text-lg font-semibold text-gray-800 mb-3 nepali">यूट्युब लिङ्क</h2>
                <a href="{{ $gallery->external_link }}" 
                   target="_blank"
                   class="text-blue-600 hover:text-blue-800 break-words nepali">
                    <i class="fab fa-youtube mr-2 text-red-500"></i>
                    {{ $gallery->external_link }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for Image Preview -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
    <div class="relative max-w-4xl w-full mx-4">
        <button id="closeImageModal" class="absolute -top-12 right-0 text-white text-3xl hover:text-gray-300 transition-colors">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" class="w-full h-auto max-h-[80vh] object-contain rounded-lg" alt="">
    </div>
</div>

<style>
.nepali {
    font-family: 'Noto Sans Devanagari', sans-serif;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
// Image Modal functionality
function openImageModal(imageUrl) {
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = imageUrl;
    imageModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

document.addEventListener('DOMContentLoaded', function() {
    const imageModal = document.getElementById('imageModal');
    const closeImageModal = document.getElementById('closeImageModal');

    // Close modal
    closeImageModal.addEventListener('click', closeModal);
    imageModal.addEventListener('click', function(e) {
        if (e.target === imageModal) {
            closeModal();
        }
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    function closeModal() {
        imageModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
});
</script>
@endsection