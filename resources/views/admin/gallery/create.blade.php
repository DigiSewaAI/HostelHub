@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Add Gallery Item</h1>
    
    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl">
        @csrf
        
        <div class="grid gap-6">
            @include('admin.gallery._form')
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.gallery.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Save Item
                </button>
            </div>
        </div>
    </form>
</div>
@endsection