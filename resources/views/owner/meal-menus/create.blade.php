@extends('layouts.owner')

@section('title', 'नयाँ खानाको योजना थप्नुहोस्')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">नयाँ खानाको योजना</h1>
            <a href="{{ route('owner.meal-menus.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> पछाडि जानुहोस्
            </a>
        </div>

        <form action="{{ route('owner.meal-menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="day" class="block text-sm font-medium text-gray-700 mb-1">दिन <span class="text-red-500">*</span></label>
                    <select name="day" id="day" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">दिन छान्नुहोस्</option>
                        <option value="Sunday" {{ old('day') == 'Sunday' ? 'selected' : '' }}>आइतबार</option>
                        <option value="Monday" {{ old('day') == 'Monday' ? 'selected' : '' }}>सोमबार</option>
                        <option value="Tuesday" {{ old('day') == 'Tuesday' ? 'selected' : '' }}>मंगलबार</option>
                        <option value="Wednesday" {{ old('day') == 'Wednesday' ? 'selected' : '' }}>बुधबार</option>
                        <option value="Thursday" {{ old('day') == 'Thursday' ? 'selected' : '' }}>बिहिबार</option>
                        <option value="Friday" {{ old('day') == 'Friday' ? 'selected' : '' }}>शुक्रबार</option>
                        <option value="Saturday" {{ old('day') == 'Saturday' ? 'selected' : '' }}>शनिबार</option>
                    </select>
                    @error('day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="meal_type" class="block text-sm font-medium text-gray-700 mb-1">खानाको प्रकार <span class="text-red-500">*</span></label>
                    <select name="meal_type" id="meal_type" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">प्रकार छान्नुहोस्</option>
                        <option value="breakfast" {{ old('meal_type') == 'breakfast' ? 'selected' : '' }}>नास्ता</option>
                        <option value="lunch" {{ old('meal_type') == 'lunch' ? 'selected' : '' }}>दिउँसो</option>
                        <option value="dinner" {{ old('meal_type') == 'dinner' ? 'selected' : '' }}>रात्रि</option>
                    </select>
                    @error('meal_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <label for="items" class="block text-sm font-medium text-gray-700 mb-1">खानाका वस्तुहरू <span class="text-red-500">*</span></label>
                <textarea name="items" id="items" rows="4" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required placeholder="जस्तै: दाल, भात, तरकारी, अचार">{{ old('items') }}</textarea>
                @error('items')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">खानाको तस्बिर</label>
                <input type="file" name="image" id="image" class="w-full border border-gray-300 rounded-md p-2" accept="image/*">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center">
                    <i class="fas fa-save mr-2"></i> सुरक्षित गर्नुहोस्
                </button>
            </div>
        </form>
    </div>
</div>
@endsection