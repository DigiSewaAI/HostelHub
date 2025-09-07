@extends('layouts.owner')

@section('title', 'कोठाको विवरण')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">कोठाको विवरण</h1>
            <div class="flex space-x-3">
                <a href="{{ route('owner.rooms.edit', $room) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                    <i class="fas fa-edit mr-1"></i> सम्पादन गर्नुहोस्
                </a>
                <a href="{{ route('owner.rooms.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                    <i class="fas fa-arrow-left mr-1"></i> पछाडि जानुहोस्
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">मूल जानकारी</h2>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-32">कोठा नम्बर:</span>
                        <span class="text-gray-800">{{ $room->room_number }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-32">प्रकार:</span>
                        <span class="text-gray-800">
                            {{ $room->type == 'single' ? 'एकल' : ($room->type == 'double' ? 'डबल' : 'साझा') }}
                        </span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-32">क्षमता:</span>
                        <span class="text-gray-800">{{ $room->capacity }} विद्यार्थी</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-32">मूल्य:</span>
                        <span class="text-gray-800">रु. {{ number_format($room->price, 2) }}/महिना</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">स्थिति</h2>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-32">स्थिति:</span>
                        <span class="text-gray-800">
                            @if($room->status == 'available')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    उपलब्ध
                                </span>
                            @elseif($room->status == 'occupied')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    भरिएको
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    मर्मत सम्भार
                                </span>
                            @endif
                        </span>
                    </div>
                    
                    @if($room->status == 'occupied' && $room->student)
                    <div>
                        <span class="font-medium text-gray-700 w-32">विद्यार्थी:</span>
                        <div class="flex items-center mt-1">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                <span class="text-indigo-800 font-semibold">{{ substr($room->student->name, 0, 2) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $room->student->name }}</p>
                                <p class="text-gray-600 text-sm">{{ $room->student->college }} - {{ $room->student->course }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($room->description)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">विवरण</h2>
            <p class="text-gray-700">{{ $room->description }}</p>
        </div>
        @endif

        @if($room->status == 'occupied' && $room->student)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">विद्यार्थी जानकारी</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-3">
                        <span class="font-medium text-gray-700">इमेल:</span>
                        <p class="text-gray-800">{{ $room->student->email }}</p>
                    </div>
                    <div class="mb-3">
                        <span class="font-medium text-gray-700">फोन नम्बर:</span>
                        <p class="text-gray-800">{{ $room->student->phone }}</p>
                    </div>
                </div>
                <div>
                    <div class="mb-3">
                        <span class="font-medium text-gray-700">प्रवेश मिति:</span>
                        <p class="text-gray-800">{{ $room->student->admission_date->format('d M, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <span class="font-medium text-gray-700">अपेक्षित निस्कासन मिति:</span>
                        <p class="text-gray-800">{{ $room->student->expected_departure_date->format('d M, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-800 mb-3">अभिभावक जानकारी</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-700">नाम:</span>
                            <p class="text-gray-800">{{ $room->student->guardian_name }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">सम्बन्ध:</span>
                            <p class="text-gray-800">{{ $room->student->guardian_relationship }}</p>
                        </div>
                    </div>
                    <div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-700">फोन नम्बर:</span>
                            <p class="text-gray-800">{{ $room->student->guardian_phone }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection