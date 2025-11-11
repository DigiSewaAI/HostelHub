@extends('layouts.admin')

@section('title', 'सम्पर्क व्यवस्थापन')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-envelope me-2"></i> 
                    @role('admin')
                    सम्पर्क सन्देशहरू
                    @else
                    मेरा सम्पर्क सन्देशहरू
                    @endrole
                </h2>
                @role('admin')
                <a href="{{ route('admin.contacts.create') }}" class="btn btn-success btn-lg shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> नयाँ सम्पर्क थप्नुहोस्
                </a>
                @endrole
            </div>
        </div>
    </div>

    <!-- ✅ IMPROVED: Compact and Attractive Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल सन्देश
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $contacts->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                नपढिएका
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unreadCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope-open fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                आजका सन्देश
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                जवाफ दिइयो
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $repliedCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-reply fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 text-secondary">
                            <i class="fas fa-list me-2"></i> सम्पर्क सूची
                        </h5>

                        <div class="d-flex align-items-center gap-3">
                            <!-- Filter Buttons -->
                            <div class="btn-group" role="group">
                                @php
                                    $currentFilter = request('filter', 'all');
                                    $currentSearch = request('search');
                                @endphp
                                
                                <a href="{{ route('admin.contacts.index', ['filter' => 'all']) }}" 
                                   class="btn btn-outline-primary {{ $currentFilter == 'all' ? 'active' : '' }}">
                                    सबै सन्देशहरू
                                </a>
                                <a href="{{ route('admin.contacts.index', ['filter' => 'unread']) }}" 
                                   class="btn btn-outline-warning {{ $currentFilter == 'unread' ? 'active' : '' }}">
                                    नपढिएका
                                </a>
                                <a href="{{ route('admin.contacts.index', ['filter' => 'today']) }}" 
                                   class="btn btn-outline-success {{ $currentFilter == 'today' ? 'active' : '' }}">
                                    आजका
                                </a>
                                <a href="{{ route('admin.contacts.index', ['filter' => 'read']) }}" 
                                   class="btn btn-outline-secondary {{ $currentFilter == 'read' ? 'active' : '' }}">
                                    पढिएका
                                </a>
                            </div>

                            <!-- ✅ FIXED: Search Form with Visible Button -->
                            <form action="{{ route('admin.contacts.index') }}" method="GET" class="d-flex" style="max-width: 350px;">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control border-primary" 
                                           placeholder="नाम, इमेल, विषय वा सन्देश खोज्नुहोस्..." 
                                           value="{{ request('search') }}">
                                    @if(request()->has('filter'))
                                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                                    @endif
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> खोज्नुहोस्
                                    </button>
                                    @if(request()->has('search') || request()->has('filter'))
                                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-danger">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 5%;">क्र.स.</th>
                                    <th><i class="fas fa-user me-1"></i> नाम</th>
                                    <th><i class="fas fa-envelope me-1"></i> इमेल</th>
                                    <th><i class="fas fa-phone me-1"></i> फोन</th>
                                    <th><i class="fas fa-home me-1"></i> होस्टल/कोठा</th>
                                    <th><i class="fas fa-comment me-1"></i> वास्तविक सन्देश</th>
                                    <th><i class="fas fa-info-circle me-1"></i> स्थिति</th>
                                    <th class="text-center" style="width: 15%;"><i class="fas fa-cogs me-1"></i> क्रियाहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                <tr class="{{ !$contact->is_read ? 'table-warning' : '' }}">
                                    <td class="text-center fw-bold">{{ ($contacts->currentPage() - 1) * $contacts->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ $contact->name }}
                                            @if(!$contact->is_read)
                                                <span class="badge bg-warning ms-2">नयाँ</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                            {{ $contact->email }}
                                        </a>
                                    </td>
                                    <td>{{ $contact->phone ?? '—' }}</td>
                                    <td>
                                        @if($contact->hostel || $contact->room)
                                            <div class="small">
                                                @if($contact->hostel)
                                                    <div><strong>होस्टल:</strong> {{ $contact->hostel->name }}</div>
                                                @endif
                                                @if($contact->room)
                                                    <div><strong>कोठा:</strong> {{ $contact->room->room_number }}</div>
                                                @else
                                                    <div class="text-muted"><small>कोठा नखुलिएको</small></div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="message-container">
                                            <strong class="d-block text-primary">{{ $contact->subject }}</strong>
                                            <small class="text-muted">{{ Str::limit($contact->message, 50) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @if($contact->is_read)
                                                <span class="badge bg-success">पढियो</span>
                                            @else
                                                <span class="badge bg-warning text-dark">नपढिएको</span>
                                            @endif
                                            
                                            @if($contact->status == 'replied' || $contact->status == 'जवाफ दिइयो')
                                                <span class="badge bg-info">जवाफ दिइयो</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.contacts.show', $contact->id) }}" 
                                               class="btn btn-info btn-sm me-1" 
                                               title="सन्देश हेर्नुहोस्">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(!$contact->is_read)
                                                <form action="{{ route('admin.contacts.update-status', $contact->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="read">
                                                    <button type="submit" class="btn btn-success btn-sm me-1" title="पढियो चिन्ह लगाउनुहोस्">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.contacts.update-status', $contact->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="unread">
                                                    <button type="submit" class="btn btn-warning btn-sm me-1" title="नपढिएको चिन्ह लगाउनुहोस्">
                                                        <i class="fas fa-envelope"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="{{ route('admin.contacts.edit', $contact->id) }}" 
                                               class="btn btn-primary btn-sm me-1" 
                                               title="सम्पादन गर्नुहोस्">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="मेटाउनुहोस्"
                                                        onclick="return confirm('के तपाईं यो सम्पर्क सन्देश स्थायी रूपमा मेटाउन चाहनुहुन्छ?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted d-block mb-3"></i>
                                        <h5 class="text-muted">कुनै सम्पर्क सन्देश फेला परेन</h5>
                                        <p class="text-muted">
                                            @if(request()->has('search') || request()->has('filter'))
                                                तपाईंको खोजी वा फिल्टरको लागि कुनै परिणाम फेला परेन। 
                                                <a href="{{ route('admin.contacts.index') }}" class="text-primary">सबै सन्देशहरू हेर्नुहोस्</a>
                                            @else
                                                अहिलेसम्म कुनै सम्पर्क सन्देश आएको छैन।
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($contacts->hasPages())
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap">
                            <div class="text-muted">
                                देखाइएको: <strong>{{ $contacts->firstItem() ?? 0 }}</strong> देखि <strong>{{ $contacts->lastItem() ?? 0 }}</strong> 
                                को <strong>{{ $contacts->total() }}</strong> मध्ये
                            </div>
                            <div>
                                {{ $contacts->withQueryString()->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.message-container {
    max-width: 200px;
}
.table-warning {
    background-color: #fff3cd !important;
}

/* ✅ IMPROVED: Better Card Styles */
.card {
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.text-xs {
    font-size: 0.7rem;
}
.text-gray-800 {
    color: #5a5c69 !important;
}

/* ✅ FIXED: Search Button Visibility */
.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
    color: white !important;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

/* ✅ NEW: Better table styling */
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Confirm before deleting
    const deleteForms = document.querySelectorAll('form[action*="destroy"]');
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('के तपाईं निश्चित हुनुहुन्छ कि तपाईं यो सम्पर्क सन्देश मेटाउन चाहनुहुन्छ?')) {
                e.preventDefault();
            }
        });
    });

    // Quick status update with AJAX
    const statusForms = document.querySelectorAll('form[action*="update-status"]');
    statusForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitButton.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('स्थिति अपडेट गर्न असफल: ' + data.message);
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('त्रुटि भयो। पुनः प्रयास गर्नुहोस्।');
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    });

    // Enhanced search functionality
    const searchForm = document.querySelector('form[action*="contacts"]');
    const searchInput = searchForm.querySelector('input[name="search"]');
    
    searchInput.addEventListener('input', function() {
        if (this.value.length > 2) {
            // Auto-search after 3 characters (optional)
            // searchForm.submit();
        }
    });
});
</script>
@endsection