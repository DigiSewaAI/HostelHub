@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">सेटिङ्ग सम्पादन गर्नुहोस्</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update', $setting->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="key" class="form-label">सेटिङ्ग कि (Key) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key', $setting->key) }}" required>
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">मान <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', $setting->value) }}" required>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="group" class="form-label">समूह <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('group') is-invalid @enderror" id="group" name="group" value="{{ old('group', $setting->group) }}" required>
                            @error('group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">प्रकार <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">प्रकार छान्नुहोस्</option>
                                <option value="text" {{ old('type', $setting->type) == 'text' ? 'selected' : '' }}>Text</option>
                                <option value="number" {{ old('type', $setting->type) == 'number' ? 'selected' : '' }}>Number</option>
                                <option value="textarea" {{ old('type', $setting->type) == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                <option value="select" {{ old('type', $setting->type) == 'select' ? 'selected' : '' }}>Select</option>
                                <option value="boolean" {{ old('type', $setting->type) == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                <option value="email" {{ old('type', $setting->type) == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="url" {{ old('type', $setting->type) == 'url' ? 'selected' : '' }}>URL</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 {{ $setting->type != 'select' ? 'd-none' : '' }}" id="options-field">
                            <label for="options" class="form-label">विकल्पहरू (JSON Format)</label>
                            <textarea class="form-control @error('options') is-invalid @enderror" id="options" name="options" rows="3">{{ old('options', $setting->options) }}</textarea>
                            @error('options')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Select प्रकारको लागि JSON format मा विकल्पहरू</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">विवरण</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $setting->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">परिवर्तनहरू सुरक्षित गर्नुहोस्</button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">रद्द गर्नुहोस्</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const optionsField = document.getElementById('options-field');
        
        function toggleOptionsField() {
            if (typeSelect.value === 'select') {
                optionsField.classList.remove('d-none');
            } else {
                optionsField.classList.add('d-none');
            }
        }
        
        typeSelect.addEventListener('change', toggleOptionsField);
        toggleOptionsField(); // Initial check
    });
</script>
@endsection