@props(['booking'])

<div class="card booking-card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">बुकिंग #{{ $booking->id }}</h6>
        <x-status-badge :status="$booking->status" />
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <p class="mb-1"><strong>होस्टेल:</strong> {{ $booking->room->hostel->name }}</p>
                <p class="mb-1"><strong>कोठा:</strong> {{ $booking->room->room_number }}</p>
                <p class="mb-1"><strong>मिति:</strong> 
                    {{ $booking->check_in_date->format('Y-m-d') }} - {{ $booking->check_out_date->format('Y-m-d') }}
                </p>
                <p class="mb-0"><strong>उद्देश्य:</strong> {{ Str::limit($booking->purpose, 100) }}</p>
            </div>
            <div class="col-md-4 text-right">
                <p class="text-muted small">{{ $booking->created_at->diffForHumans() }}</p>
                @if(isset($showActions) && $showActions)
                <a href="{{ route('student.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">
                    विवरण हेर्नुहोस्
                </a>
                @endif
            </div>
        </div>
    </div>
</div>