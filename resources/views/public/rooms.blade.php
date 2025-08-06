@extends('layouts.app')

@section('title', 'उपलब्ध कोठाहरू')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">हाम्रो उपलब्ध कोठाहरू</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($rooms as $room)
        <div class="bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-shadow border border-gray-100">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600">
                <div class="text-2xl font-bold text-white mb-2">कोठा नं. {{ $room->room_number }}</div>
                <p class="text-blue-100">भुक्तानी: रु {{ number_format($room->price) }}</p>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        {{ $room->capacity }} जनासम्म बस्न सकिने
                    </span>
                    <span class="text-sm text-gray-500">
                        {{ $room->students_count }}/{{ $room->capacity }} भरिएको
                    </span>
                </div>
                <p class="text-gray-600 mb-6">{{ $room->description }}</p>
                <a href="#" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-colors text-center block">
                    अहिले बुक गर्नुहोस्
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $rooms->links() }}
    </div>
</div>
@endsection
