@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">होस्टलहबमा साइन अप गर्नुहोस्</h3>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                        
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        
                        <div class="form-group mb-3">
                            <label for="organization_name" class="form-label">होस्टल/संस्थाको नाम</label>
                            <input type="text" name="organization_name" id="organization_name" class="form-control" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="owner_name" class="form-label">तपाईंको नाम</label>
                            <input type="text" name="owner_name" id="owner_name" class="form-control" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">इमेल ठेगाना</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">पासवर्ड</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">पासवर्ड पुष्टि गर्नुहोस्</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                ७ दिन निःशुल्क परीक्षण सुरु गर्नुहोस्
                            </button>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <p>पहिले नै खाता छ? <a href="{{ route('login') }}">लगइन गर्नुहोस्</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection