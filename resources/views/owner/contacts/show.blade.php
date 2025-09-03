@extends('layouts.owner')

@section('title', 'सम्पर्क सन्देश हेर्नुहोस्')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">सम्पर्क सन्देश</h1>
            <a href="{{ route('owner.contacts.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> पछाडि जानुहोस्
            </a>
        </div>

        <div class="mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $contact->name }}</h2>
                    <p class="text-gray-600">{{ $contact->email }}</p>
                </div>
                <div>
                    @if($contact->status == 'pending')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            प्रतीक्षामा
                        </span>
                    @elseif($contact->status == 'read')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            पढिएको
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            जवाफ दिइएको
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-700 mb-1">विषय</h3>
            <p class="text-gray-800">{{ $contact->subject }}</p>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-700 mb-1">सन्देश</h3>
            <p class="text-gray-800 whitespace-pre-wrap">{{ $contact->message }}</p>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-700 mb-1">मिति</h3>
            <p class="text-gray-800">{{ $contact->created_at->format('d M, Y h:i A') }}</p>
        </div>

        @if($contact->phone)
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-700 mb-1">फोन नम्बर</h3>
            <p class="text-gray-800">{{ $contact->phone }}</p>
        </div>
        @endif

        <div class="flex justify-end">
            @if($contact->status != 'replied')
                <form action="{{ route('owner.contacts.update', $contact) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="replied">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-check mr-2"></i> जवाफ दिइयो
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection