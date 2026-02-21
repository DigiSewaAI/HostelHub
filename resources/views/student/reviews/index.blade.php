@extends('layouts.student')

@section('title', 'मेरा समीक्षाहरू')

@section('content')
<div class="container-fluid py-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #e9ecf2 100%); min-height: 100vh;">
    <div class="row">
        <div class="col-12">

            <!-- Review Stats with Modern Gradient Cards -->
            <div class="row mb-4 g-3">
                <div class="col-xl-3 col-sm-6">
                    <div class="card border-0 shadow-lg hover-lift rounded-4 overflow-hidden"
                         style="background: linear-gradient(145deg, #667eea, #764ba2);">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-white">
                                        <p class="text-sm mb-0 text-uppercase fw-light opacity-75">कुल समीक्षा</p>
                                        <h3 class="fw-bold mb-0 text-white">{{ $reviewStats['total'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon-shape bg-white bg-opacity-25 rounded-circle p-3 d-inline-flex">
                                        <i class="fas fa-star fa-2x text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card border-0 shadow-lg hover-lift rounded-4 overflow-hidden"
                         style="background: linear-gradient(145deg, #43e97b, #38f9d7);">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-white">
                                        <p class="text-sm mb-0 text-uppercase fw-light opacity-75">स्वीकृत</p>
                                        <h3 class="fw-bold mb-0 text-white">{{ $reviewStats['approved'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon-shape bg-white bg-opacity-25 rounded-circle p-3 d-inline-flex">
                                        <i class="fas fa-check fa-2x text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card border-0 shadow-lg hover-lift rounded-4 overflow-hidden"
                         style="background: linear-gradient(145deg, #fa709a, #fee140);">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-white">
                                        <p class="text-sm mb-0 text-uppercase fw-light opacity-75">पेन्डिङ</p>
                                        <h3 class="fw-bold mb-0 text-white">{{ $reviewStats['pending'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon-shape bg-white bg-opacity-25 rounded-circle p-3 d-inline-flex">
                                        <i class="fas fa-clock fa-2x text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card border-0 shadow-lg hover-lift rounded-4 overflow-hidden"
                         style="background: linear-gradient(145deg, #f093fb, #f5576c);">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-white">
                                        <p class="text-sm mb-0 text-uppercase fw-light opacity-75">अस्वीकृत</p>
                                        <h3 class="fw-bold mb-0 text-white">{{ $reviewStats['rejected'] ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon-shape bg-white bg-opacity-25 rounded-circle p-3 d-inline-flex">
                                        <i class="fas fa-times fa-2x text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-star me-2"></i>मेरा समीक्षाहरू
                        </h4>
                        <!-- यो button ma white text and icon -->
                        <a href="{{ route('student.reviews.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4 py-2 mt-2 mt-sm-0 text-white">
                            <i class="fas fa-plus me-1 text-white"></i> नयाँ समीक्षा
                        </a>
                    </div>
                </div>
                <div class="card-body px-4 pt-4 pb-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 bg-gradient-success text-white shadow-sm mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($reviews->count() > 0)
                        <!-- Modern Table -->
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="bg-light rounded-3">
                                    <tr>
                                        <th class="border-0 rounded-start ps-3 py-3 text-uppercase text-secondary small fw-semibold">होस्टेल</th>
                                        <th class="border-0 py-3 text-uppercase text-secondary small fw-semibold">रेटिंग</th>
                                        <th class="border-0 py-3 text-uppercase text-secondary small fw-semibold">स्थिति</th>
                                        <th class="border-0 py-3 text-uppercase text-secondary small fw-semibold">मिति</th>
                                        <th class="border-0 rounded-end py-3 text-uppercase text-secondary small fw-semibold text-end pe-3">कार्य</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviews as $review)
                                    <tr class="border-bottom border-light">
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $review->hostel->images->first()->image_path ?? asset('storage/images/default-hostel.jpg') }}"
                                                     class="rounded-3 avatar avatar-md me-3 object-fit-cover" style="width: 48px; height: 48px;"
                                                     alt="{{ $review->hostel->name }}">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">{{ $review->hostel->name }}</h6>
                                                    <p class="text-muted small mb-0">{{ Str::limit($review->comment, 40) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary opacity-25' }} me-1"></i>
                                                @endfor
                                                <span class="badge bg-warning bg-opacity-15 text-warning ms-2 px-3 py-1 rounded-pill small">({{ $review->rating }}/5)</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($review->status == 'approved')
                                                <span class="badge bg-success bg-gradient px-3 py-2 rounded-pill">स्वीकृत</span>
                                            @elseif ($review->status == 'pending')
                                                <span class="badge bg-warning bg-gradient px-3 py-2 rounded-pill">पेन्डिङ</span>
                                            @else
                                                <span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill">अस्वीकृत</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted small fw-normal">
                                                <i class="far fa-calendar-alt me-1"></i>{{ $review->created_at->format('Y-m-d') }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="{{ route('student.reviews.show', $review->id) }}"
                                                   class="btn btn-sm btn-info rounded-pill px-3 text-white"
                                                   data-bs-toggle="tooltip" title="हेर्नुहोस्">
                                                    <i class="fas fa-eye text-white"></i>
                                                </a>
                                                <a href="{{ route('student.reviews.edit', $review->id) }}"
                                                   class="btn btn-sm btn-warning rounded-pill px-3 text-white"
                                                   data-bs-toggle="tooltip" title="सम्पादन गर्नुहोस्">
                                                    <i class="fas fa-edit text-white"></i>
                                                </a>
                                                <form action="{{ route('student.reviews.destroy', $review->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('के तपाईं यो समीक्षा मेटाउन निश्चित हुनुहुन्छ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger rounded-pill px-3 text-white"
                                                            data-bs-toggle="tooltip" title="मेटाउनुहोस्">
                                                        <i class="fas fa-trash text-white"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <!-- Modern Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-star fa-4x text-primary opacity-25"></i>
                            </div>
                            <h4 class="fw-bold text-secondary">कुनै समीक्षा भेटिएन</h4>
                            <p class="text-muted mb-4">तपाईंले अहिलेसम्म कुनै होस्टेलको समीक्षा दिनुभएको छैन।</p>
                            <!-- यो button ma pani white text and icon -->
                            <a href="{{ route('student.reviews.create') }}" class="btn btn-primary rounded-pill px-5 py-2 shadow text-white">
                                <i class="fas fa-plus me-2 text-white"></i>पहिलो समीक्षा दिनुहोस्
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for hover effects and extra polish -->
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.1) !important;
    }
    .avatar-md {
        width: 48px;
        height: 48px;
        object-fit: cover;
    }
    .table thead th {
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.03);
        transition: background-color 0.2s;
    }
    /* Solid button hover effects */
    .btn-info, .btn-warning, .btn-danger, .btn-primary {
        transition: all 0.2s;
        border: none;
    }
    .btn-info:hover, .btn-warning:hover, .btn-danger:hover, .btn-primary:hover {
        transform: scale(1.05);
        filter: brightness(1.1);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    .btn-close-white {
        filter: brightness(0) invert(1);
    }
    .bg-opacity-15 {
        --bs-bg-opacity: 0.15;
    }
    .rounded-4 {
        border-radius: 1rem !important;
    }
    /* Force white text on all buttons and their icons */
    .btn, .btn i, .btn span {
        color: white !important;
    }
    /* Override for any default Bootstrap button text colors */
    .btn-primary, .btn-info, .btn-warning, .btn-danger {
        color: white !important;
    }
</style>
@endsection

@section('scripts')
<script>
    // टूलटिप सक्रिय गर्ने (unchanged)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection