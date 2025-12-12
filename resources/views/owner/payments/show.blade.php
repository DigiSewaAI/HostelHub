@extends('layouts.owner')

@section('title', 'भुक्तानी विवरण')

@section('page-description', 'भुक्तानीको पूर्ण विवरण')

@section('header-buttons')
    <a href="{{ route('owner.payments.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>पछाडि
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">भुक्तानी विवरण</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">मूल विवरण</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">भुक्तानी आईडी:</th>
                                    <td>#{{ $payment->id }}</td>
                                </tr>
                                <tr>
                                    <th>विद्यार्थी:</th>
                                    <td>
                                        @if($payment->student)
                                            {{ $payment->student->name }}
                                            <br>
                                            <small class="text-muted">{{ $payment->student->email }}</small>
                                        @else
                                            <span class="text-danger">विद्यार्थी उपलब्ध छैन</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>कोठा:</th>
                                    <td>
                                        @if($payment->room)
                                            कोठा {{ $payment->room->room_number }}
                                        @else
                                            <span class="text-muted">कोठा नभएको (अग्रिम/बाँकी भुक्तानी)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>होस्टल:</th>
                                    <td>
                                        @if($payment->hostel)
                                            {{ $payment->hostel->name }}
                                        @else
                                            <span class="text-muted">होस्टल उपलब्ध छैन</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>रकम:</th>
                                    <td class="fw-bold text-success">रु {{ number_format($payment->amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">भुक्तानी जानकारी</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">भुक्तानी मिति:</th>
                                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>अन्तिम मिति:</th>
                                    <td>
                                        @if($payment->due_date)
                                            {{ $payment->due_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>भुक्तानी विधि:</th>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $payment->payment_method }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>स्थिति:</th>
                                    <td>
                                        @if($payment->status == 'completed')
                                            <span class="badge bg-success">सफल</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge bg-warning text-dark">पेन्डिङ</span>
                                        @else
                                            <span class="badge bg-danger">असफल</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>सिर्जना गरिएको:</th>
                                    <td>
                                        {{ $payment->created_at->format('Y-m-d H:i') }}
                                        @if($payment->createdBy)
                                            <br>
                                            <small class="text-muted">द्वारा: {{ $payment->createdBy->name }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @if($payment->updated_at->ne($payment->created_at))
                                <tr>
                                    <th>अद्यावधिक गरिएको:</th>
                                    <td>
                                        {{ $payment->updated_at->format('Y-m-d H:i') }}
                                        @if($payment->updatedBy)
                                            <br>
                                            <small class="text-muted">द्वारा: {{ $payment->updatedBy->name }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($payment->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted">टिप्पणी</h6>
                            <div class="border rounded p-3 bg-light">
                                {{ $payment->notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($payment->remarks)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted">अतिरिक्त विवरण</h6>
                            <div class="border rounded p-3 bg-light">
                                {{ $payment->remarks }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- 🆕 NEW: Bill and Receipt Generation Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-file-pdf me-2"></i>बिल र रसिद जारी गर्नुहोस्
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-6 mb-3">
                                            <div class="border rounded p-4 h-100">
                                                <i class="fas fa-file-invoice fa-3x text-primary mb-3"></i>
                                                <h5 class="text-primary">बिल (Invoice)</h5>
                                                <p class="text-muted mb-3">भुक्तानी गर्नुपर्ने बिल डाउनलोड गर्नुहोस्</p>
                                                <a href="{{ route('owner.payments.bill', $payment) }}" 
                                                   class="btn btn-primary btn-block" target="_blank">
                                                    <i class="fas fa-download me-2"></i>बिल डाउनलोड गर्नुहोस्
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="border rounded p-4 h-100">
                                                <i class="fas fa-receipt fa-3x text-success mb-3"></i>
                                                <h5 class="text-success">रसिद (Receipt)</h5>
                                                <p class="text-muted mb-3">भुक्तानी भएको रसिद डाउनलोड गर्नुहोस्</p>
                                                <a href="{{ route('owner.payments.receipt', $payment) }}" 
                                                   class="btn btn-success btn-block" target="_blank">
                                                    <i class="fas fa-download me-2"></i>रसिद डाउनलोड गर्नुहोस्
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($payment->status == 'completed')
                                    <div class="alert alert-success mt-3">
                                        <i class="fas fa-check-circle me-2"></i>
                                        यो भुक्तानी सफल भएकोले तपाइँ दुवै बिल र रसिद जारी गर्न सक्नुहुन्छ।
                                    </div>
                                    @elseif($payment->status == 'pending')
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        यो भुक्तानी अझै पेन्डिङमा छ। भुक्तानी पूरा भएपछि मात्र रसिद जारी गर्न सकिन्छ।
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <!-- Edit Button -->
                                <a href="{{ route('owner.payments.edit', $payment) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>सम्पादन गर्नुहोस्
                                </a>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('owner.payments.destroy', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('के तपाईं यो भुक्तानी मेटाउन निश्चित हुनुहुन्छ?')">
                                        <i class="fas fa-trash me-2"></i>मेटाउनुहोस्
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* 🆕 NEW: Styles for bill/receipt cards */
    .card.border-info {
        border-width: 2px !important;
    }
    
    .btn-group .dropdown-menu {
        min-width: 220px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .d-flex.justify-content-end.gap-2 {
            justify-content: flex-start !important;
        }
        
        .btn-group {
            margin-bottom: 0.5rem;
            width: 100%;
        }
        
        .btn-group .btn {
            width: 100%;
        }
        
        .btn-group .dropdown-menu {
            width: 100%;
        }
    }
    
    /* Logo preview styles */
    .logo-preview img {
        max-width: 100%;
        height: auto;
    }
    
    .current-logo img {
        border: 2px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
// 🆕 NEW: Handle bill/receipt download with error handling
document.addEventListener('DOMContentLoaded', function() {
    const billLinks = document.querySelectorAll('a[href*="bill"], a[href*="receipt"]');
    
    billLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>लोड हुँदै...';
            this.classList.add('disabled');
            
            // Reset after 3 seconds in case of error
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('disabled');
            }, 3000);
        });
    });
});
</script>
@endpush