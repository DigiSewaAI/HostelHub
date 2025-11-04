@extends('layouts.owner')

@section('title', 'विद्यार्थी खोज - बिल तयार गर्नुहोस्')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i>विद्यार्थी खोज परिणाम
                    </h5>
                    <a href="{{ route('owner.payments.report') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>पछाडि
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form action="{{ route('owner.payments.student.search') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" 
                                   placeholder="विद्यार्थीको नाम, इमेल वा आईडी लेख्नुहोस्..." 
                                   value="{{ $query }}" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>खोज्नुहोस्
                            </button>
                        </div>
                    </form>

                    @if($students->count() > 0)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ $students->count() }} विद्यार्थी भेटियो
                        </div>

                        @foreach($students as $student)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        {{ $student->name }}
                                        <small class="text-muted">- {{ $student->email }}</small>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>होस्टल:</strong> {{ $student->hostel->name ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>कोठा:</strong> {{ $student->room->room_number ?? 'N/A' }}
                                        </div>
                                    </div>

                                    @if($student->payments->count() > 0)
                                        <h6 class="border-bottom pb-2">भुक्तानीहरू:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>आईडी</th>
                                                        <th>रकम</th>
                                                        <th>मिति</th>
                                                        <th>विधि</th>
                                                        <th>स्थिति</th>
                                                        <th>कार्यहरू</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($student->payments as $payment)
                                                        <tr>
                                                            <td>#{{ $payment->id }}</td>
                                                            <td>रु {{ number_format($payment->amount, 2) }}</td>
                                                            <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                                            <td>
                                                                <span class="badge bg-info">
                                                                    {{ $payment->getPaymentMethodText() }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if($payment->status == 'completed')
                                                                    <span class="badge bg-success">पूरा भयो</span>
                                                                @elseif($payment->status == 'pending')
                                                                    <span class="badge bg-warning">पेन्डिङ</span>
                                                                @else
                                                                    <span class="badge bg-danger">{{ $payment->status }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="{{ route('owner.payments.bill', $payment->id) }}" 
                                                                       class="btn btn-outline-primary" 
                                                                       title="बिल डाउनलोड गर्नुहोस्">
                                                                        <i class="fas fa-file-invoice"></i> बिल
                                                                    </a>
                                                                    <a href="{{ route('owner.payments.receipt', $payment->id) }}" 
                                                                       class="btn btn-outline-success"
                                                                       title="रसिद डाउनलोड गर्नुहोस्">
                                                                        <i class="fas fa-receipt"></i> रसिद
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            यस विद्यार्थीको कुनै भुक्तानी रेकर्ड भेटिएन।
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <h5>कुनै विद्यार्थी भेटिएन</h5>
                            <p class="mb-0">कृपया फेरि खोज्नुहोस् वा भिन्न खोज शब्द प्रयोग गर्नुहोस्।</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection