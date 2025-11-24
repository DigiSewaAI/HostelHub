@props(['hostel'])

@php
    $mainImage = $hostel->images->first();
    $avgRating = $hostel->reviews_avg_rating ?? 0;
    $reviewCount = $hostel->reviews->count();
    $minPrice = $hostel->rooms->min('price') ?? 0;
    // ✅ FIXED: Use the correct available_rooms_count attribute
    $availableRooms = $hostel->available_rooms_count ?? $hostel->rooms()->where('status', 'available')->count();
@endphp

<div class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative">
    <!-- Premium Badge - FIXED: Golden yellow with black text -->
    @if($availableRooms > 5)
    <div class="absolute top-4 right-4 z-20">
        <span class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-yellow-500 text-black text-xs font-bold rounded-full shadow-lg flex items-center border border-yellow-300">
            <i class="fas fa-crown mr-1 text-xs"></i>
            PREMIUM
        </span>
    </div>
    @endif

    <!-- Image Section with Gradient Overlay -->
    <div class="relative overflow-hidden">
        @if($mainImage)
        <img 
            src="{{ asset('storage/' . $mainImage->file_path) }}" 
            class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-700"
            alt="{{ $hostel->name }}"
            loading="lazy"
            onerror="this.src='{{ asset('images/hostel-placeholder.jpg') }}'"
        >
        @else
        <div class="w-full h-56 bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center relative">
            <i class="fas fa-building text-blue-300 text-5xl"></i>
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        </div>
        @endif
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
        
        <!-- Availability Badge -->
        <div class="absolute bottom-3 left-3">
            @if($availableRooms > 0)
            <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold rounded-full nepali shadow-lg flex items-center">
                <i class="fas fa-key mr-2"></i>
                {{ $availableRooms }} कोठा उपलब्ध
            </span>
            @else
            <span class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-bold rounded-full nepali shadow-lg flex items-center">
                <i class="fas fa-times mr-2"></i>
                कोठा नभएको
            </span>
            @endif
        </div>

        <!-- Location Badge -->
        <div class="absolute top-3 left-3">
            <span class="px-3 py-2 bg-white/90 backdrop-blur-sm text-blue-700 text-sm font-semibold rounded-full nepali shadow-lg flex items-center">
                <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                {{ $hostel->city }}
            </span>
        </div>

        <!-- Rating Badge -->
        @if($avgRating > 0)
        <div class="absolute top-3 right-3">
            <span class="px-3 py-2 bg-black/70 backdrop-blur-sm text-white text-sm font-bold rounded-full flex items-center">
                <i class="fas fa-star text-yellow-400 mr-1"></i>
                {{ number_format($avgRating, 1) }}
                <span class="text-xs opacity-80 ml-1">({{ $reviewCount }})</span>
            </span>
        </div>
        @endif
    </div>

    <!-- Content Section -->
    <div class="p-6">
        <!-- Title and Rating -->
        <div class="flex items-start justify-between mb-3">
            <h3 class="text-xl font-bold text-gray-900 nepali group-hover:text-blue-600 transition-colors flex-1 pr-2">
                {{ $hostel->name }}
            </h3>
        </div>
        
        <!-- Description -->
        <p class="text-gray-600 text-sm mb-4 nepali leading-relaxed line-clamp-2">
            {{ \Illuminate\Support\Str::limit($hostel->description, 120) }}
        </p>
        
        <!-- Enhanced Features Grid -->
        <div class="grid grid-cols-2 gap-3 mb-6">
            @php
                $features = [
                    'wifi' => ['icon' => 'wifi', 'text' => 'हाइ-स्पीड वाईफाई', 'color' => 'blue'],
                    'water' => ['icon' => 'tint', 'text' => '२४/७ गरम पानी', 'color' => 'green'],
                    'security' => ['icon' => 'shield-alt', 'text' => 'सुरक्षित', 'color' => 'purple'],
                    'food' => ['icon' => 'utensils', 'text' => 'खाना उपलब्ध', 'color' => 'orange'],
                ];
                $displayed = 0;
            @endphp
            
            @foreach($features as $key => $feature)
                @if($displayed < 4)
                <div class="flex items-center text-sm text-gray-700">
                    <div class="w-8 h-8 bg-{{ $feature['color'] }}-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-{{ $feature['icon'] }} text-{{ $feature['color'] }}-600 text-xs"></i>
                    </div>
                    <span class="nepali text-xs leading-tight">{{ $feature['text'] }}</span>
                </div>
                @php $displayed++; @endphp
                @endif
            @endforeach
        </div>

        <!-- Price & Action Section - FIXED: Button container and styles -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <div>
                @if($minPrice > 0)
                <span class="text-2xl font-bold text-blue-600 nepali">रु. {{ number_format($minPrice) }}</span>
                <span class="text-gray-500 text-sm nepali block">सुरुवाती मूल्य</span>
                @else
                <span class="text-lg font-semibold text-gray-500 nepali">मूल्य उपलब्ध छैन</span>
                @endif
            </div>
            
            <!-- FIXED: Button container with proper spacing -->
            <div class="flex flex-col space-y-2 ml-4">
                <!-- FIXED: View Button with white text -->
                <a href="{{ route('hostels.show', $hostel->slug) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl nepali text-sm min-w-[120px] button-fixed view-button">
                    <i class="fas fa-eye mr-2"></i>
                    हेर्नुहोस्
                </a>
                
                <!-- ✅ FIXED: Updated booking route and enhanced date parameter handling -->
                @if($availableRooms > 0)
                    @php
                        $checkIn = request('check_in');
                        $checkOut = request('check_out');
                        $bookingUrl = route('hostel.book', $hostel->slug);
                        
                        // Add date parameters if they exist
                        if ($checkIn && $checkOut) {
                            $bookingUrl .= "?check_in={$checkIn}&check_out={$checkOut}";
                        }
                    @endphp
                    
                    <!-- FIXED: Book Button with white text -->
                    <a href="{{ $bookingUrl }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl nepali text-sm min-w-[120px] button-fixed book-button">
                        <i class="fas fa-calendar-check mr-2"></i>
                        बुक गर्नुहोस्
                    </a>
                @else
                <button disabled
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-400 text-white font-semibold rounded-xl cursor-not-allowed nepali text-sm min-w-[120px]">
                    <i class="fas fa-times mr-2"></i>
                    उपलब्ध छैन
                </button>
                @endif
            </div>
        </div>

        <!-- ✅ NEW: Date info display when dates are selected -->
        @if(request('check_in') && request('check_out'))
        <div class="mt-3 pt-3 border-t border-gray-100">
            <div class="flex items-center text-xs text-green-600 nepali">
                <i class="fas fa-calendar-day mr-2"></i>
                <span>तोकिएको मिति: {{ request('check_in') }} देखि {{ request('check_out') }} सम्म</span>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- ✅ FIXED: Enhanced custom styles for button text visibility -->
