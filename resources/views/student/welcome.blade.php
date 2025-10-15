@extends('layouts.student')

@section('title', 'स्वागत छ - HostelHub')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
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
                तपाईं सफलतापूर्वक दर्ता हुनु भएको छ।
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <h3 class="mt-2 text-lg font-medium text-gray-900">खाता सिर्जना सफल भयो</h3>
            </div>

            <div class="space-y-4">
                <p class="text-gray-600 text-center">
                    @if(auth()->user()->student && auth()->user()->student->hostel_id)
                        तपाईं {{ $hostelName }} सँग जडान हुनुभएको छ। 
                        तलका विकल्पहरूबाट आफ्नो होस्टल अनुभव सुरु गर्नुहोस्:
                    @else
                        तपाईंको खाता सफलतापूर्वक सिर्जना गरिएको छ। 
                        होस्टलमा जडान गर्नका लागि तलका विकल्पहरू छन्:
                    @endif
                </p>

                <!-- COMPLETELY FIXED BUTTONS -->
                <div class="space-y-4 mt-6">
                    @if(!auth()->user()->student || !auth()->user()->student->hostel_id)
                        <!-- Option 1: Search hostel -->
                        <a href="{{ route('hostels.search') }}" 
                           class="w-full flex items-center justify-center px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 transition duration-150">
                            <i class="fas fa-search mr-3"></i>
                            होस्टल खोज्नुहोस्
                        </a>

                        <!-- Option 2: Use hostel code -->
                        <a href="{{ route('student.hostel.join') }}" 
                           class="w-full flex items-center justify-center px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                            <i class="fas fa-key mr-3"></i>
                            होस्टल कोड प्रयोग गर्नुहोस्
                        </a>
                    @endif

                    <!-- Option 3: Go to dashboard - FIXED: Now uses StudentController dashboard method -->
                    <a href="{{ route('student.dashboard') }}" 
                       class="w-full flex items-center justify-center px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        ड्यासबोर्डमा जानुहोस्
                    </a>

                    <!-- Option 4: Profile -->
                    <a href="{{ route('student.profile') }}" 
                       class="w-full flex items-center justify-center px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-purple-600 hover:bg-purple-700 transition duration-150">
                        <i class="fas fa-user mr-3"></i>
                        प्रोफाइल
                    </a>

                    <!-- Option 5: Payments -->
                    <a href="{{ route('student.payments.index') }}" 
                       class="w-full flex items-center justify-center px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700 transition duration-150">
                        <i class="fas fa-credit-card mr-3"></i>
                        भुक्तानी
                    </a>

                    <!-- Option 6: Meal Plan - FIXED: Using inline CSS for guaranteed visibility -->
                    <a href="{{ route('student.meal-menus.index') }}" 
                       class="w-full flex items-center justify-center px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white transition duration-150"
                       style="background-color: #4a5568; color: white; border-color: transparent;">
                        <i class="fas fa-utensils mr-3"></i>
                        खानाको योजना
                    </a>
                </div>

                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>कुनै प्रश्न छ? 
                        <a href="{{ route('contact') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            सम्पर्क गर्नुहोस्
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection