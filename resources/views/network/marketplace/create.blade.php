@extends('layouts.owner')

@section('title', __('network.create_listing'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item"><a href="{{ route('network.marketplace.index') }}">{{ __('network.marketplace') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('network.create_listing') }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('network.create_listing') }}</h1>
</div>

<form method="POST" action="{{ route('network.marketplace.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="title" class="form-label">{{ __('network.listing_title') }}</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">{{ __('network.listing_description') }}</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="type" class="form-label">{{ __('network.listing_type') }}</label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="">{{ __('network.select') }}</option>
                @foreach(['sale', 'lease', 'partnership', 'investment'] as $type)
                    <option value="{{ $type }}" @selected(old('type')==$type)>{{ __('network.type_' . $type) }}</option>
                @endforeach
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="price" class="form-label">{{ __('network.listing_price') }}</label>
            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="{{ __('network.price_placeholder') }}">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="location" class="form-label">{{ __('network.listing_location') }}</label>
            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="media" class="form-label">{{ __('network.upload_images') }}</label>
        <input type="file" class="form-control @error('media.*') is-invalid @enderror" id="media" name="media[]" multiple accept="image/*">
        <small class="text-muted">{{ __('network.max_files_5') }}</small>
        @error('media.*')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="alert alert-info">
        {{ __('network.listing_pending') }}
    </div>

    <button type="submit" class="btn btn-primary">{{ __('network.save') }}</button>
    <a href="{{ route('network.marketplace.index') }}" class="btn btn-secondary">{{ __('network.cancel') }}</a>
</form>
@endsection