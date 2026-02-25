@extends('layouts.admin')

@section('title', 'सन्देश थ्रेड')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">थ्रेड: {{ $thread->subject ?? 'कुनै विषय छैन' }}</h1>
        <a href="{{ route('admin.network.messages.index') }}" class="btn btn-secondary">पछाडि</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">सन्देशहरू</h6>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @foreach($thread->messages as $message)
                    <div class="mb-3 p-3 {{ $message->sender_id == auth()->id() ? 'bg-light' : 'bg-white' }} rounded border">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $message->sender->name }}</strong>
                            <small class="text-muted">{{ $message->created_at->format('Y-m-d H:i') }}</small>
                        </div>
                        <div class="mt-2">
                            {!! nl2br(e($message->body)) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">सहभागीहरू</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($thread->participants as $participant)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $participant->user->name }}
                            <span class="badge badge-primary badge-pill">{{ $participant->user->email }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Optional: Block thread -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">कार्य</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.network.messages.block', $thread) }}" method="POST" onsubmit="return confirm('के तपाईं यो थ्रेड हटाउन चाहनुहुन्छ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">थ्रेड हटाउनुहोस्</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection