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
                        Here you can update the availability status of rooms in this hostel.
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Rooms</h5>
                                    <h3 class="text-primary">{{ $hostel->rooms->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Available</h5>
                                    <h3>{{ $hostel->rooms->where('status', 'available')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Occupied</h5>
                                    <h3>{{ $hostel->rooms->where('status', 'occupied')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.hostels.availability.update', $hostel) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="bulk_action" class="form-label">Bulk Action:</label>
                                <select class="form-select" id="bulk_action">
                                    <option value="">Select Action</option>
                                    <option value="available">Mark All as Available</option>
                                    <option value="occupied">Mark All as Occupied</option>
                                    <option value="maintenance">Mark All as Maintenance</option>
                                </select>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary" id="apply_bulk_action">
                                    <i class="fas fa-check me-1"></i> Apply to All
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="roomsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Room #</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Current Occupants</th>
                                        <th>Current Status</th>
                                        <th>New Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hostel->rooms as $room)
                                    <tr>
                                        <td>{{ $room->room_number }}</td>
                                        <td>{{ ucfirst($room->type) }}</td>
                                        <td>{{ $room->capacity }} beds</td>
                                        <td>{{ $room->students_count }} / {{ $room->capacity }}</td>
                                        <td>
                                            <span class="badge {{ $room->status === 'available' ? 'bg-success' : ($room->status === 'occupied' ? 'bg-danger' : 'bg-warning') }}">
                                                {{ ucfirst($room->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <select name="rooms[{{ $room->id }}][status]" class="form-select room-status">
                                                <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Available</option>
                                                <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                                                <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="rooms[{{ $room->id }}][notes]" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Optional notes"
                                                   value="{{ old('rooms.'.$room->id.'.notes') }}">
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
                                <i class="fas fa-save me-1"></i> Update Room Statuses
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
        // Bulk action functionality
        const bulkAction = document.getElementById('bulk_action');
        const applyBulkAction = document.getElementById('apply_bulk_action');
        const statusSelects = document.querySelectorAll('.room-status');

        if (applyBulkAction && bulkAction) {
            applyBulkAction.addEventListener('click', function() {
                const selectedValue = bulkAction.value;
                if (!selectedValue) {
                    alert('Please select a bulk action first.');
                    return;
                }
                
                if (confirm('Are you sure you want to apply this status to all rooms?')) {
                    statusSelects.forEach(select => {
                        select.value = selectedValue;
                    });
                }
            });
        }

        // Add confirmation for occupied status
        statusSelects.forEach(select => {
            select.addEventListener('change', function() {
                if (this.value === 'occupied') {
                    const roomNumber = this.closest('tr').querySelector('td:first-child').textContent;
                    if (!confirm(`Mark room ${roomNumber} as occupied? This will prevent new bookings.`)) {
                        this.value = 'available';
                    }
                }
            });
        });
    });
</script>
@endpush