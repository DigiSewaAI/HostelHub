@extends('network.layouts.app')

@section('title', __('network.my_broadcasts'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('network.my_broadcasts') }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('network.broadcast.create') }}" class="btn btn-sm btn-primary">
            {{ __('network.create_broadcast') }}
        </a>
    </div>
</div>

@if($broadcasts->count())
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('network.subject') }}</th>
                    <th>{{ __('network.status') }}</th>
                    <th>{{ __('network.sent_at') }}</th>
                    <th>{{ __('network.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($broadcasts as $broadcast)
                <tr>
                    <td>{{ $broadcast->subject }}</td>
                    <td>
                        @switch($broadcast->status)
                            @case('pending')
                                <span class="badge bg-warning">{{ __('network.pending') }}</span>
                                @break
                            @case('approved')
                                <span class="badge bg-info">{{ __('network.approved') }}</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger">{{ __('network.rejected') }}</span>
                                @break
                            @case('sent')
                                <span class="badge bg-success">{{ __('network.sent') }}</span>
                                @break
                        @endswitch
                    </td>
                    <td>{{ $broadcast->sent_at ? $broadcast->sent_at->format('Y-m-d H:i') : '-' }}</td>
                    <td>
                        @if($broadcast->status === 'rejected' && $broadcast->moderation_notes)
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#notesModal-{{ $broadcast->id }}">
                                {{ __('network.moderator_notes') }}
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="notesModal-{{ $broadcast->id }}" tabindex="-1" aria-labelledby="notesModalLabel-{{ $broadcast->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="notesModalLabel-{{ $broadcast->id }}">{{ __('network.moderator_notes') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $broadcast->moderation_notes }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('network.close') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $broadcasts->links() }}
@else
    <p>{{ __('network.no_broadcasts') }}</p>
@endif
@endsection