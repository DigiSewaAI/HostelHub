@extends('layouts.owner')

@section('title', 'नयाँ होस्टल थप्नुहोस्')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">नयाँ होस्टल थप्नुहोस्</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.hostels.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">होस्टलको नाम <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">ठेगाना <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">शहर <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                           id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">सम्पर्क व्यक्ति <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                           id="contact_person" name="contact_person" value="{{ old('contact_person') }}" required>
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">सम्पर्क फोन <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                           id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" required>
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">सम्पर्क इमेल</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                           id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_rooms" class="form-label">कुल कोठाहरू <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('total_rooms') is-invalid @enderror"
                                           id="total_rooms" name="total_rooms" value="{{ old('total_rooms', 1) }}" min="1" required>
                                    @error('total_rooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="available_rooms" class="form-label">उपलब्ध कोठाहरू <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('available_rooms') is-invalid @enderror"
                                           id="available_rooms" name="available_rooms" value="{{ old('available_rooms', 1) }}" min="0" required>
                                    @error('available_rooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">विवरण</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="facilities" class="form-label">सुविधाहरू (कमा द्वारा छुट्याइएको)</label>
                            <textarea class="form-control @error('facilities') is-invalid @enderror"
                                      id="facilities" name="facilities" rows="2" placeholder="WiFi, Laundry, Hot Water, CCTV">{{ old('facilities') }}</textarea>
                            @error('facilities')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">होस्टल तस्बिर</label>
                            <input class="form-control @error('image') is-invalid @enderror"
                                   type="file" id="image" name="image" accept="image/*">
                            <div class="form-text">सिफारिश गरिएको आकार: 800x600 pixels. अधिकतम: 2MB</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('owner.hostels.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> रद्द गर्नुहोस्
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> होस्टल थप्नुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection