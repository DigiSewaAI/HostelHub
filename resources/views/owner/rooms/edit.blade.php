@extends('layouts.owner')

@section('title', 'कोठा सम्पादन गर्नुहोस्')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">कोठा सम्पादन गर्नुहोस्</h1>
            <div class="flex space-x-3">
                <a href="{{ route('owner.rooms.show', $room) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                    <i class="fas fa-arrow-left mr-1"></i> पछाडि जानुहोस्
                </a>
            </div>
        </div>

        <form action="{{ route('owner.rooms.update', $room) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="room_number" class="block text-sm font-medium text-gray-700 mb-1">कोठा नम्बर <span class="text-red-500">*</span></label>
                    <input type="text" name="room_number" id="room_number" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" 
                           required value="{{ old('room_number', $room->room_number) }}">
                    @error('room_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">प्रकार <span class="text-red-500">*</span></label>
                    <select name="type" id="type" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="single" {{ $room->type == 'single' ? 'selected' : '' }}>एकल</option>
                        <option value="double" {{ $room->type == 'double' ? 'selected' : '' }}>डबल</option>
                        <option value="shared" {{ $room->type == 'shared' ? 'selected' : '' }}>साझा</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">क्षमता <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity" id="capacity" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" 
                           required min="1" value="{{ old('capacity', $room->capacity) }}">
                    @error('capacity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">मूल्य <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" 
                           required min="0" step="0.01" value="{{ old('price', $room->price) }}">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">स्थिति <span class="text-red-500">*</span></label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>उपलब्ध</option>
                    <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>भरिएको</option>
                    <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>मर्मत सम्भार</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">विवरण</label>
                <textarea name="description" id="description" rows="3" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500" 
                          placeholder="कोठाको विवरण यहाँ लेख्नुहोस्...">{{ old('description', $room->description) }}</textarea>
                @error('description')
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