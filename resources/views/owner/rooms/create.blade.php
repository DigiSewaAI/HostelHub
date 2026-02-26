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
{{-- ✅ Validation Error Summary --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>कृपया तलका त्रुटिहरू सच्याउनुहोस्:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">कोठा विवरण</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.rooms.store') }}" method="POST" enctype="multipart/form-data" id="roomForm">
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
                                {{-- ✅ FIXED: Unified room types --}}
                                <option value="1 seater" {{ old('type') == '1 seater' ? 'selected' : '' }}>१ सिटर कोठा</option>
                                <option value="2 seater" {{ old('type') == '2 seater' ? 'selected' : '' }}>२ सिटर कोठा</option>
                                <option value="3 seater" {{ old('type') == '3 seater' ? 'selected' : '' }}>३ सिटर कोठा</option>
                                <option value="4 seater" {{ old('type') == '4 seater' ? 'selected' : '' }}>४ सिटर कोठा</option>
                                <option value="साझा कोठा" {{ old('type') == 'साझा कोठा' ? 'selected' : '' }}>साझा कोठा</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">क्षमता (व्यक्ति संख्या) <span class="text-danger">*</span></label>
                            <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror"
                                   value="{{ old('capacity', 1) }}" min="1" max="20" required readonly>
                            <small class="form-text text-muted" id="capacityHelp">कोठा प्रकार छान्नुहोस्</small>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_occupancy" class="form-label">हालको अधिभोग <span class="text-danger">*</span></label>
                            <input type="number" name="current_occupancy" id="current_occupancy" class="form-control @error('current_occupancy') is-invalid @enderror"
                                   value="{{ old('current_occupancy', 0) }}" min="0" max="1" required>
                            <small class="form-text text-muted">कोठामा हाल बस्ने विद्यार्थीहरूको संख्या</small>
                            @error('current_occupancy')
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

                    {{-- ✅ FIXED: Gallery Category Field with auto-selection based on room type --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gallery_category" class="form-label">ग्यालरी श्रेणी</label>
                            <select name="gallery_category" id="gallery_category" class="form-select @error('gallery_category') is-invalid @enderror">
                                <option value="">स्वचालित रूपमा सेट हुन्छ</option>
                                <option value="1 seater">१ सिटर कोठा</option>
                                <option value="2 seater">२ सिटर कोठा</option>
                                <option value="3 seater">३ सिटर कोठा</option>
                                <option value="4 seater">४ सिटर कोठा</option>
                                <option value="साझा कोठा">साझा कोठा</option> <!-- ✅ NEW OPTION -->
                                <option value="living_room">लिभिङ रूम</option>
                                <option value="bathroom">बाथरूम</option>
                                <option value="kitchen">भान्सा</option>
                                <option value="study_room">अध्ययन कोठा</option>
                                <option value="events">कार्यक्रम</option>
                                <option value="video_tour">भिडियो टुर</option>
                            </select>
                            <small class="form-text text-muted">स्वचालित रूपमा कोठा प्रकार अनुसार सेट हुन्छ</small>
                            @error('gallery_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- ✅ FIXED: Status Field with Normalized Values --}}
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">स्थिति <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                {{-- ✅ FIXED: Using normalized English values with Nepali labels --}}
                                <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>उपलब्ध</option>
                                <option value="partially_available" {{ old('status') == 'partially_available' ? 'selected' : '' }}>आंशिक उपलब्ध</option>
                                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>व्यस्त</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>मर्मत सम्भार</option>
                            </select>
                            <small class="form-text text-muted">स्वचालित रूपमा अद्यावधिक हुन्छ</small>
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
        // ✅ NEW: Type-capacity validation rules
        const typeCapacityRules = {
            '1 seater': 1,
            '2 seater': 2,
            '3 seater': 3,
            '4 seater': 4,
            'साझा कोठा': 'custom'
        };

        // ✅ NEW: Type-capacity validation function
        function validateTypeCapacity(type, capacity) {
            if (typeCapacityRules[type] && typeCapacityRules[type] !== 'custom') {
                return capacity == typeCapacityRules[type];
            }
            return type !== 'साझा कोठा' || capacity >= 5;
        }

        // Auto-calculate capacity based on room type
        const typeSelect = document.getElementById('type');
        const capacityInput = document.getElementById('capacity');
        const currentOccupancyInput = document.getElementById('current_occupancy');
        const galleryCategorySelect = document.getElementById('gallery_category');
        const statusSelect = document.getElementById('status');
        const capacityHelp = document.getElementById('capacityHelp');
        const form = document.getElementById('roomForm');
        
        if (typeSelect && capacityInput && galleryCategorySelect && currentOccupancyInput && statusSelect) {
            typeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                
                switch(selectedType) {
                    case '1 seater':
                        capacityInput.value = 1;
                        capacityInput.readOnly = true;
                        currentOccupancyInput.max = 1;
                        galleryCategorySelect.value = '1 seater';
                        capacityHelp.textContent = '१ सिटर कोठाको क्षमता 1 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-success';
                        break;
                    case '2 seater':
                        capacityInput.value = 2;
                        capacityInput.readOnly = true;
                        currentOccupancyInput.max = 2;
                        galleryCategorySelect.value = '2 seater';
                        capacityHelp.textContent = '२ सिटर कोठाको क्षमता 2 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-success';
                        break;
                    case '3 seater':
                        capacityInput.value = 3;
                        capacityInput.readOnly = true;
                        currentOccupancyInput.max = 3;
                        galleryCategorySelect.value = '3 seater';
                        capacityHelp.textContent = '३ सिटर कोठाको क्षमता 3 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-success';
                        break;
                    case '4 seater':
                        capacityInput.value = 4;
                        capacityInput.readOnly = true;
                        currentOccupancyInput.max = 4;
                        galleryCategorySelect.value = '4 seater';
                        capacityHelp.textContent = '४ सिटर कोठाको क्षमता 4 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-success';
                        break;
                    case 'साझा कोठा':
                        capacityInput.value = 5;
                        capacityInput.readOnly = false;
                        capacityInput.min = 5;
                        currentOccupancyInput.max = capacityInput.value;
                        galleryCategorySelect.value = 'साझा कोठा'; // ✅ CHANGED: '4 seater' → 'साझा कोठा'
                        capacityHelp.textContent = 'साझा कोठाको क्षमता कम्तिमा 5 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-info';
                        break;
                    default:
                        capacityInput.value = 1;
                        capacityInput.readOnly = true;
                        currentOccupancyInput.max = 1;
                        capacityHelp.textContent = 'कोठा प्रकार छान्नुहोस्';
                        capacityHelp.className = 'form-text text-muted';
                }
                
                // Ensure current occupancy doesn't exceed new capacity
                if (parseInt(currentOccupancyInput.value) > parseInt(capacityInput.value)) {
                    currentOccupancyInput.value = capacityInput.value;
                }
                
                // Auto-update status based on new capacity and occupancy
                updateStatus();
            });

            // Update capacity max for current occupancy when capacity changes manually (only for shared rooms)
            capacityInput.addEventListener('change', function() {
                if (typeSelect.value === 'साझा कोठा') {
                    const capacity = parseInt(this.value);
                    currentOccupancyInput.max = capacity;
                    
                    // Ensure current occupancy doesn't exceed new capacity
                    if (parseInt(currentOccupancyInput.value) > capacity) {
                        currentOccupancyInput.value = capacity;
                    }
                    
                    // Auto-update status based on new capacity and occupancy
                    updateStatus();
                }
            });

            // Set initial values based on default type
            if (typeSelect.value) {
                typeSelect.dispatchEvent(new Event('change'));
            } else {
                // Set default state
                capacityInput.readOnly = true;
                capacityHelp.textContent = 'कोठा प्रकार छान्नुहोस्';
            }

            // ✅ FIXED: Update status based on occupancy using normalized English values
            currentOccupancyInput.addEventListener('change', updateStatus);
            
            // ✅ FIXED: Status update function with normalized English values
            function updateStatus() {
                const capacity = parseInt(capacityInput.value);
                const occupancy = parseInt(currentOccupancyInput.value);
                
                // Don't auto-update if status is manually set to maintenance
                if (statusSelect.value === 'maintenance') {
                    return;
                }
                
                if (occupancy === 0) {
                    statusSelect.value = 'available';
                } else if (occupancy === capacity) {
                    statusSelect.value = 'occupied';
                } else if (occupancy > 0 && occupancy < capacity) {
                    statusSelect.value = 'partially_available';
                }
            }

            // Initialize status on page load
            updateStatus();
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
        
        // ✅ ENHANCED: Form validation with type-capacity checking
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            // Clear previous invalid states
            form.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                }
            });

            // Enhanced capacity validation
            const capacity = parseInt(capacityInput.value);
            const occupancy = parseInt(currentOccupancyInput.value);
            const selectedType = typeSelect.value;
            
            // Validate type-capacity consistency
            if (!validateTypeCapacity(selectedType, capacity)) {
                isValid = false;
                capacityInput.classList.add('is-invalid');
                
                const errorMsg = selectedType === 'साझा कोठा' 
                    ? 'साझा कोठाको लागि क्षमता कम्तिमा 5 हुनुपर्छ'
                    : `${selectedType} को लागि क्षमता ${typeCapacityRules[selectedType]} हुनुपर्छ`;
                    
                alert(errorMsg);
            }
            
            // Validate occupancy doesn't exceed capacity
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

        // ✅ NEW: Reset form handler
        form.addEventListener('reset', function() {
            // Clear invalid states
            form.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            // Reset capacity field to default state
            capacityInput.readOnly = true;
            capacityHelp.textContent = 'कोठा प्रकार छान्नुहोस्';
            capacityHelp.className = 'form-text text-muted';
            
            // Hide image preview
            if (imagePreview) {
                imagePreview.style.display = 'none';
            }
        });
    });
</script>
@endpush