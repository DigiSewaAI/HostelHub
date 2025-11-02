@extends('layouts.owner')

@section('title', 'भुक्तानी रिपोर्ट - HostelHub')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">भुक्तानी रिपोर्ट</h1>
        </div>
    </div>

    <!-- ✅ IMPROVED: Quick Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">द्रुत कार्यहरू</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('owner.payments.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> नयाँ भुक्तानी
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('owner.payments.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-list"></i> सबै भुक्तानीहरू
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-warning btn-block" onclick="printReport()">
                                <i class="fas fa-print"></i> रिपोर्ट प्रिन्ट गर्नुहोस्
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-tachometer-alt"></i> ड्यासबोर्ड
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ IMPROVED: More Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल आय</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">रु {{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                यो महिनाको आय</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">रु {{ number_format($currentMonthRevenue ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                औसत भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">रु {{ number_format($averagePayment ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                बाँकी बैंक स्थानान्तरण</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingTransfers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                कुल भुक्तानीहरू</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPayments ?? $payments->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                सफल भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedPayments ?? $payments->where('status', 'completed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ IMPROVED: Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body py-3">
                    <form action="{{ route('owner.payments.report') }}" method="GET" class="form-inline">
                        <label class="mr-2 mb-2"><strong>मिति अनुसार फिल्टर गर्नुहोस्:</strong></label>
                        <input type="date" name="start_date" class="form-control form-control-sm mr-2 mb-2" 
                               value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
                        <span class="mr-2 mb-2">देखि</span>
                        <input type="date" name="end_date" class="form-control form-control-sm mr-2 mb-2" 
                               value="{{ request('end_date', now()->format('Y-m-d')) }}">
                        <button type="submit" class="btn btn-primary btn-sm mr-2 mb-2">
                            <i class="fas fa-filter"></i> फिल्टर गर्नुहोस्
                        </button>
                        <a href="{{ route('owner.payments.report') }}" class="btn btn-secondary btn-sm mb-2">
                            <i class="fas fa-redo"></i> रिसेट गर्नुहोस्
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- म्यानुअल भुक्तानी फर्म -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">म्यानुअल भुक्तानी दर्ता गर्नुहोस्</h6>
                    <div class="export-buttons">
                    <form action="{{ route('owner.payments.export') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ request('start_date', '') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date', '') }}">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> एक्सेल डाउनलोड
                    </button>
                </form>
                </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.payments.manual') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="student_id">विद्यार्थी</label>
                                    <select class="form-control" id="student_id" name="student_id" required>
                                        <option value="">विद्यार्थी छान्नुहोस्</option>
                                        @php
                                            // ✅ FIXED: Get current user's hostel IDs and their students
                                            $hostelIds = App\Models\Hostel::where('owner_id', auth()->id())
                                                ->orWhere('manager_id', auth()->id())
                                                ->pluck('id');
                                            $students = App\Models\Student::whereHas('room', function($query) use ($hostelIds) {
                                                $query->whereIn('hostel_id', $hostelIds);
                                            })->where('status', 'active')->get();
                                        @endphp
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}">
                                                {{ $student->name }} - {{ $student->room->name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">रकम (रुपैयाँ)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="paid_at">भुक्तानी मिति</label>
                                    <input type="date" class="form-control" id="paid_at" name="paid_at" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-save"></i> दर्ता गर्नुहोस्
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ IMPROVED: Payment Methods Distribution -->
@if(isset($paymentMethods) && count($paymentMethods) > 0)
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">भुक्तानी विधिहरू</h6>
            </div>
            <div class="card-body">
                <div class="payment-methods-breakdown">
                    @php
                        $totalMethods = array_sum($paymentMethods);
                    @endphp
                    @foreach($paymentMethods as $method => $count)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                        <span class="font-weight-bold">
                            {{ \App\Models\Payment::getPaymentMethodTextStatic($method) }}
                        </span>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-primary badge-pill mr-2">{{ $count }}</span>
                            <div class="progress" style="width: 100px; height: 10px;">
                                @php
                                    $percentage = $totalMethods > 0 ? ($count / $totalMethods) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ $percentage }}%" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

    <!-- भुक्तानी तालिका -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">सबै भुक्तानीहरू</h6>
                    <div class="d-flex">
                        <input type="text" class="form-control form-control-sm mr-2" placeholder="खोज्नुहोस्..." id="searchInput">
                        <span class="text-muted small align-self-center">
                            जम्मा: {{ $payments->total() }} भुक्तानी
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="paymentsTable">
                            <thead>
                                <tr>
                                    <th>आईडी</th>
                                    <th>विद्यार्थी</th>
                                    <th>होस्टल</th>
                                    <th>रकम</th>
                                    <th>विधि</th>
                                    <th>स्थिति</th>
                                    <th>मिति</th>
                                    <th>कार्यहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>#{{ $payment->id }}</td>
                                    <td>
                                        {{ $payment->student->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $payment->hostel->name ?? 'N/A' }}</td>
                                    <td>रु {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <!-- ✅ FIXED: Improved badge styling for better visibility -->
                                        <span class="badge payment-method-badge badge-{{ $payment->payment_method == 'cash' ? 'success' : ($payment->payment_method == 'bank_transfer' ? 'info' : ($payment->payment_method == 'khalti' ? 'primary' : ($payment->payment_method == 'esewa' ? 'warning' : 'secondary'))) }}">
                                            {{ $payment->getPaymentMethodText() }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- ✅ FIXED: Improved status badge styling -->
                                        @if($payment->status == 'completed')
                                            <span class="badge status-badge badge-success">पूरा भयो</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge status-badge badge-warning">बाँकी</span>
                                        @elseif($payment->status == 'failed')
                                            <span class="badge status-badge badge-danger">असफल</span>
                                        @elseif($payment->status == 'refunded')
                                            <span class="badge status-badge badge-info">फिर्ता भयो</span>
                                        @else
                                            <span class="badge status-badge badge-secondary">{{ $payment->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            @if($payment->payment_method == 'bank_transfer' && $payment->status == 'pending')
                                                <button type="button" class="btn btn-sm btn-success action-btn" 
                                                        onclick="approvePayment({{ $payment->id }})" title="स्वीकार गर्नुहोस्">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger action-btn" 
                                                        data-toggle="modal" 
                                                        data-target="#rejectModal"
                                                        data-payment-id="{{ $payment->id }}" title="अस्वीकार गर्नुहोस्">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('owner.payments.show', $payment) }}" 
                                               class="btn btn-sm btn-info action-btn" title="विवरण हेर्नुहोस्">
                                                <i class="fas fa-eye"></i>
                                            </a>
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

<!-- अस्वीकार गर्ने मोडल -->
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
@endsection

@push('styles')
<style>
/* ✅ FIXED: Improved badge styling for better visibility */
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

/* Specific styling for payment method badges */
.badge.badge-success { background-color: #28a745; color: white; }
.badge.badge-info { background-color: #17a2b8; color: white; }
.badge.badge-primary { background-color: #007bff; color: white; }
.badge.badge-warning { background-color: #ffc107; color: #212529; }
.badge.badge-secondary { background-color: #6c757d; color: white; }

/* Specific styling for status badges */
.badge.badge-success { background-color: #28a745; color: white; }
.badge.badge-warning { background-color: #ffc107; color: #212529; }
.badge.badge-danger { background-color: #dc3545; color: white; }
.badge.badge-info { background-color: #17a2b8; color: white; }

/* Hover effects for better interactivity */
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

/* Table cell adjustments for better spacing */
#paymentsTable td {
    vertical-align: middle;
    padding: 0.75rem;
}

#paymentsTable th {
    font-weight: 600;
    background-color: #f8f9fa;
}

/* Print styles */
@media print {
    .btn, .action-buttons, .card-header .export-buttons, .quick-actions {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
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
    
    .form-inline .form-control {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput')?.addEventListener('keyup', function() {
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

function printReport() {
    window.print();
}

$('#rejectModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var paymentId = button.data('payment-id');
    var form = $('#rejectForm');
    form.attr('action', `/owner/payments/${paymentId}/reject`);
});

// Auto-set end date to today if not set
document.addEventListener('DOMContentLoaded', function() {
    const endDateInput = document.querySelector('input[name="end_date"]');
    if (endDateInput && !endDateInput.value) {
        endDateInput.value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endpush