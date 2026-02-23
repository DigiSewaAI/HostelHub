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

<!-- सहभागीहरू -->
<div class="mb-3">
    <strong>{{ __('network.participants') }}:</strong>
    @foreach($thread->participants as $participant)
        @if($participant->user_id != Auth::id())
            <span class="badge bg-info">{{ $participant->user->name }}</span>
        @endif
    @endforeach
</div>

<!-- सन्देश इतिहास -->
<div class="card mb-4" id="message-container" style="max-height: 500px; overflow-y: auto;">
    <div class="card-body">
        @forelse($thread->messages as $message)
            <div class="mb-3 {{ $message->sender_id == Auth::id() ? 'text-end' : '' }}">
                <div class="d-inline-block p-3 rounded {{ $message->sender_id == Auth::id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 70%;">
                    <div class="fw-bold">{{ $message->sender->name }}</div>
                    <div>{{ $message->body }}</div>
                    <div class="small {{ $message->sender_id == Auth::id() ? 'text-white-50' : 'text-muted' }}">
                        {{ $message->created_at->format('Y-m-d H:i') }}
                        @if($message->category)
                            <span class="badge bg-secondary">{{ __('network.category_' . $message->category) }}</span>
                        @endif
                        @if($message->priority)
                            <span class="badge bg-{{ $message->priority == 'urgent' ? 'danger' : ($message->priority == 'high' ? 'warning' : 'info') }}">
                                {{ __('network.priority_' . $message->priority) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">{{ __('network.no_messages_in_thread') ?? 'यस वार्तालापमा कुनै सन्देश छैन।' }}</p>
            </div>
        @endforelse
    </div>
    @if($thread->messages->count() > 5)
        <div class="text-center mb-2">
            <button onclick="document.getElementById('message-container').scrollTo(0, document.getElementById('message-container').scrollHeight)" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-down"></i> {{ __('network.scroll_to_bottom') }}
            </button>
        </div>
    @endif
</div>

<!-- जवाफ दिने फारम -->
<div class="card">
    <div class="card-header">
        {{ __('network.reply') }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('network.messages.store') }}">
            @csrf
            <input type="hidden" name="thread_id" value="{{ $thread->id }}">

            <div class="mb-3">
                <label for="body" class="form-label">{{ __('network.message') }}</label>
                <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="3" required>{{ old('body') }}</textarea>
                @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category" class="form-label">{{ __('network.category') }}</label>
                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                        @foreach(['business_inquiry', 'partnership', 'hostel_sale', 'emergency', 'general'] as $cat)
                            <option value="{{ $cat }}" @selected(old('category', 'general')==$cat)>{{ __('network.category_' . $cat) }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="priority" class="form-label">{{ __('network.priority') }}</label>
                    <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                        @foreach(['low', 'medium', 'high', 'urgent'] as $pri)
                            <option value="{{ $pri }}" @selected(old('priority', 'medium')==$pri)>{{ __('network.priority_' . $pri) }}</option>
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('network.send') }}</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // पेज लोड हुँदा स्वतः तल स्क्रोल गर्न
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('message-container');
        container.scrollTop = container.scrollHeight;
    });
</script>
@endpush

@endsection