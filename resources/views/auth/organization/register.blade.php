@extends('layouts.frontend')

@section('content')
<style>
    .error-message {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    /* Global Plan Note */
    .global-plan-note {
        background: #fff8e1;
        border: 2px solid #ffd54f;
        border-radius: 12px;
        padding: 1.25rem;
        margin: 1rem auto 1.5rem auto;
        max-width: 800px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .global-plan-note p {
        font-size: 1.125rem;
        color: #5d4037;
        font-weight: 600;
        margin: 0;
        line-height: 1.5;
    }
    
    .global-plan-note i {
        color: #ff9800;
        margin-right: 8px;
    }
    
    /* Plan Card Styling */
    .plan-card-container {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .popular-badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: #ffc107;
        color: #000;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        z-index: 10;
    }
    
    .plan-content {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));
        border-radius: 12px;
        padding: 2rem;
        border: 2px solid rgba(99, 102, 241, 0.2);
        position: relative;
        overflow: visible;
        margin-top: 0;
    }
    
    /* Add top margin if popular badge exists */
    .plan-card-container.has-badge .plan-content {
        margin-top: 15px;
    }
    
    .capacity-box {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 8px;
        padding: 1.25rem;
        margin: 1.5rem 0;
        border-left: 4px solid #0d6efd;
    }
    
    .capacity-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 15px;
        color: #333;
    }
    
    .capacity-item:last-child {
        margin-bottom: 0;
    }
    
    .capacity-item i {
        color: #0d6efd;
        margin-right: 12px;
        font-size: 16px;
        min-width: 20px;
        text-align: center;
    }
    
    .trial-badge {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
        margin-top: 10px;
    }
    
    .plan-header {
        text-align: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(99, 102, 241, 0.2);
    }
    
    .plan-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1a3a8f;
        margin-bottom: 0.5rem;
    }
    
    .plan-price {
        font-size: 2.25rem;
        font-weight: 800;
        color: #0d6efd;
        margin: 0;
    }
    
    .plan-period {
        color: #6c757d;
        font-size: 1rem;
        margin-top: 0.25rem;
    }
    
    .additional-note {
        margin-top: 1rem;
        padding: 0.75rem;
        background: rgba(13, 110, 253, 0.1);
        border: 1px solid rgba(13, 110, 253, 0.2);
        border-radius: 8px;
        font-size: 0.875rem;
        color: #0d6efd;
    }
    
    .additional-note i {
        color: #0d6efd;
        margin-right: 8px;
    }
</style>

