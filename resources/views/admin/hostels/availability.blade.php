@extends('layouts.admin')

@section('title', 'Manage Room Availability: ' . $hostel->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.hostels.show', $hostel) }}" class="btn btn-outline-primary mb-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Hostel Details
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Manage Room Availability for {{ $hostel->name }}</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Here you can update the availability status of all rooms in this hostel at once.
                    </div>

                    <form action="{{ route('admin.hostels.availability', $hostel) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover" id="roomsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Room #</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Current Status</th>
                                        <th>New Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hostel->rooms as $room)
                                    <tr>
                                        <td>{{ $room->room_number }}</td>
                                        <td>{{ ucfirst($room->type) }}</td>
                                        <td>{{ $room->capacity }} beds</td>
                                        <td>
                                            <span class="badge {{ $room->status === 'available' ? 'bg-success' : ($room->status === 'occupied' ? 'bg-danger' : 'bg-warning') }}">
                                                {{ ucfirst($room->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <select name="rooms[{{ $room->id }}]" class="form-select">
                                                <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Available</option>
                                                <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                                                <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('admin.hostels.show', $hostel) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update All Room Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add bulk action functionality
        const selectAll = document.getElementById('selectAll');
        const statusSelects = document.querySelectorAll('select[name^="rooms["]');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const selectedValue = this.value;
                statusSelects.forEach(select => {
                    select.value = selectedValue;
                });
            });
        }
    });
</script>
@endpush
