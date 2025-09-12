@extends('layouts.app')

@section('title', 'भुक्तानी प्रतिवेदन')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 nepali">भुक्तानी प्रतिवेदन</h1>
        <div>
            @role(['admin', 'owner'])
            <a href="{{ route('payments.export') }}" class="btn btn-primary me-2 nepali">
                <i class="fas fa-file-export me-1"></i> निर्यात गर्नुहोस्
            </a>
            @endrole
            <a href="{{ route('payments.index') }}" class="btn btn-secondary nepali">
                <i class="fas fa-arrow-left me-1"></i> भुक्तानीहरूमा फर्कनुहोस्
            </a>
        </div>
    </div>

    <!-- मिति फिल्टर फारम -->
    @role(['admin', 'owner'])
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0 nepali">प्रतिवेदन फिल्टर गर्नुहोस्</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('payments.report') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label nepali">सुरु मिति</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label nepali">अन्त्य मिति</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ request('end_date', now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 nepali">
                            <i class="fas fa-filter me-1"></i> फिल्टर लागू गर्नुहोस्
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endrole

    <!-- प्रतिवेदन सारांश -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary nepali">कुल रकम</h5>
                    <p class="card-text fs-3 fw-bold">रु {{ number_format($totalAmount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title text-success nepali">पूर्ण भुक्तानीहरू</h5>
                    <p class="card-text fs-3 fw-bold">{{ $completedCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning nepali">प्रतीक्षामा भुक्तानीहरू</h5>
                    <p class="card-text fs-3 fw-bold">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- भुक्तानी तालिका -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0 nepali">भुक्तानी विवरण 
                @role(['admin', 'owner'])
                ({{ $startDate }} देखि {{ $endDate }} सम्म)
                @else
                (तपाइँको भुक्तानीहरू)
                @endrole
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="nepali">आईडी</th>
                            @role(['admin', 'owner'])
                            <th class="nepali">विद्यार्थी</th>
                            <th class="nepali">होस्टल</th>
                            @endrole
                            <th class="nepali">रकम</th>
                            <th class="nepali">मिति</th>
                            <th class="nepali">विधि</th>
                            <th class="nepali">स्थिति</th>
                            @role(['admin', 'owner'])
                            <th class="nepali">लेनदेन आईडी</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            @role(['admin', 'owner'])
                            <td>{{ $payment->student->name ?? 'उपलब्ध छैन' }}</td>
                            <td>{{ $payment->hostel->name ?? 'उपलब्ध छैन' }}</td>
                            @endrole
                            <td>रु {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_date }}</td>
                            <td class="nepali">
                                @if($payment->payment_method === 'cash')
                                    नगद
                                @elseif($payment->payment_method === 'bank_transfer')
                                    बैंक ट्रान्सफर
                                @else
                                    अनलाइन
                                @endif
                            </td>
                            <td>
                                @if($payment->status === 'completed')
                                    <span class="badge bg-success nepali">पूर्ण</span>
                                @elseif($payment->status === 'pending')
                                    <span class="badge bg-warning text-dark nepali">प्रतीक्षामा</span>
                                @else
                                    <span class="badge bg-danger nepali">असफल</span>
                                @endif
                            </td>
                            @role(['admin', 'owner'])
                            <td>{{ $payment->transaction_id ?? 'उपलब्ध छैन' }}</td>
                            @endrole
                        </tr>
                        @empty
                        <tr>
                            <td colspan="@role(['admin', 'owner']) 8 @else 5 @endrole" class="text-center nepali">
                                चयन गरिएको अवधिका लागि कुनै भुक्तानी फेला परेन
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @role(['admin', 'owner'])
            <div class="d-flex justify-content-between mt-4">
                <div class="nepali">
                    देखाइरहेको छ {{ $payments->firstItem() }} देखि {{ $payments->lastItem() }} सम्म, कुल {{ $payments->total() }} मध्ये
                </div>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>
            @endrole
        </div>
    </div>
    
    @role('student')
    <div class="alert alert-info mt-4 nepali">
        <i class="fas fa-info-circle me-2"></i>
        यो प्रतिवेदनमा तपाइँको व्यक्तिगत भुक्तानी इतिहास मात्र देखाइएको छ। थप विस्तृत प्रतिवेदनको लागि प्रशासकसँग सम्पर्क गर्नुहोस्।
    </div>
    @endrole
</div>
@endsection