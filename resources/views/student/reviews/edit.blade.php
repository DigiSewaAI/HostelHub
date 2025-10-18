@extends('layouts.student')

@section('title', 'समीक्षा सम्पादन गर्नुहोस्')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>समीक्षा सम्पादन गर्नुहोस्
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.reviews.update', $review->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">होस्टेल</label>
                            <input type="text" class="form-control" value="{{ $review->hostel->name }}" readonly>
                            <small class="text-muted">होस्टेल परिवर्तन गर्न सकिँदैन। नयाँ समीक्षा सिर्जना गर्नुहोस्।</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">रेटिंग दिनुहोस्</label>
                            <div class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                           {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
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
                                      required>{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">कम्तिमा १० वर्णको समीक्षा लेख्नुहोस्।</div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>नोट:</strong> समीक्षा सम्पादन गर्दा, यो पुनः प्रशासकको स्वीकृतिको लागि पेश गरिनेछ।
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('student.reviews.show', $review->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> रद्द गर्नुहोस्
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> परिवर्तनहरू सेभ गर्नुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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