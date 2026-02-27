@props(['listing'])

<div class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 hover:border-transparent">
    {{-- Image with overlay badge --}}
    <div class="relative aspect-video overflow-hidden bg-gray-100">
        @if($listing->media->first())
            <img src="{{ asset('storage/'.$listing->media->first()->file_path) }}"
                 alt="{{ $listing->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-50">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        {{-- Condition badge --}}
        @if($listing->condition)
            <span class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold rounded-full
                {{ $listing->condition === 'new' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                {{ $listing->condition === 'new' ? 'नयाँ' : 'प्रयोग गरिएको' }}
            </span>
        @endif
    </div>

    <div class="p-4">
        {{-- Title & price --}}
        <div class="flex justify-between items-start gap-2 mb-2">
            <h3 class="font-semibold text-gray-800 line-clamp-1">
                <a href="{{ route('public.bazar.show', $listing->slug) }}" class="hover:text-primary-600 transition-colors">
                    {{ $listing->title }}
                </a>
            </h3>
            <div class="text-lg font-bold text-primary-600 whitespace-nowrap">
                रु. {{ number_format($listing->price) }}
                @if($listing->price_type === 'negotiable')
                    <span class="text-xs font-normal text-gray-500">(मोलमोलाइ)</span>
                @endif
            </div>
        </div>

        {{-- Location & category --}}
        <div class="flex items-center gap-3 text-sm text-gray-500 mb-3">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $listing->location ?? 'स्थान उल्लेख छैन' }}
            </span>
            @if($listing->category)
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                    </svg>
                    {{ $listing->category->name_np }}
                </span>
            @endif
        </div>

        {{-- Description --}}
        <p class="text-gray-600 text-sm line-clamp-2 mb-3">
            {{ Str::limit($listing->description, 100) }}
        </p>

        {{-- Footer: views & button --}}
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-400 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ $listing->views }} हेराइ
            </span>
            <a href="{{ route('public.bazar.show', $listing->slug) }}"
               class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700">
                विवरण हेर्नुहोस्
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </div>
</div>