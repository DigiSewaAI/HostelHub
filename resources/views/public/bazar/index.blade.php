@extends('layouts.frontend')

@section('page-title', 'HostelHub Bazar - खरिद बिक्री बजार | HostelHub')
@section('og-title', 'HostelHub Bazar - नेटवर्क बजार')
@section('og-description', 'होस्टल सञ्चालकहरूले राखेका सामान, सेवा र अवसरहरू हेर्नुहोस्।')
@section('page-class', 'marketplace-page')
@section('container-class', 'no-padding')

@section('content')
<div class="container mx-auto px-4 py-4 lg:py-6">
    {{-- Header with search --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
        <div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">HostelHub Bazar</h1>
            <p class="text-lg text-gray-600 mt-1">नेटवर्कका मालिकहरूले राखेका सामान, सेवा र अवसरहरू</p>
        </div>
        <div class="md:w-80">
            <form action="{{ route('public.bazar.index') }}" method="GET" class="flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="खोज्नुहोस्..."
                       class="flex-1 rounded-l-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-r-lg hover:bg-primary-700 transition flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="text-sm">खोज</span>
                </button>
            </form>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Filter sidebar --}}
        <aside class="lg:w-56 flex-shrink-0">
            <div class="bg-sky-50 rounded-xl shadow-sm border border-sky-100 p-4 sticky top-24">
                <h2 class="font-semibold text-base text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    फिल्टर
                </h2>

                <form action="{{ route('public.bazar.index') }}" method="GET" id="filterForm">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <div class="space-y-3">
                        {{-- Category --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">वर्ग</label>
                            <select name="category" class="w-full text-sm rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
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
                            <label class="block text-xs font-medium text-gray-700 mb-1">स्थान</label>
                            <input type="text" name="location" value="{{ request('location') }}"
                                   placeholder="जस्तै: काठमाडौं"
                                   class="w-full text-sm rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        {{-- Price range --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">मूल्य दायरा (रु.)</label>
                            <div class="flex gap-1">
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                       placeholder="न्यूनतम"
                                       class="w-1/2 text-sm rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                       placeholder="अधिकतम"
                                       class="w-1/2 text-sm rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        {{-- Condition --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">अवस्था</label>
                            <select name="condition" class="w-full text-sm rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">सबै</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>नयाँ</option>
                                <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>प्रयोग गरिएको</option>
                            </select>
                        </div>

                        {{-- Sort --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">क्रमबद्ध</label>
                            <select name="sort" class="w-full text-sm rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>नयाँ पहिले</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>मूल्य (घट्दो)</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>मूल्य (बढ्दो)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <button type="submit" class="w-full bg-primary-600 text-white py-2 px-3 text-sm rounded-lg hover:bg-primary-700 transition font-medium">
                            फिल्टर लागू गर्नुहोस्
                        </button>
                        <a href="{{ route('public.bazar.index') }}"
                           class="w-full block text-center py-2 px-3 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition font-medium">
                            फिल्टर हटाउनुहोस्
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        {{-- Listings grid --}}
        <main class="flex-1">
            @if($listings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="listings-grid">
                    @foreach($listings as $listing)
                        <x-market-card :listing="$listing" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $listings->withQueryString()->links() }}
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