@extends('layouts.owner')

@section('title', 'कोठा सम्पादन गर्नुहोस्')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">कोठा सम्पादन गर्नुहोस्</h3>
                </div>

                <form action="{{ route('owner.rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hostel_id">होस्टल</label>
                                    <select class="form-control @error('hostel_id') is-invalid @enderror" id="hostel_id" name="hostel_id" required>
                                        <option value="">होस्टल छान्नुहोस्</option>
                                        @foreach($hostels as $hostel)
                                            <option value="{{ $hostel->id }}" {{ $room->hostel_id == $hostel->id ? 'selected' : '' }}>
                                                {{ $hostel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hostel_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_number">कोठा नम्बर</label>
                                    <input type="text" class="form-control @error('room_number') is-invalid @enderror" id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" required>
                                    @error('room_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">कोठाको प्रकार</label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">प्रकार छान्नुहोस्</option>
                                        {{-- ✅ FIXED: Unified room types --}}
                                        <option value="1 seater" {{ old('type', $room->type) == '1 seater' ? 'selected' : '' }}>१ सिटर कोठा</option>
                                        <option value="2 seater" {{ old('type', $room->type) == '2 seater' ? 'selected' : '' }}>२ सिटर कोठा</option>
                                        <option value="3 seater" {{ old('type', $room->type) == '3 seater' ? 'selected' : '' }}>३ सिटर कोठा</option>
                                        <option value="4 seater" {{ old('type', $room->type) == '4 seater' ? 'selected' : '' }}>४ सिटर कोठा</option>
                                        <option value="साझा कोठा" {{ old('type', $room->type) == 'साझा कोठा' ? 'selected' : '' }}>साझा कोठा</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="capacity">क्षमता (व्यक्ति संख्या)</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="current_occupancy">हालको अधिभोग</label>
                                    <input type="number" class="form-control @error('current_occupancy') is-invalid @enderror" id="current_occupancy" name="current_occupancy" 
                                           value="{{ old('current_occupancy', $room->current_occupancy ?? 0) }}" min="0" max="{{ $room->capacity }}" required>
                                    <small class="form-text text-muted">कोठामा हाल बस्ने विद्यार्थीहरूको संख्या</small>
                                    @error('current_occupancy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">मूल्य (प्रतिमहिना)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">रु.</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $room->price) }}" min="0" step="0.01" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- ✅ FIXED: Auto-set gallery category based on room type --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gallery_category" class="form-label">ग्यालरी श्रेणी <span class="text-danger">*</span></label>
                                    <select name="gallery_category" id="gallery_category" class="form-control @error('gallery_category') is-invalid @enderror" required>
                                        <option value="">श्रेणी छान्नुहोस्</option>
                                        <option value="1 seater" {{ old('gallery_category', $room->gallery_category) == '1 seater' ? 'selected' : '' }}>१ सिटर कोठा</option>
                                        <option value="2 seater" {{ old('gallery_category', $room->gallery_category) == '2 seater' ? 'selected' : '' }}>२ सिटर कोठा</option>
                                        <option value="3 seater" {{ old('gallery_category', $room->gallery_category) == '3 seater' ? 'selected' : '' }}>३ सिटर कोठा</option>
                                        <option value="4 seater" {{ old('gallery_category', $room->gallery_category) == '4 seater' ? 'selected' : '' }}>४ सिटर कोठा</option>
                                        <option value="living_room" {{ old('gallery_category', $room->gallery_category) == 'living_room' ? 'selected' : '' }}>लिभिङ रूम</option>
                                        <option value="bathroom" {{ old('gallery_category', $room->gallery_category) == 'bathroom' ? 'selected' : '' }}>बाथरूम</option>
                                        <option value="kitchen" {{ old('gallery_category', $room->gallery_category) == 'kitchen' ? 'selected' : '' }}>भान्सा</option>
                                        <option value="study_room" {{ old('gallery_category', $room->gallery_category) == 'study_room' ? 'selected' : '' }}>अध्ययन कोठा</option>
                                        <option value="events" {{ old('gallery_category', $room->gallery_category) == 'events' ? 'selected' : '' }}>कार्यक्रम</option>
                                        <option value="video_tour" {{ old('gallery_category', $room->gallery_category) == 'video_tour' ? 'selected' : '' }}>भिडियो टुर</option>
                                    </select>
                                    @error('gallery_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">हालको स्थिति</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>उपलब्ध</option>
                                        <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>व्यस्त</option>
                                        <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>मर्मत सम्भार</option>
                                        <option value="partially_available" {{ old('status', $room->status) == 'partially_available' ? 'selected' : '' }}>आंशिक उपलब्ध</option>
                                    </select>
                                    <small class="form-text text-muted">स्वचालित रूपमा अद्यावधिक हुन्छ</small>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Image Upload Field --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image">कोठाको फोटो</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <small class="form-text text-muted">JPG, PNG, JPEG, GIF, WEBP format मा मात्र, अधिकतम size: 2MB</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    {{-- Current Image Preview --}}
                                    @if($room->image)
                                        <div class="mt-2">
                                            <label>हालको फोटो:</label>
                                            <div>
                                                <img src="{{ Storage::disk('public')->url($room->image) }}" alt="Room Image" 
                                                     style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                                            </div>
                                        </div>
                                    @endif
                                    
                                    {{-- New Image Preview --}}
                                    <div id="imagePreview" class="mt-2" style="display: none;">
                                        <label>नयाँ फोटो पूर्वावलोकन:</label>
                                        <div>
                                            <img id="preview" src="#" alt="Image Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">कोठाको विवरण</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> परिवर्तनहरू सुरक्षित गर्नुहोस्
                        </button>
                        <a href="{{ route('owner.rooms.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i> रद्द गर्नुहोस्
                        </a>
                    </div>
                </form>
            </div>
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
        const currentOccupancyInput = document.getElementById('current_occupancy');
        const galleryCategorySelect = document.getElementById('gallery_category');
        const statusSelect = document.getElementById('status');
        
        if (typeSelect && capacityInput && galleryCategorySelect && currentOccupancyInput) {
            typeSelect.addEventListener('change', function() {
                switch(this.value) {
                    case '1 seater':
                        capacityInput.value = 1;
                        currentOccupancyInput.max = 1;
                        galleryCategorySelect.value = '1 seater';
                        break;
                    case '2 seater':
                        capacityInput.value = 2;
                        currentOccupancyInput.max = 2;
                        galleryCategorySelect.value = '2 seater';
                        break;
                    case '3 seater':
                        capacityInput.value = 3;
                        currentOccupancyInput.max = 3;
                        galleryCategorySelect.value = '3 seater';
                        break;
                    case '4 seater':
                        capacityInput.value = 4;
                        currentOccupancyInput.max = 4;
                        galleryCategorySelect.value = '4 seater';
                        break;
                    case 'साझा कोठा':
                        capacityInput.value = 4;
                        currentOccupancyInput.max = 4;
                        galleryCategorySelect.value = '4 seater';
                        break;
                    default:
                        capacityInput.value = 1;
                        currentOccupancyInput.max = 1;
                }
                
                // Ensure current occupancy doesn't exceed new capacity
                if (parseInt(currentOccupancyInput.value) > parseInt(capacityInput.value)) {
                    currentOccupancyInput.value = capacityInput.value;
                }
                
                updateStatus();
            });

            // Update capacity max for current occupancy and gallery category when capacity changes manually
            capacityInput.addEventListener('change', function() {
                const capacity = parseInt(this.value);
                currentOccupancyInput.max = capacity;
                
                // Ensure current occupancy doesn't exceed new capacity
                if (parseInt(currentOccupancyInput.value) > capacity) {
                    currentOccupancyInput.value = capacity;
                }
                
                // Auto-set gallery category based on capacity
                if (capacity >= 1 && capacity <= 4) {
                    galleryCategorySelect.value = capacity + ' seater';
                }
                
                updateStatus();
            });

            // Update status based on occupancy
            currentOccupancyInput.addEventListener('change', updateStatus);

            function updateStatus() {
                const capacity = parseInt(capacityInput.value);
                const occupancy = parseInt(currentOccupancyInput.value);
                
                if (occupancy === 0) {
                    statusSelect.value = 'available';
                } else if (occupancy === capacity) {
                    statusSelect.value = 'occupied';
                } else if (occupancy > 0 && occupancy < capacity) {
                    statusSelect.value = 'partially_available';
                }
            }

            // Initialize on page load
            updateStatus();
        }

        // Image preview functionality
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

            // Validate current occupancy doesn't exceed capacity
            const capacity = parseInt(capacityInput.value);
            const occupancy = parseInt(currentOccupancyInput.value);
            if (occupancy > capacity) {
                isValid = false;
                currentOccupancyInput.classList.add('is-invalid');
                alert('हालको अधिभोग क्षमताभन्दा बढी हुन सक्दैन');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('कृपया सबै आवश्यक फिल्डहरू सही ढंगले भर्नुहोस्');
            }
        });
    });
</script>
@endpush