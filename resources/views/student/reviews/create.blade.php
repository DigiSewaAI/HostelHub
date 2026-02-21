@extends('layouts.student')

@section('title', 'नयाँ समीक्षा सिर्जना गर्नुहोस्')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2"></i>नयाँ समीक्षा सिर्जना गर्नुहोस्
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.reviews.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="hostel_id" class="form-label">होस्टेल छान्नुहोस्</label>
                            <select name="hostel_id" id="hostel_id" class="form-select @error('hostel_id') is-invalid @enderror" required>
                                <option value="">होस्टेल छान्नुहोस्</option>
                                @foreach ($hostels as $hostel)
                                    <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                        {{ $hostel->name }} - {{ $hostel->city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hostel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">रेटिंग दिनुहोस्</label>
                            <div class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                           {{ old('rating') == $i ? 'checked' : '' }} required>
                                    <label for="star{{ $i }}" class="star-label">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">तपाईंको समीक्षा</label>
                            <textarea name="comment" id="comment" rows="6" 
                                      class="form-control @error('comment') is-invalid @enderror"
                                      placeholder="होस्टेलको बारेमा तपाईंको अनुभव र विचारहरू लेख्नुहोस्..."
                                      required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">कम्तिमा १० वर्णको समीक्षा लेख्नुहोस्।</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('student.reviews.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> पछि जानुहोस्
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> समीक्षा पेश गर्नुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🚀 Direct inline style block to force white button text (always works) -->
<style>
/* Target both buttons and all their children in every state */
.card-body .btn.btn-secondary,
.card-body .btn.btn-primary,
.card-body .btn.btn-secondary *,
.card-body .btn.btn-primary *,
.card-body .btn.btn-secondary:hover,
.card-body .btn.btn-primary:hover,
.card-body .btn.btn-secondary:hover *,
.card-body .btn.btn-primary:hover *,
.card-body .btn.btn-secondary:focus,
.card-body .btn.btn-primary:focus,
.card-body .btn.btn-secondary:focus *,
.card-body .btn.btn-primary:focus *,
.card-body .btn.btn-secondary:active,
.card-body .btn.btn-primary:active,
.card-body .btn.btn-secondary:active *,
.card-body .btn.btn-primary:active *,
.card-body .btn.btn-secondary:visited,
.card-body .btn.btn-primary:visited,
.card-body .btn.btn-secondary:visited *,
.card-body .btn.btn-primary:visited * {
    color: #ffffff !important;
}
</style>
@endsection

@section('styles')
<style>
.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.rating-stars input[type="radio"] {
    display: none;
}

.star-label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-stars input[type="radio"]:checked ~ .star-label,
.star-label:hover,
.star-label:hover ~ .star-label {
    color: #ffc107;
}

.rating-stars input[type="radio"]:checked + .star-label {
    color: #ffc107;
}
</style>
@endsection