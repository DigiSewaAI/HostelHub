@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Gallery Item</h1>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Gallery
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.gallery.update', $item['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title"
                           value="{{ $item['title'] }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Media</label>
                    <div>
                        @if($item['type'] === 'image')
                            <img src="https://via.placeholder.com/300x200?text=Current+Image"
                                 alt="{{ $item['title'] }}" class="img-thumbnail" width="200">
                        @else
                            <div class="ratio ratio-16x9" style="max-width: 300px;">
                                <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                        title="{{ $item['title'] }}"
                                        frameborder="0" allowfullscreen></iframe>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label for="media" class="form-label">Replace File (Optional)</label>
                    <input class="form-control" type="file" id="media" name="media">
                    <div class="form-text">Leave empty to keep current file</div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"
                              rows="3">{{ $item['description'] ?? '' }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Gallery Item
                    </button>
                    <a href="{{ route('admin.gallery.show', $item['id']) }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
