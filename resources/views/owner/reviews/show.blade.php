@extends('layouts.owner')

@section('title', 'समीक्षा हेर्नुहोस्')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">समीक्षा हेर्नुहोस्</h1>
            <a href="{{ route('owner.reviews.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> पछाडि जानुहोस्
            </a>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
            <div class="flex items-start mb-4">
                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                    <span class="text-indigo-800 font-semibold">{{ substr($review->student->name, 0, 2) }}</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">{{ $review->student->name }}</h3>
                    <div class="flex items-center mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="ml-2 text-gray-600">{{ $review->rating }}/5</span>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">{{ $review->created_at->format('d M, Y h:i A') }}</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">{{ $review->comment }}</p>
            
            @if($review->reply)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-start">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-store text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $hostel->name ?? 'होस्टल' }}</h4>
                            <p class="text-gray-500 text-sm">{{ $review->reply_date->format('d M, Y h:i A') }}</p>
                            <p class="text-gray-700 mt-2">{{ $review->reply }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if(!$review->reply)
        <div class="flex justify-end">
            <a href="{{ route('owner.reviews.reply', $review) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center">
                <i class="fas fa-reply mr-2"></i> जवाफ दिनुहोस्
            </a>
        </div>
        @endif
    </div>
</div>
@endsection