@extends('layouts.owner')

@section('title', 'नयाँ कागजात अपलोड')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-upload me-2"></i>नयाँ कागजात अपलोड गर्नुहोस्
                    </h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('owner.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Student Selection -->
                            <div class="col-md-6">
                                <label for="student_id" class="form-label">विद्यार्थी *</label>
                                <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                    <option value="">विद्यार्थी छान्नुहोस्</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->user->name }} ({{ $student->student_id ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Document Type -->
                            <div class="col-md-6">
                                <label for="document_type" class="form-label">कागजातको प्रकार *</label>
                                <select name="document_type" id="document_type" class="form-select @error('document_type') is-invalid @enderror" required>
                                    <option value="">प्रकार छान्नुहोस्</option>
                                    @foreach($documentTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('document_type') == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('document_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- File Upload -->
                            <div class="col-12">
                                <label for="document" class="form-label">कागजात फाइल *</label>
                                <input type="file" name="document" id="document" 
                                       class="form-control @error('document') is-invalid @enderror"
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                                <div class="form-text">
                                    अनुमतिहरू: PDF, JPG, PNG, DOC, DOCX (अधिकतम 10MB)
                                </div>
                                @error('document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">विवरण</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="कागजातको छोटो विवरण...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Expiry Date -->
                            <div class="col-md-6">
                                <label for="expiry_date" class="form-label">म्याद मिति (यदि लागू भएमा)</label>
                                <input type="date" name="expiry_date" id="expiry_date" 
                                       class="form-control @error('expiry_date') is-invalid @enderror"
                                       value="{{ old('expiry_date') }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- File Preview (will be shown after selection) -->
                            <div class="col-12">
                                <div id="filePreview" class="mt-2" style="display: none;">
                                    <h6>फाइल पूर्वावलोकन:</h6>
                                    <div id="previewContent" class="border p-2 rounded"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('owner.documents.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>पछि फर्कनुहोस्
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-upload me-1"></i>कागजात अपलोड गर्नुहोस्
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('document').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('filePreview');
    const previewContent = document.getElementById('previewContent');
    
    if (file) {
        preview.style.display = 'block';
        const fileType = file.type;
        
        if (fileType.startsWith('image/')) {
            previewContent.innerHTML = `<img src="${URL.createObjectURL(file)}" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">`;
        } else if (fileType === 'application/pdf') {
            previewContent.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-pdf text-danger fa-3x me-3"></i>
                    <div>
                        <strong>PDF फाइल</strong><br>
                        साइज: ${(file.size / 1024).toFixed(2)} KB
                    </div>
                </div>`;
        } else if (fileType.includes('word') || fileType.includes('document')) {
            previewContent.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-word text-primary fa-3x me-3"></i>
                    <div>
                        <strong>Word फाइल</strong><br>
                        साइज: ${(file.size / 1024).toFixed(2)} KB
                    </div>
                </div>`;
        } else {
            previewContent.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-file text-secondary fa-3x me-3"></i>
                    <div>
                        <strong>${file.name}</strong><br>
                        साइज: ${(file.size / 1024).toFixed(2)} KB
                    </div>
                </div>`;
        }
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endpush
@endsection