@extends('layouts.owner')

@section('title', 'नयाँ भुक्तानी')

@section('page-description', 'नयाँ भुक्तानी थप्नुहोस्')

@section('header-buttons')
    <a href="{{ route('owner.payments.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>पछाडि
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <!-- ✅ FIX: Success Message Display -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">नयाँ भुक्तानी फारम</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.payments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">विद्यार्थी *</label>
                                <select class="form-select @error('student_id') is-invalid @enderror" 
                                        id="student_id" name="student_id" required>
                                    <option value="">विद्यार्थी छान्नुहोस्</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" 
                                            {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                                       value="{{ old('amount') }}" 
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
                                       value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">भुक्तानी अन्तिम मिति</label>
                                <input type="date" 
                                       class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" 
                                       value="{{ old('due_date', date('Y-m-d')) }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">खाली छोड्नुहोस् यदि भुक्तानी अहिले नै गर्नुपर्छ</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">भुक्तानी विधि *</label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">भुक्तानी विधि छान्नुहोस्</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>नगद</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>बैंक हस्तान्तरण</option>
                                    <option value="digital_wallet" {{ old('payment_method') == 'digital_wallet' ? 'selected' : '' }}>डिजिटल वालेट</option>
                                    <option value="khalti" {{ old('payment_method') == 'khalti' ? 'selected' : '' }}>खल्ती</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">स्थिति *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>पेन्डिङ</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>सफल</option>
                                    <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>असफल</option>
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
                                      placeholder="अतिरिक्त टिप्पणी...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-secondary me-md-2">
                                <i class="fas fa-undo me-2"></i>फारम खाली गर्नुहोस्
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>भुक्तानी सेभ गर्नुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ✅ FIX: Quick Actions Section -->
            <div class="card shadow mt-4">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-3">द्रुत कार्यहरू</h6>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('owner.payments.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-1"></i>सबै भुक्तानी हेर्नुहोस्
                        </a>
                        <a href="{{ route('owner.payments.report') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-chart-bar me-1"></i>रिपोर्ट हेर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // ✅ FIX: Auto-clear form after successful submission if success message exists
        @if(session('success'))
            // Clear form fields except student selection
            document.getElementById('amount').value = '';
            document.getElementById('payment_date').value = '{{ date("Y-m-d") }}';
            document.getElementById('due_date').value = '';
            document.getElementById('notes').value = '';
            
            // Reset selects to default
            document.getElementById('payment_method').selectedIndex = 0;
            document.getElementById('status').selectedIndex = 0;
        @endif
    });
</script>
@endsection