@extends('layouts.owner')

@section('title', 'होस्टल सन्देशहरू')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-envelope me-2"></i>
                {{ $hostel->name }} - सन्देशहरू
            </h1>
            <p class="text-muted mb-0">होस्टलबाट आएका सम्पर्क सन्देशहरू</p>
        </div>
        <div>
            <a href="{{ route('owner.hostels.show', $hostel) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> होस्टलमा फिर्ता जानुहोस्
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल सन्देशहरू
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMessages }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                नपढिएका सन्देशहरू
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unreadMessages }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                आजका सन्देशहरू
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayMessages }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">सबै सन्देशहरू</h6>
            <div>
                @if($hostel->messages->count() > 0)
                <form action="{{ route('owner.messages.bulk-mark-read') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="message_ids" value="{{ $hostel->messages->pluck('id')->join(',') }}">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-check-double me-1"></i> सबै पढियो चिन्ह लगाउनुहोस्
                    </button>
                </form>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($hostel->messages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="messagesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>नाम</th>
                                <th>ईमेल</th>
                                <th>फोन</th>
                                <th>सन्देश</th>
                                <th>मिति</th>
                                <th>स्थिति</th>
                                <th>कार्यहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hostel->messages as $message)
                            <tr class="{{ $message->status == 'unread' ? 'table-warning' : '' }}">
                                <td>{{ $message->name }}</td>
                                <td>{{ $message->email }}</td>
                                <td>{{ $message->phone ?? 'नभएको' }}</td>
                                <td>{{ Str::limit($message->message, 50) }}</td>
                                <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($message->status == 'unread')
                                        <span class="badge bg-warning text-dark">नपढिएको</span>
                                    @else
                                        <span class="badge bg-success">पढियो</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <!-- View Button -->
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" 
                                                data-bs-target="#messageModal{{ $message->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <!-- Mark as Read -->
                                        @if($message->status == 'unread')
                                        <form action="{{ route('owner.messages.mark-read', $message->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="पढियो चिन्ह लगाउनुहोस्">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('owner.messages.delete', $message->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('के तपाईं यो सन्देश मेटाउन निश्चित हुनुहुन्छ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- Message Modal -->
                                    <div class="modal fade" id="messageModal{{ $message->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">सन्देश विवरण</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>नाम:</strong> {{ $message->name }}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>ईमेल:</strong> {{ $message->email }}
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>फोन:</strong> {{ $message->phone ?? 'नभएको' }}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>मिति:</strong> {{ $message->created_at->format('Y-m-d H:i') }}
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>सन्देश:</strong>
                                                        <div class="border p-3 mt-2 rounded">
                                                            {{ $message->message }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">बन्द गर्नुहोस्</button>
                                                    @if($message->status == 'unread')
                                                    <form action="{{ route('owner.messages.mark-read', $message->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success">पढियो चिन्ह लगाउनुहोस्</button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-envelope-open-text fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">कुनै सन्देश छैन</h4>
                    <p class="text-muted">होस्टलबाट अहिलेसम्म कुनै सम्पर्क सन्देश आएको छैन</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#messagesTable').DataTable({
            order: [[4, 'desc']], // Sort by date descending
            language: {
                search: "खोज्नुहोस्:",
                lengthMenu: "_MENU_ प्रति पृष्ठ देखाउनुहोस्",
                info: "पृष्ठ _PAGE_ को _PAGES_ देखाउँदै",
                paginate: {
                    first: "पहिलो",
                    last: "अन्तिम",
                    next: "अर्को",
                    previous: "अघिल्लो"
                }
            }
        });
    });
</script>
@endpush
@endsection