<!-- Registration Form -->
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">होस्टल संचालकको लागि साइन अप गर्नुहोस्</h1>
                <p class="text-gray-600">हाम्रो {{ $plan->name ?? 'योजना' }} सुरु गर्नुहोस्</p>
                <div class="mt-4 bg-indigo-50 text-indigo-700 px-4 py-2 rounded-full inline-block">
                    ७ दिन निःशुल्क परीक्षण | कुनै पनि क्रेडिट कार्ड आवश्यक छैन
                </div>
                <div class="mt-3 bg-yellow-50 text-yellow-700 px-4 py-2 rounded-lg text-sm">
                    <strong>नोट:</strong> दर्ता पश्चात प्रशासकद्वारा स्वीकृतिपछि मात्र तपाईंले आफ्नो ड्यासबोर्डमा पहुँच गर्न सक्नुहुन्छ
                </div>
            </div>
            
            <!-- Global Plan Note -->
            <div class="global-plan-note">
                <p><i class="fas fa-info-circle"></i> सबै योजनाहरूमा समान सुविधाहरू उपलब्ध छन्। फरक केवल विद्यार्थी संख्या र होस्टल क्षमतामा मात्र हो।</p>
            </div>
            
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg">
                    <strong class="font-medium">सावधान!</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register.organization.store') }}" autocomplete="off" id="registration-form">
                @csrf
                
                <!-- Explicit CSRF token field as a backup -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <!-- Plan Type -->
                @php
                    $planSlug = $plan->slug ?? request()->route('plan') ?? request()->query('plan') ?? 'starter';
                    $planName = $plan->name ?? ucfirst($planSlug);
                    
                    // Set prices based on plan
                    $prices = [
                        'starter' => 2999,
                        'pro' => 4999,
                        'enterprise' => 8999
                    ];
                    $price = $plan->price ?? $prices[$planSlug] ?? 0;
                    
                    // Set capacities
                    $capacities = [
                        'starter' => [
                            'students' => '५० विद्यार्थी सम्म',
                            'hostels' => '१ होस्टल सम्म'
                        ],
                        'pro' => [
                            'students' => '२०० विद्यार्थी सम्म',
                            'hostels' => '१ होस्टल सम्म'
                        ],
                        'enterprise' => [
                            'students' => 'असीमित विद्यार्थी',
                            'hostels' => 'बहु-होस्टल व्यवस्थापन (५ होस्टल सम्म)'
                        ]
                    ];
                    $capacity = $capacities[$planSlug] ?? $capacities['starter'];
                @endphp
                <input type="hidden" name="plan" value="{{ $planSlug }}">
                <input type="hidden" name="plan_slug" value="{{ $planSlug }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">होस्टल/संगठनको नाम *</label>
                        <input type="text" id="organization_name" name="organization_name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ old('organization_name') }}" autocomplete="organization"
                            placeholder="तपाईंको होस्टलको नाम">
                        @error('organization_name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">प्रबन्धकको नाम *</label>
                        <input type="text" id="owner_name" name="owner_name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ old('owner_name') }}" autocomplete="name"
                            placeholder="तपाईंको पूरा नाम">
                        @error('owner_name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">इमेल ठेगाना *</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('email') }}" autocomplete="email"
                        placeholder="example@gmail.com">
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Required Fields: Phone, PAN, Address -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">फोन नम्बर *</label>
                        <input type="text" id="phone" name="phone" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ old('phone') }}" autocomplete="tel"
                            placeholder="९८००००००००">
                        @error('phone')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="pan_no" class="block text-sm font-medium text-gray-700 mb-1">PAN नम्बर (वैकल्पिक)</label>
                        <input type="text" id="pan_no" name="pan_no"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ old('pan_no') }}"
                            placeholder="१२३४५६७८९">
                        @error('pan_no')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">ठेगाना *</label>
                    <textarea id="address" name="address" required rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="तपाईंको पूरा ठेगाना">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">पासवर्ड *</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="कम्तिमा ८ वर्णको">
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">पासवर्ड पुष्टि गर्नुहोस् *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="पासवर्ड पुनः लेख्नुहोस्">
                    </div>
                </div>
                
                <!-- Plan Information - FIXED: Proper spacing for popular badge -->
                <div class="plan-card-container {{ $planSlug == 'pro' ? 'has-badge' : '' }}">
                    @if($planSlug == 'pro')
                        <div class="popular-badge">लोकप्रिय</div>
                    @endif
                    
                    <div class="plan-content">
                        <div class="plan-header">
                            <h3 class="plan-title">{{ $planName }} योजना</h3>
                            <p class="plan-price">रु. {{ number_format($price) }}</p>
                            <p class="plan-period">/महिना</p>
                        </div>
                        
                        <!-- Capacity Information -->
                        <div class="capacity-box">
                            <h4 class="font-semibold text-indigo-800 mb-3 text-lg">क्षमता:</h4>
                            <div class="capacity-item">
                                <i class="fas fa-users"></i>
                                <span><strong>विद्यार्थी सीमा:</strong> {{ $capacity['students'] }}</span>
                            </div>
                            <div class="capacity-item">
                                <i class="fas fa-building"></i>
                                <span><strong>होस्टल सीमा:</strong> {{ $capacity['hostels'] }}</span>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <div class="trial-badge">
                                <i class="fas fa-check-circle"></i> ७ दिन निःशुल्क परीक्षण
                            </div>
                        </div>
                        
                        <!-- Additional note for enterprise -->
                        @if($planSlug == 'enterprise')
                            <div class="additional-note">
                                <i class="fas fa-info-circle"></i> 
                                <strong>अतिरिक्त होस्टल थप्न सकिन्छ:</strong> रु. १,०००/महिना प्रति अतिरिक्त होस्टल
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="flex items-start space-x-2">
                        <input type="checkbox" name="terms" required 
                            class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-600">
                            म <a href="{{ route('terms') }}" class="text-indigo-600 hover:underline">सेवा सर्तहरू</a> 
                            र <a href="{{ route('privacy') }}" class="text-indigo-600 hover:underline">गोप्यता नीति</a> 
                            सहमत छु
                        </span>
                    </label>
                    @error('terms')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" 
                    class="w-full bg-indigo-600 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:bg-indigo-700 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    स्वीकृतिको लागि दर्ता गर्नुहोस्
                </button>
            </form>
            
            <div class="mt-8 text-center border-t pt-6">
                <p class="text-gray-600">
                    पहिले नै खाता छ? 
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-semibold">लगइन गर्नुहोस्</a>
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    दर्ता गर्दा कुनै समस्या भएमा 
                    <a href="mailto:support@hostelhub.com" class="text-indigo-600 hover:underline">support@hostelhub.com</a> 
                    मा सम्पर्क गर्नुहोस्
                </p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registration-form');
    
    // ✅ Ensure plan is not empty before submission
    form.addEventListener('submit', function(e) {
        const planInput = document.querySelector('input[name="plan"]');
        if (!planInput || !planInput.value) {
            e.preventDefault();
            
            // Try to get plan from URL
            const urlParams = new URLSearchParams(window.location.search);
            const planFromUrl = urlParams.get('plan');
            const planFromRoute = window.location.pathname.split('/').pop();
            
            if (planFromUrl) {
                planInput.value = planFromUrl;
                form.submit();
            } else if (planFromRoute && ['starter', 'pro', 'enterprise'].includes(planFromRoute)) {
                planInput.value = planFromRoute;
                form.submit();
            } else {
                alert('Please select a subscription plan. Redirecting to pricing page...');
                window.location.href = '/pricing';
            }
        }
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('पासवर्डहरू मेल खाएनन्। कृपया पुनः प्रयास गर्नुहोस्।');
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('पासवर्ड कम्तिमा ८ वर्णको हुनुपर्छ।');
            return false;
        }

        // Validate phone number (Nepali format)
        const phone = document.getElementById('phone').value;
        const phoneRegex = /^[9][6-8]\d{8}$/;
        if (!phoneRegex.test(phone.replace(/\D/g, ''))) {
            e.preventDefault();
            alert('कृपया मान्य नेपाली फोन नम्बर प्रविष्ट गर्नुहोस् (९८०००००००० को रूपमा)');
            return false;
        }
    });
    
    // Real-time password match check
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    function checkPasswordMatch() {
        if (passwordInput.value && confirmPasswordInput.value) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.classList.add('border-red-500');
            } else {
                confirmPasswordInput.classList.remove('border-red-500');
            }
        }
    }
    
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        e.target.value = value;
    });
});
</script>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection