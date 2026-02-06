@extends('layouts.owner')

@section('title', 'रूम समस्याहरू')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                रूम समस्याहरू
            </h1>
            <p class="text-muted mb-0">विद्यार्थीहरूबाट रिपोर्ट गरिएका रूम समस्याहरू</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="d-flex">
            <div class="card stats-card mr-3">
                <div class="card-body text-center p-2">
                    <div class="text-sm font-weight-bold text-primary">कुल</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                </div>
            </div>
            <a href="{{ route('owner.room-issues.index', ['status' => 'pending']) }}" 
               class="card stats-card mr-3 {{ request('status') == 'pending' ? 'active' : '' }}">
                <div class="card-body text-center p-2">
                    <div class="text-sm font-weight-bold text-warning">पेन्डिङ</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                </div>
            </a>
            <a href="{{ route('owner.room-issues.index', ['priority' => 'high']) }}" 
               class="card stats-card mr-3 {{ request('priority') == 'high' ? 'active' : '' }}">
                <div class="card-body text-center p-2">
                    <div class="text-sm font-weight-bold text-danger">जरुरी</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['high_priority'] }}</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card shadow-sm mb-4 border-left-primary">
        <div class="card-body">
            <form method="GET" action="{{ route('owner.room-issues.index') }}" id="filterForm" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">स्थिति</label>
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>सबै</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>पेन्डिङ</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>प्रक्रियामा</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>समाधान भएको</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">प्राथमिकता</label>
                    <select name="priority" class="form-control" onchange="this.form.submit()">
                        <option value="all" {{ request('priority') == 'all' || !request('priority') ? 'selected' : '' }}>सबै</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>उच्च</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>मध्यम</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>कम</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">होस्टेल</label>
                    <select name="hostel_id" class="form-control" onchange="this.form.submit()">
                        <option value="all" {{ request('hostel_id') == 'all' || !request('hostel_id') ? 'selected' : '' }}>सबै होस्टेल</option>
                        @foreach($hostels as $hostel)
                            <option value="{{ $hostel->id }}" {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                {{ $hostel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Issues Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($issues->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>विद्यार्थी</th>
                                <th>होस्टेल / कोठा</th>
                                <th>समस्याको प्रकार</th>
                                <th>प्राथमिकता</th>
                                <th>स्थिति</th>
                                <th>मिति</th>
                                <th>कार्यहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issues as $issue)
                                <tr>
                                    <td class="text-dark font-weight-bold">
                                        {{ $issues->firstItem() + $loop->index }}
                                    </td>
                                    
                                    <td>
                                        <div class="font-weight-bold text-dark">
                                            {{ $issue->student->user->name ?? 'अज्ञात' }}
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $issue->hostel->name ?? 'अज्ञात' }}</div>
                                        <small class="text-muted">
                                            कोठा: {{ $issue->room->room_number ?? 'N/A' }}
                                        </small>
                                    </td>
                                    
                                    <td>
                                        <span class="badge badge-primary text-white">
                                            {{ $issue->issue_type_nepali ?? $issue->issue_type }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        @if($issue->priority == 'high')
                                            <span class="badge badge-danger text-white">
                                                उच्च
                                            </span>
                                        @elseif($issue->priority == 'medium')
                                            <span class="badge badge-warning text-dark">
                                                मध्यम
                                            </span>
                                        @else
                                            <span class="badge badge-success text-white">
                                                कम
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if($issue->status == 'pending')
                                            <span class="badge badge-warning text-dark">
                                                पेन्डिङ
                                            </span>
                                        @elseif($issue->status == 'processing')
                                            <span class="badge badge-info text-white">
                                                प्रक्रियामा
                                            </span>
                                        @elseif($issue->status == 'resolved')
                                            <span class="badge badge-success text-white">
                                                समाधान भएको
                                            </span>
                                        @else
                                            <span class="badge badge-secondary text-white">
                                                एउटै issue दुई पटक report
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-dark">
                                        {{ $issue->created_at->format('Y-m-d') }}<br>
                                        <small class="text-muted">{{ $issue->created_at->format('h:i A') }}</small>
                                    </td>
                                    
                                    <td>
                                        <!-- Desktop View -->
                                        <div class="d-none d-md-block">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('owner.room-issues.show', $issue->id) }}" 
                                                   class="btn btn-primary" title="विवरण हेर्नुहोस्">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($issue->status != 'processing')
                                                <form method="POST" action="{{ route('owner.room-issues.update', $issue->id) }}" 
                                                      class="d-inline status-change-form">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="processing">
                                                    <button type="submit" class="btn btn-info" title="प्रक्रियामा राख्नुहोस्">
                                                        <i class="fas fa-cog"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                
                                                @if($issue->status != 'resolved')
                                                <form method="POST" action="{{ route('owner.room-issues.update', $issue->id) }}" 
                                                      class="d-inline status-change-form">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="resolved">
                                                    <button type="submit" class="btn btn-success" title="समाधान भएको">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                
                                                <button type="button" class="btn btn-danger" 
                                                        onclick="if(confirm('के तपाईं यो समस्या मेटाउन चाहनुहुन्छ?')) { document.getElementById('delete-form-{{ $issue->id }}').submit(); }"
                                                        title="मेटाउनुहोस्">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Mobile View -->
                                        <div class="d-block d-md-none">
                                            <select class="form-control form-control-sm mobile-actions" 
                                                    onchange="handleMobileAction(this, {{ $issue->id }})">
                                                <option value="">कार्यहरू छान्नुहोस्</option>
                                                <option value="view">विवरण हेर्नुहोस्</option>
                                                @if($issue->status != 'processing')
                                                <option value="processing">प्रक्रियामा राख्नुहोस्</option>
                                                @endif
                                                @if($issue->status != 'resolved')
                                                <option value="resolved">समाधान भएको</option>
                                                @endif
                                                <option value="delete">मेटाउनुहोस्</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Hidden Forms -->
                                        <form id="delete-form-{{ $issue->id }}" 
                                              action="{{ route('owner.room-issues.destroy', $issue->id) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        
                                        <form id="processing-form-{{ $issue->id }}" 
                                              action="{{ route('owner.room-issues.update', $issue->id) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="processing">
                                        </form>
                                        
                                        <form id="resolved-form-{{ $issue->id }}" 
                                              action="{{ route('owner.room-issues.update', $issue->id) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="resolved">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $issues->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h4 class="text-muted">कुनै रूम समस्या फेला परेन</h4>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.stats-card {
    width: 120px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-decoration: none;
    color: inherit;
}
.stats-card:hover {
    border-color: #4e73df;
    text-decoration: none;
}
.stats-card.active {
    border-color: #4e73df;
    background-color: #f8f9fc;
}
.badge-primary { background-color: #4e73df !important; color: white !important; }
.badge-danger { background-color: #dc3545 !important; color: white !important; }
.badge-warning { background-color: #ffc107 !important; color: #212529 !important; }
.badge-success { background-color: #28a745 !important; color: white !important; }
.badge-info { background-color: #17a2b8 !important; color: white !important; }
.badge-secondary { background-color: #6c757d !important; color: white !important; }
</style>

<script>
function handleMobileAction(select, issueId) {
    const action = select.value;
    
    if (action === 'view') {
        window.location.href = `/owner/room-issues/${issueId}`;
    } 
    else if (action === 'processing') {
        if (confirm('के तपाईं प्रक्रियामा राख्न चाहनुहुन्छ?')) {
            document.getElementById(`processing-form-${issueId}`).submit();
        }
    }
    else if (action === 'resolved') {
        if (confirm('के तपाईं समाधान भएको चिन्ह लगाउन चाहनुहुन्छ?')) {
            document.getElementById(`resolved-form-${issueId}`).submit();
        }
    }
    else if (action === 'delete') {
        if (confirm('के तपाईं मेटाउन चाहनुहुन्छ?')) {
            document.getElementById(`delete-form-${issueId}`).submit();
        }
    }
    
    // Reset dropdown
    select.value = '';
}
</script>
@endsection