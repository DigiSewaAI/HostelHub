@extends('layouts.owner')

@section('title', 'ग्यालरी सम्पादन गर्नुहोस्')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 nepali">ग्यालरी सम्पादन गर्नुहोस्</h1>
        <a href="{{ route('owner.galleries.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-medium no-underline transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>फिर्ता जानुहोस्
        </a>
    </div>

    <form action="{{ route('owner.galleries.update', $gallery) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">आधारभूत जानकारी</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2 nepali">शीर्षक *</label>
                            <input type="text" name="title" id="title" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   value="{{ old('title', $gallery->title) }}"
                                   placeholder="ग्यालरीको शीर्षक लेख्नुहोस्">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2 nepali">विवरण</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="ग्यालरीको छोटो विवरण लेख्नुहोस्">{{ old('description', $gallery->description) }}</textarea>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2 nepali">श्रेणी *</label>
                            <select name="category" id="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="room" {{ $gallery->category == 'room' ? 'selected' : '' }}>कोठाका तस्बिरहरू</option>
                                <option value="common_area" {{ $gallery->category == 'common_area' ? 'selected' : '' }}>साझा क्षेत्रहरू</option>
                                <option value="facility" {{ $gallery->category == 'facility' ? 'selected' : '' }}>सुविधाहरू</option>
                                <option value="event" {{ $gallery->category == 'event' ? 'selected' : '' }}>कार्यक्रमहरू</option>
                                <option value="other" {{ $gallery->category == 'other' ? 'selected' : '' }}>अन्य</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Settings -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">सेटिङहरू</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label for="is_featured" class="text-sm font-medium text-gray-700 nepali">फिचर्ड ग्यालरी बनाउनुहोस्</label>
                                <p class="text-xs text-gray-500 nepali">यो ग्यालरी होमपेजमा देखाइनेछ</p>
                            </div>
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                   {{ $gallery->is_featured ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label for="is_active" class="text-sm font-medium text-gray-700 nepali">सक्रिय गर्नुहोस्</label>
                                <p class="text-xs text-gray-500 nepali">ग्यालरी सार्वजनिक पृष्ठमा देखाइनेछ</p>
                            </div>
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ $gallery->is_active ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Current Media Preview -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">हालको मिडिया</h2>
                    
                    <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                        @if($gallery->media_type === 'image')
                            <img src="{{ $gallery->thumbnail_url }}" 
                                 alt="{{ $gallery->title }}"
                                 class="w-full h-full object-cover">
                        @elseif($gallery->media_type === 'external_video')
                            <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                <i class="fab fa-youtube text-white text-4xl"></i>
                            </div>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                                <i class="fas fa-video text-white text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-3 text-center text-sm text-gray-600">
                        <p class="nepali">मिडिया प्रकार: {{ $gallery->media_type }}</p>
                        @if($gallery->media_type === 'external_video')
                            <a href="{{ $gallery->external_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 nepali">
                                लिङ्क हेर्नुहोस्
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl font-semibold transition-colors nepali">
                        <i class="fas fa-save mr-2"></i>परिवर्तनहरू सेभ गर्नुहोस्
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection