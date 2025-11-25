@extends('layouts.owner')

@section('title', 'बुकिंग अनुरोध विवरण')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">बुकिंग अनुरोध विवरण</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.booking-requests.index', ['status' => $bookingRequest->status]) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> पछाडि
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">बुकिंग जानकारी</h6>
                    <span class="badge 
                        @if($bookingRequest->status == 'pending') bg-warning
                        @elseif($bookingRequest->status == 'approved') bg-success
                        @elseif($bookingRequest->status == 'rejected') bg-danger
                        @else bg-secondary @endif">
                        @if($bookingRequest->status == 'pending') पेन्डिङ
                        @elseif($bookingRequest->status == 'approved') स्वीकृत
                        @elseif($bookingRequest->status == 'rejected') अस्वीकृत
                        @else {{ $bookingRequest->status }} @endif
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>अनुरोध नम्बर:</strong> #{{ str_pad($bookingRequest->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p><strong>प्रकार:</strong> 
                                @if($bookingRequest instanceof \App\Models\Booking)
                                    <span class="badge bg-info">बुकिंग</span>
                                @else
                                    <span class="badge bg-secondary">अनुरोध</span>
                                @endif
                            </p>
                            <p><strong>नाम:</strong> 
                                @if($bookingRequest instanceof \App\Models\Booking)
                                    {{ $bookingRequest->guest_name ?? $bookingRequest->user->name ?? 'N/A' }}
                                @else
                                    {{ $bookingRequest->name }}
                                @endif
                            </p>
                            <p><strong>फोन:</strong> 
                                @if($bookingRequest instanceof \App\Models\Booking)
                                    {{ $bookingRequest->guest_phone ?? 'N/A' }}
                                @else
                                    {{ $bookingRequest->phone }}
                                @endif
                            </p>
                            <p><strong>इमेल:</strong> 
                                @if($bookingRequest instanceof \App\Models\Booking)
                                    {{ $bookingRequest->guest_email ?? $bookingRequest->email ?? 'N/A' }}
                                @else
                                    {{ $bookingRequest->email ?? 'N/A' }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>होस्टल:</strong> {{ $bookingRequest->hostel->name }}</p>
                            <p><strong>कोठा:</strong> 
                                @if($bookingRequest->room)
                                    {{ $bookingRequest->room->room_number }}
                                    ({{ $bookingRequest->room->nepali_type ?? $bookingRequest->room->type }})
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </p>
                            <p><strong>चेक-इन मिति:</strong> {{ $bookingRequest->check_in_date->format('Y-m-d') }}</p>
                            <p><strong>चेक-आउट मिति:</strong> 
                                @if($bookingRequest->check_out_date)
                                    {{ $bookingRequest->check_out_date->format('Y-m-d') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </p>
                            <p><strong>रकम:</strong> 
                                @if($bookingRequest->amount)
                                    रु {{ number_format($bookingRequest->amount) }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($bookingRequest->message)
                    <div class="mt-3">
                        <strong>सन्देश:</strong>
                        <p class="border p-3 rounded bg-light">{{ $bookingRequest->message }}</p>
                    </div>
                    @endif

                    @if($bookingRequest->admin_notes)
                    <div class="mt-3">
                        <strong>प्रशासकीय नोटहरू:</strong>
                        <p class="border p-3 rounded bg-light">{{ $bookingRequest->admin_notes }}</p>
                    </div>
                    @endif

                    @if($bookingRequest->rejection_reason)
                    <div class="mt-3">
                        <strong>अस्वीकृतको कारण:</strong>
                        <p class="border p-3 rounded bg-light text-danger">{{ $bookingRequest->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">कार्यहरू</h6>
                </div>
                <div class="card-body">
                    @if($bookingRequest->status === 'pending')
                    <div class="d-grid gap-3">
                        <form action="{{ route('owner.booking-requests.approve', $bookingRequest) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="admin_notes" class="form-label">नोटहरू (वैकल्पिक):</label>
                                <textarea class="form-control" id="admin_notes" name="admin_notes" rows="2" 
                                          placeholder="स्वीकृत गर्ने कारण...">{{ old('admin_notes') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 d-flex align-items-center justify-content-center py-2">
                                <i class="fas fa-check me-2"></i> स्वीकृत गर्नुहोस्
                            </button>
                        </form>
                        
                        <form action="{{ route('owner.booking-requests.reject', $bookingRequest) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="reject_notes" class="form-label">अस्वीकृतको कारण:</label>
                                <textarea class="form-control" id="reject_notes" name="admin_notes" rows="2" 
                                          placeholder="अस्वीकृत गर्ने कारण..." required>{{ old('admin_notes') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-lg w-100 d-flex align-items-center justify-content-center py-2">
                                <i class="fas fa-times me-2"></i> अस्वीकृत गर्नुहोस्
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="alert 
                        @if($bookingRequest->status == 'approved') alert-success
                        @elseif($bookingRequest->status == 'rejected') alert-danger
                        @else alert-secondary @endif">
                        <i class="fas 
                            @if($bookingRequest->status == 'approved') fa-check-circle
                            @elseif($bookingRequest->status == 'rejected') fa-times-circle
                            @else fa-info-circle @endif me-2"></i>
                        यो अनुरोध पहिले नै 
                        @if($bookingRequest->status == 'approved') स्वीकृत
                        @elseif($bookingRequest->status == 'rejected') अस्वीकृत
                        @else {{ $bookingRequest->status }} @endif गरिसकिएको छ
                    </div>
                    
                    <!-- Additional action buttons for approved/rejected status -->
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('owner.booking-requests.index', ['status' => $bookingRequest->status]) }}" 
                           class="btn btn-outline-primary d-flex align-items-center justify-content-center py-2">
                            <i class="fas fa-list me-2"></i> सबै {{ $bookingRequest->status == 'approved' ? 'स्वीकृत' : 'अस्वीकृत' }} अनुरोध हेर्नुहोस्
                        </a>
                        
                        @if($bookingRequest->status == 'approved')
                        <button class="btn btn-outline-info d-flex align-items-center justify-content-center py-2">
                            <i class="fas fa-user-plus me-2"></i> विद्यार्थीको रूपमा दर्ता गर्नुहोस्
                        </button>
                        @endif
                    </div>
                    @endif

                    <hr>
                    
                    <div class="text-muted small">
                        <p><strong>सिर्जना मिति:</strong> {{ $bookingRequest->created_at->format('Y-m-d H:i') }}</p>
                        @if($bookingRequest->approved_at)
                        <p><strong>स्वीकृत मिति:</strong> {{ $bookingRequest->approved_at->format('Y-m-d H:i') }}</p>
                        @endif
                        @if($bookingRequest->rejected_at)
                        <p><strong>अस्वीकृत मिति:</strong> {{ $bookingRequest->rejected_at->format('Y-m-d H:i') }}</p>
                        @endif
                        @if($bookingRequest->approved_by)
                        <p><strong>स्वीकृत गर्ने:</strong> 
                            @php
                                $approvedBy = \App\Models\User::find($bookingRequest->approved_by);
                            @endphp
                            {{ $approvedBy->name ?? 'N/A' }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Improved button styles for better UX */
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
    border-radius: 0.5rem;
    font-weight: 500;
}

/* Ensure consistent spacing */
.d-grid.gap-3 {
    gap: 1rem !important;
}

.d-grid.gap-2 {
    gap: 0.75rem !important;
}

/* Better form control styling */
.form-control {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    padding: 0.75rem;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Alert improvements */
.alert {
    border-radius: 0.5rem;
    padding: 1rem 1.5rem;
    border: none;
}

/* Card styling enhancements */
.card {
    border: none;
    border-radius: 0.75rem;
}

.card-header {
    border-radius: 0.75rem 0.75rem 0 0 !important;
    border-bottom: 1px solid #e3e6f0;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .btn-lg {
        padding: 0.65rem 1.25rem;
        font-size: 1rem;
    }
    
    .d-grid.gap-3 {
        gap: 0.75rem !important;
    }
}
</style>
@endsection