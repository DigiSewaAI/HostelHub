@extends('layouts.student')

@section('title', 'विद्यार्थी ड्यासबोर्ड')

@section('content')
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">नमस्ते, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mt-2">यो तपाईंको विद्यार्थी ड्यासबोर्ड हो</p>
    </div>

    <!-- Student Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Room Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">कोठा जानकारी</h2>
            @if($student->room)
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">होस्टल:</span>
                    <span class="font-medium">{{ $student->room->hostel->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">कोठा नं.:</span>
                    <span class="font-medium">{{ $student->room->room_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">कोठा प्रकार:</span>
                    <span class="font-medium">{{ $student->room->room_type }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">मासिक भुक्तानी:</span>
                    <span class="font-medium">रु. {{ $student->room->rent }}</span>
                </div>
            </div>
            @else
            <p class="text-gray-600">तपाईंको कोठा जानकारी उपलब्ध छैन</p>
            @endif
        </div>

        <!-- Payment Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">भुक्तानी स्थिति</h2>
            @php
                $latestPayment = $student->payments()->latest()->first();
            @endphp
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">अन्तिम भुक्तानी:</span>
                    <span class="font-medium">
                        @if($latestPayment)
                            रु. {{ $latestPayment->amount }}
                        @else
                            कुनै भुक्तानी छैन
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">अन्तिम मिति:</span>
                    <span class="font-medium">
                        @if($latestPayment)
                            {{ $latestPayment->created_at->format('Y-m-d') }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">स्थिति:</span>
                    <span class="font-medium {{ $latestPayment && $latestPayment->status === 'paid' ? 'text-green-600' : 'text-red-600' }}">
                        @if($latestPayment)
                            {{ $latestPayment->status === 'paid' ? 'भुक्तानी भएको' : 'बाकी' }}
                        @else
                            बाकी
                        @endif
                    </span>
                </div>
            </div>
            <a href="{{ route('student.payments.index') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                भुक्तानी गर्नुहोस्
            </a>
        </div>
    </div>

    <!-- Today's Meal -->
    @if($todayMeal)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">आजको खाना</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">बिहान</h3>
                <p>{{ $todayMeal->breakfast }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="font-semibold text-green-800 mb-2">दिउँसो</h3>
                <p>{{ $todayMeal->lunch }}</p>
            </div>
            <div class="bg-amber-50 p-4 rounded-lg">
                <h3 class="font-semibold text-amber-800 mb-2">बेलुका</h3>
                <p>{{ $todayMeal->dinner }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Links -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">द्रुत लिंकहरू</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('student.profile') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors">
                <div class="text-blue-600 text-2xl mb-2">
                    <i class="fas fa-user"></i>
                </div>
                <div class="font-medium text-blue-800">प्रोफाइल</div>
            </a>
            
            <a href="{{ route('student.payments.index') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors">
                <div class="text-green-600 text-2xl mb-2">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="font-medium text-green-800">भुक्तानी</div>
            </a>
            
            <a href="{{ route('student.meals.index') }}" class="p-4 bg-amber-50 hover:bg-amber-100 rounded-lg text-center transition-colors">
                <div class="text-amber-600 text-2xl mb-2">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="font-medium text-amber-800">खाना</div>
            </a>
            
            <a href="{{ route('student.complaints.create') }}" class="p-4 bg-red-50 hover:bg-red-100 rounded-lg text-center transition-colors">
                <div class="text-red-600 text-2xl mb-2">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="font-medium text-red-800">समस्या</div>
            </a>
        </div>
    </div>
@endsection