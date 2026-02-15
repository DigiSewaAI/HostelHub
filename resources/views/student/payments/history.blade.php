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

    <!-- Main Content Row -->
    <div class="row">
        <!-- Payments List Column -->
        <div class="col-lg-8 mb-4">
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
                                                    @if($payment->room)
                                                    <i class="fas fa-bed me-1"></i>
                                                    कोठा {{ $payment->room->room_number ?? 'N/A' }}
                                                    @if($payment->room->hostel)
                                                        ({{ $payment->room->hostel->name ?? '' }})
                                                    @endif
                                                @elseif($payment->booking && $payment->booking->room)
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
                                                        {{-- 🔥 NEW: Initial Payment Badge --}}
                                                        @if($payment->payment_type == 'initial')
                                                            <span class="badge bg-info mt-1">प्रारम्भिक</span>
                                                        @endif

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
                                                   class="btn btn-outline-primary tooltip-btn" 
                                                   title="रसिद डाउनलोड गर्नुहोस्"
                                                   target="_blank"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-download"></i>
                                                </a>

                                                <button type="button" 
                                                        class="btn btn-outline-info tooltip-btn" 
                                                        title="विवरण हेर्नुहोस्"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#paymentModal{{ $payment->id }}"
                                                        data-bs-toggle="tooltip">
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
                                                            
                                                            @if($payment->room || $payment->booking)
                                                                <div class="mt-4 pt-3 border-top">
                                                                    <h6 class="text-muted mb-3">
                                                                        <i class="fas fa-bed me-2"></i>बुकिंग जानकारी
                                                                    </h6>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <small class="text-muted d-block">कोठा:</small>
                                                                            <strong>
                                                                                @if($payment->room)
                                                                                    {{ $payment->room->room_number ?? 'N/A' }}
                                                                                @elseif($payment->booking && $payment->booking->room)
                                                                                    {{ $payment->booking->room->room_number ?? 'N/A' }}
                                                                                @else
                                                                                    N/A
                                                                                @endif
                                                                            </strong>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <small class="text-muted d-block">होस्टल:</small>
                                                                            <strong>
                                                                                @if($payment->room && $payment->room->hostel)
                                                                                    {{ $payment->room->hostel->name ?? 'N/A' }}
                                                                                @elseif($payment->booking && $payment->booking->room && $payment->booking->room->hostel)
                                                                                    {{ $payment->booking->room->hostel->name ?? 'N/A' }}
                                                                                @else
                                                                                    N/A
                                                                                @endif
                                                                            </strong>
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
                                <a href="{{ route('student.bookings.index') ?? '#' }}" class="btn btn-outline-primary">
                                    <i class="fas fa-bed me-2"></i> कोठा बुक गर्नुहोस्
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Payment Methods & Quick Actions -->
        <div class="col-lg-4 mb-4">
            <!-- Payment Methods Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-university me-2"></i>
                        भुक्तानी गर्ने विधिहरू
                    </h5>
                </div>
                <div class="card-body">
                    @if($paymentMethods->isNotEmpty())
                        <div class="mb-3">
                            <h6 class="text-muted mb-3">तपाईंको होस्टेल: <strong>{{ $hostel->name ?? 'N/A' }}</strong></h6>
                            
                            @foreach($paymentMethods as $method)
                                <div class="card mb-3 border-start border-{{ 
                                    $method->type == 'bank' ? 'primary' : 
                                    (in_array($method->type, ['esewa', 'khalti']) ? 'success' : 'warning') 
                                }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="card-title mb-0" style="font-size: 0.9rem;">
                                                <i class="fas 
                                                    @if($method->type == 'bank') fa-university text-primary 
                                                    @elseif($method->type == 'esewa') fa-mobile-alt text-success
                                                    @elseif($method->type == 'khalti') fa-wallet text-info
                                                    @elseif($method->type == 'cash') fa-money-bill text-warning
                                                    @else fa-credit-card text-secondary @endif
                                                    me-2">
                                                </i>
                                                {{ $method->title }}
                                            </h6>
                                            @if($method->is_default)
                                                <span class="badge bg-warning" style="font-size: 0.7rem;">मुख्य</span>
                                            @endif
                                        </div>
                                        
                                        <div class="small text-muted">
                                            @if($method->type == 'bank')
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">बैंक:</span>
                                                    <strong style="font-size: 0.85rem;">{{ $method->bank_name ?? 'N/A' }}</strong>
                                                </div>
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">खाता नम्बर:</span>
                                                    <strong style="font-size: 0.85rem;">{{ $method->account_number ?? 'N/A' }}</strong>
                                                </div>
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">खाता धनी:</span>
                                                    <strong style="font-size: 0.85rem;">{{ $method->account_name ?? 'N/A' }}</strong>
                                                </div>
                                                @if($method->branch_name)
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">शाखा:</span>
                                                    <strong style="font-size: 0.85rem;">{{ $method->branch_name }}</strong>
                                                </div>
                                                @endif
                                            @elseif(in_array($method->type, ['esewa', 'khalti', 'fonepay', 'imepay']))
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">वालेट:</span>
                                                    <strong style="font-size: 0.85rem;">{{ 
                                                        $method->type == 'esewa' ? 'ईसेवा' : 
                                                        ($method->type == 'khalti' ? 'खल्ती' : 
                                                        ($method->type == 'fonepay' ? 'फोनपे' : 'आईमेपे')) 
                                                    }}</strong>
                                                </div>
                                                @if($method->mobile_number)
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">मोबाइल नम्बर:</span>
                                                    <strong style="font-size: 0.85rem;">{{ $method->mobile_number }}</strong>
                                                </div>
                                                @endif
                                                @if($method->wallet_id)
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">आईडी:</span>
                                                    <strong style="font-size: 0.85rem;">{{ $method->wallet_id }}</strong>
                                                </div>
                                                @endif
                                                @if($method->account_name)
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">नाम:</span>
                                                    <strong style="font-size: 0.85rem;">{{ $method->account_name }}</strong>
                                                </div>
                                                @endif
                                            @elseif($method->type == 'cash')
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">विधि:</span>
                                                    <strong style="font-size: 0.85rem;">नगद भुक्तानी</strong>
                                                </div>
                                                @if($method->additional_info && isset($method->additional_info['location']))
                                                <div class="mb-1">
                                                    <span class="d-block" style="font-size: 0.8rem;">स्थान:</span>
                                                    <strong style="font-size: 0.85rem;">{{ $method->additional_info['location'] }}</strong>
                                                </div>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        @if($method->qr_code_path)
                                            <div class="text-center mt-2">
                                                <p class="small text-muted mb-1" style="font-size: 0.75rem;">QR कोड स्क्यान गर्नुहोस्:</p>
                                                <img src="{{ \media_url($method->qr_code_path) }}" 
                                                     class="img-fluid rounded border" 
                                                     style="max-width: 120px;">
                                            </div>
                                        @endif
                                        
                                        @if($method->additional_info && isset($method->additional_info['instructions']))
                                            <div class="mt-2 pt-2 border-top">
                                                <p class="small text-muted mb-1" style="font-size: 0.75rem;"><strong>निर्देशनहरू:</strong></p>
                                                <ul class="small mb-0 ps-3" style="font-size: 0.75rem;">
                                                    @if(is_array($method->additional_info['instructions']))
                                                        @foreach($method->additional_info['instructions'] as $instruction)
                                                            <li>{{ $instruction }}</li>
                                                        @endforeach
                                                    @else
                                                        <li>{{ $method->additional_info['instructions'] }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="alert alert-info" style="font-size: 0.85rem;">
                            <i class="fas fa-info-circle"></i>
                            <strong>नोट:</strong> भुक्तानी गरेपछि रसिद होस्टेल कार्यालयमा पेश गर्न नबिर्सनुहोस्।
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-credit-card fa-2x text-muted mb-3"></i>
                            <h6 class="text-muted">भुक्तानी विवरण</h6>
                            
                            @if($hostel)
                                <p class="small text-muted mb-3">
                                    भुक्तानी गर्नका लागि होस्टेल कार्यालयमा सम्पर्क गर्नुहोस्।
                                </p>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1 small">
                                        <strong>फोन:</strong> {{ $hostel->contact_phone_formatted ?? 'N/A' }}
                                    </p>
                                    <p class="mb-0 small">
                                        <strong>इमेल:</strong> {{ $hostel->contact_email_formatted ?? 'N/A' }}
                                    </p>
                                </div>
                            @else
                                <p class="small text-muted">
                                    तपाईंको होस्टेल भुक्तानी विधिहरू सेटअप गर्नुभएको छैन।
                                    <br>कृपया होस्टेल प्रबन्धकसँग सम्पर्क गर्नुहोस्।
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        द्रुत कार्यहरू
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($hostel && $paymentMethods->isNotEmpty())
                            <a href="#" onclick="alert('यो सुविधा चाँडै उपलब्ध हुनेछ।')" 
                               class="btn btn-outline-success tooltip-btn" 
                               title="बैंक हस्तान्तरण गर्नुहोस्"
                               data-bs-toggle="tooltip">
                                <i class="fas fa-bank me-2"></i> बैंक हस्तान्तरण गर्नुहोस्
                            </a>
                        @endif
                        
                        <a href="{{ route('student.payments.index') }}" 
                           class="btn btn-outline-info tooltip-btn" 
                           title="सबै भुक्तानी हेर्नुहोस्"
                           data-bs-toggle="tooltip">
                            <i class="fas fa-history me-2"></i> सबै भुक्तानी हेर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>
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
                        <li class="mb-2">{{ $contactMessage ?? 'भुक्तानी सम्बन्धी जानकारीका लागि होस्टल कार्यालयमा सम्पर्क गर्नुहोस्' }}</li>
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

<!-- Help Modal -->
<div class="modal fade" id="paymentHelpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">भुक्तानी सम्बन्धी मद्दत</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>समस्या समाधान:</h6>
                <ul>
                    <li>भुक्तानी गर्न कठिनाई भएमा होस्टेल कार्यालयमा जानुहोस्</li>
                    <li>भुक्तानी रसिद नभएमा प्रबन्धकलाई दिखाउनुहोस्</li>
                    <li>भुक्तानी विवरण गलत भएमा तुरुन्तै जानकारी गराउनुहोस्</li>
                </ul>
                
                @if(isset($hostel) && $hostel)
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6>सम्पर्क विवरण:</h6>
                        <p class="mb-1">
                            <strong>होस्टेल:</strong> {{ $hostel->name ?? 'N/A' }}
                        </p>
                        <p class="mb-1">
                            <strong>फोन:</strong> {{ $hostel->contact_phone_formatted ?? 'N/A' }}
                        </p>
                        <p class="mb-0">
                            <strong>इमेल:</strong> {{ $hostel->contact_email_formatted ?? 'N/A' }}
                        </p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">बन्द गर्नुहोस्</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.375rem;
        border-left: 4px solid #4e73df;
    }
    
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .empty-state-icon {
        color: #dddfeb;
    }
    
    .card {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
    }
    
    .card-header {
        border-bottom: 1px solid #e3e6f0;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(90deg, #1cc88a 0%, #13855c 100%);
    }
    
    .table th {
        font-weight: 600;
        color: #4e73df;
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.75em;
        font-weight: 600;
        padding: 0.35em 0.65em;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    /* Tooltip Customization - FIX for black text on black background */
    .tooltip {
        --bs-tooltip-bg: #fff;
        --bs-tooltip-color: #000;
    }
    
    .tooltip-inner {
        background-color: #fff !important;
        color: #000 !important;
        border: 1px solid #ddd;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        font-size: 12px;
        padding: 6px 10px;
        max-width: 200px;
    }
    
    .bs-tooltip-top .tooltip-arrow::before {
        border-top-color: #fff !important;
        border-bottom-color: transparent;
    }
    
    .bs-tooltip-bottom .tooltip-arrow::before {
        border-bottom-color: #fff !important;
        border-top-color: transparent;
    }
    
    .bs-tooltip-start .tooltip-arrow::before {
        border-left-color: #fff !important;
        border-right-color: transparent;
    }
    
    .bs-tooltip-end .tooltip-arrow::before {
        border-right-color: #fff !important;
        border-left-color: transparent;
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .tooltip-inner {
            background-color: #333 !important;
            color: #fff !important;
            border-color: #555;
        }
        
        .bs-tooltip-top .tooltip-arrow::before {
            border-top-color: #333 !important;
        }
        
        .bs-tooltip-bottom .tooltip-arrow::before {
            border-bottom-color: #333 !important;
        }
        
        .bs-tooltip-start .tooltip-arrow::before {
            border-left-color: #333 !important;
        }
        
        .bs-tooltip-end .tooltip-arrow::before {
            border-right-color: #333 !important;
        }
    }
    
    /* Tooltip button styling */
    .tooltip-btn {
        position: relative;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Add animation to payment method cards
    $('.card.border-start').hover(
        function() {
            $(this).addClass('shadow-sm');
            $(this).css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).removeClass('shadow-sm');
            $(this).css('transform', 'translateY(0)');
        }
    );

    // Modal animation
    $('.modal').on('show.bs.modal', function () {
        $(this).find('.modal-dialog').css({
            'transform': 'scale(0.9)',
            'transition': 'transform 0.3s ease-out'
        });
    });
    
    $('.modal').on('shown.bs.modal', function () {
        $(this).find('.modal-dialog').css('transform', 'scale(1)');
    });

    // Tooltip initialization with custom options
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            delay: {show: 100, hide: 100},
            placement: 'top'
        });
    });
    
    // Initialize all title attributes as tooltips
    $('[title]').each(function() {
        if (!$(this).attr('data-bs-toggle')) {
            $(this).attr('data-bs-toggle', 'tooltip');
            new bootstrap.Tooltip(this, {
                delay: {show: 100, hide: 100},
                placement: 'top'
            });
        }
    });
});
</script>
@endpush