@extends('layouts.app')

@section('title', 'कोठा विवरण')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">कोठा विवरण</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%;">कोठा नम्बर:</th>
                                    <td>{{ $room->room_number }}</td>
                                </tr>
                                @role('admin')
                                <tr>
                                    <th>होस्टल:</th>
                                    <td>{{ $room->hostel->name }}</td>
                                </tr>
                                @endrole
                                <tr>
                                    <th>प्रकार:</th>
                                    <td>
                                        @if($room->type == 'single')
                                            एकल
                                        @elseif($room->type == 'double')
                                            डबल
                                        @else
                                            साझा
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>क्षमता:</th>
                                    <td>{{ $room->capacity }} जना</td>
                                </tr>
                                <tr>
                                    <th>मूल्य:</th>
                                    <td>रु. {{ number_format($room->price, 2) }}</td>
                                </tr>
                                @role('admin')
                                <tr>
                                    <th>तल्ला:</th>
                                    <td>{{ $room->floor ?? 'N/A' }}</td>
                                </tr>
                                @endrole
                                <tr>
                                    <th>स्थिति:</th>
                                    <td>
                                        @if($room->status == 'available' || $room->status == 'उपलब्ध')
                                            <span class="badge bg-success">उपलब्ध</span>
                                        @elseif($room->status == 'occupied' || $room->status == 'बुक भएको')
                                            <span class="badge bg-danger">व्यस्त</span>
                                        @else
                                            <span class="badge bg-warning">मर्मत सम्भार</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            @role('admin')
                            @if($room->image)
                                <div class="text-center mb-3">
                                    <img src="{{ asset('storage/' . $room->image) }}" alt="कोठाको तस्वीर" class="img-fluid" style="max-height: 200px;">
                                </div>
                            @endif
                            @endrole
                            
                            <h5>विवरण:</h5>
                            <p>{{ $room->description ?? 'कुनै विवरण उपलब्ध छैन' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    @role('admin|owner')
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                    </a>
                    @endrole
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> कोठा सूचीमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection