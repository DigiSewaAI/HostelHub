@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">नयाँ सेटिङ्ग थप्नुहोस्</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">सेटिङ्गको नाम</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">मान</label>
                            <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" required>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">विवरण</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">सेटिङ्ग सुरक्षित गर्नुहोस्</button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">रद्द गर्नुहोस्</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection