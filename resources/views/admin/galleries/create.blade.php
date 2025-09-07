@extends('layouts.admin')

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
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    शीर्षक
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="title" name="title" type="text" placeholder="शीर्षक" value="{{ old('title') }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    विवरण
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                          id="description" name="description" rows="3" placeholder="विवरण" required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                    श्रेणी
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="category" name="category" required>
                    <option value="">श्रेणी छान्नुहोस्</option>
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="media_type">
                    मिडिया प्रकार
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="media_type" name="media_type" required>
                    <option value="">मिडिया प्रकार छान्नुहोस्</option>
                    <option value="photo" {{ old('media_type') == 'photo' ? 'selected' : 'selected' }}>तस्वीर</option>
                    <option value="local_video" {{ old('media_type') == 'local_video' ? 'selected' : '' }}>स्थानिय भिडियो</option>
                    <option value="external_video" {{ old('media_type') == 'external_video' ? 'selected' : '' }}>यूट्युब लिंक</option>
                </select>
            </div>

            <!-- Media Upload Field (Replaced with multiple file input) -->
            <div id="media-upload-field" class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="media">
                    मिडिया अपलोड गर्नुहोस्
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="media" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">JPG, PNG, GIF, MP4, MOV, AVI (MAX. 10MB each)</p>
                        </div>
                        <input id="media" name="media[]" type="file" class="hidden" accept="image/*,video/*" multiple />
                    </label>
                </div>
                <div id="media-preview" class="mt-4 grid grid-cols-2 gap-4"></div>
            </div>

            <!-- External Video Link Field -->
            <div id="external-link-field" class="mb-4 hidden">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="external_link">
                    यूट्युब लिंक
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="external_link" name="external_link" type="url" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('external_link') }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    स्थिति
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="status" name="status" required>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>सक्रिय</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>निष्क्रिय</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="featured" class="form-checkbox" {{ old('featured') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">फिचर्ड गर्नुहोस्</span>
                </label>
            </div>
        </div>

        <!-- Save & Cancel Buttons -->
        <div class="flex justify-end space-x-4 mt-8">
            <a href="{{ route('admin.galleries.index') }}" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded transition duration-200">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded transition duration-200 shadow">
                💾 Save Item
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mediaType = document.getElementById('media_type');
    const mediaUploadField = document.getElementById('media-upload-field');
    const externalLinkField = document.getElementById('external-link-field');
    const mediaInput = document.getElementById('media');
    const mediaPreview = document.getElementById('media-preview');
    
    // Media preview functionality for multiple files
    mediaInput.addEventListener('change', function() {
        // Clear previous previews
        mediaPreview.innerHTML = '';
        
        const files = this.files;
        if (files && files.length > 0) {
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'relative';
                    
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-32 object-cover rounded-lg';
                        previewContainer.appendChild(img);
                    } else if (file.type.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.className = 'w-full h-32 object-cover rounded-lg';
                        video.controls = true;
                        previewContainer.appendChild(video);
                    }
                    
                    // Add file name
                    const fileName = document.createElement('p');
                    fileName.className = 'text-xs text-gray-500 truncate mt-1';
                    fileName.textContent = file.name;
                    previewContainer.appendChild(fileName);
                    
                    mediaPreview.appendChild(previewContainer);
                }
                reader.readAsDataURL(file);
            });
        }
    });
    
    function toggleMediaFields() {
        const selectedType = mediaType.value;
        
        // Show/hide fields based on selection
        if (selectedType === 'external_video') {
            mediaUploadField.classList.add('hidden');
            externalLinkField.classList.remove('hidden');
            mediaInput.removeAttribute('required');
            document.getElementById('external_link').setAttribute('required', 'required');
        } else {
            mediaUploadField.classList.remove('hidden');
            externalLinkField.classList.add('hidden');
            mediaInput.setAttribute('required', 'required');
            document.getElementById('external_link').removeAttribute('required');
        }
    }
    
    // Initialize on page load
    toggleMediaFields();
    
    // Add event listener for media type change
    mediaType.addEventListener('change', toggleMediaFields);
});
</script>
@endsection