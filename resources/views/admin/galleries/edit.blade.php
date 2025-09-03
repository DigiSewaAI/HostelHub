@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Edit Gallery Item</h1>
    
    <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data" class="max-w-3xl">
        @csrf
        @method('PUT')
        
        @include('admin.gallery._form')
        
        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('admin.gallery.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Update Item
            </button>
        </div>
    </form>
</div>
@endsection