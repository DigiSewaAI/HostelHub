@extends('layouts.owner')

@section('title', $thread->subject ?? __('network.thread'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item"><a href="{{ route('network.messages.index') }}">{{ __('network.inbox') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $thread->subject }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $thread->subject ?? __('network.direct_message') }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form method="POST" action="{{ route('network.messages.archive', $thread->id) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('{{ __('network.archive_confirm') }}')">
                <i class="fas fa-archive"></i> {{ __('network.archive') }}
            </button>
        </form>
        <a href="{{ route('network.messages.index') }}" class="btn btn-sm btn-secondary ms-2">
            <i class="fas fa-arrow-left"></i> {{ __('network.back') }}
        </a>
    </div>
</div>

{{-- Chat Header: Show other participant's hostel info --}}
@php
    $otherParticipant = $thread->participants->filter(fn($p) => $p->user_id != Auth::id())->first();
    $otherUser = $otherParticipant?->user;
    $otherHostel = $otherUser?->primary_hostel;
    $otherHostelName = $otherHostel?->name ?? $otherUser?->name ?? 'अज्ञात';
    $otherHostelLogo = $otherHostel?->logo ? asset('storage/'.$otherHostel->logo) : null;
@endphp
<div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
    <div class="flex-shrink-0 me-3">
        @if($otherHostelLogo)
            <img src="{{ $otherHostelLogo }}" alt="{{ $otherHostelName }}" class="rounded-circle" width="56" height="56" style="object-fit: cover;">
        @else
            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:56px; height:56px; font-weight:bold; font-size:1.5rem;">
                {{ strtoupper(substr($otherHostelName, 0, 1)) }}
            </div>
        @endif
    </div>
    <div>
        <h4 class="mb-0 fw-bold">{{ $otherHostelName }}</h4>
        @if($otherUser && $otherUser->name)
            <small class="text-muted">{{ $otherUser->name }} (मालिक)</small>
        @endif
    </div>
</div>

{{-- Messenger-style Chat Container --}}
<div class="card mb-4" id="message-container" style="height: 60vh; overflow-y: auto; background: #f0f2f5;">
    <div class="card-body d-flex flex-column">
        @forelse($thread->messages as $message)
            @php
                $isMine = $message->sender_id == Auth::id();
                $sender = $message->sender;
                $senderHostel = $sender->primary_hostel;
                $senderName = $senderHostel?->name ?? $sender->name;
                $senderLogo = $senderHostel?->logo ? asset('storage/'.$senderHostel->logo) : null;
            @endphp
            <div class="d-flex {{ $isMine ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                @if(!$isMine)
                    {{-- Other's logo --}}
                    <div class="flex-shrink-0 me-2">
                        @if($senderLogo)
                            <img src="{{ $senderLogo }}" alt="{{ $senderName }}" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:36px; height:36px; font-weight:bold;">
                                {{ strtoupper(substr($senderName, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                @endif
                <div class="d-flex flex-column {{ $isMine ? 'align-items-end' : 'align-items-start' }}" style="max-width: 70%;">
                    {{-- Sender name (only for others) --}}
                    @if(!$isMine)
                        <small class="text-muted ms-1 mb-1">{{ $senderName }}</small>
                    @endif
                    {{-- Bubble --}}
                    <div class="p-3 rounded-3 shadow-sm {{ $isMine ? 'bg-primary text-white' : 'bg-white' }}" style="word-wrap: break-word;">
                        {{ $message->body }}
                    </div>
                    {{-- Timestamp + metadata --}}
                    <div class="d-flex align-items-center mt-1 small {{ $isMine ? 'text-muted' : 'text-muted' }}">
                        <span>{{ $message->created_at->format('H:i A') }}</span>
                        @if($message->category)
                            <span class="badge bg-secondary ms-2">{{ __('network.category_' . $message->category->value) }}</span>
                        @endif
                        @if($message->priority)
                            <span class="badge bg-{{ $message->priority->value == 'urgent' ? 'danger' : ($message->priority->value == 'high' ? 'warning' : 'info') }} ms-2">
                                {{ __('network.priority_' . $message->priority->value) }}
                            </span>
                        @endif
                    </div>
                </div>
                @if($isMine)
                    {{-- Self logo --}}
                    <div class="flex-shrink-0 ms-2">
                        @php $myHostel = Auth::user()->primary_hostel; @endphp
                        @if($myHostel && $myHostel->logo)
                            <img src="{{ asset('storage/'.$myHostel->logo) }}" alt="{{ $myHostel->name }}" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:36px; height:36px; font-weight:bold;">
                                {{ strtoupper(substr($myHostel?->name ?? Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-4">
                <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">{{ __('network.no_messages_in_thread') ?? 'यस वार्तालापमा कुनै सन्देश छैन।' }}</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Sticky Reply Form --}}
<div class="card mt-3 sticky-bottom">
    <div class="card-body">
        <form method="POST" action="{{ route('network.messages.store') }}">
            @csrf
            <input type="hidden" name="thread_id" value="{{ $thread->id }}">

            <div class="mb-3">
                <textarea class="form-control @error('body') is-invalid @enderror" name="body" rows="2" placeholder="सन्देश लेख्नुहोस्..." required>{{ old('body') }}</textarea>
                @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-2">
                <div class="col-md-5">
                    <select class="form-select @error('category') is-invalid @enderror" name="category" required>
                        @foreach(['business_inquiry', 'partnership', 'hostel_sale', 'emergency', 'general'] as $cat)
                            <option value="{{ $cat }}" @selected(old('category', 'general')==$cat)>{{ __('network.category_' . $cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <select class="form-select @error('priority') is-invalid @enderror" name="priority" required>
                        @foreach(['low', 'medium', 'high', 'urgent'] as $pri)
                            <option value="{{ $pri }}" @selected(old('priority', 'medium')==$pri)>{{ __('network.priority_' . $pri) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">{{ __('network.send') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('message-container');
        container.scrollTop = container.scrollHeight;
    });
</script>
@endpush