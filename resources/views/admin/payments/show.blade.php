@extends('layouts.admin')

@section('title', 'भुक्तानी विवरण #'.str_pad($payment->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary mb-3 nepali">
                <i class="fas fa-arrow-left me-1"></i> भुक्तानीहरूमा फर्कनुहोस्
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary nepali">भुक्तानी सारांश</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center nepali">
                            <strong>भुक्तानी आईडी</strong>
                            <span>{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center nepali">
                            <strong>रकम</strong>
                            <span class="h5 text-success">रु {{ number_format($payment->amount, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center nepali">
                            <strong>विधि</strong>
                            <span class="badge bg-{{ $payment->method === 'khalti' ? 'primary' : ($payment->method === 'cash' ? 'success' : 'info') }}">
                                {{ $payment->method === 'khalti' ? 'खल्ती' : ($payment->method === 'cash' ? 'नगद' : 'बैंक हस्तान्तरण') }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center nepali">
                            <strong>स्थिति</strong>
                            <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $payment->status === 'completed' ? 'पूर्ण' : ($payment->status === 'pending' ? 'प्रतीक्षामा' : 'असफल') }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center nepali">
                            <strong>मिति</strong>
                            <span>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, h:i A') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center nepali">
                            <strong>लेनदेन आईडी</strong>
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
                            <button type="submit" class="btn btn-success w-100 nepali"
                                    onclick="return confirm('के तपाईं यो भुक्तानीलाई पूर्ण गर्न निश्चित हुनुहुन्छ?')">
                                <i class="fas fa-check me-1"></i> पूर्ण गर्नुहोस्
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary nepali">कार्यहरू</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.students.show', $payment->student) }}" class="btn btn-info nepali">
                            <i class="fas fa-user me-1"></i> विद्यार्थी प्रोफाइल हेर्नुहोस्
                        </a>
                        <a href="{{ route('admin.rooms.show', $payment->student->room) }}" class="btn btn-primary nepali">
                            <i class="fas fa-door-open me-1"></i> कोठा विवरण हेर्नुहोस्
                        </a>
                        <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 nepali"
                                    onclick="return confirm('के तपाईं यो भुक्तानी रेकर्ड मेटाउन निश्चित हुनुहुन्छ?')">
                                <i class="fas fa-trash me-1"></i> भुक्तानी मेट्नुहोस्
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary nepali">विद्यार्थी जानकारी</h6>
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
                                    <span class="text-muted">चित्र उपलब्ध छैन</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="nepali">{{ $payment->student->name }}</h4>
                            <p class="text-muted mb-1 nepali">
                                <i class="fas fa-envelope me-1"></i> {{ $payment->student->email ?? 'उपलब्ध छैन' }}<br>
                                <i class="fas fa-phone me-1"></i> {{ $payment->student->mobile }}<br>
                                <i class="fas fa-home me-1"></i> {{ $payment->student->address }}
                            </p>
                            <div class="mt-3">
                                <span class="badge bg-primary me-1 nepali">कोठा: {{ $payment->student->room->room_number }}</span>
                                <span class="badge bg-secondary nepali">स्थिति: {{ $payment->student->status === 'active' ? 'सक्रिय' : 'निष्क्रिय' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary nepali">भुक्तानी विवरण</h6>
                    <div>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary nepali">
                            <i class="fas fa-print me-1"></i> रसिद प्रिन्ट गर्नुहोस्
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered nepali">
                            <tr>
                                <th width="30%">भुक्तानी आईडी</th>
                                <td>{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <th>मिति र समय</th>
                                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>विद्यार्थीको नाम</th>
                                <td>{{ $payment->student->name }}</td>
                            </tr>
                            <tr>
                                <th>कोठा नम्बर</th>
                                <td>{{ $payment->student->room->room_number }}</td>
                            </tr>
                            <tr>
                                <th>भुक्तानी विधि</th>
                                <td>
                                    <span class="badge bg-{{ $payment->method === 'khalti' ? 'primary' : ($payment->method === 'cash' ? 'success' : 'info') }}">
                                        {{ $payment->method === 'khalti' ? 'खल्ती' : ($payment->method === 'cash' ? 'नगद' : 'बैंक हस्तान्तरण') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>लेनदेन आईडी</th>
                                <td>{{ $payment->transaction_id }}</td>
                            </tr>
                            <tr>
                                <th>रकम</th>
                                <td class="h4 text-success">रु {{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>स्थिति</th>
                                <td>
                                    <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $payment->status === 'completed' ? 'पूर्ण' : ($payment->status === 'pending' ? 'प्रतीक्षामा' : 'असफल') }}
                                    </span>
                                </td>
                            </tr>
                            @if($payment->notes)
                            <tr>
                                <th>टिप्पणी</th>
                                <td>{{ $payment->notes }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    <div class="mt-4">
                        <h6 class="mb-3 nepali">भुक्तानी समयरेखा</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center nepali">
                                <div>
                                    <h6 class="mb-1">भुक्तानी सुरु गरियो</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, h:i A') }}</small>
                                </div>
                                <span class="badge bg-primary">सिर्जना गरियो</span>
                            </li>
                            @if($payment->status === 'completed' && $payment->completed_at)
                            <li class="list-group-item d-flex justify-content-between align-items-center nepali">
                                <div>
                                    <h6 class="mb-1">भुक्तानी पूर्ण भयो</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($payment->completed_at)->format('d M Y, h:i A') }}</small>
                                </div>
                                <span class="badge bg-success">पूर्ण</span>
                            </li>
                            @elseif($payment->status === 'failed' && $payment->failed_at)
                            <li class="list-group-item d-flex justify-content-between align-items-center nepali">
                                <div>
                                    <h6 class="mb-1">भुक्तानी असफल भयो</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($payment->failed_at)->format('d M Y, h:i A') }}</small>
                                </div>
                                <span class="badge bg-danger">असफल</span>
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