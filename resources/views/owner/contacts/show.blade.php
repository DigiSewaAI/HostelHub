@extends('layouts.owner')

@section('title', 'सन्देश विवरण')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-envelope-open-text me-2"></i>सन्देश विवरण
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('owner.contacts.index') }}">सम्पर्क सन्देश</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">विवरण</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('owner.contacts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>पछाडि
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Message Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        सन्देश विवरण
                    </h6>
                    <div>
                        @if(!$contact->is_read)
                            <form action="{{ route('owner.contacts.mark-read', $contact) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check me-1"></i>पढिएको चिन्ह लगाउनुहोस्
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">नाम:</h6>
                            <p class="mb-3">{{ $contact->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">इमेल:</h6>
                            <p class="mb-3">
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">फोन नम्बर:</h6>
                            <p class="mb-3">{{ $contact->phone ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">मिति:</h6>
                            <p class="mb-3">{{ $contact->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">होस्टल:</h6>
                            <p class="mb-3">
                                <span class="badge bg-info">
                                    {{ $contact->hostel->name ?? '—' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">कोठा नम्बर:</h6>
                            <p class="mb-3">{{ $contact->room->room_number ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-muted mb-1">विषय:</h6>
                            <p class="mb-3"><strong>{{ $contact->subject }}</strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-1">सन्देश:</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{!! nl2br(e($contact->message)) !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <span class="badge bg-{{ $contact->is_read ? 'success' : 'warning' }}">
                            {{ $contact->is_read ? 'पढियो' : 'नपढिएको' }}
                        </span>
                        <span class="badge bg-info ms-2">
                            स्थिति: {{ $contact->status }}
                        </span>
                    </div>
                    <div>
                        <a href="{{ route('owner.contacts.edit', $contact) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i>सम्पादन
                        </a>
                        <form action="{{ route('owner.contacts.destroy', $contact) }}" 
                              method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('के तपाईं यो सन्देश मेटाउन चाहनुहुन्छ?')">
                                <i class="fas fa-trash me-1"></i>मेटाउनुहोस्
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reply Form (Optional) -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-reply me-1"></i>जवाफ दिनुहोस्
                    </h6>
                </div>
                <div class="card-body">
                    <form action="mailto:{{ $contact->email }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label for="replySubject" class="form-label">विषय:</label>
                            <input type="text" class="form-control" id="replySubject" 
                                   value="Re: {{ $contact->subject }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="replyMessage" class="form-label">जवाफ:</label>
                            <textarea class="form-control" id="replyMessage" rows="5" 
                                      placeholder="आफ्नो जवाफ यहाँ लेख्नुहोस्..."></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" 
                                    onclick="markAsReplied()">
                                <i class="fas fa-check me-1"></i>जवाफ दिइयो चिन्ह लगाउनुहोस्
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>इमेल पठाउनुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-1"></i>स्थिति जानकारी
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            सन्देश आईडी
                            <span class="badge bg-secondary">#{{ $contact->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            सिर्जना मिति
                            <span>{{ $contact->created_at->format('Y-m-d') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            सिर्जना समय
                            <span>{{ $contact->created_at->format('h:i A') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            अद्यावधिक मिति
                            <span>{{ $contact->updated_at->format('Y-m-d H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            स्थिति
                            <span class="badge bg-{{ $contact->is_read ? 'success' : 'warning' }}">
                                {{ $contact->is_read ? 'पढियो' : 'नपढिएको' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-1"></i>द्रुत कार्यहरू
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(!$contact->is_read)
                            <form action="{{ route('owner.contacts.mark-read', $contact) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-check me-1"></i>पढिएको चिन्ह लगाउनुहोस्
                                </button>
                            </form>
                        @else
                            <form action="{{ route('owner.contacts.mark-unread', $contact) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100 mb-2">
                                    <i class="fas fa-envelope me-1"></i>नपढिएको चिन्ह लगाउनुहोस्
                                </button>
                            </form>
                        @endif
                        
                        <a href="tel:{{ $contact->phone }}" class="btn btn-info w-100 mb-2 {{ !$contact->phone ? 'disabled' : '' }}">
                            <i class="fas fa-phone me-1"></i>फोन गर्नुहोस्
                        </a>
                        
                        <a href="mailto:{{ $contact->email }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-paper-plane me-1"></i>इमेल पठाउनुहोस्
                        </a>
                        
                        <a href="{{ route('owner.contacts.edit', $contact) }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-edit me-1"></i>सम्पादन गर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function markAsReplied() {
    if (confirm('के तपाईं यो सन्देशको जवाफ दिइसकेको चिन्ह लगाउन चाहनुहुन्छ?')) {
        fetch('{{ route("owner.contacts.update", $contact) }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: 'replied'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('सन्देश जवाफ दिइयोको रूपमा चिन्ह लगाइयो');
                location.reload();
            }
        });
    }
}

// Delete confirmation
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (!confirm('के तपाईं यो सन्देश मेटाउन निश्चित हुनुहुन्छ?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush