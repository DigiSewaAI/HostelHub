@extends('layouts.admin')

@section('शीर्षक', 'कोठा विवरण')

@section('विस्तार')
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
                                <tr>
                                    <th>होस्टल:</th>
                                    <td>{{ $room->hostel->name }}</td>
                                </tr>
                                <tr>
                                    <th>प्रकार:</th>
                                    <td>{{ $room->type }}</td>
                                </tr>
                                <tr>
                                    <th>क्षमता:</th>
                                    <td>{{ $room->capacity }} जना</td>
                                </tr>
                                <tr>
                                    <th>मूल्य:</th>
                                    <td>रु. {{ number_format($room->price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>तल्ला:</th>
                                    <td>{{ $room->floor ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>स्थिति:</th>
                                    <td>
                                        @if($room->status == 'उपलब्ध')
                                            <span class="badge badge-success">उपलब्ध</span>
                                        @elseif($room->status == 'बुक भएको')
                                            <span class="badge badge-danger">बुक भएको</span>
                                        @else
                                            <span class="badge badge-warning">रिङ्गोट</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            @if($room->image)
                                <div class="text-center mb-3">
                                    <img src="{{ asset('storage/' . $room->image) }}" alt="कोठाको तस्वीर" class="img-fluid" style="max-height: 200px;">
                                </div>
                            @endif
                            
                            <h5>विवरण:</h5>
                            <p>{{ $room->description ?? 'कुनै विवरण उपलब्ध छैन' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                    </a>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> कोठा सूचीमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
