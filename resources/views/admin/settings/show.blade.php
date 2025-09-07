@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">सेटिङ्ग विवरण</h3>
                </div>

                <div class="card-body">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">सेटिङ्गको नाम:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">{{ $setting->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">मान:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">{{ $setting->value }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">विवरण:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">{{ $setting->description ?? 'कुनै विवरण उपलब्ध छैन' }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">सिर्जना मिति:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">{{ $setting->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">अद्यावधिक मिति:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">{{ $setting->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.settings.edit', $setting->id) }}" class="btn btn-primary me-md-2">
                            <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection