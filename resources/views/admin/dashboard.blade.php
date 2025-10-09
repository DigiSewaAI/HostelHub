@extends('layouts.admin')

@section('title', 'ड्यासबोर्ड')

@section('content')
    @isset($error)
        <!-- Error Alert -->
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <p>{{ $error }}</p>
        </div>
    @endisset

    <!-- Notification Bell Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-bell text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-semibold">तपाईंसँग {{ $metrics['total_contacts'] }} सम्पर्क सूचनाहरू छन्</h3>
                <p class="text-sm text-gray-600">हालसम्म {{ $metrics['total_contacts'] }} सूचनाहरू प्राप्त भएका छन्</p>
            </div>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-eye mr-2"></i>
            सबै हेर्नुहोस्
        </button>
    </div>

    <!-- System Overview Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-bold mb-6">प्रणाली संक्षिप्त विवरण</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Hostels Card -->
            <div class="stat-card bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-5 rounded-lg shadow-sm card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">होस्टलहरू</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-900">{{ $metrics['total_hostels'] }}</p>
                    </div>
                    <div class="bg-blue-500 text-white p-3 rounded-lg">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3">कुल दर्ता भएका होस्टलहरू</p>
            </div>
            
            <!-- Rooms Card -->
            <div class="stat-card bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-5 rounded-lg shadow-sm card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">कोठाहरू</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-900">{{ $metrics['total_rooms'] }}</p>
                    </div>
                    <div class="bg-green-500 text-white p-3 rounded-lg">
                        <i class="fas fa-door-open text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3">
                    <span class="text-green-600 font-medium">{{ $metrics['room_occupancy'] }}%</span> अधिभोग दर
                </p>
            </div>
            
            <!-- Students Card -->
            <div class="stat-card bg-gradient-to-r from-amber-50 to-amber-100 border-l-4 border-amber-500 p-5 rounded-lg shadow-sm card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">विद्यार्थीहरू</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-900">{{ $metrics['total_students'] }}</p>
                    </div>
                    <div class="bg-amber-500 text-white p-3 rounded-lg">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3">कुल दर्ता भएका विद्यार्थीहरू</p>
            </div>
            
            <!-- Contacts Card -->
            <div class="stat-card bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-5 rounded-lg shadow-sm card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">सम्पर्कहरू</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-900">{{ $metrics['total_contacts'] }}</p>
                    </div>
                    <div class="bg-red-500 text-white p-3 rounded-lg">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3">कुल प्राप्त सम्पर्क सूचनाहरू</p>
            </div>
        </div>
        
        <!-- Room Status Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-50 p-4 rounded-lg text-center">
                <div class="text-blue-600 text-2xl font-bold">{{ $metrics['available_rooms'] }}</div>
                <div class="text-sm text-blue-800">उपलब्ध कोठाहरू</div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg text-center">
                <div class="text-green-600 text-2xl font-bold">{{ $metrics['occupied_rooms'] }}</div>
                <div class="text-sm text-green-800">अधिभृत कोठाहरू</div>
            </div>
            <div class="bg-amber-50 p-4 rounded-lg text-center">
                <div class="text-amber-600 text-2xl font-bold">{{ $metrics['reserved_rooms'] }}</div>
                <div class="text-sm text-amber-800">आरक्षित कोठाहरू</div>
            </div>
            <div class="bg-red-50 p-4 rounded-lg text-center">
                <div class="text-red-600 text-2xl font-bold">{{ $metrics['maintenance_rooms'] }}</div>
                <div class="text-sm text-red-800">मर्मतकोठाहरू</div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="border-t pt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">हालका गतिविधिहरू</h3>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-history mr-1"></i> सबै गतिविधि हेर्नुहोस्
                </button>
            </div>
            
            <div class="relative">
                <!-- Timeline style activities -->
                <div class="border-l-2 border-gray-200 ml-4 pb-10">
                    <!-- Recent Students -->
                    @foreach($metrics['recent_students'] as $student)
                    <div class="flex items-start mb-6">
                        <div class="bg-red-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-user-plus text-white text-sm"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-gray-800">नयाँ विद्यार्थी दर्ता</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $student->name }} ({{ $student->room->hostel->name ?? 'अज्ञात होस्टल' }})
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
                    <div class="flex items-start mb-6">
                        <div class="bg-blue-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-envelope text-white text-sm"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-gray-800">नयाँ सम्पर्क सन्देश</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $contact->name }} - {{ Str::limit($contact->message, 50) }}</p>
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
                    <div class="flex items-start mb-6">
                        <div class="bg-amber-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-building text-white text-sm"></i>
                        </div>
                        <div class="ml-6">
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
                    
                    @if($metrics['recent_students']->isEmpty() && $metrics['recent_contacts']->isEmpty() && $metrics['recent_hostels']->isEmpty())
                    <div class="flex items-start mb-6">
                        <div class="bg-gray-300 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-info text-white text-sm"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-gray-800">कुनै गतिविधि छैन</h4>
                            <p class="text-sm text-gray-600 mt-1">हाल कुनै गतिविधि दर्ता भएको छैन</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Info Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">प्रणाली स्थिति</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-database text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">डाटाबेस कनेक्सन</h4>
                            <p class="text-sm text-gray-600">सफलतापूर्वक जडान भएको छ</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-server text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">सर्भर स्थिति</h4>
                            <p class="text-sm text-gray-600">सबै सेवाहरू सामान्य रूपमा चलिरहेका छन्</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-shield-alt text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">सुरक्षा स्थिति</h4>
                            <p class="text-sm text-gray-600">प्रणाली सुरक्षित रूपमा चलिरहेको छ</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">द्रुत कार्यहरू</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.students.create') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors">
                    <div class="text-blue-600 text-2xl mb-2">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="font-medium text-blue-800">विद्यार्थी थप्नुहोस्</div>
                </a>
                
                <a href="{{ route('admin.rooms.create') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors">
                    <div class="text-green-600 text-2xl mb-2">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="font-medium text-green-800">कोठा थप्नुहोस्</div>
                </a>
                
                <a href="{{ route('admin.hostels.create') }}" class="p-4 bg-amber-50 hover:bg-amber-100 rounded-lg text-center transition-colors">
                    <div class="text-amber-600 text-2xl mb-2">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="font-medium text-amber-800">होस्टल थप्नुहोस्</div>
                </a>
                
                <a href="{{ route('admin.reports.index') }}" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors">
                    <div class="text-purple-600 text-2xl mb-2">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="font-medium text-purple-800">प्रतिवेदन हेर्नुहोस्</div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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
    </script>
@endsection

<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .stat-card {
        transition: all 0.3s ease;
    }
</style>