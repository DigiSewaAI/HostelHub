@extends('layouts.owner')

@section('title', __('network.owner_directory'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('network.directory') }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('network.owner_directory') }}</h1>
</div>

<div class="row">
    <!-- फिल्टर साइडबार -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                {{ __('network.filters') }}
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('network.directory.index') }}">
                    <div class="mb-3">
                        <label for="city" class="form-label">{{ __('network.city') }}</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ $filters['city'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="services" class="form-label">{{ __('network.services') }}</label>
                        <select class="form-select" id="services" name="services">
                            <option value="">{{ __('network.all') }}</option>
                            <option value="hostel" @selected(($filters['services'] ?? '') == 'hostel')>होस्टल</option>
                            <option value="paying guest" @selected(($filters['services'] ?? '') == 'paying guest')>पेइङ गेस्ट</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="pricing_category" class="form-label">{{ __('network.pricing_category') }}</label>
                        <select class="form-select" id="pricing_category" name="pricing_category">
                            <option value="">{{ __('network.all') }}</option>
                            <option value="budget" @selected(($filters['pricing_category'] ?? '') == 'budget')>{{ __('network.pricing_budget') }}</option>
                            <option value="mid" @selected(($filters['pricing_category'] ?? '') == 'mid')>{{ __('network.pricing_mid') }}</option>
                            <option value="premium" @selected(($filters['pricing_category'] ?? '') == 'premium')>{{ __('network.pricing_premium') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="min_size" class="form-label">{{ __('network.hostel_size') }} (न्यूनतम)</label>
                        <input type="number" class="form-control" id="min_size" name="min_size" value="{{ $filters['min_size'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="max_size" class="form-label">{{ __('network.hostel_size') }} (अधिकतम)</label>
                        <input type="number" class="form-control" id="max_size" name="max_size" value="{{ $filters['max_size'] ?? '' }}">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="verified_only" name="verified_only" value="1" @checked(($filters['verified_only'] ?? false))>
                        <label class="form-check-label" for="verified_only">{{ __('network.verified_only') }}</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">{{ __('network.search') }}</button>
                    <a href="{{ route('network.directory.index') }}" class="btn btn-secondary w-100 mt-2">{{ __('network.clear_filters') }}</a>
                </form>
            </div>
        </div>
    </div>

    <!-- परिणाम -->
    <div class="col-md-9">
        <p>{{ $owners->total() }} {{ __('network.owners_found') }}</p>

        @if($owners->count())
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($owners as $profile)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $profile->business_name ?? $profile->user->name }}
                                    @if($profile->is_verified)
                                        <span class="badge bg-success">{{ __('network.verified') }}</span>
                                    @endif
                                </h5>
                                <p class="card-text">
                                    @if($profile->city)
                                        <i class="bi bi-geo-alt"></i> {{ $profile->city }}<br>
                                    @endif
                                    @if($profile->phone)
                                        <i class="bi bi-telephone"></i> {{ $profile->phone }}<br>
                                    @endif
                                    @if($profile->hostel_size)
                                        <i class="bi bi-building"></i> {{ __('network.hostel_size_beds', ['count' => $profile->hostel_size]) }}<br>
                                    @endif
                                    @if($profile->services)
                                        <strong>{{ __('network.services_provided') }}:</strong>
                                        @foreach($profile->services as $service)
                                            <span class="badge bg-secondary">{{ $service }}</span>
                                        @endforeach
                                    @endif
                                </p>
                                @if($profile->bio)
                                    <p class="card-text">{{ Str::limit($profile->bio, 100) }}</p>
                                @endif
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('network.messages.create') }}?recipient={{ $profile->user_id }}" class="btn btn-sm btn-primary">
                                    {{ __('network.send_message') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $owners->withQueryString()->links() }}
            </div>
        @else
    <div class="text-center py-5">
        <i class="fas fa-address-book fa-4x text-muted mb-3"></i>
        <h4>{{ __('network.no_owners_found') }}</h4>
        <p class="text-muted">{{ __('network.no_owners_found_desc') ?? 'कुनै मालिक फेला परेन। फिल्टर परिवर्तन गरेर पुन: प्रयास गर्नुहोस्।' }}</p>
        <a href="{{ route('network.directory.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> {{ __('network.clear_filters') }}
        </a>
    </div>
@endif
    </div>
</div>
@endsection