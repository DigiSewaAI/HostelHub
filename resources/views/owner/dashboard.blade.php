@extends('layouts.owner')

@section('title', 'होस्टल ड्यासबोर्ड')

@section('content')
@php
    $organizationId = session('current_organization_id');
    $ownerId = Auth::id();
    $hostelIds = \App\Models\Hostel::where('organization_id', $organizationId)
        ->where('owner_id', $ownerId)
        ->pluck('id');

    $roomIssuesTotal = \App\Models\RoomIssue::whereIn('hostel_id', $hostelIds)->count();
    $pendingRoomIssues = \App\Models\RoomIssue::whereIn('hostel_id', $hostelIds)
        ->where('status', 'pending')->count();
    $highPriorityRoomIssues = \App\Models\RoomIssue::whereIn('hostel_id', $hostelIds)
        ->where('priority', 'high')->count();
    $todayRoomIssues = \App\Models\RoomIssue::whereIn('hostel_id', $hostelIds)
        ->whereDate('created_at', today())->count();
    $recentRoomIssues = \App\Models\RoomIssue::whereIn('hostel_id', $hostelIds)
        ->with(['hostel', 'room', 'student.user'])->latest()->take(5)->get();

    $pendingBookingsCount = \App\Models\Booking::whereIn('hostel_id', $hostelIds)
        ->where('status', 'pending')->count();
    $pendingBookingRequests = \App\Models\BookingRequest::whereIn('hostel_id', $hostelIds)
        ->where('status', 'pending')->count();
    $totalPending = $pendingBookingsCount + $pendingBookingRequests;

    $ownerContacts = \App\Models\Contact::whereIn('hostel_id', $hostelIds)->get();
    $totalContacts = $ownerContacts->count();
    $unreadContacts = $ownerContacts->where('is_read', false)->count();
    $todayContacts = $ownerContacts->filter(fn($c) => $c->created_at->isToday())->count();
    $recentContacts = $ownerContacts->take(6)->sortByDesc('created_at');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    <!-- WELCOME CARD -->
    <div class="flex flex-col md:flex-row items-center justify-between bg-gradient-to-r from-indigo-50 to-indigo-100 p-6 rounded-2xl shadow-md mb-8 hover:shadow-lg transition-shadow">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">नमस्ते, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600 mt-1">यो तपाईंको होस्टल व्यवस्थापन ड्यासबोर्ड हो</p>
        </div>
        <a href="{{ url('/') }}" class="mt-4 md:mt-0 text-indigo-600 font-semibold hover:underline">← मुख्य पृष्ठमा जानुहोस्</a>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        @php
            $stats = [
                ['label'=>'कुल मासिक आय','value'=>$totalMonthlyRevenue ?? 0,'icon'=>'fa-rupee-sign','bg'=>'green','unit'=>'रु'],
                ['label'=>'औसत ओक्युपेन्सी','value'=>$averageOccupancy ?? 0,'icon'=>'fa-chart-line','bg'=>'blue','unit'=>'%'],
                ['label'=>'विद्यार्थीहरू','value'=>$totalStudents ?? 0,'icon'=>'fa-user-graduate','bg'=>'purple','unit'=>''],
                ['label'=>'एक्टिभ होस्टेल','value'=>$activeHostelsCount ?? 0,'icon'=>'fa-building','bg'=>'yellow','unit'=>''],
                ['label'=>'रूम समस्याहरू','value'=>$roomIssuesTotal ?? 0,'icon'=>'fa-exclamation-triangle','bg'=>'red','unit'=>''],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-{{ $stat['bg'] }}-50 rounded-2xl p-6 shadow hover:shadow-lg transition relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 font-medium">{{ $stat['label'] }}</span>
                <div class="text-{{ $stat['bg'] }}-500 text-xl"><i class="fas {{ $stat['icon'] }}"></i></div>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stat['unit'] }} {{ number_format($stat['value']) }}</h3>

            @if($stat['label']=='रूम समस्याहरू')
            <div class="flex justify-between mt-2 text-xs font-medium">
                <span>{{ $pendingRoomIssues ?? 0 }} पेन्डिङ</span>
                <span class="text-red-600">{{ $highPriorityRoomIssues ?? 0 }} जरुरी</span>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- PERFORMANCE OVERVIEW -->
    <div class="bg-white rounded-2xl shadow p-6 mb-8 hover:shadow-lg transition">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">कार्यसम्पादन अवलोकन</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-500 font-medium">पढ्ने दर</p>
                <p class="text-xl font-bold text-gray-800">{{ $circularReadRate ?? 0 }}%</p>
                <div class="w-full bg-gray-200 h-2 rounded-full mt-2">
                    <div class="h-2 rounded-full bg-gradient-to-r from-blue-400 to-blue-600" style="width: {{ $circularReadRate ?? 0 }}%"></div>
                </div>
            </div>
            <div>
                <p class="text-gray-500 font-medium">विद्यार्थी संलग्नता</p>
                <p class="text-xl font-bold text-gray-800">{{ $studentEngagement ?? 0 }}%</p>
                <div class="w-full bg-gray-200 h-2 rounded-full mt-2">
                    <div class="h-2 rounded-full bg-gradient-to-r from-green-400 to-green-600" style="width: {{ $studentEngagement ?? 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- HOSTEL SUMMARY -->
    @if(isset($hostel) && $hostel)
    <div class="bg-white rounded-2xl shadow p-6 mb-8 hover:shadow-lg transition">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">{{ $hostel->name }} को विवरण</h2>
            <div class="flex space-x-3">
                <a href="{{ route('owner.public-page.edit') }}" class="text-teal-600 hover:underline">सार्वजनिक पृष्ठ</a>
                <a href="{{ route('owner.hostels.show', $hostel) }}" class="text-blue-600 hover:underline">हेर्नुहोस्</a>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-700">
            <div>कोठा: <span class="font-medium">{{ $totalRooms ?? 0 }}</span></div>
            <div>अधिभृत: <span class="font-medium">{{ $occupiedRooms ?? 0 }}</span></div>
            <div>विद्यार्थी: <span class="font-medium">{{ $totalStudents ?? 0 }}</span></div>
            <div>पृष्ठ: <span class="font-medium {{ $hostel->getRawOriginal('is_published') ? 'text-green-600' : 'text-amber-600' }}">
                {{ $hostel->getRawOriginal('is_published') ? 'प्रकाशित' : 'मस्यौदा' }}</span></div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
            <div>मासिक भाडा: <span class="font-medium">रु {{ number_format($hostel->monthly_rent ?? 0) }}</span></div>
            <div>सुरक्षा जमानत: <span class="font-medium">रु {{ number_format($hostel->security_deposit ?? 0) }}</span></div>
        </div>
    </div>
    @endif

    <!-- TWO-COLUMN ACTIVITY FEEDS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- Left Column -->
    <div class="space-y-6">
        <!-- Recent Circulars -->
        <div class="bg-white rounded-2xl shadow p-5 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-800">पछिल्ला सूचनाहरू</h3>
                <a href="{{ route('owner.circulars.index') }}" class="text-indigo-600 hover:underline text-sm">सबै</a>
            </div>
            <div class="space-y-2">
                @forelse($recentCirculars ?? [] as $circular)
                    <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-2 last:border-0">
                        <span class="truncate max-w-[200px]">{{ Str::limit($circular->title, 40) }}</span>
                        <a href="{{ route('owner.circulars.show', $circular) }}" class="text-blue-600 hover:underline">हेर्नुहोस्</a>
                    </div>
                @empty
                    <div class="text-gray-500 py-2 text-sm">📭 कुनै सूचना छैन।</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Room Issues -->
        <div class="bg-white rounded-2xl shadow p-5 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-800">हालका रूम समस्याहरू</h3>
                <a href="{{ route('owner.room-issues.index') }}" class="text-red-600 hover:underline text-sm">सबै</a>
            </div>
            <div class="space-y-2">
                @forelse($recentRoomIssues ?? [] as $issue)
                    <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-2 last:border-0">
                        <span class="truncate max-w-[180px]">{{ $issue->student->user->name ?? 'अज्ञात' }} - {{ Str::limit($issue->description, 25) }}</span>
                        <span class="text-xs px-2 py-1 rounded-full 
                            {{ $issue->priority == 'high' ? 'bg-red-100 text-red-600' : ($issue->priority == 'medium' ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600') }}">
                            {{ $issue->priority == 'high' ? 'जरुरी' : ($issue->priority == 'medium' ? 'मध्यम' : 'कम') }}
                        </span>
                    </div>
                @empty
                    <div class="text-gray-500 py-2 text-sm">✅ कुनै समस्या छैन।</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-6">
        <!-- Recent Contacts -->
        <div class="bg-white rounded-2xl shadow p-5 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-800">सम्पर्क सन्देशहरू</h3>
                <a href="{{ route('owner.contacts.index') }}" class="text-blue-600 hover:underline text-sm">सबै</a>
            </div>
            <div class="space-y-2">
                @forelse($recentContacts as $contact)
                    <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-2 last:border-0">
                        <span class="truncate max-w-[180px]">{{ $contact->name }} - {{ Str::limit($contact->subject, 25) }}</span>
                        @if(!$contact->is_read)
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">नयाँ</span>
                        @endif
                    </div>
                @empty
                    <div class="text-gray-500 py-2 text-sm">📭 कुनै सन्देश छैन।</div>
                @endforelse
            </div>
            <div class="flex justify-between mt-3 text-sm text-gray-500">
                <span>कुल: {{ $totalContacts }}</span>
                <span>नपढिएका: {{ $unreadContacts }}</span>
                <span>आज: {{ $todayContacts }}</span>
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="bg-white rounded-2xl shadow p-5 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-800">कागजातहरू</h3>
                <a href="{{ route('owner.documents.index') }}" class="text-purple-600 hover:underline text-sm">सबै</a>
            </div>
            <div class="space-y-2">
                @forelse($recentDocuments ?? [] as $document)
                    <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-2 last:border-0">
                        <span class="truncate max-w-[200px]">{{ Str::limit($document->original_name, 35) }}</span>
                        <a href="{{ route('owner.documents.show', $document) }}" class="text-blue-600 hover:underline">हेर्नुहोस्</a>
                    </div>
                @empty
                    <div class="text-gray-500 py-2 text-sm">📄 कुनै कागजात छैन।</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

    <!-- QUICK ACTIONS -->
    <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">द्रुत कार्यहरू</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @php
                $quickActions = [
                    ['route'=>'owner.rooms.index','label'=>'कोठाहरू','icon'=>'fa-door-open','color'=>'blue'],
                    ['route'=>'owner.students.index','label'=>'विद्यार्थी','icon'=>'fa-users','color'=>'green'],
                    ['route'=>'owner.payments.index','label'=>'भुक्तानी','icon'=>'fa-money-bill-wave','color'=>'purple'],
                    ['route'=>'owner.meal-menus.index','label'=>'खानाको योजना','icon'=>'fa-utensils','color'=>'amber'],
                    ['route'=>'owner.circulars.index','label'=>'सूचना','icon'=>'fa-bullhorn','color'=>'indigo'],
                    ['route'=>'owner.contacts.index','label'=>'सन्देश','icon'=>'fa-envelope','color'=>'blue'],
                    ['route'=>'owner.room-issues.index','label'=>'रूम समस्या','icon'=>'fa-exclamation-triangle','color'=>'red'],
                    ['route'=>'owner.galleries.index','label'=>'ग्यालरी','icon'=>'fa-images','color'=>'teal'],
                    ['route'=>'owner.documents.index','label'=>'कागजात','icon'=>'fa-file-alt','color'=>'purple'],
                    ['route'=>'owner.public-page.edit','label'=>'सार्वजनिक पृष्ठ','icon'=>'fa-globe','color'=>'teal'],
                ];
            @endphp

            @foreach($quickActions as $action)
            <a href="{{ route($action['route']) }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex flex-col items-center justify-center transition border border-gray-100 min-h-[100px] hover:scale-105 transform">
                <div class="bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-500 p-3 rounded-full mb-2">
                    <i class="fas {{ $action['icon'] }} text-xl"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">{{ $action['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection