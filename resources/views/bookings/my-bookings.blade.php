@extends('layouts.app')

@section('title', 'मेरो बुकिङहरू')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">मेरो बुकिङहरू</h2>
                <a href="{{ route('student.rooms.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> नयाँ कोठा बुक गर्नुहोस्
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($bookings->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">तपाईंसँग कुनै बुकिङ छैन</h4>
                <p class="text-muted">कोठा बुक गर्न कोठाहरू ब्राउज गर्नुहोस् र बुकिङ गर्नुहोस्</p>
                <a href="{{ route('student.rooms.index') }}" class="btn btn-primary">
                    <i class="fas fa-bed"></i> कोठाहरू ब्राउज गर्नुहोस्
                </a>
            </div>
        </div>
    @else
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">बुकिङ सूची</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>होस्टल</th>
                                <th>कोठा</th>
                                <th>चेक-इन</th>
                                <th>चेक-आउट</th>
                                <th>अवधि</th>
                                <th>रकम</th>
                                <th>स्थिति</th>
                                <th>भुक्तानी</th>
                                <th>कार्यहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $booking->hostel->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $booking->hostel->address }}</small>
                                </td>
                                <td>
                                    {{ $booking->room->room_number }}
                                    <br>
                                    <small class="text-muted">{{ $booking->room->type }}</small>
                                </td>
                                <td>
                                    {{ $booking->check_in_date->format('Y-m-d') }}
                                    <br>
                                    <small class="text-muted">{{ $booking->check_in_date->format('l') }}</small>
                                </td>
                                <td>
                                    {{ $booking->check_out_date->format('Y-m-d') }}
                                    <br>
                                    <small class="text-muted">{{ $booking->check_out_date->format('l') }}</small>
                                </td>
                                <td>
                                    {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} दिन
                                </td>
                                <td>
                                    <strong>रु. {{ number_format($booking->amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> पेन्डिङ
                                        </span>
                                        @if($booking->hostel->organization->subscription->requiresManualBookingApproval())
                                        <br>
                                        <small class="text-muted">म्यानेजर स्वीकृतिको पर्खाइमा</small>
                                        @endif
                                    @elseif($booking->status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> स्वीकृत
                                        </span>
                                        @if($booking->approved_at)
                                        <br>
                                        <small class="text-muted">{{ $booking->approved_at->format('Y-m-d') }}</small>
                                        @endif
                                    @elseif($booking->status === 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> अस्वीकृत
                                        </span>
                                        @if($booking->rejection_reason)
                                        <br>
                                        <small class="text-muted" title="{{ $booking->rejection_reason }}">
                                            {{ Str::limit($booking->rejection_reason, 20) }}
                                        </small>
                                        @endif
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-ban"></i> रद्द
                                        </span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-info">
                                            <i class="fas fa-flag-checkered"></i> पूर्ण
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark">
                                            {{ $booking->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->payment_status === 'paid')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> भुक्तानी भएको
                                        </span>
                                    @elseif($booking->payment_status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> पेन्डिङ
                                        </span>
                                    @elseif($booking->payment_status === 'failed')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> असफल
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $booking->payment_status }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#bookingModal{{ $booking->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if($booking->status === 'pending' || $booking->status === 'approved')
                                            @if($booking->check_in_date->isFuture())
                                            <button type="button" class="btn btn-outline-danger cancel-booking-btn"
                                                    data-booking-id="{{ $booking->id }}"
                                                    data-booking-details="{{ $booking->hostel->name }} - {{ $booking->room->room_number }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Booking Details Modal -->
                            <div class="modal fade" id="bookingModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">बुकिङ विवरण</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>होस्टल जानकारी</h6>
                                                    <p>
                                                        <strong>नाम:</strong> {{ $booking->hostel->name }}<br>
                                                        <strong>ठेगाना:</strong> {{ $booking->hostel->address }}<br>
                                                        <strong>सम्पर्क:</strong> {{ $booking->hostel->contact_phone }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>कोठा जानकारी</h6>
                                                    <p>
                                                        <strong>कोठा नम्बर:</strong> {{ $booking->room->room_number }}<br>
                                                        <strong>प्रकार:</strong> {{ $booking->room->type }}<br>
                                                        <strong>मूल्य:</strong> रु. {{ number_format($booking->room->price, 2) }}/महिना
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <h6>बुकिङ जानकारी</h6>
                                                    <p>
                                                        <strong>चेक-इन:</strong> {{ $booking->check_in_date->format('Y-m-d (l)') }}<br>
                                                        <strong>चेक-आउट:</strong> {{ $booking->check_out_date->format('Y-m-d (l)') }}<br>
                                                        <strong>अवधि:</strong> {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} दिन
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>वित्तीय जानकारी</h6>
                                                    <p>
                                                        <strong>कुल रकम:</strong> रु. {{ number_format($booking->amount, 2) }}<br>
                                                        <strong>भुक्तानी स्थिति:</strong> 
                                                        @if($booking->payment_status === 'paid')
                                                            <span class="badge bg-success">भुक्तानी भएको</span>
                                                        @else
                                                            <span class="badge bg-warning">पेन्डिङ</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            @if($booking->notes)
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <h6>नोटहरू</h6>
                                                    <p class="border p-3 rounded">{{ $booking->notes }}</p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($booking->rejection_reason && $booking->status === 'rejected')
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <h6 class="text-danger">अस्वीकृतको कारण</h6>
                                                    <p class="border border-danger p-3 rounded bg-light">
                                                        {{ $booking->rejection_reason }}
                                                    </p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">बन्द गर्नुहोस्</button>
                                            @if($booking->status === 'pending' && $booking->check_in_date->isFuture())
                                            <button type="button" class="btn btn-danger cancel-booking-btn"
                                                    data-booking-id="{{ $booking->id }}"
                                                    data-booking-details="{{ $booking->hostel->name }} - {{ $booking->room->room_number }}">
                                                <i class="fas fa-times"></i> बुकिङ रद्द गर्नुहोस्
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        देखाइएको: {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} of {{ $bookings->total() }} बुकिङहरू
                    </div>
                    <nav>
                        {{ $bookings->links() }}
                    </nav>
                </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">बुकिङ रद्द गर्नुहोस्</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>के तपाईं निश्चित हुनुहुन्छ कि तपाईं यो बुकिङ रद्द गर्न चाहनुहुन्छ?</p>
                <p><strong id="bookingDetails"></strong></p>
                <div class="mb-3">
                    <label for="cancellationReason" class="form-label">रद्द गर्ने कारण (वैकल्पिक)</label>
                    <textarea class="form-control" id="cancellationReason" rows="3" placeholder="रद्द गर्ने कारण लेख्नुहोस्..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="fas fa-times"></i> बुकिङ रद्द गर्नुहोस्
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .badge {
        font-size: 0.75em;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentBookingId = null;
    
    // Cancel booking button click handler
    document.querySelectorAll('.cancel-booking-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentBookingId = this.getAttribute('data-booking-id');
            const bookingDetails = this.getAttribute('data-booking-details');
            
            document.getElementById('bookingDetails').textContent = bookingDetails;
            document.getElementById('cancellationReason').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('cancelBookingModal'));
            modal.show();
        });
    });

    // Confirm cancellation
    document.getElementById('confirmCancelBtn').addEventListener('click', function() {
        if (!currentBookingId) return;

        const reason = document.getElementById('cancellationReason').value;
        const button = this;
        const originalText = button.innerHTML;

        // Show loading state
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> प्रक्रिया हुदैछ...';

        // Send cancellation request
        fetch(`/bookings/${currentBookingId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and reload
                alert(data.message || 'बुकिङ सफलतापूर्वक रद्द गरियो।');
                window.location.reload();
            } else {
                throw new Error(data.message || 'त्रुटि भयो');
            }
        })
        .catch(error => {
            alert('त्रुटि: ' + error.message);
            button.disabled = false;
            button.innerHTML = originalText;
        });
    });

    // Reset modal when closed
    document.getElementById('cancelBookingModal').addEventListener('hidden.bs.modal', function() {
        currentBookingId = null;
        document.getElementById('cancellationReason').value = '';
        const button = document.getElementById('confirmCancelBtn');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-times"></i> बुकिङ रद्द गर्नुहोस्';
    });
});
</script>
@endsection