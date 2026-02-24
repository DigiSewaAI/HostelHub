@extends('layouts.owner')

@section('title', __('network.marketplace'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('network.marketplace') }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('network.marketplace') }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('network.marketplace.create') }}" class="btn btn-sm btn-primary">
            {{ __('network.create_listing') }}
        </a>
    </div>
</div>

<!-- फिल्टर फारम (unchanged) -->
<form method="GET" action="{{ route('network.marketplace.index') }}" class="row g-3 mb-4">
    <div class="col-md-3">
        <label for="type" class="form-label">{{ __('network.listing_type') }}</label>
        <select name="type" id="type" class="form-select">
            <option value="">{{ __('network.all') }}</option>
            @foreach(['sale', 'lease', 'partnership', 'investment'] as $type)
                <option value="{{ $type }}" @selected(request('type')==$type)>
                    {{ __('network.type_' . $type) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="status" class="form-label">{{ __('network.status') }}</label>
        <select name="status" id="status" class="form-select">
            <option value="">{{ __('network.all') }}</option>
            <option value="approved" @selected(request('status')=='approved')>{{ __('network.approved') }}</option>
            <option value="pending" @selected(request('status')=='pending')>{{ __('network.pending') }}</option>
            <option value="sold" @selected(request('status')=='sold')>{{ __('network.sold') }}</option>
            <option value="closed" @selected(request('status')=='closed')>{{ __('network.closed') }}</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="location" class="form-label">{{ __('network.location') }}</label>
        <input type="text" class="form-control" id="location" name="location" value="{{ request('location') }}">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary me-2">{{ __('network.search') }}</button>
        <a href="{{ route('network.marketplace.index') }}" class="btn btn-secondary">{{ __('network.clear_filters') }}</a>
    </div>
</form>

<!-- सूची कार्डहरू (fixed) -->
@if($listings->count())
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($listings as $listing)
        <div class="col">
            <div class="card h-100">
                @if($listing->media->count())
                    <img src="{{ Storage::url($listing->media->first()->file_path) }}" class="card-img-top" alt="{{ $listing->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span class="text-muted">{{ __('network.no_image') }}</span>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $listing->title }}</h5>
                    <p class="card-text">{{ Str::limit($listing->description, 100) }}</p>
                    <p class="card-text">
                        <strong>{{ __('network.listing_type') }}:</strong> {{ __('network.type_' . $listing->type) }}<br>
                        @if($listing->price)
                            <strong>{{ __('network.listing_price') }}:</strong> रू. {{ number_format($listing->price, 2) }}<br>
                        @endif
                        @if($listing->location)
                            <strong>{{ __('network.location') }}:</strong> {{ $listing->location }}<br>
                        @endif
                        <span class="badge bg-{{ $listing->status === 'approved' ? 'success' : ($listing->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ __('network.' . $listing->status) }}
                        </span>
                    </p>
                    <!-- Owner info: only name and phone from User model -->
                    <p class="card-text">
                        <strong>{{ __('network.owner') }}:</strong> {{ $listing->owner->name }}<br>
                        @if($listing->owner->phone)
                            <i class="bi bi-telephone"></i> {{ $listing->owner->phone }}
                        @endif
                    </p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('network.marketplace.show', $listing->slug) }}" class="btn btn-sm btn-outline-primary">{{ __('network.view') }}</a>
                    @if($listing->owner_id !== Auth::id())
                        <a href="{{ route('network.marketplace.contact', $listing->id) }}" class="btn btn-sm btn-primary">{{ __('network.contact_seller') }}</a>
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
        <h4>{{ __('network.no_listings') }}</h4>
        <p class="text-muted">{{ __('network.no_listings_desc') ?? 'हाल कुनै लिस्टिंग उपलब्ध छैन।' }}</p>
        <a href="{{ route('network.marketplace.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('network.create_listing') }}
        </a>
        <a href="{{ route('network.marketplace.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> {{ __('network.clear_filters') }}
        </a>
    </div>
@endif
@endsection