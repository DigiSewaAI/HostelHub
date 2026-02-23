@extends('network.layouts.app')

@section('title', $listing->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $listing->title }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('network.marketplace.index') }}" class="btn btn-sm btn-secondary">
            {{ __('network.back') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- मिडिया ग्यालरी -->
        @if($listing->media->count())
            <div id="listingCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach($listing->media as $index => $media)
                        <button type="button" data-bs-target="#listingCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : '' }}" aria-label="Slide {{ $index+1 }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($listing->media as $index => $media)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ Storage::url($media->file_path) }}" class="d-block w-100" alt="{{ $listing->title }}" style="max-height: 400px; object-fit: contain;">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        @endif

        <!-- विवरण -->
        <div class="card mb-4">
            <div class="card-header">
                {{ __('network.listing_description') }}
            </div>
            <div class="card-body">
                <p class="card-text">{{ $listing->description }}</p>
            </div>
        </div>

        <!-- विवरण तालिका -->
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
        <!-- मालिक जानकारी -->
        <div class="card mb-4">
            <div class="card-header">
                {{ __('network.owner') }}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $listing->owner->name }}</h5>
                @if($listing->owner->ownerNetworkProfile)
                    <p class="card-text">
                        @if($listing->owner->ownerNetworkProfile->business_name)
                            <strong>{{ __('network.business_name') }}:</strong> {{ $listing->owner->ownerNetworkProfile->business_name }}<br>
                        @endif
                        @if($listing->owner->ownerNetworkProfile->city)
                            <strong>{{ __('network.city') }}:</strong> {{ $listing->owner->ownerNetworkProfile->city }}<br>
                        @endif
                        @if($listing->owner->ownerNetworkProfile->is_verified)
                            <span class="badge bg-success">{{ __('network.verified') }}</span>
                        @endif
                    </p>
                @endif

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