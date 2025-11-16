@props(['hostel'])

@php
    $mainImage = $hostel->images->first();
    $avgRating = $hostel->reviews_avg_rating ?? 0;
    $reviewCount = $hostel->reviews->count();
    $minPrice = $hostel->rooms->min('price') ?? 0;
@endphp

<div class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative">
    <!-- Premium Badge -->
    @if($hostel->available_rooms_count > 5)
    <div class="absolute top-4 right-4 z-10">
        <span class="px-3 py-1 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-xs font-bold rounded-full nepali shadow-lg flex items-center">
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
            @if($hostel->available_rooms_count > 0)
            <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold rounded-full nepali shadow-lg flex items-center">
                <i class="fas fa-key mr-2"></i>
                {{ $hostel->available_rooms_count }} कोठा उपलब्ध
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

        <!-- Price & Action Section -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <div>
                @if($minPrice > 0)
                <span class="text-2xl font-bold text-blue-600 nepali">रु. {{ number_format($minPrice) }}</span>
                <span class="text-gray-500 text-sm nepali block">सुरुवाती मूल्य</span>
                @else
                <span class="text-lg font-semibold text-gray-500 nepali">मूल्य उपलब्ध छैन</span>
                @endif
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('hostels.show', $hostel->slug) }}" 
                   class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl nepali">
                    <i class="fas fa-eye mr-2"></i>
                    हेर्नुहोस्
                </a>
                @if($hostel->available_rooms_count > 0)
                <a href="{{ route('hostel.book-room', $hostel->slug) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}" 
                   class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl nepali">
                    <i class="fas fa-calendar-check mr-2"></i>
                    बुक गर्नुहोस्
                </a>
                @else
                <button disabled
                   class="inline-flex items-center px-5 py-3 bg-gray-400 text-white font-semibold rounded-xl cursor-not-allowed nepali">
                    <i class="fas fa-times mr-2"></i>
                    उपलब्ध छैन
                </button>
                @endif
            </div>
        </div>
    </div>
</div>