@extends('layouts.frontend')

@section('content')
<style>
    .error-message {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
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
                <input type="hidden" name="plan" value="{{ $plan->slug ?? '' }}">
                
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
                
                <!-- Plan Information -->
                <div class="mb-8 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-indigo-800">{{ $plan->name ?? 'योजना' }}</h3>
                            <p class="text-indigo-700 text-lg font-semibold mt-1">
                                मूल्य: रु. {{ number_format($plan->price ?? 0) }}/महिना
                            </p>
                        </div>
                        <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                            लोकप्रिय
                        </span>
                    </div>
                    
                    <div class="mt-4">
                        <h4 class="font-semibold text-indigo-800 mb-3 text-lg">विशेषताहरू:</h4>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @if($plan && $plan->features)
                                @php
                                    // Features लाई array मा बदल्ने
                                    $features = [];
                                    if (is_string($plan->features)) {
                                        // JSON string हो भने
                                        $decoded = json_decode($plan->features, true);
                                        $features = is_array($decoded) ? $decoded : explode(',', $plan->features);
                                    } elseif (is_array($plan->features)) {
                                        $features = $plan->features;
                                    } else {
                                        $features = ['विशेषताहरू उपलब्ध छैनन्'];
                                    }
                                @endphp
                                
                                @foreach($features as $feature)
                                    <li class="flex items-center space-x-2 text-indigo-700">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>{{ trim($feature) }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-indigo-700">योजना विवरण उपलब्ध छैन</li>
                            @endif
                        </ul>
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
@endsection