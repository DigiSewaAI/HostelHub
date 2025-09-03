@extends('layouts.admin')

@section('title', 'ड्यासबोर्ड')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Dashboard Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">स्वागतम्, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600">आजको दिनको होस्टल प्रबन्धन अवलोकन</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Students -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600 mr-4">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">कुल विद्यार्थीहरू</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $metrics['total_students'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Room Occupancy -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-bed text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">कोठा भराइ दर</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $metrics['room_occupancy'] ?? 0 }}%</p>
                </div>
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-door-open text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">उपलब्ध कोठाहरू</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $metrics['available_rooms'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Occupied Rooms -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-door-closed text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">भरिएका कोठाहरू</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $metrics['occupied_rooms'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">छिटो कार्यहरू</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                सबै हेर्नुहोस् →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.students.create') }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                <div class="p-3 rounded-full bg-indigo-100 mb-2">
                    <i class="fas fa-user-plus text-indigo-600 text-xl"></i>
                </div>
                <span class="text-center text-gray-700 font-medium">विद्यार्थी थप्नुहोस्</span>
            </a>
            <a href="{{ route('admin.rooms.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="p-3 rounded-full bg-green-100 mb-2">
                    <i class="fas fa-door-open text-green-600 text-xl"></i>
                </div>
                <span class="text-center text-gray-700 font-medium">कोठा थप्नुहोस्</span>
            </a>
            <a href="{{ route('admin.meals.create') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="p-3 rounded-full bg-purple-100 mb-2">
                    <i class="fas fa-utensils text-purple-600 text-xl"></i>
                </div>
                <span class="text-center text-gray-700 font-medium">खाना थप्नुहोस्</span>
            </a>
            <a href="{{ route('admin.hostels.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="p-3 rounded-full bg-blue-100 mb-2">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <span class="text-center text-gray-700 font-medium">होस्टल थप्नुहोस्</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Students -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">हालका विद्यार्थीहरू</h3>
                <a href="{{ route('admin.students.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm flex items-center">
                    <i class="fas fa-arrow-right mr-1"></i> सबै हेर्नुहोस्
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">नाम</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">इमेल</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">कोठा</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">स्थिति</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($metrics['recent_students']) && $metrics['recent_students']->count() > 0)
                            @foreach($metrics['recent_students'] as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                            <span class="text-indigo-800 font-semibold">{{ substr($student->name, 0, 2) }}</span>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $student->room ? $student->room->room_number : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="{{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 inline-block py-1 rounded-full text-xs font-semibold">
                                        {{ $student->status == 'active' ? 'सक्रिय' : 'निष्क्रिय' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-user-friends text-4xl mb-3 text-gray-300"></i>
                                    <p>कुनै विद्यार्थी थपिएको छैन</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Room Status Chart -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">कोठा स्थिति</h3>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                    <span class="text-xs text-gray-600 mr-4">उपलब्ध</span>
                    <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                    <span class="text-xs text-gray-600 mr-4">भरिएको</span>
                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                    <span class="text-xs text-gray-600">मर्मत सम्भार</span>
                </div>
            </div>
            
            <div class="relative h-64 mb-6">
                <canvas id="roomStatusChart" width="400" height="200"></canvas>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-500">उपलब्ध</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $metrics['available_rooms'] ?? 0 }}</p>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-500">भरिएको</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $metrics['occupied_rooms'] ?? 0 }}</p>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-500">मर्मत सम्भार</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $metrics['maintenance_rooms'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Hostels -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">हालका होस्टलहरू</h3>
            <a href="{{ route('admin.hostels.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm flex items-center">
                <i class="fas fa-arrow-right mr-1"></i> सबै हेर्नुहोस्
            </a>
        </div>
        <div class="divide-y divide-gray-200">
            @if(isset($metrics['recent_hostels']) && $metrics['recent_hostels']->count() > 0)
                @foreach($metrics['recent_hostels'] as $hostel)
                <div class="p-6 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center mr-4">
                            <i class="fas fa-building text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $hostel->name }}</h4>
                            <p class="text-gray-500 text-sm">{{ $hostel->address }}, {{ $hostel->city }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-600 text-sm">क्षमता</p>
                        <p class="font-medium">{{ $hostel->max_capacity }} विद्यार्थी</p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-building text-4xl mb-3 text-gray-300"></i>
                    <p>कुनै होस्टल थपिएको छैन</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('roomStatusChart').getContext('2d');
        const roomStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['उपलब्ध', 'भरिएको', 'मर्मत सम्भार'],
                datasets: [{
                    data: [
                        {{ $metrics['available_rooms'] ?? 0 }},
                        {{ $metrics['occupied_rooms'] ?? 0 }},
                        {{ $metrics['maintenance_rooms'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(55, 65, 81, 0.9)',
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' कोठा';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection