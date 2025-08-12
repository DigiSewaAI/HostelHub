@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gallery Item Details</h1>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Gallery
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="gallery-media-preview mb-4">
                        @if($item['type'] === 'image')
                            <img src="https://via.placeholder.com/400x300?text=Gallery+Image"
                                 alt="{{ $item['title'] }}" class="img-fluid rounded">
                        @else
                            <div class="ratio ratio-16x9">
                                <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                        title="{{ $item['title'] }}"
                                        frameborder="0" allowfullscreen></iframe>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <h2>{{ $item['title'] }}</h2>
                    <p class="text-muted">ID: {{ $item['id'] }} | Type: {{ ucfirst($item['type']) }}</p>

                    <div class="mb-4">
                        <h5>Description</h5>
                        <p>{{ $item['description'] ?? 'No description available' }}</p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.gallery.edit', $item['id']) }}"
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.gallery.destroy', $item['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
