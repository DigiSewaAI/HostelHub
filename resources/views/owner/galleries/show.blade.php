@extends('layouts.owner')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">{{ $gallery->title }}</h1>
    
    <div class="bg-white p-6 rounded-lg shadow">
        @if($gallery->type === 'photo')
            <img src="{{ asset('storage/'.$gallery->file_path) }}" alt="{{ $gallery->title }}" class="mb-4 max-w-full">
        @elseif($gallery->type === 'local_video')
            <video controls class="mb-4 w-full">
                <source src="{{ asset('storage/'.$gallery->file_path) }}" type="video/mp4">
            </video>
        @else
            <iframe src="https://www.youtube.com/embed/{{ getYoutubeId($gallery->external_link) }}" 
                    class="w-full h-96 mb-4"></iframe>
        @endif

        <div class="prose max-w-none">
            {!! $gallery->description !!}
        </div>

        <div class="mt-4 flex space-x-4">
            <span class="px-3 py-1 rounded-full text-sm 
                  {{ $gallery->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $gallery->is_active ? 'Active' : 'Inactive' }}
            </span>
            @if($gallery->is_featured)
                <span class="px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                    Featured
                </span>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.gallery.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Back to List
        </a>
    </div>
</div>
@endsection