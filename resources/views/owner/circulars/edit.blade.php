@extends('layouts.owner')

@section('title', 'सूचना सम्पादन गर्नुहोस्')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit mr-2"></i>सूचना सम्पादन गर्नुहोस्
                    </h3>
                </div>

                <form action="{{ route('owner.circulars.update', $circular) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        {{-- सबै validation errors को लागि सामान्य सारांश --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Left Column - Basic Info -->
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">शीर्षक <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title', $circular->title) }}" 
                                           placeholder="सूचनाको शीर्षक लेख्नुहोस्..." required>
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="form-group">
                                    <label for="content">सूचनाको विवरण <span class="text-danger">*</span></label>
                                    <textarea name="content" id="content" 
                                              class="form-control @error('content') is-invalid @enderror" 
                                              rows="8" 
                                              placeholder="सूचनाको पूर्ण विवरण लेख्नुहोस्..." required>{{ old('content', $circular->content) }}</textarea>
                                    @error('content')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column - Settings -->
                            <div class="col-md-4">
                                <!-- Priority -->
                                <div class="form-group">
                                    <label for="priority">प्राथमिकता <span class="text-danger">*</span></label>
                                    <select name="priority" id="priority" class="form-control" required>
                                        <option value="normal" {{ old('priority', $circular->priority) == 'normal' ? 'selected' : '' }}>सामान्य</option>
                                        <option value="urgent" {{ old('priority', $circular->priority) == 'urgent' ? 'selected' : '' }}>जरुरी</option>
                                        <option value="info" {{ old('priority', $circular->priority) == 'info' ? 'selected' : '' }}>जानकारी</option>
                                    </select>
                                    @error('priority')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label for="status">स्थिति <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="draft" {{ old('status', $circular->status) == 'draft' ? 'selected' : '' }}>मस्यौदा</option>
                                        <option value="published" {{ old('status', $circular->status) == 'published' ? 'selected' : '' }}>प्रकाशित</option>
                                        <option value="archived" {{ old('status', $circular->status) == 'archived' ? 'selected' : '' }}>संग्रहित</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Audience Type -->
                                <div class="form-group">
                                    <label for="audience_type">लक्षित प्रयोगकर्ता <span class="text-danger">*</span></label>
                                    <select name="audience_type" id="audience_type" class="form-control" required>
                                        <optgroup label="संस्था-विशेष">
                                            <option value="organization_students" {{ old('audience_type', $circular->audience_type) == 'organization_students' ? 'selected' : '' }}>संस्थाका विद्यार्थीहरू</option>
                                            <option value="organization_managers" {{ old('audience_type', $circular->audience_type) == 'organization_managers' ? 'selected' : '' }}>संस्थाका म्यानेजरहरू</option>
                                            <option value="organization_users" {{ old('audience_type', $circular->audience_type) == 'organization_users' ? 'selected' : '' }}>संस्थाका सबै प्रयोगकर्ताहरू</option>
                                        </optgroup>
                                        <optgroup label="विशेष लक्ष्यहरू">
                                            <option value="specific_hostel" {{ old('audience_type', $circular->audience_type) == 'specific_hostel' ? 'selected' : '' }}>विशेष होस्टेल</option>
                                            <option value="specific_students" {{ old('audience_type', $circular->audience_type) == 'specific_students' ? 'selected' : '' }}>विशेष विद्यार्थीहरू</option>
                                        </optgroup>
                                    </select>
                                    @error('audience_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Specific Audience Selection (Dynamic) -->
                                <div id="specific_audience_section" class="form-group" style="display: none;">
                                    <label id="specific_audience_label">लक्षित प्रयोगकर्ता चयन गर्नुहोस्</label>
                                    <select id="target_audience" name="target_audience[]" class="form-control" multiple>
                                        <!-- Options will be loaded dynamically -->
                                    </select>
                                    @error('target_audience')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Schedule -->
                                <div class="form-group">
                                    <label for="scheduled_at">तोकिएको प्रकाशन मिति</label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" 
                                           class="form-control @error('scheduled_at') is-invalid @enderror" 
                                           value="{{ old('scheduled_at', $circular->scheduled_at ? $circular->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                    <small class="text-muted">खाली छोड्नुहोस् यदि तुरुन्त प्रकाशन गर्न चाहनुहुन्छ भने</small>
                                    @error('scheduled_at')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Expiry -->
                                <div class="form-group">
                                    <label for="expires_at">समाप्ति मिति</label>
                                    <input type="datetime-local" name="expires_at" id="expires_at" 
                                           class="form-control @error('expires_at') is-invalid @enderror" 
                                           value="{{ old('expires_at', $circular->expires_at ? $circular->expires_at->format('Y-m-d\TH:i') : '') }}">
                                    <small class="text-muted">सूचना स्वतः समाप्त हुने मिति</small>
                                    @error('expires_at')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('owner.circulars.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i>पछि जानुहोस्
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i>अद्यावधिक गर्नुहोस्
                                </button>
                            </div>
                        </div>
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
    const audienceType = document.getElementById('audience_type');
    const specificSection = document.getElementById('specific_audience_section');
    const targetAudience = document.getElementById('target_audience');
    const specificLabel = document.getElementById('specific_audience_label');

    // PHP variables to JavaScript
    const hostels = @json($hostels);
    const students = @json($students);
    const oldTarget = @json(old('target_audience', $circular->target_audience ?? []));

    function loadSpecificAudienceOptions() {
        const selectedAudience = audienceType.value;
        specificSection.style.display = 'none';
        targetAudience.innerHTML = '';

        if (selectedAudience === 'specific_hostel') {
            specificSection.style.display = 'block';
            specificLabel.textContent = 'होस्टेलहरू चयन गर्नुहोस्';
            
            hostels.forEach(hostel => {
                const option = document.createElement('option');
                option.value = hostel.id;
                option.textContent = hostel.name;
                if (oldTarget.includes(String(hostel.id))) {
                    option.selected = true;
                }
                targetAudience.appendChild(option);
            });
            
        } else if (selectedAudience === 'specific_students') {
            specificSection.style.display = 'block';
            specificLabel.textContent = 'विद्यार्थीहरू चयन गर्नुहोस्';
            
            students.forEach(student => {
                // सुरक्षित रूपमा user जाँच गर्ने
                if (student.user) {
                    const option = document.createElement('option');
                    option.value = student.user_id;
                    option.textContent = student.user.name + ' (' + (student.user.email || 'N/A') + ')';
                    if (oldTarget.includes(String(student.user_id))) {
                        option.selected = true;
                    }
                    targetAudience.appendChild(option);
                }
            });
        }
    }

    audienceType.addEventListener('change', loadSpecificAudienceOptions);
    loadSpecificAudienceOptions();
});
</script>
@endpush