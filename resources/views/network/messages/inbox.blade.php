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

{{-- Filters (unchanged) --}}
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

{{-- Threads List (unchanged) --}}
@forelse($threads as $participant)
    @php $thread = $participant->thread; @endphp
    <div class="card mb-2">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h5 class="card-title">
                    <a href="{{ route('network.messages.show', $thread->id) }}" class="text-decoration-none">
                        {{ $thread->subject ?? __('network.direct_message') }}
                    </a>
                    @if($participant->last_read_at < $thread->last_message_at)
                        <span class="badge bg-primary">{{ __('network.new') }}</span>
                    @endif
                </h5>
                <small class="text-muted">{{ $thread->last_message_at?->diffForHumans() }}</small>
            </div>
            <p class="card-text text-truncate">
                {{ $thread->latestMessage?->body ?? '' }}
            </p>
            <div>
                @foreach($thread->participants as $p)
                    @if($p->user_id != Auth::id())
                        <span class="badge bg-secondary">{{ $p->user->name }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">{{ __('network.no_messages') }}</div>
@endforelse

{{ $threads->links() }}

{{-- Compose Modal (fixed recipient dropdown) --}}
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