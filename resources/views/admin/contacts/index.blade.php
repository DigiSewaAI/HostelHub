@extends('layouts.admin')

@section('title', 'सम्पर्क व्यवस्थापन')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-envelope me-2"></i> सम्पर्क सन्देशहरू
                </h2>
                <a href="{{ route('admin.contacts.create') }}" class="btn btn-success btn-lg shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> नयाँ सम्पर्क थप्नुहोस्
                </a>
            </div>
            <p class="text-muted">यहाँ तपाईंले आएका सम्पर्क सन्देशहरू व्यवस्थापन गर्न सक्नुहुन्छ।</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 text-secondary">
                            <i class="fas fa-list me-2"></i> सम्पर्क सूची
                        </h5>

                        <!-- Search Form -->
                        <form action="{{ route('admin.contacts.search') }}" method="GET" class="input-group" style="max-width: 300px;">
                            <input type="text" name="search" class="form-control" placeholder="नाम, इमेल वा विषय खोज्नुहोस्..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 5%;">क्र.स.</th>
                                    <th><i class="fas fa-user me-1"></i> नाम</th>
                                    <th><i class="fas fa-envelope me-1"></i> इमेल</th>
                                    <th><i class="fas fa-phone me-1"></i> फोन</th>
                                    <th><i class="fas fa-tag me-1"></i> विषय</th>
                                    <th><i class="fas fa-info-circle me-1"></i> स्थिति</th>
                                    <th class="text-center" style="width: 15%;"><i class="fas fa-cogs me-1"></i> क्रियाहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submissions as $submission)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>{{ $submission->name }}</td>
                                    <td><a href="mailto:{{ $submission->email }}" class="text-decoration-none">{{ $submission->email }}</a></td>
                                    <td>{{ $submission->phone ?? '—' }}</td>
                                    <td>{{ Str::limit($submission->subject, 30) }}</td>
                                    <td>
                                        @if($submission->status == 'नयाँ')
                                            <span class="badge bg-warning text-dark">नयाँ</span>
                                        @elseif($submission->status == 'पढियो')
                                            <span class="badge bg-info">पढियो</span>
                                        @elseif($submission->status == 'जवाफ दिइयो')
                                            <span class="badge bg-success">जवाफ दिइयो</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $submission->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.contacts.show', $submission->id) }}" class="btn btn-info btn-sm me-1" title="सन्देश हेर्नुहोस्">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.contacts.edit', $submission->id) }}" class="btn btn-primary btn-sm me-1" title="सम्पादन गर्नुहोस्">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.contacts.destroy', $submission->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="मेटाउनुहोस्"
                                                onclick="return confirm('के तपाईं यो सम्पर्क सन्देश स्थायी रूपमा मेटाउन चाहनुहुन्छ?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted d-block mb-3"></i>
                                        <h5 class="text-muted">कुनै सम्पर्क सन्देश फेला परेन</h5>
                                        <p class="text-muted">तपाईंले नयाँ सम्पर्क थप्न सक्नुहुन्छ वा खोजी गर्न सक्नुहुन्छ।</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($submissions->hasPages())
                        <div class="card-footer bg-white d-flex justify-content-center">
                            {{ $submissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection