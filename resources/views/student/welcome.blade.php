@extends('layouts.student')

@section('title', 'स्वागत छ - HostelHub')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                स्वागत छ, {{ auth()->user()->name }}!
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
                    तपाईंको खाता सफलतापूर्वक सिर्जना गरिएको छ। 
                    होस्टलमा जडान गर्नका लागि तलका विकल्पहरू छन्:
                </p>

                <div class="grid grid-cols-1 gap-4 mt-6">
                    <!-- Option 1: Join existing hostel -->
                    <a href="{{ route('student.hostels.search') }}" 
                       class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-search mr-2"></i>
                        होस्टल खोज्नुहोस्
                    </a>

                    <!-- Option 2: Enter hostel code -->
                    <a href="{{ route('student.hostels.join') }}" 
                       class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-key mr-2"></i>
                        होस्टल कोड प्रयोग गर्नुहोस्
                    </a>

                    <!-- Option 3: Go to dashboard -->
                    <a href="{{ route('student.dashboard') }}" 
                       class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        ड्यासबोर्डमा जानुहोस्
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