@extends('layouts.owner')

@section('title', __('network.inbox'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('network.inbox') }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('network.inbox') }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#composeModal">
            <i class="bi bi-pencil"></i> {{ __('network.compose') }}
        </a>
    </div>
</div>

{{-- Filters (unchanged from original) --}}
<form method="GET" class="row g-3 mb-4">
    <div class="col-auto">
        <select name="category" class="form-select">
            <option value="">{{ __('network.all_categories') }}</option>
            @foreach(['business_inquiry', 'partnership', 'hostel_sale', 'emergency', 'general'] as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                    {{ __('network.' . $cat) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary">{{ __('network.search') }}</button>
    </div>
</form>

{{-- Modern Tabs with Counters --}}
<ul class="nav nav-tabs mb-4" id="inboxTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'marketplace' ? 'active' : '' }}" 
           href="{{ route('network.messages.index', ['tab' => 'marketplace']) }}">
            🛒 बजार सन्देश
            @if($marketplaceUnread > 0)
                <span class="badge bg-primary ms-1">{{ $marketplaceUnread }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'broadcast' ? 'active' : '' }}" 
           href="{{ route('network.messages.index', ['tab' => 'broadcast']) }}">
            📢 प्रसारण सन्देश
            @if($broadcastUnread > 0)
                <span class="badge bg-primary ms-1">{{ $broadcastUnread }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'direct' ? 'active' : '' }}" 
           href="{{ route('network.messages.index', ['tab' => 'direct']) }}">
            💬 प्रत्यक्ष च्याट
            @if($directUnread > 0)
                <span class="badge bg-primary ms-1">{{ $directUnread }}</span>
            @endif
        </a>
    </li>
</ul>

{{-- Threads List --}}
<div class="tab-content">
    <div class="tab-pane active">
        @forelse($filteredThreads as $participant)
            @php 
                $thread = $participant->thread;
                // Get the other participant(s) – for now take first non-auth
                $otherUser = $thread->participants->filter(fn($p) => $p->user_id != Auth::id())->first()?->user;
                $hostel = $otherUser?->primary_hostel; // assumes accessor on User model
                $hostelName = $hostel?->name ?? $otherUser?->name ?? __('network.unknown');
                $hostelLogo = $hostel?->logo ? asset('storage/'.$hostel->logo) : null;
                $listingTitle = $thread->subject ?? __('network.direct_message'); // or extract from message if marketplace
                $lastMessage = $thread->latestMessage;
                $unread = $participant->last_read_at < $thread->last_message_at;
            @endphp
            <div class="card mb-2 conversation-row">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        {{-- Hostel Logo / Avatar --}}
                        <div class="flex-shrink-0 me-3">
                            @if($hostelLogo)
                                <img src="{{ $hostelLogo }}" alt="{{ $hostelName }}" class="rounded-circle" width="48" height="48" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:48px; height:48px; font-weight:bold;">
                                    {{ strtoupper(substr($hostelName, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        {{-- Conversation Details --}}
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-truncate">
                                    {{ $hostelName }}
                                    @if($otherUser && $otherUser->name)
                                        <small class="text-muted fw-normal">({{ $otherUser->name }})</small>
                                    @endif
                                </h6>
                                <small class="text-muted">{{ $lastMessage?->created_at?->diffForHumans() }}</small>
                            </div>
                            @if($listingTitle && $listingTitle != __('network.direct_message'))
                                <div class="small text-primary">{{ $listingTitle }}</div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0 text-truncate small text-muted" style="max-width: 70%;">
                                    {{ $lastMessage?->body ?? '' }}
                                </p>
                                @if($unread)
                                    <span class="badge bg-primary rounded-pill">नयाँ</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('network.messages.show', $thread->id) }}" class="stretched-link"></a>
            </div>
        @empty
            <div class="alert alert-info">{{ __('network.no_messages') }}</div>
        @endforelse

        {{ $filteredThreads->links() }}
    </div>
</div>

{{-- Compose Modal (unchanged from original) --}}
<div class="modal fade" id="composeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('network.messages.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('network.compose') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('network.recipient') }}</label>
                        <select name="recipient_id" class="form-select" required>
                            <option value="">{{ __('network.select_recipient') }}</option>
                            @foreach(\App\Models\User::whereHas('hostels', function($q) {
                                    $q->where('status', 'active')->where('is_published', true);
                                })->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('network.subject') }}</label>
                        <input type="text" name="subject" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('network.message') }}</label>
                        <textarea name="body" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('network.category') }}</label>
                            <select name="category" class="form-select" required>
                                @foreach(['business_inquiry', 'partnership', 'hostel_sale', 'emergency', 'general'] as $cat)
                                    <option value="{{ $cat }}">{{ __("network.{$cat}") }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('network.priority') }}</label>
                            <select name="priority" class="form-select" required>
                                @foreach(['low', 'medium', 'high', 'urgent'] as $pri)
                                    <option value="{{ $pri }}">{{ __("network.priority.{$pri}") }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('network.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('network.send') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection