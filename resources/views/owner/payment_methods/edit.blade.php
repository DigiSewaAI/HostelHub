@extends('layouts.owner')

@section('title', 'भुक्तानी विधि सम्पादन गर्नुहोस्')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0 nepali">भुक्तानी विधि सम्पादन गर्नुहोस्</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.payment-methods.update', $paymentMethod) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if(session('error'))
                            <div class="alert alert-danger nepali">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Hostel Info (Read-only) -->
                        <div class="mb-3">
                            <label class="form-label nepali">होस्टेल</label>
                            <input type="text" class="form-control" value="{{ $hostel->name }}" readonly>
                            <input type="hidden" name="hostel_id" value="{{ $hostel->id }}">
                        </div>

                        <!-- Payment Method Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label nepali">भुक्तानी प्रकार <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="bank" {{ old('type', $paymentMethod->type) == 'bank' ? 'selected' : '' }}>बैंक खाता</option>
                                <option value="esewa" {{ old('type', $paymentMethod->type) == 'esewa' ? 'selected' : '' }}>ईसेवा</option>
                                <option value="khalti" {{ old('type', $paymentMethod->type) == 'khalti' ? 'selected' : '' }}>खल्ती</option>
                                <option value="fonepay" {{ old('type', $paymentMethod->type) == 'fonepay' ? 'selected' : '' }}>फोनपे</option>
                                <option value="imepay" {{ old('type', $paymentMethod->type) == 'imepay' ? 'selected' : '' }}>आईमेपे</option>
                                <option value="connectips" {{ old('type', $paymentMethod->type) == 'connectips' ? 'selected' : '' }}>कनेक्टआइपीएस</option>
                                <option value="cash" {{ old('type', $paymentMethod->type) == 'cash' ? 'selected' : '' }}>नगद</option>
                                <option value="other" {{ old('type', $paymentMethod->type) == 'other' ? 'selected' : '' }}>अन्य</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label nepali">शीर्षक <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $paymentMethod->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bank Details Section -->
                        <div id="bank_details" style="{{ in_array($paymentMethod->type, ['bank']) ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label nepali">बैंकको नाम</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" 
                                           value="{{ old('bank_name', $paymentMethod->bank_name) }}">
                                    @error('bank_name')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="account_number" class="form-label nepali">खाता नम्बर</label>
                                    <input type="text" name="account_number" id="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                                           value="{{ old('account_number', $paymentMethod->account_number) }}">
                                    @error('account_number')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="account_name" class="form-label nepali">खाता धनीको नाम</label>
                                    <input type="text" name="account_name" id="account_name" class="form-control @error('account_name') is-invalid @enderror" 
                                           value="{{ old('account_name', $paymentMethod->account_name) }}">
                                    @error('account_name')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="branch_name" class="form-label nepali">शाखा नाम</label>
                                    <input type="text" name="branch_name" id="branch_name" class="form-control @error('branch_name') is-invalid @enderror" 
                                           value="{{ old('branch_name', $paymentMethod->branch_name) }}">
                                    @error('branch_name')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Digital Wallet Details Section -->
                        <div id="digital_details" style="{{ in_array($paymentMethod->type, ['esewa', 'khalti', 'fonepay', 'imepay', 'connectips']) ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mobile_number" class="form-label nepali">मोबाइल नम्बर</label>
                                    <input type="text" name="mobile_number" id="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" 
                                           value="{{ old('mobile_number', $paymentMethod->mobile_number) }}">
                                    @error('mobile_number')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="wallet_id" class="form-label nepali">वालेट आईडी</label>
                                    <input type="text" name="wallet_id" id="wallet_id" class="form-control @error('wallet_id') is-invalid @enderror" 
                                           value="{{ old('wallet_id', $paymentMethod->wallet_id) }}">
                                    @error('wallet_id')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current QR Code -->
                        @if($paymentMethod->qr_code_url)
                            <div class="mb-3">
                                <label class="form-label nepali">हालको QR कोड</label>
                                <div class="border p-3 text-center">
                                    <img src="{{ $paymentMethod->qr_code_url }}" 
                                         alt="QR Code" 
                                         class="img-fluid rounded" 
                                         style="max-width: 150px;">
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input type="checkbox" name="remove_qr_code" id="remove_qr_code" value="1" class="form-check-input">
                                            <label for="remove_qr_code" class="form-check-label nepali text-danger">
                                                यो QR कोड हटाउनुहोस्
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- New QR Code Upload -->
                        <div class="mb-3">
                            <label for="qr_code" class="form-label nepali">नयाँ QR कोड (ऐच्छिक)</label>
                            <input type="file" name="qr_code" id="qr_code" class="form-control @error('qr_code') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="form-text nepali">
                                {{ $paymentMethod->qr_code_url ? 'नयाँ फाइल अपलोड गर्नुहोस् वा हालको राख्नुहोस्।' : 'QR कोड अपलोड गर्नुहोस् (ऐच्छिक)' }}
                            </div>
                            @error('qr_code')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-3">
                            <label for="additional_info" class="form-label nepali">थप जानकारी (ऐच्छिक)</label>
                            <textarea name="additional_info" id="additional_info" class="form-control @error('additional_info') is-invalid @enderror" 
                                      rows="3">{{ old('additional_info', is_array($paymentMethod->additional_info) ? json_encode($paymentMethod->additional_info, JSON_PRETTY_PRINT) : $paymentMethod->additional_info) }}</textarea>
                            <div class="form-text nepali">
                                JSON फरम्याटमा थप जानकारी (निर्देशनहरू, सम्पर्क विवरण, आदि)
                            </div>
                            @error('additional_info')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status and Default -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_default" id="is_default" value="1" 
                                           class="form-check-input @error('is_default') is-invalid @enderror" 
                                           {{ old('is_default', $paymentMethod->is_default) ? 'checked' : '' }}>
                                    <label for="is_default" class="form-check-label nepali">मुख्य भुक्तानी विधि बनाउनुहोस्</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                                           class="form-check-input @error('is_active') is-invalid @enderror" 
                                           {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                    <label for="is_active" class="form-check-label nepali">सक्रिय गर्नुहोस्</label>
                                </div>
                            </div>
                        </div>

                        <!-- Order -->
                        <div class="mb-3">
                            <label for="order" class="form-label nepali">क्रम (ऐच्छिक)</label>
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" 
                                   value="{{ old('order', $paymentMethod->order) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('owner.payment-methods.index') }}" class="btn btn-secondary nepali">
                                <i class="fas fa-arrow-left"></i> फिर्ता जानुहोस्
                            </a>
                            <div>
                                @if($paymentMethod->can_be_deleted)
                                    <a href="#" class="btn btn-danger nepali" 
                                       onclick="if(confirm('के तपाईं यो भुक्तानी विधि हटाउन निश्चित हुनुहुन्छ?')) {
                                           event.preventDefault();
                                           document.getElementById('delete-form').submit();
                                       }">
                                        <i class="fas fa-trash"></i> हटाउनुहोस्
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-warning nepali">
                                    <i class="fas fa-save"></i> परिवर्तनहरू सुरक्षित गर्नुहोस्
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Delete Form -->
                    @if($paymentMethod->can_be_deleted)
                        <form id="delete-form" action="{{ route('owner.payment-methods.destroy', $paymentMethod) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const bankDetails = document.getElementById('bank_details');
    const digitalDetails = document.getElementById('digital_details');
    
    function toggleDetails() {
        const selectedType = typeSelect.value;
        
        // Hide all details sections
        bankDetails.style.display = 'none';
        digitalDetails.style.display = 'none';
        
        // Show relevant section
        if (selectedType === 'bank') {
            bankDetails.style.display = 'block';
        } else if (['esewa', 'khalti', 'fonepay', 'imepay', 'connectips'].includes(selectedType)) {
            digitalDetails.style.display = 'block';
        }
    }
    
    // Initial toggle
    toggleDetails();
    
    // Add event listener for type change
    typeSelect.addEventListener('change', toggleDetails);
});
</script>
@endpush