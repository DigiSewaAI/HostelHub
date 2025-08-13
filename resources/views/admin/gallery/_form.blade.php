<div class="bg-white p-6 rounded-lg shadow">
    <!-- Title -->
    <div class="mb-4">
        <label for="title" class="block text-gray-700 font-medium mb-2">Title *</label>
        <input type="text" name="title" id="title" value="{{ old('title', $gallery->title ?? '') }}" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Description -->
    <div class="mb-4">
        <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
        <textarea name="description" id="description" rows="3"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $gallery->description ?? '') }}</textarea>
    </div>

    <!-- Type Selection -->
    <div class="mb-4">
        <label for="type" class="block text-gray-700 font-medium mb-2">Media Type *</label>
        <select name="type" id="type" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <option value="photo" {{ (old('type', $gallery->type ?? '') == 'photo' ? 'selected' : '') }}>Photo</option>
            <option value="local_video" {{ (old('type', $gallery->type ?? '') == 'local_video' ? 'selected' : '') }}>Local Video</option>
            <option value="youtube" {{ (old('type', $gallery->type ?? '') == 'youtube' ? 'selected' : '') }}>YouTube Video</option>
        </select>
        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- File Upload (Conditional) -->
    <div class="mb-4" id="file-upload-section">
        <label class="block text-gray-700 font-medium mb-2">Upload File</label>
        <input type="file" name="file" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
        @if(isset($gallery) && in_array($gallery->type, ['photo', 'local_video']))
            <div class="mt-2">
                <p class="text-sm text-gray-600">Current file: {{ basename($gallery->file_path) }}</p>
                @if($gallery->type === 'photo')
                    <img src="{{ asset('storage/'.$gallery->file_path) }}" alt="Current" class="mt-2 h-32">
                @else
                    <video src="{{ asset('storage/'.$gallery->file_path) }}" controls class="mt-2 h-32"></video>
                @endif
            </div>
        @endif
    </div>

    <!-- YouTube URL (Conditional) -->
    <div class="mb-4 hidden" id="youtube-section">
        <label for="external_link" class="block text-gray-700 font-medium mb-2">YouTube URL *</label>
        <input type="url" name="external_link" id="external_link" 
            value="{{ old('external_link', $gallery->external_link ?? '') }}"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('external_link') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Status Toggles -->
    <div class="flex space-x-6 mb-4">
        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" 
                {{ old('is_active', $gallery->is_active ?? true) ? 'checked' : '' }}
                class="h-5 w-5 text-blue-600 rounded">
            <label for="is_active" class="ml-2 text-gray-700">Active</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                {{ old('is_featured', $gallery->is_featured ?? false) ? 'checked' : '' }}
                class="h-5 w-5 text-blue-600 rounded">
            <label for="is_featured" class="ml-2 text-gray-700">Featured</label>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelector = document.getElementById('type');
        const fileSection = document.getElementById('file-upload-section');
        const youtubeSection = document.getElementById('youtube-section');
        
        function toggleFields() {
            if (typeSelector.value === 'youtube') {
                fileSection.classList.add('hidden');
                youtubeSection.classList.remove('hidden');
            } else {
                fileSection.classList.remove('hidden');
                youtubeSection.classList.add('hidden');
            }
        }
        
        // Initial toggle
        toggleFields();
        
        // On change event
        typeSelector.addEventListener('change', toggleFields);
    });
</script>