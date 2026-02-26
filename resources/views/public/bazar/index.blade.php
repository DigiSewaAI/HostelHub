@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold">HostelHub Bazar</h1>
            <p class="lead">नेटवर्कका मालिकहरूले राखेका सामानहरू, सेवाहरू र अवसरहरू यहाँ हेर्नुहोस्।</p>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-md-end">
            <form action="{{ route('public.bazar.index') }}" method="GET" class="w-100">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="खोज्नुहोस्..." value="{{ request('q') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">फिल्टर</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('public.bazar.index') }}" method="GET">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">वर्ग</label>
                            <select name="category" class="form-select">
                                <option value="">सबै वर्गहरू</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">स्थान</label>
                            <input type="text" name="location" class="form-control" value="{{ request('location') }}" placeholder="जस्तै: काठमाडौं">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">मूल्य दायरा</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control" placeholder="न्यूनतम" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control" placeholder="अधिकतम" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">अवस्था</label>
                            <select name="condition" class="form-select">
                                <option value="">सबै</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>नयाँ</option>
                                <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>प्रयोग गरिएको</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">क्रमबद्ध</label>
                            <select name="sort" class="form-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>नयाँ पहिले</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>मूल्य (घट्दो)</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>मूल्य (बढ्दो)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">फिल्टर लागू गर्नुहोस्</button>
                        <a href="{{ route('public.bazar.index') }}" class="btn btn-outline-secondary w-100 mt-2">फिल्टर हटाउनुहोस्</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            @if($listings->count() > 0)
                <div class="row g-4">
                    @foreach($listings as $listing)
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100 shadow-sm hover-shadow transition">
                                {{-- पहिलो तस्वीर देखाउने --}}
                                @if($listing->media->first())
                                    <img src="{{ asset('storage/'.$listing->media->first()->file_path) }}" class="card-img-top" alt="{{ $listing->title }}" style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif

                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $listing->title }}</h5>
                                        @if($listing->condition == 'new')
                                            <span class="badge bg-success">नयाँ</span>
                                        @elseif($listing->condition == 'used')
                                            <span class="badge bg-warning text-dark">प्रयोग गरिएको</span>
                                        @endif
                                    </div>

                                    <p class="card-text text-muted small">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $listing->location ?? 'स्थान उल्लेख छैन' }}
                                    </p>

                                    <p class="card-text text-truncate">{{ Str::limit($listing->description, 80) }}</p>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            @if($listing->category)
                                                <span class="badge bg-info text-dark">{{ $listing->category->name_np }}</span>
                                            @endif
                                        </div>
                                        <div class="fw-bold text-primary">
                                            रु. {{ number_format($listing->price) }}
                                            @if($listing->price_type == 'negotiable')
                                                <small class="text-muted fw-normal">(मोलमोलाइ)</small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i> {{ $listing->views }} हेराइ
                                        </small>
                                        <a href="{{ route('public.bazar.show', $listing->slug) }}" class="btn btn-sm btn-outline-primary">विवरण हेर्नुहोस्</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5">
                    {{ $listings->withQueryString()->links() }}
                </div>
            @else
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-box-open fa-4x mb-3 text-muted"></i>
                    <h4>कुनै सूची फेला परेन</h4>
                    <p>कृपया फिल्टर परिवर्तन गरेर पुन: प्रयास गर्नुहोस्।</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-shadow:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        transition: box-shadow .3s ease;
    }
    .transition {
        transition: all .3s ease;
    }
</style>
@endpush
@endsection