@extends('layouts.owner')

@section('title', 'कोठा समस्याहरू - होस्टलहब')

@section('content')
<div class="container-fluid px-4">
    <!-- पृष्ठ शीर्षक र क्रियाहरू -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">कोठा समस्याहरू</h1>
            <p class="mb-0 text-muted">तपाईंको होस्टलका सबै कोठा समस्याहरूको सूची</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- फिल्टर बटनहरू -->
            <div class="btn-group" role="group">
                <a href="{{ route('owner.room-issues.index') }}" 
                   class="btn btn-outline-primary {{ request()->get('status') == null ? 'active' : '' }}">
                    सबै ({{ $roomIssues->total() }})
                </a>
                <a href="{{ route('owner.room-issues.index') }}?status=pending" 
                   class="btn btn-outline-warning {{ request()->get('status') == 'pending' ? 'active' : '' }}">
                    बाँकी <span class="badge bg-warning text-dark">{{ $pendingCount ?? 0 }}</span>
                </a>
                <a href="{{ route('owner.room-issues.index') }}?status=resolved" 
                   class="btn btn-outline-success {{ request()->get('status') == 'resolved' ? 'active' : '' }}">
                    समाधान भएका
                </a>
            </div>
        </div>
    </div>

    <!-- स्ट्याटस कार्डहरू -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल समस्याहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $roomIssues->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                बाँकी समस्याहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                जरुरी समस्याहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $urgentCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                समाधान भएका</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resolvedCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- समस्याहरूको तालिका -->
    <div class="card shadow mb-4">
        <div class="card-body">
            @if($roomIssues->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="roomIssuesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>समस्याको प्रकार</th>
                                <th>विवरण</th>
                                <th>विद्यार्थी</th>
                                <th>कोठा नं.</th>
                                <th>प्राथमिकता</th>
                                <th>स्थिति</th>
                                <th>रिपोर्ट मिति</th>
                                <th>क्रियाहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roomIssues as $index => $issue)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $issue->issue_type }}</strong>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" 
                                             title="{{ $issue->description }}">
                                            {{ $issue->description }}
                                        </div>
                                    </td>
                                    <td>{{ $issue->student_name ?? 'N/A' }}</td>
                                    <td>{{ $issue->room_number ?? 'N/A' }}</td>
                                    <td>
                                        @if($issue->priority == 'urgent')
                                            <span class="badge bg-danger">जरुरी</span>
                                        @elseif($issue->priority == 'high')
                                            <span class="badge bg-warning text-dark">उच्च</span>
                                        @else
                                            <span class="badge bg-info">सामान्य</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($issue->status == 'pending')
                                            <span class="badge bg-warning text-dark">बाँकी</span>
                                        @elseif($issue->status == 'resolved')
                                            <span class="badge bg-success">समाधान भएको</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $issue->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($issue->reported_at)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- ✅ FIXED: Changed from link to button with JavaScript function -->
                                            <button type="button" class="btn btn-info" title="हेर्नुहोस्" onclick="viewRoomIssue({{ $issue->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <!-- ✅ FIXED: Changed from form to button with JavaScript function -->
                                            @if($issue->status == 'pending')
                                                <button type="button" class="btn btn-success" title="समाधान गर्नुहोस्" onclick="resolveIssue({{ $issue->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-warning" title="फेरि खोल्नुहोस्" onclick="reopenIssue({{ $issue->id }})">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            @endif
                                            
                                            <!-- ✅ ADDED: Delete Button -->
                                            <button type="button" class="btn btn-danger" title="मेटाउनुहोस्" onclick="deleteIssue({{ $issue->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- पेजिनेशन -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        देखाइएको: {{ $roomIssues->firstItem() }} - {{ $roomIssues->lastItem() }} 
                        कुल: {{ $roomIssues->total() }}
                    </div>
                    <div>
                        {{ $roomIssues->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 class="text-success">कुनै कोठा समस्या छैन!</h4>
                    <p class="text-muted">तपाईंको होस्टलमा अहिले कुनै कोठा समस्या रिपोर्ट भएको छैन।</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.owner')

@section('title', 'कोठा समस्याहरू')

@section('styles')
<style>
    .status-badge {
        font-size: 0.85em;
        padding: 5px 10px;
        border-radius: 20px;
    }
    .badge-pending {
        background-color: #ffc107;
        color: #000;
    }
    .badge-resolved {
        background-color: #28a745;
        color: #fff;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    .table-responsive {
        margin-top: 20px;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0 !important;
        padding: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tools text-primary"></i> कोठा समस्याहरू
        </h1>
        <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ड्यासबोर्डमा फिर्ता जानुहोस्
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल समस्याहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $roomIssues->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                पेन्डिङ समस्याहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $roomIssues->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                समाधान भएका</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $roomIssues->where('status', 'resolved')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                अत्यावश्यक समस्याहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $roomIssues->where('priority', 'urgent')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-list-alt mr-2"></i>सबै कोठा समस्याहरू
            </h6>
            <div>
                <span class="badge badge-light mr-2">पेन्डिङ: <span class="text-warning font-weight-bold">{{ $roomIssues->where('status', 'pending')->count() }}</span></span>
                <span class="badge badge-light">समाधान: <span class="text-success font-weight-bold">{{ $roomIssues->where('status', 'resolved')->count() }}</span></span>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($roomIssues->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-tools fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">कुनै कोठा समस्या छैन</h4>
                    <p class="text-muted">अहिलेसम्म कुनै पनि कोठा समस्या रिपोर्ट भएको छैन।</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="roomIssuesTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-user mr-1"></i> विद्यार्थी</th>
                                <th><i class="fas fa-bed mr-1"></i> कोठा</th>
                                <th><i class="fas fa-home mr-1"></i> होस्टल</th>
                                <th><i class="fas fa-tag mr-1"></i> समस्याको प्रकार</th>
                                <th><i class="fas fa-flag mr-1"></i> प्राथमिकता</th>
                                <th><i class="fas fa-info-circle mr-1"></i> स्थिति</th>
                                <th><i class="fas fa-calendar mr-1"></i> रिपोर्ट गरेको मिति</th>
                                <th><i class="fas fa-cogs mr-1"></i> कार्यहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roomIssues as $index => $issue)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="font-weight-bold">{{ $issue->student->first_name ?? 'N/A' }} {{ $issue->student->last_name ?? '' }}</div>
                                    <small class="text-muted">ID: {{ $issue->student->student_id ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-primary p-2">
                                        <i class="fas fa-door-closed mr-1"></i> कोठा {{ $issue->room_number }}
                                    </span>
                                </td>
                                <td>{{ $issue->hostel->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $issueTypes = [
                                            'electricity' => ['icon' => 'fas fa-bolt', 'color' => 'warning'],
                                            'plumbing' => ['icon' => 'fas fa-faucet', 'color' => 'info'],
                                            'furniture' => ['icon' => 'fas fa-couch', 'color' => 'secondary'],
                                            'cleanliness' => ['icon' => 'fas fa-broom', 'color' => 'success'],
                                            'other' => ['icon' => 'fas fa-tools', 'color' => 'dark']
                                        ];
                                        $type = $issueTypes[$issue->issue_type] ?? $issueTypes['other'];
                                    @endphp
                                    <span class="badge badge-{{ $type['color'] }} p-2">
                                        <i class="{{ $type['icon'] }} mr-1"></i>
                                        @if($issue->issue_type == 'electricity')
                                            विद्युत
                                        @elseif($issue->issue_type == 'plumbing')
                                            पानी
                                        @elseif($issue->issue_type == 'furniture')
                                            सामान
                                        @elseif($issue->issue_type == 'cleanliness')
                                            सफाइ
                                        @else
                                            अन्य
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $priorityClasses = [
                                            'urgent' => ['class' => 'danger', 'text' => 'अत्यावश्यक'],
                                            'high' => ['class' => 'warning', 'text' => 'उच्च'],
                                            'medium' => ['class' => 'info', 'text' => 'मध्यम'],
                                            'low' => ['class' => 'secondary', 'text' => 'निम्न']
                                        ];
                                        $priority = $priorityClasses[$issue->priority] ?? $priorityClasses['medium'];
                                    @endphp
                                    <span class="badge badge-{{ $priority['class'] }} p-2">
                                        {{ $priority['text'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($issue->status == 'pending')
                                        <span class="badge badge-warning p-2">
                                            <i class="fas fa-clock mr-1"></i> पेन्डिङ
                                        </span>
                                    @else
                                        <span class="badge badge-success p-2">
                                            <i class="fas fa-check-circle mr-1"></i> समाधान भयो
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($issue->reported_at)->format('Y-m-d h:i A') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($issue->reported_at)->locale('ne')->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- View Button -->
                                        <a href="{{ route('owner.room-issues.show', $issue->id) }}" 
                                           class="btn btn-info" 
                                           title="हेर्नुहोस्"
                                           onclick="return confirmView()">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Resolve/Reopen Button -->
                                        @if($issue->status == 'pending')
                                            <form method="POST" 
                                                  action="{{ route('owner.room-issues.resolve', $issue->id) }}" 
                                                  style="display: inline;"
                                                  id="resolveForm{{ $issue->id }}">
                                                @csrf
                                                <button type="button" 
                                                        class="btn btn-success" 
                                                        title="समाधान गर्नुहोस्"
                                                        onclick="confirmResolve('resolveForm{{ $issue->id }}')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" 
                                                  action="{{ route('owner.room-issues.reopen', $issue->id) }}" 
                                                  style="display: inline;"
                                                  id="reopenForm{{ $issue->id }}">
                                                @csrf
                                                <button type="button" 
                                                        class="btn btn-warning" 
                                                        title="फेरि खोल्नुहोस्"
                                                        onclick="confirmReopen('reopenForm{{ $issue->id }}')">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Delete Button -->
                                        <form method="POST" 
                                              action="{{ route('owner.room-issues.destroy', $issue->id) }}" 
                                              style="display: inline;"
                                              id="deleteForm{{ $issue->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="मेटाउनुहोस्"
                                                    onclick="confirmDelete('deleteForm{{ $issue->id }}')">
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
            @endif
        </div>
        <div class="card-footer text-muted">
            <small>
                <i class="fas fa-info-circle mr-1"></i>
                स्वतः अपडेट: यो पृष्ठ प्रत्येक ३० सेकेन्डमा स्वतः अपडेट हुन्छ।
                अन्तिम अपडेट: {{ now()->format('Y-m-d h:i:s A') }}
            </small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SIMPLE jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
// SIMPLE GLOBAL FUNCTIONS - NO COMPLEX WRAPPING
function confirmView() {
    return true; // Allow direct navigation
}

function confirmResolve(formId) {
    if (confirm('के तपाईं यो समस्या समाधान गर्न चाहनुहुन्छ?\nयो कार्य उल्टाउन सकिँदैन।')) {
        document.getElementById(formId).submit();
    }
    return false;
}

function confirmReopen(formId) {
    if (confirm('के तपाईं यो समस्या फेरि बाँकीमा राख्न चाहनुहुन्छ?')) {
        document.getElementById(formId).submit();
    }
    return false;
}

function confirmDelete(formId) {
    if (confirm('के तपाईं यो कोठा समस्या मेटाउन निश्चित हुनुहुन्छ?\nयो कार्य उल्टाउन सकिँदैन।')) {
        document.getElementById(formId).submit();
    }
    return false;
}

// Simple DataTable initialization
$(document).ready(function() {
    // Check if DataTable exists
    if ($.fn.dataTable) {
        $('#roomIssuesTable').DataTable({
            "pageLength": 25,
            "language": {
                "search": "खोज्नुहोस्:",
                "lengthMenu": "प्रति पृष्ठ _MENU_ वस्तुहरू देखाउनुहोस्",
                "info": "देखाइएको _START_ देखि _END_ सम्म _TOTAL_ वस्तुहरू",
                "paginate": {
                    "first": "पहिलो",
                    "last": "अन्तिम",
                    "next": "अर्को",
                    "previous": "अघिल्लो"
                }
            },
            "order": [[7, "desc"]] // Sort by reported date descending
        });
    }
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        console.log('Auto-refreshing page...');
        location.reload();
    }, 30000);
});
</script>
@endsection