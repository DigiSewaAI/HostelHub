@extends('layouts.admin')

@section('title', 'नयाँ भुक्तानी थप्नुहोस्')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 nepali">नयाँ भुक्तानी थप्नुहोस्</h1>
            <a href="{{ route('payments.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center nepali">
                <i class="fas fa-arrow-left mr-2"></i> पछाडि जानुहोस्
            </a>
        </div>

        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1 nepali">विद्यार्थी <span class="text-red-500">*</span></label>
                    <select name="student_id" id="student_id" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="" class="nepali">विद्यार्थी छान्नुहोस्</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->room->room_number ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1 nepali">रकम <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="amount" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" 
                           required min="0" step="0.01" value="{{ old('amount') }}">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1 nepali">भुक्तानी मिति <span class="text-red-500">*</span></label>
                    <input type="date" name="payment_date" id="payment_date" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" 
                           required value="{{ old('payment_date', now()->format('Y-m-d')) }}">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1 nepali">भुक्तानी विधि <span class="text-red-500">*</span></label>
                    <select name="payment_method" id="payment_method" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="" class="nepali">विधि छान्नुहोस्</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }} class="nepali">नगद</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }} class="nepali">बैंक ट्रान्सफर</option>
                        <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }} class="nepali">अनलाइन</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1 nepali">विवरण</label>
                <textarea name="description" id="description" rows="3" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" 
                          placeholder="भुक्तानी सम्बन्धी थप जानकारी">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1 nepali">स्थिति <span class="text-red-500">*</span></label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }} class="nepali">प्रतीक्षामा</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }} class="nepali">पूरा भएको</option>
                    <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }} class="nepali">असफल</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center nepali">
                    <i class="fas fa-save mr-2"></i> सुरक्षित गर्नुहोस्
                </button>
            </div>
        </form>
    </div>
</div>
@endsection