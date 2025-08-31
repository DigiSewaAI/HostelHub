@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-6">
    <!-- Media Type Field -->
    <div class="form-group">
        <label for="media_type" class="block text-sm font-medium text-gray-700 mb-1">Media Type *</label>
        <select name="media_type" id="media_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <option value="photo" {{ old('media_type', $gallery->media_type ?? 'photo') == 'photo' ? 'selected' : '' }}>Image</option>
            <option value="external_video" {{ old('media_type', $gallery->media_type ?? '') == 'external_video' ? 'selected' : '' }}>External Video (YouTube/Vimeo)</option>
            <option value="local_video" {{ old('media_type', $gallery->media_type ?? '') == 'local_video' ? 'selected' : '' }}>Local Video Upload</option>
        </select>
    </div>

    <!-- Image Upload Field -->
    <div class="form-group" id="image-field" style="{{ old('media_type', $gallery->media_type ?? 'photo') == 'photo' ? '' : 'display:none;' }}">
        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image {{ old('media_type', $gallery->media_type ?? 'photo') == 'photo' ? '*' : '' }}</label>
        <input type="file" name="image" id="image" class="w-full" accept="image/*">
        <small class="text-gray-500 mt-1 block">Supported formats: JPG, PNG, GIF (Max size: 5MB)</small>
        @if(isset($gallery) && $gallery->media_type == 'photo' && $gallery->file_path)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="Current Image" class="max-h-40 rounded">
                <p class="text-sm text-gray-500 mt-1">Current image</p>
            </div>
        @endif
    </div>

    <!-- External Link Field -->
    <div class="form-group" id="external-link-field" style="{{ old('media_type', $gallery->media_type ?? '') == 'external_video' ? '' : 'display:none;' }}">
        <label for="external_link" class="block text-sm font-medium text-gray-700 mb-1">External Link *</label>
        <input type="url" name="external_link" id="external_link" 
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
               placeholder="https://www.youtube.com/watch?v=example"
               value="{{ old('external_link', $gallery->external_link ?? '') }}">
        <small class="text-gray-500 mt-1 block">YouTube, Vimeo or other video platform URL</small>
        @if(isset($gallery) && $gallery->media_type == 'external_video' && $gallery->external_link)
            @php 
                $youtubeId = '';
                $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
                preg_match($pattern, $gallery->external_link, $matches);
                $youtubeId = $matches[1] ?? '';
            @endphp
            @if($youtubeId)
            <div class="mt-2">
                <img src="https://img.youtube.com/vi/{{ $youtubeId }}/mqdefault.jpg" alt="YouTube Thumbnail" class="max-h-40 rounded">
                <p class="text-sm text-gray-500 mt-1">Current video thumbnail</p>
            </div>
            @endif
        @endif
    </div>

    <!-- Local Video Upload Field -->
    <div class="form-group" id="local-video-field" style="{{ old('media_type', $gallery->media_type ?? '') == 'local_video' ? '' : 'display:none;' }}">
        <label for="local_video" class="block text-sm font-medium text-gray-700 mb-1">Upload Video {{ old('media_type', $gallery->media_type ?? '') == 'local_video' ? '*' : '' }}</label>
        <input type="file" name="local_video" id="local_video" class="w-full" accept="video/*">
        <small class="text-gray-500 mt-1 block">Supported formats: MP4, MOV (Max size: 50MB)</small>
        @if(isset($gallery) && $gallery->media_type == 'local_video' && $gallery->file_path)
            <div class="mt-2">
                <video width="300" controls class="rounded">
                    <source src="{{ asset('storage/' . $gallery->file_path) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <p class="text-sm text-gray-500 mt-1">Current video</p>
            </div>
        @endif
    </div>

    <!-- Title Field -->
    <div class="form-group">
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
        <input type="text" name="title" id="title" 
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
               value="{{ old('title', $gallery->title ?? '') }}" required>
    </div>

    <!-- Description Field -->
    <div class="form-group">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" id="description" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                  rows="3">{{ old('description', $gallery->description ?? '') }}</textarea>
    </div>

    <!-- Category Field - UPDATED CATEGORIES -->
    <div class="form-group">
        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
        <select name="category" id="category" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                required>
            <option value="">Select Category</option>
            <option value="video" {{ old('category', $gallery->category ?? '') == 'video' ? 'selected' : '' }}>Video Tour</option>
            <option value="1 seater" {{ old('category', $gallery->category ?? '') == '1 seater' ? 'selected' : '' }}>1 Seater Room</option>
            <option value="2 seater" {{ old('category', $gallery->category ?? '') == '2 seater' ? 'selected' : '' }}>2 Seater Room</option>
            <option value="3 seater" {{ old('category', $gallery->category ?? '') == '3 seater' ? 'selected' : '' }}>3 Seater Room</option>
            <option value="4 seater" {{ old('category', $gallery->category ?? '') == '4 seater' ? 'selected' : '' }}>4 Seater Room</option>
            <option value="common" {{ old('category', $gallery->category ?? '') == 'common' ? 'selected' : '' }}>Common Area</option>
            <option value="kitchen" {{ old('category', $gallery->category ?? '') == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
            <option value="bathroom" {{ old('category', $gallery->category ?? '') == 'bathroom' ? 'selected' : '' }}>Bathroom</option>
            <option value="study room" {{ old('category', $gallery->category ?? '') == 'study room' ? 'selected' : '' }}>Study Room</option>
            <option value="event" {{ old('category', $gallery->category ?? '') == 'event' ? 'selected' : '' }}>Event Photos</option>
        </select>
    </div>

    <!-- Status Field -->
    <div class="form-group">
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
        <select name="status" id="status" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                required>
            <option value="active" {{ old('status', isset($gallery) ? ($gallery->is_active ? 'active' : 'inactive') : 'active') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', isset($gallery) ? ($gallery->is_active ? 'active' : 'inactive') : 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    
    <!-- Featured Field -->
    <div class="form-group">
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input type="checkbox" name="featured" id="featured" class="h-4 w-4 text-blue-600"
                       {{ old('featured', isset($gallery) ? $gallery->is_featured : false) ? 'checked' : '' }}>
            </div>
            <div class="ml-3 text-sm">
                <label for="featured" class="font-medium text-gray-700">Featured Item</label>
                <p class="text-gray-500">Display this item prominently on the gallery page</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mediaTypeSelect = document.getElementById('media_type');
    const imageField = document.getElementById('image-field');
    const externalLinkField = document.getElementById('external-link-field');
    const localVideoField = document.getElementById('local-video-field');
    
    function toggleFields() {
        imageField.style.display = 'none';
        externalLinkField.style.display = 'none';
        localVideoField.style.display = 'none';
        
        switch(mediaTypeSelect.value) {
            case 'photo':
                imageField.style.display = 'block';
                break;
            case 'external_video':
                externalLinkField.style.display = 'block';
                break;
            case 'local_video':
                localVideoField.style.display = 'block';
                break;
        }
    }

    toggleFields();
    mediaTypeSelect.addEventListener('change', toggleFields);
});
</script>