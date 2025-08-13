@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gallery Management</h1>
        <a href="{{ route('admin.gallery.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            + Add New
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Thumbnail</th>
                    <th class="px-6 py-3 text-left">Title</th>
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
                        @if($item->type === 'photo')
                            <img src="{{ asset('storage/'.$item->file_path) }}" alt="Thumb" class="w-16 h-16 object-cover rounded">
                        @elseif($item->type === 'local_video')
                            <video class="w-16 h-16 rounded" muted>
                                <source src="{{ asset('storage/'.$item->file_path) }}" type="video/mp4">
                            </video>
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $item->title }}</td>
                    <td class="px-6 py-4 capitalize">{{ $item->type }}</td>
                    <td class="px-6 py-4">
                        <form class="toggle-form" action="{{ route('admin.gallery.toggle-status', $item) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        <form class="toggle-form" action="{{ route('admin.gallery.toggle-featured', $item) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $item->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $item->is_featured ? 'Yes' : 'No' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="text-blue-500 hover:text-blue-700">
                            Edit
                        </a>
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST">
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

<script>
    document.querySelectorAll('.toggle-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: new FormData(this)
            }).then(response => {
                if(response.ok) location.reload();
            });
        });
    });
</script>
@endsection