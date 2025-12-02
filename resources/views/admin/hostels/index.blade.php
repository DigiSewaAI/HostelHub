@extends('layouts.admin')

@section('title', 'होस्टल व्यवस्थापन')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">होस्टल व्यवस्थापन</h2>
                            <p class="text-muted mb-0">सबै होस्टलहरूको व्यापक व्यवस्थापन</p>
                        </div>
                        <a href="{{ route('admin.hostels.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>नयाँ होस्टल
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('bulk_errors'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>त्रुटिहरू:</strong>
            <ul class="mb-0 mt-2">
                @foreach(session('bulk_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल होस्टलहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalHostels }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                सक्रिय होस्टलहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeHostels }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                प्रकाशित होस्टलहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $publishedHostels }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                संस्थाहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $organizationsWithHostels }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filter and Search Section -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>उन्नत खोज र छनौट
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.hostels.index') }}" method="GET" class="row g-3" id="searchForm">
                <div class="col-md-3">
                    <label for="search" class="form-label">खोज शब्द</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="नाम, ठेगाना, शहर, सम्पर्क..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">स्थिति</label>
                    <select name="status" class="form-select">
                        <option value="">सबै स्थिति</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>सक्रिय</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>निष्क्रिय</option>
                        <option value="under_maintenance" {{ request('status') == 'under_maintenance' ? 'selected' : '' }}>मर्मतमा</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="publish_status" class="form-label">प्रकाशन</label>
                    <select name="publish_status" class="form-select">
                        <option value="">सबै प्रकाशन</option>
                        <option value="published" {{ request('publish_status') == 'published' ? 'selected' : '' }}>प्रकाशित</option>
                        <option value="unpublished" {{ request('publish_status') == 'unpublished' ? 'selected' : '' }}>अप्रकाशित</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="organization_id" class="form-label">संस्था</label>
                    <select name="organization_id" class="form-select">
                        <option value="">सबै संस्थाहरू</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}" {{ request('organization_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="sort_by" class="form-label">क्रमबद्ध गर्नुहोस्</label>
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>सिर्जना मिति</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>नाम</option>
                        <option value="total_rooms" {{ request('sort_by') == 'total_rooms' ? 'selected' : '' }}>कोठा संख्या</option>
                    </select>
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>खोज्नुहोस्
                        </button>
                        <a href="{{ route('admin.hostels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh me-1"></i>रिसेट गर्नुहोस्
                        </a>
                        <button type="button" class="btn btn-outline-primary ms-auto" id="exportBtn">
                            <i class="fas fa-download me-1"></i>निर्यात गर्नुहोस्
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions and Main Content -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>होस्टल सूची
                </h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="bulk-actions d-none" id="bulkActionsPanel">
                        <select class="form-select form-select-sm me-2" id="bulkActionSelect" style="width: auto;">
                            <option value="">बल्क कार्य छान्नुहोस्</option>
                            <option value="publish">प्रकाशित गर्नुहोस्</option>
                            <option value="unpublish">अप्रकाशित गर्नुहोस्</option>
                            <option value="activate">सक्रिय गर्नुहोस्</option>
                            <option value="deactivate">निष्क्रिय गर्नुहोस्</option>
                            <option value="feature">फिचर गर्नुहोस्</option>
                            <option value="unfeature">फिचर हटाउनुहोस्</option>
                            <option value="delete">मेटाउनुहोस्</option>
                        </select>
                        <button class="btn btn-sm btn-primary" id="applyBulkAction">
                            <i class="fas fa-play me-1"></i>लागू गर्नुहोस्
                        </button>
                        <button class="btn btn-sm btn-secondary" id="cancelBulkAction">
                            <i class="fas fa-times me-1"></i>रद्द गर्नुहोस्
                        </button>
                    </div>
                    <span class="badge bg-primary">कुल: {{ $hostels->total() }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($hostels->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="hostelsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="30">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th width="5%">#</th>
                            <th width="20%">होस्टेलको नाम</th>
                            <th width="15%">ठेगाना</th>
                            <th width="10%">शहर</th>
                            <th width="10%">सम्पर्क</th>
                            <th width="10%">कोठाहरू</th>
                            <th width="10%">प्रबन्धक</th>
                            <th width="10%">स्थिति</th>
                            <th width="10%">प्रकाशन</th>
                            <th width="10%">कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hostels as $hostel)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input hostel-checkbox" type="checkbox" 
                                           value="{{ $hostel->id }}">
                                </div>
                            </td>
                            <td>{{ $loop->iteration + ($hostels->currentPage() - 1) * $hostels->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($hostel->image)
                                        <img src="{{ asset('storage/'.$hostel->image) }}"
                                             class="rounded me-3"
                                             width="50"
                                             height="50"
                                             style="object-fit: cover;"
                                             onerror="this.src='{{ asset('images/default-hostel.jpg') }}'">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center me-3"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-building text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-primary">{{ $hostel->name }}</div>
                                        @if($hostel->organization)
                                            <small class="text-muted">
                                                <i class="fas fa-university me-1"></i>
                                                {{ $hostel->organization->name }}
                                            </small>
                                        @endif
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $hostel->created_at->format('Y-m-d') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="d-block text-truncate" style="max-width: 200px;" 
                                      title="{{ $hostel->address }}">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                    {{ $hostel->address }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-city me-1"></i>
                                    {{ $hostel->city }}
                                </span>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="fw-semibold">{{ $hostel->contact_phone }}</div>
                                    @if($hostel->contact_person)
                                        <small class="text-muted">{{ $hostel->contact_person }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="room-stats">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-door-closed me-1"></i>
                                            {{ $hostel->rooms_count ?? 0 }}
                                        </span>
                                        <span class="badge bg-success">
                                            <i class="fas fa-bed me-1"></i>
                                            {{ $hostel->available_rooms ?? 0 }}
                                        </span>
                                    </div>
                                    @php
                                        $occupancyRate = $hostel->rooms_count > 0 ? 
                                            (($hostel->rooms_count - $hostel->available_rooms) / $hostel->rooms_count) * 100 : 0;
                                    @endphp
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar {{ $occupancyRate > 80 ? 'bg-danger' : ($occupancyRate > 50 ? 'bg-warning' : 'bg-success') }}" 
                                             style="width: {{ $occupancyRate }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ round($occupancyRate) }}% अधिभृत</small>
                                </div>
                            </td>
                            <td>
                                @if($hostel->owner)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                             style="width: 32px; height: 32px;">
                                            <span class="text-white fw-bold">
                                                {{ substr($hostel->owner->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $hostel->owner->name }}</div>
                                            <small class="text-muted">{{ $hostel->owner->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-user-slash me-1"></i>तोकिएको छैन
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $hostel->status === 'active' ? 'bg-success' : ($hostel->status === 'inactive' ? 'bg-secondary' : 'bg-warning') }}">
                                    @if($hostel->status === 'active')
                                        <i class="fas fa-check-circle me-1"></i>सक्रिय
                                    @elseif($hostel->status === 'inactive')
                                        <i class="fas fa-pause-circle me-1"></i>निष्क्रिय
                                    @else
                                        <i class="fas fa-tools me-1"></i>मर्मतमा
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
    @if($hostel->is_published)
        <span class="badge bg-success mb-2 d-block">प्रकाशित</span>
        <form action="{{ url('/admin/hostels/' . $hostel->id . '/unpublish') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm" 
                    onclick="return confirm('यो होस्टल अप्रकाशित गर्नुहुन्छ?')">
                अप्रकाशित
            </button>
        </form>
    @else
        <span class="badge bg-secondary mb-2 d-block">अप्रकाशित</span>
        <form action="{{ url('/admin/hostels/' . $hostel->id . '/publish') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success btn-sm"
                    onclick="return confirm('यो होस्टल प्रकाशित गर्नुहुन्छ?')">
                प्रकाशित
            </button>
        </form>
    @endif
</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.hostels.show', $hostel) }}"
                                       class="btn btn-sm btn-info"
                                       title="विवरण हेर्नुहोस्"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.hostels.edit', $hostel) }}"
                                       class="btn btn-sm btn-warning"
                                       title="सम्पादन गर्नुहोस्"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Live View Button -->
                                    @if($hostel->is_published && $hostel->slug)
                                        <a href="{{ route('hostels.show', $hostel->slug) }}" 
                                           target="_blank"
                                           class="btn btn-sm btn-primary"
                                           title="लाइव पृष्ठ हेर्नुहोस्"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.hostels.destroy', $hostel) }}"
                                          method="POST"
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="हटाउनुहोस्"
                                                data-bs-toggle="tooltip"
                                                onclick="return confirm('के तपाइँ यो होस्टल मेटाउन निश्चित हुनुहुन्छ? सबै कोठाहरू र सम्बन्धित डाटा हटाइनेछ।')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination and Summary -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    देखाइएको: {{ $hostels->firstItem() }} - {{ $hostels->lastItem() }} of {{ $hostels->total() }} होस्टलहरू
                </div>
                <div>
                    {{ $hostels->links() }}
                </div>
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-building text-muted" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-muted">कुनै होस्टल फेला परेन</h5>
                <p class="text-muted mb-4">तपाईंको खोजका लागि कुनै होस्टल मेल खाँदैन वा अझै सम्म कुनै होस्टल थपिएको छैन।</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('admin.hostels.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-refresh me-2"></i>सबै होस्टल हेर्नुहोस्
                    </a>
                    <a href="{{ route('admin.hostels.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>नयाँ होस्टल सिर्जना गर्नुहोस्
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    font-size: 0.875rem;
}
.room-stats .progress {
    background-color: #e9ecef;
}
.empty-state-icon {
    opacity: 0.5;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* 🔥 CRITICAL LAYOUT FIXES - ADDED FOR RIGHT PART VISIBILITY */
.table-responsive {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch !important;
}

#hostelsTable {
    min-width: 1200px !important;
    width: 100% !important;
}

.card-body {
    padding: 1.5rem !important;
}

#main-content {
    padding: 1rem !important;
    margin: 0 !important;
    width: 100% !important;
    max-width: none !important;
}

.container-fluid {
    padding-right: 1rem !important;
    padding-left: 1rem !important;
    margin-right: auto !important;
    margin-left: auto !important;
    width: 100% !important;
}

.bulk-actions {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 0.5rem;
    border: 1px solid #e3e6f0;
}

.btn-group {
    flex-wrap: nowrap !important;
}

.row.mb-4 {
    margin-right: -0.75rem !important;
    margin-left: -0.75rem !important;
}

.row.mb-4 > [class*="col-"] {
    padding-right: 0.75rem !important;
    padding-left: 0.75rem !important;
}

.card {
    margin-bottom: 1.5rem !important;
}

#searchForm .row {
    margin-right: -0.5rem !important;
    margin-left: -0.5rem !important;
}

#searchForm .row > [class*="col-"] {
    padding-right: 0.5rem !important;
    padding-left: 0.5rem !important;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .container-fluid {
        padding-right: 0.5rem !important;
        padding-left: 0.5rem !important;
    }
    
    #hostelsTable {
        min-width: 1000px !important;
    }
    
    .btn-group {
        flex-wrap: wrap !important;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // 🔥 FIXED: Bulk selection functionality - COMPLETELY UPDATED
    const selectAll = document.getElementById('selectAll');
    const hostelCheckboxes = document.querySelectorAll('.hostel-checkbox');
    const bulkActionsPanel = document.getElementById('bulkActionsPanel');
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    const applyBulkAction = document.getElementById('applyBulkAction');
    const cancelBulkAction = document.getElementById('cancelBulkAction');

    // Select All functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const isChecked = this.checked;
            hostelCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            toggleBulkActionsPanel();
        });
    }

    // Individual checkbox functionality
    hostelCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActionsPanel);
    });

    // Toggle bulk actions panel
    function toggleBulkActionsPanel() {
        const checkedCount = document.querySelectorAll('.hostel-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkActionsPanel.classList.remove('d-none');
            if (selectAll) {
                selectAll.checked = checkedCount === hostelCheckboxes.length;
            }
        } else {
            bulkActionsPanel.classList.add('d-none');
            if (selectAll) {
                selectAll.checked = false;
            }
        }
    }

    // 🔥 SIMPLE BULK ACTION FIX - NO AJAX, SIMPLE FORM SUBMISSION
    if (applyBulkAction) {
        applyBulkAction.addEventListener('click', function() {
            const action = bulkActionSelect.value;
            if (!action) {
                alert('कृपया बल्क कार्य छान्नुहोस्');
                return;
            }

            const selectedIds = Array.from(document.querySelectorAll('.hostel-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('कुनै होस्टल चयन गरिएको छैन');
                return;
            }

            // Confirmation messages
            let confirmMessage = '';
            switch(action) {
                case 'publish':
                    confirmMessage = `के तपाइँ निश्चित हुनुहुन्छ कि यी ${selectedIds.length} होस्टलहरू प्रकाशित गर्न चाहनुहुन्छ?`;
                    break;
                case 'unpublish':
                    confirmMessage = `के तपाइँ निश्चित हुनुहुन्छ कि यी ${selectedIds.length} होस्टलहरू अप्रकाशित गर्न चाहनुहुन्छ?`;
                    break;
                case 'activate':
                    confirmMessage = `के तपाइँ निश्चित हुनुहुन्छ कि यी ${selectedIds.length} होस्टलहरू सक्रिय गर्न चाहनुहुन्छ?`;
                    break;
                case 'deactivate':
                    confirmMessage = `के तपाइँ निश्चित हुनुहुन्छ कि यी ${selectedIds.length} होस्टलहरू निष्क्रिय गर्न चाहनुहुन्छ?`;
                    break;
                case 'feature':
                    confirmMessage = `के तपाइँ निश्चित हुनुहुन्छ कि यी ${selectedIds.length} होस्टलहरू फिचर गर्न चाहनुहुन्छ?`;
                    break;
                case 'unfeature':
                    confirmMessage = `के तपाइँ निश्चित हुनुहुन्छ कि यी ${selectedIds.length} होस्टलहरू फिचर हटाउन चाहनुहुन्छ?`;
                    break;
                case 'delete':
                    confirmMessage = `के तपाइँ निश्चित हुनुहुन्छ कि यी ${selectedIds.length} होस्टलहरू मेटाउन चाहनुहुन्छ? सबै कोठाहरू र सम्बन्धित डाटा हटाइनेछ।`;
                    break;
            }

            if (!confirm(confirmMessage)) {
                return;
            }

            // Create a form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.hostels.bulk-operations") }}';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add action
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);
            
            // Add hostel_ids as hidden inputs
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'hostel_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            // Add form to body and submit
            document.body.appendChild(form);
            form.submit();
        });
    }

    // Cancel bulk action
    if (cancelBulkAction) {
        cancelBulkAction.addEventListener('click', function() {
            hostelCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            toggleBulkActionsPanel();
            if (bulkActionSelect) {
                bulkActionSelect.value = '';
            }
        });
    }

    // Export functionality
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            const searchParams = new URLSearchParams(window.location.search);
            window.open('{{ route("admin.hostels.index") }}?export=1&' + searchParams.toString(), '_blank');
        });
    }

    // Auto-submit form on filter change (optional)
    const filterSelects = document.querySelectorAll('select[name="status"], select[name="publish_status"], select[name="organization_id"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    });
});
</script>
@endpush