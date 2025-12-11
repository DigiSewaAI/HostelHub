@extends('layouts.owner')

@section('title', 'सम्पर्क सन्देशहरू')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-envelope me-2"></i>सम्पर्क सन्देशहरू
                </h1>
                <a href="{{ route('owner.contacts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>नयाँ सन्देश
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                नपढिएका सन्देश</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unreadCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                आजका सन्देश</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                जवाफ दिइएका</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $repliedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-reply fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                कुल सन्देश</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $contacts->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-inbox fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route('owner.contacts.index') }}" method="GET" class="d-flex">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="सन्देश खोज्नुहोस्..." 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="btn-group float-end">
                                <a href="{{ route('owner.contacts.index') }}" 
                                   class="btn btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">
                                    सबै
                                </a>
                                <a href="{{ route('owner.contacts.index', ['filter' => 'unread']) }}" 
                                   class="btn btn-outline-warning {{ request('filter') == 'unread' ? 'active' : '' }}">
                                    <i class="fas fa-envelope me-1"></i>नपढिएका
                                </a>
                                <a href="{{ route('owner.contacts.index', ['filter' => 'today']) }}" 
                                   class="btn btn-outline-info {{ request('filter') == 'today' ? 'active' : '' }}">
                                    <i class="fas fa-calendar-day me-1"></i>आज
                                </a>
                                <a href="{{ route('owner.contacts.index', ['filter' => 'read']) }}" 
                                   class="btn btn-outline-success {{ request('filter') == 'read' ? 'active' : '' }}">
                                    <i class="fas fa-check me-1"></i>पढिएका
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    @if($contacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>नाम</th>
                                        <th>इमेल</th>
                                        <th>विषय</th>
                                        <th>होस्टल</th>
                                        <th>स्थिति</th>
                                        <th>मिति</th>
                                        <th class="text-end">कार्यहरू</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contacts as $contact)
                                        <tr class="{{ !$contact->is_read ? 'table-warning' : '' }}">
                                            <td>
                                                <input type="checkbox" name="contact_ids[]" 
                                                       value="{{ $contact->id }}" class="form-check-input contact-checkbox">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(!$contact->is_read)
                                                        <span class="badge bg-warning me-2">नयाँ</span>
                                                    @endif
                                                    {{ $contact->name }}
                                                </div>
                                            </td>
                                            <td>{{ $contact->email }}</td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;">
                                                    {{ $contact->subject }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $contact->hostel->name ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'warning',
                                                        'read' => 'success',
                                                        'replied' => 'info'
                                                    ][$contact->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ $contact->status }}
                                                </span>
                                            </td>
                                            <td>{{ $contact->created_at->format('Y-m-d H:i') }}</td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <a href="{{ route('owner.contacts.show', $contact) }}" 
                                                       class="btn btn-sm btn-info" title="हेर्नुहोस्">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(!$contact->is_read)
                                                        <form action="{{ route('owner.contacts.mark-read', $contact) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" 
                                                                    title="पढिएको चिन्ह लगाउनुहोस्">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('owner.contacts.destroy', $contact) }}" 
                                                          method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('के तपाईं यो सन्देश मेटाउन चाहनुहुन्छ?')"
                                                                title="मेटाउनुहोस्">
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

                        <!-- Bulk Actions -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <select id="bulkAction" class="form-select form-select-sm d-inline w-auto me-2">
                                            <option value="">बल्क कार्य चयन गर्नुहोस्</option>
                                            <option value="mark-read">पढिएको चिन्ह लगाउनुहोस्</option>
                                            <option value="mark-unread">नपढिएको चिन्ह लगाउनुहोस्</option>
                                            <option value="delete">मेटाउनुहोस्</option>
                                        </select>
                                        <button id="applyBulkAction" class="btn btn-sm btn-primary">
                                            लागू गर्नुहोस्
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('owner.contacts.export-csv') }}" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-file-export me-1"></i> CSV एक्सपोर्ट
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                {{ $contacts->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">कुनै सन्देश छैन</h5>
                            <p class="text-muted">अहिलेसम्म कुनै सम्पर्क सन्देश प्राप्त भएको छैन।</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAll = document.getElementById('selectAll');
    const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            contactCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Bulk Actions
    const applyBulkAction = document.getElementById('applyBulkAction');
    const bulkActionSelect = document.getElementById('bulkAction');
    
    if (applyBulkAction && bulkActionSelect) {
        applyBulkAction.addEventListener('click', function() {
            const selectedAction = bulkActionSelect.value;
            const selectedContacts = [];
            
            contactCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedContacts.push(checkbox.value);
                }
            });
            
            if (selectedContacts.length === 0) {
                alert('कृपया कम्तिमा एक सन्देश चयन गर्नुहोस्');
                return;
            }
            
            if (!selectedAction) {
                alert('कृपया कार्य चयन गर्नुहोस्');
                return;
            }
            
            if (selectedAction === 'delete') {
                if (!confirm('के तपाईं ' + selectedContacts.length + ' सन्देशहरू मेटाउन चाहनुहुन्छ?')) {
                    return;
                }
            }
            
            // Create form for bulk action
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("owner.contacts.bulk-action") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = selectedAction;
            form.appendChild(actionInput);
            
            selectedContacts.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        });
    }

    // Individual delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('के तपाईं यो सन्देश मेटाउन निश्चित हुनुहुन्छ?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush