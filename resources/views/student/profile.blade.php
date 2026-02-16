@extends('layouts.student')

@section('content')
<div class="container">
    <h1>मेरो प्रोफाइल</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Read-only View -->
<div id="profileView">
    {{-- ✅ फोटो बायाँपट्टि, साइज आधा --}}
    @if($student->image)
        <div class="mb-4">
            <img src="{{ asset('storage/students/'.$student->image) }}" 
                 alt="Profile Photo" 
                 class="w-44 h-48 object-cover rounded-lg shadow-md border-2 border-gray-200">
        </div>
    @endif

        <div class="row">
            <div class="col-md-6">
                <h4>व्यक्तिगत जानकारी</h4>
                <div class="mb-3">
                    <label class="form-label"><strong>नाम:</strong></label>
                    <p class="ps-2">{{ $student->user->name }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>इमेल:</strong></label>
                    <p class="ps-2">{{ $student->user->email }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>फोन नम्बर:</strong></label>
                    <p class="ps-2" id="currentPhone">{{ $student->phone }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>ठेगाना:</strong></label>
                    <p class="ps-2" id="currentAddress">{{ $student->address }}</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <h4>अभिभावकको जानकारी</h4>
                <div class="mb-3">
                    <label class="form-label"><strong>अभिभावकको नाम:</strong></label>
                    <p class="ps-2" id="currentGuardianName">{{ $student->guardian_name }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>अभिभावकको फोन:</strong></label>
                    <p class="ps-2" id="currentGuardianPhone">{{ $student->guardian_phone }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>अभिभावकको नाता:</strong></label>
                    <p class="ps-2" id="currentGuardianRelation">{{ $student->guardian_relation }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>अभिभावकको ठेगाना:</strong></label>
                    <p class="ps-2" id="currentGuardianAddress">{{ $student->guardian_address }}</p>
                </div>
            </div>
        </div>
        
        <button type="button" class="btn btn-primary" onclick="showEditForm()">प्रोफाइल सम्पादन गर्नुहोस्</button>
    </div>

    <!-- Edit Form (Hidden by default) -->
    <form action="{{ route('student.profile.update') }}" method="POST" id="editForm" style="display: none;">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <h4>व्यक्तिगत जानकारी</h4>
                
                <div class="mb-3">
                    <label for="phone" class="form-label">फोन नम्बर</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" 
                           value="{{ old('phone', $student->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">ठेगाना</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3" required>{{ old('address', $student->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <h4>अभिभावकको जानकारी</h4>
                <div class="mb-3">
                    <label for="guardian_name" class="form-label">अभिभावकको नाम</label>
                    <input type="text" class="form-control @error('guardian_name') is-invalid @enderror" name="guardian_name" 
                           value="{{ old('guardian_name', $student->guardian_name) }}" required>
                    @error('guardian_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="guardian_phone" class="form-label">अभिभावकको फोन</label>
                    <input type="text" class="form-control @error('guardian_phone') is-invalid @enderror" name="guardian_phone" 
                           value="{{ old('guardian_phone', $student->guardian_phone) }}" required>
                    @error('guardian_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="guardian_relation" class="form-label">अभिभावकको नाता</label>
                    <input type="text" class="form-control @error('guardian_relation') is-invalid @enderror" name="guardian_relation" 
                           value="{{ old('guardian_relation', $student->guardian_relation) }}" required>
                    @error('guardian_relation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="guardian_address" class="form-label">अभिभावकको ठेगाना</label>
                    <textarea class="form-control @error('guardian_address') is-invalid @enderror" name="guardian_address" rows="3" required>{{ old('guardian_address', $student->guardian_address) }}</textarea>
                    @error('guardian_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-success">अपडेट गर्नुहोस्</button>
            <button type="button" class="btn btn-secondary" onclick="hideEditForm()">रद्द गर्नुहोस्</button>
        </div>
    </form>
    
    <div class="mt-5">
        <h4>अन्य जानकारी</h4>
        <div class="row">
            <div class="col-md-4">
                <p><strong>कलेज:</strong> {{ $student->college ?? 'उपलब्ध छैन' }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>कोठा नम्बर:</strong> {{ $student->room->room_number ?? 'तोकिएको छैन' }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>स्थिति:</strong> 
                    <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'warning' }}">
                        {{ $student->status == 'active' ? 'सक्रिय' : 'निष्क्रिय' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function showEditForm() {
    document.getElementById('profileView').style.display = 'none';
    document.getElementById('editForm').style.display = 'block';
}

function hideEditForm() {
    document.getElementById('editForm').style.display = 'none';
    document.getElementById('profileView').style.display = 'block';
}
</script>
@endsection