@extends('layouts.owner')

@section('title', 'भुक्तानीहरू - HostelHub')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">भुक्तानीहरू</h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">रु {{ number_format($payments->sum('amount'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                सफल भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $payments->where('status', 'completed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                पेन्डिङ भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $payments->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                असफल भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $payments->where('status', 'failed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">भुक्तानीहरूको सूची</h6>
                    <div class="d-flex">
                        <input type="text" class="form-control form-control-sm mr-2" placeholder="खोज्नुहोस्..." id="searchInput">
                        <!-- ✅ FIXED: Added Payment Report Button -->
                        <a href="{{ route('owner.payments.report') }}" class="btn btn-info btn-sm mr-2">
                            <i class="fas fa-chart-bar"></i> भुक्तानी रिपोर्ट
                        </a>
                        <a href="{{ route('owner.payments.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> नयाँ भुक्तानी
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="paymentsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>विद्यार्थी</th>
                                    <th>कोठा</th>
                                    <th>रकम</th>
                                    <th>भुक्तानी मिति</th>
                                    <th>अन्तिम मिति</th>
                                    <th>भुक्तानी विधि</th>
                                    <th>स्थिति</th>
                                    <th>क्रियाहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->student->name ?? 'N/A' }}</td>
                                    <td>{{ $payment->room->name ?? 'N/A' }}</td>
                                    <td>रु {{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                    <td>{{ $payment->due_date ? $payment->due_date->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        <!-- ✅ FIXED: Use getPaymentMethodText() for Nepali translation -->
                                        <span class="badge payment-method-badge badge-{{ $payment->payment_method == 'cash' ? 'success' : ($payment->payment_method == 'bank_transfer' ? 'info' : ($payment->payment_method == 'khalti' ? 'primary' : ($payment->payment_method == 'esewa' ? 'warning' : 'secondary'))) }}">
                                            {{ $payment->getPaymentMethodText() }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- ✅ FIXED: Use Nepali status text -->
                                        @if($payment->status == 'completed')
                                            <span class="badge status-badge badge-success">सफल</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge status-badge badge-warning">पेन्डिङ</span>
                                        @elseif($payment->status == 'failed')
                                            <span class="badge status-badge badge-danger">असफल</span>
                                        @elseif($payment->status == 'refunded')
                                            <span class="badge status-badge badge-info">फिर्ता भयो</span>
                                        @else
                                            <span class="badge status-badge badge-secondary">{{ $payment->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- ✅ FIXED: Improved action buttons with proper spacing and delete button -->
                                        <div class="action-buttons">
                                            <!-- View Button -->
                                            <a href="{{ route('owner.payments.show', $payment) }}" 
                                               class="btn btn-info btn-sm action-btn" title="विवरण हेर्नुहोस्">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <!-- Edit Button -->
                                            <a href="{{ route('owner.payments.edit', $payment) }}" 
                                               class="btn btn-primary btn-sm action-btn" title="सम्पादन गर्नुहोस्">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('owner.payments.destroy', $payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm action-btn" 
                                                        title="हटाउनुहोस्"
                                                        onclick="return confirm('के तपाईं यो भुक्तानी हटाउन निश्चित हुनुहुन्छ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Bank Transfer Approval Buttons -->
                                            @if($payment->payment_method == 'bank_transfer' && $payment->status == 'pending')
                                                <button type="button" class="btn btn-success btn-sm action-btn" 
                                                        onclick="approvePayment({{ $payment->id }})" title="स्वीकार गर्नुहोस्">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm action-btn" 
                                                        data-toggle="modal" 
                                                        data-target="#rejectModal"
                                                        data-payment-id="{{ $payment->id }}" title="अस्वीकार गर्नुहोस्">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">भुक्तानी अस्वीकार गर्नुहोस्</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">अस्वीकार गर्ने कारण</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">रद्द गर्नुहोस्</button>
                    <button type="submit" class="btn btn-danger">अस्वीकार गर्नुहोस्</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">भुक्तानी हटाउनुहोस्</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                के तपाईं यो भुक्तानी हटाउन निश्चित हुनुहुन्छ? यो कार्य पछि फिर्ता गर्न सकिँदैन।
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">रद्द गर्नुहोस्</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">हटाउनुहोस्</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ✅ Consistent styling with report page */
.payment-method-badge,
.status-badge {
    font-size: 0.85rem !important;
    font-weight: 600 !important;
    padding: 0.5rem 0.75rem !important;
    border-radius: 0.35rem !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-width: 100px;
    text-align: center;
    display: inline-block;
}

/* ✅ FIXED: Improved action buttons styling */
.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    justify-content: center;
}

.action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.25rem;
    padding: 0;
    font-size: 0.875rem;
}

/* Color coding for payment methods */
.badge.badge-success { background-color: #28a745; color: white; }
.badge.badge-info { background-color: #17a2b8; color: white; }
.badge.badge-primary { background-color: #007bff; color: white; }
.badge.badge-warning { background-color: #ffc107; color: #212529; }
.badge.badge-secondary { background-color: #6c757d; color: white; }

/* Color coding for status */
.badge.badge-success { background-color: #28a745; color: white; }
.badge.badge-warning { background-color: #ffc107; color: #212529; }
.badge.badge-danger { background-color: #dc3545; color: white; }
.badge.badge-info { background-color: #17a2b8; color: white; }

/* Hover effects */
.payment-method-badge:hover,
.status-badge:hover {
    opacity: 0.9;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Table styling */
#paymentsTable td {
    vertical-align: middle;
    padding: 0.75rem;
}

#paymentsTable th {
    font-weight: 600;
    background-color: #f8f9fa;
}

/* Header buttons styling */
.card-header .btn {
    margin-left: 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        gap: 0.125rem;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 0.75rem;
    }
    
    .card-header .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .card-header .btn {
        margin-left: 0;
        margin-bottom: 0.25rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    const rows = document.querySelectorAll('#paymentsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
    });
});

function approvePayment(paymentId) {
    if (confirm('के तपाइँ यो भुक्तानी स्वीकार गर्न निश्चित हुनुहुन्छ?')) {
        fetch(`/owner/payments/${paymentId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('भुक्तानी स्वीकार गर्न असफल।');
            }
        });
    }
}

$('#rejectModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var paymentId = button.data('payment-id');
    var form = $('#rejectForm');
    form.attr('action', `/owner/payments/${paymentId}/reject`);
});

// Delete confirmation with modal (alternative approach)
function confirmDelete(paymentId) {
    $('#deleteModal').modal('show');
    $('#deleteForm').attr('action', `/owner/payments/${paymentId}`);
}
</script>
@endpush