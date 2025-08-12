@extends('layouts.app')

@section('content')
<div class="container">
    <h2>मेरो बुकिङहरू</h2>

    @if($bookings->isEmpty())
        <div class="alert alert-info">
            तपाईंसँग कुनै बुकिङ छैन।
        </div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>होस्टल</th>
                    <th>कोठा</th>
                    <th>चेक-इन</th>
                    <th>चेक-आउट</th>
                    <th>स्थिति</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->hostel->name }}</td>
                    <td>{{ $booking->room->room_number }}</td>
                    <td>{{ $booking->check_in_date->format('Y-m-d') }}</td>
                    <td>{{ $booking->check_out_date->format('Y-m-d') }}</td>
                    <td>{{ $booking->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
