@extends('layouts.frontend')

@section('page-title', 'होस्टलहरू')
@section('page-description', 'नेपालका विभिन्न होस्टलहरूको सूची। आफ्नो लागि उपयुक्त होस्टल खोज्नुहोस्।')

@push('styles')
@vite(['resources/css/public-themes.css'])
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Search Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-6">
            <form method="GET" action="{{ route('hostels.index') }}" class="space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="होस्टलको नाम, सहर, वा विवरण खोज्नुहोस्..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 nepali">
                    </div>
                    
                    <!-- City Filter -->
                    <select name="city" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">सबै सहरहरू</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                    
                    <!-- Search Button -->
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors nepali">
                        <i class="fas fa-search mr-2"></i>खोज्नुहोस्
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Results Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 nepali">
                होस्टलहरू 
                @if(request('search') || request('city'))
                    <span class="text-lg font-normal text-gray-600">
                        ({{ $hostels->total() }} परिणाम)
                    </span>
                @endif
            </h1>
        </div>

        @if($hostels->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($hostels as $hostel)
                    @php
                        $hostelTheme = $hostel->theme_color ?? '#3b82f6';
                        $hostelAvgRating = $hostel->approvedReviews()->avg('rating') ?? 0;
                        $hostelReviewCount = $hostel->approvedReviews()->count();
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Hostel Image/Logo -->
                        <div class="h-48 bg-gray-200 relative" style="background: {{ $hostelTheme }};">
                            @if($hostel->logo_path)
                                <img src="{{ asset('storage/' . $hostel->logo_path) }}" alt="{{ $hostel->name }}" 
                                     class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <i class="fas fa-building text-white text-4xl"></i>
                                </div>
                            @endif
                            @if($hostel->available_rooms > 0)
                                <span class="absolute top-3 right-3 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    {{ $hostel->available_rooms }} कोठा उपलब्ध
                                </span>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 nepali">{{ $hostel->name }}</h3>
                                @if($hostelReviewCount > 0)
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span class="text-sm font-medium">
                                            {{ number_format($hostelAvgRating, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt text-sm mr-1"></i>
                                <span class="text-sm nepali">{{ $hostel->city ?? 'काठमाडौं' }}</span>
                            </div>
                            
                            @if($hostel->description)
                                <p class="text-gray-700 text-sm mb-4 line-clamp-2 nepali">
                                    {{ \Illuminate\Support\Str::limit($hostel->description, 120) }}
                                </p>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <a href="{{ route('hostels.show', $hostel->slug) }}" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm nepali">
                                    विवरण हेर्नुहोस्
                                </a>
                                
                                @if($hostelReviewCount > 0)
                                    <span class="text-xs text-gray-500 nepali">
                                        {{ $hostelReviewCount }} समीक्षा
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $hostels->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-building text-gray-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2 nepali">कुनै होस्टल फेला परेन</h3>
                <p class="text-gray-500 mb-4 nepali">
                    @if(request('search') || request('city'))
                        तपाईंको खोजका लागि कुनै होस्टल फेला परेन। कृपया अर्को खोज प्रयास गर्नुहोस्।
                    @else
                        अहिले कुनै होस्टल उपलब्ध छैन।
                    @endif
                </p>
                <a href="{{ route('hostels.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors nepali">
                    सबै होस्टल हेर्नुहोस्
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection