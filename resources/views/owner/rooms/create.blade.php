@extends('layouts.owner')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">नयाँ कोठा थप्नुहोस्</h1>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>पछाडि फर्कनुहोस्
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">कोठा विवरण</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.rooms.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hostel_id" class="form-label">होस्टल</label>
                            <select name="hostel_id" id="hostel_id" class="form-select @error('hostel_id') is-invalid @enderror" required>
                                <option value="">होस्टल छान्नुहोस्</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                        {{ $hostel->name }} ({{ $hostel->location }})
                                    </option>
                                @endforeach
                            </select>
                            @error('hostel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="room_number" class="form-label">कोठा नम्बर</label>
                            <input type="text" name="room_number" id="room_number" class="form-control @error('room_number') is-invalid @enderror"
                                   value="{{ old('room_number') }}" required>
                            @error('room_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_type" class="form-label">कोठा प्रकार</label>
                            <select name="room_type" id="room_type" class="form-select @error('room_type') is-invalid @enderror" required>
                                <option value="single" {{ old('room_type') == 'single' ? 'selected' : '' }}>एकल कोठा</option>
                                <option value="double" {{ old('room_type') == 'double' ? 'selected' : '' }}>दुई ब्यक्ति कोठा</option>
                                <option value="triple" {{ old('room_type') == 'triple' ? 'selected' : '' }}>तीन ब्यक्ति कोठा</option>
                                <option value="quad" {{ old('room_type') == 'quad' ? 'selected' : '' }}>चार ब्यक्ति कोठा</option>
                                <option value="suite" {{ old('room_type') == 'suite' ? 'selected' : '' }}>सुट</option>
                            </select>
                            @error('room_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">क्षमता</label>
                            <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror"
                                   value="{{ old('capacity', 1) }}" min="1" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">मूल्य (प्रति महिना)</label>
                            <div class="input-group">
                                <span class="input-group-text">रु.</span>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}" min="0" step="0.01" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">स्थिति</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>उपलब्ध</option>
                                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>व्यस्त</option>
                                <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>आरक्षित</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>मर्मत सम्भार</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">वर्णन</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary">रीसेट</button>
                        <button type="submit" class="btn btn-primary">कोठा थप्नुहोस्</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
