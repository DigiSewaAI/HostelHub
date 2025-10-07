@extends('layouts.student')

@section('title', 'नयाँ बुकिंग')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">नयाँ बुकिंग</h1>
        <a href="{{ route('student.bookings.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> बुकिंगहरूमा फर्कनुहोस्
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">बुकिंग विवरण</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.bookings.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="hostel_id">होस्टेल रोज्नुहोस्</label>
                            <select class="form-control @error('hostel_id') is-invalid @enderror" id="hostel_id" name="hostel_id" required>
                                <option value="">होस्टेल रोज्नुहोस्</option>
                                @foreach($hostels as $hostel)
                                <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                    {{ $hostel->name }} - {{ $hostel->location }}
                                </option>
                                @endforeach
                            </select>
                            @error('hostel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="room_id">कोठा रोज्नुहोस्</label>
                            <select class="form-control @error('room_id') is-invalid @enderror" id="room_id" name="room_id" required disabled>
                                <option value="">पहिले होस्टेल रोज्नुहोस्</option>
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_in_date">चेक-इन मिति</label>
                                    <input type="date" class="form-control @error('check_in_date') is-invalid @enderror" 
                                           id="check_in_date" name="check_in_date" value="{{ old('check_in_date') }}" required>
                                    @error('check_in_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_out_date">चेक-आउट मिति</label>
                                    <input type="date" class="form-control @error('check_out_date') is-invalid @enderror" 
                                           id="check_out_date" name="check_out_date" value="{{ old('check_out_date') }}" required>
                                    @error('check_out_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="purpose">बुकिंगको उद्देश्य</label>
                            <textarea class="form-control @error('purpose') is-invalid @enderror" id="purpose" 
                                      name="purpose" rows="3" required>{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> बुकिंग पेश गर्नुहोस्
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">बुकिंग निर्देशन</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> महत्त्वपूर्ण जानकारी</h6>
                        <ul class="mb-0 pl-3">
                            <li>बुकिंग स्वीकृत हुन २४ घण्टा लाग्न सक्छ</li>
                            <li>सबै आवश्यक कागजातहरू तयार गर्नुहोस्</li>
                            <li>भुक्तानी बुकिंग स्वीकृत पछि मात्र गर्नुपर्छ</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('hostel_id').addEventListener('change', function() {
    const hostelId = this.value;
    const roomSelect = document.getElementById('room_id');
    
    if (hostelId) {
        roomSelect.disabled = false;
        // AJAX call to get available rooms
        fetch(`/api/hostels/${hostelId}/available-rooms`)
            .then(response => response.json())
            .then(data => {
                roomSelect.innerHTML = '<option value="">कोठा रोज्नुहोस्</option>';
                data.rooms.forEach(room => {
                    roomSelect.innerHTML += `<option value="${room.id}">${room.room_number} - रु ${room.price_per_semester}</option>`;
                });
            });
    } else {
        roomSelect.disabled = true;
        roomSelect.innerHTML = '<option value="">पहिले होस्टेल रोज्नुहोस्</option>';
    }
});
</script>
@endpush