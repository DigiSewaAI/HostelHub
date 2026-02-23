@extends('network.layouts.app')

@section('title', __('network.create_broadcast'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('network.create_broadcast') }}</h1>
</div>

@if(session('cooldown'))
    <div class="alert alert-warning">
        {{ session('cooldown') }}
    </div>
@endif

<form method="POST" action="{{ route('network.broadcast.store') }}">
    @csrf

    <div class="mb-3">
        <label for="subject" class="form-label">{{ __('network.broadcast_subject') }}</label>
        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
        @error('subject')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="body" class="form-label">{{ __('network.broadcast_body') }}</label>
        <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="5" required>{{ old('body') }}</textarea>
        @error('body')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> {{ __('network.cooldown_warning') }}
    </div>

    <button type="submit" class="btn btn-primary">{{ __('network.send') }}</button>
    <a href="{{ route('network.broadcast.index') }}" class="btn btn-secondary">{{ __('network.cancel') }}</a>
</form>
@endsection