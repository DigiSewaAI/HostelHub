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
    <!-- Hostel Selection (Admin Only) -->
    @role('admin')
    <div class="form-group">
        <label for="hostel_id" class="block text-sm font-medium text-gray-700 mb-1">होस्टल</label>
        <select name="hostel_id" id="hostel_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">होस्टल छान्नुहोस्</option>
            @foreach($hostels as $id => $name)
                <option value="{{ $id }}" {{ old('hostel_id', isset($gallery) ? $gallery->hostel_id : '') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>
    @endrole

    <!-- Media Type Field -->
    <div class="form-group">
        <label for="media_type" class="block text-sm font-medium text-gray-700 mb-1">मिडिया प्रकार *</label>
        <select name="media_type" id="media_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <option value="photo" {{ old('media_type', $gallery->media_type ?? 'photo') == 'photo' ? 'selected' : '' }}>तस्वीर</option>
            <option value="external_video" {{ old('media_type', $gallery->media_type ?? '') == 'external_video' ? 'selected' : '' }}>बाह्य भिडियो (YouTube/Vimeo)</option>
            <option value="local_video" {{ old('media_type', $gallery->media_type ?? '') == 'local_video' ? 'selected' : '' }}>स्थानीय भिडियो अपलोड</option>
        </select>
    </div>

    <!-- Multiple File Upload Field -->
    <div class="form-group" id="multiple-media-field" style="{{ old('media_type', $gallery->media_type ?? 'photo') == 'photo' ? '' : 'display:none;' }}">
        <label for="media" class="block text-sm font-medium text-gray-700 mb-1">फाइलहरू अपलोड गर्नुहोस् *</label>
        <input type="file" name="media[]" id="media" class="w-full" accept="image/*,video/*" multiple>
        <small class="text-gray-500 mt-1 block">समर्थित प्रारूपहरू: JPG, PNG, GIF, MP4, MOV, AVI (अधिकतम आकार: १००MB)</small>
        @if(isset($gallery) && $gallery->media_type == 'photo' && $gallery->file_path)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="हालको तस्वीर" class="max-h-40 rounded">
                <p class="text-sm text-gray-500 mt-1">हालको तस्वीर</p>
            </div>
        @endif
    </div>

    <!-- External Link Field -->
    <div class="form-group" id="external-link-field" style="{{ old('media_type', $gallery->media_type ?? '') == 'external_video' ? '' : 'display:none;' }}">
        <label for="external_link" class="block text-sm font-medium text-gray-700 mb-1">बाह्य लिंक *</label>
        <input type="url" name="external_link" id="external_link" 
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
               placeholder="https://www.youtube.com/watch?v=example"
               value="{{ old('external_link', $gallery->external_link ?? '') }}">
        <small class="text-gray-500 mt-1 block">YouTube, Vimeo वा अन्य भिडियो प्लेटफर्म URL</small>
        @if(isset($gallery) && $gallery->media_type == 'external_video' && $gallery->external_link)
            @php 
                $youtubeId = '';
                $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
                preg_match($pattern, $gallery->external_link, $matches);
                $youtubeId = $matches[1] ?? '';
            @endphp
            @if($youtubeId)
            <div class="mt-2">
                <img src="https://img.youtube.com/vi/{{ $youtubeId }}/mqdefault.jpg" alt="YouTube थम्बनेल" class="max-h-40 rounded">
                <p class="text-sm text-gray-500 mt-1">हालको भिडियो थम्बनेल</p>
            </div>
            @endif
        @endif
    </div>

    <!-- Title Field -->
    <div class="form-group">
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">शीर्षक *</label>
        <input type="text" name="title" id="title" 
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
               value="{{ old('title', $gallery->title ?? '') }}" required>
    </div>

    <!-- Description Field -->
    <div class="form-group">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">विवरण</label>
        <textarea name="description" id="description" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                  rows="3">{{ old('description', $gallery->description ?? '') }}</textarea>
    </div>

    <!-- Category Field -->
    <div class="form-group">
        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">श्रेणी *</label>
        <select name="category" id="category" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                required>
            <option value="">श्रेणी छान्नुहोस्</option>
            <option value="video" {{ old('category', $gallery->category ?? '') == 'video' ? 'selected' : '' }}>भिडियो टुर</option>
            <option value="1 seater" {{ old('category', $gallery->category ?? '') == '1 seater' ? 'selected' : '' }}>१ सिटर कोठा</option>
            <option value="2 seater" {{ old('category', $gallery->category ?? '') == '2 seater' ? 'selected' : '' }}>२ सिटर कोठा</option>
            <option value="3 seater" {{ old('category', $gallery->category ?? '') == '3 seater' ? 'selected' : '' }}>३ सिटर कोठा</option>
            <option value="4 seater" {{ old('category', $gallery->category ?? '') == '4 seater' ? 'selected' : '' }}>४ सिटर कोठा</option>
            <option value="common" {{ old('category', $gallery->category ?? '') == 'common' ? 'selected' : '' }}>सामान्य क्षेत्र</option>
            <option value="kitchen" {{ old('category', $gallery->category ?? '') == 'kitchen' ? 'selected' : '' }}>भान्सा</option>
            <option value="bathroom" {{ old('category', $gallery->category ?? '') == 'bathroom' ? 'selected' : '' }}>बाथरूम</option>
            <option value="study room" {{ old('category', $gallery->category ?? '') == 'study room' ? 'selected' : '' }}>अध्ययन कोठा</option>
            <option value="event" {{ old('category', $gallery->category ?? '') == 'event' ? 'selected' : '' }}>कार्यक्रम तस्वीरहरू</option>
        </select>
    </div>

    <!-- Status Field -->
    <div class="form-group">
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">स्थिति *</label>
        <select name="status" id="status" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                required>
            <option value="active" {{ old('status', isset($gallery) ? ($gallery->is_active ? 'active' : 'inactive') : 'active') == 'active' ? 'selected' : '' }}>सक्रिय</option>
            <option value="inactive" {{ old('status', isset($gallery) ? ($gallery->is_active ? 'active' : 'inactive') : 'active') == 'inactive' ? 'selected' : '' }}>निष्क्रिय</option>
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
                <label for="featured" class="font-medium text-gray-700">फिचर्ड आइटम</label>
                <p class="text-gray-500">यस आइटमलाई ग्यालेरी पृष्ठमा प्रमुखता देखाउनुहोस्</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mediaTypeSelect = document.getElementById('media_type');
    const multipleMediaField = document.getElementById('multiple-media-field');
    const externalLinkField = document.getElementById('external-link-field');
    
    function toggleFields() {
        multipleMediaField.style.display = 'none';
        externalLinkField.style.display = 'none';
        
        switch(mediaTypeSelect.value) {
            case 'photo':
                multipleMediaField.style.display = 'block';
                break;
            case 'external_video':
                externalLinkField.style.display = 'block';
                break;
            case 'local_video':
                multipleMediaField.style.display = 'block';
                break;
        }
    }

    toggleFields();
    mediaTypeSelect.addEventListener('change', toggleFields);
});
</script>