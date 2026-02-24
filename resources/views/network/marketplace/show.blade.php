@extends('layouts.owner')

@section('title', $listing->title)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item"><a href="{{ route('network.marketplace.index') }}">{{ __('network.marketplace') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $listing->title }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $listing->title }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('network.marketplace.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('network.back') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- मिडिया ग्यालरी (unchanged) -->
        @if($listing->media->count())
            <div id="listingCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <!-- ... carousel code same as before ... -->
            </div>
        @else
            <div class="bg-light d-flex align-items-center justify-content-center mb-4" style="height: 200px; border-radius: 0.375rem;">
                <span class="text-muted">{{ __('network.no_image') }}</span>
            </div>
        @endif

        <!-- विवरण (unchanged) -->
        <div class="card mb-4">
            <div class="card-header">
                {{ __('network.listing_description') }}
            </div>
            <div class="card-body">
                <p class="card-text">{{ $listing->description }}</p>
            </div>
        </div>

        <!-- विवरण तालिका (unchanged) -->
        <table class="table table-bordered">
            <tr>
                <th>{{ __('network.listing_type') }}</th>
                <td>{{ __('network.type_' . $listing->type) }}</td>
            </tr>
            @if($listing->price)
            <tr>
                <th>{{ __('network.listing_price') }}</th>
                <td>रू. {{ number_format($listing->price, 2) }}</td>
            </tr>
            @endif
            @if($listing->location)
            <tr>
                <th>{{ __('network.location') }}</th>
                <td>{{ $listing->location }}</td>
            </tr>
            @endif
            <tr>
                <th>{{ __('network.status') }}</th>
                <td>
                    <span class="badge bg-{{ $listing->status === 'approved' ? 'success' : ($listing->status === 'pending' ? 'warning' : 'secondary') }}">
                        {{ __('network.' . $listing->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>{{ __('network.views') }}</th>
                <td>{{ $listing->views }}</td>
            </tr>
        </table>
    </div>

    <div class="col-md-4">
        <!-- मालिक जानकारी (fixed) -->
        <div class="card mb-4">
            <div class="card-header">
                {{ __('network.owner') }}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $listing->owner->name }}</h5>
                @if($listing->owner->phone)
                    <p><i class="bi bi-telephone"></i> {{ $listing->owner->phone }}</p>
                @endif
                @if($listing->owner->email)
                    <p><i class="bi bi-envelope"></i> {{ $listing->owner->email }}</p>
                @endif
                <!-- No verification badge here because verification is per hostel -->
                @if($listing->owner_id !== Auth::id())
                    <a href="{{ route('network.marketplace.contact', $listing->id) }}" class="btn btn-primary w-100">
                        {{ __('network.contact_seller') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection