@extends('layouts.owner')

@section('title', __('network.inbox'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('network.broadcast') }}</li>
@endsection


@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('network.inbox') }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('network.messages.create') }}" class="btn btn-sm btn-primary">
            {{ __('network.compose') }}
        </a>
    </div>
</div>

{{-- फिल्टर --}}
<form method="GET" class="row g-3 mb-4">
    <div class="col-auto">
        <select name="category" class="form-select">
            <option value="">{{ __('network.all_categories') ?? 'सबै श्रेणी' }}</option>
            @foreach(['business_inquiry', 'partnership', 'hostel_sale', 'emergency', 'general'] as $cat)
                <option value="{{ $cat }}" @selected(request('category')==$cat)>
                    {{ __('network.category.' . $cat) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary">{{ __('network.search') }}</button>
    </div>
</form>

{{-- थ्रेड सूची --}}
@forelse($threads as $participant)
    @php $thread = $participant->thread; @endphp
    <div class="card mb-2">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h5 class="card-title">
                    <a href="{{ route('network.messages.show', $thread->id) }}">
                        {{ $thread->subject ?? __('network.direct_message') }}
                    </a>
                    @if($participant->last_read_at < $thread->last_message_at)
                        <span class="badge bg-primary">{{ __('network.new') }}</span>
                    @endif
                </h5>
                <small>{{ $thread->last_message_at?->diffForHumans() }}</small>
            </div>
            <p class="card-text">
                {{ $thread->messages->first()->body ?? '' }}
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
    <div class="text-center py-5">
        <i class="fas fa-envelope fa-4x text-muted mb-3"></i>
        <h4>{{ __('network.no_messages') ?? 'कुनै सन्देश छैन।' }}</h4>
        <p class="text-muted">{{ __('network.no_messages_desc') ?? 'तपाईंको इनबक्स खाली छ।' }}</p>
        <a href="{{ route('network.messages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('network.compose') }}
        </a>
    </div>
@endforelse

{{ $threads->links() }}
@endsection