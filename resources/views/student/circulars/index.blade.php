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
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-envelope"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">कुल सूचनाहरू</span>
                                    <span class="info-box-number">{{ $circulars->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-eye"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">पढिसकेका</span>
                                    <span class="info-box-number">
                                        @php
                                            $readCount = 0;
                                            foreach($circulars as $circular) {
                                                $recipient = $circular->recipients->where('user_id', auth()->id())->first();
                                                if($recipient && $recipient->is_read) {
                                                    $readCount++;
                                                }
                                            }
                                        @endphp
                                        {{ $readCount }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-envelope-open"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">नपढेका</span>
                                    <span class="info-box-number">{{ $circulars->total() - $readCount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">जरुरी सूचनाहरू</span>
                                    <span class="info-box-number">
                                        {{ $circulars->where('priority', 'urgent')->count() }}
                                    </span>
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
                                $recipient = $circular->recipients->where('user_id', auth()->id())->first();
                                $isRead = $recipient ? $recipient->is_read : false;
                            @endphp
                            <a href="{{ route('student.circulars.show', $circular) }}" 
                               class="list-group-item list-group-item-action {{ $isRead ? '' : 'bg-light' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        @if($circular->priority == 'urgent')
                                            <span class="badge badge-danger mr-2" style="font-size: 0.9em; padding: 0.5em 0.8em;">जरुरी</span>
                                        @elseif($circular->priority == 'normal')
                                            <span class="badge badge-primary mr-2" style="font-size: 0.9em; padding: 0.5em 0.8em;">सामान्य</span>
                                        @else
                                            <span class="badge badge-dark mr-2" style="font-size: 0.9em; padding: 0.5em 0.8em; background-color: #6c757d !important;">जानकारी</span>
                                        @endif
                                        
                                        {{ $circular->title }}
                                        
                                        @if(!$isRead)
                                            <span class="badge badge-warning ml-2" style="font-size: 0.8em; padding: 0.4em 0.7em;">नयाँ</span>
                                        @endif
                                    </h5>
                                    <small>
                                        @if($circular->published_at)
                                            {{ $circular->published_at->format('Y-m-d') }}
                                        @else
                                            {{ $circular->created_at->format('Y-m-d') }}
                                        @endif
                                    </small>
                                </div>
                                <p class="mb-1">{{ Str::limit($circular->content, 150) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <strong>संस्था:</strong> {{ $circular->organization->name }}
                                        @if($circular->hostel)
                                            | <strong>होस्टेल:</strong> {{ $circular->hostel->name }}
                                        @endif
                                    </small>
                                    <small>
                                        @if($isRead)
                                            <span class="badge badge-success" style="font-size: 0.8em; padding: 0.4em 0.7em; background-color: #28a745 !important;">
                                                <i class="fas fa-check mr-1"></i>पढिसक्नुभएको
                                            </span>
                                        @else
                                            <span class="badge badge-secondary" style="font-size: 0.8em; padding: 0.4em 0.7em; background-color: #6c757d !important;">
                                                <i class="fas fa-envelope mr-1"></i>नपढेको
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </a>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                तपाईंलाई अहिलेसम्म कुनै पनि सूचना पठाइएको छैन<br>
                                <small class="text-muted">नयाँ सूचनाहरू यहाँ देखिनेछन्</small>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $circulars->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional CSS to ensure badge visibility */
.badge {
    font-weight: 600;
    color: white !important;
}

.list-group-item .badge {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
<style>
/* =========================================== */
/* FIXES FOR मेरा सूचनाहरू PAGE - WHITE TEXT & ATTRACTIVE */
/* =========================================== */

/* 1. Card Header - White Text */
.card-header.bg-primary.text-white,
.card-header.bg-primary.text-white h3,
.card-header.bg-primary.text-white i {
    color: white !important;
}

.card-header.bg-primary.text-white .card-title {
    color: white !important;
}

/* 2. Info Boxes - White Text & Attractive */
.info-box {
    min-height: 100px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    padding: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    align-items: center;
}

.info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.info-box.bg-info {
    background: linear-gradient(45deg, #17a2b8, #0f7c8f) !important;
}

.info-box.bg-success {
    background: linear-gradient(45deg, #28a745, #1e7e34) !important;
}

.info-box.bg-warning {
    background: linear-gradient(45deg, #ffc107, #d39e00) !important;
}

.info-box.bg-danger {
    background: linear-gradient(45deg, #dc3545, #bd2130) !important;
}

.info-box-icon {
    font-size: 2.5rem;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    margin-right: 15px;
    color: white !important;
}

.info-box-icon i {
    color: white !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.info-box-content {
    flex: 1;
}

.info-box-text {
    font-size: 14px;
    font-weight: 500;
    color: rgba(255,255,255,0.9) !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-box-number {
    font-size: 28px;
    font-weight: 700;
    color: white !important;
    line-height: 1.2;
    text-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Override any global dark text */
.info-box *,
.info-box-icon *,
.info-box-content * {
    color: white !important;
}

/* 3. Buttons - White Text */
.btn-primary,
.btn-secondary {
    color: white !important;
    font-weight: 500;
    padding: 8px 20px;
    border-radius: 30px;
    transition: all 0.3s;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #007bff) !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,123,255,0.3);
}

.btn-secondary {
    background: linear-gradient(45deg, #6c757d, #545b62) !important;
    border: none;
}

.btn-secondary:hover {
    background: linear-gradient(45deg, #545b62, #6c757d) !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108,117,125,0.3);
}

.btn i {
    color: white !important;
}

/* 4. Filter/Search Form Elements - Better Styling */
.form-control-sm {
    border-radius: 20px;
    padding: 8px 15px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
}

.form-control-sm:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

select.form-control-sm {
    cursor: pointer;
}

/* 5. Empty State Styling */
.text-center.text-muted {
    color: #6c757d !important;
}

.text-center.text-muted i {
    color: #adb5bd !important;
}

/* 6. Pagination Styling - Optional */
.pagination .page-link {
    color: #007bff;
    border-radius: 30px;
    margin: 0 3px;
    transition: all 0.3s;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-color: #007bff;
    color: white;
    box-shadow: 0 3px 8px rgba(0,123,255,0.3);
}

.pagination .page-link:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
}
</style>
@endsection
