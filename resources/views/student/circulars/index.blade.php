@extends('layouts.student')

@section('title', 'मेरा सूचनाहरू')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-bullhorn mr-2"></i>मेरा सूचनाहरू
                    </h3>
                </div>

                <div class="card-body">
                    <!-- Quick Stats - Optimized: Now using values passed from controller -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-envelope"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">कुल सूचनाहरू</span>
                                    <span class="info-box-number">{{ $stats['total'] ?? $circulars->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-eye"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">पढिसकेका</span>
                                    <span class="info-box-number">{{ $stats['read'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-envelope-open"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">नपढेका</span>
                                    <span class="info-box-number">{{ $stats['unread'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">जरुरी सूचनाहरू</span>
                                    <span class="info-box-number">{{ $stats['urgent_count'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters and Search -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" class="form-inline">
                                <div class="form-group mr-2 mb-2">
                                    <select name="read_status" class="form-control form-control-sm">
                                        <option value="">सबै सूचनाहरू</option>
                                        <option value="read" {{ request('read_status') == 'read' ? 'selected' : '' }}>पढिसकेका</option>
                                        <option value="unread" {{ request('read_status') == 'unread' ? 'selected' : '' }}>नपढेका</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mr-2 mb-2">
                                    <select name="priority" class="form-control form-control-sm">
                                        <option value="">सबै प्राथमिकता</option>
                                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>जरुरी</option>
                                        <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>सामान्य</option>
                                        <option value="info" {{ request('priority') == 'info' ? 'selected' : '' }}>जानकारी</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mr-2 mb-2">
                                    <input type="text" name="search" class="form-control form-control-sm" 
                                           placeholder="सूचना खोज्नुहोस्..." 
                                           value="{{ request('search') }}">
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm mb-2">
                                    <i class="fas fa-search mr-1"></i>खोज्नुहोस्
                                </button>
                                
                                <a href="{{ route('student.circulars.index') }}" class="btn btn-secondary btn-sm mb-2 ml-1">
                                    <i class="fas fa-redo mr-1"></i>रिसेट गर्नुहोस्
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Circulars List -->
                    <div class="list-group">
                        @forelse($circulars as $circular)
                            @php
                                // ✅ FIXED: Use first() instead of filtering collection
                                // Since we eager loaded with filter, this will have only the current user's recipient
                                $recipient = $circular->recipients->first();
                                $isRead = $recipient ? $recipient->is_read : false;
                            @endphp
                            <a href="{{ route('student.circulars.show', $circular) }}" 
                               class="list-group-item list-group-item-action {{ $isRead ? '' : 'bg-light' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        @if($circular->priority == 'urgent')
                                            <span class="badge badge-danger priority-badge">जरुरी</span>
                                        @elseif($circular->priority == 'normal')
                                            <span class="badge badge-primary priority-badge">सामान्य</span>
                                        @else
                                            <span class="badge badge-secondary priority-badge">जानकारी</span>
                                        @endif
                                        
                                        {{ $circular->title }}
                                        
                                        @if(!$isRead)
                                            <span class="badge badge-warning new-badge ml-2">नयाँ</span>
                                        @endif
                                    </h5>
                                    <small>
                                        @if($circular->published_at)
                                            {{ \Carbon\Carbon::parse($circular->published_at)->format('Y-m-d') }}
                                        @else
                                            {{ $circular->created_at->format('Y-m-d') }}
                                        @endif
                                    </small>
                                </div>
                                <p class="mb-1">{{ Str::limit($circular->content, 150) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <strong>संस्था:</strong> {{ $circular->organization->name ?? 'N/A' }}
                                        @if($circular->hostel)
                                            | <strong>होस्टेल:</strong> {{ $circular->hostel->name }}
                                        @endif
                                    </small>
                                    <small>
                                        @if($isRead)
                                            <span class="badge badge-success status-badge">
                                                <i class="fas fa-check mr-1"></i>पढिसक्नुभएको
                                            </span>
                                        @else
                                            <span class="badge badge-secondary status-badge">
                                                <i class="fas fa-envelope mr-1"></i>नपढेको
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </a>
                        @empty
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-4x mb-3"></i><br>
                                <h5>तपाईंलाई अहिलेसम्म कुनै पनि सूचना पठाइएको छैन</h5>
                                <small class="text-muted">नयाँ सूचनाहरू यहाँ देखिनेछन्</small>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $circulars->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* =========================================== */
/* ENHANCED STYLES FOR मेरा सूचनाहरू PAGE */
/* =========================================== */

/* 1. Card Header - White Text */
.card-header.bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    border-bottom: none;
    padding: 1rem 1.5rem;
}

.card-header.bg-primary.text-white,
.card-header.bg-primary.text-white h3,
.card-header.bg-primary.text-white i,
.card-header.bg-primary.text-white .card-title {
    color: white !important;
}

/* 2. Info Boxes - Attractive Gradient Design */
.info-box {
    min-height: 110px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    padding: 15px 20px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    border: none;
    position: relative;
    overflow: hidden;
}

.info-box::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: rgba(255,255,255,0.1);
    transform: rotate(45deg);
    transition: all 0.5s ease;
}

.info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.info-box:hover::before {
    transform: rotate(45deg) translate(10%, 10%);
}

.info-box.bg-info {
    background: linear-gradient(135deg, #17a2b8, #0f7c8f) !important;
}

.info-box.bg-success {
    background: linear-gradient(135deg, #28a745, #1e7e34) !important;
}

.info-box.bg-warning {
    background: linear-gradient(135deg, #ffc107, #d39e00) !important;
}

.info-box.bg-danger {
    background: linear-gradient(135deg, #dc3545, #bd2130) !important;
}

.info-box-icon {
    font-size: 2.5rem;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.15);
    border-radius: 50%;
    margin-right: 20px;
    color: white !important;
    z-index: 1;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.info-box-icon i {
    color: white !important;
    text-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.info-box-content {
    flex: 1;
    z-index: 1;
}

.info-box-text {
    font-size: 14px;
    font-weight: 600;
    color: rgba(255,255,255,0.9) !important;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 5px;
}

.info-box-number {
    font-size: 32px;
    font-weight: 700;
    color: white !important;
    line-height: 1.2;
    text-shadow: 0 2px 5px rgba(0,0,0,0.3);
    margin-bottom: 0;
}

/* 3. Badge Styling - All White Text */
.badge {
    font-weight: 600;
    padding: 0.5em 1em;
    border-radius: 30px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    color: white !important;
}

.badge-danger.priority-badge {
    background: linear-gradient(135deg, #dc3545, #bd2130) !important;
}

.badge-primary.priority-badge {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.badge-secondary.priority-badge {
    background: linear-gradient(135deg, #6c757d, #545b62) !important;
}

.badge-warning.new-badge {
    background: linear-gradient(135deg, #ffc107, #d39e00) !important;
    color: white !important;
}

.badge-success.status-badge {
    background: linear-gradient(135deg, #28a745, #1e7e34) !important;
}

.badge-secondary.status-badge {
    background: linear-gradient(135deg, #6c757d, #545b62) !important;
}

/* Override Bootstrap's default badge colors */
.badge-danger,
.badge-primary,
.badge-secondary,
.badge-warning,
.badge-success {
    color: white !important;
}

.badge i {
    color: white !important;
}

/* 4. Buttons - Attractive Gradient */
.btn-primary,
.btn-secondary {
    color: white !important;
    font-weight: 500;
    padding: 8px 22px;
    border-radius: 30px;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border: none;
    letter-spacing: 0.5px;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #007bff) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0,123,255,0.3);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #545b62) !important;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #545b62, #6c757d) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(108,117,125,0.3);
}

.btn i {
    color: white !important;
}

/* 5. Form Elements */
.form-control-sm {
    border-radius: 30px;
    padding: 8px 18px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
    font-size: 0.9rem;
}

.form-control-sm:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    outline: none;
}

select.form-control-sm {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 8L1 3h10L6 8z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

/* 6. List Group Items */
.list-group-item {
    border: none;
    border-radius: 12px !important;
    margin-bottom: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: all 0.3s;
    padding: 1.2rem 1.5rem;
}

.list-group-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.list-group-item.bg-light {
    background-color: #f8f9ff !important;
    border-left: 4px solid #ffc107;
}

/* 7. Empty State */
.text-center.text-muted {
    padding: 40px 20px !important;
}

.text-center.text-muted i {
    font-size: 5rem;
    color: #adb5bd;
    opacity: 0.5;
}

.text-center.text-muted h5 {
    color: #6c757d;
    margin-top: 15px;
}

/* 8. Pagination */
.pagination {
    margin-top: 20px;
}

.pagination .page-link {
    color: #007bff;
    border-radius: 30px;
    margin: 0 4px;
    padding: 8px 15px;
    border: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-color: transparent;
    color: white;
    box-shadow: 0 5px 12px rgba(0,123,255,0.3);
}

.pagination .page-link:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(0,0,0,0.1);
}

/* 9. Responsive Adjustments */
@media (max-width: 768px) {
    .info-box {
        min-height: 90px;
        padding: 10px 15px;
    }
    
    .info-box-icon {
        width: 50px;
        height: 50px;
        font-size: 1.8rem;
        margin-right: 12px;
    }
    
    .info-box-number {
        font-size: 24px;
    }
    
    .info-box-text {
        font-size: 12px;
    }
    
    .list-group-item {
        padding: 1rem;
    }
}
</style>
@endpush