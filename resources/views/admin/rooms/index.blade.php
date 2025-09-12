@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">कोठा व्यवस्थापन</h1>
            
            @role('admin|owner')
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>नयाँ कोठा थप्नुहोस्
            </a>
            @endrole
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">कोठाहरूको सूची</h6>
                <form action="{{ route('admin.rooms.search') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                           placeholder="खोज्नुहोस्..." value="{{ request('search') }}">
                    <button class="btn btn-sm btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>क्रम संख्या</th>
                                @role('admin')
                                <th>होस्टल</th>
                                @endrole
                                <th>कोठा नम्बर</th>
                                <th>प्रकार</th>
                                <th>क्षमता</th>
                                <th>मूल्य</th>
                                <th>स्थिति</th>
                                @role('admin|owner')
                                <th class="text-center">कार्यहरू</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    @role('admin')
                                    <td>{{ $room->hostel ? $room->hostel->name : 'N/A' }}</td>
                                    @endrole
                                    <td>{{ $room->room_number }}</td>
                                    <td>
                                        @if($room->type == 'single')
                                            एकल कोठा
                                        @elseif($room->type == 'double')
                                            दुई ब्यक्ति कोठा
                                        @elseif($room->type == 'shared')
                                            साझा कोठा
                                        @else
                                            {{ $room->type }}
                                        @endif
                                    </td>
                                    <td>{{ $room->capacity }}</td>
                                    <td>रु. {{ number_format($room->price) }}</td>
                                    <td>
                                        @if($room->status == 'available' || $room->status == 'उपलब्ध')
                                            <span class="badge bg-success">उपलब्ध</span>
                                        @elseif($room->status == 'occupied' || $room->status == 'बुक भएको')
                                            <span class="badge bg-danger">व्यस्त</span>
                                        @elseif($room->status == 'reserved')
                                            <span class="badge bg-warning text-dark">आरक्षित</span>
                                        @else
                                            <span class="badge bg-secondary">मर्मत सम्भार</span>
                                        @endif
                                    </td>
                                    @role('admin|owner')
                                    <td class="text-center">
                                        <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-sm btn-info me-1" title="हेर्नुहोस्">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-primary me-1" title="सम्पादन गर्नुहोस्">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @role('admin')
                                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('के तपाईं यो कोठा हटाउन चाहनुहुन्छ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="हटाउनुहोस्">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endrole
                                    </td>
                                    @endrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->hasRole('admin') ? '8' : '7' }}" class="text-center">कुनै कोठा फेला परेन</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection