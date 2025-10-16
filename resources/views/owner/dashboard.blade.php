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
        
        <!-- Circular Notifications -->
        @if(($organizationCirculars ?? 0) > 0)
        <div class="mt-4 bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-bullhorn text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-indigo-800">तपाईंसँग {{ $organizationCirculars }} सक्रिय सूचनाहरू छन्</h3>
                        <p class="text-sm text-indigo-600">हालसम्म {{ $organizationCirculars }} सूचनाहरू प्रकाशित गरिएको छ</p>
                    </div>
                </div>
                <a href="{{ route('owner.circulars.index') }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium no-underline">
                    सबै हेर्नुहोस्
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <!-- Total Monthly Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">कुल मासिक आय</h3>
                    <p class="text-2xl font-bold text-gray-800">रु {{ number_format($totalMonthlyRevenue ?? 0) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Security Deposit -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">जम्मा जमानत</h3>
                    <p class="text-2xl font-bold text-gray-800">रु {{ number_format($totalSecurityDeposit ?? 0) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Occupancy -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">औसत ओक्युपेन्सी</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $averageOccupancy ?? 0 }}%</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Hostels -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-amber-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">एक्टिभ होस्टेल</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $activeHostelsCount ?? 0 }}</p>
                </div>
                <div class="bg-amber-100 p-3 rounded-lg">
                    <i class="fas fa-hotel text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- ✅ ADDED: Circulars Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">सूचनाहरू</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $organizationCirculars ?? 0 }}</p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-lg">
                    <i class="fas fa-bullhorn text-indigo-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('owner.circulars.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                सबै हेर्नुहोस् <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    @if(isset($hostel) && $hostel)
        <!-- Hostel Overview -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">{{ $hostel->name }} को विवरण</h2>
                
                <!-- ✅ FIXED: Proper Blue Button without Underline -->
                <a href="{{ route('owner.hostels.show', $hostel) }}" 
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-5 py-3 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                    <i class="fas fa-eye mr-2"></i>
                    हेर्नुहोस् — Hostel विवरण
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Rooms Card -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-blue-800">कुल कोठाहरू</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ $totalRooms ?? 0 }}</p>
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
                            <p class="text-2xl font-bold text-green-600">{{ $occupiedRooms ?? 0 }}</p>
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
                            <p class="text-2xl font-bold text-amber-600">{{ $totalStudents ?? 0 }}</p>
                        </div>
                        <div class="bg-amber-600 text-white p-3 rounded-lg">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">मासिक भाडा</h3>
                    <p class="text-2xl font-bold text-green-600">रु {{ number_format($hostel->monthly_rent ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">सुरक्षा जमानत</h3>
                    <p class="text-2xl font-bold text-blue-600">रु {{ number_format($hostel->security_deposit ?? 0) }}</p>
                </div>
            </div>

            <!-- Today's Meal -->
            @if(isset($todayMeal) && $todayMeal)
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
    @endif

    <!-- ✅ NEW: Circulars Overview Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">सूचना व्यवस्थापन</h2>
            <div class="flex space-x-2">
                <a href="{{ route('owner.circulars.analytics') }}" 
                   class="inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg px-4 py-2 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                    <i class="fas fa-chart-bar mr-2"></i>
                    विश्लेषण
                </a>
                <a href="{{ route('owner.circulars.index') }}" 
                   class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg px-5 py-2 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                    <i class="fas fa-bullhorn mr-2"></i>
                    सबै सूचनाहरू
                </a>
            </div>
        </div>

        <!-- Circulars Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Circulars Statistics -->
            <div class="bg-indigo-50 p-6 rounded-lg border-l-4 border-indigo-500">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-indigo-600">सूचना तथ्याङ्क</h3>
                        <p class="text-2xl font-bold text-indigo-800">{{ $organizationCirculars ?? 0 }}</p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-lg">
                        <i class="fas fa-bullhorn text-indigo-600 text-xl"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-indigo-700">जरुरी सूचनाहरू:</span>
                        <span class="font-semibold">{{ $urgentCirculars ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-indigo-700">सामान्य सूचनाहरू:</span>
                        <span class="font-semibold">{{ $normalCirculars ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-indigo-700">नोटिसहरू:</span>
                        <span class="font-semibold">{{ $noticeCirculars ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Circulars Activity -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-600 mb-4">हालका सूचनाहरू</h3>
                @if($recentCirculars && $recentCirculars->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentCirculars as $circular)
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-bullhorn text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ Str::limit($circular->title, 40) }}</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $circular->created_at->diffForHumans() }}
                                            <span class="ml-2 px-2 py-1 bg-{{ $circular->priority == 'urgent' ? 'red' : ($circular->priority == 'normal' ? 'blue' : 'gray') }}-100 text-{{ $circular->priority == 'urgent' ? 'red' : ($circular->priority == 'normal' ? 'blue' : 'gray') }}-600 rounded-full text-xs">
                                                {{ $circular->priority_nepali ?? 'सामान्य' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('owner.circulars.show', $circular) }}" 
                                       class="text-blue-600 hover:text-blue-800 p-1" 
                                       title="हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('owner.circulars.edit', $circular) }}" 
                                       class="text-green-600 hover:text-green-800 p-1" 
                                       title="सम्पादन गर्नुहोस्">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-bullhorn text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-sm">हाल कुनै सूचना छैन</p>
                        <a href="{{ route('owner.circulars.create') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-2 inline-block">
                            पहिलो सूचना सिर्जना गर्नुहोस्
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ✅ NEW: Documents Overview Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">कागजात व्यवस्थापन</h2>
            <a href="{{ route('owner.documents.index') }}" 
               class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg px-5 py-3 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                <i class="fas fa-file-alt mr-2"></i>
                सबै कागजात हेर्नुहोस्
            </a>
        </div>

        <!-- Documents Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Total Documents Card -->
            <div class="bg-purple-50 p-6 rounded-lg border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-purple-600">कुल कागजातहरू</h3>
                        <p class="text-2xl font-bold text-purple-800">{{ $totalDocuments ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-purple-600 mt-2">तपाईंको संस्थाका सबै कागजातहरू</p>
            </div>

            <!-- Recent Documents Activity -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-600 mb-4">हालका कागजातहरू</h3>
                @if($recentDocuments && $recentDocuments->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentDocuments as $document)
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-file text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ $document->original_name }}</p>
                                        <p class="text-xs text-gray-600">
                                            {{ optional($document->student)->user->name ?? 'अज्ञात विद्यार्थी' }} • 
                                            {{ $document->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('owner.documents.show', $document) }}" 
                                       class="text-blue-600 hover:text-blue-800 p-1" 
                                       title="हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('owner.documents.download', $document) }}" 
                                       class="text-green-600 hover:text-green-800 p-1" 
                                       title="डाउनलोड गर्नुहोस्">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-sm">हाल कुनै कागजात छैन</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions - Updated with Circulars -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold mb-4">द्रुत कार्यहरू</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('owner.hostels.create') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors no-underline group">
                <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="font-medium text-blue-800 text-sm">नयाँ होस्टेल</div>
            </a>
            
            <a href="{{ route('owner.rooms.index') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors no-underline group">
                <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="font-medium text-blue-800 text-sm">कोठाहरू</div>
            </a>
            
            <a href="{{ route('owner.students.index') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors no-underline group">
                <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-users"></i>
                </div>
                <div class="font-medium text-green-800 text-sm">विद्यार्थीहरू</div>
            </a>
            
            <a href="{{ route('owner.payments.index') }}" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors no-underline group">
                <div class="text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="font-medium text-purple-800 text-sm">भुक्तानीहरू</div>
            </a>

            <!-- ✅ ADDED: Circulars Quick Actions -->
            <a href="{{ route('owner.circulars.create') }}" class="p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-center transition-colors no-underline group">
                <div class="text-indigo-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="font-medium text-indigo-800 text-sm">सूचना थप्नुहोस्</div>
            </a>

            <a href="{{ route('owner.circulars.index') }}" class="p-4 bg-teal-50 hover:bg-teal-100 rounded-lg text-center transition-colors no-underline group">
                <div class="text-teal-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-list"></i>
                </div>
                <div class="font-medium text-teal-800 text-sm">सूचनाहरू हेर्नुहोस्</div>
            </a>
        </div>
    </div>
@endsection