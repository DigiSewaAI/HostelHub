@extends('layouts.owner')

@section('title', 'बुकिंग अनुरोधहरू')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">बुकिंग अनुरोधहरू</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> ड्यासबोर्ड
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $organizationId = session('current_organization_id');
            $ownerId = Auth::id();
            
            // ✅ FIXED: Get hostels managed by this owner
            $hostelIds = \App\Models\Hostel::where('organization_id', $organizationId)
                ->where('owner_id', $ownerId)
                ->pluck('id');
            
            // Count from both Booking and BookingRequest models
            $bookingRequestsCount = \App\Models\BookingRequest::whereIn('hostel_id', $hostelIds)->count();
            $bookingsCount = \App\Models\Booking::whereIn('hostel_id', $hostelIds)->count();
            
            $stats = [
                'total' => $bookingRequestsCount + $bookingsCount,
                'pending' => \App\Models\BookingRequest::whereIn('hostel_id', $hostelIds)->where('status', 'pending')->count() + 
                           \App\Models\Booking::whereIn('hostel_id', $hostelIds)->where('status', \App\Models\Booking::STATUS_PENDING)->count(),
                'approved' => \App\Models\BookingRequest::whereIn('hostel_id', $hostelIds)->where('status', 'approved')->count() + 
                            \App\Models\Booking::whereIn('hostel_id', $hostelIds)->where('status', \App\Models\Booking::STATUS_APPROVED)->count(),
                'rejected' => \App\Models\BookingRequest::whereIn('hostel_id', $hostelIds)->where('status', 'rejected')->count() + 
                            \App\Models\Booking::whereIn('hostel_id', $hostelIds)->where('status', \App\Models\Booking::STATUS_REJECTED)->count(),
            ];
        @endphp

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल अनुरोधहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                पेन्डिङ अनुरोधहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                स्वीकृत अनुरोधहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                अस्वीकृत अनुरोधहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Requests Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">बुकिंग अनुरोध सूची</h6>
        </div>
        <div class="card-body">
            @php
                $allRequests = $allRequests ?? [];
            @endphp

            @if(count($allRequests) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>प्रकार</th>
                                <th>अनुरोधकर्ता</th>
                                <th>होस्टल</th>
                                <th>कोठा</th>
                                <th>चेक-इन</th>
                                <th>चेक-आउट</th>
                                <th>फोन</th>
                                <th>मिति</th>
                                <th>स्थिति</th>
                                <th>कार्यहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allRequests as $request)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($request instanceof \App\Models\Booking)
                                        <span class="badge bg-info">बुकिंग</span>
                                    @else
                                        <span class="badge bg-secondary">अनुरोध</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>
                                        @if($request instanceof \App\Models\Booking)
                                            {{ $request->guest_name ?? $request->user->name ?? 'N/A' }}
                                        @else
                                            {{ $request->name }}
                                        @endif
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        @if($request instanceof \App\Models\Booking)
                                            {{ $request->guest_email ?? $request->email ?? 'N/A' }}
                                        @else
                                            {{ $request->email ?? 'N/A' }}
                                        @endif
                                    </small>
                                </td>
                                <td>{{ $request->hostel->name }}</td>
                                <td>
                                    @if($request->room)
                                        {{ $request->room->room_number }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $request->check_in_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($request->check_out_date)
                                        {{ $request->check_out_date->format('Y-m-d') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request instanceof \App\Models\Booking)
                                        {{ $request->guest_phone ?? 'N/A' }}
                                    @else
                                        {{ $request->phone }}
                                    @endif
                                </td>
                                <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($request->status == 'pending') bg-warning
                                        @elseif($request->status == 'approved') bg-success
                                        @elseif($request->status == 'rejected') bg-danger
                                        @else bg-secondary @endif">
                                        @if($request->status == 'pending') पेन्डिङ
                                        @elseif($request->status == 'approved') स्वीकृत
                                        @elseif($request->status == 'rejected') अस्वीकृत
                                        @else {{ $request->status }} @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('owner.booking-requests.show', $request->id) }}" 
                                           class="btn btn-outline-primary" title="हेर्नुहोस्">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($request->status === 'pending')
                                        <form action="{{ route('owner.booking-requests.approve', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="स्वीकृत गर्नुहोस्">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('owner.booking-requests.reject', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger" title="अस्वीकृत गर्नुहोस्">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">कुनै बुकिंग अनुरोध फेला परेन</h5>
                    <p class="text-muted">नयाँ बुकिंग अनुरोधहरू यहाँ देखिनेछन्</p>
                </div>
            @endif
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
@endsection