@extends('layouts.owner')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">विद्यार्थी सम्पादन: {{ $student->name }}</h1>
        <a href="{{ route('owner.students.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            ⬅ फर्कनुहोस्
        </a>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Edit Student Form --}}
    <form action="{{ route('owner.students.update', $student->id) }}" method="POST" class="bg-white shadow-md rounded-lg p-6" id="studentForm">
        @csrf
        @method('PUT')

        {{-- 🔥 CRITICAL: Hidden hostel_id field to prevent NULL updates --}}
        <input type="hidden" name="hostel_id" value="{{ $student->hostel_id ?? auth()->user()->hostel_id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left Column --}}
            <div>
                {{-- Name Field --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">नाम *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $student->name) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email Field --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        ईमेल 
                        @if($student->email)
                            <span class="text-xs text-green-600">(हालको: {{ $student->email }})</span>
                        @else
                            <span class="text-xs text-gray-500">(वैकल्पिक)</span>
                        @endif
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="student@example.com"
                           @if($student->email) readonly @endif>
                    
                    @if($student->email)
                        <p class="text-xs text-blue-500 mt-1">
                            ⓘ यो ईमेल पहिले नै सेट गरिएको छ। यसलाई परिवर्तन गर्न आवश्यक भएमा, प्रशासकलाई सम्पर्क गर्नुहोस्।
                        </p>
                    @endif
                    
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- User --}}
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700">प्रयोगकर्ता</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="0">-- कुनै प्रयोगकर्ता छैन --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $student->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- College Selection --}}
                <div class="mb-4">
                    <label for="college_id" class="block text-sm font-medium text-gray-700">कलेज *</label>
                    <select name="college_id" id="college_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                        <option value="">-- कलेज छान्नुहोस् --</option>
                        @foreach($colleges as $college)
                            <option value="{{ $college->id }}" {{ old('college_id', $student->college_id) == $college->id ? 'selected' : '' }}>
                                {{ $college->name }}
                            </option>
                        @endforeach
                        <option value="others" {{ old('college_id', $student->college_id) === null && $student->college ? 'selected' : '' }}>अन्य</option>
                    </select>
                    @error('college_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4" id="other_college_field" style="{{ (old('college_id', $student->college_id) === null && $student->college) ? '' : 'display: none;' }}">
                    <label for="other_college" class="block text-sm font-medium text-gray-700">अन्य कलेजको नाम</label>
                    <input type="text" name="other_college" id="other_college" value="{{ old('other_college', $student->college_id ? '' : $student->college) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                    @error('other_college')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">फोन *</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DOB --}}
                <div class="mb-4">
                    <label for="dob" class="block text-sm font-medium text-gray-700">जन्म मिति</label>
                    <input type="date" name="dob" id="dob" value="{{ old('dob', $student->dob ? $student->dob->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                    @error('dob')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gender --}}
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">लिङ्ग</label>
                    <select name="gender" id="gender" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- लिङ्ग छान्नुहोस् --</option>
                        <option value="male" {{ old('gender', $student->gender)=='male' ? 'selected' : '' }}>पुरुष</option>
                        <option value="female" {{ old('gender', $student->gender)=='female' ? 'selected' : '' }}>महिला</option>
                        <option value="other" {{ old('gender', $student->gender)=='other' ? 'selected' : '' }}>अन्य</option>
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
                    <input type="text" name="guardian_name" id="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                    @error('guardian_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Guardian Contact --}}
                <div class="mb-4">
                    <label for="guardian_contact" class="block text-sm font-medium text-gray-700">अभिभावकको सम्पर्क *</label>
                    <input type="text" name="guardian_contact" id="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                    @error('guardian_contact')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Guardian Relation --}}
                <div class="mb-4">
                    <label for="guardian_relation" class="block text-sm font-medium text-gray-700">अभिभावकको सम्बन्ध *</label>
                    <input type="text" name="guardian_relation" id="guardian_relation" value="{{ old('guardian_relation', $student->guardian_relation) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                    @error('guardian_relation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Room --}}
                <div class="mb-4">
                    <label for="room_id" class="block text-sm font-medium text-gray-700">कोठा तोक्नुहोस्</label>
                    <select name="room_id" id="room_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- कुनै कोठा तोकिएको छैन --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" 
                                    data-price="{{ $room->price ?? 0 }}"
                                    {{ old('room_id', $student->room_id) == $room->id ? 'selected' : '' }}>
                                {{ $room->room_number }} ({{ $room->hostel->name }}) - रु.{{ number_format($room->price ?? 0, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- INITIAL PAYMENT SECTION --}}
                <div id="initial_payment_section" class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50 {{ old('room_id', $student->room_id) ? '' : 'hidden' }}">
                    <h3 class="font-semibold text-gray-800 mb-3">🏷️ प्रारम्भिक भुक्तानी</h3>
                    
                    {{-- Payment Status --}}
                    <div class="mb-3">
                        <label for="initial_payment_status" class="block text-sm font-medium text-gray-700">भुक्तानी स्थिति *</label>
                        <select name="initial_payment_status" id="initial_payment_status" 
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                            <option value="">-- छान्नुहोस् --</option>
                            <option value="pending" {{ old('initial_payment_status', $initialPayment->status ?? 'pending') == 'pending' ? 'selected' : '' }}>पेन्डिङ</option>
                            <option value="paid" {{ old('initial_payment_status', $initialPayment->status ?? '') == 'paid' ? 'selected' : '' }}>भुक्तानी भएको</option>
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
                                   value="{{ old('initial_payment_amount', $initialPayment->amount ?? ($student->room->price ?? '')) }}" 
                                   class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                                   placeholder="कोठा रकम">
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
                                <option value="cash" {{ old('initial_payment_method', $initialPayment->payment_method ?? '') == 'cash' ? 'selected' : '' }}>नगद</option>
                                <option value="bank" {{ old('initial_payment_method', $initialPayment->payment_method ?? '') == 'bank' ? 'selected' : '' }}>बैंक</option>
                                <option value="online" {{ old('initial_payment_method', $initialPayment->payment_method ?? '') == 'online' ? 'selected' : '' }}>अनलाइन</option>
                                <option value="cheque" {{ old('initial_payment_method', $initialPayment->payment_method ?? '') == 'cheque' ? 'selected' : '' }}>चेक</option>
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
                               value="{{ old('initial_payment_date', $initialPayment->payment_date ? $initialPayment->payment_date->format('Y-m-d') : ($student->admission_date ? $student->admission_date->format('Y-m-d') : date('Y-m-d'))) }}" 
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
                    <input type="date" name="admission_date" id="admission_date" value="{{ old('admission_date', $student->admission_date ? $student->admission_date->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                    @error('admission_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Student Status --}}
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">स्थिति *</label>
                    <select name="status" id="status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                        <option value="pending" {{ old('status', $student->status)=='pending' ? 'selected' : '' }}>पेन्डिङ</option>
                        <option value="approved" {{ old('status', $student->status)=='approved' ? 'selected' : '' }}>स्वीकृत</option>
                        <option value="active" {{ old('status', $student->status)=='active' ? 'selected' : '' }}>सक्रिय</option>
                        <option value="inactive" {{ old('status', $student->status)=='inactive' ? 'selected' : '' }}>निष्क्रिय</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-gray-700">ठेगाना *</label>
            <textarea name="address" id="address" rows="3"
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>{{ old('address', $student->address) }}</textarea>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4">
            <label for="guardian_address" class="block text-sm font-medium text-gray-700">अभिभावकको ठेगाना *</label>
            <textarea name="guardian_address" id="guardian_address" rows="3"
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>{{ old('guardian_address', $student->guardian_address) }}</textarea>
            @error('guardian_address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                🔄 विद्यार्थी अद्यावधिक गर्नुहोस्
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- College "Others" option functionality ---
    const collegeSelect = document.getElementById('college_id');
    const otherCollegeField = document.getElementById('other_college_field');
    
    if (collegeSelect && otherCollegeField) {
        collegeSelect.addEventListener('change', function() {
            if (this.value === 'others') {
                otherCollegeField.style.display = 'block';
            } else {
                otherCollegeField.style.display = 'none';
            }
        });
        // Trigger on page load
        collegeSelect.dispatchEvent(new Event('change'));
    }

    // --- Room & Initial Payment Section ---
    const roomSelect = document.getElementById('room_id');
    const paymentSection = document.getElementById('initial_payment_section');
    const amountField = document.getElementById('initial_payment_amount');

    function updatePaymentSection() {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        const hasRoom = roomSelect.value && selectedOption && selectedOption.dataset.price !== undefined;
        
        if (hasRoom) {
            paymentSection.classList.remove('hidden');
            // If amount field is empty, auto-fill with room price; otherwise keep existing value
            if (amountField.value.trim() === '') {
                amountField.value = selectedOption.dataset.price;
            }
        } else {
            paymentSection.classList.add('hidden');
        }
    }

    if (roomSelect && paymentSection && amountField) {
        roomSelect.addEventListener('change', updatePaymentSection);
        // Initialize on page load
        updatePaymentSection();
    }

    // --- Prevent accidental email removal ---
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

            // Guardian phone validation (guardian_contact)
            const guardianContact = document.querySelector('input[name="guardian_contact"]');
            if (guardianContact && guardianContact.value) {
                const phoneRegex = /^[0-9+\-\s()]{7,15}$/;
                if (!phoneRegex.test(guardianContact.value)) {
                    isValid = false;
                    if (!firstErrorField) firstErrorField = guardianContact;
                    guardianContact.classList.add('border-red-500');
                    alert('कृपया वैध अभिभावकको फोन नम्बर लेख्नुहोस्।');
                }
            }

            // Email removal warning
            const emailField = document.getElementById('email');
            const studentEmail = "{{ $student->email }}";
            const currentEmail = emailField ? emailField.value.trim() : '';
            if (studentEmail && studentEmail.length > 0 && currentEmail === '') {
                if (!confirm('⚠️ सावधान! विद्यार्थीको ईमेल हटाइँदैछ। यदि ईमेल हटाउनुभयो भने विद्यार्थीले आफ्नो खातामा लगइन गर्न सक्दैन।\n\nतपाईं निश्चित हुनुहुन्छ?')) {
                    isValid = false;
                    if (!firstErrorField) firstErrorField = emailField;
                    emailField.focus();
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
                submitButton.innerHTML = '⏳ अद्यावधिक हुँदैछ...';
            }
        });

        // Real-time validation (remove red border on input)
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