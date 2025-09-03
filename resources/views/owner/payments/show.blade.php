@extends('layouts.owner')

@section('title', 'Payment Details #'.str_pad($payment->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary mb-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Payments
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Summary</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Payment ID</strong>
                            <span>{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Amount</strong>
                            <span class="h5 text-success">रु {{ number_format($payment->amount, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Method</strong>
                            <span class="badge bg-{{ $payment->method === 'khalti' ? 'primary' : ($payment->method === 'cash' ? 'success' : 'info') }}">
                                {{ ucfirst($payment->method) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Status</strong>
                            <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Date</strong>
                            <span>{{ $payment->created_at->format('d M Y, h:i A') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Transaction ID</strong>
                            <span class="text-truncate" style="max-width: 150px;" title="{{ $payment->transaction_id }}">
                                {{ $payment->transaction_id }}
                            </span>
                        </li>
                    </ul>

                    @if($payment->status === 'pending')
                    <div class="mt-4">
                        <form action="{{ route('admin.payments.updateStatus', $payment) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success w-100"
                                    onclick="return confirm('Are you sure you want to mark this payment as completed?')">
                                <i class="fas fa-check me-1"></i> Mark as Completed
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.students.show', $payment->student) }}" class="btn btn-info">
                            <i class="fas fa-user me-1"></i> View Student Profile
                        </a>
                        <a href="{{ route('admin.rooms.show', $payment->student->room) }}" class="btn btn-primary">
                            <i class="fas fa-door-open me-1"></i> View Room Details
                        </a>
                        <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Are you sure you want to delete this payment record?')">
                                <i class="fas fa-trash me-1"></i> Delete Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            @if($payment->student->room && $payment->student->room->image)
                                <img src="{{ asset('storage/'.$payment->student->room->image) }}"
                                     class="img-fluid rounded"
                                     style="max-height: 150px; object-fit: cover; width: 100%;">
                            @else
                                <div class="bg-light rounded"
                                     style="height: 150px; width: 100%; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">No image</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $payment->student->name }}</h4>
                            <p class="text-muted mb-1">
                                <i class="fas fa-envelope me-1"></i> {{ $payment->student->email ?? 'Not provided' }}<br>
                                <i class="fas fa-phone me-1"></i> {{ $payment->student->mobile }}<br>
                                <i class="fas fa-home me-1"></i> {{ $payment->student->address }}
                            </p>
                            <div class="mt-3">
                                <span class="badge bg-primary me-1">Room: {{ $payment->student->room->room_number }}</span>
                                <span class="badge bg-secondary">Status: {{ ucfirst($payment->student->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Details</h6>
                    <div>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-print me-1"></i> Print Receipt
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Payment ID</th>
                                <td>{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <th>Date & Time</th>
                                <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Student Name</th>
                                <td>{{ $payment->student->name }}</td>
                            </tr>
                            <tr>
                                <th>Room Number</th>
                                <td>{{ $payment->student->room->room_number }}</td>
                            </tr>
                            <tr>
                                <th>Payment Method</th>
                                <td>
                                    <span class="badge bg-{{ $payment->method === 'khalti' ? 'primary' : ($payment->method === 'cash' ? 'success' : 'info') }}">
                                        {{ ucfirst($payment->method) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Transaction ID</th>
                                <td>{{ $payment->transaction_id }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td class="h4 text-success">रु {{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @if($payment->notes)
                            <tr>
                                <th>Notes</th>
                                <td>{{ $payment->notes }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    <div class="mt-4">
                        <h6 class="mb-3">Payment Timeline</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Payment Initiated</h6>
                                    <small class="text-muted">{{ $payment->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                <span class="badge bg-primary">Created</span>
                            </li>
                            @if($payment->status === 'completed' && $payment->completed_at)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Payment Completed</h6>
                                    <small class="text-muted">{{ $payment->completed_at->format('d M Y, h:i A') }}</small>
                                </div>
                                <span class="badge bg-success">Completed</span>
                            </li>
                            @elseif($payment->status === 'failed' && $payment->failed_at)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Payment Failed</h6>
                                    <small class="text-muted">{{ $payment->failed_at->format('d M Y, h:i A') }}</small>
                                </div>
                                <span class="badge bg-danger">Failed</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
