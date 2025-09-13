@extends('layouts.admin')

@section('title', 'नयाँ समीक्षा सिर्जना गर्नुहोस्')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">नयाँ समीक्षा सिर्जना गर्नुहोस्</h1>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> पछाडि फर्कनुहोस्
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reviews.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">नाम <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">पद <span class="text-danger">*</span></label>
                            <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position') }}" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">समीक्षा सामग्री <span class="text-danger">*</span></label>
                            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="initials" class="form-label">प्रारम्भिक अक्षर (वैकल्पिक)</label>
                            <input type="text" name="initials" id="initials" class="form-control @error('initials') is-invalid @enderror" value="{{ old('initials') }}" maxlength="10" placeholder="जस्तै: RS, JP, आदि">
                            @error('initials')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">छवि (वैकल्पिक)</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            <div class="form-text">अनुमतिहरू: JPG, PNG, JPEG, GIF। अधिकतम साइज: 2MB</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">प्रकार <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">-- प्रकार छान्नुहोस् --</option>
                                    <option value="testimonial" {{ old('type') == 'testimonial' ? 'selected' : '' }}>प्रशंसापत्र</option>
                                    <option value="review" {{ old('type') == 'review' ? 'selected' : '' }}>समीक्षा</option>
                                    <option value="feedback" {{ old('type') == 'feedback' ? 'selected' : '' }}>प्रतिक्रिया</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">स्थिति <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>सक्रिय</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>निष्क्रिय</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">मूल्याङ्कन (१-५)</label>
                            <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror">
                                <option value="">-- मूल्याङ्कन छान्नुहोस् --</option>
                                <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>१ - धेरै नराम्रो</option>
                                <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>२ - नराम्रो</option>
                                <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>३ - सामान्य</option>
                                <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>४ - राम्रो</option>
                                <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>५ - धेरै राम्रो</option>
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">समीक्षा सिर्जना गर्नुहोस्</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection