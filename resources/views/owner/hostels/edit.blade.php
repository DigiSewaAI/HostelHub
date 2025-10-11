@extends('layouts.owner')

@section('title', $hostel->name . ' - सम्पादन')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $hostel->name }} - सम्पादन</h5>
                    <a href="{{ route('owner.hostels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> होस्टल सूची
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ url('/owner/hostels/' . $hostel->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">होस्टलको नाम *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $hostel->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_person">सम्पर्क व्यक्ति *</label>
                                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                           id="contact_person" name="contact_person" 
                                           value="{{ old('contact_person', $hostel->contact_person) }}" required>
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">सम्पर्क फोन *</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" name="contact_phone" 
                                           value="{{ old('contact_phone', $hostel->contact_phone) }}" required>
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_email">इमेल</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" 
                                           value="{{ old('contact_email', $hostel->contact_email) }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address">ठेगाना *</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                           id="address" name="address" value="{{ old('address', $hostel->address) }}" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">शहर *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city', $hostel->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">स्थिति</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ $hostel->status == 'active' ? 'selected' : '' }}>सक्रिय</option>
                                        <option value="inactive" {{ $hostel->status == 'inactive' ? 'selected' : '' }}>निष्क्रिय</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">विवरण</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $hostel->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="facilities">सुविधाहरू (comma separated)</label>
                                    <input type="text" class="form-control @error('facilities') is-invalid @enderror" 
                                           id="facilities" name="facilities" 
                                           value="{{ old('facilities', $hostel->facilities ? implode(', ', json_decode($hostel->facilities)) : '') }}"
                                           placeholder="उदाहरण: WiFi, पानी, बिजुली">
                                    @error('facilities')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="monthly_rent">मासिक भाडा *</label>
                                    <input type="number" class="form-control @error('monthly_rent') is-invalid @enderror" 
                                           id="monthly_rent" name="monthly_rent" 
                                           value="{{ old('monthly_rent', $hostel->monthly_rent) }}" required min="0">
                                    @error('monthly_rent')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="security_deposit">सुरक्षा जमानत *</label>
                                    <input type="number" class="form-control @error('security_deposit') is-invalid @enderror" 
                                           id="security_deposit" name="security_deposit" 
                                           value="{{ old('security_deposit', $hostel->security_deposit) }}" required min="0">
                                    @error('security_deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image">होस्टलको तस्बिर</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($hostel->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $hostel->image) }}" alt="{{ $hostel->name }}" 
                                                 class="img-thumbnail" style="max-height: 200px;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                                                <label class="form-check-label" for="remove_image">
                                                    तस्बिर हटाउनुहोस्
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> अपडेट गर्नुहोस्
                                </button>
                                <a href="{{ route('owner.hostels.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> रद्द गर्नुहोस्
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