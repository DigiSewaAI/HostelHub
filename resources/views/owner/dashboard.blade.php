@extends('layouts.owner')

@section('title', 'ड्यासबोर्ड')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">स्वागतम्, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600">तपाईंको होस्टल प्रबन्धन ड्यासबोर्ड</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-100 mr-4">
                    <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalStudents ?? 0 }}</h3>
                    <p class="text-gray-600">कुल विद्यार्थीहरू</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-100 mr-4">
                    <i class="fas fa-door-open text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalRooms ?? 0 }} <span class="text-sm text-gray-500">({{ $occupiedRooms ?? 0 }} व्यस्त)</span></h3>
                    <p class="text-gray-600">कोठाहरू</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-purple-100 mr-4">
                    <i class="fas fa-utensils text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $mealMenus->count() ?? 0 }}</h3>
                    <p class="text-gray-600">खानाको योजना</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-yellow-100 mr-4">
                    <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">आजको</h3>
                    <p class="text-gray-600">{{ $todayMeal->meal_type ?? 'खाना योजना छैन' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">छिटो कार्यहरू</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('owner.meal-menus.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="p-3 rounded-full bg-blue-100 mb-2">
                    <i class="fas fa-utensils text-blue-600 text-xl"></i>
                </div>
                <span class="text-center text-gray-700 font-medium">खाना थप्नुहोस्</span>
            </a>
            <a href="{{ route('owner.rooms.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="p-3 rounded-full bg-green-100 mb-2">
                    <i class="fas fa-door-open text-green-600 text-xl"></i>
                </div>
                <span class="text-center text-gray-700 font-medium">कोठा थप्नुहोस्</span>
            </a>
            <a href="{{ route('owner.students.create') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="p-3 rounded-full bg-purple-100 mb-2">
                    <i class="fas fa-user-plus text-purple-600 text-xl"></i>
                </div>
                <span class="text-center text-gray-700 font-medium">विद्यार्थी थप्नुहोस्</span>
            </a>
            <a href="{{ route('owner.galleries.create') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <div class="p-3 rounded-full bg-yellow-100 mb-2">
                    <i class="fas fa-images text-yellow-600 text-xl"></i>
                </div>
                <span class="text-center text-gray-700 font-medium">ग्यालरी थप्नुहोस्</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Today's Meal -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">आजको खानाको योजना</h2>
            </div>
            <div class="p-6">
                @if($todayMeal)
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded mr-2">
                                {{ $todayMeal->meal_type == 'breakfast' ? 'नास्ता' : ($todayMeal->meal_type == 'lunch' ? 'दिउँसो' : 'रात्रि') }}
                            </span>
                        </div>
                        <p class="text-gray-700 mb-2">{{ $todayMeal->items }}</p>
                        @if($todayMeal->image)
                            <img src="{{ asset($todayMeal->image) }}" alt="Meal Image" class="w-full h-40 object-cover rounded-lg mt-2">
                        @endif
                    </div>
                    <a href="{{ route('owner.meal-menus.edit', $todayMeal) }}" class="text-indigo-600 hover:text-indigo-900">
                        <i class="fas fa-edit mr-1"></i> सम्पादन गर्नुहोस्
                    </a>
                @else
                    <p class="text-gray-600">आजको लागि खानाको योजना थप गर्नुहोस्।</p>
                    <a href="{{ route('owner.meal-menus.create') }}" class="mt-3 inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        <i class="fas fa-plus mr-1"></i> खाना थप्नुहोस्
                    </a>
                @endif
            </div>
        </div>

        <!-- Recent Students -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">हालै थपिएका विद्यार्थीहरू</h2>
                    <a href="{{ route('owner.students.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">सबै हेर्नुहोस्</a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @if($students->isNotEmpty())
                    @foreach($students->take(5) as $student)
                        <div class="p-6 flex items-center">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                                <span class="text-indigo-800 font-semibold">{{ substr($student->name, 0, 2) }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $student->name }}</h3>
                                <p class="text-gray-500 text-sm">{{ $student->college }} - {{ $student->course }}</p>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $student->admission_date->format('d M, Y') }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-6 text-center text-gray-500">
                        कुनै विद्यार्थी थपिएको छैन
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Hostel Information -->
    <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">होस्टल जानकारी</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $hostel->name ?? 'होस्टल नाम' }}</h3>
                    <p class="text-gray-600 mb-4">{{ $hostel->address ?? 'होस्टल ठेगाना' }}, {{ $hostel->city ?? 'शहर' }}</p>
                    
                    <div class="mb-4">
                        <h4 class="text-gray-700 font-medium mb-1">सम्पर्क जानकारी</h4>
                        <p class="text-gray-600">फोन: {{ $hostel->phone ?? 'फोन नम्बर' }}</p>
                        <p class="text-gray-600">इमेल: {{ $hostel->email ?? 'इमेल' }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-gray-700 font-medium mb-1">क्षमता</h4>
                        <p class="text-gray-600">{{ $hostel->max_capacity ?? 'क्षमता' }} विद्यार्थीहरूको लागि</p>
                        <p class="text-gray-600">मूल्य: रु. {{ $hostel->price_per_month ?? 'मूल्य' }}/महिना</p>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-gray-700 font-medium mb-2">होस्टल विवरण</h4>
                    <p class="text-gray-600 mb-4">
                        {{ $hostel->description ?? 'होस्टल बारे विवरण यहाँ आउनेछ। हाम्रो होस्टलमा विद्यार्थीहरूको लागि सुविधाजनक वातावरण उपलब्ध गराइएको छ।' }}
                    </p>
                    
                    <div class="mt-4">
                        <a href="{{ route('owner.hostels.edit', $hostel) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-edit mr-2"></i> होस्टल विवरण सम्पादन गर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection