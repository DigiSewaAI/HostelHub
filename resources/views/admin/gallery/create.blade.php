@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add Gallery Item</h1>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Gallery
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title"
                           placeholder="Enter title" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Media Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">Select type</option>
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="media" class="form-label">Upload File</label>
                    <input class="form-control" type="file" id="media" name="media" required>
                    <div class="form-text">Max file size: 5MB. Supported: JPG, PNG, MP4</div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"
                              rows="3" placeholder="Enter description"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Gallery Item
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
