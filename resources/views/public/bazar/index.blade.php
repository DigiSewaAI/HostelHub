@extends('layouts.frontend')

@section('page-title', 'HostelHub Bazar - खरिद बिक्री बजार | HostelHub')
@section('og-title', 'HostelHub Bazar - नेटवर्क बजार')
@section('og-description', 'होस्टल सञ्चालकहरूले राखेका सामान, सेवा र अवसरहरू हेर्नुहोस्।')
@section('page-class', 'marketplace-page')
@section('container-class', 'no-padding')

@section('content')
<div class="container mx-auto px-4 py-8 lg:py-12">
    {{-- Header with search --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">HostelHub Bazar</h1>
            <p class="text-lg text-gray-600 mt-1">नेटवर्कका मालिकहरूले राखेका सामान, सेवा र अवसरहरू</p>
        </div>
        <div class="md:w-80">
            <form action="{{ route('public.bazar.index') }}" method="GET" class="flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="खोज्नुहोस्..."
                       class="flex-1 rounded-l-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                <button type="submit" class="bg-primary-600 text-white px-4 rounded-r-lg hover:bg-primary-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Filter sidebar --}}
        <aside class="lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-24">
                <h2 class="font-semibold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    फिल्टर
                </h2>

                <form action="{{ route('public.bazar.index') }}" method="GET" id="filterForm">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <div class="space-y-4">
                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">वर्ग</label>
                            <select name="category" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">सबै वर्गहरू</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Location --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">स्थान</label>
                            <input type="text" name="location" value="{{ request('location') }}"
                                   placeholder="जस्तै: काठमाडौं"
                                   class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        {{-- Price range --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">मूल्य दायरा (रु.)</label>
                            <div class="flex gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                       placeholder="न्यूनतम"
                                       class="w-1/2 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                       placeholder="अधिकतम"
                                       class="w-1/2 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        {{-- Condition --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">अवस्था</label>
                            <select name="condition" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">सबै</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>नयाँ</option>
                                <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>प्रयोग गरिएको</option>
                            </select>
                        </div>

                        {{-- Sort --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">क्रमबद्ध</label>
                            <select name="sort" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>नयाँ पहिले</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>मूल्य (घट्दो)</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>मूल्य (बढ्दो)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-5 space-y-2">
                        <button type="submit" class="w-full bg-primary-600 text-white py-2 px-4 rounded-lg hover:bg-primary-700 transition font-medium">
                            फिल्टर लागू गर्नुहोस्
                        </button>
                        <a href="{{ route('public.bazar.index') }}"
                           class="w-full block text-center py-2 px-4 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition font-medium">
                            फिल्टर हटाउनुहोस्
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        {{-- Listings grid --}}
        <main class="flex-1">
            @if($listings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="listings-grid">
                    @foreach($listings as $listing)
                        <x-market-card :listing="$listing" />
                    @endforeach
                </div>

                {{-- Pagination (hidden by default, used by infinite scroll) --}}
                <div class="mt-10" id="pagination-links" style="display: none;">
                    {{ $listings->withQueryString()->links() }}
                </div>

                {{-- Load more / infinite scroll trigger --}}
                <div class="mt-8 text-center" id="infinite-scroll-trigger">
                    <div class="inline-flex items-center gap-2 text-gray-500">
                        <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>थप लोड हुँदै…</span>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">कुनै सूची फेला परेन</h3>
                    <p class="text-gray-500">कृपया फिल्टर परिवर्तन गरेर पुन: प्रयास गर्नुहोस्।</p>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ---------- INFINITE SCROLL ----------
        const grid = document.getElementById('listings-grid');
        const trigger = document.getElementById('infinite-scroll-trigger');
        const paginationLinks = document.getElementById('pagination-links');

        if (!grid || !trigger || !paginationLinks) return;

        let loading = false;
        let nextPageUrl = null;

        // Extract next page URL from pagination links
        const nextLink = paginationLinks.querySelector('a[rel="next"]');
        if (nextLink) {
            nextPageUrl = nextLink.href;
        } else {
            trigger.style.display = 'none'; // No more pages
        }

        // Intersection Observer to detect when trigger is visible
        const observer = new IntersectionObserver(async (entries) => {
            const entry = entries[0];
            if (entry.isIntersecting && nextPageUrl && !loading) {
                loading = true;
                trigger.style.display = 'block'; // Show spinner

                try {
                    const response = await fetch(nextPageUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const html = await response.text();

                    // Parse the response HTML to extract new cards and next URL
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newGrid = doc.getElementById('listings-grid');
                    const newPagination = doc.getElementById('pagination-links');

                    if (newGrid) {
                        // Append new cards
                        newGrid.querySelectorAll('.group').forEach(card => {
                            grid.appendChild(card.cloneNode(true));
                        });
                    }

                    // Update next page URL
                    if (newPagination) {
                        const newNextLink = newPagination.querySelector('a[rel="next"]');
                        nextPageUrl = newNextLink ? newNextLink.href : null;
                    } else {
                        nextPageUrl = null;
                    }

                    if (!nextPageUrl) {
                        trigger.style.display = 'none'; // No more pages
                    }
                } catch (error) {
                    console.error('Error loading more listings:', error);
                    trigger.style.display = 'none';
                } finally {
                    loading = false;
                }
            }
        }, { threshold: 0.1 });

        observer.observe(trigger);

        // ---------- SKELETON LOADER ON INITIAL? Not needed because we show actual content immediately.
        // But if you want skeleton before first load, you'd need to hide grid and show skeletons.
        // Since we already have server-rendered content, we skip.
    });
</script>
@endpush