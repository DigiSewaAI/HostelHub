@extends('layouts.admin')

@section('title', 'संस्था दर्ता अनुरोधहरू')

@section('content')
<div class="space-y-6">
    <!-- Pending Requests -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">📋 पेन्डिङ अनुरोधहरू</h2>
        
        @if($pendingRequests->count() > 0)
            <div class="space-y-4">
                @foreach($pendingRequests as $request)
                <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-800">{{ $request->organization_name }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 text-sm text-gray-600">
                                <div><strong>प्रबन्धक:</strong> {{ $request->manager_full_name }}</div>
                                <div><strong>ईमेल:</strong> {{ $request->email }}</div>
                                <div><strong>फोन:</strong> {{ $request->phone }}</div>
                                <div><strong>PAN:</strong> {{ $request->pan_no ?? 'N/A' }}</div>
                            </div>
                            <p class="mt-2 text-sm"><strong>ठेगाना:</strong> {{ $request->address }}</p>
                            <p class="text-xs text-gray-500 mt-2">अनुरोध मिति: {{ $request->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <a href="{{ route('admin.organization-requests.show', $request) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                विस्तार हेर्नुहोस्
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p>कुनै पेन्डिङ अनुरोध छैन</p>
            </div>
        @endif
    </div>

    <!-- Approved Requests -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">✅ स्वीकृत अनुरोधहरू</h2>
        
        @if($approvedRequests->count() > 0)
            <div class="space-y-4">
                @foreach($approvedRequests as $request)
                <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-800">{{ $request->organization_name }}</h3>
                            <p class="text-sm text-green-600 font-medium">स्वीकृत मिति: {{ $request->updated_at->format('Y-m-d H:i') }}</p>
                            @if($request->admin_notes)
                            <p class="text-sm text-gray-600 mt-1"><strong>प्रशासकको टिप्पणी:</strong> {{ $request->admin_notes }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-check-circle text-4xl mb-3"></i>
                <p>कुनै स्वीकृत अनुरोध छैन</p>
            </div>
        @endif
    </div>

    <!-- Rejected Requests -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">❌ अस्वीकृत अनुरोधहरू</h2>
        
        @if($rejectedRequests->count() > 0)
            <div class="space-y-4">
                @foreach($rejectedRequests as $request)
                <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-800">{{ $request->organization_name }}</h3>
                            <p class="text-sm text-red-600 font-medium">अस्वीकृत मिति: {{ $request->updated_at->format('Y-m-d H:i') }}</p>
                            @if($request->admin_notes)
                            <p class="text-sm text-gray-600 mt-1"><strong>कारण:</strong> {{ $request->admin_notes }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-times-circle text-4xl mb-3"></i>
                <p>कुनै अस्वीकृत अनुरोध छैन</p>
            </div>
        @endif
    </div>
</div>
@endsection