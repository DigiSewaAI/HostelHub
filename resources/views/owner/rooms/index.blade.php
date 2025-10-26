@extends('layouts.owner')

@section('title', 'कोठा व्यवस्थापन')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">कोठा व्यवस्थापन</h1>
            
            <a href="{{ route('owner.rooms.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>नयाँ कोठा थप्नुहोस्
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">कोठाहरूको सूची</h6>
                <form action="{{ route('owner.rooms.search') }}" method="GET" class="d-flex">
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
                                <th>फोटो</th>
                                <th>होस्टल</th>
                                <th>कोठा नम्बर</th>
                                <th>प्रकार</th>
                                <th>ग्यालरी श्रेणी</th>
                                <th>क्षमता</th>
                                <th>मूल्य</th>
                                <th>स्थिति</th>
                                <th class="text-center">कार्यहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                                <tr>
                                    <!-- ✅ FIXED: Pagination serial numbers continue across pages -->
                                    <td>{{ $rooms->firstItem() + $loop->index }}</td>
                                    
                                    <!-- ✅ FIXED: Room Image Thumbnail -->
                                    <td class="text-center">
                                        @if($room->has_image)
                                            <img src="{{ $room->image_url }}" 
                                                 alt="Room Image" 
                                                 class="img-thumbnail"
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" 
                                                 alt="No Image" 
                                                 class="img-thumbnail"
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; opacity: 0.6;">
                                        @endif
                                    </td>
                                    
                                    <td>{{ $room->hostel ? $room->hostel->name : 'N/A' }}</td>
                                    <td>{{ $room->room_number }}</td>
                                    
                                    <!-- ✅ FIXED: Room Type Display -->
                                    <td>
                                        @if($room->type == '1 seater')
                                            एकल कोठा
                                        @elseif($room->type == '2 seater')
                                            दोहोरो कोठा
                                        @elseif($room->type == '3 seater')
                                            तीन कोठा
                                        @elseif($room->type == '4 seater' || $room->type == 'साझा कोठा')
                                            साझा कोठा
                                        @else
                                            {{ $room->type }}
                                        @endif
                                    </td>
                                    
                                    <!-- ✅ FIXED: Gallery Category Display -->
                                    <td>
                                        @php
                                            $galleryCategories = [
                                                '1 seater' => '१ सिटर कोठा',
                                                '1_seater' => '१ सिटर कोठा',
                                                '2 seater' => '२ सिटर कोठा', 
                                                '2_seater' => '२ सिटर कोठा',
                                                '3 seater' => '३ सिटर कोठा',
                                                '3_seater' => '३ सिटर कोठा',
                                                '4 seater' => '४ सिटर कोठा',
                                                '4_seater' => '४ सिटर कोठा',
                                                'साझा कोठा' => 'साझा कोठा',
                                                'living_room' => 'लिभिङ रूम',
                                                'bathroom' => 'बाथरूम',
                                                'kitchen' => 'भान्सा',
                                                'study_room' => 'अध्ययन कोठा',
                                                'events' => 'कार्यक्रम',
                                                'video_tour' => 'भिडियो टुर'
                                            ];
                                        @endphp
                                        {{ $galleryCategories[$room->gallery_category] ?? $room->gallery_category }}
                                    </td>
                                    
                                    <td>{{ $room->capacity }}</td>
                                    <td>रु. {{ number_format($room->price) }}</td>
                                    
                                    <!-- ✅ FIXED: Status Display - Now properly in Status column -->
                                    <td>
                                        @php
                                            // Status display logic
                                            $status = $room->status;
                                            $available_beds = $room->available_beds ?? ($room->capacity - $room->current_occupancy);
                                            
                                            if ($status === 'maintenance' || $status === 'मर्मत सम्भार') {
                                                $displayStatus = 'मर्मत सम्भार';
                                                $badgeClass = 'bg-secondary text-white';
                                            } elseif ($status === 'occupied' || $status === 'व्यस्त' || $status === 'बुक भएको') {
                                                $displayStatus = 'व्यस्त';
                                                $badgeClass = 'bg-danger text-white';
                                            } elseif ($status === 'partially_available' || $status === 'आंशिक उपलब्ध') {
                                                $displayStatus = 'आंशिक उपलब्ध (' . $available_beds . ' बेड खाली)';
                                                $badgeClass = 'bg-warning text-dark';
                                            } else {
                                                $displayStatus = 'उपलब्ध';
                                                $badgeClass = 'bg-success text-white';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} p-2">
                                            {{ $displayStatus }}
                                        </span>
                                    </td>
                                    
                                    <!-- ✅ FIXED: Actions Column - Only action buttons now -->
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('owner.rooms.show', $room) }}" class="btn btn-sm btn-info me-1" title="हेर्नुहोस्">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('owner.rooms.edit', $room) }}" class="btn btn-sm btn-primary me-1" title="सम्पादन गर्नुहोस्">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('owner.rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('के तपाईं यो कोठा हटाउन चाहनुहुन्छ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="हटाउनुहोस्">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">कुनै कोठा फेला परेन</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($rooms instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-3">
                    {{ $rooms->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection