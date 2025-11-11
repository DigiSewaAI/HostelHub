@extends('layouts.admin')

@section('title', 'ड्यासबोर्ड')

@section('content')
    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-700 font-medium">ड्यासबोर्ड डाटा लोड हुँदैछ...</p>
        </div>
    </div>

    @isset($error)
        <!-- Error Alert with Retry Option -->
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded flex justify-between items-center">
            <div>
                <p class="font-medium">{{ $error }}</p>
            </div>
            <button onclick="window.location.reload()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                <i class="fas fa-redo mr-2"></i>पुनः प्रयास गर्नुहोस्
            </button>
        </div>
    @endisset

    <div class="space-y-4">
        <!-- Contact Notifications Bar -->
        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-envelope text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold">
                        तपाईंसँग 
                        <span class="text-red-600">{{ $unreadContacts ?? 0 }}</span> नपढिएका सन्देशहरू 
                        र जम्मा <span class="text-blue-600">{{ $totalContacts ?? 0 }}</span> सम्पर्क सूचनाहरू छन्
                    </h3>
                    <p class="text-sm text-gray-600">
                        आज <span class="text-green-600">{{ $todayContacts ?? 0 }}</span> नयाँ सन्देशहरू प्राप्त भएका छन्
                    </p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.contacts.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-eye mr-2"></i>
                    सबै सन्देशहरू
                </a>
                @if(($unreadContacts ?? 0) > 0)
                <a href="{{ route('admin.contacts.index', ['filter' => 'unread']) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    नपढिएका ({{ $unreadContacts ?? 0 }})
                </a>
                @endif
            </div>
        </div>

        <!-- Circular Notification Bell Bar -->
        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-indigo-100 p-3 rounded-full mr-4">
                    <i class="fas fa-bell text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold">तपाईंसँग {{ $totalCirculars ?? 0 }} सूचनाहरू छन्</h3>
                    <p class="text-sm text-gray-600">हालसम्म {{ $totalCirculars ?? 0 }} सूचनाहरू प्राप्त भएका छन्</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.circulars.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-bullhorn mr-2"></i>
                    सूचनाहरू हेर्नुहोस्
                </a>
            </div>
        </div>

        <!-- Recent Contacts Section -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">📨 हालका सम्पर्क सन्देशहरू</h2>
                <a href="{{ route('admin.contacts.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    सबै हेर्नुहोस्
                </a>
            </div>
            
            <div class="space-y-3">
                @forelse($recentContacts ?? [] as $contact)
                <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors {{ $contact->is_read ? 'bg-white' : 'bg-yellow-50 border-yellow-200' }}">
                    <div class="flex items-center flex-1">
                        <div class="{{ $contact->is_read ? 'bg-gray-100' : 'bg-yellow-100' }} p-2 rounded-lg mr-3">
                            <i class="fas {{ $contact->is_read ? 'fa-envelope-open text-gray-600' : 'fa-envelope text-yellow-600' }}"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-800 text-sm">
                                    {{ $contact->name }}
                                    @if(!$contact->is_read)
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full ml-2">नयाँ</span>
                                    @endif
                                </p>
                                <span class="text-xs text-gray-500">{{ $contact->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $contact->subject }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ \Illuminate\Support\Str::limit($contact->message, 60) }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2 ml-4">
                        <a href="{{ route('admin.contacts.show', $contact) }}" 
                           class="text-blue-600 hover:text-blue-800 p-2 transition-colors" 
                           title="हेर्नुहोस्">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(!$contact->is_read)
                        <form action="{{ route('admin.contacts.mark-read', $contact) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 p-2 transition-colors" title="पढियो चिन्ह लगाउनुहोस्">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-3"></i>
                    <h4 class="text-lg font-semibold text-gray-600">कुनै सम्पर्क सन्देश छैन</h4>
                    <p class="text-gray-500">हाल कुनै सम्पर्क सन्देश प्राप्त भएको छैन</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            <!-- Left Column - Stats -->
            <div class="xl:col-span-2 space-y-4">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Hostels Card -->
                    <div class="stat-card bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-4 rounded-lg shadow-sm card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">होस्टलहरू</h3>
                                <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($metrics['total_hostels']) }}</p>
                            </div>
                            <div class="bg-blue-500 text-white p-3 rounded-lg">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">कुल दर्ता भएका होस्टलहरू</p>
                    </div>
                    
                    <!-- Rooms Card -->
                    <div class="stat-card bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-4 rounded-lg shadow-sm card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">कोठाहरू</h3>
                                <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($metrics['total_rooms']) }}</p>
                            </div>
                            <div class="bg-green-500 text-white p-3 rounded-lg">
                                <i class="fas fa-door-open text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            <span class="text-green-600 font-medium">{{ $metrics['room_occupancy'] }}%</span> अधिभोग दर
                        </p>
                    </div>
                    
                    <!-- Students Card -->
                    <div class="stat-card bg-gradient-to-r from-amber-50 to-amber-100 border-l-4 border-amber-500 p-4 rounded-lg shadow-sm card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">विद्यार्थीहरू</h3>
                                <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($metrics['total_students']) }}</p>
                            </div>
                            <div class="bg-amber-500 text-white p-3 rounded-lg">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">कुल दर्ता भएका विद्यार्थीहरू</p>
                    </div>
                    
                    <!-- Contacts Card -->
                    <div class="stat-card bg-gradient-to-r from-purple-50 to-purple-100 border-l-4 border-purple-500 p-4 rounded-lg shadow-sm card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">सम्पर्क सन्देश</h3>
                                <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($totalContacts ?? 0) }}</p>
                            </div>
                            <div class="bg-purple-500 text-white p-3 rounded-lg">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            <span class="text-red-600 font-medium">{{ $unreadContacts ?? 0 }}</span> नपढिएका
                        </p>
                        <a href="{{ route('admin.contacts.index') }}" class="text-xs text-purple-600 hover:text-purple-800 font-medium mt-2 inline-block">
                            सबै हेर्नुहोस् <i class="fas fa-arrow-circle-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Circular Statistics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Total Circulars Card -->
                    <div class="stat-card bg-gradient-to-r from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 p-4 rounded-lg shadow-sm card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">कुल सूचनाहरू</h3>
                                <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($totalCirculars ?? 0) }}</p>
                            </div>
                            <div class="bg-indigo-500 text-white p-3 rounded-lg">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">कुल प्रकाशित सूचनाहरू</p>
                    </div>
                    
                    <!-- Published Circulars -->
                    <div class="stat-card bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-4 rounded-lg shadow-sm card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">प्रकाशित</h3>
                                <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($publishedCirculars ?? 0) }}</p>
                            </div>
                            <div class="bg-green-500 text-white p-3 rounded-lg">
                                <i class="fas fa-paper-plane text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">सक्रिय सूचनाहरू</p>
                    </div>
                    
                    <!-- Urgent Circulars -->
                    <div class="stat-card bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-4 rounded-lg shadow-sm card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">जरुरी</h3>
                                <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($urgentCirculars ?? 0) }}</p>
                            </div>
                            <div class="bg-red-500 text-white p-3 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">जरुरी प्राथमिकताका</p>
                    </div>
                    
                    <!-- Read Rate -->
                    <div class="stat-card bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-4 rounded-lg shadow-sm card-hover">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">पढ्ने दर</h3>
                                <p class="text-3xl font-bold mt-2 text-gray-900">{{ $circularReadRate ?? 0 }}%</p>
                            </div>
                            <div class="bg-blue-500 text-white p-3 rounded-lg">
                                <i class="fas fa-eye text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">सम्पूर्ण पढ्ने दर</p>
                    </div>
                </div>

                <!-- Room Status -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg text-center hover:bg-blue-100 transition-colors border border-blue-100">
                        <div class="text-blue-600 text-2xl font-bold">{{ number_format($metrics['available_rooms']) }}</div>
                        <div class="text-sm text-blue-800 font-medium">उपलब्ध कोठाहरू</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg text-center hover:bg-green-100 transition-colors border border-green-100">
                        <div class="text-green-600 text-2xl font-bold">{{ number_format($metrics['occupied_rooms']) }}</div>
                        <div class="text-sm text-green-800 font-medium">अधिभृत कोठाहरू</div>
                    </div>
                    <div class="bg-amber-50 p-4 rounded-lg text-center hover:bg-amber-100 transition-colors border border-amber-100">
                        <div class="text-amber-600 text-2xl font-bold">{{ number_format($metrics['reserved_rooms']) }}</div>
                        <div class="text-sm text-amber-800 font-medium">आरक्षित कोठाहरू</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg text-center hover:bg-red-100 transition-colors border border-red-100">
                        <div class="text-red-600 text-2xl font-bold">{{ number_format($metrics['maintenance_rooms']) }}</div>
                        <div class="text-sm text-red-800 font-medium">मर्मतकोठाहरू</div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">हालका गतिविधिहरू</h2>
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            <i class="fas fa-filter mr-1"></i> छान्नुहोस्
                        </button>
                    </div>
                    
                    <div class="relative">
                        <!-- Timeline style activities -->
                        <div class="border-l-2 border-gray-200 ml-4 pb-4">
                            @if($metrics['recent_students']->isEmpty() && $metrics['recent_contacts']->isEmpty() && $metrics['recent_hostels']->isEmpty() && $metrics['recent_documents']->isEmpty() && empty($recentCirculars))
                            <!-- Empty State -->
                            <div class="text-center py-6">
                                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                <h4 class="text-lg font-semibold text-gray-600">कुनै गतिविधि छैन</h4>
                                <p class="text-gray-500 mb-4">हाल कुनै गतिविधि दर्ता भएको छैन</p>
                                <a href="{{ route('admin.students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                    <i class="fas fa-plus mr-2"></i> नयाँ विद्यार्थी थप्नुहोस्
                                </a>
                            </div>
                            @else
                            <!-- Recent Circulars -->
                            @foreach($recentCirculars ?? [] as $circular)
                            <div class="flex items-start mb-4">
                                <div class="bg-indigo-500 rounded-full p-2 -ml-3 mt-1 relative">
                                    <i class="fas fa-bullhorn text-white text-sm"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="font-semibold text-gray-800">नयाँ सूचना प्रकाशित</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $circular->title }} - {{ \Illuminate\Support\Str::limit($circular->content, 50) }}
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                            <i class="far fa-clock mr-1"></i> {{ $circular->created_at->diffForHumans() }}
                                        </span>
                                        <span class="text-xs text-indigo-600 bg-indigo-100 py-1 px-2 rounded-full ml-2">
                                            {{ $circular->priority_nepali ?? 'सामान्य' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            <!-- Recent Students -->
                            @foreach($metrics['recent_students'] as $student)
                            <div class="flex items-start mb-4">
                                <div class="bg-red-500 rounded-full p-2 -ml-3 mt-1 relative">
                                    <i class="fas fa-user-plus text-white text-sm"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="font-semibold text-gray-800">नयाँ विद्यार्थी दर्ता</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $student->name }} ({{ optional(optional($student->room)->hostel)->name ?? 'अज्ञात होस्टल' }})
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                            <i class="far fa-clock mr-1"></i> {{ $student->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            <!-- Recent Contacts -->
                            @foreach($metrics['recent_contacts'] as $contact)
                            <div class="flex items-start mb-4">
                                <div class="bg-blue-500 rounded-full p-2 -ml-3 mt-1 relative">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="font-semibold text-gray-800">नयाँ सम्पर्क सन्देश</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $contact->name }} - {{ \Illuminate\Support\Str::limit($contact->message, 50) }}</p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                            <i class="far fa-clock mr-1"></i> {{ $contact->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            <!-- Recent Hostels -->
                            @foreach($metrics['recent_hostels'] as $hostel)
                            <div class="flex items-start mb-4">
                                <div class="bg-amber-500 rounded-full p-2 -ml-3 mt-1 relative">
                                    <i class="fas fa-building text-white text-sm"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="font-semibold text-gray-800">नयाँ होस्टल दर्ता</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $hostel->name }} ({{ $hostel->rooms_count }} कोठाहरू)</p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                            <i class="far fa-clock mr-1"></i> {{ $hostel->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Recent Documents -->
                            @foreach($metrics['recent_documents'] as $document)
                            <div class="flex items-start mb-4">
                                <div class="bg-purple-500 rounded-full p-2 -ml-3 mt-1 relative">
                                    <i class="fas fa-file-upload text-white text-sm"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="font-semibold text-gray-800">नयाँ कागजात अपलोड</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $document->original_name }} 
                                        ({{ optional($document->student)->user->name ?? 'अज्ञात विद्यार्थी' }})
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                            <i class="far fa-clock mr-1"></i> {{ $document->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <!-- Activity Count -->
                        @if(!$metrics['recent_students']->isEmpty() || !$metrics['recent_contacts']->isEmpty() || !$metrics['recent_hostels']->isEmpty() || !$metrics['recent_documents']->isEmpty() || !empty($recentCirculars))
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">
                                देखाइएको: 
                                <span class="font-medium">
                                    {{ $metrics['recent_students']->count() + $metrics['recent_contacts']->count() + $metrics['recent_hostels']->count() + $metrics['recent_documents']->count() + count($recentCirculars ?? []) }}
                                </span> गतिविधिहरू
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Actions -->
            <div class="space-y-4">
                <!-- Quick Circular Actions -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">सूचना कार्यहरू</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.circulars.create') }}" class="p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-center transition-colors group border border-indigo-100">
                            <div class="text-indigo-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="font-medium text-indigo-800">नयाँ सूचना</div>
                        </a>
                        
                        <a href="{{ route('admin.circulars.index') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors group border border-green-100">
                            <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="font-medium text-green-800">सबै सूचनाहरू</div>
                        </a>
                        
                        <a href="{{ route('admin.circulars.analytics') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors group border border-blue-100">
                            <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="font-medium text-blue-800">विश्लेषण</div>
                        </a>
                        
                        <a href="{{ route('admin.circulars.templates') }}" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors group border border-purple-100">
                            <div class="text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-stamp"></i>
                            </div>
                            <div class="font-medium text-purple-800">टेम्प्लेटहरू</div>
                        </a>
                    </div>
                </div>

                <!-- Quick Contact Actions -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">सम्पर्क कार्यहरू</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.contacts.index') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors group border border-blue-100">
                            <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <div class="font-medium text-blue-800">सबै सन्देशहरू</div>
                        </a>
                        
                        <a href="{{ route('admin.contacts.index', ['filter' => 'unread']) }}" class="p-4 bg-red-50 hover:bg-red-100 rounded-lg text-center transition-colors group border border-red-100">
                            <div class="text-red-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="font-medium text-red-800">नपढिएका</div>
                            @if(($unreadContacts ?? 0) > 0)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $unreadContacts }}
                            </span>
                            @endif
                        </a>
                        
                        <a href="{{ route('admin.contacts.index', ['filter' => 'today']) }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors group border border-green-100">
                            <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="font-medium text-green-800">आजका</div>
                        </a>
                        
                        <a href="{{ route('admin.settings.index') }}#contact" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors group border border-purple-100">
                            <div class="text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="font-medium text-purple-800">सेटिङहरू</div>
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">द्रुत कार्यहरू</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.students.create') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors group border border-blue-100">
                            <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="font-medium text-blue-800">विद्यार्थी थप्नुहोस्</div>
                        </a>
                        
                        <a href="{{ route('admin.rooms.create') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors group border border-green-100">
                            <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div class="font-medium text-green-800">कोठा थप्नुहोस्</div>
                        </a>
                        
                        <a href="{{ route('admin.circulars.create') }}" class="p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-center transition-colors group border border-indigo-100">
                            <div class="text-indigo-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="font-medium text-indigo-800">सूचना थप्नुहोस्</div>
                        </a>
                        
                        <a href="{{ route('admin.circulars.analytics') }}" class="p-4 bg-teal-50 hover:bg-teal-100 rounded-lg text-center transition-colors group border border-teal-100">
                            <div class="text-teal-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="font-medium text-teal-800">विश्लेषण हेर्नुहोस्</div>
                        </a>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h3 class="text-xl font-bold mb-4">प्रणाली स्थिति</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-database text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold">डाटाबेस कनेक्सन</h4>
                                    <p class="text-sm text-gray-600">सफलतापूर्वक जडान भएको छ</p>
                                </div>
                            </div>
                            <div class="text-green-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-server text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold">सर्भर स्थिति</h4>
                                    <p class="text-sm text-gray-600">सबै सेवाहरू सामान्य रूपमा चलिरहेका छन्</p>
                                </div>
                            </div>
                            <div class="text-green-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-shield-alt text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold">सुरक्षा स्थिति</h4>
                                    <p class="text-sm text-gray-600">प्रणाली सुरक्षित रूपमा चलिरहेको छ</p>
                                </div>
                            </div>
                            <div class="text-green-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-bullhorn text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold">सूचना प्रणाली</h4>
                                    <p class="text-sm text-gray-600">{{ $totalCirculars ?? 0 }} सक्रिय सूचनाहरू</p>
                                </div>
                            </div>
                            <div class="text-blue-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-envelope text-purple-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold">सम्पर्क प्रणाली</h4>
                                    <p class="text-sm text-gray-600">{{ $totalContacts ?? 0 }} सन्देशहरू, {{ $unreadContacts ?? 0 }} नपढिएका</p>
                                </div>
                            </div>
                            <div class="text-purple-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h3 class="text-xl font-bold mb-4">अन्य कार्यहरू</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.hostels.create') }}" class="p-4 bg-amber-50 hover:bg-amber-100 rounded-lg text-center transition-colors group border border-amber-100">
                            <div class="text-amber-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="font-medium text-amber-800">होस्टल थप्नुहोस्</div>
                        </a>
                        
                        <a href="{{ route('admin.documents.index') }}" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors group border border-purple-100">
                            <div class="text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="font-medium text-purple-800">कागजातहरू हेर्नुहोस्</div>
                        </a>

                        <a href="{{ route('admin.contacts.index') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors group border border-blue-100">
                            <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="font-medium text-blue-800">सम्पर्कहरू हेर्नुहोस्</div>
                        </a>

                        <a href="{{ route('admin.settings.index') }}" class="p-4 bg-gray-50 hover:bg-gray-100 rounded-lg text-center transition-colors group border border-gray-100">
                            <div class="text-gray-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="font-medium text-gray-800">सेटिङहरू</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Show loading indicator when page is loading
