@extends('layouts.app')

@section('title', 'ग्यालरी')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">हाम्रो ग्यालरी</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($galleries as $gallery)
        <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <img src="{{ $gallery->image }}" alt="{{ $gallery->title }}" class="w-full h-48 object-cover">
            <div class="p-4">
                <h2 class="text-xl font-bold">{{ $gallery->title }}</h2>
                <p class="text-gray-600 mt-2">{{ $gallery->description }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $galleries->links() }}
    </div>
</div>
@endsection
