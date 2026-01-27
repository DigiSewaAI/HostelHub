@extends('layouts.student')

@section('title', 'मेरो कोठा - विवरण')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-door-open text-primary me-2"></i>
                        मेरो कोठा विवरण
                    </h1>
                    <p class="text-muted mb-0">तपाईंको कोठाको पूरा विवरण</p>
                </div>
                <div>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i> ड्यासबोर्डमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Room Details Card -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <!-- Room Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        कोठा जानकारी
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column: Room Image -->
                        <div class="col-md-5 mb-4">
                            <div class="room-image-container border rounded overflow-hidden">
                                @if($room->image_url && $room->image_url != asset('images/default-room.jpg'))
                                    <img src="{{ $room->image_url }}" 
                                         alt="Room {{ $room->room_number }}" 
                                         class="img-fluid w-100 rounded"
                                         style="height: 280px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 280px;">
                                        <div class="text-center p-4">
                                            <i class="fas fa-door-closed fa-4x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">कोठाको फोटो उपलब्ध छैन</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Room Status Badge -->
                            <div class="mt-3 text-center">
                                @php
                                    $statusColors = [
                                        'available' => 'success',
                                        'partially_available' => 'warning',
                                        'occupied' => 'danger',
                                        'maintenance' => 'secondary'
                                    ];
                                    $statusColor = $statusColors[$room->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusColor }} fs-6 py-2 px-4">
                                    <i class="fas fa-circle me-1"></i>
                                    {{ $room->nepali_status }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Right Column: Room Details -->
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td width="40%" class="text-muted py-2">
                                                <i class="fas fa-hashtag me-2"></i>कोठा नम्बर
                                            </td>
                                            <td class="py-2">
                                                <strong class="fs-5">{{ $room->room_number ?? 'N/A' }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">
                                                <i class="fas fa-building me-2"></i>होस्टल
                                            </td>
                                            <td class="py-2">
                                                <strong>{{ $room->hostel->name ?? 'N/A' }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">
                                                <i class="fas fa-layer-group me-2"></i>प्रकार
                                            </td>
                                            <td class="py-2">
                                                <span class="badge bg-info">{{ $room->nepali_type }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">
                                                <i class="fas fa-users me-2"></i>क्षमता
                                            </td>
                                            <td class="py-2">
                                                <strong>{{ $room->capacity ?? '0' }} जना</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">
                                                <i class="fas fa-user-check me-2"></i>हालको अधिभोग
                                            </td>
                                            <td class="py-2">
                                                <strong>{{ $room->current_occupancy ?? 0 }} जना</strong>
                                                <small class="text-muted ms-2">(तपाईं सहित)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">
                                                <i class="fas fa-bed me-2"></i>खाली ठाउँ
                                            </td>
                                            <td class="py-2">
                                                @if(($room->available_beds ?? 0) > 0)
                                                    <span class="badge bg-success">{{ $room->available_beds ?? 0 }} जना</span>
                                                @else
                                                    <span class="badge bg-danger">खाली छैन</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">
                                                <i class="fas fa-money-bill-wave me-2"></i>मासिक भाडा
                                            </td>
                                            <td class="py-2">
                                                <h5 class="text-success mb-0">
                                                    रु {{ number_format($room->price, 2) }}
                                                    <small class="text-muted fs-6">/ महिना</small>
                                                </h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">
                                                <i class="fas fa-tags me-2"></i>ग्यालरी श्रेणी
                                            </td>
                                            <td class="py-2">
                                                {{ $room->gallery_category_nepali ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">
                                                <i class="fas fa-map-marker-alt me-2"></i>तल्ला
                                            </td>
                                            <td class="py-2">
                                                {{ $room->floor ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Description -->
                            @if($room->description)
                            <div class="mt-4">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-align-left me-2"></i>विवरण
                                </h6>
                                <div class="border rounded p-3 bg-light">
                                    {{ $room->description }}
                                </div>
                            </div>
                            @endif
                            
                            <!-- Metadata -->
                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        सिर्जना गरिएको: {{ $room->created_at->format('Y-m-d') }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        अन्तिम अपडेट: {{ $room->updated_at->format('Y-m-d') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <!-- Roommates Card - FIXED VERSION -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-gradient-success text-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>
            साथीहरू (Roommates)
            <span class="badge bg-light text-dark ms-2">{{ $roommates->count() }} जना</span>
        </h5>
    </div>
    <div class="card-body">
        @if($roommates->count() > 0)
            <div class="row g-3">
                @foreach($roommates as $roommate)
                <div class="col-12">
                    <div class="d-flex align-items-center p-3 rounded border bg-light">
                        <!-- Image को सट्टा icon प्रयोग गर्ने -->
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-user fa-lg"></i>
                            </div>
                        </div>
                        
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fw-bold">
                                        {{ $roommate->name }}
                                        @if($roommate->id == $student->id)
                                            <span class="badge bg-primary ms-2">तपाईं</span>
                                        @else
                                            <span class="badge bg-secondary ms-2">साथी</span>
                                        @endif
                                    </h6>
                                    
                                    <div class="text-muted small">
                                        <i class="fas fa-user-graduate me-1"></i> 
                                        {{ $roommate->college ?? 'विद्यार्थी' }}
                                    </div>
                                </div>
                                
                                @if($roommate->created_at)
                                <div class="text-end">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $roommate->created_at->format('Y-m-d') }} देखि
                                    </small>
                                    @if($roommate->status === 'active')
                                        <span class="badge bg-success">सक्रिय</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <div class="empty-state-icon mb-3">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user-slash fa-2x text-muted"></i>
                    </div>
                </div>
                <h6 class="text-muted mb-2">यस कोठामा अहिले तपाईं मात्र हुनुहुन्छ</h6>
                <p class="text-muted small">नयाँ साथीहरू आउन सक्छन्</p>
            </div>
        @endif
        
        <!-- Roommates Info Summary - SIMPLIFIED -->
        <div class="mt-4 pt-3 border-top">
            <h6 class="text-muted mb-3">
                <i class="fas fa-info-circle me-2"></i>साथीहरूको जानकारी
            </h6>
            
            <div class="row text-center">
                <div class="col-6">
                    <div class="p-2 bg-light rounded">
                        <div class="h4 mb-0 text-primary">{{ $roommates->count() }}</div>
                        <small class="text-muted">कुल साथी</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-light rounded">
                        <div class="h4 mb-0 text-success">
                            {{ $roommates->where('status', 'active')->count() }}
                        </div>
                        <small class="text-muted">सक्रिय</small>
                    </div>
                </div>
            </div>
            
            <!-- Privacy Note -->
            <div class="mt-3 pt-2 border-top small text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                <strong>प्राइभेसी नोट:</strong> अन्य साथीहरूको व्यक्तिगत जानकारी सुरक्षित छ।
            </div>
        </div>
    </div>
</div>
        
        <!-- Room Occupancy Summary -->
        <div class="mt-4 pt-3 border-top">
            <h6 class="text-muted mb-3">
                <i class="fas fa-chart-pie me-2"></i>अधिभोग विवरण
            </h6>
            
            @php
                $currentOccupancy = $room->current_occupancy ?? 0;
                $occupancyPercentage = $room->capacity > 0 ? ($currentOccupancy / $room->capacity) * 100 : 0;
                $availableBeds = $room->available_beds ?? 0;
                $roundedPercentage = round($occupancyPercentage);
            @endphp
            
            <!-- Custom Progress Bar -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark">कोठा अधिभोग</span>
                    <span class="badge bg-primary fs-6">{{ $roundedPercentage }}%</span>
                </div>
                
                <div class="position-relative" style="height: 35px; background: #f8f9fa; border-radius: 20px; border: 2px solid #e0e0e0; overflow: hidden; box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="h-100 bg-primary position-absolute top-0 start-0 d-flex align-items-center justify-content-center" 
                         style="width: {{ $occupancyPercentage }}%; border-radius: 18px; background: linear-gradient(90deg, #4e73df, #224abe);">
                        <span class="fw-bold text-white text-shadow" style="font-size: 16px; letter-spacing: 0.5px;">
                            {{ $roundedPercentage }}%
                        </span>
                    </div>
                    
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                        <span class="fw-bold text-dark" style="font-size: 16px; z-index: 5; mix-blend-mode: difference;">
                            {{ $roundedPercentage }}%
                        </span>
                    </div>
                </div>
                
                <!-- Stats below -->
                <div class="mt-3">
                    <div class="row">
                        <div class="col-6">
                            <div class="bg-light rounded p-3 text-center border">
                                <div class="h4 mb-1 text-primary fw-bold">{{ $currentOccupancy }}/{{ $room->capacity }}</div>
                                <small class="text-muted">अधिभोग</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3 text-center border">
                                <div class="h4 mb-1 fw-bold {{ $availableBeds > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $availableBeds }}
                                </div>
                                <small class="text-muted">खाली बेड</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional info -->
            <div class="mt-3 pt-2 border-top small">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">
                        <i class="fas fa-bed me-1"></i>कुल बेड: {{ $room->capacity }}
                    </span>
                    <span class="text-muted">
                        <i class="fas fa-user-check me-1"></i>अधिभोग: {{ $currentOccupancy }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Card - SIMPLIFIED -->
<div class="card shadow-sm">
    <div class="card-header bg-gradient-warning text-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-bolt me-2"></i>
            द्रुत कार्यहरू
        </h5>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <button class="btn btn-outline-primary text-start" data-bs-toggle="modal" data-bs-target="#reportIssueModal">
                <i class="fas fa-tools me-2"></i>समस्या रिपोर्ट गर्नुहोस्
            </button>
            <button class="btn btn-outline-info text-start">
                <i class="fas fa-comment me-2"></i>प्रतिक्रिया दिनुहोस्
            </button>
        </div>
        
        <!-- Privacy Notice Section -->
        <div class="alert alert-info mt-3 mb-0">
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="ms-3">
                    <h6 class="alert-heading mb-2 small">प्राइभेसी सुरक्षा</h6>
                    <p class="mb-2 small">हामी तपाईंको र तपाईंका साथीहरूको प्राइभेसीको सम्मान गर्दछौं।</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Issue Modal -->
<div class="modal fade" id="reportIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('student.report-room-issue') }}" method="POST">
                @csrf
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        कोठा समस्या रिपोर्ट गर्नुहोस्
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">समस्याको प्रकार</label>
                        <select name="issue_type" class="form-select" required>
                            <option value="">चयन गर्नुहोस्</option>
                            <option value="cleaning">सफाइ समस्या</option>
                            <option value="maintenance">मर्मत आवश्यक</option>
                            <option value="noise">शोर समस्या</option>
                            <option value="furniture">सामान समस्या</option>
                            <option value="other">अन्य</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">विवरण</label>
                        <textarea name="description" class="form-control" rows="3" 
                                  placeholder="समस्याको विस्तृत विवरण लेख्नुहोस्..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">प्राथमिकता</label>
                        <select name="priority" class="form-select" required>
                            <option value="low">निम्न</option>
                            <option value="medium" selected>मध्यम</option>
                            <option value="high">उच्च</option>
                            <option value="urgent">अत्यावश्यक</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-1"></i> रिपोर्ट गर्नुहोस्
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add some CSS for better appearance -->
<style>
    .room-image-container {
        transition: transform 0.3s;
    }
    .room-image-container:hover {
        transform: scale(1.02);
    }
    .icon-container {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .text-shadow {
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }
</style>
@endsection