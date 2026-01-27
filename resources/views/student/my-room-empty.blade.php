@extends('layouts.student')

@section('title', 'कोठा असाइन भएको छैन')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8 col-md-10">
            <!-- Empty State Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center py-5">
                    <!-- Icon -->
                    <div class="empty-state-icon mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-door-closed fa-3x text-muted"></i>
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h2 class="h3 text-gray-800 mb-3">
                        तपाईंलाई कोठा असाइन भएको छैन
                    </h2>
                    
                    <!-- Message -->
                    <p class="text-muted mb-4">
                        तपाईंलाई अहिले सम्म कुनै कोठा असाइन गरिएको छैन। 
                        होस्टेल प्रशासनले तपाईंलाई कोठा असाइन गरेपछि यो पेजमा तपाईंको कोठाको विवरण देखिनेछ।
                    </p>
                    
                    <!-- Additional Info -->
                    <div class="alert alert-info mb-4 text-start">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">के गर्नुपर्छ?</h5>
                                <ul class="mb-0 ps-3">
                                    <li>होस्टेल प्रशासनसँग सम्पर्क गर्नुहोस्</li>
                                    <li>कोठा आवेदन गर्नुहोस् (यदि गरेको छैन भने)</li>
                                    <li>आवेदनको स्थिति जाँच गर्नुहोस्</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary px-4">
                            <i class="fas fa-home me-2"></i> ड्यासबोर्डमा फर्कनुहोस्
                        </a>
                        
                        <button class="btn btn-outline-primary px-4" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="fas fa-phone-alt me-2"></i> सम्पर्क गर्नुहोस्
                        </button>
                        
                        <a href="{{ route('student.room-application') ?? '#' }}" class="btn btn-success px-4">
                            <i class="fas fa-edit me-2"></i> कोठा आवेदन गर्नुहोस्
                        </a>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="mt-5 pt-4 border-top">
                        <h6 class="text-muted mb-3">होस्टेल सम्पर्क जानकारी</h6>
                        <div class="row justify-content-center">
                            @if($student && $student->hostel)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-building text-primary me-2"></i>
                                        <span>{{ $student->hostel->name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-phone text-primary me-2"></i>
                                        <span>{{ $student->hostel->phone ?? 'उपलब्ध छैन' }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="col-12">
                                    <p class="text-muted">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        होस्टेल जानकारी उपलब्ध छैन
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- What to expect section -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-clock me-2 text-primary"></i>कोठा असाइन हुने प्रक्रिया
                    </h5>
                    <div class="timeline">
                        <div class="timeline-step">
                            <div class="timeline-icon bg-primary">
                                <i class="fas fa-1 text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>आवेदन दिनुहोस्</h6>
                                <p class="text-muted small">कोठाको लागि आवेदन फारम भर्नुहोस्</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-icon bg-secondary">
                                <i class="fas fa-2 text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>प्रशासनिक समीक्षा</h6>
                                <p class="text-muted small">होस्टेल प्रशासनले आवेदन समीक्षा गर्ने</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-icon bg-info">
                                <i class="fas fa-3 text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>कोठा असाइन</h6>
                                <p class="text-muted small">उपलब्ध कोठा अनुसार असाइन गरिने</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-icon bg-success">
                                <i class="fas fa-4 text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>भुक्तानी र भर्ना</h6>
                                <p class="text-muted small">भुक्तानी गरेपछि कोठामा भर्ना हुने</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-headset me-2"></i>होस्टेल सम्पर्क
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($student && $student->hostel)
                    <div class="contact-info">
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon bg-primary rounded-circle p-3 me-3">
                                <i class="fas fa-building fa-lg text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $student->hostel->name }}</h6>
                                <small class="text-muted">होस्टेल</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">फोन नम्बर</small>
                                        <strong>{{ $student->hostel->phone ?? 'उपलब्ध छैन' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">इमेल</small>
                                        <strong>{{ $student->hostel->email ?? 'उपलब्ध छैन' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-map-marker-alt text-primary me-2 mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">ठेगाना</small>
                                        <strong>{{ $student->hostel->address ?? 'उपलब्ध छैन' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                        <p class="text-muted">होस्टेल जानकारी उपलब्ध छैन</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">बन्द गर्नुहोस्</button>
                <a href="tel:{{ $student->hostel->phone ?? '' }}" class="btn btn-primary">
                    <i class="fas fa-phone me-1"></i> कल गर्नुहोस्
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Styles for the empty state -->
<style>
    .empty-state-icon {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-step {
        position: relative;
        padding-bottom: 20px;
        padding-left: 30px;
    }
    
    .timeline-step:not(:last-child):before {
        content: '';
        position: absolute;
        left: 15px;
        top: 30px;
        bottom: -20px;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-icon {
        position: absolute;
        left: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .timeline-content {
        margin-left: 10px;
    }
    
    .contact-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection