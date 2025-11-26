@extends('layouts.frontend')

@section('page-title', 'बुकिंग सफल - HostelHub')
@section('page-header', 'बुकिंग अनुरोध सफल')
@section('page-description', 'तपाईंको बुकिंग अनुरोध सफलतापूर्वक पेश गरियो')

@section('content')
<div class="success-container">
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 class="success-title">धन्यवाद!</h1>
        <p class="success-message">
            @if(isset($booking))
                तपाईंको बुकिंग सफलतापूर्वक पेश गरियो
            @elseif(isset($bookingRequest))
                तपाईंको बुकिंग अनुरोध सफलतापूर्वक पेश गरियो
            @endif
        </p>
        
        <div class="booking-details">
            <div class="detail-card">
                <h3 class="detail-title">बुकिंग विवरण</h3>
                <div class="detail-list">
                    @if(isset($booking))
                        <!-- NEW SYSTEM - Booking Model -->
                        <div class="detail-item">
                            <span class="detail-label">बुकिंग नम्बर:</span>
                            <span class="detail-value">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">होस्टल:</span>
                            <span class="detail-value">{{ $booking->hostel->name ?? 'N/A' }}</span>
                        </div>
                        <!-- 🚨 FIXED: Room details for gallery bookings -->
                        @if($booking->room)
                        <div class="detail-item">
                            <span class="detail-label">कोठा:</span>
                            <span class="detail-value">
                                {{ $booking->room->nepali_type ?? $booking->room->type }} - कोठा {{ $booking->room->room_number }}
                            </span>
                        </div>
                        @endif
                        <div class="detail-item">
                            <span class="detail-label">नाम:</span>
                            <span class="detail-value">
                                {{ $booking->guest_name ?? ($booking->user->name ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">इमेल:</span>
                            <span class="detail-value">{{ $booking->guest_email ?? ($booking->user->email ?? 'N/A') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">फोन:</span>
                            <span class="detail-value">{{ $booking->guest_phone ?? ($booking->user->phone ?? 'N/A') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">चेक-इन:</span>
                            <span class="detail-value">{{ $booking->check_in_date->format('Y-m-d') }}</span>
                        </div>
                        <!-- 🚨 UPDATED: Check-out date with null handling -->
                        <div class="detail-item">
                            <span class="detail-label">चेक-आउट:</span>
                            <span class="detail-value">
                                {{ $booking->check_out_date ? $booking->check_out_date->format('Y-m-d') : 'N/A' }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">रकम:</span>
                            <span class="detail-value">रु {{ number_format($booking->amount, 2) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">आपतकालीन सम्पर्क:</span>
                            <span class="detail-value">{{ $booking->emergency_contact ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">स्थिति:</span>
                            <span class="detail-value badge {{ $booking->status === 'pending' ? 'bg-warning' : 'bg-success' }}">
                                {{ $booking->status === 'pending' ? 'पेन्डिङ' : 'स्वीकृत' }}
                            </span>
                        </div>
                        @if($booking->notes)
                        <div class="detail-item">
                            <span class="detail-label">नोटहरू:</span>
                            <span class="detail-value">{{ $booking->notes }}</span>
                        </div>
                        @endif
                    @elseif(isset($bookingRequest))
                        <!-- OLD SYSTEM - BookingRequest Model -->
                        <div class="detail-item">
                            <span class="detail-label">अनुरोध नम्बर:</span>
                            <span class="detail-value">#{{ str_pad($bookingRequest->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">होस्टल:</span>
                            <span class="detail-value">{{ $bookingRequest->hostel->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">नाम:</span>
                            <span class="detail-value">{{ $bookingRequest->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">फोन:</span>
                            <span class="detail-value">{{ $bookingRequest->phone }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">चेक-इन:</span>
                            <span class="detail-value">{{ $bookingRequest->check_in_date->format('Y-m-d') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">कोठा प्रकार:</span>
                            <span class="detail-value">{{ (new App\Models\Room())->getNepaliTypeAttribute($bookingRequest->room_type) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">स्थिति:</span>
                            <span class="detail-value badge {{ $bookingRequest->status_badge_class }}">
                                {{ $bookingRequest->status_nepali }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="next-steps">
            <h3 class="steps-title">अर्को चरणहरू</h3>
            <ul class="steps-list">
                <li class="step-item">
                    <i class="fas fa-phone step-icon"></i>
                    <div class="step-content">
                        <strong>सम्पर्क प्रतीक्षा गर्नुहोस्</strong>
                        <p>होस्टल प्रबन्धकले २४ घण्टाभित्रमा तपाईंसँग सम्पर्क गर्नेछन्</p>
                    </div>
                </li>
                <li class="step-item">
                    <i class="fas fa-file-alt step-icon"></i>
                    <div class="step-content">
                        <strong>कागजात तयार गर्नुहोस्</strong>
                        <p>आवश्यक कागजातहरू (पहिचान, फोटो, आदि) तयार गर्नुहोस्</p>
                    </div>
                </li>
                <li class="step-item">
                    <i class="fas fa-home step-icon"></i>
                    <div class="step-content">
                        <strong>चेक-इन गर्नुहोस्</strong>
                        <p>निर्धारित मितिमा होस्टलमा चेक-इन गर्नुहोस्</p>
                    </div>
                </li>
            </ul>
        </div>
        
        <div class="action-buttons">
            @if(isset($booking) && $booking->hostel)
                <!-- 🚨 FIXED: Use the correct hostel slug from booking -->
                <a href="{{ route('hostels.show', $booking->hostel->slug) }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> होस्टल पृष्ठमा फर्कनुहोस्
                </a>
                <!-- 🚨 FIXED: Gallery booking button with correct hostel -->
                <a href="{{ route('hostel.book.from.gallery', ['slug' => $booking->hostel->slug]) }}" class="btn btn-outline">
                    <i class="fas fa-plus"></i> फेरी बुक गर्नुहोस्
                </a>
            @elseif(isset($bookingRequest))
                <a href="{{ route('hostels.show', $bookingRequest->hostel->slug) }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> होस्टल पृष्ठमा फर्कनुहोस्
                </a>
            @endif
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> गृह पृष्ठमा जानुहोस्
            </a>
        </div>
        
        <div class="contact-info">
            <h4 class="contact-title">तत्काल सम्पर्क आवश्यक छ?</h4>
            <p class="contact-details">
                @if(isset($booking) && $booking->hostel)
                    <i class="fas fa-phone"></i> {{ $booking->hostel->contact_phone ?? 'N/A' }} | 
                    <i class="fas fa-envelope"></i> {{ $booking->hostel->contact_email ?? 'N/A' }}
                @elseif(isset($bookingRequest))
                    <i class="fas fa-phone"></i> {{ $bookingRequest->hostel->contact_phone }} | 
                    <i class="fas fa-envelope"></i> {{ $bookingRequest->hostel->contact_email }}
                @endif
            </p>
        </div>
    </div>
</div>

<style>
.success-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.success-card {
    background: white;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.success-icon {
    font-size: 4rem;
    color: #28a745;
    margin-bottom: 1rem;
}

.success-title {
    color: #001F5B;
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.success-message {
    color: #6c757d;
    font-size: 1.2rem;
    margin-bottom: 2rem;
}

.booking-details {
    margin: 2rem 0;
}

.detail-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: left;
}

.detail-title {
    color: #001F5B;
    font-size: 1.3rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #001F5B;
}

.detail-list {
    display: grid;
    gap: 0.75rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #495057;
}

.detail-value {
    color: #001F5B;
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
}

.bg-warning {
    background: #fff3cd;
    color: #856404;
}

.bg-success {
    background: #d1edff;
    color: #0c5460;
}

.next-steps {
    margin: 2rem 0;
    text-align: left;
}

.steps-title {
    color: #001F5B;
    font-size: 1.3rem;
    margin-bottom: 1rem;
    text-align: center;
}

.steps-list {
    list-style: none;
    padding: 0;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #001F5B;
}

.step-icon {
    font-size: 1.5rem;
    color: #001F5B;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.step-content strong {
    color: #001F5B;
    display: block;
    margin-bottom: 0.25rem;
}

.step-content p {
    color: #6c757d;
    margin: 0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin: 2rem 0;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.btn-primary {
    background: #001F5B;
    color: white;
}

.btn-primary:hover {
    background: #001338;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-outline {
    background: transparent;
    color: #001F5B;
    border: 2px solid #001F5B;
}

.btn-outline:hover {
    background: #001F5B;
    color: white;
}

.contact-info {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

.contact-title {
    color: #001F5B;
    margin-bottom: 0.5rem;
}

.contact-details {
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .success-card {
        padding: 2rem 1rem;
    }
    
    .success-title {
        font-size: 2rem;
    }
    
    .detail-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .contact-details {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
@endsection