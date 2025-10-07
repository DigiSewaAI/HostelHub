@extends('layouts.owner')

@section('title', 'भुक्तानी रिपोर्ट - HostelHub')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">भुक्तानी रिपोर्ट</h1>
        </div>
    </div>

    <!-- तथ्याङ्क कार्डहरू -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
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
    </div>

    <!-- म्यानुअल भुक्तानी फर्म -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">म्यानुअल भुक्तानी दर्ता गर्नुहोस्</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.payments.manual') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="booking_id">बुकिंग</label>
                                    <select class="form-control" id="booking_id" name="booking_id" required>
                                        <option value="">बुकिंग छान्नुहोस्</option>
                                        @foreach(\App\Models\Booking::whereIn('hostel_id', auth()->user()->hostels->pluck('id'))->where('status', 'pending_payment')->get() as $booking)
                                            <option value="{{ $booking->id }}">बुकिंग #{{ $booking->id }} - {{ $booking->user->name }}</option>
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
                                    <button type="submit" class="btn btn-primary btn-block">भुक्तानी दर्ता गर्नुहोस्</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- भुक्तानी तालिका -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">सबै भुक्तानीहरू</h6>
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
                                    <td>{{ $payment->user->name }}</td>
                                    <td>{{ $payment->booking->hostel->name }}</td>
                                    <td>रु {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            @if($payment->method == 'esewa') इसेवा
                                            @elseif($payment->method == 'khalti') खल्ती
                                            @elseif($payment->method == 'bank_transfer') बैंक स्थानान्तरण
                                            @elseif($payment->method == 'cash') नगद
                                            @else {{ $payment->method }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->status == 'completed')
                                            <span class="badge badge-success">पूरा भयो</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge badge-warning">बाँकी</span>
                                        @else
                                            <span class="badge badge-danger">
                                                @if($payment->status == 'rejected') अस्वीकृत
                                                @elseif($payment->status == 'failed') असफल
                                                @else {{ $payment->status }}
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->paid_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if($payment->method == 'bank_transfer' && $payment->status == 'pending')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="approvePayment({{ $payment->id }})">
                                                स्वीकार गर्नुहोस्
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-toggle="modal" 
                                                    data-target="#rejectModal"
                                                    data-payment-id="{{ $payment->id }}">
                                                अस्वीकार गर्नुहोस्
                                            </button>
                                        @endif
                                        @if($payment->proof_document)
                                            <a href="{{ route('owner.payments.proof', $payment) }}" 
                                               class="btn btn-sm btn-info" target="_blank">
                                                प्रमाण हेर्नुहोस्
                                            </a>
                                        @endif
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

@push('scripts')
<script>
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
</script>
@endpush