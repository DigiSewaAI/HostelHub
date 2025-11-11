@extends('layouts.frontend')

@section('page-title', 'सम्पर्क - HostelHub')
@section('page-header', 'सम्पर्क गर्नुहोस्')
@section('page-description', 'तपाईंसँग कुनै प्रश्न वा सुझाव छ भने हामीलाई तलको फारम वा नक्सा प्रयोग गरेर सम्पर्क गर्न सक्नुहुन्छ।')

@section('content')
<div class="contact-container">
    <div class="contact-grid">
        {{-- Contact Form --}}
        <div class="contact-form-container">
            {{-- ✅ FIXED: Auto-hide Success Message --}}
            @if(session('success'))
                <div id="successAlert" class="success-message alert-show">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="close-alert" onclick="hideAlert()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="contact-form" id="contactForm">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">नाम *</label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            class="form-input"
                            placeholder="तपाईंको पूरा नाम"
                        >
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">इमेल *</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            class="form-input"
                            placeholder="तपाईंको इमेल ठेगाना"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">फोन नम्बर</label>
                    <input 
                        type="tel" 
                        id="phone"
                        name="phone" 
                        value="{{ old('phone') }}" 
                        class="form-input"
                        placeholder="तपाईंको फोन नम्बर"
                    >
                </div>

                <div class="form-group">
                    <label for="subject" class="form-label">विषय *</label>
                    <input 
                        type="text" 
                        id="subject"
                        name="subject" 
                        value="{{ old('subject') }}" 
                        required 
                        class="form-input"
                        placeholder="सन्देशको विषय"
                    >
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">सन्देश *</label>
                    <textarea 
                        id="message"
                        name="message" 
                        rows="5" 
                        required 
                        class="form-input"
                        placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..."
                    >{{ old('message') }}</textarea>
                </div>

                <div class="form-submit">
                    <button type="submit" class="submit-button" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> सन्देश पठाउनुहोस्
                    </button>
                </div>
            </form>
        </div>

        {{-- Google Maps --}}
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.310614597818!2d85.3240!3d27.7079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb190a6d1c9d9f%3A0x2ef7c9dfb4b6a0d!2sKathmandu%20Durbar%20Square!5e0!3m2!1sen!2snp!4v1639397261912!5m2!1sen!2snp" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>

    {{-- Contact Info Section --}}
    <div class="contact-info-grid">
        <div class="contact-info-card">
            <div class="contact-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h3 class="contact-info-title">📍 ठेगाना</h3>
            <p class="contact-info-detail">कमलपोखरी, काठमाडौं, नेपाल</p>
        </div>
        <div class="contact-info-card">
            <div class="contact-icon">
                <i class="fas fa-phone-alt"></i>
            </div>
            <h3 class="contact-info-title">📞 फोन</h3>
            <p class="contact-info-detail">+९७७ ९७६१७६२०३६</p>
        </div>
        <div class="contact-info-card">
            <div class="contact-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h3 class="contact-info-title">✉ इमेल</h3>
            <p class="contact-info-detail">info@hostelhub.com</p>
        </div>
    </div>
</div>

<style>
    .contact-container {
        padding: 2rem 0;
    }
    
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .contact-form-container {
        background: white;
        padding: 2rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        position: relative;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .form-input {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        font-family: inherit;
        font-size: 1rem;
        transition: var(--transition);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
    }
    
    .form-submit {
        text-align: right;
    }
    
    .submit-button {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        border: none;
        border-radius: var(--radius);
        padding: 0.85rem 1.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .submit-button:hover {
        background: linear-gradient(to right, var(--primary-dark), var(--secondary-dark));
        transform: translateY(-2px);
        box-shadow: var(--glow);
    }
    
    .map-container {
        height: 450px;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    
    .contact-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    
    .contact-info-card {
        background: white;
        padding: 1.5rem;
        border-radius: var(--radius);
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
    }
    
    .contact-info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .contact-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: var(--bg-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--secondary);
    }
    
    .contact-info-title {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        color: var(--primary);
    }
    
    .contact-info-detail {
        color: var(--text-dark);
        margin: 0;
    }
    
    /* ✅ FIXED: Success Message Styles */
    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 1rem 1rem 1rem 3rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        border-left: 4px solid #28a745;
        animation: slideIn 0.5s ease-out;
    }
    
    .success-message::before {
        content: '✓';
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        font-weight: bold;
    }
    
    .close-alert {
        background: none;
        border: none;
        color: #155724;
        cursor: pointer;
        padding: 0.25rem;
        margin-left: auto;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s;
    }
    
    .close-alert:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        border-left: 4px solid #dc3545;
    }
    
    .error-message ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    
    /* Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
    
    .alert-hide {
        animation: slideOut 0.5s ease-in forwards;
    }
    
    /* Responsive Design */
    @media (max-width: 968px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
        
        .map-container {
            height: 350px;
            order: -1;
        }
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .contact-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
// ✅ FIXED: Auto-hide success message
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.getElementById('successAlert');
    
    if (successAlert) {
        // Auto hide after 5 seconds
        setTimeout(() => {
            hideAlert();
        }, 5000);
        
        // Hide on close button click
        const closeBtn = successAlert.querySelector('.close-alert');
        if (closeBtn) {
            closeBtn.addEventListener('click', hideAlert);
        }
    }
    
    // Form submission handling
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function() {
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> पठाइदै...';
            }
        });
    }
});

function hideAlert() {
    const alert = document.getElementById('successAlert');
    if (alert) {
        alert.classList.add('alert-hide');
        setTimeout(() => {
            alert.remove();
        }, 500);
    }
}

{{-- public contact form मा यो hidden field थप्नुहोस् --}}
@if(isset($room) && $room)
    <input type="hidden" name="room_id" value="{{ $room->id }}">
    <input type="hidden" name="hostel_id" value="{{ $room->hostel_id }}">
@endif

// Form validation
function validateForm() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value.trim();
    const message = document.getElementById('message').value.trim();
    
    if (!name || !email || !subject || !message) {
        alert('कृपया सबै आवश्यक फिल्डहरू भर्नुहोस्');
        return false;
    }
    
    return true;
}
</script>
@endsection