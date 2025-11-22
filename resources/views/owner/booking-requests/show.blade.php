@extends('layouts.owner')

@section('title', 'बुकिंग अनुरोध विवरण')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">बुकिंग अनुरोध विवरण</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.booking-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> पछाडि
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">बुकिंग जानकारी</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>अनुरोध नम्बर:</strong> #{{ str_pad($bookingRequest->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p><strong>नाम:</strong> {{ $bookingRequest->name }}</p>
                            <p><strong>फोन:</strong> {{ $bookingRequest->phone }}</p>
                            <p><strong>इमेल:</strong> {{ $bookingRequest->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>होस्टल:</strong> {{ $bookingRequest->hostel->name }}</p>
                            <p><strong>कोठा प्रकार:</strong> {{ (new \App\Models\Room())->getNepaliTypeAttribute($bookingRequest->room_type) }}</p>
                            <p><strong>चेक-इन मिति:</strong> {{ $bookingRequest->check_in_date->format('Y-m-d') }}</p>
                            <p><strong>स्थिति:</strong> 
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
                    <div class="d-grid gap-2">
                        <form action="{{ route('owner.booking-requests.approve', $bookingRequest) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block mb-2">
                                <i class="fas fa-check"></i> स्वीकृत गर्नुहोस्
                            </button>
                        </form>
                        
                        <form action="{{ route('owner.booking-requests.reject', $bookingRequest) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times"></i> अस्वीकृत गर्नुहोस्
                            </button>
                        </form>
                    </div>
                    @else
                    <p class="text-muted text-center">यो अनुरोध पहिले नै 
                        @if($bookingRequest->status == 'approved') स्वीकृत
                        @else अस्वीकृत @endif गरिसकिएको छ
                    </p>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection