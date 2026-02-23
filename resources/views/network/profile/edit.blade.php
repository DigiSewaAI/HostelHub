@extends('layouts.owner')

@section('title', 'प्रोफाइल सम्पादन')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('network.profile') }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>मालिक प्रोफाइल</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('network.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">व्यवसायको नाम</label>
                        <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror" 
                               value="{{ old('business_name', $profile->business_name) }}">
                        @error('business_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">फोन नम्बर</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $profile->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">शहर</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                               value="{{ old('city', $profile->city) }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">बायोडाटा</label>
                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">होस्टल क्षमता</label>
                        <input type="number" name="hostel_size" class="form-control @error('hostel_size') is-invalid @enderror" 
                               value="{{ old('hostel_size', $profile->hostel_size) }}">
                        @error('hostel_size')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">मूल्य श्रेणी</label>
                        <select name="pricing_category" class="form-control @error('pricing_category') is-invalid @enderror">
                            <option value="">चयन गर्नुहोस्</option>
                            <option value="budget" {{ old('pricing_category', $profile->pricing_category) == 'budget' ? 'selected' : '' }}>बजेट</option>
                            <option value="mid" {{ old('pricing_category', $profile->pricing_category) == 'mid' ? 'selected' : '' }}>मध्यम</option>
                            <option value="premium" {{ old('pricing_category', $profile->pricing_category) == 'premium' ? 'selected' : '' }}>प्रिमियम</option>
                        </select>
                        @error('pricing_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">सुरक्षित गर्नुहोस्</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection