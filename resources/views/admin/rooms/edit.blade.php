@extends('layouts.app')

@section('title', 'कोठा सम्पादन गर्नुहोस्')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">कोठा सम्पादन गर्नुहोस्</h3>
                </div>

                <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            @role('admin')
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hostel_id">होस्टल</label>
                                    <select class="form-control" id="hostel_id" name="hostel_id" required>
                                        <option value="">होस्टल छान्नुहोस्</option>
                                        @foreach($hostels as $hostel)
                                            <option value="{{ $hostel->id }}" {{ $room->hostel_id == $hostel->id ? 'selected' : '' }}>
                                                {{ $hostel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="hostel_id" value="{{ $room->hostel_id }}">
                            @endrole
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_number">कोठा नम्बर</label>
                                    <input type="text" class="form-control" id="room_number" name="room_number" value="{{ $room->room_number }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">कोठाको प्रकार</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="single" {{ $room->type == 'single' ? 'selected' : '' }}>एकल</option>
                                        <option value="double" {{ $room->type == 'double' ? 'selected' : '' }}>डबल</option>
                                        <option value="shared" {{ $room->type == 'shared' ? 'selected' : '' }}>साझा</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="capacity">क्षमता (व्यक्ति संख्या)</label>
                                    <input type="number" class="form-control" id="capacity" name="capacity" value="{{ $room->capacity }}" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">मूल्य (प्रतिमहिना)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">रु.</span>
                                        <input type="number" class="form-control" id="price" name="price" value="{{ $room->price }}" min="0" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">हालको स्थिति</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>उपलब्ध</option>
                                        <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>व्यस्त</option>
                                        <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>मर्मत सम्भार</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @role('admin')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="floor">तल्ला</label>
                                    <input type="text" class="form-control" id="floor" name="floor" value="{{ $room->floor }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">कोठाको तस्वीर</label>
                                    <input type="file" class="form-control-file" id="image" name="image">
                                    @if($room->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $room->image) }}" alt="कोठाको तस्वीर" style="max-width: 200px;">
                                            <p class="text-muted">हालको तस्वीर</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endrole

                        <div class="form-group">
                            <label for="description">कोठाको विवरण</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $room->description }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> परिवर्तनहरू सुरक्षित गर्नुहोस्
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i> रद्द गर्नुहोस्
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection