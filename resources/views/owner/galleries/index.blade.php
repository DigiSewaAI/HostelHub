@extends('layouts.owner')

@section('title', 'ग्यालरी व्यवस्थापन')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 nepali">ग्यालरी व्यवस्थापन</h1>
        <a href="{{ route('owner.galleries.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium no-underline transition-colors">
            <i class="fas fa-plus mr-2"></i>नयाँ ग्यालरी थप्नुहोस्
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-blue-600 text-sm font-medium nepali">कुल ग्यालरी</p>
                    <p class="text-2xl font-bold text-blue-800">{{ $galleries->count() }}</p>
                </div>
                <div class="bg-blue-600 text-white p-3 rounded-lg">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>

        <div class="bg-green-50 p-4 rounded-xl border border-green-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-green-600 text-sm font-medium nepali">फिचर्ड</p>
                    <p class="text-2xl font-bold text-green-800">{{ $galleries->where('is_featured', true)->count() }}</p>
                </div>
                <div class="bg-green-600 text-white p-3 rounded-lg">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-purple-600 text-sm font-medium nepali">तस्बिरहरू</p>
                    <p class="text-2xl font-bold text-purple-800">{{ $galleries->where('media_type', 'image')->count() }}</p>
                </div>
                <div class="bg-purple-600 text-white p-3 rounded-lg">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-orange-600 text-sm font-medium nepali">भिडियोहरू</p>
                    <p class="text-2xl font-bold text-orange-800">{{ $galleries->where('media_type', '!=', 'image')->count() }}</p>
                </div>
                <div class="bg-orange-600 text-white p-3 rounded-lg">
                    <i class="fas fa-video"></i>
                </div>
            </div>
        </div>
    </div>

    @if($galleries->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($galleries as $gallery)
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
                <!-- Media Preview -->
                <div class="relative aspect-video bg-gray-200">
                    @if($gallery->media_type === 'image')
                        <img src="{{ $gallery->thumbnail_url }}" 
                             alt="{{ $gallery->title }}"
                             class="w-full h-full object-cover">
                    @elseif($gallery->media_type === 'external_video')
                        <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                            <i class="fas fa-play text-white text-3xl"></i>
                        </div>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                            <i class="fas fa-video text-white text-3xl"></i>
                        </div>
                    @endif

                    <!-- Status Badges -->
                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                        @if($gallery->is_featured)
                            <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full nepali">फिचर्ड</span>
                        @endif
                        @if(!$gallery->is_active)
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full nepali">निष्क्रिय</span>
                        @endif
                    </div>

                    <!-- Category Badge -->
                    <div class="absolute top-2 right-2">
                        <span class="bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded-full nepali">
                            {{ $gallery->category }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 text-sm mb-2 nepali">{{ Str::limit($gallery->title, 40) }}</h3>
                    
                    @if($gallery->description)
                        <p class="text-gray-600 text-xs mb-3 nepali">{{ Str::limit($gallery->description, 60) }}</p>
                    @endif

                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <span class="nepali">{{ $gallery->created_at->format('Y-m-d') }}</span>
                        <span class="capitalize nepali">{{ $gallery->media_type }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200">
                        <div class="flex space-x-2">
                            <form action="{{ route('owner.galleries.toggle-featured', $gallery) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-{{ $gallery->is_featured ? 'yellow' : 'gray' }}-600 hover:text-{{ $gallery->is_featured ? 'yellow' : 'yellow' }}-800 text-sm">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>

                            <form action="{{ route('owner.galleries.toggle-active', $gallery) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-{{ $gallery->is_active ? 'green' : 'gray' }}-600 hover:text-{{ $gallery->is_active ? 'green' : 'green' }}-800 text-sm">
                                    <i class="fas fa-{{ $gallery->is_active ? 'eye' : 'eye-slash' }}"></i>
                                </button>
                            </form>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('owner.galleries.edit', $gallery) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('owner.galleries.destroy', $gallery) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('के तपाईं यो ग्यालरी आइटम मेटाउन निश्चित हुनुहुन्छ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-images text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-600 nepali mb-2">कुनै ग्यालरी सामग्री छैन</h3>
            <p class="text-gray-500 nepali mb-6">आफ्नो होस्टलको ग्यालरी सुरु गर्नुहोस्</p>
            <a href="{{ route('owner.galleries.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium no-underline transition-colors">
                <i class="fas fa-plus mr-2"></i>पहिलो ग्यालरी थप्नुहोस्
            </a>
        </div>
    @endif
</div>
@endsection