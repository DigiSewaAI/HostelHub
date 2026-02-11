@extends('layouts.student')

@section('title', 'स्वागत छ - HostelHub')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8">
        <div class="text-center">
            @php
                $student = auth()->user()->student;
                $hostelName = optional($student)->hostel ? $student->hostel->name : 'HostelHub';
            @endphp
            
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                @if($hostelName !== 'HostelHub')
                    {{ $hostelName }} मा स्वागत छ, {{ auth()->user()->name }}!
                @else
                    स्वागत छ, {{ auth()->user()->name }}!
                @endif
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                तपाईं सफलतापूर्वक लगइन हुनु भएको छ।
            </p>
        </div>

        <!-- ✅ UPDATED: Comprehensive Booking Summary Section -->
        @if(isset($bookings) && $bookings->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    तपाईंका बुकिंगहरू
                    <span class="ml-2 bg-white text-blue-600 text-sm px-2 py-1 rounded-full">
                        {{ $bookings->count() }} बुकिंग
                    </span>
                </h3>
            </div>

            <div class="p-6">
                <!-- Booking Statistics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $bookings->count() }}</div>
                        <div class="text-sm text-blue-800">कुल बुकिंग</div>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $pendingCount ?? 0 }}</div>
                        <div class="text-sm text-yellow-800">पेन्डिङ</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $bookings->where('status', 'approved')->count() }}</div>
                        <div class="text-sm text-green-800">स्वीकृत</div>
                    </div>
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $bookings->where('status', 'rejected')->count() }}</div>
                        <div class="text-sm text-red-800">अस्वीकृत</div>
                    </div>
                </div>

                <!-- Bookings List -->
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200
                        {{ $booking->status === 'approved' ? 'bg-green-50 border-green-200' : '' }}
                        {{ $booking->status === 'rejected' ? 'bg-red-50 border-red-200' : '' }}
                        {{ $booking->status === 'pending' ? 'bg-yellow-50 border-yellow-200' : '' }}">
                        
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <!-- Booking Info -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-lg text-gray-900">
                                        {{ $booking->hostel->name ?? 'होस्टल' }}
                                        @if($booking->is_guest_booking)
                                        <span class="ml-2 text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">गेस्ट बुकिंग</span>
                                        @endif
                                    </h4>
                                    <span class="text-sm font-medium px-2 py-1 rounded 
                                        {{ $booking->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ $booking->status === 'approved' ? '✅ स्वीकृत' : '' }}
                                        {{ $booking->status === 'rejected' ? '❌ अस्वीकृत' : '' }}
                                        {{ $booking->status === 'pending' ? '⏳ पेन्डिङ' : '' }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-door-open mr-2 text-blue-500"></i>
                                        कोठा: {{ $booking->room->type ?? 'निर्धारित हुन बाँकी' }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-day mr-2 text-green-500"></i>
                                        चेक-इन: {{ $booking->check_in_date->format('Y-m-d') }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-money-bill-wave mr-2 text-purple-500"></i>
                                        रकम: रु {{ number_format($booking->amount ?? 0) }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-gray-500"></i>
                                        बुकिंग: {{ $booking->created_at->format('Y-m-d') }}
                                    </div>
                                </div>

                                @if($booking->status === 'rejected' && $booking->rejection_reason)
                                <div class="mt-2 p-2 bg-red-100 border border-red-200 rounded">
                                    <p class="text-sm text-red-700">
                                        <strong>कारण:</strong> {{ $booking->rejection_reason }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-3 md:mt-0 md:ml-4 flex flex-col space-y-2">
                                @if($booking->is_guest_booking && !$booking->user_id)
                                <form method="POST" action="{{ route('user.convert-booking', $booking->id) }}">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition duration-150 flex items-center justify-center">
                                        <i class="fas fa-link mr-2"></i>खातामा जोड्नुहोस्
                                    </button>
                                </form>
                                @elseif($booking->is_guest_booking && $booking->user_id)
                                <span class="px-3 py-2 bg-blue-100 text-blue-700 text-sm rounded text-center">
                                    <i class="fas fa-check mr-1"></i>जोडिएको
                                </span>
                                @endif

                                <a href="{{ route('bookings.show', $booking->id) }}" 
                                    class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition duration-150 flex items-center justify-center">
                                    <i class="fas fa-eye mr-2"></i>विवरण हेर्नुहोस्
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('bookings.my') }}" 
                        class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 text-center font-medium">
                        <i class="fas fa-list mr-2"></i>सबै बुकिंग हेर्नुहोस्
                    </a>
                    
                    @if($pendingCount > 0 && !$isStudent)
                    <a href="{{ route('student.quick-register') }}" 
                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-150 text-center font-medium">
                        <i class="fas fa-user-graduate mr-2"></i>त्वरित विद्यार्थी दर्ता
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- ✅ UPDATED: Main Content Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 text-xl"></i>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- Welcome Message Section -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">खातामा स्वागत छ</h3>
                
                <p class="text-gray-600 max-w-2xl mx-auto">
    @if(isset($isStudent) && $isStudent && isset($studentProfile) && $studentProfile && $studentProfile->hostel_id && isset($hostelName))
        तपाईं {{ $hostelName }} सँग जडान हुनुभएको छ। 
        तलका विकल्पहरूबाट आफ्नो होस्टल अनुभव सुरु गर्नुहोस्:
    @elseif(isset($bookings) && $bookings->count() > 0)
        तपाईंसँग {{ $bookings->count() }} वटा बुकिंग छन्। 
        तपाईंका बुकिंगहरू माथि देखाइएका छन्।
    @else
        तपाईंको खातामा स्वागत छ। 
        होस्टलमा बुकिंग गर्नका लागि तलका विकल्पहरू छन्:
    @endif
</p>

            </div>

            <!-- Action Buttons Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                <!-- Hostel Search -->
                <a href="{{ route('student.hostel.search') }}" 
                   class="flex items-center justify-center p-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 transition duration-150 group">
                    <div class="text-center">
                        <i class="fas fa-search text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <div>होस्टल खोज्नुहोस्</div>
                    </div>
                </a>

                <!-- Use Hostel Code -->
                <a href="{{ route('student.hostel.join') }}" 
                   class="flex items-center justify-center p-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 group">
                    <div class="text-center">
                        <i class="fas fa-key text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <div>होस्टल कोड प्रयोग गर्नुहोस्</div>
                    </div>
                </a>

                <!-- Dashboard -->
                <a href="{{ route('student.dashboard') }}" 
                   class="flex items-center justify-center p-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition duration-150 group">
                    <div class="text-center">
                        <i class="fas fa-tachometer-alt text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <div>ड्यासबोर्डमा जानुहोस्</div>
                    </div>
                </a>

                <!-- Profile -->
                <a href="{{ route('student.profile') }}" 
                   class="flex items-center justify-center p-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-purple-600 hover:bg-purple-700 transition duration-150 group">
                    <div class="text-center">
                        <i class="fas fa-user text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <div>प्रोफाइल</div>
                    </div>
                </a>

                <!-- Payments -->
                <a href="{{ route('student.payments.index') }}" 
                   class="flex items-center justify-center p-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700 transition duration-150 group">
                    <div class="text-center">
                        <i class="fas fa-credit-card text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <div>भुक्तानी</div>
                    </div>
                </a>

                <!-- Meal Plan -->
                <a href="{{ route('student.meal-menus') }}" 
                   class="flex items-center justify-center p-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gray-700 hover:bg-gray-800 transition duration-150 group">
                    <div class="text-center">
                        <i class="fas fa-utensils text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <div>खानाको योजना</div>
                    </div>
                </a>
            </div>

            <!-- Contact Section -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    कुनै प्रश्न छ? 
                    <a href="{{ route('contact') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-150">
                        <i class="fas fa-headset mr-1"></i>सम्पर्क गर्नुहोस्
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ✅ UPDATED: Auto-refresh for booking updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success/error messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('[class*="bg-"]');
        alerts.forEach(alert => {
            if (alert.classList.contains('bg-green-50') || alert.classList.contains('bg-red-50')) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);

    // Check for new bookings every 30 seconds if user has guest bookings
    @if(isset($pendingCount) && $pendingCount > 0)
    setInterval(() => {
        fetch('{{ route("check.bookings") }}')
            .then(response => response.json())
            .then(data => {
                if (data.has_pending && data.pending_count !== {{ $pendingCount }}) {
                    location.reload();
                }
            });
    }, 30000);
    @endif
});
</script>

@endsection