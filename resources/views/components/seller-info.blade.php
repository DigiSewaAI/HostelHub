@props(['listing'])

@php
    $owner = $listing->owner;
    // Try multiple possible relationships for hostel
    $hostel = optional($owner->ownerProfile)->hostel ?? $owner->hostel ?? null;
    $hostelName = $hostel->name ?? ($owner->ownerProfile->hostel_name ?? null) ?? 'स्वतन्त्र विक्रेता';
    $hostelLogo = $hostel->logo ?? $hostel->logo_path ?? null;
    $ownerName = $owner->name;
    $ownerEmail = $owner->email;
    
    // Try all possible phone fields from different tables
    $ownerPhone = $owner->phone 
        ?? optional($owner->ownerProfile)->phone 
        ?? $owner->contact_phone 
        ?? optional($owner->ownerProfile)->contact_phone 
        ?? null;
    
    $hostelPhone = $hostel->phone 
        ?? $hostel->contact_phone 
        ?? $hostel->phone_number 
        ?? null;
    
    $phone = $hostelPhone ?? $ownerPhone;
    
    $hostelAddress = $hostel->address 
        ?? optional($owner->ownerProfile)->address 
        ?? $listing->location 
        ?? null;
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
    <h2 class="font-semibold text-lg text-gray-800 mb-4">विक्रेता जानकारी</h2>

    <div class="flex items-center gap-3 mb-4">
        @if($hostelLogo)
            <img src="{{ asset('storage/'.$hostelLogo) }}" alt="{{ $hostelName }}" class="w-12 h-12 rounded-full object-cover">
        @else
            <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-xl font-bold">
                {{ substr($hostelName, 0, 1) }}
            </div>
        @endif
        <div>
            <div class="font-medium text-gray-800">{{ $hostelName }}</div>
            <div class="text-sm text-gray-500 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ $ownerName }}
            </div>
        </div>
    </div>

    {{-- Contact details --}}
    <div class="space-y-2 text-sm text-gray-600 mb-4">
        @if($hostelAddress)
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ $hostelAddress }}</span>
            </div>
        @endif
        
        @if($phone)
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>{{ $phone }}</span>
            </div>
        @endif
        
        @if($ownerEmail)
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>{{ $ownerEmail }}</span>
            </div>
        @endif
    </div>

    {{-- Contact button --}}
    @auth
        @if(auth()->id() !== $listing->owner_id)
            <form method="POST" action="{{ route('network.marketplace.contact', $listing->id) }}" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-primary-600 text-white text-center py-2 px-4 rounded-lg hover:bg-primary-700 transition font-medium flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    सन्देश पठाउनुहोस्
                </button>
            </form>
        @endif
    @else
        <a href="{{ route('login') }}"
           class="block w-full border border-primary-600 text-primary-600 text-center py-2 px-4 rounded-lg hover:bg-primary-50 transition font-medium">
            सम्पर्क गर्न लगइन गर्नुहोस्
        </a>
    @endauth
</div>