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
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">दिन <span class="text-red-500">*</span></label>
                    <select name="day_of_week" id="day_of_week" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">दिन छान्नुहोस्</option>
                        <option value="sunday" {{ old('day_of_week') == 'sunday' ? 'selected' : '' }}>आइतबार</option>
                        <option value="monday" {{ old('day_of_week') == 'monday' ? 'selected' : '' }}>सोमबार</option>
                        <option value="tuesday" {{ old('day_of_week') == 'tuesday' ? 'selected' : '' }}>मंगलबार</option>
                        <option value="wednesday" {{ old('day_of_week') == 'wednesday' ? 'selected' : '' }}>बुधबार</option>
                        <option value="thursday" {{ old('day_of_week') == 'thursday' ? 'selected' : '' }}>बिहिबार</option>
                        <option value="friday" {{ old('day_of_week') == 'friday' ? 'selected' : '' }}>शुक्रबार</option>
                        <option value="saturday" {{ old('day_of_week') == 'saturday' ? 'selected' : '' }}>शनिबार</option>
                    </select>
                    @error('day_of_week')
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
            
            <!-- FIXED: Dynamic items input for array handling -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">खानाका वस्तुहरू <span class="text-red-500">*</span></label>
                <div id="items-container">
                    <div class="flex items-center gap-2 mb-2">
                        <input type="text" name="items[]" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required placeholder="जस्तै: दाल, भात, तरकारी">
                        <button type="button" onclick="addMoreItems()" class="bg-green-500 text-white p-2 rounded-md hover:bg-green-600">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                @error('items')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('items.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">विवरण</label>
                <textarea name="description" id="description" rows="3" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="अतिरिक्त विवरण...">{{ old('description') }}</textarea>
                @error('description')
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
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">सक्रिय गर्नुहोस्</span>
                </label>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center">
                    <i class="fas fa-save mr-2"></i> सुरक्षित गर्नुहोस्
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function addMoreItems() {
    const container = document.getElementById('items-container');
    const newItem = document.createElement('div');
    newItem.className = 'flex items-center gap-2 mb-2';
    newItem.innerHTML = `
        <input type="text" name="items[]" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required placeholder="अर्को वस्तु थप्नुहोस्">
        <button type="button" onclick="removeItem(this)" class="bg-red-500 text-white p-2 rounded-md hover:bg-red-600">
            <i class="fas fa-minus"></i>
        </button>
    `;
    container.appendChild(newItem);
}

function removeItem(button) {
    button.parentElement.remove();
}
</script>
@endsection