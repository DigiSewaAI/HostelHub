<div class="text-center mb-4">
    <h4 class="font-weight-bold">१. आफ्नो होस्टल संस्थाको प्रोफाइल पूरा गर्नुहोस्</h4>
    <p>आफ्नो होस्टल संस्थाको बारेमा आधारभूत जानकारी प्रदान गर्नुहोस्</p>
</div>

<form method="POST" action="{{ route('onboarding.store', 1) }}" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">होस्टल/संस्थाको नाम</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="{{ $organization->name }}" required>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="city" class="form-label">शहर</label>
            <input type="text" name="city" id="city" class="form-control" 
                   value="{{ $organization->settings['city'] ?? '' }}" required>
        </div>
        
        <div class="col-md-12 mb-3">
            <label for="address" class="form-label">ठेगाना</label>
            <textarea name="address" id="address" class="form-control" rows="2" required>{{ $organization->settings['address'] ?? '' }}</textarea>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="contact_phone" class="form-label">सम्पर्क फोन नम्बर</label>
            <input type="tel" name="contact_phone" id="contact_phone" class="form-control" 
                   value="{{ $organization->settings['contact_phone'] ?? '' }}" required>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="logo" class="form-label">लोगो (वैकल्पिक)</label>
            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
        </div>
    </div>
    
    <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-primary px-4">
            सुरक्षित गर्नुहोस् र अगाडि बढ्नुहोस्
        </button>
        <button type="button" class="btn btn-outline-secondary px-4" 
                onclick="window.location.href='{{ route('onboarding.skip', 1) }}'">
            यो चरण छोड्नुहोस्
        </button>
    </div>
</form>