document.addEventListener('DOMContentLoaded', function() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    if (loadingIndicator) {
        setTimeout(() => {
            loadingIndicator.classList.add('hidden');
        }, 500);
    }

    // Initialize real-time notification polling
    checkNewMessages();
    setInterval(checkNewMessages, 30000);
});

// Add hover effects to cards
document.querySelectorAll('.card-hover').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
        this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.1)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.04)';
    });
});

// Clear dashboard cache function
async function clearDashboardCache() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.classList.remove('hidden');
    }

    try {
        const response = await fetch('{{ route("admin.dashboard.clear-cache") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const result = await response.json();
        
        if (result.success) {
            // Show success message
            showNotification('क्यास सफलतापूर्वक मेटाइयो', 'success');
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('क्यास मेटाउन असफल भयो', 'error');
        }
    } catch (error) {
        console.error('Error clearing cache:', error);
        showNotification('त्रुटि भयो', 'error');
    } finally {
        if (loadingIndicator) {
            loadingIndicator.classList.add('hidden');
        }
    }
}

// Real-time notification polling for new messages
async function checkNewMessages() {
    try {
        const response = await fetch('/admin/dashboard/contacts-count');
        const data = await response.json();
        
        if (data.unreadCount > 0) {
            // Update notification badge
            const badge = document.getElementById('notification-badge');
            if (badge) {
                badge.textContent = data.unreadCount;
                badge.classList.remove('hidden');
            }
            
            // Show desktop notification if permission granted
            if (data.unreadCount > 0 && Notification.permission === 'granted') {
                new Notification('नयाँ सम्पर्क सन्देश', {
                    body: `तपाईंसँग ${data.unreadCount} नयाँ सन्देशहरू छन्`,
                    icon: '/images/logo.png'
                });
            }
        }
    } catch (error) {
        console.error('Error checking messages:', error);
    }
}

