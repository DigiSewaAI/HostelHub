@extends('layouts.admin')

@section('title', 'खाना थप्नुहोस्')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0"><i class="fas fa-utensils me-2"></i> नयाँ खाना थप्नुहोस्</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.meals.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">विद्यार्थी</label>
                                <select name="student_id" class="form-control" required>
                                    <option value="">विद्यार्थी छान्नुहोस्</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->name }} ({{ $student->hostel->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">होस्टल</label>
                                <select name="hostel_id" class="form-control" required>
                                    <option value="">होस्टल छान्नुहोस्</option>
                                    @foreach($hostels as $hostel)
                                        <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">खानाको प्रकार</label>
                                <select name="meal_type" class="form-control" required>
                                    <option value="breakfast">बिहानको खाना</option>
                                    <option value="lunch">दिउँसोको खाना</option>
                                    <option value="dinner">बेलुकाको खाना</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">मिति</label>
                                <input type="date" name="date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">अवस्था</label>
                                <select name="status" class="form-control">
                                    <option value="present">उपस्थित</option>
                                    <option value="absent">अनुपस्थित</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> सुरक्षित गर्नुहोस्
                            </button>
                            <a href="{{ route('admin.meals.index') }}" class="btn btn-secondary">
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