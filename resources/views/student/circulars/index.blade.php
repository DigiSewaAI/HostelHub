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
@endsection
