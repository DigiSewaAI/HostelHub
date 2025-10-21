@extends('layouts.owner')

@section('title', 'नयाँ ग्यालरी थप्नुहोस्')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 nepali">नयाँ ग्यालरी थप्नुहोस्</h1>
        <a href="{{ route('owner.galleries.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-medium no-underline transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>फिर्ता जानुहोस्
        </a>
    </div>

    <form action="{{ route('owner.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
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
                                   placeholder="ग्यालरीको शीर्षक लेख्नुहोस्">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2 nepali">विवरण</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="ग्यालरीको छोटो विवरण लेख्नुहोस्"></textarea>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2 nepali">श्रेणी *</label>
                            <select name="category" id="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">श्रेणी छान्नुहोस्</option>
                                <option value="room">कोठाका तस्बिरहरू</option>
                                <option value="common_area">साझा क्षेत्रहरू</option>
                                <option value="facility">सुविधाहरू</option>
                                <option value="event">कार्यक्रमहरू</option>
                                <option value="other">अन्य</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Media Settings -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">मिडिया सेटिङहरू</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="media_type" class="block text-sm font-medium text-gray-700 mb-2 nepali">मिडिया प्रकार *</label>
                            <select name="media_type" id="media_type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">मिडिया प्रकार छान्नुहोस्</option>
                                <option value="image">तस्बिर</option>
                                <option value="video">भिडियो फाइल</option>
                                <option value="external_video">यूट्युब लिङ्क</option>
                            </select>
                        </div>

                        <!-- File Upload Field -->
                        <div id="file_upload_field">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2 nepali">फाइल छान्नुहोस् *</label>
                            <input type="file" name="file" id="file" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   accept="image/*,video/*">
                            <p class="text-xs text-gray-500 mt-1 nepali">अनुमतिहरू: JPEG, PNG, JPG, GIF, MP4, MOV, AVI (अधिकतम 10MB)</p>
                        </div>

                        <!-- External Link Field -->
                        <div id="external_link_field" class="hidden">
                            <label for="external_link" class="block text-sm font-medium text-gray-700 mb-2 nepali">यूट्युब लिङ्क *</label>
                            <input type="url" name="external_link" id="external_link"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="https://www.youtube.com/watch?v=...">
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
                                   class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="text-md font-medium text-gray-800 mb-2 nepali">सार्वजनिक पृष्ठमा देखाउने</h3>
                            <p class="text-sm text-gray-600 nepali">यो ग्यालरी तपाईंको सार्वजनिक पृष्ठको ग्यालरी सेक्सनमा देखाइनेछ।</p>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">पूर्वावलोकन</h2>
                    
                    <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center" id="preview_container">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-image text-3xl mb-2"></i>
                            <p class="text-sm nepali">पूर्वावलोकन यहाँ देखाइनेछ</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl font-semibold transition-colors nepali">
                        <i class="fas fa-save mr-2"></i>ग्यालरी सेभ गर्नुहोस्
                    </button>
                    <p class="text-xs text-blue-600 text-center mt-2 nepali">ग्यालरी सेभ भएपछि सार्वजनिक पृष्ठमा देखाइनेछ</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mediaTypeSelect = document.getElementById('media_type');
    const fileUploadField = document.getElementById('file_upload_field');
    const externalLinkField = document.getElementById('external_link_field');
    const previewContainer = document.getElementById('preview_container');
    const fileInput = document.getElementById('file');

    function updateMediaFields() {
        const mediaType = mediaTypeSelect.value;
        
        if (mediaType === 'external_video') {
            fileUploadField.classList.add('hidden');
            externalLinkField.classList.remove('hidden');
            previewContainer.innerHTML = `
                <div class="text-center text-gray-500">
                    <i class="fab fa-youtube text-3xl mb-2 text-red-600"></i>
                    <p class="text-sm nepali">यूट्युब भिडियो पूर्वावलोकन</p>
                </div>
            `;
        } else {
            fileUploadField.classList.remove('hidden');
            externalLinkField.classList.add('hidden');
            
            if (mediaType === 'image') {
                previewContainer.innerHTML = `
                    <div class="text-center text-gray-500">
                        <i class="fas fa-image text-3xl mb-2"></i>
                        <p class="text-sm nepali">तस्बिर पूर्वावलोकन</p>
                    </div>
                `;
            } else if (mediaType === 'video') {
                previewContainer.innerHTML = `
                    <div class="text-center text-gray-500">
                        <i class="fas fa-video text-3xl mb-2"></i>
                        <p class="text-sm nepali">भिडियो पूर्वावलोकन</p>
                    </div>
                `;
            }
        }
    }

    // Handle file preview
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                previewContainer.innerHTML = `
                    <div class="text-center text-gray-500">
                        <i class="fas fa-video text-3xl mb-2 text-blue-600"></i>
                        <p class="text-sm nepali">भिडियो फाइल: ${file.name}</p>
                    </div>
                `;
            }
        }
    });

    mediaTypeSelect.addEventListener('change', updateMediaFields);
    updateMediaFields(); // Initial call
});
</script>
@endsection