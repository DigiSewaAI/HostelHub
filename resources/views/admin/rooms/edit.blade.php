@extends('layouts.admin')

@section('शीर्षक', 'कोठा सम्पादन गर्नुहोस्')

@section('विस्तार')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">कोठा सम्पादन गर्नुहोस्</h3>
                </div>

                <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
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
                                        <option value="स्ट्यान्डर्ड" {{ $room->type == 'स्ट्यान्डर्ड' ? 'selected' : '' }}>स्ट्यान्डर्ड</option>
                                        <option value="डीलक्स" {{ $room->type == 'डीलक्स' ? 'selected' : '' }}>डीलक्स</option>
                                        <option value="विआईपी" {{ $room->type == 'विआईपी' ? 'selected' : '' }}>विआईपी</option>
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
                                        <option value="उपलब्ध" {{ $room->status == 'उपलब्ध' ? 'selected' : '' }}>उपलब्ध</option>
                                        <option value="बुक भएको" {{ $room->status == 'बुक भएको' ? 'selected' : '' }}>बुक भएको</option>
                                        <option value="रिङ्गोट" {{ $room->status == 'रिङ्गोट' ? 'selected' : '' }}>रिङ्गोट</option>
                                    </select>
                                </div>
                            </div>
                        </div>

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
