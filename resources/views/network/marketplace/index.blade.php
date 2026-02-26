@extends('layouts.owner')

@section('title', 'बजार सूची')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item active" aria-current="page">बजार सूची</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">बजार सूची</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('network.marketplace.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> नयाँ सूची थप्नुहोस्
        </a>
    </div>
</div>

<!-- फिल्टर फारम -->
<form method="GET" action="{{ route('network.marketplace.index') }}" class="row g-3 mb-4">
    <div class="col-md-3">
        <label for="type" class="form-label">प्रकार</label>
        <select name="type" id="type" class="form-select">
            <option value="">सबै</option>
            @foreach(['sale', 'lease', 'partnership', 'investment'] as $type)
                <option value="{{ $type }}" @selected(request('type')==$type)>
                    @switch($type)
                        @case('sale') बिक्री @break
                        @case('lease') भाडा @break
                        @case('partnership') साझेदारी @break
                        @case('investment') लगानी @break
                    @endswitch
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label for="status" class="form-label">स्थिति</label>
        <select name="status" id="status" class="form-select">
            <option value="">सबै</option>
            <option value="approved" @selected(request('status')=='approved')>स्वीकृत</option>
            <option value="pending" @selected(request('status')=='pending')>पेन्डिङ</option>
            <option value="sold" @selected(request('status')=='sold')>बिक्री भयो</option>
            <option value="closed" @selected(request('status')=='closed')>बन्द</option>
        </select>
    </div>
    <div class="col-md-2">
        <label for="visibility" class="form-label">दृश्यता</label>
        <select name="visibility" id="visibility" class="form-select">
            <option value="">सबै</option>
            <option value="private" @selected(request('visibility')=='private')>केवल मालिक</option>
            <option value="both" @selected(request('visibility')=='both')>सार्वजनिक+मालिक</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="location" class="form-label">स्थान</label>
        <input type="text" class="form-control" id="location" name="location" value="{{ request('location') }}" placeholder="काठमाडौं">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">खोजी गर्नुहोस्</button>
    </div>
    <div class="col-12 text-end">
        <a href="{{ route('network.marketplace.index') }}" class="btn btn-outline-secondary btn-sm">फिल्टर हटाउनुहोस्</a>
    </div>
</form>

<!-- सूची कार्डहरू -->
@if($listings->count())
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($listings as $listing)
        <div class="col">
            <div class="card h-100 shadow-sm">
                @if($listing->media->count())
                    <img src="{{ Storage::url($listing->media->first()->file_path) }}" class="card-img-top" alt="{{ $listing->title }}" style="height: 180px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                        <span class="text-muted">तस्वीर छैन</span>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $listing->title }}</h5>
                    <p class="card-text">{{ Str::limit($listing->description, 80) }}</p>
                    <div class="mb-2">
                        @if($listing->category)
                            <span class="badge bg-info text-dark">{{ $listing->category->name_np }}</span>
                        @endif
                        @if($listing->condition)
                            <span class="badge bg-{{ $listing->condition == 'new' ? 'success' : 'warning' }} text-dark">
                                {{ $listing->condition == 'new' ? 'नयाँ' : 'प्रयोग गरिएको' }}
                            </span>
                        @endif
                    </div>
                    <p class="card-text small">
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $listing->location ?? 'स्थान उल्लेख छैन' }}<br>
                        <strong>मूल्य:</strong> रू. {{ number_format($listing->price) }}
                        @if($listing->price_type == 'negotiable')
                            <span class="text-muted">(मोलमोलाइ हुने)</span>
                        @endif<br>
                        <strong>मात्रा:</strong> {{ $listing->quantity }}<br>
                        <span class="badge bg-{{ $listing->status === 'approved' ? 'success' : ($listing->status === 'pending' ? 'warning' : 'secondary') }}">
                            @switch($listing->status)
                                @case('approved') स्वीकृत @break
                                @case('pending') पेन्डिङ @break
                                @case('sold') बिक्री भयो @break
                                @case('closed') बन्द @break
                                @default {{ $listing->status }}
                            @endswitch
                        </span>
                        @if($listing->visibility == 'both')
                            <span class="badge bg-primary">सार्वजनिक</span>
                        @endif
                    </p>
                    <p class="card-text small">
                        <strong>मालिक:</strong> {{ $listing->owner->name }}<br>
                        @if($listing->owner->phone)
                            <i class="bi bi-telephone"></i> {{ $listing->owner->phone }}
                        @endif
                    </p>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                    <a href="{{ route('network.marketplace.show', $listing->slug) }}" class="btn btn-sm btn-outline-primary">विवरण</a>
                    @if($listing->owner_id === Auth::id())
                        <a href="{{ route('network.marketplace.edit', $listing->slug) }}" class="btn btn-sm btn-outline-secondary">सम्पादन</a>
                    @else
                        <a href="{{ route('network.marketplace.contact', $listing->id) }}" class="btn btn-sm btn-primary">सम्पर्क</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">
        {{ $listings->withQueryString()->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-store fa-4x text-muted mb-3"></i>
        <h4>कुनै सूची फेला परेन</h4>
        <p class="text-muted">हाल कुनै लिस्टिङ उपलब्ध छैन। कृपया फिल्टर परिवर्तन गर्नुहोस् वा नयाँ सूची थप्नुहोस्।</p>
        <a href="{{ route('network.marketplace.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> नयाँ सूची थप्नुहोस्
        </a>
        <a href="{{ route('network.marketplace.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> फिल्टर हटाउनुहोस्
        </a>
    </div>
@endif
@endsection