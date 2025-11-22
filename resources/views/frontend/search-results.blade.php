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
                                request('min_price') || request('room_type') || request('amenities') || request('hostel_type') ||
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

                                @if(request('room_type') && is_array(request('room_type')))
                                    @foreach(request('room_type') as $roomType)
                                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600 text-white text-sm font-medium shadow-sm nepali">
                                        <i class="fas fa-bed mr-2 text-xs"></i>
                                        {{ $roomType }}
                                        <button class="ml-2 hover:bg-indigo-700 rounded-full w-4 h-4 flex items-center justify-center" onclick="removeFilter('room_type')">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </span>
                                    @endforeach
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
                        
                        <!-- TEMPORARY FIX: Comment out price range until we have proper room data -->
                        <!-- 
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-800 mb-3 nepali">मूल्य दायरा (रु. प्रति महिना)</h4>
                            <div class="space-y-3">
                                <div class="flex gap-2">
                                    <input type="number" name="min_price" 
                                           value="{{ request('min_price') ?? ($searchFilters['min_price'] ?? '') }}"
                                           placeholder="न्यूनतम" 
                                           class="w-1/2 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <input type="number" name="max_price" 
                                           value="{{ request('max_price') ?? ($searchFilters['max_price'] ?? '') }}"
                                           placeholder="अधिकतम" 
                                           class="w-1/2 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="radio" id="price-budget" name="price_preset" value="budget" class="mr-3 text-blue-600 focus:ring-blue-500">
                                        <label for="price-budget" class="text-gray-700 nepali text-sm">बजेट (रु. ५,००० - ८,०००)</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="price-mid" name="price_preset" value="mid" class="mr-3 text-blue-600 focus:ring-blue-500">
                                        <label for="price-mid" class="text-gray-700 nepali text-sm">मध्यम (रु. ८,००० - १२,०००)</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="price-premium" name="price_preset" value="premium" class="mr-3 text-blue-600 focus:ring-blue-500">
                                        <label for="price-premium" class="text-gray-700 nepali text-sm">प्रिमियम (रु. १२,०००+)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->

                        <!-- Room Type Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-800 mb-3 nepali">कोठाको प्रकार</h4>
                            <div class="space-y-2">
                                @if(isset($roomTypes) && $roomTypes->count() > 0)
                                    @foreach($roomTypes as $roomType)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="type-{{ $roomType['value'] }}" 
                                               name="room_type[]" value="{{ $roomType['value'] }}"
                                               {{ in_array($roomType['value'], request('room_type', $searchFilters['room_type'] ?? [])) ? 'checked' : '' }}
                                               class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                        <label for="type-{{ $roomType['value'] }}" class="text-gray-700 nepali text-sm">
                                            {{ $roomType['label'] }}
                                        </label>
                                    </div>
                                    @endforeach
                                @else
                                    <!-- Fallback room types -->
                                    <div class="flex items-center">
                                        <input type="checkbox" id="type-1" name="room_type[]" value="1 seater" 
                                               {{ in_array('1 seater', request('room_type', $searchFilters['room_type'] ?? [])) ? 'checked' : '' }}
                                               class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                        <label for="type-1" class="text-gray-700 nepali text-sm">१ सिटर</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="type-2" name="room_type[]" value="2 seater" 
                                               {{ in_array('2 seater', request('room_type', $searchFilters['room_type'] ?? [])) ? 'checked' : '' }}
                                               class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                        <label for="type-2" class="text-gray-700 nepali text-sm">२ सिटर</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="type-3" name="room_type[]" value="3 seater" 
                                               {{ in_array('3 seater', request('room_type', $searchFilters['room_type'] ?? [])) ? 'checked' : '' }}
                                               class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                        <label for="type-3" class="text-gray-700 nepali text-sm">३ सिटर</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="type-4" name="room_type[]" value="4 seater" 
                                               {{ in_array('4 seater', request('room_type', $searchFilters['room_type'] ?? [])) ? 'checked' : '' }}
                                               class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                        <label for="type-4" class="text-gray-700 nepali text-sm">४ सिटर</label>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Amenities Filter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-800 mb-3 nepali">सुविधाहरू</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="wifi" name="amenities[]" value="wifi" 
                                           {{ in_array('wifi', request('amenities', $searchFilters['amenities'] ?? [])) ? 'checked' : '' }}
                                           class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                    <label for="wifi" class="text-gray-700 nepali text-sm">वाईफाई</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="water" name="amenities[]" value="24/7 water" 
                                           {{ in_array('24/7 water', request('amenities', $searchFilters['amenities'] ?? [])) ? 'checked' : '' }}
                                           class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                    <label for="water" class="text-gray-700 nepali text-sm">२४/७ पानी</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="security" name="amenities[]" value="security" 
                                           {{ in_array('security', request('amenities', $searchFilters['amenities'] ?? [])) ? 'checked' : '' }}
                                           class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                    <label for="security" class="text-gray-700 nepali text-sm">सुरक्षा गार्ड</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="cctv" name="amenities[]" value="CCTV" 
                                           {{ in_array('CCTV', request('amenities', $searchFilters['amenities'] ?? [])) ? 'checked' : '' }}
                                           class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                    <label for="cctv" class="text-gray-700 nepali text-sm">CCTV</label>
                                </div>
                            </div>
                        </div>

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
                        <a href="{{ route('home') }}#gallery" 
                           class="block w-full text-center bg-green-50 border border-green-200 text-green-700 py-2 rounded-lg font-semibold hover:bg-green-100 transition-colors nepali">
                            ग्यालरी हेर्नुहोस्
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
                        <div class="flex items-center mt-3 sm:mt-0">
                            <span class="text-gray-600 mr-3 nepali">दृश्य:</span>
                            <div class="flex space-x-2">
                                <button class="p-2 rounded-lg bg-blue-100 text-blue-600">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button class="p-2 rounded-lg text-gray-400 hover:bg-gray-100">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Hostels Grid -->
                @if($hostels->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6 mb-8">
                    @foreach($hostels as $hostel)
                        @include('frontend.partials.hostel-card', ['hostel' => $hostel])
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
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

/* FIXED: GOLDEN PREMIUM BADGE STYLING */
.absolute.top-4.right-4.z-20 span {
    background: linear-gradient(135deg, #FFD700, #FFA500) !important;
    color: #000000 !important;
    font-weight: 800 !important;
    padding: 6px 12px !important;
    border-radius: 20px !important;
    font-size: 11px !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    border: 2px solid #FFD700 !important;
    box-shadow: 0 4px 12px rgba(255, 215, 0, 0.5) !important;
    text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7) !important;
}

/* Crown icon color for premium badge */
.absolute.top-4.right-4.z-20 span i {
    color: #000000 !important;
    text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7) !important;
}

