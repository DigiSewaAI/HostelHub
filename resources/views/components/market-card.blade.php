@props(['listing'])

@php
    $owner = $listing->owner;
    // Try multiple possible relationships for hostel
    $hostel = optional($owner->ownerProfile)->hostel ?? $owner->hostel ?? null;
    $hostelName = $hostel->name ?? ($owner->ownerProfile->hostel_name ?? null) ?? 'स्वतन्त्र विक्रेता';
    $hostelLogo = $hostel->logo ?? $hostel->logo_path ?? null;
    $ownerName = $owner->name;
    
    // Try all possible phone fields from different tables
    $phone = $owner->phone 
        ?? optional($owner->ownerProfile)->phone 
        ?? $hostel->phone 
        ?? $hostel->contact_phone 
        ?? $hostel->phone_number 
        ?? $owner->contact_phone 
        ?? optional($owner->ownerProfile)->contact_phone 
        ?? null;
    
    $location = $listing->location ?? $hostel->address ?? optional($owner->ownerProfile)->address ?? 'स्थान उल्लेख छैन';
@endphp

<div class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100 hover:border-transparent max-w-sm mx-auto w-full">
    {{-- Image with overlay badge --}}
    <div class="relative aspect-video overflow-hidden bg-gray-100">
        @if($listing->media->first())
            <img src="{{ asset('storage/'.$listing->media->first()->file_path) }}"
                 alt="{{ $listing->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-50">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        {{-- Condition badge --}}
        @if($listing->condition)
            <span class="absolute top-2 left-2 px-1.5 py-0.5 text-xs font-semibold rounded-full
                {{ $listing->condition === 'new' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                {{ $listing->condition === 'new' ? 'नयाँ' : 'प्रयोग गरिएको' }}
            </span>
        @endif
    </div>

    <div class="p-3">
        {{-- Title & price --}}
        <div class="flex justify-between items-start gap-1 mb-1">
            <h3 class="font-medium text-sm text-gray-800 line-clamp-1">
                <a href="{{ route('public.bazar.show', $listing->slug) }}" class="hover:text-primary-600 transition-colors">
                    {{ $listing->title }}
                </a>
            </h3>
            <div class="text-base font-bold text-primary-600 whitespace-nowrap">
                रु. {{ number_format($listing->price) }}
                @if($listing->price_type === 'negotiable')
                    <span class="text-[10px] font-normal text-gray-500">(मोलमोलाइ)</span>
                @endif
            </div>
        </div>

        {{-- Hostel info (primary seller) --}}
        <div class="flex items-center gap-1.5 mb-1">
            @if($hostelLogo)
                <img src="{{ asset('storage/'.$hostelLogo) }}" alt="{{ $hostelName }}" class="w-5 h-5 rounded-full object-cover">
            @else
                <div class="w-5 h-5 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-[10px] font-bold">
                    {{ substr($hostelName, 0, 1) }}
                </div>
            @endif
            <span class="text-xs font-medium text-gray-700">{{ $hostelName }}</span>
        </div>

        {{-- Owner (secondary) --}}
        <div class="text-[10px] text-gray-500 mb-1 flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            {{ $ownerName }}
        </div>

        {{-- Location & phone --}}
        <div class="flex flex-col gap-1 text-[10px] text-gray-500 mb-2">
            <div class="flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ $location }}</span>
            </div>
            
            @if($phone)
                <div class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>{{ $phone }}</span>
                </div>
            @else
                <div class="flex items-center gap-1 text-gray-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span class="italic">सम्पर्क उपलब्ध छैन</span>
                </div>
            @endif
        </div>

        {{-- Footer: views & button --}}
        <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100">
            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>{{ $listing->views }} views</span>
            </div>
            <a href="{{ route('public.bazar.show', $listing->slug) }}"
               class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-700">
                विवरण
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </div>
</div>