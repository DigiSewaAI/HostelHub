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
                    <!-- शहर फिल्टर -->
                    <div class="mb-3">
                        <label for="city" class="form-label">{{ __('network.city') }}</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ request('city') }}">
                    </div>

                    <!-- सुविधा फिल्टर (facilities JSON मा खोज) -->
                    <div class="mb-3">
                        <label for="facility" class="form-label">{{ __('network.facility') }}</label>
                        <select class="form-select" id="facility" name="facility">
                            <option value="">{{ __('network.all') }}</option>
                            <option value="wifi" @selected(request('facility') == 'wifi')>वाइफाइ</option>
                            <option value="parking" @selected(request('facility') == 'parking')>पार्किङ</option>
                            <option value="cctv" @selected(request('facility') == 'cctv')>सीसीटीभी</option>
                            <option value="generator" @selected(request('facility') == 'generator')>जेनेरेटर</option>
                        </select>
                    </div>

                    <!-- मूल्य दायरा (rooms बाट) -->
                    <div class="mb-3">
                        <label for="min_price" class="form-label">{{ __('network.min_price') }}</label>
                        <input type="number" class="form-control" id="min_price" name="min_price" value="{{ request('min_price') }}">
                    </div>
                    <div class="mb-3">
                        <label for="max_price" class="form-label">{{ __('network.max_price') }}</label>
                        <input type="number" class="form-control" id="max_price" name="max_price" value="{{ request('max_price') }}">
                    </div>

                    <!-- कोठा संख्या दायरा -->
                    <div class="mb-3">
                        <label for="min_rooms" class="form-label">{{ __('network.min_rooms') }}</label>
                        <input type="number" class="form-control" id="min_rooms" name="min_rooms" value="{{ request('min_rooms') }}">
                    </div>
                    <div class="mb-3">
                        <label for="max_rooms" class="form-label">{{ __('network.max_rooms') }}</label>
                        <input type="number" class="form-control" id="max_rooms" name="max_rooms" value="{{ request('max_rooms') }}">
                    </div>

                    <!-- प्रमाणित मात्र -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="verified_only" name="verified_only" value="1" @checked(request('verified_only'))>
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
        <p>{{ $hostels->total() }} {{ __('network.hostels_found') }}</p>

        @if($hostels->count())
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($hostels as $hostel)
                    @php
                        $snapshot = $hostel->networkProfile?->auto_snapshot ?? [];
                        $verified = !is_null($hostel->networkProfile?->verified_at);
                        // ✅ facilities लाई array मा बदल्ने
                        $facilities = $hostel->facilities;
                        if (is_string($facilities)) {
                            $facilities = json_decode($facilities, true) ?? [];
                        }
                        // यदि अझै array छैन भने खाली array
                        if (!is_array($facilities)) {
                            $facilities = [];
                        }
                    @endphp
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $hostel->name }}
                                    @if($verified)
                                        <span class="badge bg-success">{{ __('network.verified') }}</span>
                                    @endif
                                </h5>
                                <p class="card-text">
                                    @if($hostel->city)
                                        <i class="bi bi-geo-alt"></i> {{ $hostel->city }}<br>
                                    @endif
                                    @if($hostel->contact_phone)
                                        <i class="bi bi-telephone"></i> {{ $hostel->contact_phone_formatted }}<br>
                                    @endif
                                    @if($hostel->total_rooms)
                                        <i class="bi bi-building"></i> {{ __('network.total_rooms', ['count' => $hostel->total_rooms]) }}<br>
                                    @endif
                                    @if(!empty($facilities))
                                        <strong>{{ __('network.facilities_provided') }}:</strong>
                                        @foreach($facilities as $facility)
                                            <span class="badge bg-secondary">{{ $facility }}</span>
                                        @endforeach
                                    @endif
                                </p>
                                @if($hostel->description)
                                    <p class="card-text">{{ Str::limit($hostel->description, 100) }}</p>
                                @endif
                                <p class="card-text">
                                    <strong>{{ __('network.price_range') }}:</strong>
                                    {{ $hostel->formatted_price_range }}
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('network.messages.create', ['recipient' => $hostel->owner_id]) }}" class="btn btn-sm btn-primary">
                                    {{ __('network.send_message') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $hostels->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-address-book fa-4x text-muted mb-3"></i>
                <h4>{{ __('network.no_hostels_found') }}</h4>
                <p class="text-muted">{{ __('network.no_hostels_found_desc') ?? 'कुनै होस्टल फेला परेन। फिल्टर परिवर्तन गरेर पुन: प्रयास गर्नुहोस्।' }}</p>
                <a href="{{ route('network.directory.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> {{ __('network.clear_filters') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection