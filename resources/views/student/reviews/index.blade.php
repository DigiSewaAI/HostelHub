@extends('layouts.student')

@section('title', 'मेरा समीक्षाहरू')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            
            <!-- Review Stats -->
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">कुल समीक्षा</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $reviewStats['total'] }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                        <i class="fas fa-star text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">स्वीकृत</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $reviewStats['approved'] }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                        <i class="fas fa-check text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">पेन्डिङ</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $reviewStats['pending'] }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                        <i class="fas fa-clock text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">अस्वीकृत</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $reviewStats['rejected'] ?? 0 }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                        <i class="fas fa-times text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2"></i>मेरा समीक्षाहरू
                        </h5>
                        <a href="{{ route('student.reviews.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> नयाँ समीक्षा
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($reviews->count() > 0)
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">होस्टेल</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">रेटिंग</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">स्थिति</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">मिति</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviews as $review)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ $review->hostel->images->first()->image_path ?? asset('storage/images/default-hostel.jpg') }}" 
                                                         class="avatar avatar-sm me-3" alt="{{ $review->hostel->name }}">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $review->hostel->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ Str::limit($review->comment, 50) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }} me-1"></i>
                                                @endfor
                                                <span class="text-xs font-weight-bold ms-1">({{ $review->rating }}/5)</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($review->status == 'approved')
                                                <span class="badge bg-success">स्वीकृत</span>
                                            @elseif ($review->status == 'pending')
                                                <span class="badge bg-warning">पेन्डिङ</span>
                                            @else
                                                <span class="badge bg-danger">अस्वीकृत</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $review->created_at->format('Y-m-d') }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('student.reviews.show', $review->id) }}" 
                                                   class="btn btn-info btn-sm mx-1" 
                                                   data-bs-toggle="tooltip" 
                                                   title="हेर्नुहोस्">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('student.reviews.edit', $review->id) }}" 
                                                   class="btn btn-warning btn-sm mx-1" 
                                                   data-bs-toggle="tooltip" 
                                                   title="सम्पादन गर्नुहोस्">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('student.reviews.destroy', $review->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('के तपाईं यो समीक्षा मेटाउन निश्चित हुनुहुन्छ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm mx-1" 
                                                            data-bs-toggle="tooltip" 
                                                            title="मेटाउनुहोस्">
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
                        <div class="px-4 pt-3">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">कुनै समीक्षा भेटिएन</h5>
                            <p class="text-muted">तपाईंले अहिलेसम्म कुनै होस्टेलको समीक्षा दिनुभएको छैन।</p>
                            <a href="{{ route('student.reviews.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> पहिलो समीक्षा दिनुहोस्
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // टूलटिप सक्रिय गर्ने
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection