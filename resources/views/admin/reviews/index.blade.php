@extends('layouts.admin')

@section('title', 'समीक्षाहरू')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">समीक्षाहरू</h1>
        <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> नयाँ समीक्षा
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reviews.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label for="type" class="form-label">प्रकार</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">सबै प्रकार</option>
                            <option value="testimonial" {{ request('type') == 'testimonial' ? 'selected' : '' }}>प्रशंसापत्र</option>
                            <option value="review" {{ request('type') == 'review' ? 'selected' : '' }}>समीक्षा</option>
                            <option value="feedback" {{ request('type') == 'feedback' ? 'selected' : '' }}>प्रतिक्रिया</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="status" class="form-label">स्थिति</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">सबै स्थिति</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>पेन्डिंग</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>स्वीकृत</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>अस्वीकृत</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>सक्रिय</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>निष्क्रिय</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="search" class="form-label">खोज्नुहोस्</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="नाम, पद वा समीक्षा खोज्नुहोस्" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i> फिल्टर
                        </button>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync me-1"></i> रिसेट
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($reviews->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="50">क्र.स.</th>
                            <th width="80">छवि</th>
                            <th>नाम</th>
                            <th>पद</th>
                            <th>प्रकार</th>
                            <th>मूल्याङ्कन</th>
                            <th>स्थिति</th>
                            <th width="200">कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
                            <td>
                                @if($review->image)
                                    <img src="{{ Storage::disk('public')->exists($review->image) ? Storage::url($review->image) : asset('images/default-avatar.png') }}" 
                                         alt="{{ $review->name }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                @elseif($review->initials)
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" 
                                         style="width: 40px; height: 40px; font-size: 14px; font-weight: bold;">
                                        {{ $review->initials }}
                                    </div>
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user" style="font-size: 14px;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $review->name }}</td>
                            <td>{{ $review->position }}</td>
                            <td>
                                @if($review->type == 'testimonial')
                                    <span class="badge bg-success">प्रशंसापत्र</span>
                                @elseif($review->type == 'review')
                                    <span class="badge bg-primary">समीक्षा</span>
                                @else
                                    <span class="badge bg-info">प्रतिक्रिया</span>
                                @endif
                            </td>
                            <td>
                                @if($review->rating)
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}">
                                                <i class="fas fa-star"></i>
                                            </span>
                                        @endfor
                                        <span class="ms-1">({{ $review->rating }})</span>
                                    </div>
                                @else
                                    <span class="text-muted">नभएको</span>
                                @endif
                            </td>
                            <td>
                                @if($review->status == 'pending')
                                    <span class="badge bg-warning">पेन्डिंग</span>
                                @elseif($review->status == 'approved')
                                    <span class="badge bg-success">स्वीकृत</span>
                                @elseif($review->status == 'rejected')
                                    <span class="badge bg-danger">अस्वीकृत</span>
                                @elseif($review->status == 'active')
                                    <span class="badge bg-success">सक्रिय</span>
                                @else
                                    <span class="badge bg-secondary">निष्क्रिय</span>
                                @endif
                                @if($review->is_featured)
                                    <span class="badge bg-warning ms-1">फिचर</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    {{-- View Button --}}
                                    <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    {{-- Edit Button --}}
                                    <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- ✅ NEW: Approve/Reject Buttons for Pending Reviews --}}
                                    @if($review->status == 'pending')
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('समीक्षा स्वीकृत गर्नुहुन्छ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('समीक्षा अस्वीकृत गर्नुहुन्छ?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- ✅ NEW: Feature Button for Approved Reviews --}}
                                    @if($review->status == 'approved' && isset($review->is_featured) && !$review->is_featured)
                                        <form action="{{ route('admin.reviews.feature', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('समीक्षा हटाउनुहुन्छ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    कुल {{ $reviews->total() }} समीक्षाहरू मध्ये {{ $reviews->firstItem() }} देखि {{ $reviews->lastItem() }} सम्म देखाइएको छ
                </div>
                <div>
                    {{ $reviews->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <div class="py-5">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">कुनै समीक्षा फेला परेन</h5>
                    <p class="text-muted">नयाँ समीक्षा सिर्जना गर्न तलको बटनमा क्लिक गर्नुहोस्</p>
                    <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-1"></i> नयाँ समीक्षा
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
@endpush