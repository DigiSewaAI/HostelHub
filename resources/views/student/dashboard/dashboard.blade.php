@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <h5 class="mb-0">विद्यार्थी ड्यासबोर्ड</h5>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <i class="fas fa-user-graduate text-primary" style="font-size: 3rem;"></i>
                        <h4 class="mt-3 text-primary">स्वागत छ, {{ auth()->user()->name }}!</h4>
                        <p class="text-muted">तपाईं विद्यार्थी रूपमा सफलतापूर्वक लगइन गर्नुभयो</p>
                    </div>

                    <div class="row g-4">
                        <!-- Quick Actions -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-book me-2 text-primary"></i>मेरो जानकारी</h5>
                                    <p class="card-text">तपाईंको विद्यार्थी प्रोफाइल, ठेगाना र सम्पर्क जानकारी हेर्नुहोस्</p>
                                    <a href="{{ route('student.profile') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-user me-1"></i> प्रोफाइल हेर्नुहोस्
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-credit-card me-2 text-success"></i>भुक्तानी</h5>
                                    <p class="card-text">तपाईंको भुक्तानी इतिहास र बक्यौता जानकारी हेर्नुहोस्</p>
                                    <a href="{{ route('student.payments') }}" class="btn btn-outline-success">
                                        <i class="fas fa-wallet me-1"></i> भुक्तानी हेर्नुहोस्
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-utensils me-2 text-warning"></i>खाना मेनु</h5>
                                    <p class="card-text">होस्टलको खाना मेनु र भोजन जानकारी हेर्नुहोस्</p>
                                    <a href="{{ route('student.meal-menus.index') }}" class="btn btn-outline-warning">
                                        <i class="fas fa-clipboard-list me-1"></i> मेनु हेर्नुहोस्
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-home me-2 text-info"></i>कोठा बुकिङ</h5>
                                    <p class="card-text">तपाईंको कोठा बुकिङ र आवास जानकारी हेर्नुहोस्</p>
                                    <a href="{{ route('bookings.my') }}" class="btn btn-outline-info">
                                        <i class="fas fa-bed me-1"></i> बुकिङ हेर्नुहोस्
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Stats -->
                    <div class="mt-5">
                        <h5><i class="fas fa-chart-line me-2 text-primary"></i>प्रणाली तथ्याङ्क</h5>
                        <div class="row mt-3">
                            <div class="col-md-3 col-6">
                                <div class="card text-center border-0 bg-primary text-white">
                                    <div class="card-body">
                                        <h3 class="mb-1">247</h3>
                                        <p class="mb-0 small">विद्यार्थी</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card text-center border-0 bg-success text-white">
                                    <div class="card-body">
                                        <h3 class="mb-1">45</h3>
                                        <p class="mb-0 small">कोठा</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card text-center border-0 bg-warning text-white">
                                    <div class="card-body">
                                        <h3 class="mb-1">89%</h3>
                                        <p class="mb-0 small">आबादी</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card text-center border-0 bg-info text-white">
                                    <div class="card-body">
                                        <h3 class="mb-1">₹12,500</h3>
                                        <p class="mb-0 small">औसत भुक्तानी</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="mt-5">
                        <h5><i class="fas fa-history me-2 text-primary"></i>हालको गतिविधि</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>गतिविधि</th>
                                        <th>मिति</th>
                                        <th>स्थिति</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>मासिक भुक्तानी जम्मा</td>
                                        <td>2025-04-12</td>
                                        <td><span class="badge bg-success">सफल</span></td>
                                    </tr>
                                    <tr>
                                        <td>खाना मेनु अपडेट</td>
                                        <td>2025-04-11</td>
                                        <td><span class="badge bg-info">हेरियो</span></td>
                                    </tr>
                                    <tr>
                                        <td>प्रोफाइल अपडेट</td>
                                        <td>2025-04-10</td>
                                        <td><span class="badge bg-warning">प्रगति</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Student Dashboard specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Student Dashboard loaded');
        
        // Add any student-specific functionality here
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.addEventListener('closed.bs.alert', function () {
                console.log('Alert closed');
            })
        });
    });
</script>
@endpush