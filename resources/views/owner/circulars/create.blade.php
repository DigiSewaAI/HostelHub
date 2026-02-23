@extends('layouts.owner')

@section('title', 'नयाँ सूचना सिर्जना गर्नुहोस्')

@section('content')
<div class="container-fluid">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">शीर्षक <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}"
                                           placeholder="सूचनाको शीर्षक लेख्नुहोस्..." required>
                                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="content">सूचनाको विवरण <span class="text-danger">*</span></label>
                                    <textarea name="content" id="content"
                                              class="form-control @error('content') is-invalid @enderror"
                                              rows="8"
                                              placeholder="सूचनाको पूर्ण विवरण लेख्नुहोस्..." required>{{ old('content') }}</textarea>
                                    @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="priority">प्राथमिकता <span class="text-danger">*</span></label>
                                    <select name="priority" id="priority" class="form-control" required>
                                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>सामान्य</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>जरुरी</option>
                                        <option value="info" {{ old('priority') == 'info' ? 'selected' : '' }}>जानकारी</option>
                                    </select>
                                    @error('priority') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="audience_type">लक्षित प्रयोगकर्ता <span class="text-danger">*</span></label>
                                    <select name="audience_type" id="audience_type" class="form-control" required>
                                        <optgroup label="संस्था-विशेष">
                                            <option value="organization_students" {{ old('audience_type')=='organization_students'?'selected':'' }}>संस्थाका विद्यार्थीहरू</option>
                                            <option value="organization_managers" {{ old('audience_type')=='organization_managers'?'selected':'' }}>संस्थाका म्यानेजरहरू</option>
                                            <option value="organization_users" {{ old('audience_type')=='organization_users'?'selected':'' }}>संस्थाका सबै प्रयोगकर्ताहरू</option>
                                        </optgroup>
                                        <optgroup label="विशेष लक्ष्यहरू">
                                            <option value="specific_hostel" {{ old('audience_type')=='specific_hostel'?'selected':'' }}>विशेष होस्टेल</option>
                                            <option value="specific_students" {{ old('audience_type')=='specific_students'?'selected':'' }}>विशेष विद्यार्थीहरू</option>
                                        </optgroup>
                                    </select>
                                    @error('audience_type') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <!-- Specific Audience -->
                                <div id="specific_audience_section" class="form-group" style="display:none;">
                                    <label id="specific_audience_label">लक्षित प्रयोगकर्ता चयन गर्नुहोस्</label>
                                    <select id="target_audience" name="target_audience[]" class="form-control" multiple>
                                        <!-- Options will be loaded dynamically -->
                                    </select>
                                    @error('target_audience') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="scheduled_at">तोकिएको प्रकाशन मिति</label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                           class="form-control @error('scheduled_at') is-invalid @enderror"
                                           value="{{ old('scheduled_at') }}">
                                    <small class="text-muted">खाली छोड्नुहोस् यदि तुरुन्त प्रकाशन गर्न चाहनुहुन्छ भने</small>
                                    @error('scheduled_at') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="expires_at">समाप्ति मिति</label>
                                    <input type="datetime-local" name="expires_at" id="expires_at"
                                           class="form-control @error('expires_at') is-invalid @enderror"
                                           value="{{ old('expires_at') }}">
                                    <small class="text-muted">सूचना स्वतः समाप्त हुने मिति</small>
                                    @error('expires_at') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('owner.circulars.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i>पछि जानुहोस्</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i>सूचना सुरक्षित गर्नुहोस्</button>
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

    const hostels = @json($hostels);
    const students = @json($students);
    const oldTarget = @json(old('target_audience', []));

    function loadSpecificAudienceOptions() {
        const selectedAudience = audienceType.value;
        targetAudience.innerHTML = '';
        specificSection.style.display = 'none';

        if (selectedAudience === 'specific_hostel') {
            specificSection.style.display = 'block';
            specificLabel.textContent = 'होस्टेलहरू चयन गर्नुहोस्';
            hostels.forEach(h => {
                const opt = document.createElement('option');
                opt.value = h.id;
                opt.textContent = h.name;
                if (oldTarget.includes(h.id)) opt.selected = true;
                targetAudience.appendChild(opt);
            });
        } else if (selectedAudience === 'specific_students') {
            specificSection.style.display = 'block';
            specificLabel.textContent = 'विद्यार्थीहरू चयन गर्नुहोस्';
            students.forEach(s => {
                if (s.user) {
                    const opt = document.createElement('option');
                    opt.value = s.user_id;
                    opt.textContent = s.user.name + ' (' + (s.user.email ?? 'N/A') + ')';
                    if (oldTarget.includes(s.user_id)) opt.selected = true;
                    targetAudience.appendChild(opt);
                }
            });
        }
    }

    audienceType.addEventListener('change', loadSpecificAudienceOptions);
    loadSpecificAudienceOptions();

    document.getElementById('circularForm').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>सुरक्षित गर्दै...';
    });
});
</script>
@endpush