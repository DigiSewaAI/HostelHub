@extends('layouts.owner')

@section('title', 'खानाको योजना सम्पादन')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">खानाको योजना सम्पादन</h1>
            <a href="{{ route('owner.meal-menus.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> पछाडि जानुहोस्
            </a>
        </div>

        <form action="{{ route('owner.meal-menus.update', $mealMenu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="day" class="block text-sm font-medium text-gray-700 mb-1">दिन <span class="text-red-500">*</span></label>
                    <select name="day" id="day" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">दिन छान्नुहोस्</option>
                        <option value="Sunday" {{ $mealMenu->day == 'Sunday' ? 'selected' : '' }}>आइतबार</option>
                        <option value="Monday" {{ $mealMenu->day == 'Monday' ? 'selected' : '' }}>सोमबार</option>
                        <option value="Tuesday" {{ $mealMenu->day == 'Tuesday' ? 'selected' : '' }}>मंगलबार</option>
                        <option value="Wednesday" {{ $mealMenu->day == 'Wednesday' ? 'selected' : '' }}>बुधबार</option>
                        <option value="Thursday" {{ $mealMenu->day == 'Thursday' ? 'selected' : '' }}>बिहिबार</option>
                        <option value="Friday" {{ $mealMenu->day == 'Friday' ? 'selected' : '' }}>शुक्रबार</option>
                        <option value="Saturday" {{ $mealMenu->day == 'Saturday' ? 'selected' : '' }}>शनिबार</option>
                    </select>
                </div>
                
                <div>
                    <label for="meal_type" class="block text-sm font-medium text-gray-700 mb-1">खानाको प्रकार <span class="text-red-500">*</span></label>
                    <select name="meal_type" id="meal_type" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">प्रकार छान्नुहोस्</option>
                        <option value="breakfast" {{ $mealMenu->meal_type == 'breakfast' ? 'selected' : '' }}>नास्ता</option>
                        <option value="lunch" {{ $mealMenu->meal_type == 'lunch' ? 'selected' : '' }}>दिउँसो</option>
                        <option value="dinner" {{ $mealMenu->meal_type == 'dinner' ? 'selected' : '' }}>रात्रि</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="items" class="block text-sm font-medium text-gray-700 mb-1">खानाका वस्तुहरू <span class="text-red-500">*</span></label>
                <textarea name="items" id="items" rows="4" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required placeholder="जस्तै: दाल, भात, तरकारी, अचार">{{ $mealMenu->items }}</textarea>
            </div>
            
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">खानाको तस्बिर</label>
                <input type="file" name="image" id="image" class="w-full border border-gray-300 rounded-md p-2" accept="image/*">
                @if($mealMenu->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/'.$mealMenu->image) }}" alt="Current Image" class="img-thumbnail" width="150">
                    </div>
                @endif
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center">
                    <i class="fas fa-save mr-2"></i> अपडेट गर्नुहोस्
                </button>
            </div>
        </form>
    </div>
</div>
@endsection