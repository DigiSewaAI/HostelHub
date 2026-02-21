@extends('layouts.student')

@section('title', 'मेरा बुकिंगहरू')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">मेरा बुकिंगहरू</h1>
        <a href="{{ route('student.bookings.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> नयाँ बुकिंग
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">बुकिंग इतिहास</h6>
                </div>
                <div class="card-body">
                    @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>बुकिंग ID</th>
                                    <th>होस्टेल</th>
                                    <th>कोठा</th>
                                    <th>चेक-इन</th>
                                    <th>चेक-आउट</th>
                                    <th>स्थिति</th>
                                    <th>कार्यहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->room->hostel->name }}</td>
                                    <td>{{ $booking->room->room_number }}</td>
                                    <td>{{ $booking->check_in_date->format('Y-m-d') }}</td>
                                    <td>{{ $booking->check_out_date->format('Y-m-d') }}</td>
                                    <td>
                                        <x-status-badge :status="$booking->status" />
                                    </td>
                                    <td>
                                        <a href="{{ route('student.bookings.show', $booking->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> हेर्नुहोस्
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $bookings->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                        <h4 class="text-gray-500">कुनै बुकिंग छैन</h4>
                        <p class="text-muted">तपाईंले अहिले सम्म कुनै होस्टेल बुक गर्नुभएको छैन।</p>
                        <a href="{{ route('student.bookings.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> पहिलो बुकिंग गर्नुहोस्
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Force all button text and icons to be white in every state -->
<style>
.container-fluid .btn,
.container-fluid .btn *,
.container-fluid .btn:hover,
.container-fluid .btn:hover *,
.container-fluid .btn:focus,
.container-fluid .btn:focus *,
.container-fluid .btn:active,
.container-fluid .btn:active *,
.container-fluid .btn:visited,
.container-fluid .btn:visited * {
    color: #ffffff !important;
}
</style>
@endsection