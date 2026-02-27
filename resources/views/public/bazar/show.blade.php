@extends('layouts.frontend')

@section('page-title', $listing->title . ' - HostelHub Bazar')
@section('og-title', $listing->title)
@section('og-description', Str::limit($listing->description, 160))
@section('page-class', 'marketplace-page')
@section('container-class', 'no-padding')

@section('content')
<div class="container mx-auto px-4 py-8 lg:py-12">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('public.bazar.index') }}" class="hover:text-primary-600 transition">बजार</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-800 font-medium">{{ $listing->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Gallery & Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Gallery --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($listing->media->count() > 0)
                    <div x-data="{ activeSlide: 0 }" class="relative">
                        <div class="aspect-video bg-gray-100">
                            <img :src="'{{ asset('storage/') }}/' + {{ $listing->media->map(fn($m) => $m->file_path)->toJson() }}[activeSlide]"
                                 alt="{{ $listing->title }}"
                                 class="w-full h-full object-contain">
                        </div>
                        @if($listing->media->count() > 1)
                            <div class="flex gap-2 p-4 overflow-x-auto">
                                @foreach($listing->media as $index => $media)
                                    <button @click="activeSlide = {{ $index }}"
                                            class="flex-shrink-0 w-20 h-20 rounded border-2 overflow-hidden"
                                            :class="activeSlide === {{ $index }} ? 'border-primary-600' : 'border-transparent'">
                                        <img src="{{ asset('storage/'.$media->file_path) }}" alt=""
                                             class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="aspect-video bg-gray-100 flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Details card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $listing->title }}</h1>

                <div class="flex flex-wrap gap-2 mb-4">
                    @if($listing->category)
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">{{ $listing->category->name_np }}</span>
                    @endif
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ $listing->views }} हेराइ
                    </span>
                </div>

                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($listing->description)) !!}
                </div>

                {{-- Key info grid --}}
                <dl class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <dt class="text-xs text-gray-500">मूल्य</dt>
                        <dd class="text-lg font-bold text-primary-600">रु. {{ number_format($listing->price) }}
                            @if($listing->price_type == 'negotiable')
                                <span class="text-sm font-normal text-gray-500">(मोलमोलाइ)</span>
                            @endif
                        </dd>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <dt class="text-xs text-gray-500">मात्रा</dt>
                        <dd class="text-lg font-semibold">{{ $listing->quantity }}</dd>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <dt class="text-xs text-gray-500">स्थान</dt>
                        <dd class="text-base">{{ $listing->location ?? 'उल्लेख छैन' }}</dd>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <dt class="text-xs text-gray-500">प्रकार</dt>
                        <dd class="text-base">
                            @switch($listing->type)
                                @case('sale') बिक्री @break
                                @case('lease') भाडा @break
                                @case('partnership') साझेदारी @break
                                @case('investment') लगानी @break
                                @default {{ $listing->type }}
                            @endswitch
                        </dd>
                    </div>
                </dl>

                <p class="text-xs text-gray-400 mt-4">
                    प्रकाशित मिति: {{ $listing->created_at->format('Y-m-d') }}
                </p>
            </div>
        </div>

        {{-- Right: Seller & Related --}}
        <div class="space-y-6">
            {{-- Seller info component --}}
            <x-seller-info :listing="$listing" />

            {{-- Related listings --}}
            @if(isset($related) && $related->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="font-semibold text-lg text-gray-800 mb-4">सम्बन्धित सूचीहरू</h2>
                    <div class="space-y-4">
                        @foreach($related as $rel)
                            <div class="flex gap-3 group items-center">
                                <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                    @if($rel->media->first())
                                        <img src="{{ asset('storage/'.$rel->media->first()->file_path) }}" alt=""
                                             class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-gray-800 group-hover:text-primary-600 truncate">{{ $rel->title }}</h3>
                                    <p class="text-sm text-primary-600 font-bold">रु. {{ number_format($rel->price) }}</p>
                                </div>
                                <a href="{{ route('public.bazar.show', $rel->slug) }}" 
                                   class="flex-shrink-0 bg-primary-600 hover:bg-primary-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors duration-200">
                                    हेर्नुहोस्
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection