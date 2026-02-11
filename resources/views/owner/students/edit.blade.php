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
    <form action="{{ route('owner.students.update', $student->id) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
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
                </div>
                <div class="mb-4" id="other_college_field" style="{{ (old('college_id', $student->college_id) === null && $student->college) ? '' : 'display: none;' }}">
                    <label for="other_college" class="block text-sm font-medium text-gray-700">अन्य कलेजको नाम</label>
                    <input type="text" name="other_college" id="other_college" value="{{ old('other_college', $student->college_id ? '' : $student->college) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                {{-- Phone --}}
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">फोन *</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- DOB --}}
                <div class="mb-4">
                    <label for="dob" class="block text-sm font-medium text-gray-700">जन्म मिति</label>
                    <input type="date" name="dob" id="dob" value="{{ old('dob', $student->dob ? $student->dob->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
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
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                {{-- Guardian Name --}}
                <div class="mb-4">
                    <label for="guardian_name" class="block text-sm font-medium text-gray-700">अभिभावकको नाम *</label>
                    <input type="text" name="guardian_name" id="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Guardian Contact --}}
                <div class="mb-4">
                    <label for="guardian_contact" class="block text-sm font-medium text-gray-700">अभिभावकको सम्पर्क *</label>
                    <input type="text" name="guardian_contact" id="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Guardian Relation --}}
                <div class="mb-4">
                    <label for="guardian_relation" class="block text-sm font-medium text-gray-700">अभिभावकको सम्बन्ध *</label>
                    <input type="text" name="guardian_relation" id="guardian_relation" value="{{ old('guardian_relation', $student->guardian_relation) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Room --}}
                <div class="mb-4">
                    <label for="room_id" class="block text-sm font-medium text-gray-700">कोठा तोक्नुहोस्</label>
                    <select name="room_id" id="room_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- कुनै कोठा तोकिएको छैन --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $student->room_id)==$room->id ? 'selected' : '' }}>
                                {{ $room->room_number }} ({{ $room->hostel->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Admission Date --}}
                <div class="mb-4">
                    <label for="admission_date" class="block text-sm font-medium text-gray-700">भर्ना मिति *</label>
                    <input type="date" name="admission_date" id="admission_date" value="{{ old('admission_date', $student->admission_date ? $student->admission_date->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Status + Payment --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">स्थिति *</label>
                        <select name="status" id="status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" {{ old('status', $student->status)=='pending' ? 'selected' : '' }}>पेन्डिङ</option>
                            <option value="approved" {{ old('status', $student->status)=='approved' ? 'selected' : '' }}>स्वीकृत</option>
                            <option value="active" {{ old('status', $student->status)=='active' ? 'selected' : '' }}>सक्रिय</option>
                            <option value="inactive" {{ old('status', $student->status)=='inactive' ? 'selected' : '' }}>निष्क्रिय</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">भुक्तानी स्थिति *</label>
                        <select name="payment_status" id="payment_status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" {{ old('payment_status', $student->payment_status)=='pending' ? 'selected' : '' }}>पेन्डिङ</option>
                            <option value="paid" {{ old('payment_status', $student->payment_status)=='paid' ? 'selected' : '' }}>भुक्तानी भएको</option>
                            <option value="unpaid" {{ old('payment_status', $student->payment_status)=='unpaid' ? 'selected' : '' }}>भुक्तानी नभएको</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-gray-700">ठेगाना *</label>
            <textarea name="address" id="address" rows="3"
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>{{ old('address', $student->address) }}</textarea>
        </div>

        <div class="mt-4">
            <label for="guardian_address" class="block text-sm font-medium text-gray-700">अभिभावकको ठेगाना *</label>
            <textarea name="guardian_address" id="guardian_address" rows="3"
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>{{ old('guardian_address', $student->guardian_address) }}</textarea>
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
// JavaScript to handle college selection
document.addEventListener('DOMContentLoaded', function() {
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

    // Prevent form submission if email is being removed accidentally
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const emailField = document.getElementById('email');
            const studentEmail = "{{ $student->email }}";
            const currentEmail = emailField.value.trim();
            
            // If student had email and now it's empty, warn user
            if (studentEmail && studentEmail.length > 0 && currentEmail === '') {
                if (!confirm('⚠️ सावधान! विद्यार्थीको ईमेल हटाइँदैछ। यदि ईमेल हटाउनुभयो भने विद्यार्थीले आफ्नो खातामा लगइन गर्न सक्दैन।\n\nतपाईं निश्चित हुनुहुन्छ?')) {
                    e.preventDefault();
                    emailField.focus();
                    return false;
                }
            }
        });
    }
});
</script>
@endsection