@extends('layouts.public')

@push('head')
<style>
    :root {
        --theme-color: {{ $hostel->theme_color ?? '#3b82f6' }};
        --primary-color: {{ $hostel->theme_color ?? '#3b82f6' }};
    }
    
    .modern-theme {
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    }
    
    .hero-gradient {
        background: linear-gradient(135deg, var(--theme-color) 0%, #7c3aed 100%);
    }
    
    /* Perfect Logo Size */
    .logo-container {
        width: 70px;
        height: 70px;
        border-radius: 14px;
        overflow: hidden;
        border: 3px solid white;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        background: white;
        flex-shrink: 0;
    }
    
    .logo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .verified-badge {
        position: absolute;
        bottom: -4px;
        right: -4px;
        width: 18px;
        height: 18px;
        background: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }
    
    .verified-badge i {
        font-size: 10px;
    }
    
    /* Better Header Layout */
    .header-content {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .hostel-info {
        flex: 1;
    }
    
    .hostel-name {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.2;
    }
    
    .hostel-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }
    
    .meta-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }
    
    .availability-badge {
        background: rgba(34, 197, 94, 0.9);
        color: white;
        font-weight: 600;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 16px 12px;
        text-align: center;
        color: white;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
    }
    
    .stat-number {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .stat-label {
        font-size: 12px;
        opacity: 0.9;
    }
    
    /* Social Media & CTA */
    .header-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
        align-items: flex-end;
    }
    
    .social-media-compact {
        display: flex;
        gap: 6px;
    }
    
    .social-icon-compact {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 14px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .social-icon-compact:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.2);
    }
    
    .phone-cta {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        padding: 10px 16px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .phone-cta:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }
    
    /* Main Content Sections */
    .section-title {
        position: relative;
        padding-bottom: 12px;
        margin-bottom: 20px;
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(to right, var(--theme-color), #7c3aed);
        border-radius: 2px;
    }
    
    .content-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
        margin-bottom: 24px;
    }
    
    /* Facilities Grid */
    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }
    
    .facility-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .facility-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: var(--theme-color);
    }
    
    .facility-icon {
        width: 40px;
        height: 40px;
        background: var(--theme-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        flex-shrink: 0;
    }
    
    /* Reviews */
    .review-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
        margin-bottom: 16px;
        position: relative;
    }
    
    .review-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, var(--theme-color), #7c3aed);
        border-radius: 0 2px 2px 0;
    }
    
    .review-header {
        display: flex;
        justify-content: between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    
    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }
    
    .reviewer-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--theme-color), #7c3aed);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }
    
    .reviewer-details h4 {
        font-weight: 600;
        margin-bottom: 4px;
        color: #1f2937;
    }
    
    .review-date {
        color: #6b7280;
        font-size: 13px;
    }
    
    /* Sidebar */
    .sidebar-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 10px;
        border-left: 3px solid var(--theme-color);
    }
    
    .contact-icon {
        width: 36px;
        height: 36px;
        background: var(--theme-color);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .contact-details {
        flex: 1;
    }
    
    .contact-label {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 2px;
    }
    
    .contact-value {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
    }
    
    /* Trust Badges */
    .trust-badges {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .trust-badge {
        text-align: center;
        padding: 16px 12px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .trust-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .trust-icon {
        width: 40px;
        height: 40px;
        background: var(--theme-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        margin: 0 auto 8px;
    }
    
    /* Floating Actions */
    .floating-actions {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .floating-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .floating-btn.whatsapp {
        background: #25d366;
    }
    
    .floating-btn.phone {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .floating-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .header-actions {
            width: 100%;
            align-items: stretch;
        }
        
        .phone-cta {
            justify-content: center;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .facilities-grid {
            grid-template-columns: 1fr;
        }
        
        .trust-badges {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="modern-theme">
    <!-- Perfect Hero Section -->
    <section class="hero-gradient text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Row -->
            <div class="header-content">
                <!-- Logo and Basic Info -->
                <div class="flex items-start gap-4 flex-1">
                    <div class="relative">
                        <div class="logo-container">
                            @if($logo)
                                <img src="{{ $logo }}" alt="{{ $hostel->name }}">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <i class="fas fa-building text-white text-xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="verified-badge">
                            <i class="fas fa-check text-white"></i>
                        </div>
                    </div>
                    
                    <div class="hostel-info">
                        <h1 class="hostel-name nepali">{{ $hostel->name }}</h1>
                        
                        <div class="hostel-meta">
                            <div class="meta-badge">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                                <span class="nepali">{{ $hostel->city ?? 'काठमाडौं' }}</span>
                            </div>
                            
                            @if($reviewCount > 0 && $avgRating > 0)
                                <div class="meta-badge">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-yellow-300' : 'text-white/60' }} text-xs"></i>
                                        @endfor
                                    </div>
                                    <span class="font-bold">{{ number_format($avgRating, 1) }}</span>
                                    <span class="nepali">({{ $reviewCount }})</span>
                                </div>
                            @endif
                            
                            @if($hostel->available_rooms > 0)
                                <div class="meta-badge availability-badge">
                                    <i class="fas fa-bed text-xs"></i>
                                    <span class="nepali">{{ $hostel->available_rooms }} कोठा उपलब्ध</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Social Media and Phone -->
                <div class="header-actions">
                    <div class="text-right">
                        <p class="text-white/80 nepali text-sm mb-2">हामीलाई फलो गर्नुहोस्</p>
                        <div class="social-media-compact">
                            @if($hostel->facebook_url)
                                <a href="{{ $hostel->facebook_url }}" target="_blank" class="social-icon-compact facebook-bg">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            
                            @if($hostel->instagram_url)
                                <a href="{{ $hostel->instagram_url }}" target="_blank" class="social-icon-compact instagram-bg">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                            
                            @if($hostel->whatsapp_number)
                                <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="social-icon-compact whatsapp-bg">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    @if($hostel->contact_phone)
                        <a href="tel:{{ $hostel->contact_phone }}" class="phone-cta nepali">
                            <i class="fas fa-phone text-green-300"></i>
                            अहिले फोन गर्नुहोस्
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $hostel->total_rooms ?? 0 }}</div>
                    <div class="stat-label nepali">कुल कोठा</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $hostel->available_rooms ?? 0 }}</div>
                    <div class="stat-label nepali">उपलब्ध कोठा</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $studentCount }}</div>
                    <div class="stat-label nepali">विद्यार्थी</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $reviewCount }}</div>
                    <div class="stat-label nepali">समीक्षाहरू</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Content -->
            <div class="lg:col-span-2">
                <!-- About Section -->
                <section class="content-card">
                    <h2 class="section-title nepali">हाम्रो बारेमा</h2>
                    @if($hostel->description)
                        <p class="text-gray-700 leading-relaxed nepali whitespace-pre-line">
                            {{ $hostel->description }}
                        </p>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-file-alt text-4xl mb-3 opacity-30"></i>
                            <p class="nepali italic">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
                        </div>
                    @endif
                </section>

                <!-- Facilities Section -->
                @if(!empty($facilities) && count($facilities) > 0)
                    <section class="content-card">
                        <h2 class="section-title nepali">हाम्रा सुविधाहरू</h2>
                        <div class="facilities-grid">
                            @foreach($facilities as $facility)
                                @if(trim($facility))
                                    <div class="facility-item">
                                        <div class="facility-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span class="nepali font-medium text-gray-800">{{ trim($facility) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Gallery Section -->
                <section class="content-card">
                    <h2 class="section-title nepali">ग्यालेरी</h2>
                    @include('public.hostels.partials.gallery')
                </section>

                <!-- Reviews Section -->
                <section class="content-card" id="reviews">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="section-title nepali">विद्यार्थी समीक्षाहरू</h2>
                        <div class="bg-purple-100 text-purple-800 px-4 py-2 rounded-full">
                            <span class="nepali font-semibold">{{ $reviewCount }} समीक्षाहरू</span>
                        </div>
                    </div>

                    @if($reviewCount > 0)
                        <div class="space-y-4">
                            @foreach($reviews as $review)
                                <div class="review-card">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                {{ substr($review->student->user->name ?? 'A', 0, 1) }}
                                            </div>
                                            <div class="reviewer-details">
                                                <h4 class="nepali">{{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}</h4>
                                                <div class="flex items-center gap-2">
                                                    <div class="rating-stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="review-date">{{ $review->created_at->format('Y-m-d') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 nepali mb-4">{{ $review->comment }}</p>
                                    
                                    @if($review->reply)
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                                            <div class="flex items-start space-x-3">
                                                <i class="fas fa-reply text-blue-500 mt-1 text-sm"></i>
                                                <div>
                                                    <strong class="text-blue-800 nepali text-sm">होस्टलको जवाफ:</strong>
                                                    <p class="text-blue-700 mt-1 nepali text-sm">{{ $review->reply }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($reviews->hasPages())
                            <div class="mt-6">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-comment-slash text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-600 nepali mb-2">अहिलेसम्म कुनै समीक्षा छैन</h3>
                            <p class="text-gray-500 nepali">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
                        </div>
                    @endif
                </section>

                <!-- Contact Form Section -->
                <section class="content-card">
                    <h2 class="section-title nepali">सम्पर्क गर्नुहोस्</h2>
                    @include('public.hostels.partials.contact-form')
                </section>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Contact Information -->
                <div class="sidebar-card sticky top-6">
                    <h3 class="text-xl font-bold text-gray-900 nepali mb-4 flex items-center gap-2">
                        <i class="fas fa-address-card text-blue-600"></i>
                        सम्पर्क जानकारी
                    </h3>
                    <div class="space-y-3">
                        @if($hostel->contact_person)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label nepali">सम्पर्क व्यक्ति</div>
                                    <div class="contact-value nepali">{{ $hostel->contact_person }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->contact_phone)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label nepali">फोन नम्बर</div>
                                    <a href="tel:{{ $hostel->contact_phone }}" class="contact-value hover:text-blue-600 transition-colors">
                                        {{ $hostel->contact_phone }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->contact_email)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label nepali">इमेल ठेगाना</div>
                                    <a href="mailto:{{ $hostel->contact_email }}" class="contact-value hover:text-blue-600 transition-colors text-sm">
                                        {{ $hostel->contact_email }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        @if($hostel->address)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label nepali">ठेगाना</div>
                                    <div class="contact-value nepali text-sm">{{ $hostel->address }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-bold text-gray-900 nepali mb-3">द्रुत कार्यहरू</h4>
                        <div class="space-y-2">
                            <a href="#contact-form" 
                               class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 flex items-center justify-center nepali font-semibold gap-2 text-sm">
                                <i class="fas fa-envelope"></i>
                                <span>सन्देश पठाउनुहोस्</span>
                            </a>
                            
                            <a href="{{ route('hostels.index') }}" 
                               class="w-full bg-gradient-to-r from-gray-600 to-gray-700 text-white py-3 px-4 rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 flex items-center justify-center nepali font-semibold gap-2 text-sm">
                                <i class="fas fa-building"></i>
                                <span>अन्य होस्टलहरू</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="sidebar-card">
                    <h3 class="text-lg font-bold text-gray-900 nepali mb-4">विश्वसनीय होस्टल</h3>
                    <div class="trust-badges">
                        <div class="trust-badge">
                            <div class="trust-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 nepali text-sm mb-1">सत्यापित</h4>
                            <p class="text-gray-600 nepali text-xs">प्रमाणित होस्टल</p>
                        </div>
                        
                        <div class="trust-badge">
                            <div class="trust-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 nepali text-sm mb-1">प्रमाणित</h4>
                            <p class="text-gray-600 nepali text-xs">गुणस्तरीय सेवा</p>
                        </div>
                        
                        <div class="trust-badge">
                            <div class="trust-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 nepali text-sm mb-1">रेटेड</h4>
                            <p class="text-gray-600 nepali text-xs">उत्कृष्ट समीक्षा</p>
                        </div>
                        
                        <div class="trust-badge">
                            <div class="trust-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 nepali text-sm mb-1">२४/७</h4>
                            <p class="text-gray-600 nepali text-xs">समर्थन उपलब्ध</p>
                        </div>
                    </div>
                </div>

                <!-- Availability Notice -->
                @if($hostel->available_rooms > 0)
                    <div class="sidebar-card bg-gradient-to-r from-green-500 to-emerald-600 text-white text-center">
                        <i class="fas fa-bed text-2xl mb-3"></i>
                        <h4 class="font-bold nepali mb-1">कोठा उपलब्ध!</h4>
                        <p class="nepali text-sm mb-3">अहिले {{ $hostel->available_rooms }} कोठा खाली छन्</p>
                        <a href="#contact-form" 
                           class="bg-white text-green-700 py-2 px-4 rounded-lg hover:bg-gray-100 transition-colors font-bold nepali text-sm inline-block">
                            अहिले बुक गर्नुहोस्
                        </a>
                    </div>
                @else
                    <div class="sidebar-card bg-gradient-to-r from-gray-500 to-gray-700 text-white text-center">
                        <i class="fas fa-bed text-2xl mb-3"></i>
                        <h4 class="font-bold nepali mb-1">सबै कोठा भरिएको</h4>
                        <p class="nepali text-sm">अहिले कुनै कोठा उपलब्ध छैन</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="floating-actions">
        @if($hostel->whatsapp_number)
            <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="floating-btn whatsapp">
                <i class="fab fa-whatsapp"></i>
            </a>
        @endif
        
        @if($hostel->contact_phone)
            <a href="tel:{{ $hostel->contact_phone }}" class="floating-btn phone">
                <i class="fas fa-phone"></i>
            </a>
        @endif
    </div>
</div>

<!-- Add Font Awesome -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
@endsection