<div class="rounded-lg overflow-hidden shadow-lg bg-white transition-transform duration-300 hover:shadow-xl hover:-translate-y-1">
    @if($item->type === 'photo')
        <img 
            src="{{ asset('storage/'.$item->file_path) }}" 
            alt="{{ $item->title }}" 
            class="w-full h-48 object-cover"
        >
    @elseif($item->type === 'local_video')
        <video controls class="w-full h-48 object-cover">
            <source src="{{ asset('storage/'.$item->file_path) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    @else
        <iframe 
            src="https://www.youtube.com/embed/{{ getYoutubeId($item->external_link) }}" 
            class="w-full h-48" 
            frameborder="0" 
            allowfullscreen
        ></iframe>
    @endif
    
    <div class="p-4">
        <h3 class="font-bold text-lg mb-1">{{ $item->title }}</h3>
        <p class="text-gray-600 text-sm">{{ Str::limit($item->description, 80) }}</p>
        
        @if($item->is_featured)
            <span class="inline-block mt-2 px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                Featured
            </span>
        @endif
    </div>
</div>