// Request notification permission
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add smooth scrolling for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Mark contact as read
async function markContactAsRead(contactId) {
    try {
        const response = await fetch(`/admin/contacts/${contactId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        if (response.ok) {
            // Update UI
            const contactElement = document.querySelector(`[data-contact-id="${contactId}"]`);
            if (contactElement) {
                contactElement.classList.remove('bg-yellow-50', 'border-yellow-200');
                contactElement.classList.add('bg-white');
                
                const icon = contactElement.querySelector('.fa-envelope');
                if (icon) {
                    icon.classList.remove('fa-envelope', 'text-yellow-600');
                    icon.classList.add('fa-envelope-open', 'text-gray-600');
                }
                
                const badge = contactElement.querySelector('.bg-red-500');
                if (badge) {
                    badge.remove();
                }
            }
            
            showNotification('सन्देश पढियोको रूपमा चिन्ह लगाइयो', 'success');
        }
    } catch (error) {
        console.error('Error marking contact as read:', error);
        showNotification('त्रुटि भयो', 'error');
    }
}
</script>

<style>
.card-hover {
    transition: all 0.3s ease;
}

.stat-card {
    transition: all 0.3s ease;
}

.loading-spinner {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    border: 3px solid rgba(59, 130, 246, 0.3);
    border-radius: 50%;
    border-top-color: #3b82f6;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Notification badge styles */
.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Contact status styles */
.contact-unread {
    background: #fef3c7;
    border-color: #f59e0b;
}

.contact-read {
    background: white;
    border-color: #e5e7eb;
}

/* Responsive improvements */
@media (max-width: 640px) {
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .contact-notification-bar .flex {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .contact-notification-bar .flex.space-x-2 {
        margin-top: 1rem;
        width: 100%;
        justify-content: space-between;
    }
}

/* Print styles */
@media print {
    .card-hover {
        transform: none !important;
        box-shadow: none !important;
    }
    
    .bg-gradient-to-r {
        background: white !important;
    }
    
    .notification-badge {
        display: none !important;
    }
}
</style>
@endsection