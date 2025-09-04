@extends('layouts.admin')

@section('title', 'भुक्तानी प्रतिवेदन')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">भुक्तानी प्रतिवेदन</h1>
        <div>
            <a href="{{ route('admin.payments.export') }}" class="btn btn-primary me-2">
                <i class="fas fa-file-export me-1"></i> निर्यात गर्नुहोस्
            </a>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> भुक्तानीहरूमा फर्कनुहोस्
            </a>
        </div>
    </div>

    <!-- मिति फिल्टर फारम -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">प्रतिवेदन फिल्टर गर्नुहोस्</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.report') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">सुरु मिति</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">अन्त्य मिति</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ request('end_date', now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> फिल्टर लागू गर्नुहोस्
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- प्रतिवेदन सारांश -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">कुल रकम</h5>
                    <p class="card-text fs-3 fw-bold">रु {{ number_format($totalAmount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title text-success">पूर्ण भुक्तानीहरू</h5>
                    <p class="card-text fs-3 fw-bold">{{ $completedCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">प्रतीक्षामा भुक्तानीहरू</h5>
                    <p class="card-text fs-3 fw-bold">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- भुक्तानी तालिका -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">भुक्तानी विवरण ({{ $startDate }} देखि {{ $endDate }} सम्म)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>आईडी</th>
                            <th>विद्यार्थी</th>
                            <th>होस्टल</th>
                            <th>रकम</th>
                            <th>मिति</th>
                            <th>विधि</th>
                            <th>स्थिति</th>
                            <th>लेनदेन आईडी</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->student->name ?? 'उपलब्ध छैन' }}</td>
                            <td>{{ $payment->hostel->name ?? 'उपलब्ध छैन' }}</td>
                            <td>रु {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_date }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>
                                @if($payment->status === 'completed')
                                    <span class="badge bg-success">पूर्ण</span>
                                @elseif($payment->status === 'pending')
                                    <span class="badge bg-warning text-dark">प्रतीक्षामा</span>
                                @else
                                    <span class="badge bg-danger">असफल</span>
                                @endif
                            </td>
                            <td>{{ $payment->transaction_id ?? 'उपलब्ध छैन' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">चयन गरिएको अवधिका लागि कुनै भुक्तानी फेला परेन</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection