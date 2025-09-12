@extends('layouts.admin')

@section('title', 'ग्यालेरी विवरण')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">{{ $gallery->title }}</h1>
    
    <div class="bg-white p-6 rounded-lg shadow">
        @if($gallery->media_type === 'photo' && $gallery->file_path)
            <img src="{{ asset('storage/'.$gallery->file_path) }}" alt="{{ $gallery->title }}" class="mb-4 max-w-full">
        @elseif($gallery->media_type === 'local_video' && $gallery->file_path)
            <video controls class="mb-4 w-full">
                <source src="{{ asset('storage/'.$gallery->file_path) }}" type="video/mp4">
            </video>
        @elseif($gallery->media_type === 'external_video' && $gallery->external_link)
            @php 
                $youtubeId = '';
                $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
                preg_match($pattern, $gallery->external_link, $matches);
                $youtubeId = $matches[1] ?? '';
            @endphp
            @if($youtubeId)
                <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                        class="w-full h-96 mb-4" frameborder="0" allowfullscreen></iframe>
            @else
                <div class="bg-gray-200 h-96 flex items-center justify-center mb-4">
                    <p class="text-gray-500">अमान्य भिडियो लिंक</p>
                </div>
            @endif
        @endif

        <div class="prose max-w-none">
            <p>{{ $gallery->description }}</p>
        </div>

        <div class="mt-4 flex space-x-4">
            <span class="px-3 py-1 rounded-full text-sm 
                  {{ $gallery->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $gallery->is_active ? 'सक्रिय' : 'निष्क्रिय' }}
            </span>
            @if($gallery->is_featured)
                <span class="px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                    फिचर्ड
                </span>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.galleries.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            सूचीमा फर्कनुहोस्
        </a>
    </div>
</div>
@endsection
