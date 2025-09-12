@extends('layouts.admin')

@section('title', 'ग्यालेरी आइटम सम्पादन गर्नुहोस्')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">ग्यालेरी आइटम सम्पादन गर्नुहोस्</h1>
    
    <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data" class="max-w-3xl">
        @csrf
        @method('PUT')
        
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @include('admin.galleries._form')
        </div>
        
        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('admin.galleries.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                रद्द गर्नुहोस्
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                आइटम अद्यावधिक गर्नुहोस्
            </button>
        </div>
    </form>
</div>
@endsection