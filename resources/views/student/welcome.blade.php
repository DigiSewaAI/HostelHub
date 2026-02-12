@extends('layouts.student')

@section('title', 'स्वागत छ - HostelHub')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8">
        <!-- ✅ TOP WELCOME MESSAGE (SINGLE, WITH "HOSTELHUB" DEFAULT) -->
        <div class="text-center">
            @php
                $student = auth()->user()->student;
                $hostelName = optional($student)->hostel ? $student->hostel->name : 'HostelHub';
            @endphp
            
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                @if($hostelName !== 'HostelHub')
                    {{ $hostelName }} मा स्वागत छ, {{ auth()->user()->name }}!
                @else
                    HostelHub मा स्वागत छ, {{ auth()->user()->name }}!
                @endif
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                तपाईं सफलतापूर्वक लगइन हुनु भएको छ।
            </p>
            
            <!-- ✅ OPTIONAL SMALL TEXT: DASHBOARD INACTIVE UNTIL HOSTEL JOINED -->
            @if(!$student || !$student->hostel_id)
            <p class="mt-3 text-xs text-gray-500 bg-gray-100 inline-block px-3 py-1 rounded-full">
                ⚠️ होस्टल join नहुदासम्म तपाईंको ड्यासबोर्ड पुरै सक्रिय हुने छैन।
            </p>
            @endif
        </div>

        <!-- ✅ BOOKING SUMMARY SECTION (KEEP AS IS) -->
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
                <!-- ... (keep your existing booking statistics and list) ... -->
            </div>
        </div>
        @endif

        <!-- ✅ MAIN CONTENT CARD (NO DUPLICATE WELCOME HEADING) -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <!-- Success/Error Messages -->
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

            <!-- ✅ WELCOME TEXT (NO DUPLICATE HEADING) -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
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

            <!-- ✅ ACTION BUTTONS – ONLY TWO AS REQUESTED -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                <!-- Hostel Search Button -->
<a href="{{ route('student.hostel.search') }}" 
   class="flex items-center justify-center p-4 border border-transparent rounded-lg shadow-sm text-base font-medium bg-green-600 hover:bg-green-700 transition duration-150 group no-underline text-white">
    <div class="text-center">
        <i class="fas fa-search text-2xl mb-2 group-hover:scale-110 transition-transform text-white"></i>
        <div class="text-white">होस्टल खोज्नुहोस्</div>
    </div>
</a>

<!-- Use Hostel Code Button -->
<a href="{{ route('student.hostel.join') }}" 
   class="flex items-center justify-center p-4 border border-transparent rounded-lg shadow-sm text-base font-medium bg-indigo-600 hover:bg-indigo-700 transition duration-150 group no-underline text-white">
    <div class="text-center">
        <i class="fas fa-key text-2xl mb-2 group-hover:scale-110 transition-transform text-white"></i>
        <div class="text-white">होस्टल कोड प्रयोग गर्नुहोस्</div>
    </div>
</a>

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

<!-- Auto-refresh script (keep as is) -->
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