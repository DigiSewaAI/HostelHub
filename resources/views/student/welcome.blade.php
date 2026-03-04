@extends('layouts.student')

@section('title', 'स्वागत छ - HostelHub')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- ✅ TOP WELCOME CARD -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 sm:px-10 sm:py-10">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2 nepali">
                            @php
                                $student = auth()->user()->student;
                                $hostelName = optional($student)->hostel ? $student->hostel->name : 'HostelHub';
                            @endphp
                            @if($hostelName !== 'HostelHub')
                                {{ $hostelName }} मा स्वागत छ, {{ auth()->user()->name }}!
                            @else
                                HostelHub मा स्वागत छ, {{ auth()->user()->name }}!
                            @endif
                        </h1>
                        <p class="text-blue-100 text-sm sm:text-base nepali">
                            <i class="fas fa-check-circle mr-2"></i>
                            तपाईं सफलतापूर्वक लगइन हुनु भएको छ।
                        </p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-smile text-4xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optional: Warning if no hostel joined -->
            @if(!$student || !$student->hostel_id)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 nepali">
                            ⚠️ होस्टल join नहुदासम्म तपाईंको ड्यासबोर्ड पुरै सक्रिय हुने छैन। कृपया तलको बटन प्रयोग गरी होस्टल खोज्नुहोस्।
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- ✅ BOOKING SUMMARY (if any) -->
        @if(isset($bookings) && $bookings->count() > 0)
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 border border-gray-100">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    तपाईंका बुकिंगहरू
                    <span class="ml-2 bg-white text-purple-600 text-xs px-2 py-1 rounded-full">
                        {{ $bookings->count() }} बुकिंग
                    </span>
                </h3>
            </div>
            <div class="p-6">
                <!-- तपाईंको बुकिंग तथ्याङ्क र सूची -->
                <p class="text-gray-600 nepali">तपाईंसँग {{ $bookings->count() }} वटा बुकिंग छन्।</p>
            </div>
        </div>
        @endif

        <!-- ✅ MAIN ACTION CARD -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="m-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 text-xl"></i>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- Hero Section with Illustration -->
            <div class="px-6 py-10 sm:p-10 text-center">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-6 shadow-inner">
                    <i class="fas fa-building text-4xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 nepali">
                    आफ्नो सपनाको होस्टल खोज्नुहोस्
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto mb-8 nepali text-sm sm:text-base">
                    @if(isset($isStudent) && $isStudent && isset($studentProfile) && $studentProfile && $studentProfile->hostel_id && isset($hostelName))
                        तपाईं {{ $hostelName }} सँग जडान हुनुभएको छ। 
                        तलको बटनबाट अन्य होस्टल हेर्न सक्नुहुन्छ।
                    @elseif(isset($bookings) && $bookings->count() > 0)
                        तपाईंसँग {{ $bookings->count() }} वटा बुकिंग छन्। 
                        नयाँ बुकिंग गर्न तलको बटन प्रयोग गर्नुहोस्।
                    @else
                        तपाईंको खातामा स्वागत छ। 
                        होस्टलमा बुकिंग गर्नका लागि तलको बटनमा क्लिक गर्नुहोस्।
                    @endif
                </p>

                <!-- Search Button – Perfect Size -->
                <div class="flex justify-center">
                    <a href="{{ route('hostels.index') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-base sm:text-lg">
                        <i class="fas fa-search mr-3 text-xl"></i>
                        <span class="nepali">होस्टल खोज्नुहोस्</span>
                    </a>
                </div>

                <!-- Quick Tips -->
                <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-4 text-left">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 nepali">शहर छान्नुहोस्</h4>
                        </div>
                        <p class="text-xs text-gray-500 nepali">तपाईंको मनपर्ने शहरको होस्टल हेर्नुहोस्।</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-bed text-purple-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 nepali">कोठा छान्नुहोस्</h4>
                        </div>
                        <p class="text-xs text-gray-500 nepali">एकल, दोहोरो वा साझा कोठा उपलब्ध।</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-check text-green-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 nepali">बुक गर्नुहोस्</h4>
                        </div>
                        <p class="text-xs text-gray-500 nepali">सजिलै बुक गर्नुहोस् र आफ्नो ठाउँ सुरक्षित गर्नुहोस्।</p>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 nepali">
                        <i class="fas fa-question-circle mr-1 text-indigo-400"></i>
                        कुनै प्रश्न छ? 
                        <a href="{{ route('contact') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-150">
                            सम्पर्क गर्नुहोस्
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-refresh script (if needed) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success/error messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('[class*="bg-"].border');
        alerts.forEach(alert => {
            if (alert.classList.contains('bg-green-50') || alert.classList.contains('bg-red-50')) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);

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

<!-- Nepali font style (if not already in layout) -->
<style>
    .nepali {
        font-family: 'Preeti', 'Mangal', 'Arial', sans-serif;
    }
</style>
@endsection