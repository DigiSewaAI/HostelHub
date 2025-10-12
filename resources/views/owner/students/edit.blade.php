@extends('layouts.owner')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">विद्यार्थी सम्पादन: {{ $student->name }}</h1>
        <a href="{{ route('owner.students.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left Column --}}
            <div>
                {{-- User --}}
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $student->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- College --}}
                <div class="mb-4">
                    <label for="college" class="block text-sm font-medium text-gray-700">College</label>
                    <input type="text" name="college" value="{{ old('college', $student->college) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Phone --}}
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- DOB --}}
                <div class="mb-4">
                    <label for="dob" class="block text-sm font-medium text-gray-700">जन्म मिति</label>
                    <input type="date" name="dob" value="{{ old('dob', $student->dob ? $student->dob->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                {{-- Gender --}}
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- Select Gender --</option>
                        <option value="male" {{ old('gender', $student->gender)=='male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $student->gender)=='female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $student->gender)=='other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                {{-- Guardian Name --}}
                <div class="mb-4">
                    <label for="guardian_name" class="block text-sm font-medium text-gray-700">Guardian Name</label>
                    <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Guardian Phone --}}
                <div class="mb-4">
                    <label for="guardian_phone" class="block text-sm font-medium text-gray-700">Guardian Phone</label>
                    <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Guardian Relation --}}
                <div class="mb-4">
                    <label for="guardian_relation" class="block text-sm font-medium text-gray-700">Guardian Relation</label>
                    <input type="text" name="guardian_relation" value="{{ old('guardian_relation', $student->guardian_relation) }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Room --}}
                <div class="mb-4">
                    <label for="room_id" class="block text-sm font-medium text-gray-700">Assign Room</label>
                    <select name="room_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- No Room Assigned --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $student->room_id)==$room->id ? 'selected' : '' }}>
                                {{ $room->room_number }} ({{ $room->hostel->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Admission Date --}}
                <div class="mb-4">
                    <label for="admission_date" class="block text-sm font-medium text-gray-700">Admission Date</label>
                    <input type="date" name="admission_date" value="{{ old('admission_date', $student->admission_date ? $student->admission_date->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                {{-- Status + Payment --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" {{ old('status', $student->status)=='pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status', $student->status)=='approved' ? 'selected' : '' }}>Approved</option>
                            <option value="active" {{ old('status', $student->status)=='active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $student->status)=='inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <select name="payment_status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" {{ old('payment_status', $student->payment_status)=='pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('payment_status', $student->payment_status)=='paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <textarea name="address" rows="3"
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>{{ old('address', $student->address) }}</textarea>
        </div>

        <div class="mt-4">
            <label for="guardian_address" class="block text-sm font-medium text-gray-700">Guardian Address</label>
            <textarea name="guardian_address" rows="3"
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>{{ old('guardian_address', $student->guardian_address) }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                🔄 Update Student
            </button>
        </div>
    </form>
</div>
@endsection