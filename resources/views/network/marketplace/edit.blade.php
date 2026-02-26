@extends('layouts.owner')

@section('title', 'सूची सम्पादन गर्नुहोस्')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item"><a href="{{ route('network.marketplace.index') }}">बजार सूची</a></li>
    <li class="breadcrumb-item"><a href="{{ route('network.marketplace.show', $listing->slug) }}">{{ $listing->title }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">सम्पादन</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">सूची सम्पादन गर्नुहोस्: {{ $listing->title }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('network.marketplace.show', $listing->slug) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> फिर्ता
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('network.marketplace.update', $listing->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">शीर्षक <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $listing->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">प्रकार <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">छान्नुहोस्</option>
                                <option value="sale" {{ old('type', $listing->type) == 'sale' ? 'selected' : '' }}>बिक्री</option>
                                <option value="lease" {{ old('type', $listing->type) == 'lease' ? 'selected' : '' }}>भाडा</option>
                                <option value="partnership" {{ old('type', $listing->type) == 'partnership' ? 'selected' : '' }}>साझेदारी</option>
                                <option value="investment" {{ old('type', $listing->type) == 'investment' ? 'selected' : '' }}>लगानी</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">वर्ग</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option value="">वर्ग छान्नुहोस्</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $listing->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_en }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">विवरण <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $listing->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">मूल्य (रु.)</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $listing->price) }}">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price_type" class="form-label">मूल्य प्रकार <span class="text-danger">*</span></label>
                            <select class="form-select @error('price_type') is-invalid @enderror" id="price_type" name="price_type" required>
                                <option value="fixed" {{ old('price_type', $listing->price_type) == 'fixed' ? 'selected' : '' }}>निश्चित मूल्य</option>
                                <option value="negotiable" {{ old('price_type', $listing->price_type) == 'negotiable' ? 'selected' : '' }}>मोलमोलाइ हुने</option>
                            </select>
                            @error('price_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label">मात्रा</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $listing->quantity ?? 1) }}" min="1">
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="condition" class="form-label">अवस्था</label>
                            <select class="form-select @error('condition') is-invalid @enderror" id="condition" name="condition">
                                <option value="">छान्नुहोस्</option>
                                <option value="new" {{ old('condition', $listing->condition) == 'new' ? 'selected' : '' }}>नयाँ</option>
                                <option value="used" {{ old('condition', $listing->condition) == 'used' ? 'selected' : '' }}>प्रयोग गरिएको</option>
                            </select>
                            @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">स्थान</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $listing->location) }}">
                            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">दृश्यता <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="visibility_private" value="private" {{ old('visibility', $listing->visibility) == 'private' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="visibility_private">
                                <strong>केवल मालिकहरू (नेटवर्क भित्र मात्र)</strong>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="visibility_both" value="both" {{ old('visibility', $listing->visibility) == 'both' ? 'checked' : '' }}>
                            <label class="form-check-label" for="visibility_both">
                                <strong>सार्वजनिक + मालिकहरू</strong> (एडमिन स्वीकृत पछि)
                            </label>
                        </div>
                        @error('visibility') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    @if($listing->media->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">हालका तस्वीरहरू</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($listing->media as $media)
                                    <div class="position-relative border p-1">
                                        <img src="{{ Storage::url($media->file_path) }}" alt="Media" style="width: 80px; height: 80px; object-fit: cover;">
                                        {{-- यहाँ delete button थप्न सकिन्छ --}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="media" class="form-label">नयाँ तस्वीरहरू थप्नुहोस्</label>
                        <input type="file" class="form-control @error('media.*') is-invalid @enderror" id="media" name="media[]" multiple accept="image/*">
                        <div class="form-text">प्रति फाइल अधिकतम 2MB। जोड्दा पुराना तस्वीरहरू रहिरहनेछन्।</div>
                        @error('media.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('network.marketplace.show', $listing->slug) }}" class="btn btn-secondary">रद्द गर्नुहोस्</a>
                        <button type="submit" class="btn btn-primary">अपडेट गर्नुहोस्</button>
                    </div>
                </form>

                {{-- Delete form --}}
                <hr>
                <form action="{{ route('network.marketplace.destroy', $listing->slug) }}" method="POST" onsubmit="return confirm('के तपाईं यो सूची स्थायी रूपमा मेटाउन चाहनुहुन्छ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">सूची मेटाउनुहोस्</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection