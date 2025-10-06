@extends('owner.layouts.app')

@section('title', 'समीक्षाको जवाफ दिनुहोस्')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-reply me-2"></i>
                        समीक्षाको जवाफ दिनुहोस्
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>त्रुटिहरू पत्ता लाग्यो:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- समीक्षाको विवरण -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="text-primary">समीक्षाको विवरण:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>विद्यार्थी:</strong> {{ $review->user->name }}</p>
                                <p><strong>होस्टेल:</strong> {{ $review->hostel->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>रेटिंग:</strong> 
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                    @endfor
                                    ({{ $review->rating }}/5)
                                </p>
                                <p><strong>मिति:</strong> {{ $review->created_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>टिप्पणी:</strong>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    </div>

                    <!-- जवाफ दिने फारम -->
                    <form action="{{ route('owner.reviews.update', $review->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="action" value="reply">

                        <div class="mb-3">
                            <label for="owner_reply" class="form-label">
                                <strong>तपाईंको जवाफ:</strong>
                            </label>
                            <textarea name="owner_reply" id="owner_reply" rows="5" 
                                      class="form-control @error('owner_reply') is-invalid @enderror"
                                      placeholder="विद्यार्थीको समीक्षाको लागि जवाफ दिनुहोस्...">{{ old('owner_reply', $review->owner_reply) }}</textarea>
                            @error('owner_reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <strong>स्थिति:</strong>
                            </label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>पेन्डिङ</option>
                                <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>स्वीकृत</option>
                                <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>अस्वीकृत</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('owner.reviews.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> पछि जानुहोस्
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i> जवाफ सेभ गर्नुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // टेक्स्ट एरिया स्वतः विस्तार गर्ने
        const textarea = document.getElementById('owner_reply');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
@endsection