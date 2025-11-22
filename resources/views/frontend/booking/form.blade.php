@extends('layouts.frontend')

@section('page-title', 'बुकिंग फारम - ' . $hostel->name)
@section('page-header', $hostel->name . ' को लागि बुकिंग')
@section('page-description', 'तलको फारम भरेर कोठा बुक गर्नुहोस्')

@section('content')
<div class="booking-container">
    <div class="booking-form-wrapper">
        <div class="booking-header">
            <h1 class="booking-title">{{ $hostel->name }} कोठा बुकिंग</h1>
            <p class="booking-subtitle">तपाईंको बुकिंग अनुरोध पेश गर्नुहोस्</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('hostel.book.store', $hostel->slug) }}" class="booking-form">
            @csrf
            
            <div class="form-section">
                <h3 class="section-title">व्यक्तिगत जानकारी</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">पूरा नाम *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="तपाईंको पूरा नाम" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">फोन नम्बर *</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               placeholder="९८XXXXXXXX" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">इमेल ठेगाना</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="form-control @error('email') is-invalid @enderror" 
                           placeholder="तपाईंको इमेल ठेगाना">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">बुकिंग जानकारी</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="check_in_date" class="form-label">चेक-इन मिति *</label>
                        <input type="date" id="check_in_date" name="check_in_date" 
                               value="{{ old('check_in_date') }}" 
                               class="form-control @error('check_in_date') is-invalid @enderror" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('check_in_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="room_type" class="form-label">कोठा प्रकार *</label>
                        <select id="room_type" name="room_type" 
                                class="form-control @error('room_type') is-invalid @enderror" required>
                            <option value="">कोठा प्रकार छान्नुहोस्</option>
                            @foreach($roomTypes as $roomType)
                                <option value="{{ $roomType['value'] }}" 
                                    {{ old('room_type') == $roomType['value'] ? 'selected' : '' }}>
                                    {{ $roomType['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label for="message" class="form-label">अतिरिक्त सन्देश (वैकल्पिक)</label>
                    <textarea id="message" name="message" rows="4" 
                              class="form-control @error('message') is-invalid @enderror" 
                              placeholder="तपाईंको अतिरिक्त आवश्यकता वा सन्देश...">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-submit">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane"></i> बुकिंग अनुरोध पेश गर्नुहोस्
                </button>
                
                <a href="{{ route('hostels.show', $hostel->slug) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
                </a>
            </div>
        </form>
    </div>

    <div class="booking-sidebar">
        <div class="hostel-summary">
            <h3 class="sidebar-title">होस्टल विवरण</h3>
            <div class="hostel-info">
                <img src="{{ $hostel->logo_url ?? asset('images/default-hostel.png') }}" 
                     alt="{{ $hostel->name }}" class="hostel-image">
                <h4 class="hostel-name">{{ $hostel->name }}</h4>
                <p class="hostel-address">
                    <i class="fas fa-map-marker-alt"></i> {{ $hostel->address }}, {{ $hostel->city }}
                </p>
                <p class="hostel-contact">
                    <i class="fas fa-phone"></i> {{ $hostel->contact_phone }}
                </p>
            </div>
        </div>

        <div class="booking-info">
            <h3 class="sidebar-title">बुकिंग प्रक्रिया</h3>
            <ul class="process-steps">
                <li class="step">
                    <div class="step-number">१</div>
                    <div class="step-content">
                        <strong>फारम भर्नुहोस्</strong>
                        <p>तलको फारममा आवश्यक जानकारी भर्नुहोस्</p>
                    </div>
                </li>
                <li class="step">
                    <div class="step-number">२</div>
                    <div class="step-content">
                        <strong>अनुरोध पेश गर्नुहोस्</strong>
                        <p>तपाईंको अनुरोध होस्टल प्रबन्धकसम्म पुग्नेछ</p>
                    </div>
                </li>
                <li class="step">
                    <div class="step-number">३</div>
                    <div class="step-content">
                        <strong>सम्पर्क प्राप्त गर्नुहोस्</strong>
                        <p>प्रबन्धकले तपाईंसँग सम्पर्क गरी बाँकी प्रक्रिया पूरा गर्नेछन्</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
.booking-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
}

.booking-form-wrapper {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.booking-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f1f1f1;
}

.booking-title {
    color: #001F5B;
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

.booking-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
}

.form-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: #f8f9fa;
}

.section-title {
    color: #001F5B;
    font-size: 1.2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #495057;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #001F5B;
    box-shadow: 0 0 0 3px rgba(0, 31, 91, 0.1);
}

.form-submit {
    text-align: center;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

.btn-primary {
    background: #001F5B;
    color: white;
}

.btn-primary:hover {
    background: #001338;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
    margin-left: 1rem;
}

.btn-secondary:hover {
    background: #5a6268;
}

.booking-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.hostel-summary, .booking-info {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.sidebar-title {
    color: #001F5B;
    font-size: 1.2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #001F5B;
}

.hostel-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.hostel-name {
    color: #001F5B;
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.hostel-address, .hostel-contact {
    color: #6c757d;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.process-steps {
    list-style: none;
    padding: 0;
}

.step {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.step:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.step-number {
    background: #001F5B;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
}

.step-content strong {
    color: #001F5B;
    display: block;
    margin-bottom: 0.25rem;
}

.step-content p {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
}

.alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.is-invalid {
    border-color: #dc3545;
}

@media (max-width: 968px) {
    .booking-container {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to tomorrow
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const checkInDate = document.getElementById('check_in_date');
    if (checkInDate) {
        checkInDate.min = tomorrow.toISOString().split('T')[0];
    }
    
    // Form validation
    const form = document.querySelector('.booking-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^[0-9+\-\s()]{10,15}$/;
            
            if (!phoneRegex.test(phone)) {
                e.preventDefault();
                alert('कृपया मान्य फोन नम्बर प्रविष्ट गर्नुहोस्');
                return false;
            }
        });
    }
});
</script>
@endsection