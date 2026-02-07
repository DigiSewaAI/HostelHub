@extends('layouts.student')

@section('title', 'मेरो भुक्तानी इतिहास')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-credit-card text-primary me-2"></i>
                        मेरो भुक्तानी इतिहास
                    </h1>
                    <p class="text-muted mb-0">तपाईंको सबै भुक्तानीहरूको विवरण</p>
                </div>
                <div>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i> ड्यासबोर्डमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल भुक्तानी
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                रु {{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                सफल भुक्तानी
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $payments->where('status', 'completed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                पेन्डिङ भुक्तानी
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $payments->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                कुल रेकर्ड
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $payments->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments List Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i>
                भुक्तानीहरूको सूची
            </h5>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="50">#</th>
                                <th>भुक्तानी विवरण</th>
                                <th>रकम</th>
                                <th>विधि</th>
                                <th>मिति</th>
                                <th>स्थिति</th>
                                <th width="120">कार्यहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $index => $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong class="text-dark">भुक्तानी ID: {{ $payment->id }}</strong>
                                        <small class="text-muted">
                                            @if($payment->booking && $payment->booking->room)
                                                <i class="fas fa-bed me-1"></i>
                                                कोठा {{ $payment->booking->room->room_number ?? 'N/A' }}
                                                @if($payment->booking->room->hostel)
                                                    ({{ $payment->booking->room->hostel->name ?? '' }})
                                                @endif
                                            @else
                                                <i class="fas fa-info-circle me-1"></i>
                                                {{ $payment->getPurposeText() }}
                                            @endif
                                        </small>
                                        @if($payment->transaction_id)
                                            <small class="text-muted">
                                                <i class="fas fa-hashtag me-1"></i>
                                                ट्रान्जेक्सन: {{ $payment->transaction_id }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-success">रु {{ number_format($payment->amount, 2) }}</h6>
                                    <small class="text-muted">{{ $payment->getPurposeText() }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $payment->getPaymentMethodText() }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $payment->payment_date->format('Y-m-d') }}</span>
                                        <small class="text-muted">{{ $payment->payment_date->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($payment->status == 'completed')
                                        <span class="badge bg-success">सफल</span>
                                    @elseif($payment->status == 'pending')
                                        <span class="badge bg-warning">पेन्डिङ</span>
                                    @elseif($payment->status == 'failed')
                                        <span class="badge bg-danger">असफल</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $payment->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('student.payments.receipt.pdf', $payment->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="रसिद डाउनलोड गर्नुहोस्"
                                           target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        <button type="button" 
                                                class="btn btn-outline-info" 
                                                title="विवरण हेर्नुहोस्"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#paymentModal{{ $payment->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>

                                    <!-- Payment Details Modal -->
                                    <div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-credit-card me-2"></i>
                                                        भुक्तानी विवरण
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <table class="table table-borderless">
                                                                <tr>
                                                                    <td class="text-muted">भुक्तानी ID:</td>
                                                                    <td><strong>#{{ $payment->id }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">रकम:</td>
                                                                    <td><strong class="text-success">रु {{ number_format($payment->amount, 2) }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">विधि:</td>
                                                                    <td><span class="badge bg-info">{{ $payment->getPaymentMethodText() }}</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">उद्देश्य:</td>
                                                                    <td>{{ $payment->getPurposeText() }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <table class="table table-borderless">
                                                                <tr>
                                                                    <td class="text-muted">मिति:</td>
                                                                    <td>{{ $payment->payment_date->format('Y-m-d h:i A') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">स्थिति:</td>
                                                                    <td>
                                                                        @if($payment->status == 'completed')
                                                                            <span class="badge bg-success">सफल</span>
                                                                        @elseif($payment->status == 'pending')
                                                                            <span class="badge bg-warning">पेन्डिङ</span>
                                                                        @elseif($payment->status == 'failed')
                                                                            <span class="badge bg-danger">असफल</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">{{ $payment->status }}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @if($payment->transaction_id)
                                                                <tr>
                                                                    <td class="text-muted">ट्रान्जेक्सन ID:</td>
                                                                    <td><code>{{ $payment->transaction_id }}</code></td>
                                                                </tr>
                                                                @endif
                                                                @if($payment->remarks)
                                                                <tr>
                                                                    <td class="text-muted">टिप्पणी:</td>
                                                                    <td>{{ $payment->remarks }}</td>
                                                                </tr>
                                                                @endif
                                                            </table>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($payment->booking)
                                                    <div class="mt-4 pt-3 border-top">
                                                        <h6 class="text-muted mb-3">
                                                            <i class="fas fa-bed me-2"></i>बुकिंग जानकारी
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <small class="text-muted d-block">कोठा:</small>
                                                                <strong>{{ $payment->booking->room->room_number ?? 'N/A' }}</strong>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <small class="text-muted d-block">होस्टल:</small>
                                                                <strong>{{ $payment->booking->room->hostel->name ?? 'N/A' }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">बन्द गर्नुहोस्</button>
                                                    <a href="{{ route('student.payments.receipt', $payment->id) }}" 
                                                       class="btn btn-primary" 
                                                       target="_blank">
                                                        <i class="fas fa-print me-1"></i> रसिद प्रिन्ट गर्नुहोस्
                                                    </a>
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

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        देखाइएको: {{ $payments->firstItem() }} - {{ $payments->lastItem() }} of {{ $payments->total() }}
                    </div>
                    <div>
                        {{ $payments->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-credit-card fa-3x text-muted"></i>
                        </div>
                    </div>
                    <h5 class="text-muted mb-3">कुनै भुक्तानी रेकर्ड भेटिएन</h5>
                    <p class="text-muted mb-4">तपाईंले अहिले सम्म कुनै भुक्तानी गर्नुभएको छैन।</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> ड्यासबोर्डमा फर्कनुहोस्
                        </a>
                        <a href="{{ route('student.booking.index') ?? '#' }}" class="btn btn-outline-primary">
                            <i class="fas fa-bed me-2"></i> कोठा बुक गर्नुहोस्
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Help Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info text-white py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        मद्दत
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 ps-3">
                        <li class="mb-2">{{ $contactMessage }}</li>
                        <li>रसिद डाउनलोड गर्न <i class="fas fa-download text-primary"></i> बटनमा क्लिक गर्नुहोस्</li>
                        <li>भुक्तानी विवरण हेर्न <i class="fas fa-eye text-info"></i> बटनमा क्लिक गर्नुहोस्</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        सुझाव
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 ps-3">
                        <li class="mb-2">भुक्तानी रसिद सधैं सेभ गर्नुहोस्</li>
                        <li>ट्रान्जेक्सन ID रेकर्ड गर्नुहोस्</li>
                        <li>पेन्डिङ भुक्तानीहरूको स्थिति नियमित रूपमा जाँच गर्नुहोस्</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection