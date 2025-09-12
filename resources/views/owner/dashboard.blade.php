@extends('layouts.owner')

@section('title', 'होस्टल ड्यासबोर्ड')

@section('content')
    @isset($error)
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <p>{{ $error }}</p>
        </div>
    @endisset

    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">नमस्ते, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mt-2">यो तपाईंको होस्टल व्यवस्थापन ड्यासबोर्ड हो</p>
    </div>

    <!-- Hostel Overview -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">{{ $hostel->name }} को विवरण</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Rooms Card -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-blue-800">कुल कोठाहरू</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalRooms }}</p>
                    </div>
                    <div class="bg-blue-600 text-white p-3 rounded-lg">
                        <i class="fas fa-door-open text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Occupied Rooms Card -->
            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-green-800">अधिभृत कोठाहरू</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $occupiedRooms }}</p>
                    </div>
                    <div class="bg-green-600 text-white p-3 rounded-lg">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Students Card -->
            <div class="bg-amber-50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-amber-800">विद्यार्थीहरू</h3>
                        <p class="text-2xl font-bold text-amber-600">{{ $totalStudents }}</p>
                    </div>
                    <div class="bg-amber-600 text-white p-3 rounded-lg">
                        <i class="fas fa-user-graduate text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Meal -->
        @if($todayMeal)
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-800 mb-2">आजको खाना</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">बिहान</p>
                    <p class="font-medium">{{ $todayMeal->breakfast }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">दिउँसो</p>
                    <p class="font-medium">{{ $todayMeal->lunch }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">बेलुका</p>
                    <p class="font-medium">{{ $todayMeal->dinner }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold mb-4">द्रुत कार्यहरू</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('owner.rooms.index') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors">
                <div class="text-blue-600 text-2xl mb-2">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="font-medium text-blue-800">कोठाहरू</div>
            </a>
            
            <a href="{{ route('owner.students.index') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors">
                <div class="text-green-600 text-2xl mb-2">
                    <i class="fas fa-users"></i>
                </div>
                <div class="font-medium text-green-800">विद्यार्थीहरू</div>
            </a>
            
            <a href="{{ route('owner.meals.index') }}" class="p-4 bg-amber-50 hover:bg-amber-100 rounded-lg text-center transition-colors">
                <div class="text-amber-600 text-2xl mb-2">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="font-medium text-amber-800">खाना व्यवस्था</div>
            </a>
            
            <a href="{{ route('owner.payments.index') }}" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors">
                <div class="text-purple-600 text-2xl mb-2">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="font-medium text-purple-800">भुक्तानीहरू</div>
            </a>
        </div>
    </div>
@endsection