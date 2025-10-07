@extends('layouts.student')

@section('title', 'बुकिंग विवरण #' . $booking->id)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">बुकिंग विवरण #{{ $booking->id }}</h1>
        <a href="{{ route('student.bookings.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> बुकिंगहरूमा फर्कनुहोस्
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">बुकिंग जानकारी</h6>
                    <x-status-badge :status="$booking->status" />
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">होस्टेल विवरण</h6>
                            <p><strong>नाम:</strong> {{ $booking->room->hostel->name }}</p>
                            <p><strong>ठेगाना:</strong> {{ $booking->room->hostel->location }}</p>
                            <p><strong>कोठा नं.:</strong> {{ $booking->room->room_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">बुकिंग अवधि</h6>
                            <p><strong>चेक-इन:</strong> {{ $booking->check_in_date->format('Y-m-d') }}</p>
                            <p><strong>चेक-आउट:</strong> {{ $booking->check_out_date->format('Y-m-d') }}</p>
                            <p><strong>अवधि:</strong> {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} दिन</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="font-weight-bold">बुकिंग उद्देश्य</h6>
                            <p>{{ $booking->purpose }}</p>
                        </div>
                    </div>

                    @if($booking->status === 'approved')
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle"></i>
                        <strong>बुकिंग स्वीकृत भएको छ!</strong> 
                        कृपया भुक्तानी गर्नुहोस् र होस्टेल प्रबन्धकलाई सम्पर्क गर्नुहोस्।
                    </div>
                    @elseif($booking->status === 'rejected')
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-times-circle"></i>
                        <strong>बुकिंग अस्वीकृत भएको छ</strong>
                        @if($booking->rejection_reason)
                        <br>कारण: {{ $booking->rejection_reason }}
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">कार्यहरू</h6>
                </div>
                <div class="card-body">
                    @if($booking->status === 'approved')
                    <a href="{{ route('student.payments.create', $booking->id) }}" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-credit-card"></i> भुक्तानी गर्नुहोस्
                    </a>
                    @endif
                    
                    @if(in_array($booking->status, ['pending', 'approved']))
                    <form action="{{ route('student.bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" 
                                onclick="return confirm('के तपाईं यो बुकिंग रद्द गर्न चाहनुहुन्छ?')">
                            <i class="fas fa-times"></i> बुकिंग रद्द गर्नुहोस्
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">समयरेखा</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $booking->created_at ? 'active' : '' }}">
                            <small>{{ $booking->created_at->format('Y-m-d H:i') }}</small>
                            <p>बुकिंग सिर्जना गरियो</p>
                        </div>
                        @if($booking->status === 'approved' || $booking->status === 'rejected')
                        <div class="timeline-item active">
                            <small>{{ $booking->updated_at->format('Y-m-d H:i') }}</small>
                            <p>बुकिंग {{ $booking->status === 'approved' ? 'स्वीकृत' : 'अस्वीकृत' }} गरियो</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection