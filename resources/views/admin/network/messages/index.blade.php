@extends('layouts.admin')

@section('title', 'सन्देश अनुगमन')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">सन्देश थ्रेडहरू</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">सबै थ्रेड</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>विषय</th>
                            <th>सहभागीहरू</th>
                            <th>अन्तिम सन्देश</th>
                            <th>मिति</th>
                            <th>कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($threads as $thread)
                        <tr>
                            <td>{{ $thread->subject ?? '(कुनै विषय छैन)' }}</td>
                            <td>
                                @foreach($thread->participants as $participant)
                                    {{ $participant->user->name }}@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>{{ Str::limit($thread->latestMessage->body ?? '', 50) }}</td>
                            <td>{{ $thread->last_message_at ? $thread->last_message_at->diffForHumans() : $thread->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.network.messages.show', $thread) }}" class="btn btn-sm btn-info">हेर्नुहोस्</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">कुनै सन्देश थ्रेड छैन।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</div>
@endsection