@extends('layouts.owner')

@section('title', 'भुक्तानी सम्पादन गर्नुहोस्')

@section('page-description', 'भुक्तानी विवरण सम्पादन गर्नुहोस्')

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
                    <h5 class="card-title mb-0">भुक्तानी सम्पादन गर्नुहोस्</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.payments.update', $payment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">विद्यार्थी *</label>
                                <select class="form-select @error('student_id') is-invalid @enderror" 
                                        id="student_id" name="student_id" required>
                                    <option value="">विद्यार्थी छान्नुहोस्</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" 
                                            {{ old('student_id', $payment->student_id) == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} - {{ $student->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">रकम (रु) *</label>
                                <input type="number" step="0.01" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" 
                                       value="{{ old('amount', $payment->amount) }}" 
                                       placeholder="रकम राख्नुहोस्" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_date" class="form-label">भुक्तानी मिति *</label>
                                <input type="date" 
                                       class="form-control @error('payment_date') is-invalid @enderror" 
                                       id="payment_date" name="payment_date" 
                                       value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">भुक्तानी विधि *</label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">भुक्तानी विधि छान्नुहोस्</option>
                                    <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>नगद</option>
                                    <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>बैंक हस्तान्तरण</option>
                                    <option value="digital_wallet" {{ old('payment_method', $payment->payment_method) == 'digital_wallet' ? 'selected' : '' }}>डिजिटल वालेट</option>
                                    <option value="khalti" {{ old('payment_method', $payment->payment_method) == 'khalti' ? 'selected' : '' }}>खल्ती</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">स्थिति *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>पेन्डिङ</option>
                                    <option value="completed" {{ old('status', $payment->status) == 'completed' ? 'selected' : '' }}>सफल</option>
                                    <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>असफल</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">टिप्पणी</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" 
                                      rows="3" 
                                      placeholder="अतिरिक्त टिप्पणी...">{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('owner.payments.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>रद्द गर्नुहोस्
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>अद्यावधिक गर्नुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endsection