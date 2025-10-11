@extends('layouts.owner')

@section('title', $hostel->name . ' - विवरण')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $hostel->name }} को विवरण</h5>
                    <div>
                        <a href="{{ route('owner.hostels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> होस्टल सूची
                        </a>
                        <a href="{{ route('owner.hostels.edit', $hostel) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">होस्टलको नाम</h6>
                                    <p class="fs-5">{{ $hostel->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">स्थिति</h6>
                                    <span class="badge bg-{{ $hostel->status == 'active' ? 'success' : 'danger' }}">
                                        {{ $hostel->status == 'active' ? 'सक्रिय' : 'निष्क्रिय' }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-muted">ठेगाना</h6>
                                    <p>{{ $hostel->address }}, {{ $hostel->city }}</p>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">सम्पर्क व्यक्ति</h6>
                                    <p>{{ $hostel->contact_person }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">सम्पर्क फोन</h6>
                                    <p>{{ $hostel->contact_phone }}</p>
                                </div>
                            </div>

                            @if($hostel->contact_email)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-muted">इमेल</h6>
                                    <p>{{ $hostel->contact_email }}</p>
                                </div>
                            </div>
                            @endif

                            @if($hostel->description)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-muted">विवरण</h6>
                                    <p>{{ $hostel->description }}</p>
                                </div>
                            </div>
                            @endif

                            @if($hostel->facilities)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-muted">सुविधाहरू</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach(json_decode($hostel->facilities) as $facility)
                                            <span class="badge bg-info">{{ $facility }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            @if($hostel->image)
                                <img src="{{ asset('storage/' . $hostel->image) }}" alt="{{ $hostel->name }}" class="img-fluid rounded">
                            @else
                                <div class="text-center text-muted py-5 border rounded">
                                    <i class="fas fa-image fa-3x mb-3"></i>
                                    <p>कुनै छवि उपलब्ध छैन</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $hostel->rooms_count ?? $hostel->rooms->count() }}</h4>
                            <p class="mb-0">कुल कोठाहरू</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-door-open fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $availableRooms }}</h4>
                            <p class="mb-0">खाली कोठाहरू</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bed fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $occupiedRooms }}</h4>
                            <p class="mb-0">भएका कोठाहरू</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalStudents }}</h4>
                            <p class="mb-0">कुल विद्यार्थी</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="{{ route('owner.hostels.edit', $hostel) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> होस्टल सम्पादन गर्नुहोस्
                        </a>
                        
                        {{-- Rooms management button - यदि route छ भने मात्र देखाउने --}}
                        @if(Route::has('owner.rooms.index'))
                        <a href="{{ route('owner.rooms.index', ['hostel' => $hostel->id]) }}" class="btn btn-success">
                            <i class="fas fa-door-open"></i> कोठाहरू व्यवस्थापन गर्नुहोस्
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection