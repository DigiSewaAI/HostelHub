@extends('layouts.frontend')

@section('page-title', $hostel->name)
@section('page-description', $hostel->description ? \Illuminate\Support\Str::limit($hostel->description, 160) : 'होस्टलको विवरण')

@section('content')
<div class="min-h-screen bg-gray-50">
    @if(isset($preview) && $preview)
        <div class="bg-yellow-500 text-white py-2 px-4 text-center">
            <i class="fas fa-eye mr-2"></i>
            <span class="nepali">यो पूर्वावलोकन मोडमा हो। सार्वजनिक रूपमा यो पृष्ठ {{ $hostel->is_published ? 'उपलब्ध छ' : 'उपलब्ध छैन' }}</span>
        </div>
    @endif

    <!-- Hero Section with Custom Theme Color -->
    <div class="relative py-12" style="background: {{ $hostel->theme_color ?? '#3b82f6' }};">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center space-x-6 text-white">
                    @if($hostel->logo_path)
                        <img src="{{ asset('storage/' . $hostel->logo_path) }}" alt="{{ $hostel->name }}" 
                             class="h-20 w-20 rounded-full border-4 border-white shadow-lg object-cover">
                    @else
                        <div class="h-20 w-20 rounded-full border-4 border-white shadow-lg bg-white bg-opacity-20 flex items-center justify-center">
                            <i class="fas fa-building text-2xl text-white"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-3xl font-bold nepali">{{ $hostel->name }}</h1>
                        <div class="flex items-center space-x-4 mt-2">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span class="nepali">{{ $hostel->city ?? 'काठमाडौं' }}</span>
                            </div>
                            @if($hostel->approved_reviews_count > 0)
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span>{{ number_format($hostel->approvedReviews()->avg('rating') ?? 0, 1) }}</span>
                                    <span class="ml-1 nepali">({{ $hostel->approved_reviews_count }} समीक्षा)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($hostel->available_rooms > 0)
                    <div class="mt-4 md:mt-0 bg-white rounded-lg p-4 text-center shadow-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $hostel->available_rooms }}</div>
                        <div class="text-sm text-gray-600 nepali">कोठा उपलब्ध छन्</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About Section -->
                <section class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 nepali">हाम्रो बारेमा</h2>
                    <div class="prose max-w-none">
                        @if($hostel->description)
                            <p class="text-gray-700 leading-relaxed nepali whitespace-pre-line">
                                {{ $hostel->description }}
                            </p>
                        @else
                            <p class="text-gray-500 italic nepali">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
                        @endif
                    </div>
                    
                    <!-- Facilities -->
                    @php
    // Convert string to array if necessary
    $facilities = $hostel->facilities;
    if (is_string($facilities)) {
        $facilities = array_map('trim', explode(',', $facilities));
    }
@endphp

@if(!empty($facilities) && count($facilities) > 0)
    <div class="mt-6">
        <h3 class="text-lg font-medium text-gray-800 mb-3 nepali">सुविधाहरू</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($facilities as $facility)
                <div class="flex items-center space-x-2 text-gray-700">
                    <i class="fas fa-check text-green-500"></i>
                    <span class="nepali">{{ $facility }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

                </section>

                <!-- Meal Plans Section -->
                <section class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 nepali">खानाको योजना</h2>
                    @if($hostel->mealMenus && $hostel->mealMenus->count() > 0)
                        <div class="space-y-4">
                            @foreach($hostel->mealMenus as $mealMenu)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 nepali">{{ $mealMenu->name }}</h3>
                                    <p class="text-gray-600 nepali">{{ $mealMenu->description }}</p>
                                    <div class="mt-2 text-sm text-gray-500">
                                        <span class="nepali">मूल्य: रु {{ number_format($mealMenu->price) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic nepali">खानाको योजना उपलब्ध छैन।</p>
                    @endif
                </section>

                <!-- Reviews Section -->
                <section class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800 nepali">विद्यार्थी समीक्षाहरू</h2>
                        <div class="text-sm text-gray-600 nepali">
                            {{ $hostel->approved_reviews_count ?? 0 }} समीक्षाहरू
                        </div>
                    </div>

                    @if($hostel->approvedReviews && $hostel->approvedReviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($hostel->approvedReviews->take(5) as $review)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-800 nepali">
                                                {{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}
                                            </h4>
                                            <div class="flex items-center space-x-1 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500">
                                            {{ $review->created_at->format('Y-m-d') }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-700 mb-3 nepali">{{ $review->comment }}</p>
                                    
                                    @if($review->reply)
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                                            <div class="flex items-start space-x-2">
                                                <i class="fas fa-reply text-blue-500 mt-1"></i>
                                                <div>
                                                    <strong class="text-blue-800 nepali">होस्टलको जवाफ:</strong>
                                                    <p class="text-blue-700 mt-1 nepali">{{ $review->reply }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-comment-slash text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-600 nepali">अहिलेसम्म कुनै समीक्षा छैन।</p>
                        </div>
                    @endif
                </section>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 nepali">सम्पर्क जानकारी</h3>
                    <div class="space-y-3">
                        @if($hostel->contact_person)
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-user text-blue-500"></i>
                                <span class="text-gray-700 nepali">{{ $hostel->contact_person }}</span>
                            </div>
                        @endif
                        
                        @if($hostel->contact_phone)
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-phone text-blue-500"></i>
                                <a href="tel:{{ $hostel->contact_phone }}" class="text-gray-700 hover:text-blue-600">
                                    {{ $hostel->contact_phone }}
                                </a>
                            </div>
                        @endif
                        
                        @if($hostel->contact_email)
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-blue-500"></i>
                                <a href="mailto:{{ $hostel->contact_email }}" class="text-gray-700 hover:text-blue-600">
                                    {{ $hostel->contact_email }}
                                </a>
                            </div>
                        @endif
                        
                        @if($hostel->address)
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-map-marker-alt text-blue-500 mt-1"></i>
                                <span class="text-gray-700 nepali">{{ $hostel->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 nepali">होस्टल तथ्याङ्क</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-xl font-bold text-blue-600">{{ $hostel->total_rooms ?? 0 }}</div>
                            <div class="text-sm text-gray-600 nepali">कुल कोठा</div>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-xl font-bold text-green-600">{{ $hostel->available_rooms ?? 0 }}</div>
                            <div class="text-sm text-gray-600 nepali">उपलब्ध कोठा</div>
                        </div>
                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                            <div class="text-xl font-bold text-purple-600">{{ $hostel->students_count ?? 0 }}</div>
                            <div class="text-sm text-gray-600 nepali">विद्यार्थी</div>
                        </div>
                        <div class="text-center p-3 bg-orange-50 rounded-lg">
                            <div class="text-xl font-bold text-orange-600">{{ $hostel->approved_reviews_count ?? 0 }}</div>
                            <div class="text-sm text-gray-600 nepali">समीक्षा</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 nepali">अन्य कार्यहरू</h3>
                    <div class="space-y-3">
                        @if(isset($preview) && $preview)
                            <a href="{{ route('owner.public-page.edit') }}" 
                               class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center nepali">
                                <i class="fas fa-edit mr-2"></i>सम्पादन गर्नुहोस्
                            </a>
                        @endif
                        <a href="{{ route('contact') }}" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center nepali">
                            <i class="fas fa-envelope mr-2"></i>सम्पर्क गर्नुहोस्
                        </a>
                        <a href="{{ route('hostels.index') }}" class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center nepali">
                            <i class="fas fa-arrow-left mr-2"></i>अन्य होस्टलहरू हेर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.whitespace-pre-line {
    white-space: pre-line;
}
</style>