/* FIXED: Book button styling - ensure text is visible */
a[href*="book-room"] {
    background: linear-gradient(135deg, #059669, #047857) !important;
    color: white !important;
    border: none !important;
    font-weight: 600 !important;
}

a[href*="book-room"]:hover {
    background: linear-gradient(135deg, #047857, #065f46) !important;
    color: white !important;
}

/* FIXED: View button styling */
a[href*="hostels.show"] {
    background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
    color: white !important;
    border: none !important;
    font-weight: 600 !important;
}

a[href*="hostels.show"]:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af) !important;
    color: white !important;
}

/* FIXED: Button container spacing */
.flex.space-y-2 > * {
    margin-bottom: 8px;
}

/* FIXED: Ensure all buttons have proper contrast */
button, a {
    color: inherit !important;
}

/* FIXED: Specific fix for the book now button text visibility */
.bg-gradient-to-r.from-green-500.to-green-600,
.bg-gradient-to-r.from-green-600.to-green-700 {
    color: white !important;
    font-weight: 600 !important;
}

.bg-gradient-to-r.from-blue-600.to-blue-700,
.bg-gradient-to-r.from-blue-700.to-blue-800 {
    color: white !important;
    font-weight: 600 !important;
}

/* FIXED: Button text visibility override */
.text-white {
    color: white !important;
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

    // Fix button text visibility
    const bookButtons = document.querySelectorAll('a[href*="book-room"]');
    bookButtons.forEach(button => {
        button.style.color = 'white';
        button.style.fontWeight = '600';
    });

    const viewButtons = document.querySelectorAll('a[href*="hostels.show"]');
    viewButtons.forEach(button => {
        button.style.color = 'white';
        button.style.fontWeight = '600';
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
    } else if (filterType === 'room_type') {
        url.searchParams.delete('room_type');
    } else if (filterType === 'amenities') {
        url.searchParams.delete('amenities');
    } else if (filterType === 'hostel_type') {
        url.searchParams.delete('hostel_type');
    }
    
    window.location.href = url.toString();
}
</script>
@endsection