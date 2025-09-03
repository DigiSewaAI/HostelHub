@extends('layouts.student')

@section('content')
<div class="container">
    <h1>My Profile</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('student.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <h4>Personal Information</h4>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control-plaintext" 
                           value="{{ $student->user->name }}" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control-plaintext" 
                           value="{{ $student->user->email }}" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" 
                           value="{{ old('phone', $student->phone) }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" name="address" rows="3" required>
                        {{ old('address', $student->address) }}
                    </textarea>
                </div>
            </div>
            
            <div class="col-md-6">
                <h4>Guardian Information</h4>
                <div class="mb-3">
                    <label for="guardian_name" class="form-label">Guardian Name</label>
                    <input type="text" class="form-control" name="guardian_name" 
                           value="{{ old('guardian_name', $student->guardian_name) }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="guardian_phone" class="form-label">Guardian Phone</label>
                    <input type="text" class="form-control" name="guardian_phone" 
                           value="{{ old('guardian_phone', $student->guardian_phone) }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="guardian_relation" class="form-label">Relation</label>
                    <input type="text" class="form-control" name="guardian_relation" 
                           value="{{ old('guardian_relation', $student->guardian_relation) }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="guardian_address" class="form-label">Guardian Address</label>
                    <textarea class="form-control" name="guardian_address" rows="3" required>
                        {{ old('guardian_address', $student->guardian_address) }}
                    </textarea>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>
    
    <div class="mt-5">
        <h4>Other Information</h4>
        <div class="row">
            <div class="col-md-4">
                <p><strong>College:</strong> {{ $student->college }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>Room:</strong> {{ $student->room->room_number ?? 'Not Assigned' }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>Status:</strong> <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'warning' }}">
                    {{ ucfirst($student->status) }}
                </span></p>
            </div>
        </div>
    </div>
</div>
@endsection