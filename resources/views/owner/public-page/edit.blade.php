@extends('layouts.owner')

@section('title', 'सार्वजनिक पृष्ठ व्यवस्थापन')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 nepali">तपाईंको सार्वजनिक पृष्ठ व्यवस्थापन</h2>
            <p class="text-gray-600 mt-1 nepali">तपाईंको होस्टलको सार्वजनिक पृष्ठलाई अनुकूलन गर्नुहोस् र प्रकाशित गर्नुहोस्</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="nepali">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="nepali">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('owner.public-page.preview') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 nepali">होस्टल लोगो</label>
                    <div class="flex items-center space-x-4">
                        @if($hostel->logo_path)
                            <img src="{{ $hostel->logo_url }}" alt="{{ $hostel->name }}" class="h-16 w-16 rounded-lg object-cover">
                        @else
                            <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1 nepali">JPG, PNG, GIF, 2MB सम्म</p>
                        </div>
                    </div>
                </div>

                <!-- Theme Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 nepali">थीम रङ</label>
                    <input type="color" name="theme_color" value="{{ $hostel->theme_color ?? '#3b82f6' }}" class="h-10 w-20 rounded border border-gray-300">
                    <p class="text-xs text-gray-500 mt-1 nepali">तपाईंको होस्टल पृष्ठको प्रमुख रङ चयन गर्नुहोस्</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 nepali">होस्टल विवरण</label>
                    <textarea name="description" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 nepali" placeholder="तपाईंको होस्टलको बारेमा विस्तृत विवरण लेख्नुहोस्...">{{ old('description', $hostel->description) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1 nepali">यो विवरण तपाईंको सार्वजनिक पृष्ठमा देखिनेछ</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors nepali">
                        <i class="fas fa-eye mr-2"></i>पूर्वावलोकन हेर्नुहोस्
                    </button>

                    @if($hostel->is_published)
                        <form method="POST" action="{{ route('owner.public-page.unpublish') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors nepali">
                                <i class="fas fa-eye-slash mr-2"></i>अप्रकाशित गर्नुहोस्
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('owner.public-page.publish') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors nepali">
                                <i class="fas fa-globe mr-2"></i>प्रकाशित गर्नुहोस्
                            </button>
                        </form>
                    @endif

                    @if($hostel->is_published)
                        <a href="{{ route('hostels.show', $hostel->slug) }}" target="_blank" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors nepali">
                            <i class="fas fa-external-link-alt mr-2"></i>लाइव पृष्ठ हेर्नुहोस्
                        </a>
                    @endif
                </div>
            </form>

            <!-- Status Card -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg border">
                <h3 class="font-medium text-gray-800 mb-2 nepali">पृष्ठ स्थिति</h3>
                <div class="flex items-center space-x-2">
                    @if($hostel->is_published)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span class="nepali">प्रकाशित</span>
                        </span>
                        <span class="text-sm text-gray-600 nepali">
                            प्रकाशित मिति: {{ $hostel->published_at->format('Y-m-d') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            <span class="nepali">अप्रकाशित</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection