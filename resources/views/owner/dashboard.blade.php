@extends('layouts.owner')

@section('title', 'होस्टल ड्यासबोर्ड')

@section('content')
    @php
        // ✅ FIXED: Correct query for owner's booking counts
        $organizationId = session('current_organization_id');
        $ownerId = Auth::id();
        
        // Get hostels managed by this owner in current organization
        $hostelIds = \App\Models\Hostel::where('organization_id', $organizationId)
            ->where('owner_id', $ownerId)
            ->pluck('id');

        // Count pending bookings from Booking model (NEW SYSTEM)
        $pendingBookingsCount = \App\Models\Booking::whereIn('hostel_id', $hostelIds)
            ->where('status', 'pending')
            ->count();
            
        // Count pending booking requests from BookingRequest model (OLD SYSTEM)  
        $pendingBookingRequests = \App\Models\BookingRequest::whereIn('hostel_id', $hostelIds)
            ->where('status', 'pending')
            ->count();
            
        $totalPending = $pendingBookingsCount + $pendingBookingRequests;
    @endphp

    <!-- Welcome Section -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800">नमस्ते, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600 mt-2">यो तपाईंको होस्टल व्यवस्थापन ड्यासबोर्ड हो</p>
                
                <!-- Circular Notifications -->
                @if(($organizationCirculars ?? 0) > 0)
                <div class="mt-4 bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-xl">
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
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium no-underline transition-colors">
                            सबै हेर्नुहोस्
                        </a>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- 🏠 Homepage Button in Welcome Section -->
            <div class="ml-6">
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl px-5 py-3 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                    <i class="fas fa-home mr-2"></i>
                    मुख्य पृष्ठमा जानुहोस्
                </a>
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-6 mb-6">
        <!-- Total Monthly Revenue -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">कुल मासिक आय</h3>
                    <p class="text-2xl font-bold text-gray-800">रु {{ number_format($totalMonthlyRevenue ?? 0) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Security Deposit -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">जम्मा जमानत</h3>
                    <p class="text-2xl font-bold text-gray-800">रु {{ number_format($totalSecurityDeposit ?? 0) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Occupancy -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">औसत ओक्युपेन्सी</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $averageOccupancy ?? 0 }}%</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Hostels -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-amber-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">एक्टिभ होस्टेल</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $activeHostelsCount ?? 0 }}</p>
                </div>
                <div class="bg-amber-100 p-3 rounded-xl">
                    <i class="fas fa-hotel text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Circulars Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-indigo-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">सूचनाहरू</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $organizationCirculars ?? 0 }}</p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-xl">
                    <i class="fas fa-bullhorn text-indigo-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('owner.circulars.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                सबै हेर्नुहोस् <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>

        <!-- 🆕 BOOKING REQUESTS CARD -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-orange-500 relative">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">बुकिंग अनुरोध</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPending }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-xl">
                    <i class="fas fa-calendar-check text-orange-600 text-xl"></i>
                </div>
            </div>
            @if($totalPending > 0)
                <span class="absolute top-2 right-2 bg-red-500 text-white rounded-full text-xs w-6 h-6 flex items-center justify-center animate-pulse">
                    {{ $totalPending }}
                </span>
            @endif
            <a href="{{ route('owner.booking-requests.index') }}" class="text-xs text-orange-600 hover:text-orange-800 font-medium mt-2 inline-block">
                व्यवस्थापन गर्नुहोस् <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>

        <!-- 🆕 PUBLIC PAGE STATUS CARD -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-teal-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">सार्वजनिक पृष्ठ</h3>
                    <p class="text-2xl font-bold text-gray-800">
                        @if(isset($hostel) && $hostel->getRawOriginal('is_published'))
                            <span class="text-green-600">प्रकाशित</span>
                        @else
                            <span class="text-amber-600">मस्यौदा</span>
                        @endif
                    </p>
                </div>
                <div class="bg-teal-100 p-3 rounded-xl">
                    <i class="fas fa-globe text-teal-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('owner.public-page.edit') }}" class="text-xs text-teal-600 hover:text-teal-800 font-medium mt-2 inline-block">
                व्यवस्थापन गर्नुहोस् <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Circular Engagement Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Today's Circulars -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">आजका सूचनाहरू</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $todayCirculars ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-bullhorn text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">आज प्रकाशित सूचनाहरू</p>
        </div>

        <!-- Read Rate -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">पढ्ने दर</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $circularReadRate ?? 0 }}%</p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <i class="fas fa-eye text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">सम्पूर्ण पढ्ने दर</p>
        </div>

        <!-- Student Engagement -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">विद्यार्थी संलग्नता</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $studentEngagement ?? 0 }}%</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">सक्रिय विद्यार्थीहरू</p>
        </div>
    </div>

    @if(isset($hostel) && $hostel)
        <!-- Hostel Overview -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">{{ $hostel->name }} को विवरण</h2>
                
                <div class="flex space-x-3">
                    <!-- Public Page Quick Action -->
                    <a href="{{ route('owner.public-page.edit') }}" 
                       class="inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-xl px-5 py-3 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                        <i class="fas fa-globe mr-2"></i>
                        सार्वजनिक पृष्ठ
                    </a>
                    
                    <a href="{{ route('owner.hostels.show', $hostel) }}" 
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl px-5 py-3 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                        <i class="fas fa-eye mr-2"></i>
                        हेर्नुहोस् — Hostel विवरण
                    </a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Rooms Card -->
                <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-blue-800">कुल कोठाहरू</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ $totalRooms ?? 0 }}</p>
                        </div>
                        <div class="bg-blue-600 text-white p-3 rounded-xl">
                            <i class="fas fa-door-open text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Occupied Rooms Card -->
                <div class="bg-green-50 p-4 rounded-2xl border border-green-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-green-800">अधिभृत कोठाहरू</h3>
                            <p class="text-2xl font-bold text-green-600">{{ $occupiedRooms ?? 0 }}</p>
                        </div>
                        <div class="bg-green-600 text-white p-3 rounded-xl">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Students Card -->
                <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-amber-800">विद्यार्थीहरू</h3>
                            <p class="text-2xl font-bold text-amber-600">{{ $totalStudents ?? 0 }}</p>
                        </div>
                        <div class="bg-amber-600 text-white p-3 rounded-xl">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                    <h3 class="font-semibold text-gray-800 mb-2">मासिक भाडा</h3>
                    <p class="text-2xl font-bold text-green-600">रु {{ number_format($hostel->monthly_rent ?? 0) }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                    <h3 class="font-semibold text-gray-800 mb-2">सुरक्षा जमानत</h3>
                    <p class="text-2xl font-bold text-blue-600">रु {{ number_format($hostel->security_deposit ?? 0) }}</p>
                </div>
            </div>

            <!-- Today's Meal -->
            @if(isset($todayMeal) && $todayMeal)
            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
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

    <!-- Quick Actions Section -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">द्रुत कार्यहरू</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-9 gap-4">
            <!-- 🏠 Homepage Button in Quick Actions -->
            <a href="{{ url('/') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-2xl text-center transition-colors no-underline group border border-green-100">
                <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-home"></i>
                </div>
                <div class="font-medium text-green-800 text-sm">मुख्य पृष्ठ</div>
            </a>
            
            <!-- 🆕 PUBLIC PAGE QUICK ACTION -->
            <a href="{{ route('owner.public-page.edit') }}" class="p-4 bg-teal-50 hover:bg-teal-100 rounded-2xl text-center transition-colors no-underline group border border-teal-100">
                <div class="text-teal-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="font-medium text-teal-800 text-sm">सार्वजनिक पृष्ठ</div>
            </a>

            <!-- 🆕 GALLERY QUICK ACTION -->
            <a href="{{ route('owner.galleries.index') }}" class="p-4 bg-teal-50 hover:bg-teal-100 rounded-2xl text-center transition-colors no-underline group border border-teal-100">
                <div class="text-teal-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-images"></i>
                </div>
                <div class="font-medium text-teal-800 text-sm nepali">ग्यालरी</div>
            </a>
            
            <!-- 🆕 CONTACT MESSAGES QUICK ACTION -->
            <a href="{{ route('owner.contacts.index') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-2xl text-center transition-colors no-underline group border border-blue-100">
                <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="font-medium text-blue-800 text-sm">सम्पर्क सन्देश</div>
            </a>

            <!-- 🆕 BOOKING REQUESTS QUICK ACTION -->
            <a href="{{ route('owner.booking-requests.index') }}" class="p-4 bg-orange-50 hover:bg-orange-100 rounded-2xl text-center transition-colors no-underline group border border-orange-100 relative">
                <div class="text-orange-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="font-medium text-orange-800 text-sm">बुकिंग अनुरोध</div>
                @if($totalPending > 0)
                    <span class="absolute top-2 right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center animate-pulse">
                        {{ $totalPending }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('owner.hostels.create') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-2xl text-center transition-colors no-underline group border border-blue-100">
                <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="font-medium text-blue-800 text-sm">नयाँ होस्टेल</div>
            </a>
            
            <a href="{{ route('owner.rooms.index') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-2xl text-center transition-colors no-underline group border border-blue-100">
                <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="font-medium text-blue-800 text-sm">कोठाहरू</div>
            </a>
            
            <a href="{{ route('owner.students.index') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-2xl text-center transition-colors no-underline group border border-green-100">
                <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-users"></i>
                </div>
                <div class="font-medium text-green-800 text-sm">विद्यार्थीहरू</div>
            </a>
            
            <a href="{{ route('owner.payments.index') }}" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-2xl text-center transition-colors no-underline group border border-purple-100">
                <div class="text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="font-medium text-purple-800 text-sm">भुक्तानीहरू</div>
            </a>
        </div>
    </div>

    <!-- 🚨 FIX 1: DUPLICATE BOOKING REQUEST SECTION REMOVED HERE -->

    <!-- Circulars & Documents Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Circulars Overview -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">सूचना व्यवस्थापन</h2>
                <div class="flex space-x-2">
                    <!-- FIXED: Changed from teal to blue for better visibility -->
                    <a href="{{ route('owner.circulars.analytics') }}" 
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl px-4 py-2 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                        <i class="fas fa-chart-bar mr-2"></i>
                        विश्लेषण
                    </a>
                    <a href="{{ route('owner.circulars.index') }}" 
                       class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl px-5 py-2 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                        <i class="fas fa-bullhorn mr-2"></i>
                        सबै सूचनाहरू
                    </a>
                </div>
            </div>

            <!-- Recent Circulars -->
            <div class="space-y-3">
                @forelse($recentCirculars ?? [] as $circular)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
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
                               class="text-blue-600 hover:text-blue-800 p-1 transition-colors" 
                               title="हेर्नुहोस्">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-bullhorn text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-sm">हाल कुनै सूचना छैन</p>
                        <a href="{{ route('owner.circulars.create') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-2 inline-block">
                            पहिलो सूचना सिर्जना गर्नुहोस्
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Documents Overview -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">कागजात व्यवस्थापन</h2>
                <a href="{{ route('owner.documents.index') }}" 
                   class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl px-5 py-2 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                    <i class="fas fa-file-alt mr-2"></i>
                    सबै कागजात हेर्नुहोस्
                </a>
            </div>

            <!-- Recent Documents -->
            <div class="space-y-3">
                @forelse($recentDocuments ?? [] as $document)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
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
                               class="text-blue-600 hover:text-blue-800 p-1 transition-colors" 
                               title="हेर्नुहोस्">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-sm">हाल कुनै कागजात छैन</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 🆕 CONTACT MESSAGES SECTION -->
<div class="bg-white rounded-2xl shadow-sm p-6 mt-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">सम्पर्क सन्देशहरू</h2>
        <div class="flex space-x-2">
            <a href="{{ route('owner.contacts.index') }}" 
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl px-5 py-2 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                <i class="fas fa-envelope mr-2"></i>
                सबै सन्देशहरू
            </a>
        </div>
    </div>

    <!-- Contact Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-blue-800">कुल सन्देशहरू</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalContacts ?? 0 }}</p>
                </div>
                <div class="bg-blue-600 text-white p-3 rounded-xl">
                    <i class="fas fa-envelope-open text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-red-50 p-4 rounded-2xl border border-red-100">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-red-800">नपढिएका</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $unreadContacts ?? 0 }}</p>
                </div>
                <div class="bg-red-600 text-white p-3 rounded-xl">
                    <i class="fas fa-envelope text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-green-50 p-4 rounded-2xl border border-green-100">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-green-800">आजको सन्देश</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $todayContacts ?? 0 }}</p>
                </div>
                <div class="bg-green-600 text-white p-3 rounded-xl">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Contacts -->
    <div class="space-y-3">
        @forelse($recentContacts ?? [] as $contact)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors">
                <div class="flex items-center flex-1">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-800 text-sm">{{ $contact->name }}</p>
                                <p class="text-xs text-gray-600">{{ $contact->email }}</p>
                                <p class="text-xs text-gray-600">{{ Str::limit($contact->subject, 40) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ $contact->created_at->diffForHumans() }}</p>
                                @if(!$contact->is_read)
                                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">नयाँ</span>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($contact->message, 60) }}</p>
                    </div>
                </div>
                <div class="flex space-x-2 ml-4">
                    <a href="{{ route('owner.contacts.show', $contact) }}" 
                       class="text-blue-600 hover:text-blue-800 p-2 transition-colors" 
                       title="हेर्नुहोस्">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if(!$contact->is_read)
                        <form action="{{ route('owner.contacts.update-status', $contact) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="read">
                            <button type="submit" class="text-green-600 hover:text-green-800 p-2 transition-colors" title="पढियो चिन्ह लगाउनुहोस्">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    @endif
                    
                    <!-- ✅ FIXED: CORRECTED DELETE BUTTON WITH NAMED ROUTE -->
                    <form action="{{ route('owner.contacts.destroy', $contact) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-800 p-2 transition-colors" 
                                title="मेटाउनुहोस्"
                                onclick="return confirm('के तपाईं यो सन्देश मेटाउन निश्चित हुनुहुन्छ? यो कार्य पूर्ववत गर्न सकिँदैन।')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <i class="fas fa-envelope-open-text text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500 text-sm">हाल कुनै सम्पर्क सन्देश छैन</p>
                <p class="text-gray-400 text-xs mt-1">नयाँ सन्देशहरू यहाँ देखिनेछन्</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive functionality here
    console.log('Owner dashboard loaded');

    // Real-time contact notifications
    function updateContactNotifications() {
        fetch('{{ route("owner.dashboard.contact-counts") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update badge counts if needed
                    const unreadBadge = document.querySelector('.contact-unread-badge');
                    if (unreadBadge && data.unreadCount > 0) {
                        unreadBadge.textContent = data.unreadCount;
                        unreadBadge.classList.remove('hidden');
                    }
                }
            })
            .catch(error => console.error('Error fetching contact counts:', error));
    }

    // Real-time booking request notifications
    function updateBookingRequestNotifications() {
        fetch('{{ route("owner.dashboard.booking-requests-count") }}')
            .then(response => response.json())
            .then(data => {
                if (data.pending_count > 0) {
                    // Update booking request badges
                    const badges = document.querySelectorAll('.booking-request-badge');
                    badges.forEach(badge => {
                        badge.textContent = data.pending_count;
                        badge.classList.remove('hidden');
                    });
                }
            })
            .catch(error => console.error('Error fetching booking request counts:', error));
    }

    // Update every 30 seconds
    setInterval(updateContactNotifications, 30000);
    setInterval(updateBookingRequestNotifications, 30000);

    // Initial updates
    updateContactNotifications();
    updateBookingRequestNotifications();
});
</script>
@endsection