@extends('layouts.owner')

@section('title', 'कागजात सम्पादन गर्नुहोस्')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">कागजात सम्पादन गर्नुहोस्</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('owner.documents.index') }}">कागजातहरू</a></li>
                        <li class="breadcrumb-item active">सम्पादन गर्नुहोस्</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('owner.documents.update', $document) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <!-- Student Selection -->
                            <div class="col-md-6">
                                <label for="student_id" class="form-label">विद्यार्थी *</label>
                                <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                    <option value="">विद्यार्थी छान्नुहोस्</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id', $document->student_id) == $student->id ? 'selected' : '' }}>
                                            {{ $student->user->name ?? 'नाम उपलब्ध छैन' }} ({{ $student->student_id ?? 'N/A' }})
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
                                    <option value="admission_form" {{ old('document_type', $document->document_type) == 'admission_form' ? 'selected' : '' }}>भर्ना फारम</option>
                                    <option value="id_card" {{ old('document_type', $document->document_type) == 'id_card' ? 'selected' : '' }}>परिचय पत्र</option>
                                    <option value="fee_receipt" {{ old('document_type', $document->document_type) == 'fee_receipt' ? 'selected' : '' }}>फी रसिद</option>
                                    <option value="transfer_certificate" {{ old('document_type', $document->document_type) == 'transfer_certificate' ? 'selected' : '' }}>सर्टिफिकेट</option>
                                    <option value="character_certificate" {{ old('document_type', $document->document_type) == 'character_certificate' ? 'selected' : '' }}>चरित्र प्रमाणपत्र</option>
                                    <option value="academic_transcript" {{ old('document_type', $document->document_type) == 'academic_transcript' ? 'selected' : '' }}>अकादमिक ट्रान्सक्रिप्ट</option>
                                    <option value="other" {{ old('document_type', $document->document_type) == 'other' ? 'selected' : '' }}>अन्य</option>
                                </select>
                                @error('document_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Document Title -->
                            <div class="col-md-6">
                                <label for="title" class="form-label">कागजातको शीर्षक *</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title', $document->title) }}" placeholder="कागजातको शीर्षक लेख्नुहोस्" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Document Number -->
                            <div class="col-md-6">
                                <label for="document_number" class="form-label">कागजात नम्बर</label>
                                <input type="text" name="document_number" id="document_number" class="form-control @error('document_number') is-invalid @enderror" 
                                       value="{{ old('document_number', $document->document_number) }}" placeholder="कागजात नम्बर (यदि छ भने)">
                                @error('document_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Issue Date -->
                            <div class="col-md-6">
                                <label for="issue_date" class="form-label">जारी मिति *</label>
                                <input type="date" name="issue_date" id="issue_date" class="form-control @error('issue_date') is-invalid @enderror" 
                                       value="{{ old('issue_date', $document->issue_date->format('Y-m-d')) }}" required>
                                @error('issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Expiry Date -->
                            <div class="col-md-6">
                                <label for="expiry_date" class="form-label">समाप्ति मिति</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                       value="{{ old('expiry_date', $document->expiry_date ? $document->expiry_date->format('Y-m-d') : '') }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">विवरण</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" placeholder="कागजातको बारेमा थप विवरण">{{ old('description', $document->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- File Upload -->
                            <div class="col-12">
                                <label for="file_path" class="form-label">कागजात फाइल</label>
                                <input type="file" name="file_path" id="file_path" class="form-control @error('file_path') is-invalid @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                <div class="form-text">
                                    हालको फाइल: <a href="{{ route('owner.documents.download', $document) }}" target="_blank">{{ $document->original_name }}</a>
                                    ({{ number_format($document->file_size / 1024, 2) }} KB)<br>
                                    स्वीकार्य फाइलहरू: PDF, JPG, JPEG, PNG, DOC, DOCX. अधिकतम साइज: 10MB
                                </div>
                                @error('file_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">स्थिति</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', $document->status) == 'active' ? 'selected' : '' }}>सक्रिय</option>
                                    <option value="inactive" {{ old('status', $document->status) == 'inactive' ? 'selected' : '' }}>निष्क्रिय</option>
                                    <option value="expired" {{ old('status', $document->status) == 'expired' ? 'selected' : '' }}>समाप्त</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> कागजात अपडेट गर्नुहोस्
                                </button>
                                <a href="{{ route('owner.documents.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> रद्द गर्नुहोस्
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection