@extends('layouts.admin')

@section('title', 'कोठा उपलब्धता व्यवस्थापन: ' . $hostel->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.hostels.show', $hostel) }}" class="btn btn-outline-primary mb-3">
                <i class="fas fa-arrow-left me-1"></i> होस्टल विवरणमा फर्कनुहोस्
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $hostel->name }} को लागि कोठा उपलब्धता व्यवस्थापन</h6>
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
                        यहाँ तपाइँ यस होस्टलका कोठाहरूको उपलब्धता स्थिति अद्यावधिक गर्न सक्नुहुन्छ।
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">कुल कोठाहरू</h5>
                                    <h3 class="text-primary">{{ $hostel->rooms->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">उपलब्ध</h5>
                                    <h3>{{ $hostel->rooms->where('status', 'available')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">अधिभृत</h5>
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
                                <label for="bulk_action" class="form-label">समूह कार्य:</label>
                                <select class="form-select" id="bulk_action">
                                    <option value="">कार्य छान्नुहोस्</option>
                                    <option value="available">सबै उपलब्ध रूपमा चिन्ह लगाउनुहोस्</option>
                                    <option value="occupied">सबै अधिभृत रूपमा चिन्ह लगाउनुहोस्</option>
                                    <option value="maintenance">सबै मर्मतमा रूपमा चिन्ह लगाउनुहोस्</option>
                                </select>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary" id="apply_bulk_action">
                                    <i class="fas fa-check me-1"></i> सबैमा लागू गर्नुहोस्
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="roomsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>कोठा नं.</th>
                                        <th>प्रकार</th>
                                        <th>क्षमता</th>
                                        <th>हालका बासिन्दाहरू</th>
                                        <th>हालको स्थिति</th>
                                        <th>नयाँ स्थिति</th>
                                        <th>नोटहरू</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hostel->rooms as $room)
                                    <tr>
                                        <td>{{ $room->room_number }}</td>
                                        <td>
                                            @if($room->type === 'single')
                                                एकल
                                            @elseif($room->type === 'double')
                                                दोहोरो
                                            @else
                                                साझा
                                            @endif
                                        </td>
                                        <td>{{ $room->capacity }} ओठ</td>
                                        <td>{{ $room->students_count }} / {{ $room->capacity }}</td>
                                        <td>
                                            <span class="badge {{ $room->status === 'available' ? 'bg-success' : ($room->status === 'occupied' ? 'bg-danger' : 'bg-warning') }}">
                                                @if($room->status === 'available')
                                                    उपलब्ध
                                                @elseif($room->status === 'occupied')
                                                    अधिभृत
                                                @else
                                                    मर्मतमा
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <select name="rooms[{{ $room->id }}][status]" class="form-select room-status">
                                                <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>उपलब्ध</option>
                                                <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>अधिभृत</option>
                                                <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>मर्मतमा</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="rooms[{{ $room->id }}][notes]" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="वैकल्पिक नोटहरू"
                                                   value="{{ old('rooms.'.$room->id.'.notes') }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('admin.hostels.show', $hostel) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> रद्द गर्नुहोस्
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> कोठा स्थितिहरू अद्यावधिक गर्नुहोस्
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
                    alert('कृपया पहिले एउटा समूह कार्य छान्नुहोस्।');
                    return;
                }
                
                if (confirm('के तपाइँ निश्चित हुनुहुन्छ कि यो स्थिति सबै कोठाहरूमा लागू गर्न चाहनुहुन्छ?')) {
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
                    if (!confirm(`कोठा ${roomNumber} लाई अधिभृत रूपमा चिन्ह लगाउने? यसले नयाँ बुकिङहरू रोक्नेछ।`)) {
                        this.value = 'available';
                    }
                }
            });
        });
    });
</script>
@endpush