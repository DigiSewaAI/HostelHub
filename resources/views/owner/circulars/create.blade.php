@extends('layouts.owner')

@section('title', 'नयाँ सूचना सिर्जना गर्नुहोस्')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle mr-2"></i>नयाँ सूचना सिर्जना गर्नुहोस्
                    </h3>
                </div>

                <form action="{{ route('owner.circulars.store') }}" method="POST" id="circularForm">
                    @csrf
                    
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column - Basic Info -->
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">शीर्षक <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title') }}" 
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
                                              placeholder="सूचनाको पूर्ण विवरण लेख्नुहोस्..." required>{{ old('content') }}</textarea>
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
                                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>सामान्य</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>जरुरी</option>
                                        <option value="info" {{ old('priority') == 'info' ? 'selected' : '' }}>जानकारी</option>
                                    </select>
                                    @error('priority')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Audience Type -->
                                <div class="form-group">
                                    <label for="audience_type">लक्षित प्रयोगकर्ता <span class="text-danger">*</span></label>
                                    <select name="audience_type" id="audience_type" class="form-control" required>
                                        <optgroup label="संस्था-विशेष">
                                            <option value="organization_students">संस्थाका विद्यार्थीहरू</option>
                                            <option value="organization_managers">संस्थाका म्यानेजरहरू</option>
                                            <option value="organization_users">संस्थाका सबै प्रयोगकर्ताहरू</option>
                                        </optgroup>
                                        <optgroup label="विशेष लक्ष्यहरू">
                                            <option value="specific_hostel">विशेष होस्टेल</option>
                                            <option value="specific_students">विशेष विद्यार्थीहरू</option>
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
                                           class="form-control" 
                                           value="{{ old('scheduled_at') }}">
                                    <small class="text-muted">खाली छोड्नुहोस् यदि तुरुन्त प्रकाशन गर्न चाहनुहुन्छ भने</small>
                                </div>

                                <!-- Expiry -->
                                <div class="form-group">
                                    <label for="expires_at">समाप्ति मिति</label>
                                    <input type="datetime-local" name="expires_at" id="expires_at" 
                                           class="form-control" 
                                           value="{{ old('expires_at') }}">
                                    <small class="text-muted">सूचना स्वतः समाप्त हुने मिति</small>
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
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save mr-1"></i>सूचना सुरक्षित गर्नुहोस्
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
    const circularForm = document.getElementById('circularForm');

    function loadSpecificAudienceOptions() {
        const selectedAudience = audienceType.value;
        specificSection.style.display = 'none';
        targetAudience.innerHTML = '';

        if (selectedAudience === 'specific_hostel') {
            specificSection.style.display = 'block';
            specificLabel.textContent = 'होस्टेलहरू चयन गर्नुहोस्';
            
            @foreach($hostels as $hostel)
                targetAudience.innerHTML += `<option value="{{ $hostel->id }}" {{ (old('target_audience') && in_array($hostel->id, old('target_audience'))) ? 'selected' : '' }}>{{ $hostel->name }}</option>`;
            @endforeach
            
        } else if (selectedAudience === 'specific_students') {
            specificSection.style.display = 'block';
            specificLabel.textContent = 'विद्यार्थीहरू चयन गर्नुहोस्';
            
            @foreach($students as $student)
            @if($student->user)
                targetAudience.innerHTML += `<option value="{{ $student->user_id }}" {{ (old('target_audience') && in_array($student->user_id, old('target_audience'))) ? 'selected' : '' }}>{{ $student->user->name }} ({{ $student->user->email ?? 'N/A' }})</option>`;
            @endif
            @endforeach
        }
    }

    // ✅ FIXED: PROPER FORM RESET AFTER SUCCESSFUL SUBMISSION
    @if(session('success') && session('clear_form'))
        // Clear all form fields completely
        circularForm.reset();
        
        // Reset dynamic audience selection
        loadSpecificAudienceOptions();
        
        // Show success message
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        });
        
        // ✅ FIXED: Clear session flags via AJAX to prevent form reset on page refresh
        fetch('{{ route("owner.clear.form.flag") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        }).then(response => response.json())
          .then(data => {
              console.log('Form flags cleared successfully');
          })
          .catch(error => {
              console.error('Error clearing form flags:', error);
          });
    @endif

    // Initialize audience selection on page load
    loadSpecificAudienceOptions();
    
    // Add event listener for audience type change
    audienceType.addEventListener('change', loadSpecificAudienceOptions);

    // Enhanced form submission feedback
    circularForm.addEventListener('submit', function() {
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>सुरक्षित गर्दै...';
    });

    // Auto-set initial values for better UX
    @if(!old('audience_type'))
        audienceType.value = 'organization_students';
    @endif
    
    @if(!old('priority'))
        document.getElementById('priority').value = 'normal';
    @endif
});

// ✅ FIXED: Show success message only when not clearing form
@if(session('success') && !session('clear_form'))
    document.addEventListener('DOMContentLoaded', function() {
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        });
    });
@endif

// Show error message if there are form errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        Toast.fire({
            icon: 'error',
            title: 'केही त्रुटिहरू छन्। कृपया फर्म जाँच गर्नुहोस्।'
        });
    });
@endif
</script>
@endpush