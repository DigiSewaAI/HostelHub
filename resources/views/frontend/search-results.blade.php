@extends('layouts.frontend')

@section('page-title', 'कोठा खोजी नतिजा - HostelHub')
@section('meta-description', 'तपाईंको खोजी अनुसारको कोठा र होस्टलहरू')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Enhanced Search Header -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6 transform transition-all duration-300 hover:shadow-xl">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-4 lg:mb-0">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-search text-white text-lg"></i>
                            </div>
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 nepali">
                                @if(isset($searchFilters) && isset($searchFilters['city']) && $searchFilters['city'])
                                    {{ $searchFilters['city'] }} मा होस्टलहरू
                                @elseif(isset($searchFilters) && isset($searchFilters['q']) && $searchFilters['q'])
                                    "{{ $searchFilters['q'] }}" को लागि नतिजा
                                @elseif(request('city'))
                                    {{ request('city') }} मा होस्टलहरू
                                @elseif(request('q'))
                                    "{{ request('q') }}" को लागि नतिजा
                                @else
                                    सबै होस्टलहरू
                                @endif
                            </h1>
                        </div>
                        
                        <!-- Enhanced Results Count & Filters -->
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center bg-blue-50 px-3 py-2 rounded-lg">
                                <span class="text-blue-700 font-semibold nepali">
                                    @if(method_exists($hostels, 'total'))
                                        <span class="text-lg">{{ $hostels->total() }}</span>
                                    @else
                                        <span class="text-lg">{{ $hostels->count() }}</span>
                                    @endif
                                    वटा होस्टल फेला पर्यो
                                </span>
                            </div>
                            
                            @if(request('city') || request('q') || request('hostel_id') || request('check_in') || 
                                request('min_price') || request('amenities') || request('hostel_type') ||
                                (isset($searchFilters) && ($searchFilters['city'] ?? false)))
                            <div class="flex flex-wrap gap-2">
                                @if(request('city') || (isset($searchFilters) && isset($searchFilters['city']) && $searchFilters['city']))
                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium shadow-sm nepali">
                                    <i class="fas fa-map-marker-alt mr-2 text-xs"></i>
                                    {{ request('city') ?? ($searchFilters['city'] ?? '') }}
                                    <button class="ml-2 hover:bg-blue-700 rounded-full w-4 h-4 flex items-center justify-center" onclick="removeFilter('city')">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </span>
                                @endif
                                
                                @if(request('q') || (isset($searchFilters) && isset($searchFilters['q']) && $searchFilters['q']))
                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium shadow-sm nepali">
                                    <i class="fas fa-search mr-2 text-xs"></i>
                                    "{{ request('q') ?? ($searchFilters['q'] ?? '') }}"
                                    <button class="ml-2 hover:bg-green-700 rounded-full w-4 h-4 flex items-center justify-center" onclick="removeFilter('q')">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </span>
                                @endif

                                @if(request('check_in') && request('check_out'))
                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 text-white text-sm font-medium shadow-sm nepali">
                                    <i class="fas fa-calendar-alt mr-2 text-xs"></i>
                                    {{ request('check_in') }} - {{ request('check_out') }}
                                    <button class="ml-2 hover:bg-purple-700 rounded-full w-4 h-4 flex items-center justify-center" onclick="removeFilter('dates')">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </span>
                                @endif

                                @if(request('min_price') || request('max_price'))
                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm font-medium shadow-sm nepali">
                                    <i class="fas fa-money-bill-wave mr-2 text-xs"></i>
                                    @if(request('min_price') && request('max_price'))
                                        रु. {{ request('min_price') }} - {{ request('max_price') }}
                                    @elseif(request('min_price'))
                                        रु. {{ request('min_price') }}+
                                    @elseif(request('max_price'))
                                        रु. {{ request('max_price') }} सम्म
                                    @endif
                                    <button class="ml-2 hover:bg-yellow-700 rounded-full w-4 h-4 flex items-center justify-center" onclick="removeFilter('price')">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </span>
                                @endif

                                @if(request('hostel_type') && request('hostel_type') != 'all')
                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-pink-500 to-pink-600 text-white text-sm font-medium shadow-sm nepali">
                                    <i class="fas fa-user-friends mr-2 text-xs"></i>
                                    {{ request('hostel_type') == 'boys' ? 'ब्वाइज होस्टल' : 'गर्ल्स होस्टल' }}
                                    <button class="ml-2 hover:bg-pink-700 rounded-full w-4 h-4 flex items-center justify-center" onclick="removeFilter('hostel_type')">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Enhanced Modify Search Button -->
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl nepali">
                        <i class="fas fa-arrow-left mr-3"></i>
                        फेरि खोजी गर्नुहोस्
                    </a>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Advanced Filter Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 nepali border-b border-gray-200 pb-3">फिल्टरहरू</h3>
                    
                    <!-- Functional Filter Form -->
                    <form action="{{ route('search') }}" method="GET" class="mb-6" id="filter-form">
                        <!-- Hidden fields to preserve existing filters -->
                        <input type="hidden" name="city" value="{{ request('city') ?? ($searchFilters['city'] ?? '') }}">
                        <input type="hidden" name="check_in" value="{{ request('check_in') ?? ($searchFilters['check_in'] ?? '') }}">
                        <input type="hidden" name="check_out" value="{{ request('check_out') ?? ($searchFilters['check_out'] ?? '') }}">
                        <input type="hidden" name="hostel_id" value="{{ request('hostel_id') ?? ($searchFilters['hostel_id'] ?? '') }}">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 nepali">खोजी गर्नुहोस्</label>
                            <input type="text" name="q" value="{{ request('q') ?? ($searchFilters['q'] ?? '') }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 nepali"
                                   placeholder="होस्टलको नाम वा स्थान...">
                        </div>
                        
                        <!-- City Filter -->
                        @if(isset($cities) && $cities->count() > 0)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 nepali">शहर</label>
                            <select name="city" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 nepali">
                                <option value="">सबै शहर</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ (request('city') ?? ($searchFilters['city'] ?? '')) == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Hostel Type Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-800 mb-3 nepali">होस्टल प्रकार</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" id="type-all" name="hostel_type" value="all" 
                                           {{ (request('hostel_type') ?? ($searchFilters['hostel_type'] ?? 'all')) == 'all' ? 'checked' : '' }}
                                           class="mr-3 text-blue-600 focus:ring-blue-500">
                                    <label for="type-all" class="text-gray-700 nepali text-sm">सबै प्रकार</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="type-boys" name="hostel_type" value="boys" 
                                           {{ (request('hostel_type') ?? ($searchFilters['hostel_type'] ?? '')) == 'boys' ? 'checked' : '' }}
                                           class="mr-3 text-blue-600 focus:ring-blue-500">
                                    <label for="type-boys" class="text-gray-700 nepali text-sm">ब्वाइज होस्टल</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="type-girls" name="hostel_type" value="girls" 
                                           {{ (request('hostel_type') ?? ($searchFilters['hostel_type'] ?? '')) == 'girls' ? 'checked' : '' }}
                                           class="mr-3 text-blue-600 focus:ring-blue-500">
                                    <label for="type-girls" class="text-gray-700 nepali text-sm">गर्ल्स होस्टल</label>
                                </div>
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-800 mb-3 nepali">मूल्य दायरा</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1 nepali">न्यूनतम</label>
                                    <input type="number" name="min_price" 
                                           value="{{ request('min_price') ?? ($searchFilters['min_price'] ?? '') }}"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="रु. ०">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1 nepali">अधिकतम</label>
                                    <input type="number" name="max_price" 
                                           value="{{ request('max_price') ?? ($searchFilters['max_price'] ?? '') }}"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="रु. १००००">
                                </div>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white py-3 rounded-lg font-semibold transition-all duration-300 nepali">
                            फिल्टर लागू गर्नुहोस्
                        </button>
                        
                        <!-- Reset Filters -->
                        <button type="button" onclick="resetFilters()" 
                                class="w-full mt-2 bg-gray-100 border border-gray-300 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-colors nepali">
                            फिल्टर रिसेट गर्नुहोस्
                        </button>
                    </form>

                    <!-- Quick Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('hostels.index') }}"
                           class="block w-full text-center bg-blue-50 border border-blue-200 text-blue-700 py-2 rounded-lg font-semibold hover:bg-blue-100 transition-colors nepali">
                            सबै होस्टल हेर्नुहोस्
                        </a>
                        <a href="{{ url('/gallery') }}" 
                           class="block w-full text-center bg-green-50 border border-green-200 text-green-700 py-2 rounded-lg font-semibold hover:bg-green-100 transition-colors nepali">
                            मुख्य ग्यालरी हेर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Sorting Options -->
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-4 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center mb-3 sm:mb-0">
                            <span class="text-gray-600 mr-3 nepali">कुल {{ $hostels->total() }} वटा होस्टल</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600 mr-3 nepali">क्रमबद्ध गर्नुहोस्:</span>
                            <select class="border border-gray-300 rounded-lg px-4 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 nepali">
                                <option>सिफारिस गरिएको</option>
                                <option>मूल्य: कम-उच्च</option>
                                <option>मूल्य: उच्च-कम</option>
                                <option>रेटिंग</option>
                                <option>नयाँ</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Hostels Grid -->
                @if($hostels->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6 mb-8">
                    @foreach($hostels as $hostel)
                        <!-- FINAL FIXED HOSTEL CARD - WITH COVER IMAGE BACKGROUND -->
                        @php
                            $mainImage = $hostel->images->first();
                            $avgRating = $hostel->reviews_avg_rating ?? 0;
                            $reviewCount = $hostel->reviews->count();
                            $minPrice = $hostel->rooms->min('price') ?? 0;
                            $availableRooms = $hostel->available_rooms_count ?? $hostel->rooms()->where('status', 'available')->count();
                            
                            // Get hostel image from owner panel - FIXED IMAGE VISIBILITY
                            $hostelImage = $hostel->image ? asset('storage/' . $hostel->image) : ($mainImage ? asset('storage/' . $mainImage->file_path) : asset('images/hostel-placeholder.jpg'));
                            
                            // Use cover_image if available, otherwise fallback to existing image logic
                            $coverImage = $hostel->cover_image ? asset('storage/' . $hostel->cover_image) : $hostelImage;
                        @endphp

                        <div class="group bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative min-h-[450px] flex flex-col">
                            <!-- Top Section with Cover Image as Background -->
                            <div class="h-48 relative overflow-hidden flex-shrink-0" style="background-image: url('{{ $coverImage }}'); background-size: cover; background-position: center;">
                                <!-- Minimal Overlay for Better Text Readability -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                
                                <!-- Badges on Image -->
                                <div class="absolute top-3 left-3 right-3 flex justify-between items-start">
                                    <!-- Premium Badge -->
                                    @if($availableRooms > 5)
                                    <span class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-yellow-500 text-black text-xs font-bold rounded-full shadow-lg flex items-center border border-yellow-300">
                                        <i class="fas fa-crown mr-1 text-xs"></i>
                                        PREMIUM
                                    </span>
                                    @else
                                    <div></div>
                                    @endif

                                    <!-- Rating Badge -->
                                    @if($avgRating > 0)
                                    <span class="px-3 py-1 bg-black/70 text-white text-sm font-bold rounded-full flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        {{ number_format($avgRating, 1) }}
                                        <span class="text-xs opacity-80 ml-1">({{ $reviewCount }})</span>
                                    </span>
                                    @endif
                                </div>

                                <!-- Location Badge -->
                                <div class="absolute bottom-3 left-3">
                                    <span class="px-3 py-1 bg-white/90 text-gray-800 text-sm font-semibold rounded-full nepali shadow-lg flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-blue-600 text-xs"></i>
                                        {{ $hostel->city }}
                                    </span>
                                </div>
                            </div>

                            <!-- Bottom Content Section - Light Background with Dark Text -->
                            <div class="p-4 flex flex-col flex-grow">
                                <!-- Title and Description -->
                                <div class="mb-3 flex-grow">
                                    <h3 class="text-lg font-bold text-gray-900 nepali mb-2 leading-tight">
                                        {{ $hostel->name }}
                                    </h3>
                                    
                                    <p class="text-gray-600 text-sm nepali leading-relaxed line-clamp-3 mb-3">
                                        {{ \Illuminate\Support\Str::limit($hostel->description, 100) }}
                                    </p>
                                </div>

                                <!-- Availability and Price Section - FIXED AVAILABILITY BADGE VISIBILITY -->
                                <div class="mb-4">
                                    <!-- Availability Badge - IMPROVED VISIBILITY -->
                                    <div class="mb-3">
                                        @if($availableRooms > 0)
                                        <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-bold rounded-lg nepali shadow-lg w-full justify-center">
                                            <i class="fas fa-key mr-2 text-sm"></i>
                                            {{ $availableRooms }} कोठा उपलब्ध
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-bold rounded-lg nepali shadow-lg w-full justify-center">
                                            <i class="fas fa-times mr-2 text-sm"></i>
                                            कोठा नभएको
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="text-center">
                                        @if($minPrice > 0)
                                        <span class="text-xl font-bold text-gray-900 nepali">रु. {{ number_format($minPrice) }}</span>
                                        <span class="text-gray-500 text-sm nepali block">सुरुवाती मूल्य</span>
                                        @else
                                        <span class="text-base font-semibold text-gray-500 nepali">मूल्य उपलब्ध छैन</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Three Button Layout - ALWAYS VISIBLE AT BOTTOM -->
                                <div class="mt-auto pt-3 border-t border-gray-100">
                                    <div class="grid grid-cols-3 gap-2">
                                        <!-- विवरण हेर्नुहोस् Button -->
                                        <a href="{{ route('hostels.show', $hostel->slug) }}" 
                                           class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white font-semibold rounded-lg transition-all duration-300 hover:bg-blue-700 hover:shadow-lg nepali text-xs text-center">
                                            <i class="fas fa-info-circle mr-1 text-xs"></i>
                                            पृष्ठ हेर्नुहोस्
                                        </a>

                                        <!-- कोठा हेर्नुहोस् Button -->
                                        <a href="{{ route('hostel.gallery', $hostel->slug) }}" 
                                           class="inline-flex items-center justify-center px-3 py-2 bg-purple-600 text-white font-semibold rounded-lg transition-all duration-300 hover:bg-purple-700 hover:shadow-lg nepali text-xs text-center">
                                            <i class="fas fa-images mr-1 text-xs"></i>
                                            कोठाहरू
                                        </a>

                                        <!-- बुक गर्नुहोस् Button -->
                                        @if($availableRooms > 0)
                                            @php
                                                $checkIn = request('check_in');
                                                $checkOut = request('check_out');
                                                $bookingUrl = route('hostel.book', $hostel->slug);
                                                
                                                if ($checkIn && $checkOut) {
                                                    $bookingUrl .= "?check_in={$checkIn}&check_out={$checkOut}";
                                                }
                                            @endphp
                                            
                                            <a href="{{ $bookingUrl }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white font-semibold rounded-lg transition-all duration-300 hover:bg-green-700 hover:shadow-lg nepali text-xs text-center">
                                                <i class="fas fa-calendar-check mr-1 text-xs"></i>
                                                बुक गर्नुहोस्
                                            </a>
                                        @else
                                        <button disabled
                                           class="inline-flex items-center justify-center px-3 py-2 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed nepali text-xs text-center">
                                            <i class="fas fa-times mr-1 text-xs"></i>
                                            नभएको
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Enhanced Pagination -->
                @if(method_exists($hostels, 'links') && $hostels->hasPages())
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6">
                    <div class="flex justify-center">
                        {{ $hostels->links() }}
                    </div>
                </div>
                @endif

                @else
                <!-- Enhanced Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-search text-blue-400 text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3 nepali">कुनै होस्टल फेला परेन</h3>
                        <p class="text-gray-600 mb-8 nepali text-lg leading-relaxed">
                            तपाईंको खोजी मिल्ने कुनै होस्टल उपलब्ध छैन। 
                            कृपया अरू स्थान वा होस्टल छनौट गर्नुहोस्।
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('home') }}" 
                               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl nepali">
                                <i class="fas fa-home mr-3"></i>
                                गृहपृष्ठमा जानुहोस्
                            </a>
                            <a href="{{ route('hostels.index') }}" 
                               class="inline-flex items-center px-8 py-4 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl nepali">
                                <i class="fas fa-building mr-3"></i>
                                सबै होस्टल हेर्नुहोस्
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.nepali {
    font-family: 'Preeti', 'Mangal', 'Arial', sans-serif;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced pagination styles */
.pagination {
    @apply flex justify-center items-center space-x-3;
}

.pagination .page-item {
    @apply inline-flex;
}

.pagination .page-link {
    @apply px-4 py-2 text-sm border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-300;
}

.pagination .page-item.active .page-link {
    @apply bg-gradient-to-r from-blue-600 to-purple-600 border-blue-600 text-white shadow-lg;
}

.pagination .page-item.disabled .page-link {
    @apply bg-gray-100 text-gray-400 cursor-not-allowed hover:bg-gray-100;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #2563eb, #7c3aed);
}

/* Improved text readability */
.text-gray-900 {
    color: #1f2937 !important;
}

.text-gray-600 {
    color: #4b5563 !important;
}

/* Flexible card height */
.min-h-\[450px\] {
    min-height: 450px;
}

/* Ensure buttons are always visible */
.mt-auto {
    margin-top: auto;
}

.flex-grow {
    flex-grow: 1;
}

.flex-shrink-0 {
    flex-shrink: 0;
}

/* Improved availability badge styling */
.bg-gradient-to-r.from-green-500.to-green-600 {
    background: linear-gradient(135deg, #10b981, #059669) !important;
}

.bg-gradient-to-r.from-red-500.to-red-600 {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
}

/* Sharp image rendering */
.h-48 .absolute.inset-0 {
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
}

/* Better image loading and display */
.bg-cover {
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
}
</style>

<!-- Enhanced JavaScript for Filters -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations
    const cards = document.querySelectorAll('.group');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Auto-submit form on filter change
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        const filterInputs = filterForm.querySelectorAll('select, input[type="text"], input[type="number"]');
        
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });

        // Checkbox auto-submit
        const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                filterForm.submit();
            });
        });

        // Radio auto-submit
        const radios = filterForm.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }
});

function resetFilters() {
    const url = new URL(window.location.href);
    // Remove all filter parameters except essential ones
    const preserveParams = ['city', 'check_in', 'check_out', 'hostel_id'];
    const paramsToRemove = [];
    
    url.searchParams.forEach((value, key) => {
        if (!preserveParams.includes(key)) {
            paramsToRemove.push(key);
        }
    });
    
    paramsToRemove.forEach(param => {
        url.searchParams.delete(param);
    });
    
    window.location.href = url.toString();
}

function removeFilter(filterType) {
    const url = new URL(window.location.href);
    
    if (filterType === 'city') {
        url.searchParams.delete('city');
    } else if (filterType === 'q') {
        url.searchParams.delete('q');
    } else if (filterType === 'dates') {
        url.searchParams.delete('check_in');
        url.searchParams.delete('check_out');
    } else if (filterType === 'price') {
        url.searchParams.delete('min_price');
        url.searchParams.delete('max_price');
    } else if (filterType === 'amenities') {
        url.searchParams.delete('amenities');
    } else if (filterType === 'hostel_type') {
        url.searchParams.delete('hostel_type');
    }
    
    window.location.href = url.toString();
}
</script>
@endsection