@extends('layouts.owner')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">नयाँ विद्यार्थी दर्ता</h1>
        <a href="{{ route('owner.students.index') }}"
           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow transition duration-300 no-underline z-10"
           style="text-decoration: none;">
            ⬅ फर्कनुहोस्
        </a>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <span class="text-lg">✅</span>
                <span class="ml-2 font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong class="font-medium">गल्तीहरू:</strong>
            <ul class="list-disc ml-6 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Create Student Form --}}
    <form action="{{ route('owner.students.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-6" id="studentForm">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left Column --}}
            <div>
                {{-- Select User --}}
                <div class="mb-4">
                    <label for="user_id" class="block font-semibold mb-2">पहिले नै रजिस्टर्ड प्रयोगकर्ता छान्नुहोस् (वैकल्पिक)</label>
                    <p class="text-sm text-gray-500 mb-2">
                        यदि विद्यार्थी पहिले नै registered user हुन् भने, यहाँबाट छान्नुहोस्।  
                        नभए तलको form भरेर नयाँ विद्यार्थी दर्ता गर्नुहोस्।
                    </p>
                    <select name="user_id" id="user_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- नयाँ विद्यार्थी दर्ता गर्नुहोस् --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @if($users->isEmpty())
                        <p class="text-sm text-red-500 mt-1">
                            ❌ कुनै पनि available user छैन। तलको form भरेर नयाँ विद्यार्थी दर्ता गर्नुहोस्।
                        </p>
                    @endif
                </div>

                {{-- Name --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">नाम *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="विद्यार्थीको पुरा नाम">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">इमेल (वैकल्पिक)</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="student@example.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- College --}}
                <div class="mb-4">
                    <label for="college_id" class="block text-sm font-medium text-gray-700">कलेज *</label>
                    <select name="college_id" id="college_id" required
                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- कलेज छान्नुहोस् --</option>
                        @foreach($colleges as $college)
                            <option value="{{ $college->id }}" {{ old('college_id') == $college->id ? 'selected' : '' }}>
                                {{ $college->name }}
                            </option>
                        @endforeach
                        <option value="others" {{ old('college_id') == 'others' ? 'selected' : '' }}>अन्य (Others)</option>
                    </select>
                    
                    <div id="other_college_field" class="mt-2 {{ old('college_id') == 'others' ? '' : 'hidden' }}">
                        <label for="other_college" class="block text-sm font-medium text-gray-700">कलेजको नाम *</label>
                        <input type="text" name="other_college" value="{{ old('other_college') }}" 
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" 
                               placeholder="कलेजको नाम लेख्नुहोस्"
                               id="other_college_input">
                        @error('other_college')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($colleges->isEmpty())
                        <p class="text-sm text-yellow-600 mt-1">
                            ⚠️ कुनै पनि कलेज भेटिएन। तपाईं "अन्य" छानेर नयाँ कलेज थप्न सक्नुहुन्छ।
                        </p>
                    @else
                        <p class="text-sm text-green-600 mt-1">
                            ✅ कुल {{ $colleges->count() }} वटा कलेजहरू उपलब्ध छन्
                        </p>
                    @endif
                    @error('college_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">फोन *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required 
                           maxlength="15"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="98XXXXXXXX">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DOB --}}
                <div class="mb-4">
                    <label for="dob" class="block text-sm font-medium text-gray-700">जन्म मिति (वैकल्पिक)</label>
                    <input type="date" name="dob" value="{{ old('dob') }}" 
                           min="1950-01-01" max="{{ date('Y-m-d') }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                    @error('dob')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gender --}}
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">लिङ्ग (वैकल्पिक)</label>
                    <select name="gender" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- लिङ्ग छान्नुहोस् --</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>पुरुष</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>महिला</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>अन्य</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                {{-- Guardian Name --}}
                <div class="mb-4">
                    <label for="guardian_name" class="block text-sm font-medium text-gray-700">अभिभावकको नाम *</label>
                    <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" required 
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="अभिभावकको पुरा नाम">
                    @error('guardian_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Guardian Phone Field --}}
                <div class="mb-4">
                    <label for="guardian_phone" class="block text-sm font-medium text-gray-700">अभिभावकको फोन *</label>
                    <input type="text" name="guardian_phone" value="{{ old('guardian_phone') }}" required 
                           maxlength="15"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="98XXXXXXXX">
                    @error('guardian_phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Guardian Relation --}}
                <div class="mb-4">
                    <label for="guardian_relation" class="block text-sm font-medium text-gray-700">नाता *</label>
                    <input type="text" name="guardian_relation" value="{{ old('guardian_relation') }}" required 
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="जस्तै: बुबा, आमा, दाजु, etc.">
                    @error('guardian_relation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Room --}}
                <div class="mb-4">
                    <label for="room_id" class="block text-sm font-medium text-gray-700">कोठा छान्नुहोस् (वैकल्पिक)</label>
                    <select name="room_id" id="room_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- कोठा तोकिएको छैन --</option>
                        @forelse($rooms as $room)
                            <option value="{{ $room->id }}" 
                                    data-price="{{ $room->price ?? 0 }}"
                                    {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->room_number }} 
                                ({{ $room->hostel?->name ?? 'Unknown Hostel' }})
                                - {{ $room->type ?? 'Standard' }}
                                - स्थिति: {{ $room->nepali_status ?? $room->status }}
                            </option>
                        @empty
                            <option disabled>❌ कुनै पनि कोठा उपलब्ध छैन। कृपया पहिले कोठा सिर्जना गर्नुहोस्।</option>
                        @endforelse
                    </select>
                    @if($rooms->isEmpty())
                        <p class="text-sm text-red-500 mt-1">
                            ❌ कुनै पनि कोठा उपलब्ध छैन। कृपया पहिले कोठा सिर्जना गर्नुहोस्।
                        </p>
                    @else
                        <p class="text-sm text-green-600 mt-1">
                            ✅ कुल {{ $rooms->count() }} वटा कोठाहरू उपलब्ध छन्
                        </p>
                    @endif
                    @error('room_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ✅ INITIAL PAYMENT SECTION (सधैं देखिने) --}}
                <div id="initial_payment_section" class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h3 class="font-semibold text-gray-800 mb-3">🏷️ प्रारम्भिक भुक्तानी</h3>
                    
                    {{-- Payment Status --}}
                    <div class="mb-3">
                        <label for="initial_payment_status" class="block text-sm font-medium text-gray-700">भुक्तानी स्थिति *</label>
                        <select name="initial_payment_status" id="initial_payment_status" 
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                            <option value="">-- छान्नुहोस् --</option>
                            <option value="pending" {{ old('initial_payment_status') == 'pending' ? 'selected' : '' }}>पेन्डिङ</option>
                            <option value="paid" {{ old('initial_payment_status') == 'paid' ? 'selected' : '' }}>भुक्तानी भएको</option>
                        </select>
                        @error('initial_payment_status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        {{-- Amount --}}
                        <div>
                            <label for="initial_payment_amount" class="block text-sm font-medium text-gray-700">रकम *</label>
                            <input type="number" step="0.01" name="initial_payment_amount" id="initial_payment_amount" 
                                   value="{{ old('initial_payment_amount', '') }}" 
                                   class="mt-1 block w-full border rounded-lg px-3 py-2 bg-gray-100 focus:ring focus:ring-blue-300"
                                   readonly
                                   placeholder="कोठा रकम स्वतः आउँछ">
                            @error('initial_payment_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Payment Method --}}
                        <div>
                            <label for="initial_payment_method" class="block text-sm font-medium text-gray-700">भुक्तानी विधि</label>
                            <select name="initial_payment_method" id="initial_payment_method" 
                                    class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                                <option value="">-- छान्नुहोस् --</option>
                                <option value="cash" {{ old('initial_payment_method') == 'cash' ? 'selected' : '' }}>नगद</option>
                                <option value="bank_transfer" {{ old('initial_payment_method') == 'bank_transfer' ? 'selected' : '' }}>बैंक</option>
                                <option value="online" {{ old('initial_payment_method') == 'online' ? 'selected' : '' }}>अनलाइन</option>
                                <option value="cheque" {{ old('initial_payment_method') == 'cheque' ? 'selected' : '' }}>चेक</option>
                            </select>
                            @error('initial_payment_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Payment Date --}}
                    <div class="mt-3">
                        <label for="initial_payment_date" class="block text-sm font-medium text-gray-700">भुक्तानी मिति</label>
                        <input type="date" name="initial_payment_date" id="initial_payment_date" 
                               value="{{ old('initial_payment_date', date('Y-m-d')) }}" 
                               min="2000-01-01" max="{{ date('Y-m-d', strtotime('+1 year')) }}"
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        @error('initial_payment_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="text-xs text-gray-500 mt-2">⚠️ भुक्तानी स्थिति "भुक्तानी भएको" भएमा भुक्तानी विधि र मिति अनिवार्य हुनेछ।</p>
                </div>

                {{-- Admission Date --}}
                <div class="mb-4">
                    <label for="admission_date" class="block text-sm font-medium text-gray-700">भर्ना मिति *</label>
                    <input type="date" name="admission_date" value="{{ old('admission_date', date('Y-m-d')) }}" 
                           min="2000-01-01" max="{{ date('Y-m-d', strtotime('+1 year')) }}"
                           required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                    @error('admission_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Student Status --}}
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">विद्यार्थी स्थिति *</label>
                    <select name="status" id="status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>पेन्डिङ</option>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>स्वीकृत</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>सक्रिय</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>निष्क्रिय</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Address Fields --}}
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-gray-700">ठेगाना *</label>
            <textarea name="address" rows="3" required 
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                      placeholder="विद्यार्थीको हालको ठेगाना">{{ old('address') }}</textarea>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4">
            <label for="guardian_address" class="block text-sm font-medium text-gray-700">अभिभावकको ठेगाना *</label>
            <textarea name="guardian_address" rows="3" required 
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                      placeholder="अभिभावकको स्थायी ठेगाना">{{ old('guardian_address') }}</textarea>
            @error('guardian_address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Hidden organization_id field --}}
        <input type="hidden" name="organization_id" value="{{ auth()->user()->organization_id }}">

        {{-- Submit --}}
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('owner.students.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow flex items-center gap-1 transition duration-300">
                ❌ रद्द गर्नुहोस्
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow flex items-center gap-1 transition duration-300">
                💾 विद्यार्थी दर्ता गर्नुहोस्
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- College "Others" option functionality ---
    const collegeSelect = document.getElementById('college_id');
    const otherCollegeField = document.getElementById('other_college_field');
    const otherCollegeInput = document.getElementById('other_college_input');
    
    if (collegeSelect && otherCollegeField) {
        collegeSelect.addEventListener('change', function() {
            if (this.value === 'others') {
                otherCollegeField.classList.remove('hidden');
                if (otherCollegeInput) otherCollegeInput.required = true;
            } else {
                otherCollegeField.classList.add('hidden');
                if (otherCollegeInput) {
                    otherCollegeInput.required = false;
                    otherCollegeInput.value = '';
                }
            }
        });
        // Initialize on page load
        if (collegeSelect.value === 'others') {
            otherCollegeField.classList.remove('hidden');
            if (otherCollegeInput) otherCollegeInput.required = true;
        }
    }

    // --- Room & Initial Payment Section ---
    const roomSelect = document.getElementById('room_id');
    const amountField = document.getElementById('initial_payment_amount');

    function updateAmountField() {
        if (!roomSelect || !amountField) return;
        
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.price !== undefined) {
            // यदि room select गरिएको छ भने, price set गर्ने
            amountField.value = selectedOption.dataset.price;
        } else {
            // room select गरिएको छैन भने 0 राख्ने
            amountField.value = '0';
        }
    }

    if (roomSelect && amountField) {
        roomSelect.addEventListener('change', updateAmountField);
        // पेज लोड हुँदा पनि update गर्ने (यदि room पहिले नै select छ भने)
        updateAmountField();
    }

    // --- Form submission validation ---
    const form = document.getElementById('studentForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let firstErrorField = null;

            // Required fields validation
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    if (!firstErrorField) firstErrorField = field;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            // College validation - "others" and other_college filled
            if (collegeSelect && collegeSelect.value === 'others') {
                const otherCollege = document.querySelector('input[name="other_college"]');
                if (!otherCollege || !otherCollege.value.trim()) {
                    isValid = false;
                    if (!firstErrorField) firstErrorField = otherCollege;
                    if (otherCollege) otherCollege.classList.add('border-red-500');
                    alert('कृपया कलेजको नाम लेख्नुहोस्।');
                }
            }

            // Phone number validation
            const phone = document.querySelector('input[name="phone"]');
            if (phone && phone.value) {
                const phoneRegex = /^[0-9+\-\s()]{7,15}$/;
                if (!phoneRegex.test(phone.value)) {
                    isValid = false;
                    if (!firstErrorField) firstErrorField = phone;
                    phone.classList.add('border-red-500');
                    alert('कृपया वैध फोन नम्बर लेख्नुहोस्।');
                }
            }

            // Guardian phone validation
            const guardianPhone = document.querySelector('input[name="guardian_phone"]');
            if (guardianPhone && guardianPhone.value) {
                const phoneRegex = /^[0-9+\-\s()]{7,15}$/;
                if (!phoneRegex.test(guardianPhone.value)) {
                    isValid = false;
                    if (!firstErrorField) firstErrorField = guardianPhone;
                    guardianPhone.classList.add('border-red-500');
                    alert('कृपया वैध अभिभावकको फोन नम्बर लेख्नुहोस्।');
                }
            }

            // ✅ Initial payment validation (जब status 'paid' हुन्छ, तब amount, method, date भरिएको छ कि जाँच)
            const paymentStatus = document.getElementById('initial_payment_status');
            const paymentAmount = document.getElementById('initial_payment_amount');
            const paymentMethod = document.getElementById('initial_payment_method');
            const paymentDate = document.getElementById('initial_payment_date');

            if (paymentStatus && paymentStatus.value === 'paid') {
                // Amount check (0 भए पनि चल्छ, तर खाली भए चल्दैन)
                if (!paymentAmount.value || paymentAmount.value === '') {
                    isValid = false;
                    if (!firstErrorField) firstErrorField = paymentAmount;
                    paymentAmount.classList.add('border-red-500');
                    alert('कृपया भुक्तानी रकम भर्नुहोस् (कोठा चयन गर्नुहोस्)।');
                }
                if (!paymentMethod.value) {
                    isValid = false;
                    if (!firstErrorField) firstErrorField = paymentMethod;
                    paymentMethod.classList.add('border-red-500');
                    alert('कृपया भुक्तानी विधि छान्नुहोस्।');
                }
                if (!paymentDate.value) {
                    isValid = false;
                    if (!firstErrorField) firstErrorField = paymentDate;
                    paymentDate.classList.add('border-red-500');
                    alert('कृपया भुक्तानी मिति चयन गर्नुहोस्।');
                }
            }

            if (!isValid) {
                e.preventDefault();
                if (firstErrorField) {
                    firstErrorField.focus();
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }

            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '⏳ दर्ता हुँदैछ...';
            }
        });
    }

    // Real-time validation (remove red border on input)
    const form = document.getElementById('studentForm');
    if (form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500');
                }
            });
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500');
                }
            });
        });
    }
});
</script>

<style>
.border-red-500 {
    border-color: #ef4444 !important;
    border-width: 2px;
}
.hidden {
    display: none;
}
</style>
@endsection