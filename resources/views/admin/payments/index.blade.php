@extends('layouts.admin')

@section('title', 'भुक्तानी प्रबन्धन')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="mb-0">भुक्तानी प्रबन्धन</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary nepali">
                        <i class="fas fa-plus me-2"></i> नयाँ भुक्तानी
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle nepali" type="button" id="reportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export me-1"></i> प्रतिवेदनहरू
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                            <li><a class="dropdown-item nepali" href="{{ route('admin.payments.export', ['format' => 'csv']) }}"><i class="fas fa-file-csv me-1"></i> CSV को रूपमा निर्यात गर्नुहोस्</a></li>
                            <li><a class="dropdown-item nepali" href="{{ route('admin.payments.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel me-1"></i> Excel को रूपमा निर्यात गर्नुहोस्</a></li>
                            <li><a class="dropdown-item nepali" href="{{ route('admin.payments.report') }}"><i class="fas fa-chart-bar me-1"></i> प्रतिवेदन हेर्नुहोस्</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show nepali" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">भुक्तानीहरू फिल्टर गर्नुहोस्</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label nepali">मिति सीमा</label>
                        <input type="text" class="form-control date-range" name="date_range"
                               value="{{ request('date_range', now()->subDays(30)->format('Y-m-d') . ' to ' . now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label nepali">भुक्तानी विधि</label>
                        <select class="form-select nepali" name="method">
                            <option value="">सबै विधिहरू</option>
                            <option value="khalti" {{ request('method') == 'khalti' ? 'selected' : '' }}>खल्ती</option>
                            <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>नगद</option>
                            <option value="bank" {{ request('method') == 'bank' ? 'selected' : '' }}>बैंक हस्तान्तरण</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label nepali">स्थिति</label>
                        <select class="form-select nepali" name="status">
                            <option value="">सबै स्थितिहरू</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>पूर्ण</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>प्रतीक्षामा</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>असफल</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label nepali">विद्यार्थी खोज्नुहोस्</label>
                        <input type="text" class="form-control nepali" name="search"
                               placeholder="विद्यार्थीको नाम वा मोबाइल नम्बरले खोज्नुहोस्"
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary nepali">
                                <i class="fas fa-filter me-1"></i> फिल्टर गर्नुहोस्
                            </button>
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary nepali">
                                <i class="fas fa-sync-alt me-1"></i> रीसेट गर्नुहोस्
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">सबै भुक्तानीहरू</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>आईडी</th>
                            <th>विद्यार्थी</th>
                            <th>रकम</th>
                            <th>विधि</th>
                            <th>लेनदेन आईडी</th>
                            <th>मिति</th>
                            <th>स्थिति</th>
                            <th>कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($payment->student->room && $payment->student->room->image)
                                        <img src="{{ asset('storage/'.$payment->student->room->image) }}"
                                             class="rounded me-2"
                                             width="30"
                                             height="30"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-2" style="width: 30px; height: 30px;"></div>
                                    @endif
                                    <div>
                                        <div>{{ $payment->student->name }}</div>
                                        <small class="text-muted">{{ $payment->student->mobile }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>रु {{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->method === 'khalti' ? 'primary' : ($payment->method === 'cash' ? 'success' : 'info') }}">
                                    {{ $payment->method === 'khalti' ? 'खल्ती' : ($payment->method === 'cash' ? 'नगद' : 'बैंक हस्तान्तरण') }}
                                </span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                      title="{{ $payment->transaction_id }}">
                                    {{ $payment->transaction_id }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, h:i A') }}</td>
                            <td>
                                <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $payment->status === 'completed' ? 'पूर्ण' : ($payment->status === 'pending' ? 'प्रतीक्षामा' : 'असफल') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                       class="btn btn-sm btn-info"
                                       title="विवरण हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($payment->status === 'pending')
                                    <form action="{{ route('admin.payments.updateStatus', $payment) }}"
                                          method="POST"
                                          style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit"
                                                class="btn btn-sm btn-success"
                                                title="पूर्ण गर्नुहोस्"
                                                onclick="return confirm('के तपाईं यो भुक्तानीलाई पूर्ण गर्न निश्चित हुनुहुन्छ?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.payments.destroy', $payment) }}"
                                          method="POST"
                                          class="delete-form"
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="मेट्नुहोस्"
                                                onclick="return confirm('के तपाईं यो भुक्तानी रेकर्ड मेटाउन निश्चित हुनुहुन्छ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center nepali py-4">
                                <i class="fas fa-inbox me-2" style="font-size: 2rem;"></i><br>
                                भुक्तानी रेकर्ड फेला परेन
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <div class="nepali">
                    {{ $payments->firstItem() ?? 0 }} देखि {{ $payments->lastItem() ?? 0 }} सम्मको {{ $payments->total() }} रेकर्डहरू देखाइँदै
                </div>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date range picker with Nepali locale
        $('.date-range').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel: 'लागू गर्नुहोस्',
                cancelLabel: 'रद्द गर्नुहोस्',
                fromLabel: 'देखि',
                toLabel: 'सम्म',
                customRangeLabel: 'मेरो सीमा',
                daysOfWeek: ['आइत', 'सोम', 'मंगल', 'बुध', 'बिही', 'शुक्र', 'शनि'],
                monthNames: ['जनवरी', 'फेब्रुअरी', 'मार्च', 'अप्रिल', 'मे', 'जुन', 'जुलाई', 'अगस्ट', 'सेप्टेम्बर', 'अक्टोबर', 'नोभेम्बर', 'डिसेम्बर'],
            }
        });
        
        // Add Nepali class to date picker elements
        $('.date-range').on('apply.daterangepicker', function() {
            $('.daterangepicker').addClass('nepali');
        });
    });
</script>
@endpush