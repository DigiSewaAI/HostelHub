@extends('layouts.owner')

@section('title', 'होस्टेल सीमाहरू')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">होस्टेल सीमाहरू व्यवस्थापन</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subscription.show') }}">सदस्यता</a></li>
                    <li class="breadcrumb-item active">होस्टेल सीमाहरू</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">होस्टेल सीमा विवरण</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">वर्तमान योजना</label>
                                <p class="fs-5 text-primary">{{ $subscription->plan->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">अनुमत होस्टेलहरू</label>
                                <p class="fs-5">{{ $subscription->plan->max_hostels + $subscription->extra_hostels }}</p>
                                <small class="text-muted">
                                    (मूल: {{ $subscription->plan->max_hostels }} + अतिरिक्त: {{ $subscription->extra_hostels }})
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">प्रयोग भएका होस्टेलहरू</label>
                                <p class="fs-5">{{ $hostelsCount }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">बाँकी स्लटहरू</label>
                                <p class="fs-5">
                                    <span class="badge {{ $remainingSlots > 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                        {{ $remainingSlots }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">प्रयोग भएको सीमा</label>
                        @php
                            $totalAllowed = $subscription->plan->max_hostels + $subscription->extra_hostels;
                            $usagePercentage = $totalAllowed > 0 ? round(($hostelsCount / $totalAllowed) * 100) : 0;
                        @endphp
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar {{ $usagePercentage >= 90 ? 'bg-danger' : ($usagePercentage >= 70 ? 'bg-warning' : 'bg-success') }}" 
                                 role="progressbar" 
                                 style="width: {{ $usagePercentage }}%;"
                                 aria-valuenow="{{ $usagePercentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ $usagePercentage }}%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small>०</small>
                            <small>{{ $totalAllowed }}</small>
                        </div>
                    </div>

                    @if($remainingSlots == 0)
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle"></i> सीमा पुगिसक्यो!
                        </h6>
                        <p class="mb-0">तपाईंले आफ्नो योजनामा अनुमति भएको सबै होस्टेलहरू प्रयोग गर्नुभएको छ। नयाँ होस्टेल थप्न अतिरिक्त स्लट खरिद गर्नुहोस्।</p>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle"></i> सीमा बारे जानकारी
                        </h6>
                        <p class="mb-0">तपाईंसँग अहिले <strong>{{ $remainingSlots }}</strong> होस्टेल थप्ने स्लट उपलब्ध छ{{ $remainingSlots > 1 ? 'न्' : '' }}।</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if($remainingSlots == 0 || $subscription->plan->slug === 'enterprise')
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">अतिरिक्त होस्टेल खरिद गर्नुहोस्</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted">
                            <i class="fas fa-tag"></i> 
                            <strong>मूल्य:</strong> रु. १,००० प्रति अतिरिक्त होस्टल / महिना
                        </p>
                    </div>

                    <form action="{{ route('subscription.purchase-extra-hostel') }}" method="POST" id="extraHostelForm">
                        @csrf
                        <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-bold">अतिरिक्त होस्टेल सङ्ख्या</label>
                            <select name="quantity" id="quantity" class="form-select" required>
                                <option value="">सङ्ख्या छान्नुहोस्</option>
                                @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} होस्टेल - रु. {{ number_format(1000 * $i) }}/महिना</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">कुल अतिरिक्त शुल्क</label>
                            <div id="totalCost" class="fs-5 text-primary">रु. ०</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                            <i class="fas fa-shopping-cart"></i> अतिरिक्त होस्टेल खरिद गर्नुहोस्
                        </button>
                    </form>

                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            अतिरिक्त होस्टेलहरू तत्काल सक्रिय हुनेछन् र तपाईंको अर्को बिलमा समावेश गरिनेछ।
                        </small>
                    </div>
                </div>
            </div>
            @else
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <h5>सीमा अपरेटिङ्ग भित्र छ</h5>
                    <p class="text-muted">तपाईंसँग अहिले नै पर्याप्त होस्टेल स्लट उपलब्ध छन्।</p>
                    <a href="{{ route('owner.hostels.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> नयाँ होस्टेल सिर्जना गर्नुहोस्
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantitySelect = document.getElementById('quantity');
    const totalCostDisplay = document.getElementById('totalCost');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('extraHostelForm');

    // Calculate total cost
    quantitySelect.addEventListener('change', function() {
        const quantity = parseInt(this.value) || 0;
        const totalCost = quantity * 1000;
        totalCostDisplay.textContent = 'रु. ' + totalCost.toLocaleString('en-IN');
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!quantitySelect.value) {
            alert('कृपया अतिरिक्त होस्टेल सङ्ख्या छान्नुहोस्।');
            return;
        }

        const confirmed = confirm('के तपाईं निश्चित हुनुहुन्छ कि तपाईं अतिरिक्त होस्टेल खरिद गर्न चाहनुहुन्छ?');
        if (!confirmed) return;

        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> प्रक्रिया हुदैछ...';

        try {
            const formData = new FormData(this);
            
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert(data.message || 'अतिरिक्त होस्टेल सफलतापूर्वक खरिद गरियो।');
                window.location.reload();
            } else {
                throw new Error(data.message || 'अज्ञात त्रुटि');
            }
        } catch (error) {
            alert('त्रुटि: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
});
</script>
@endsection