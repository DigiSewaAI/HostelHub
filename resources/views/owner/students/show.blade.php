@extends('layouts.owner')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">विद्यार्थी विवरण</h1>
        <a href="{{ route('owner.students.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            ⬅ फर्कनुहोस्
        </a>
    </div>

    {{-- Student Card --}}
    <div class="bg-white shadow-md rounded-lg p-6">
        @if($student->image)
    @php
        // यदि image मा '/' छ भने पूरा पथ प्रयोग गर्नुहोस्, नभए 'students/' जोड्नुहोस् (पुरानो समर्थन)
        $imagePath = str_contains($student->image, '/') ? $student->image : 'students/' . $student->image;
    @endphp
    <div class="flex justify-center mb-4">
        <img src="{{ asset('storage/'.$imagePath) }}" 
             alt="{{ $student->name }}" 
             class="w-44 h-48 object-cover rounded-lg shadow-md border-2 border-gray-200">
    </div>
@endif
        <h2 class="text-xl font-semibold text-blue-700 mb-4">{{ $student->name }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left Side --}}
            <div>
                <p><strong>College:</strong> {{ $student->college }}</p>
                <p><strong>Phone:</strong> {{ $student->phone }}</p>
                <p><strong>Date of Birth:</strong> {{ $student->dob ? $student->dob->format('d M, Y') : 'N/A' }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
                <p><strong>Address:</strong> {{ $student->address }}</p>
            </div>

            {{-- Right Side --}}
            <div>
                <p><strong>Guardian Name:</strong> {{ $student->guardian_name }}</p>
                <p><strong>Guardian Contact:</strong> {{ $student->guardian_contact }}</p>
                <p><strong>Relation:</strong> {{ $student->guardian_relation }}</p>
                <p><strong>Guardian Address:</strong> {{ $student->guardian_address }}</p>
                <p><strong>Room:</strong> 
                    @if($student->room)
                        {{ $student->room->room_number }} ({{ $student->room->hostel->name }})
                    @else
                        Not Assigned
                    @endif
                </p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-blue-50 rounded-lg border">
                <p class="text-sm text-gray-600">Admission Date</p>
                <p class="text-lg font-bold text-blue-700">{{ $student->admission_date ? $student->admission_date->format('d M, Y') : 'N/A' }}</p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg border">
                <p class="text-sm text-gray-600">Status</p>
                <p class="text-lg font-bold text-green-700 capitalize">{{ $student->status }}</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-lg border">
                <p class="text-sm text-gray-600">Payment Status</p>
                <p class="text-lg font-bold text-yellow-700 capitalize">{{ $student->payment_status }}</p>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="mt-6 flex gap-4">
        <a href="{{ route('owner.students.edit', $student->id) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
            ✏ सम्पादन गर्नुहोस्
        </a>
        <form action="{{ route('owner.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('के तपाईं पक्का delete गर्न चाहनुहुन्छ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg shadow">
                🗑 Delete
            </button>
        </form>
    </div>
</div>
@endsection