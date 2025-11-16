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
                                कोठा खोजी नतिजा
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
                            
                            @if(request('city') || request('q') || request('hostel_id') || request('check_in'))
                            <div class="flex flex-wrap gap-2">
                                @if(request('city'))
                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium shadow-sm nepali">
                                    <i class="fas fa-map-marker-alt mr-2 text-xs"></i>
                                    {{ request('city') }}
                                    <button class="ml-2 hover:bg-blue-700 rounded-full w-4 h-4 flex items-center justify-center" onclick="removeFilter('city')">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </span>
                                @endif
                                
                                @if(request('q'))
                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium shadow-sm nepali">
                                    <i class="fas fa-search mr-2 text-xs"></i>
                                    "{{ request('q') }}"
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
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Enhanced Modify Search Button -->
                    <a href="{{ url()->previous() }}" 
                       class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl nepali">
                        <i class="fas fa-edit mr-3"></i>
                        खोजी परिवर्तन गर्नुहोस्
                    </a>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Advanced Filter Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 nepali border-b border-gray-200 pb-3">फिल्टरहरू</h3>
                    
                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-800 mb-3 nepali">मूल्य दायरा</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="price-all" name="price" class="mr-3 text-blue-600 focus:ring-blue-500" checked>
                                <label for="price-all" class="text-gray-700 nepali text-sm">सबै मूल्य</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="price-budget" name="price" class="mr-3 text-blue-600 focus:ring-blue-500">
                                <label for="price-budget" class="text-gray-700 nepali text-sm">बजेट (रु. ५,००० - ८,०००)</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="price-mid" name="price" class="mr-3 text-blue-600 focus:ring-blue-500">
                                <label for="price-mid" class="text-gray-700 nepali text-sm">मध्यम (रु. ८,००० - १२,०००)</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="price-premium" name="price" class="mr-3 text-blue-600 focus:ring-blue-500">
                                <label for="price-premium" class="text-gray-700 nepali text-sm">प्रिमियम (रु. १२,०००+)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Room Type -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-800 mb-3 nepali">कोठाको प्रकार</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="type-1" class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                <label for="type-1" class="text-gray-700 nepali text-sm">१ सिटर</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="type-2" class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                <label for="type-2" class="text-gray-700 nepali text-sm">२ सिटर</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="type-3" class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                <label for="type-3" class="text-gray-700 nepali text-sm">३ सिटर</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="type-4" class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                <label for="type-4" class="text-gray-700 nepali text-sm">४ सिटर</label>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-800 mb-3 nepali">सुविधाहरू</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="wifi" class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                <label for="wifi" class="text-gray-700 nepali text-sm">वाईफाई</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="water" class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                <label for="water" class="text-gray-700 nepali text-sm">२४/७ पानी</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="security" class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                <label for="security" class="text-gray-700 nepali text-sm">सुरक्षा गार्ड</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="cctv" class="mr-3 text-blue-600 focus:ring-blue-500 rounded">
                                <label for="cctv" class="text-gray-700 nepali text-sm">CCTV</label>
                            </div>
                        </div>
                    </div>

                    <!-- Hostel Type -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-800 mb-3 nepali">होस्टल प्रकार</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="type-all" name="hostel_type" class="mr-3 text-blue-600 focus:ring-blue-500" checked>
                                <label for="type-all" class="text-gray-700 nepali text-sm">सबै प्रकार</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="type-boys" name="hostel_type" class="mr-3 text-blue-600 focus:ring-blue-500">
                                <label for="type-boys" class="text-gray-700 nepali text-sm">ब्वाइज होस्टल</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="type-girls" name="hostel_type" class="mr-3 text-blue-600 focus:ring-blue-500">
                                <label for="type-girls" class="text-gray-700 nepali text-sm">गर्ल्स होस्टल</label>
                            </div>
                        </div>
                    </div>

                    <button class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white py-3 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg nepali">
                        फिल्टर लागू गर्नुहोस्
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Sorting Options -->
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-4 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center mb-3 sm:mb-0">
                            <span class="text-gray-600 mr-3 nepali">क्रमबद्ध गर्नुहोस्:</span>
                            <select class="border border-gray-300 rounded-lg px-4 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 nepali">
                                <option>सिफारिस गरिएको</option>
                                <option>मूल्य: कम-उच्च</option>
                                <option>मूल्य: उच्च-कम</option>
                                <option>रेटिंग</option>
                                <option>नजिकको</option>
                            </select>
                        </div>
                        <div class="flex items-center">
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
                            <a href="{{ url()->previous() }}" 
                               class="inline-flex items-center px-8 py-4 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl nepali">
                                <i class="fas fa-arrow-left mr-3"></i>
                                फेरि खोजी गर्नुहोस्
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
</style>

<!-- JavaScript for interactive elements -->
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

    // Filter functionality
    const filterButtons = document.querySelectorAll('input[type="radio"], input[type="checkbox"]');
    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            // Add filter logic here
            console.log('Filter changed:', this.id, this.checked);
        });
    });
});

function removeFilter(filterType) {
    // Remove filter logic
    console.log('Removing filter:', filterType);
}
</script>
@endsection