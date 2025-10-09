@extends('layouts.admin')

@section('title', 'होस्टल व्यवस्थापन')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="mb-0">होस्टल व्यवस्थापन</h2>
                <a href="{{ route('admin.hostels.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>नयाँ होस्टल
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">सबै होस्टलहरू</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>क्रम संख्या</th>
                            <th>होस्टेलको नाम</th>
                            <th>ठेगाना</th>
                            <th>शहर</th>
                            <th>सम्पर्क</th>
                            <th>कोठाहरू</th>
                            <th>प्रबन्धक</th>
                            <th>स्थिति</th>
                            <th>कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hostels as $hostel)
                        <tr>
                            <td>{{ $hostel->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($hostel->image)
                                        <img src="{{ asset('storage/'.$hostel->image) }}"
                                             class="rounded me-2"
                                             width="40"
                                             height="40"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-building text-muted"></i>
                                        </div>
                                    @endif
                                    <span>{{ $hostel->name }}</span>
                                </div>
                            </td>
                            <td>{{ Str::limit($hostel->address, 20) }}</td>
                            <td>{{ $hostel->city }}</td>
                            <td>{{ $hostel->contact_phone }}</td>
                            <td>
    @php
        $totalRooms = $hostel->rooms_count ?? $hostel->rooms->count();
        $availableRooms = $hostel->rooms->where('status', 'available')->count();
    @endphp
    {{ $totalRooms }} / {{ $availableRooms }} उपलब्ध
</td>

                            <td>
                                @if($hostel->manager)
                                    {{ $hostel->manager->name }}
                                @else
                                    <span class="text-muted">तोकिएको छैन</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $hostel->status === 'active' ? 'bg-success' : ($hostel->status === 'inactive' ? 'bg-secondary' : 'bg-warning') }}">
                                    @if($hostel->status === 'active')
                                        सक्रिय
                                    @elseif($hostel->status === 'inactive')
                                        निष्क्रिय
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $hostel->status)) }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.hostels.show', $hostel) }}"
                                       class="btn btn-sm btn-info"
                                       title="विवरण हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.hostels.edit', $hostel) }}"
                                       class="btn btn-sm btn-warning"
                                       title="सम्पादन गर्नुहोस्">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.hostels.destroy', $hostel) }}"
                                          method="POST"
                                          class="delete-form"
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="हटाउनुहोस्"
                                                onclick="return confirm('के तपाइँ यो होस्टल मेटाउन निश्चित हुनुहुन्छ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $hostels->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('के तपाइँ यो होस्टल मेटाउन निश्चित हुनुहुन्छ? सबै कोठाहरू र सम्बन्धित डाटा हटाइनेछ।')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush