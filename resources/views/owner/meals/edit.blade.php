@extends('layouts.owner')

@section('title', 'भोजन रेकर्ड सम्पादन गर्नुहोस्')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0"><i class="fas fa-edit me-2"></i> भोजन रेकर्ड सम्पादन गर्नुहोस्</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.meals.update', $meal) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">विद्यार्थी <span class="text-danger">*</span></label>
                                <select name="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                                    <option value="">विद्यार्थी छान्नुहोस्</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ $meal->student_id == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->room->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">खानाको प्रकार <span class="text-danger">*</span></label>
                                <select name="meal_type" class="form-control @error('meal_type') is-invalid @enderror" required>
                                    <option value="">प्रकार छान्नुहोस्</option>
                                    <option value="breakfast" {{ $meal->meal_type == 'breakfast' ? 'selected' : '' }}>नास्ता</option>
                                    <option value="lunch" {{ $meal->meal_type == 'lunch' ? 'selected' : '' }}>दिउँसोको खाना</option>
                                    <option value="dinner" {{ $meal->meal_type == 'dinner' ? 'selected' : '' }}>रात्रिको खाना</option>
                                </select>
                                @error('meal_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">मिति <span class="text-danger">*</span></label>
                                <input type="date" name="meal_date" class="form-control @error('meal_date') is-invalid @enderror" 
                                       value="{{ old('meal_date', $meal->meal_date->format('Y-m-d')) }}" required>
                                @error('meal_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">अवस्था <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ $meal->status == 'pending' ? 'selected' : '' }}>पेन्डिङ</option>
                                    <option value="served" {{ $meal->status == 'served' ? 'selected' : '' }}>सर्व गरियो</option>
                                    <option value="missed" {{ $meal->status == 'missed' ? 'selected' : '' }}>छुट्यो</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">टिप्पणी</label>
                                <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" 
                                          rows="3" placeholder="वैकल्पिक टिप्पणी...">{{ old('remarks', $meal->remarks) }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> अपडेट गर्नुहोस्
                            </button>
                            <a href="{{ route('owner.meals.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> रद्द गर्नुहोस्
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection