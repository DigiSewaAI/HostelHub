@extends('layouts.student')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white shadow-lg">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 fw-bold">नमस्ते, {{ $student->user->name }}! 👋</h2>
                            <p class="mb-0 fs-5">{{ $hostel->name }} मा तपाईंलाई स्वागत छ</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="badge bg-light text-dark p-3 fs-6">
                                <i class="fas fa-calendar me-2"></i>
                                {{ now()->format('F j, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-hover border-primary shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-door-open fa-2x text-primary mb-3"></i>
                    <h5 class="text-dark">कोठा नं.</h5>
                    <h3 class="text-primary fw-bold">{{ $student->room->room_number ?? 'उपलब्ध छैन' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover border-success shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-utensils fa-2x text-success mb-3"></i>
                    <h5 class="text-dark">आजको खाना</h5>
                    <h3 class="text-success fw-bold">{{ $todayMeal ? 'उपलब्ध' : 'हाल अपडेट छैन' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover border-warning shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-receipt fa-2x text-warning mb-3"></i>
                    <h5 class="text-dark">भुक्तानी</h5>
                    <h3 class="text-warning fw-bold">
                        @if($paymentStatus == 'Paid')
                            भुक्तानी भएको
                        @else
                            बाकी
                        @endif
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover border-info shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-bell fa-2x text-info mb-3"></i>
                    <h5 class="text-dark">सूचनाहरू</h5>
                    <h3 class="text-info fw-bold">{{ $notifications->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Main Content -->
        <div class="col-lg-8">
            <!-- Room & Payment Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-home me-2"></i>कोठा जानकारी</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-dark"><strong>होस्टेल:</strong></td>
                                    <td class="text-dark">{{ $hostel->name ?? 'उपलब्ध छैन' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>कोठा नं.:</strong></td>
                                    <td class="text-dark">{{ $student->room->room_number ?? 'उपलब्ध छैन' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>कोठा प्रकार:</strong></td>
                                    <td class="text-dark">{{ $student->room->type ?? 'उपलब्ध छैन' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>मासिक भुक्तानी:</strong></td>
                                    <td class="text-success fw-bold">रु. {{ $student->room->rent ?? 'उपलब्ध छैन' }}</td>
                                </tr>
                            </table>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#roomDetailsModal">
                                <i class="fas fa-info-circle me-1"></i>पूर्ण विवरण हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-warning text-dark py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2"></i>भुक्तानी स्थिति</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-dark"><strong>अन्तिम भुक्तानी:</strong></td>
                                    <td class="text-dark">{{ $lastPayment ? 'रु. ' . $lastPayment->amount : 'कुनै भुक्तानी छैन' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>अन्तिम मिति:</strong></td>
                                    <td class="text-dark">{{ $lastPayment ? $lastPayment->created_at->format('Y-m-d') : 'हाल अपडेट छैन' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>स्थिति:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $paymentStatus == 'Paid' ? 'success' : 'danger' }} p-2">
                                            @if($paymentStatus == 'Paid')
                                                भुक्तानी भएको
                                            @else
                                                बाकी
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <button class="btn btn-warning btn-sm">
                                <i class="fas fa-money-bill me-1"></i>भुक्तानी गर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Meal & Notifications -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-utensils me-2"></i>आजको खानाको योजना</h5>
                        </div>
                        <div class="card-body">
                            @if($todayMeal)
                                <h6 class="text-success fw-bold">{{ $todayMeal->meal_type }}</h6>
                                @if(is_array($todayMeal->items))
                                    @if(isset($todayMeal->items['breakfast']))
                                        <p class="mb-2 text-dark"><strong>बिहानको खाना:</strong><br>{{ $todayMeal->items['breakfast'] }}</p>
                                    @endif
                                    @if(isset($todayMeal->items['lunch']))
                                        <p class="mb-2 text-dark"><strong>दिउँसोको खाना:</strong><br>{{ $todayMeal->items['lunch'] }}</p>
                                    @endif
                                    @if(isset($todayMeal->items['dinner']))
                                        <p class="mb-2 text-dark"><strong>रातिको खाना:</strong><br>{{ $todayMeal->items['dinner'] }}</p>
                                    @endif
                                @else
                                    <p class="mb-2 text-dark"><strong>मुख्य खाना:</strong> {{ $todayMeal->main_dish ?? 'उपलब्ध छैन' }}</p>
                                    <p class="mb-2 text-dark"><strong>साइड डिश:</strong> {{ $todayMeal->side_dish ?? 'उपलब्ध छैन' }}</p>
                                @endif
                                <p class="mb-0 text-dark"><strong>समय:</strong> {{ $todayMeal->serving_time ?? 'उपलब्ध छैन' }}</p>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-utensils fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">आजको खानाको योजना हाल अपडेट छैन</p>
                                </div>
                            @endif
                            <div class="mt-3">
                                <a href="{{ route('student.meal-menus') }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-calendar me-1"></i>सप्ताहिक मेनु हेर्नुहोस्
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-bell me-2"></i>हालैका सूचनाहरू</h5>
                        </div>
                        <div class="card-body">
                            @if($notifications->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($notifications->take(3) as $notification)
                                        <div class="list-group-item px-0 py-2 border-0">
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            <p class="mb-0 small text-dark">{{ Str::limit($notification->message, 50) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <a href="{{ route('student.notifications') }}" class="btn btn-outline-info btn-sm mt-2">
                                    सबै सूचनाहरू हेर्नुहोस्
                                </a>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">कुनै नयाँ सूचना छैन</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bolt me-2"></i>द्रुत कार्यहरू</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.profile') }}" class="btn btn-outline-primary text-start py-2">
                            <i class="fas fa-user me-2"></i>मेरो प्रोफाइल
                        </a>
                        <a href="{{ route('student.meal-menus') }}" class="btn btn-outline-success text-start py-2">
                            <i class="fas fa-utensils me-2"></i>खानाको योजना
                        </a>
                        <button class="btn btn-outline-warning text-start py-2" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="fas fa-credit-card me-2"></i>भुक्तानी गर्नुहोस्
                        </button>
                        <a href="{{ route('student.reviews') }}" class="btn btn-outline-dark text-start py-2">
                            <i class="fas fa-star me-2"></i>समीक्षा लेख्नुहोस्
                        </a>
                        <button class="btn btn-outline-danger text-start py-2" data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                            <i class="fas fa-tools me-2"></i>मर्मत समस्या
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-purple text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i>आगामी घटनाहरू</h5>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingEvents->take(3) as $event)
                                <div class="list-group-item px-0 py-2 border-0">
                                    <h6 class="mb-1 text-primary fw-bold">{{ $event->title }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $event->date->format('M j') }} at {{ $event->time }}
                                    </small>
                                    <p class="mb-0 small text-dark">{{ Str::limit($event->description, 40) }}</p>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('student.events') }}" class="btn btn-outline-purple btn-sm mt-2 w-100">
                            सबै घटनाहरू हेर्नुहोस्
                        </a>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">कुनै आगामी घटना छैन</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hostel Gallery Preview -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-images me-2"></i>होस्टेल ग्यालेरी</h5>
                </div>
                <div class="card-body">
                    @if($galleryImages->count() > 0)
                        <div class="row g-2">
                            @foreach($galleryImages->take(4) as $image)
                                <div class="col-6">
                                    <img src="{{ asset('storage/'.$image->path) }}" 
                                         class="img-fluid rounded gallery-thumb" 
                                         alt="Hostel Image"
                                         style="height: 80px; object-fit: cover; width: 100%; cursor: pointer;"
                                         onclick="openImageModal('{{ asset('storage/'.$image->path) }}')">
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('student.gallery') }}" class="btn btn-outline-dark btn-sm w-100">
                                <i class="fas fa-expand me-1"></i>पूर्ण ग्यालेरी हेर्नुहोस्
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-images fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">ग्यालेरी उपलब्ध छैन</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('student.modals.room-details')
@include('student.modals.payment')
@include('student.modals.maintenance')
@include('student.modals.gallery-view')

<style>
.card-hover:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
.bg-purple {
    background-color: #6f42c1 !important;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
.btn-outline-purple {
    color: #6f42c1;
    border-color: #6f42c1;
}
.btn-outline-purple:hover {
    background-color: #6f42c1;
    color: white;
}
.gallery-thumb:hover {
    opacity: 0.8;
    transform: scale(1.05);
    transition: all 0.3s ease;
}
.card {
    border-radius: 12px;
    overflow: hidden;
}
.card-header {
    border-radius: 12px 12px 0 0 !important;
}
</style>

<script>
function openImageModal(imageUrl) {
    document.getElementById('galleryImage').src = imageUrl;
    new bootstrap.Modal(document.getElementById('galleryViewModal')).show();
}
</script>
@endsection