@extends('layouts.owner')

@section('title', 'रूम समस्या विवरण')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                रूम समस्या विवरण
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('owner.room-issues.index') }}">रूम समस्याहरू</a>
                    </li>
                    <li class="breadcrumb-item active">विवरण</li>
                </ol>
            </nav>
        </div>
        
        <a href="{{ route('owner.room-issues.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left mr-1"></i> पछि फर्कनुहोस्
        </a>
    </div>

    <div class="row">
        <!-- Left Column - Issue Details -->
        <div class="col-lg-8">
            <!-- Issue Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        समस्याको विवरण
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">समस्याको प्रकार:</h6>
                            <p class="h5">
                                <span class="badge badge-primary text-white">
                                    {{ $issue->issue_type_nepali ?? $issue->issue_type }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">प्राथमिकता:</h6>
                            <p class="h5">
                                @if($issue->priority == 'high')
                                    <span class="badge badge-danger text-white">
                                        <i class="fas fa-exclamation-circle mr-1"></i>उच्च प्राथमिकता
                                    </span>
                                @elseif($issue->priority == 'medium')
                                    <span class="badge badge-warning text-dark">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>मध्यम प्राथमिकता
                                    </span>
                                @else
                                    <span class="badge badge-success text-white">
                                        <i class="fas fa-info-circle mr-1"></i>कम प्राथमिकता
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">समस्याको विस्तृत विवरण:</h6>
                        <div class="bg-light p-4 rounded">
                            <p class="mb-0">{{ $issue->description }}</p>
                        </div>
                    </div>
                    
                    <!-- Attached Images -->
                    @if($issue->image_url)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">संलग्न फोटो:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ $issue->image_url }}" target="_blank" class="d-block">
                                        <img src="{{ $issue->image_url }}" 
                                             alt="समस्याको फोटो" 
                                             class="img-fluid rounded shadow-sm"
                                             style="max-height: 250px; object-fit: cover;">
                                    </a>
                                    <small class="text-muted d-block mt-2">
                                        विद्यार्थीले खिचेको फोटो
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Update Status Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-cog mr-2"></i>
                        स्थिति अद्यावधिक गर्नुहोस्
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('owner.room-issues.update', $issue->id) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">नयाँ स्थिति</label>
                                <select name="status" class="form-control" required>
                                    <option value="pending" {{ $issue->status == 'pending' ? 'selected' : '' }}>पेन्डिङ</option>
                                    <option value="processing" {{ $issue->status == 'processing' ? 'selected' : '' }}>प्रक्रियामा</option>
                                    <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>समाधान भएको</option>
                                    <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>एउटै issue दुई पटक report</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">जिम्मेवार व्यक्ति</label>
                                <input type="text" 
                                       name="assigned_to" 
                                       class="form-control" 
                                       placeholder="जिम्मेवार व्यक्तिको नाम"
                                       value="{{ old('assigned_to', $issue->assigned_to) }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">समाधान विवरण (वैकल्पिक)</label>
                            <textarea name="resolution_notes" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="समस्या कसरी समाधान गरियो?">{{ old('resolution_notes', $issue->resolution_notes) }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> अद्यावधिक गर्नुहोस्
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Info Sidebar -->
        <div class="col-lg-4">
            <!-- Student Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate mr-2"></i>
                        विद्यार्थी जानकारी
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            {{ substr($issue->student->user->name ?? 'N', 0, 1) }}
                        </div>
                        <h5>{{ $issue->student->user->name ?? 'अज्ञात' }}</h5>
                        <p class="text-muted">{{ $issue->student->user->email ?? '' }}</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between">
                            <span>फोन नम्बर:</span>
                            <strong>{{ $issue->student->user->phone ?? 'उपलब्ध छैन' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>विद्यार्थी ID:</span>
                            <strong>{{ $issue->student->student_id ?? 'N/A' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>अनुरोध मिति:</span>
                            <strong>{{ $issue->created_at->format('Y-m-d h:i A') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Room/Hostel Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-home mr-2"></i>
                        होस्टेल / कोठा जानकारी
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between">
                            <span>होस्टेल:</span>
                            <strong>{{ $issue->hostel->name ?? 'अज्ञात' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>कोठा नम्बर:</span>
                            <strong>{{ $issue->room->room_number ?? 'N/A' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>कोठा प्रकार:</span>
                            <strong>{{ $issue->room->type_nepali ?? $issue->room->type ?? 'N/A' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>होस्टेल फोन:</span>
                            <strong>{{ $issue->hostel->phone ?? 'उपलब्ध छैन' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Current Status -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line mr-2"></i>
                        हालको स्थिति
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($issue->status == 'pending')
                            <div class="h1 text-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4 class="text-warning">पेन्डिङ</h4>
                            <p class="text-muted">समस्या अझै समीक्षाको लागि पेन्डिङमा छ</p>
                        @elseif($issue->status == 'processing')
                            <div class="h1 text-info">
                                <i class="fas fa-cog fa-spin"></i>
                            </div>
                            <h4 class="text-info">प्रक्रियामा</h4>
                            <p class="text-muted">समस्या समाधानको प्रक्रियामा छ</p>
                        @elseif($issue->status == 'resolved')
                            <div class="h1 text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h4 class="text-success">समाधान भएको</h4>
                            <p class="text-muted">
                                @if($issue->resolved_at)
                                    समाधान मिति: {{ $issue->resolved_at->format('Y-m-d') }}
                                @endif
                            </p>
                        @else
                            <div class="h1 text-secondary">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h4 class="text-secondary">एउटै issue दुई पटक report</h4>
                        @endif
                    </div>
                    
                    @if($issue->assigned_to)
                        <div class="alert alert-info">
                            <i class="fas fa-user-tie mr-2"></i>
                            <strong>जिम्मेवार:</strong> {{ $issue->assigned_to }}
                        </div>
                    @endif
                    
                    @if($issue->resolution_notes)
                        <div class="alert alert-success">
                            <i class="fas fa-sticky-note mr-2"></i>
                            <strong>समाधान विवरण:</strong>
                            <p class="mb-0 mt-1">{{ $issue->resolution_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 32px;
    font-weight: bold;
}
.list-group-item {
    border: none;
    padding: 0.75rem 0;
}
.card-header {
    border-bottom: none;
}
/* Fix for badge visibility */
.badge-primary { background-color: #4e73df !important; color: white !important; }
.badge-danger { background-color: #dc3545 !important; color: white !important; }
.badge-warning { background-color: #ffc107 !important; color: #212529 !important; }
.badge-success { background-color: #28a745 !important; color: white !important; }
.badge-info { background-color: #17a2b8 !important; color: white !important; }
.badge-secondary { background-color: #6c757d !important; color: white !important; }
</style>
@endsection