@extends('layouts.admin')

@section('title', 'ग्यालेरी आइटम थप्नुहोस्')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📸 ग्यालेरी आइटम थप्नुहोस्</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @include('admin.galleries._form')
        </div>

        <!-- Save & Cancel Buttons -->
        <div class="flex justify-end space-x-4 mt-8">
            <a href="{{ route('admin.galleries.index') }}" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded transition duration-200">
                रद्द गर्नुहोस्
            </a>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded transition duration-200 shadow">
                💾 आइटम सुरक्षित गर्नुहोस्
            </button>
        </div>
    </form>
</div>
@endsection