<style>
.nepali {
    font-family: 'Preeti', 'Mangal', 'Arial', sans-serif;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions for all interactive elements */
a, button {
    transition: all 0.3s ease;
}

/* Enhanced hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.05);
}

/* Ensure proper image rendering */
img {
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
}

/* ✅ FIXED: Button text visibility - FORCE WHITE TEXT */
.button-fixed {
    color: white !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    border: none !important;
}

/* ✅ FIXED: Specific styles for view button */
.view-button {
    background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
}

.view-button:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af) !important;
    color: white !important;
    transform: translateY(-2px) !important;
}

/* ✅ FIXED: Specific styles for book button */
.book-button {
    background: linear-gradient(135deg, #059669, #047857) !important;
}

.book-button:hover {
    background: linear-gradient(135deg, #047857, #065f46) !important;
    color: white !important;
    transform: translateY(-2px) !important;
}

/* ✅ FIXED: Remove any conflicting text color classes */
.button-fixed.text-gray-500,
.button-fixed.text-gray-600,
.button-fixed.text-gray-700,
.button-fixed.text-gray-800,
.button-fixed.text-gray-900 {
    color: white !important;
}

/* ✅ FIXED: Ensure Nepali text is properly visible in buttons */
.button-fixed .nepali {
    color: white !important;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .flex.flex-col.space-y-2.ml-4 {
        margin-left: 0.5rem;
    }
    
    .min-w-\[120px\] {
        min-width: 110px;
    }
}
</style>

<!-- ✅ FIXED: Enhanced JavaScript for button text visibility -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ FIXED: Force white text on all buttons
    const viewButtons = document.querySelectorAll('.view-button');
    const bookButtons = document.querySelectorAll('.book-button');
    
    viewButtons.forEach(button => {
        button.style.color = 'white';
        button.style.fontWeight = '600';
        // Remove any conflicting classes
        button.classList.remove('text-gray-500', 'text-gray-600', 'text-gray-700', 'text-gray-800', 'text-gray-900');
        button.classList.add('text-white');
    });
    
    bookButtons.forEach(button => {
        button.style.color = 'white';
        button.style.fontWeight = '600';
        // Remove any conflicting classes
        button.classList.remove('text-gray-500', 'text-gray-600', 'text-gray-700', 'text-gray-800', 'text-gray-900');
        button.classList.add('text-white');
    });

    // Add click tracking for analytics
    const bookingButtons = document.querySelectorAll('.book-button');
    bookingButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const hostelName = this.closest('.group').querySelector('h3').textContent;
            console.log('Booking initiated for:', hostelName);
            
            // You can add analytics tracking here
            // Example: gtag('event', 'booking_click', { hostel_name: hostelName });
        });
    });

    // Enhanced error handling for images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.src = '{{ asset('images/hostel-placeholder.jpg') }}';
            this.alt = 'Image not available';
        });
    });

    // Add loading state for buttons
    const buttons = document.querySelectorAll('a, button');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.disabled && this.classList.contains('button-fixed')) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> लोड हुँदै...';
                this.disabled = true;
                
                // Revert after 3 seconds if still on same page
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            }
        });
    });

    // ✅ FIXED: Additional safety - check every second for button text color
    setInterval(() => {
        document.querySelectorAll('.button-fixed').forEach(button => {
            if (window.getComputedStyle(button).color !== 'rgb(255, 255, 255)') {
                button.style.color = 'white';
                button.style.setProperty('color', 'white', 'important');
            }
        });
    }, 1000);
});
</script>