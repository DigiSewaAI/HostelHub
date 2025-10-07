@extends('layouts.admin')

@section('title', 'पेन्डिङ बुकिंगहरू')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">पेन्डिङ बुकिंगहरू</h1>
        <span class="badge badge-warning badge-pill">{{ $pendingBookings->total() }} पेन्डिङ</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">फिल्टर गर्नुहोस्</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bookings.pending') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label nepali">होस्टेल</label>
                        <select class="form-select nepali" name="hostel_id">
                            <option value="">सबै होस्टेलहरू</option>
                            @foreach($hostels as $hostel)
                            <option value="{{ $hostel->id }}" {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                {{ $hostel->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label nepali">मिति सीमा</label>
                        <input type="text" class="form-control date-range nepali" name="date_range"
                               value="{{ request('date_range', now()->subDays(7)->format('Y-m-d') . ' to ' . now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary nepali">
                                <i class="fas fa-filter me-1"></i> फिल्टर
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($pendingBookings->count() > 0)
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">पेन्डिङ बुकिंगहरू</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="nepali">आईडी</th>
                            <th class="nepali">विद्यार्थी</th>
                            <th class="nepali">होस्टेल</th>
                            <th class="nepali">कोठा</th>
                            <th class="nepali">चेक-इन</th>
                            <th class="nepali">चेक-आउट</th>
                            <th class="nepali">उद्देश्य</th>
                            <th class="nepali">मिति</th>
                            <th class="nepali text-center">कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingBookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="nepali">{{ $booking->student->name }}</div>
                                        <small class="text-muted">{{ $booking->student->mobile }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $booking->room->hostel->name }}</td>
                            <td>{{ $booking->room->room_number }}</td>
                            <td>{{ $booking->check_in_date->format('Y-m-d') }}</td>
                            <td>{{ $booking->check_out_date->format('Y-m-d') }}</td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                      title="{{ $booking->purpose }}">
                                    {{ $booking->purpose }}
                                </span>
                            </td>
                            <td>{{ $booking->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-success btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#approveModal{{ $booking->id }}"
                                            title="स्वीकृत गर्नुहोस्">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    
                                    <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $booking->id }}"
                                            title="अस्वीकृत गर्नुहोस्">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    
                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                       class="btn btn-info btn-sm"
                                       title="विवरण हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $booking->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title nepali">बुकिंग स्वीकृत गर्नुहोस्</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="nepali">के तपाईं बुकिंग #{{ $booking->id }} लाई स्वीकृत गर्न निश्चित हुनुहुन्छ?</p>
                                                <p><strong class="nepali">विद्यार्थी:</strong> {{ $booking->student->name }}</p>
                                                <p><strong class="nepali">होस्टेल:</strong> {{ $booking->room->hostel->name }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary nepali" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                                                <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success nepali">स्वीकृत गर्नुहोस्</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $booking->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title nepali">बुकिंग अस्वीकृत गर्नुहोस्</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="nepali">के तपाईं बुकिंग #{{ $booking->id }} लाई अस्वीकृत गर्न निश्चित हुनुहुन्छ?</p>
                                                <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" id="rejectForm{{ $booking->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="reason{{ $booking->id }}" class="form-label nepali">कारण (वैकल्पिक)</label>
                                                        <textarea class="form-control" id="reason{{ $booking->id }}" name="reason" rows="3" placeholder="अस्वीकृतको कारण लेख्नुहोस्..."></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary nepali" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                                                <button type="submit" form="rejectForm{{ $booking->id }}" class="btn btn-danger nepali">अस्वीकृत गर्नुहोस्</button>
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

            <div class="d-flex justify-content-between mt-3">
                <div class="nepali">
                    देखाइरहेको छ {{ $pendingBookings->firstItem() }} देखि {{ $pendingBookings->lastItem() }} सम्म, कुल {{ $pendingBookings->total() }} मध्ये
                </div>
                <div>
                    {{ $pendingBookings->links() }}
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
        <h4 class="text-gray-500 nepali">कुनै पेन्डिङ बुकिंग छैन</h4>
        <p class="text-muted nepali">अहिले कुनै नयाँ बुकिंग अनुरोध छैन।</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date range picker
        $('.date-range').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                separator: ' to ',
                applyLabel: 'लागू गर्नुहोस्',
                cancelLabel: 'रद्द गर्नुहोस्',
                customRangeLabel: 'कस्टम'
            }
        });
    });
</script>
@endpush