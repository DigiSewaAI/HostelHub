@extends('layouts.owner')

@section('title', 'नयाँ भुक्तानी विधि थप्नुहोस्')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 nepali">नयाँ भुक्तानी विधि थप्नुहोस्</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if(session('error'))
                            <div class="alert alert-danger nepali">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Hostel Selection -->
                        <div class="mb-3">
                            <label for="hostel_id" class="form-label nepali">होस्टेल <span class="text-danger">*</span></label>
                            <select name="hostel_id" id="hostel_id" class="form-select @error('hostel_id') is-invalid @enderror" required>
                                <option value="" selected disabled>होस्टेल छान्नुहोस्</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id || ($selectedHostel && $selectedHostel->id == $hostel->id) ? 'selected' : '' }}>
                                        {{ $hostel->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hostel_id')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label nepali">भुक्तानी प्रकार <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="" selected disabled>प्रकार छान्नुहोस्</option>
                                <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>बैंक खाता</option>
                                <option value="esewa" {{ old('type') == 'esewa' ? 'selected' : '' }}>ईसेवा</option>
                                <option value="khalti" {{ old('type') == 'khalti' ? 'selected' : '' }}>खल्ती</option>
                                <option value="fonepay" {{ old('type') == 'fonepay' ? 'selected' : '' }}>फोनपे</option>
                                <option value="imepay" {{ old('type') == 'imepay' ? 'selected' : '' }}>आईमेपे</option>
                                <option value="connectips" {{ old('type') == 'connectips' ? 'selected' : '' }}>कनेक्टआइपीएस</option>
                                <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>नगद</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>अन्य</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label nepali">शीर्षक <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" placeholder="जस्तै: मुख्य बैंक खाता, ईसेवा भुक्तानी, आदि" required>
                            @error('title')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bank Details Section (shown only for bank type) -->
                        <div id="bank_details" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label nepali">बैंकको नाम</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" 
                                           value="{{ old('bank_name') }}" placeholder="जस्तै: NIBL, NMB, Everest Bank">
                                    @error('bank_name')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="account_number" class="form-label nepali">खाता नम्बर</label>
                                    <input type="text" name="account_number" id="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                                           value="{{ old('account_number') }}" placeholder="जस्तै: 123456789012">
                                    @error('account_number')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="account_name" class="form-label nepali">खाता धनीको नाम</label>
                                    <input type="text" name="account_name" id="account_name" class="form-control @error('account_name') is-invalid @enderror" 
                                           value="{{ old('account_name') }}" placeholder="जस्तै: राम बहादुर श्रेष्ठ">
                                    @error('account_name')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="branch_name" class="form-label nepali">शाखा नाम</label>
                                    <input type="text" name="branch_name" id="branch_name" class="form-control @error('branch_name') is-invalid @enderror" 
                                           value="{{ old('branch_name') }}" placeholder="जस्तै: काठमाडौँ शाखा">
                                    @error('branch_name')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Digital Wallet Details Section -->
                        <div id="digital_details" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mobile_number" class="form-label nepali">मोबाइल नम्बर</label>
                                    <input type="text" name="mobile_number" id="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" 
                                           value="{{ old('mobile_number') }}" placeholder="जस्तै: 9800000000">
                                    @error('mobile_number')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="wallet_id" class="form-label nepali">वालेट आईडी (ऐच्छिक)</label>
                                    <input type="text" name="wallet_id" id="wallet_id" class="form-control @error('wallet_id') is-invalid @enderror" 
                                           value="{{ old('wallet_id') }}" placeholder="जस्तै: esewa123">
                                    @error('wallet_id')
                                        <div class="invalid-feedback nepali">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Cash Details Section -->
                        <div id="cash_details" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label nepali">नगद भुक्तानी विवरण</label>
                                <div class="form-text nepali">
                                    नगद भुक्तानीका लागि होस्टेल कार्यालयमा भुक्तानी गर्नुपर्नेछ। 
                                    तलको ऐच्छिक क्षेत्रमा थप निर्देशनहरू दिन सक्नुहुन्छ।
                                </div>
                            </div>
                        </div>

                        <!-- Other Details Section -->
                        <div id="other_details" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label nepali">अन्य भुक्तानी विवरण</label>
                                <div class="form-text nepali">
                                    तलको ऐच्छिक क्षेत्रमा भुक्तानी सम्बन्धी विस्तृत विवरण दिनुहोस्।
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Upload -->
                        <div class="mb-3">
                            <label for="qr_code" class="form-label nepali">QR कोड (ऐच्छिक)</label>
                            <input type="file" name="qr_code" id="qr_code" class="form-control @error('qr_code') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="form-text nepali">
                                स्वीकार्य फाइलहरू: JPEG, PNG, JPG, GIF (अधिकतम 2MB)
                            </div>
                            @error('qr_code')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-3">
                            <label for="additional_info" class="form-label nepali">थप जानकारी (ऐच्छिक)</label>
                            <textarea name="additional_info" id="additional_info" class="form-control @error('additional_info') is-invalid @enderror" 
                                      rows="3" placeholder='जस्तै: {"instructions": ["भुक्तानी गरेपछि रसिद पठाउनुहोस्", "फोन नम्बर: 9800000000"]}'>{{ old('additional_info') }}</textarea>
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
                                           {{ old('is_default') ? 'checked' : '' }}>
                                    <label for="is_default" class="form-check-label nepali">मुख्य भुक्तानी विधि बनाउनुहोस्</label>
                                    <div class="form-text nepali">(एक पटकमा एउटा मात्र विधि मुख्य हुन सक्छ)</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                                           class="form-check-input @error('is_active') is-invalid @enderror" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label for="is_active" class="form-check-label nepali">सक्रिय गर्नुहोस्</label>
                                </div>
                            </div>
                        </div>

                        <!-- Order -->
                        <div class="mb-3">
                            <label for="order" class="form-label nepali">क्रम (ऐच्छिक)</label>
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" 
                                   value="{{ old('order', '') }}" min="1" placeholder="स्वत: निर्धारित हुनेछ">
                            <div class="form-text nepali">कम संख्या भएको विधि पहिले देखिनेछ</div>
                            @error('order')
                                <div class="invalid-feedback nepali">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('owner.payment-methods.index') }}" class="btn btn-secondary nepali">
                                <i class="fas fa-arrow-left"></i> फिर्ता जानुहोस्
                            </a>
                            <button type="submit" class="btn btn-primary nepali">
                                <i class="fas fa-save"></i> भुक्तानी विधि थप्नुहोस्
                            </button>
                        </div>
                    </form>
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
    const cashDetails = document.getElementById('cash_details');
    const otherDetails = document.getElementById('other_details');
    
    function toggleDetails() {
        const selectedType = typeSelect.value;
        
        // Hide all details sections
        bankDetails.style.display = 'none';
        digitalDetails.style.display = 'none';
        cashDetails.style.display = 'none';
        otherDetails.style.display = 'none';
        
        // Show relevant section
        if (selectedType === 'bank') {
            bankDetails.style.display = 'block';
        } else if (['esewa', 'khalti', 'fonepay', 'imepay', 'connectips'].includes(selectedType)) {
            digitalDetails.style.display = 'block';
        } else if (selectedType === 'cash') {
            cashDetails.style.display = 'block';
        } else if (selectedType === 'other') {
            otherDetails.style.display = 'block';
        }
    }
    
    // Initial toggle
    toggleDetails();
    
    // Add event listener for type change
    typeSelect.addEventListener('change', toggleDetails);
    
    // Set required fields based on type
    typeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        const mobileInput = document.getElementById('mobile_number');
        const mobileLabel = mobileInput.parentElement.querySelector('label');
        const bankNameInput = document.getElementById('bank_name');
        const bankNameLabel = bankNameInput.parentElement.querySelector('label');
        const accountNameInput = document.getElementById('account_name');
        const accountNameLabel = accountNameInput.parentElement.querySelector('label');
        const accountNumberInput = document.getElementById('account_number');
        const accountNumberLabel = accountNumberInput.parentElement.querySelector('label');
        
        // Reset all
        [mobileInput, bankNameInput, accountNameInput, accountNumberInput].forEach(input => {
            input.required = false;
            const label = input.parentElement.querySelector('label');
            const star = label.querySelector('.required-star');
            if (star) star.style.display = 'none';
        });
        
        // Set required based on type
        if (['esewa', 'khalti', 'fonepay', 'imepay'].includes(selectedType)) {
            mobileInput.required = true;
            addRequiredStar(mobileLabel);
        }
        
        if (selectedType === 'bank') {
            bankNameInput.required = true;
            accountNameInput.required = true;
            accountNumberInput.required = true;
            addRequiredStar(bankNameLabel);
            addRequiredStar(accountNameLabel);
            addRequiredStar(accountNumberLabel);
        }
    });
    
    function addRequiredStar(label) {
        let star = label.querySelector('.required-star');
        if (!star) {
            star = document.createElement('span');
            star.className = 'required-star text-danger';
            star.textContent = ' *';
            label.appendChild(star);
        }
        star.style.display = 'inline';
    }
    
    // Initialize on page load
    if (typeSelect.value) {
        typeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush