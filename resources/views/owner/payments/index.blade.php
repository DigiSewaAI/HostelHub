@extends('layouts.owner')

@section('title', 'Payment Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="mb-0">Payment Management</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Payment
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="reportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export me-1"></i> Reports
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.payments.export', ['format' => 'csv']) }}"><i class="fas fa-file-csv me-1"></i> Export as CSV</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.payments.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel me-1"></i> Export as Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.payments.report') }}"><i class="fas fa-chart-bar me-1"></i> View Report</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Payments</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <input type="text" class="form-control date-range" name="date_range"
                               value="{{ request('date_range', now()->subDays(30)->format('Y-m-d') . ' to ' . now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="method">
                            <option value="">All Methods</option>
                            <option value="khalti" {{ request('method') == 'khalti' ? 'selected' : '' }}>Khalti</option>
                            <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ request('method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search Student</label>
                        <input type="text" class="form-control" name="search"
                               placeholder="Search by student name or mobile"
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Payments</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($payment->student->room && $payment->student->room->image)
                                        <img src="{{ asset('storage/'.$payment->student->room->image) }}"
                                             class="rounded me-2"
                                             width="30"
                                             height="30"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-2" style="width: 30px; height: 30px;"></div>
                                    @endif
                                    <div>
                                        <div>{{ $payment->student->name }}</div>
                                        <small class="text-muted">{{ $payment->student->mobile }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>रु {{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->method === 'khalti' ? 'primary' : ($payment->method === 'cash' ? 'success' : 'info') }}">
                                    {{ ucfirst($payment->method) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                      title="{{ $payment->transaction_id }}">
                                    {{ $payment->transaction_id }}
                                </span>
                            </td>
                            <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                       class="btn btn-sm btn-info"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($payment->status === 'pending')
                                    <form action="{{ route('admin.payments.updateStatus', $payment) }}"
                                          method="POST"
                                          style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit"
                                                class="btn btn-sm btn-success"
                                                title="Mark as Completed"
                                                onclick="return confirm('Are you sure you want to mark this payment as completed?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.payments.destroy', $payment) }}"
                                          method="POST"
                                          class="delete-form"
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this payment record?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <div>
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} entries
                </div>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date range picker
        $('.date-range').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
</script>
@endpush
