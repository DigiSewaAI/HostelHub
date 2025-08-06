@extends('layouts.admin')

@section('title', 'ड्यासबोर्ड')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total Students -->
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">सक्रिय विद्यार्थीहरू</p>
                <p class="text-2xl font-bold text-gray-800">{{ $metrics['total_students'] }}</p>
            </div>
        </div>
    </div>

    <!-- Room Occupancy -->
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                <i class="fas fa-bed text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">कोठा भराइ दर</p>
                <p class="text-2xl font-bold text-gray-800">{{ $metrics['room_occupancy'] }}%</p>
            </div>
        </div>
    </div>

    <!-- Available Rooms -->
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <i class="fas fa-door-open text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">उपलब्ध कोठाहरू</p>
                <p class="text-2xl font-bold text-gray-800">{{ $metrics['available_rooms'] }}</p>
            </div>
        </div>
    </div>

    <!-- Occupied Rooms -->
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                <i class="fas fa-door-closed text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">भरिएका कोठाहरू</p>
                <p class="text-2xl font-bold text-gray-800">{{ $metrics['occupied_rooms'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Students -->
<div class="mt-8 bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold">हालका विद्यार्थीहरू</h3>
        <a href="{{ route('admin.students.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
            सबै हेर्नुहोस् →
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
                @foreach($metrics['recent_students'] as $student)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $student->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $student->room ? $student->room->name : 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="{{
                            $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        }} px-2 inline-block py-1 rounded-full text-xs font-semibold">
                            {{ $student->status == 'active' ? 'सक्रिय' : 'निष्क्रिय' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Room Status Chart -->
<div class="mt-8 bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-semibold mb-4">कोठा स्थिति</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <i class="fas fa-door-open text-green-500"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">उपलब्ध</p>
                    <p class="text-xl font-bold text-gray-800">{{ $metrics['available_rooms'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-2 rounded-full mr-3">
                    <i class="fas fa-door-closed text-yellow-500"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">भरिएको</p>
                    <p class="text-xl font-bold text-gray-800">{{ $metrics['occupied_rooms'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="bg-red-100 p-2 rounded-full mr-3">
                    <i class="fas fa-tools text-red-500"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">मर्मत सम्भार</p>
                    <p class="text-xl font-bold text-gray-800">{{ $metrics['maintenance_rooms'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
