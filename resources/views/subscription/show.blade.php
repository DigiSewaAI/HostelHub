@extends('layouts.owner')

@section('title', 'Subscription Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Subscription Management</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Subscription</h6>
                </div>
                <div class="card-body">
                    @if($currentPlan)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Plan Name</label>
                                    <p>{{ $currentPlan->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Price</label>
                                    <p>रु. {{ number_format($currentPlan->price) }} / month</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p>
                                        <span class="badge {{ $organization->subscription->status === 'active' ? 'bg-success' : ($organization->subscription->status === 'trial' ? 'bg-info' : 'bg-warning') }}">
                                            {{ ucfirst($organization->subscription->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Trial Ends At</label>
                                    <p>{{ $organization->subscription->trial_ends_at ? $organization->subscription->trial_ends_at->format('Y-m-d') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No active subscription found. 
                            <a href="{{ route('pricing') }}" class="alert-link">Choose a plan</a> to get started.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($availablePlans->count() > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Available Plans</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($availablePlans as $plan)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header text-center">
                                    <h5 class="card-title">{{ $plan->name }}</h5>
                                    <h4 class="text-primary">रु. {{ number_format($plan->price) }}</h4>
                                    <small class="text-muted">per month</small>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $plan->description }}</p>
                                    <form action="{{ route('subscription.upgrade') }}" method="POST" class="plan-form">
                                        @csrf
                                        <input type="hidden" name="plan" value="{{ $plan->slug }}">
                                        <button type="submit" class="btn btn-primary w-100">Upgrade to {{ $plan->name }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planForms = document.querySelectorAll('.plan-form');
        
        planForms.forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                
                // Show loading state
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                
                try {
                    const formData = new FormData(this);
                    
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.success) {
                        // Show success message and reload
                        alert(data.message || 'Plan upgraded successfully');
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Unknown error');
                    }
                } catch (error) {
                    alert('Error: ' + error.message);
                    button.disabled = false;
                    button.textContent = originalText;
                }
            });
        });
    });
</script>
@endsection