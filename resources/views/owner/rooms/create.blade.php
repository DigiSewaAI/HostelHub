@extends('layouts.owner')

@section('title', 'नयाँ कोठा थप्नुहोस्')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">नयाँ कोठा थप्नुहोस्</h1>
            <a href="{{ route('owner.rooms.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>पछाडि फर्कनुहोस्
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">कोठा विवरण</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.rooms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hostel_id" class="form-label">होस्टल <span class="text-danger">*</span></label>
                            <select name="hostel_id" id="hostel_id" class="form-select @error('hostel_id') is-invalid @enderror" required>
                                <option value="">होस्टल छान्नुहोस्</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                        {{ $hostel->name }} ({{ $hostel->location }})
                                    </option>
                                @endforeach
                            </select>
                            @error('hostel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="room_number" class="form-label">कोठा नम्बर <span class="text-danger">*</span></label>
                            <input type="text" name="room_number" id="room_number" class="form-control @error('room_number') is-invalid @enderror"
                                   value="{{ old('room_number') }}" required placeholder="जस्तै: 101, A-12">
                            @error('room_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">कोठा प्रकार <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">प्रकार छान्नुहोस्</option>
                                <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>एकल कोठा</option>
                                <option value="double" {{ old('type') == 'double' ? 'selected' : '' }}>दुई ब्यक्ति कोठा</option>
                                <option value="shared" {{ old('type') == 'shared' ? 'selected' : '' }}>साझा कोठा</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">क्षमता (व्यक्ति संख्या) <span class="text-danger">*</span></label>
                            <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror"
                                   value="{{ old('capacity', 1) }}" min="1" max="10" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ✅ ADDED: Gallery Category Field --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gallery_category" class="form-label">ग्यालरी श्रेणी <span class="text-danger">*</span></label>
                            <select name="gallery_category" id="gallery_category" class="form-select @error('gallery_category') is-invalid @enderror" required>
                                <option value="">श्रेणी छान्नुहोस्</option>
                                <option value="1_seater" {{ old('gallery_category') == '1_seater' ? 'selected' : '' }}>१ सिटर कोठा</option>
                                <option value="2_seater" {{ old('gallery_category') == '2_seater' ? 'selected' : '' }}>२ सिटर कोठा</option>
                                <option value="3_seater" {{ old('gallery_category') == '3_seater' ? 'selected' : '' }}>३ सिटर कोठा</option>
                                <option value="4_seater" {{ old('gallery_category') == '4_seater' ? 'selected' : '' }}>४ सिटर कोठा</option>
                                <option value="living_room" {{ old('gallery_category') == 'living_room' ? 'selected' : '' }}>लिभिङ रूम</option>
                                <option value="bathroom" {{ old('gallery_category') == 'bathroom' ? 'selected' : '' }}>बाथरूम</option>
                                <option value="kitchen" {{ old('gallery_category') == 'kitchen' ? 'selected' : '' }}>भान्सा</option>
                                <option value="study_room" {{ old('gallery_category') == 'study_room' ? 'selected' : '' }}>अध्ययन कोठा</option>
                                <option value="events" {{ old('gallery_category') == 'events' ? 'selected' : '' }}>कार्यक्रम</option>
                                <option value="video_tour" {{ old('gallery_category') == 'video_tour' ? 'selected' : '' }}>भिडियो टुर</option>
                            </select>
                            @error('gallery_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">मूल्य (प्रति महिना) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">रु.</span>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}" min="0" step="0.01" required placeholder="5000">
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">स्थिति <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>उपलब्ध</option>
                                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>व्यस्त</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>मर्मत सम्भार</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ✅ Room Image Upload Field --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="image" class="form-label">कोठाको फोटो</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                            <div class="form-text">JPG, PNG, JPEG, GIF, WEBP format मा मात्र, अधिकतम size: 2MB</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            {{-- Image Preview --}}
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="preview" src="#" alt="Image Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">विवरण</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="कोठाको बारेमा थप विवरण...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="fas fa-undo me-1"></i>रीसेट
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>कोठा थप्नुहोस्
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-calculate capacity based on room type
        const typeSelect = document.getElementById('type');
        const capacityInput = document.getElementById('capacity');
        const galleryCategorySelect = document.getElementById('gallery_category');
        
        if (typeSelect && capacityInput && galleryCategorySelect) {
            typeSelect.addEventListener('change', function() {
                switch(this.value) {
                    case 'single':
                        capacityInput.value = 1;
                        galleryCategorySelect.value = '1_seater';
                        break;
                    case 'double':
                        capacityInput.value = 2;
                        galleryCategorySelect.value = '2_seater';
                        break;
                    case 'shared':
                        capacityInput.value = 4;
                        galleryCategorySelect.value = '4_seater';
                        break;
                    default:
                        capacityInput.value = 1;
                }
            });

            // Also update gallery category when capacity changes manually
            capacityInput.addEventListener('change', function() {
                const capacity = parseInt(this.value);
                if (capacity >= 1 && capacity <= 4) {
                    galleryCategorySelect.value = capacity + '_seater';
                }
            });

            // Set initial gallery category based on default capacity
            if (capacityInput.value) {
                const initialCapacity = parseInt(capacityInput.value);
                if (initialCapacity >= 1 && initialCapacity <= 4) {
                    galleryCategorySelect.value = initialCapacity + '_seater';
                }
            }
        }

        // ✅ Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const preview = document.getElementById('preview');

        if (imageInput && preview) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.addEventListener('load', function() {
                        preview.src = reader.result;
                        imagePreview.style.display = 'block';
                    });
                    
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('कृपया सबै आवश्यक फिल्डहरू भर्नुहोस्');
            }
        });
    });
</script>
@endpush