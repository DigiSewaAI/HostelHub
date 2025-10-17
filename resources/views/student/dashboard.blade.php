@extends('layouts.student')

@section('title', 'विद्यार्थी ड्यासबोर्ड')

@section('content')
<style>
/* Custom Wave Animation */
.wave-hand {
    display: inline-block;
    animation: wave 2.5s ease-in-out infinite;
    transform-origin: 70% 70%;
}

@keyframes wave {
    0% { transform: rotate(0deg); }
    10% { transform: rotate(14deg); }
    20% { transform: rotate(-8deg); }
    30% { transform: rotate(14deg); }
    40% { transform: rotate(-4deg); }
    50% { transform: rotate(10deg); }
    60% { transform: rotate(0deg); }
    100% { transform: rotate(0deg); }
}

/* Alternative: Bounce Animation */
.bounce-hand {
    display: inline-block;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
    40% {transform: translateY(-10px);}
    60% {transform: translateY(-5px);}
}

/* Simple Pulse Animation */
.pulse-hand {
    display: inline-block;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>

    <!-- Welcome Section with WORKING Animated Hand -->
    <div class="bg-blue-800 rounded-2xl shadow-lg mb-6 border border-blue-700">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1">
                    <!-- Main Heading with Waving Hand -->
                    <h2 class="text-2xl font-bold mb-2 text-white">
                        नमस्ते, {{ $student->user->name }}! 
                        <span class="wave-hand">👋</span>
                    </h2>
                    
                    <p class="text-white text-lg font-medium mb-4">{{ $hostel->name }} मा तपाईंलाई स्वागत छ</p>
                    
                    @if(($unreadCirculars ?? 0) > 0)
                    <div class="bg-yellow-400 text-gray-900 rounded-xl p-3 inline-block border border-yellow-500">
                        <div class="flex items-center">
                            <i class="fas fa-bell mr-2"></i>
                            <span class="font-bold">तपाईंसँग {{ $unreadCirculars }} वटा नयाँ सूचनाहरू छन्!</span>
                            <a href="{{ route('student.circulars.index') }}" class="ml-2 text-blue-800 underline font-bold">
                                यहाँ क्लिक गर्नुहोस्
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 md:mt-0">
                    <div class="bg-white text-blue-800 p-3 rounded-xl border border-blue-300 font-bold">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ now()->format('F j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">कोठा नं.</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $student->room->room_number ?? 'N/A' }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-door-open text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs mt-2">तपाईंको कोठा नम्बर</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">आजको खाना</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $todayMeal ? 'उपलब्ध' : 'अपडेट छैन' }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <i class="fas fa-utensils text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs mt-2">खानाको अवस्था</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">भुक्तानी</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $paymentStatus == 'Paid' ? 'भुक्तानी भएको' : 'बाकी' }}</p>
                </div>
                <div class="bg-amber-100 p-3 rounded-xl">
                    <i class="fas fa-receipt text-amber-600 text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs mt-2">भुक्तानी स्थिति</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">सूचनाहरू</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $unreadCirculars ?? 0 }}</p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-xl">
                    <i class="fas fa-bullhorn text-indigo-600 text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs mt-2">नयाँ सूचनाहरू</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Room & Payment Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Room Information -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-home text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">कोठा जानकारी</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">होस्टेल:</span>
                            <span class="font-medium text-gray-800">{{ $hostel->name ?? 'उपलब्ध छैन' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">कोठा नं.:</span>
                            <span class="font-medium text-gray-800">{{ $student->room->room_number ?? 'उपलब्ध छैन' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">कोठा प्रकार:</span>
                            <span class="font-medium text-gray-800">{{ $student->room->type ?? 'उपलब्ध छैन' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">मासिक भुक्तानी:</span>
                            <span class="font-bold text-green-600">रु. {{ $student->room->rent ?? 'उपलब्ध छैन' }}</span>
                        </div>
                    </div>
                    
                    <button class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl font-medium transition-colors">
                        <i class="fas fa-info-circle mr-2"></i>पूर्ण विवरण हेर्नुहोस्
                    </button>
                </div>

                <!-- Payment Status -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="bg-amber-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-credit-card text-amber-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">भुक्तानी स्थिति</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">अन्तिम भुक्तानी:</span>
                            <span class="font-medium text-gray-800">{{ $lastPayment ? 'रु. ' . $lastPayment->amount : 'कुनै भुक्तानी छैन' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">अन्तिम मिति:</span>
                            <span class="font-medium text-gray-800">{{ $lastPayment ? $lastPayment->created_at->format('Y-m-d') : 'हाल अपडेट छैन' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">स्थिति:</span>
                            <span class="bg-{{ $paymentStatus == 'Paid' ? 'green' : 'red' }}-100 text-{{ $paymentStatus == 'Paid' ? 'green' : 'red' }}-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $paymentStatus == 'Paid' ? 'भुक्तानी भएको' : 'बाकी' }}
                            </span>
                        </div>
                    </div>
                    
                    <button class="w-full mt-4 bg-amber-600 hover:bg-amber-700 text-white py-2 rounded-xl font-medium transition-colors">
                        <i class="fas fa-money-bill mr-2"></i>भुक्तानी गर्नुहोस्
                    </button>
                </div>
            </div>

            <!-- Today's Meal & Recent Circulars -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Today's Meal -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-utensils text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">आजको खानाको योजना</h3>
                    </div>
                    
                    @if($todayMeal)
                        <div class="space-y-3">
                            @if(is_array($todayMeal->items))
                                @if(isset($todayMeal->items['breakfast']))
                                    <div>
                                        <p class="font-medium text-gray-700">बिहानको खाना:</p>
                                        <p class="text-gray-600">{{ $todayMeal->items['breakfast'] }}</p>
                                    </div>
                                @endif
                                @if(isset($todayMeal->items['lunch']))
                                    <div>
                                        <p class="font-medium text-gray-700">दिउँसोको खाना:</p>
                                        <p class="text-gray-600">{{ $todayMeal->items['lunch'] }}</p>
                                    </div>
                                @endif
                                @if(isset($todayMeal->items['dinner']))
                                    <div>
                                        <p class="font-medium text-gray-700">रातिको खाना:</p>
                                        <p class="text-gray-600">{{ $todayMeal->items['dinner'] }}</p>
                                    </div>
                                @endif
                            @else
                                <p class="text-gray-600">मुख्य खाना: {{ $todayMeal->main_dish ?? 'उपलब्ध छैन' }}</p>
                                <p class="text-gray-600">साइड डिश: {{ $todayMeal->side_dish ?? 'उपलब्ध छैन' }}</p>
                            @endif
                            <p class="text-sm text-gray-500"><i class="fas fa-clock mr-1"></i>समय: {{ $todayMeal->serving_time ?? 'उपलब्ध छैन' }}</p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-utensils text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-500">आजको खानाको योजना हाल अपडेट छैन</p>
                        </div>
                    @endif
                    
                    <a href="{{ route('student.meal-menus') }}" class="block w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-2 rounded-xl font-medium text-center transition-colors">
                        <i class="fas fa-calendar mr-2"></i>सप्ताहिक मेनु हेर्नुहोस्
                    </a>
                </div>

                <!-- Recent Circulars -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-bullhorn text-indigo-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">हालैका सूचनाहरू</h3>
                    </div>
                    
                    @if($recentStudentCirculars && $recentStudentCirculars->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentStudentCirculars->take(3) as $circular)
                                <div class="border-l-4 border-indigo-500 pl-3 py-2 bg-indigo-50 rounded-r-lg">
                                    <p class="font-medium text-gray-800 text-sm">{{ Str::limit($circular->title, 40) }}</p>
                                    <p class="text-xs text-gray-600 mt-1">{{ $circular->created_at->diffForHumans() }}</p>
                                    @if(!$circular->recipients->where('user_id', auth()->id())->first()?->is_read)
                                        <span class="inline-block mt-1 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">नयाँ</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <a href="{{ route('student.circulars.index') }}" class="block w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-xl font-medium text-center transition-colors">
                            <i class="fas fa-list mr-2"></i>सबै सूचनाहरू हेर्नुहोस्
                        </a>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bullhorn text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-500">कुनै नयाँ सूचना छैन</p>
                            <a href="{{ route('student.circulars.index') }}" class="inline-block mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                सबै सूचनाहरू हेर्नुहोस्
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">द्रुत कार्यहरू</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('student.profile') }}" class="bg-blue-50 hover:bg-blue-100 p-3 rounded-xl text-center transition-colors group border border-blue-100">
                        <div class="text-blue-600 text-xl mb-1">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="text-blue-800 text-xs font-medium">मेरो प्रोफाइल</div>
                    </a>
                    
                    <a href="{{ route('student.meal-menus') }}" class="bg-green-50 hover:bg-green-100 p-3 rounded-xl text-center transition-colors group border border-green-100">
                        <div class="text-green-600 text-xl mb-1">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="text-green-800 text-xs font-medium">खानाको योजना</div>
                    </a>
                    
                    <a href="{{ route('student.circulars.index') }}" class="bg-indigo-50 hover:bg-indigo-100 p-3 rounded-xl text-center transition-colors group border border-indigo-100 relative">
                        <div class="text-indigo-600 text-xl mb-1">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="text-indigo-800 text-xs font-medium">सबै सूचनाहरू</div>
                        @if(($unreadCirculars ?? 0) > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                                {{ $unreadCirculars }}
                            </span>
                        @endif
                    </a>

                    <button class="bg-amber-50 hover:bg-amber-100 p-3 rounded-xl text-center transition-colors group border border-amber-100">
                        <div class="text-amber-600 text-xl mb-1">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="text-amber-800 text-xs font-medium">भुक्तानी गर्नुहोस्</div>
                    </button>
                </div>
            </div>

            <!-- Important Circulars -->
            @if($importantCirculars && $importantCirculars->count() > 0)
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-red-800">जरुरी सूचनाहरू</h3>
                </div>
                
                <div class="space-y-3">
                    @foreach($importantCirculars->take(2) as $circular)
                        <div class="bg-white rounded-xl p-3 border border-red-200">
                            <p class="font-bold text-red-800 text-sm">{{ $circular->title }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($circular->content, 60) }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $circular->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Upcoming Events -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-calendar-alt text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">आगामी घटनाहरू</h3>
                </div>
                
                @if($upcomingEvents->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingEvents->take(2) as $event)
                            <div class="border-l-4 border-purple-500 pl-3 py-2">
                                <p class="font-medium text-gray-800 text-sm">{{ $event->title }}</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $event->date->format('M j') }} at {{ $event->time }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    
                    <a href="{{ route('student.events') }}" class="block w-full mt-4 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-xl font-medium text-center transition-colors">
                        सबै घटनाहरू हेर्नुहोस्
                    </a>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500 text-sm">कुनै आगामी घटना छैन</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive functionality here
    console.log('Student dashboard loaded');
});
</script>
@endsection