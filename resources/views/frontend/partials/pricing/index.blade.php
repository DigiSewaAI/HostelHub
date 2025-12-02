@extends('layouts.frontend')

@section('page-title', 'हाम्रा योजनाहरू - HostelHub')

@push('styles')
<style>
    /* 🚨 IMPORTANT: Pricing page spacing fix - EXACT SAME AS GALLERY PAGE */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .pricing-content-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }

    /* Page Header - EXACT SAME AS GALLERY PAGE HEADER */
    .pricing-hero {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2.5rem 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 1000px;
        width: 90%;
        
        /* 🚨 EXACT SAME SPACING AS GALLERY PAGE HEADER */
        margin: calc(var(--header-height, 70px) + 0.9rem) auto 1.5rem auto !important;
    }
    
    .pricing-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }
    
    .pricing-hero p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 800px;
        margin: 0 auto 0.75rem auto;
    }

    /* Pricing Cards Section - SAME STRUCTURE AS GALLERY FILTERS SECTION */
    .pricing-cards-section {
        padding-top: 0.5rem !important;
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }

    .pricing-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin: 0;
    }
    
    .pricing-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        width: 100%;
        max-width: 350px;
        padding: 30px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        flex: 1;
        min-width: 300px;
    }
    
    .pricing-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .pricing-header {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .pricing-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a3a8f;
        margin-bottom: 15px;
    }
    
    .pricing-price {
        font-size: 32px;
        font-weight: 800;
        color: #0d6efd;
        margin-bottom: 5px;
    }
    
    .pricing-period {
        color: #6c757d;
        font-size: 14px;
    }
    
    .pricing-features {
        list-style: none;
        margin: 25px 0;
        text-align: left;
        padding: 0;
    }
    
    .pricing-features li {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .pricing-features i {
        color: #28a745;
        margin-right: 10px;
        font-size: 18px;
        min-width: 24px;
    }
    
    .pricing-button {
        display: inline-block;
        background: #0d6efd;
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid #0d6efd;
        margin-top: 10px;
        cursor: pointer;
        width: 100%;
    }
    
    .pricing-button:hover {
        background: transparent;
        color: #0d6efd;
        transform: scale(1.05);
    }

    .pricing-button:disabled {
        background: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
        transform: none;
    }

    .pricing-button:disabled:hover {
        background: #6c757d;
        color: white;
        transform: none;
    }
    
    .popular {
        position: relative;
        border: 2px solid #ffc107;
        transform: scale(1.03);
    }
    
    .popular-badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: #ffc107;
        color: #000;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* FAQ Section */
    .faq-section {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin: 0 auto 2rem auto;
        text-align: center;
        max-width: 1200px;
        width: 95%;
    }
    
    .faq-title {
        color: #1a3a8f;
        margin-bottom: 30px;
        font-size: 28px;
    }
    
    .faq-content {
        max-width: 700px;
        margin: 0 auto;
    }
    
    .faq-item {
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px solid #eee;
    }
    
    .faq-question {
        font-weight: 600;
        color: #1a3a8f;
        margin-bottom: 10px;
        font-size: 18px;
    }
    
    .faq-answer {
        color: #666;
        line-height: 1.6;
    }
    
    .contact-cta {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        padding: 30px;
        border-radius: 10px;
        color: white;
        margin-top: 30px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    }
    
    .contact-cta h3 {
        margin-bottom: 15px;
        font-size: 24px;
        color: white;
    }
    
    .contact-email {
        font-size: 20px;
        font-weight: 600;
        margin: 20px 0;
        display: block;
        color: #ffffff;
        text-decoration: underline;
    }
    
    .trial-button {
        display: inline-block;
        background: white;
        color: #001F5B;
        padding: 15px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        border: 2px solid white;
        font-size: 18px;
        margin-top: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        cursor: pointer;
    }
    
    .trial-button:hover {
        background: transparent;
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(255,255,255,0.2);
        border-color: #ffffff;
    }

    .trial-button:disabled {
        background: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
        transform: none;
    }

    .trial-button:disabled:hover {
        background: #6c757d;
        color: white;
        transform: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    
    /* Loading state */
    .pricing-button.loading {
        position: relative;
        color: transparent;
    }
    
    .pricing-button.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin: -10px 0 0 -10px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }
    
    .current-plan-badge {
        background: #28a745;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-top: 10px;
        display: inline-block;
    }

    .trial-warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .feature-note {
        margin-top: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
        font-size: 14px;
        border-left: 4px solid #0d6efd;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* 🚨 CTA Section - EXACT SAME AS GALLERY PAGE */
    .pricing-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1rem;
    }

    .pricing-cta-section {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
    }

    .pricing-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
    }

    .pricing-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .pricing-cta-buttons-container {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        width: 100%;
        flex-wrap: wrap;
    }

    /* Mobile adjustments - EXACT SAME AS GALLERY PAGE */
    @media (max-width: 768px) {
        .pricing-hero {
            margin: calc(60px + 0.25rem) auto 1rem auto !important;
            padding: 1.75rem 1rem;
            width: calc(100% - 2rem);
        }
        
        .pricing-hero h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .pricing-hero p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .pricing-cards-section {
            padding-top: 0.25rem !important;
            margin: 0 auto 1rem auto;
        }

        .pricing-container {
            flex-direction: column;
            align-items: center;
        }
        
        .pricing-card {
            margin-bottom: 30px;
        }
        
        .popular {
            transform: scale(1);
        }
        
        .faq-section {
            padding: 25px 20px;
        }

        .pricing-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .pricing-cta-section {
            padding: 2rem 1.5rem;
        }
        
        .pricing-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .pricing-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .pricing-cta-buttons-container {
            margin-top: 0.75rem;
            flex-direction: column;
        }

        .trial-button,
        .pricing-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .pricing-hero h1 {
            font-size: 1.75rem;
        }
        
        .pricing-cta-wrapper {
            padding: 0.75rem 1rem 1.25rem 1rem;
        }
        
        .pricing-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .pricing-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .pricing-cta-section p {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')

<div class="pricing-content-wrapper">
    <!-- Hero Section - EXACT SAME AS GALLERY PAGE -->
    <section class="pricing-hero">
        <h1>हाम्रा योजनाहरू</h1>
        <p>तपाईंको होस्टल व्यवस्थापन आवश्यकताअनुसार उपयुक्त योजना छान्नुहोस्</p>
        <p>७ दिन निःशुल्क परीक्षण | कुनै पनि क्रेडिट कार्ड आवश्यक छैन</p>
    </section>

    @auth
        @php
            // ✅ FIXED: Define variables once at the top for all plans
            $organizationId = session('current_organization_id');
            $currentSubscription = null;
            $currentPlan = null;
            $isTrial = false;
            
            if ($organizationId) {
                try {
                    $organization = \App\Models\Organization::with('subscription.plan')->find($organizationId);
                    $currentSubscription = $organization->subscription ?? null;
                    $currentPlan = $currentSubscription->plan ?? null;
                    $isTrial = $currentSubscription && $currentSubscription->status == 'trial';
                } catch (Exception $e) {
                    // If error, treat as no subscription
                    $currentSubscription = null;
                    $currentPlan = null;
                    $isTrial = false;
                }
            }
            
            // ✅ FIXED: Define all plan checks once
            $isStarterCurrent = $currentPlan && $currentPlan->slug == 'starter';
            $isProCurrent = $currentPlan && $currentPlan->slug == 'pro';
            $isEnterpriseCurrent = $currentPlan && $currentPlan->slug == 'enterprise';
            $hasSubscription = $currentSubscription != null;
        @endphp
    @endauth

    <!-- Pricing Cards Section - Structured like gallery filters -->
    <section class="pricing-cards-section">
        <div class="pricing-container">
            <!-- Starter Plan -->
            <div class="pricing-card">
                <div class="pricing-header">
                    <h3 class="pricing-title">सुरुवाती</h3>
                    <div class="pricing-price">रु. 2,999</div>
                    <div class="pricing-period">/महिना</div>
                </div>
                <ul class="pricing-features">
                    <li><i class="fas fa-users"></i> ५० विद्यार्थी सम्म</li>
                    <li><i class="fas fa-building"></i> १ होस्टल सम्म</li>
                    <li><i class="fas fa-user-graduate"></i> मूल विद्यार्थी व्यवस्थापन</li>
                    <li><i class="fas fa-bed"></i> कोठा आवंटन</li>
                    <li><i class="fas fa-calendar-check"></i> <strong>बेसिक अग्रिम कोठा बुकिंग (manual approval)</strong></li>
                    <li><i class="fas fa-money-bill-wave"></i> भुक्तानी ट्र्याकिंग</li>
                </ul>
                
                @auth
                    @if($isTrial)
                        <div class="trial-warning">
                            तपाईं निःशुल्क परीक्षण अवधिमा हुनुहुन्छ
                        </div>
                        <button class="pricing-button" disabled>
                            परीक्षण अवधिमा
                        </button>
                    @elseif($isStarterCurrent)
                        <div class="current-plan-badge">
                            वर्तमान योजना
                        </div>
                        <button class="pricing-button" disabled>
                            सक्रिय
                        </button>
                    @else
                        <form action="{{ route('subscription.upgrade') }}" method="POST" class="plan-form" style="display: inline; width: 100%;">
                            @csrf
                            <input type="hidden" name="plan" value="starter">
                            <button type="submit" class="pricing-button">योजना छान्नुहोस्</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('register.organization', ['plan' => 'starter']) }}" class="pricing-button">योजना छान्नुहोस्</a>
                @endauth
            </div>

            <!-- Pro Plan -->
            <div class="pricing-card popular">
                <div class="popular-badge">लोकप्रिय</div>
                <div class="pricing-header">
                    <h3 class="pricing-title">प्रो</h3>
                    <div class="pricing-price">रु. 4,999</div>
                    <div class="pricing-period">/महिना</div>
                </div>
                <ul class="pricing-features">
                    <li><i class="fas fa-users"></i> २०० विद्यार्थी सम्म</li>
                    <li><i class="fas fa-building"></i> १ होस्टल सम्म</li>
                    <li><i class="fas fa-user-graduate"></i> पूर्ण विद्यार्थी व्यवस्थापन</li>
                    <li><i class="fas fa-calendar-check"></i> <strong>अग्रिम कोठा बुकिंग (auto-confirm, notifications)</strong></li>
                    <li><i class="fas fa-money-bill-wave"></i> भुक्तानी ट्र्याकिंग</li>
                    <li><i class="fas fa-mobile-alt"></i> मोबाइल एप्प</li>
                </ul>
                
                @auth
                    @if($isTrial)
                        <div class="trial-warning">
                            तपाईं निःशुल्क परीक्षण अवधिमा हुनुहुन्छ
                        </div>
                        <button class="pricing-button" disabled>
                            परीक्षण अवधिमा
                        </button>
                    @elseif($isProCurrent)
                        <div class="current-plan-badge">
                            वर्तमान योजना
                        </div>
                        <button class="pricing-button" disabled>
                            सक्रिय
                        </button>
                    @else
                        <form action="{{ route('subscription.upgrade') }}" method="POST" class="plan-form" style="display: inline; width: 100%;">
                            @csrf
                            <input type="hidden" name="plan" value="pro">
                            <button type="submit" class="pricing-button">योजना छान्नुहोस्</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('register.organization', ['plan' => 'pro']) }}" class="pricing-button">योजना छान्नुहोस्</a>
                @endauth
            </div>

            <!-- Enterprise Plan -->
            <div class="pricing-card">
                <div class="pricing-header">
                    <h3 class="pricing-title">एन्टरप्राइज</h3>
                    <div class="pricing-price">रु. 8,999</div>
                    <div class="pricing-period">/महिना</div>
                </div>
                <ul class="pricing-features">
                    <li><i class="fas fa-users"></i> असीमित विद्यार्थी</li>
                    <li><i class="fas fa-building"></i> <strong>बहु-होस्टल व्यवस्थापन (५ होस्टल सम्म)</strong></li>
                    <li><i class="fas fa-user-graduate"></i> पूर्ण विद्यार्थी व्यवस्थापन</li>
                    <li><i class="fas fa-calendar-check"></i> अग्रिम कोठा बुकिंग (auto-confirm)</li>
                    <li><i class="fas fa-credit-card"></i> कस्टम भुक्तानी प्रणाली</li>
                    <li><i class="fas fa-headset"></i> २४/७ समर्थन</li>
                </ul>

                <!-- Enterprise Plan को तल यो note थप्नुहोस्: -->
                @if(!isset($isEnterpriseCurrent) || !$isEnterpriseCurrent)
                    <div class="feature-note">
                        <i class="fas fa-info-circle"></i> 
                        <strong>अतिरिक्त होस्टल थप्न सकिन्छ:</strong> रु. १,०००/महिना प्रति अतिरिक्त होस्टल
                    </div>
                @endif
                
                @auth
                    @if($isTrial)
                        <div class="trial-warning">
                            तपाईं निःशुल्क परीक्षण अवधिमा हुनुहुन्छ
                        </div>
                        <button class="pricing-button" disabled>
                            परीक्षण अवधिमा
                        </button>
                    @elseif($isEnterpriseCurrent)
                        <div class="current-plan-badge">
                            वर्तमान योजना
                        </div>
                        <button class="pricing-button" disabled>
                            सक्रिय
                        </button>
                    @else
                        <form action="{{ route('subscription.upgrade') }}" method="POST" class="plan-form" style="display: inline; width: 100%;">
                            @csrf
                            <input type="hidden" name="plan" value="enterprise">
                            <button type="submit" class="pricing-button">योजना छान्नुहोस्</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('register.organization', ['plan' => 'enterprise']) }}" class="pricing-button">योजना छान्नुहोस्</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <h2 class="faq-title">अझै केही जिज्ञासा छन्? सहयोग चाहिन्छ?</h2>
        
        <div class="faq-content">
            <div class="faq-item">
                <div class="faq-question">हाम्रो सेवा कसरी सुरु गर्न सकिन्छ?</div>
                <p class="faq-answer">तपाईं माथिको योजनाहरू मध्ये कुनै एक छान्नुहोस् र ७ दिने निःशुल्क परीक्षण सुरु गर्नुहोस्। कुनै क्रेडिट कार्ड आवश्यक छैन।</p>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">परीक्षण अवधि पछि के हुन्छ?</div>
                <p class="faq-answer">परीक्षण अवधि समाप्त भएपछि, तपाईंले छान्नुभएको योजनाअनुसार सेवा सञ्चालन गर्न सक्नुहुन्छ वा कुनै पनि अतिरिक्त लागत बिना रद्द गर्न सक्नुहुन्छ।</p>
            </div>
            
            <!-- 🚨 CTA Section - EXACT SAME AS GALLERY PAGE -->
            <div class="contact-cta">
                <h3>हामीलाई सम्पर्क गर्नुहोस्</h3>
                <p>हामी तपाईंलाई सहयोग गर्न तत्पर छौं</p>
                <a href="mailto:support@hostelhub.com" class="contact-email">support@hostelhub.com</a>
                <div>
                    @auth
                        @if($hasSubscription)
                            <button class="trial-button" disabled>
                                तपाईंसँग पहिले नै सदस्यता छ
                            </button>
                        @else
                            <form action="{{ route('subscription.start-trial') }}" method="POST" class="trial-form" style="display: inline;">
                                @csrf
                                <button type="submit" class="trial-button">७ दिन निःशुल्क परीक्षण सुरु गर्नुहोस्</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('register.organization', ['plan' => 'starter']) }}" class="trial-button">७ दिन निःशुल्क परीक्षण सुरु गर्नुहोस्</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    // Simple animation for pricing cards
    document.addEventListener('DOMContentLoaded', function() {
        const pricingCards = document.querySelectorAll('.pricing-card');
        
        pricingCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                if(!this.classList.contains('popular')) {
                    this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.08)';
                }
            });
        });

        // Handle plan form submissions
        const planForms = document.querySelectorAll('.plan-form');
        
        planForms.forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                
                // Show loading state
                button.classList.add('loading');
                button.disabled = true;
                
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
                    
                    if (data.success) {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            // Show success message
                            alert(data.message || 'योजना सफलतापूर्वक अपग्रेड गरियो');
                            window.location.reload();
                        }
                    } else {
                        // Show error message from server
                        throw new Error(data.message || 'अज्ञात त्रुटि');
                    }
                } catch (error) {
                    // Show proper error message
                    alert('त्रुटि: ' + error.message);
                    button.classList.remove('loading');
                    button.textContent = originalText;
                    button.disabled = false;
                }
            });
        });

        // Handle trial form submission
        const trialForm = document.querySelector('.trial-form');
        if (trialForm) {
            trialForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                
                // Show loading state
                button.classList.add('loading');
                button.disabled = true;
                
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
                    
                    if (data.success) {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            // Show success message
                            alert(data.message || 'निःशुल्क परीक्षण सफलतापूर्वक सुरु गरियो');
                            window.location.reload();
                        }
                    } else {
                        throw new Error(data.message || 'अज्ञात त्रुटि');
                    }
                } catch (error) {
                    alert('त्रुटि: ' + error.message);
                    button.classList.remove('loading');
                    button.textContent = originalText;
                    button.disabled = false;
                }
            });
        }

        // Show success/error messages from session
        @if(session('success'))
            alert('{{ session('success') }}');
        @endif

        @if(session('error'))
            alert('{{ session('error') }}');
        @endif
    });
</script>
@endpush

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection