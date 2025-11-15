@extends('layouts.admin')

@section('title', 'संस्था दर्ता अनुरोध विवरण')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">संस्था दर्ता अनुरोध विवरण</h1>
        <a href="{{ route('admin.organization-requests.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> फर्कनुहोस्
        </a>
    </div>

    <!-- Request Details -->
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">संस्था विवरण</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">संस्थाको नाम</label>
                        <p class="mt-1 text-gray-900">{{ $organizationRequest->organization_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">प्रबन्धकको नाम</label>
                        <p class="mt-1 text-gray-900">{{ $organizationRequest->manager_full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">PAN नम्बर</label>
                        <p class="mt-1 text-gray-900">{{ $organizationRequest->pan_no ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">सम्पर्क विवरण</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">ईमेल ठेगाना</label>
                        <p class="mt-1 text-gray-900">{{ $organizationRequest->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">फोन नम्बर</label>
                        <p class="mt-1 text-gray-900">{{ $organizationRequest->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">अनुरोध मिति</label>
                        <p class="mt-1 text-gray-900">{{ $organizationRequest->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600">ठेगाना</label>
            <p class="mt-1 text-gray-900">{{ $organizationRequest->address }}</p>
        </div>

        @if($organizationRequest->user)
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">प्रयोगकर्ता विवरण</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">प्रयोगकर्ता ID</label>
                    <p class="mt-1 text-gray-900">{{ $organizationRequest->user->id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">दर्ता मिति</label>
                    <p class="mt-1 text-gray-900">{{ $organizationRequest->user->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Approval/Rejection Forms -->
        @if($organizationRequest->isPending())
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">कार्यहरू</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Approve Form -->
                <form action="{{ route('admin.organization-requests.approve', $organizationRequest) }}" method="POST" class="bg-green-50 p-4 rounded-lg">
                    @csrf
                    <h4 class="font-semibold text-green-800 mb-3">✅ संस्था स्वीकृत गर्नुहोस्</h4>
                    <div class="mb-3">
                        <label for="approve_notes" class="block text-sm font-medium text-green-700 mb-1">टिप्पणी (वैकल्पिक)</label>
                        <textarea name="admin_notes" id="approve_notes" rows="3" class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-semibold">
                        स्वीकृत गर्नुहोस्
                    </button>
                </form>

                <!-- Reject Form -->
                <form action="{{ route('admin.organization-requests.reject', $organizationRequest) }}" method="POST" class="bg-red-50 p-4 rounded-lg">
                    @csrf
                    <h4 class="font-semibold text-red-800 mb-3">❌ संस्था अस्वीकृत गर्नुहोस्</h4>
                    <div class="mb-3">
                        <label for="reject_notes" class="block text-sm font-medium text-red-700 mb-1">कारण *</label>
                        <textarea name="admin_notes" id="reject_notes" rows="3" required class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-semibold">
                        अस्वीकृत गर्नुहोस्
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">स्थिति</h3>
            <div class="p-4 rounded-lg {{ $organizationRequest->isApproved() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                <p class="font-semibold">
                    {{ $organizationRequest->isApproved() ? '✅ स्वीकृत' : '❌ अस्वीकृत' }}
                    - {{ $organizationRequest->updated_at->format('Y-m-d H:i') }}
                </p>
                @if($organizationRequest->admin_notes)
                <p class="mt-2"><strong>प्रशासकको टिप्पणी:</strong> {{ $organizationRequest->admin_notes }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection