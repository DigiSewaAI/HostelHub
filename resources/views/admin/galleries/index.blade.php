@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gallery Management</h1>
        <a href="{{ route('admin.galleries.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            + Add New
        </a>
    </div>

    <!-- Category Filter -->
    <div class="mb-6 bg-white p-4 rounded shadow">
        <form method="GET" action="{{ route('admin.galleries.index') }}">
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Filter by Category:</label>
            <div class="flex items-center space-x-4">
                <select name="category" id="category" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}" {{ $selectedCategory == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Filter
                </button>
                <a href="{{ route('admin.galleries.index') }}" class="text-gray-500 hover:text-gray-700">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Thumbnail</th>
                    <th class="px-6 py-3 text-left">Title</th>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-left">Type</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Featured</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($galleries as $item)
                <tr>
                    <td class="px-6 py-4">
                        @if($item->file_path)
                            @if(Str::contains($item->mime_type, 'video'))
                                <video width="64" height="64" class="w-16 h-16 object-cover rounded" controls>
                                    <source src="{{ asset('storage/'.$item->file_path) }}" type="{{ $item->mime_type }}">
                                </video>
                            @else
                                <img src="{{ asset('storage/'.$item->file_path) }}" alt="Gallery Image" class="w-16 h-16 object-cover rounded">
                            @endif
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $item->title }}</td>
                    <td class="px-6 py-4 capitalize">{{ $item->category }}</td>
                    <td class="px-6 py-4 capitalize">{{ $item->media_type }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.galleries.toggle-status', $item) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.galleries.toggle-featured', $item) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $item->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $item->is_featured ? 'Yes' : 'No' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('admin.galleries.edit', $item) }}" class="text-blue-500 hover:text-blue-700">
                            Edit
                        </a>
                        <form action="{{ route('admin.galleries.destroy', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $galleries->links() }}
    </div>
</div>

<!-- Modal for viewing media -->
<div id="mediaModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-4 max-w-3xl w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-xl font-bold"></h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="modalContent" class="flex justify-center"></div>
    </div>
</div>

<script>
function viewMedia(item) {
    const modal = document.getElementById('mediaModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    modalTitle.textContent = item.title;
    modalContent.innerHTML = '';
    
    if (item.media_type === 'photo' && item.file_path) {
        modalContent.innerHTML = `<img src="/storage/${item.file_path}" alt="${item.title}" class="max-h-96 object-contain">`;
    } else if (item.media_type === 'local_video' && item.file_path) {
        modalContent.innerHTML = `
            <video controls class="max-h-96">
                <source src="/storage/${item.file_path}" type="${item.mime_type}">
                Your browser does not support the video tag.
            </video>
        `;
    } else if (item.media_type === 'external_video' && item.external_link) {
        modalContent.innerHTML = `
            <iframe width="560" height="315" src="https://www.youtube.com/embed/${getYouTubeId(item.external_link)}" 
                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen></iframe>
        `;
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('mediaModal').classList.add('hidden');
}

function getYouTubeId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

// Close modal when clicking outside
document.getElementById('mediaModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection