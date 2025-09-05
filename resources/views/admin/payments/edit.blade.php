@extends('layouts.admin')

@section('title', 'भुक्तानी सम्पादन गर्नुहोस्')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 nepali">भुक्तानी सम्पादन गर्नुहोस्</h2>
                <div>
                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-secondary me-2 nepali">
                        <i class="fas fa-eye me-1"></i> हेर्नुहोस्
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary nepali">
                        <i class="fas fa-arrow-left me-1"></i> फर्कनुहोस्
                    </a>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-white nepali">
                    <h5 class="mb-0">भुक्तानी विवरण सम्पादन गर्नुहोस्</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label nepali">विद्यार्थी</label>
                                    <select name="student_id" class="form-select nepali @error('student_id') is-invalid @enderror" required>
                                        <option value="">विद्यार्थी छान्नुहोस्</option>
                                        @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ $payment->student_id == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->mobile }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label nepali">होस्टल</label>
                                    <select name="hostel_id" class="form-select nepali @error('hostel_id') is-invalid @enderror" required>
                                        <option value="">होस्टल छान्नुहोस्</option>
                                        @foreach($hostels as $hostel)
                                        <option value="{{ $hostel->id }}" {{ $payment->hostel_id == $hostel->id ? 'selected' : '' }}>
                                            {{ $hostel->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('hostel_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label nepali">रकम (रु)</label>
                                    <input type="number" name="amount" class="form-control nepali @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount', $payment->amount) }}" step="0.01" min="0" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label nepali">भुक्तानी मिति</label>
                                    <input type="date" name="payment_date" class="form-control nepali @error('payment_date') is-invalid @enderror" 
                                           value="{{ old('payment_date', $payment->payment_date) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label nepali">भुक्तानी विधि</label>
                                    <select name="payment_method" class="form-select nepali @error('payment_method') is-invalid @enderror" required>
                                        <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>नगद</option>
                                        <option value="khalti" {{ $payment->payment_method == 'khalti' ? 'selected' : '' }}>खल्ती</option>
                                        <option value="bank" {{ $payment->payment_method == 'bank' ? 'selected' : '' }}>बैंक हस्तान्तरण</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label nepali">लेनदेन आईडी (वैकल्पिक)</label>
                                    <input type="text" name="transaction_id" class="form-control nepali @error('transaction_id') is-invalid @enderror" 
                                           value="{{ old('transaction_id', $payment->transaction_id) }}">
                                    @error('transaction_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label nepali">स्थिति</label>
                                    <select name="status" class="form-select nepali @error('status') is-invalid @enderror" required>
                                        <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>पूर्ण</option>
                                        <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>प्रतीक्षामा</option>
                                        <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>असफल</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label nepali">टिप्पणी (वैकल्पिक)</label>
                                    <textarea name="notes" class="form-control nepali @error('notes') is-invalid @enderror" 
                                              rows="3">{{ old('notes', $payment->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary nepali px-4">
                                <i class="fas fa-save me-2"></i> परिवर्तनहरू सुरक्षित गर्नुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection