@extends('layouts.frontend')

@section('page-title', 'हाम्रो बारेमा - HostelHub')

@push('styles')
<style>
    /* 🚨 CRITICAL: About page specific fixes - EXACT SAME AS FEATURES PAGE */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    .about-page-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }
    
    /* Remove any duplicate header protection */
    .page-header {
        display: none !important;
    }
    
    /* Updated Header Styles - EXACT SAME AS FEATURES PAGE HEADER */
    .about-header {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2.5rem 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 1000px;
        width: 90%;
        
        /* 🚨 EXACT SAME SPACING AS FEATURES PAGE HEADER */
        margin: calc(var(--header-height, 70px) + 0.9rem) auto 1.5rem auto !important;
    }
    
    .about-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
    }
    
    .about-header p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 800px;
        margin: 0 auto 0.75rem auto;
        line-height: 1.6;
    }

    /* Main Content Section - EXACT SAME STRUCTURE AS FEATURES GRID SECTION */
    .about-content-section {
        padding-top: 0.5rem !important;
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }
    
    /* Updated Intro Section - More Professional */
    .about-intro {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
        align-items: center;
        padding: 0 1rem;
        max-width: 1000px;
        margin: 0 auto 2rem auto;
    }
    
    .intro-title {
        font-size: 2.2rem;
        color: var(--primary);
        margin-bottom: 1.5rem;
        font-weight: 700;
    }
    
    .intro-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--text-dark);
    }
    
    .intro-text p {
        margin-bottom: 1.5rem;
    }
    
    .intro-image {
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    
    .intro-img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        display: block;
    }
    
    /* Platform Explanation Section - Updated with SAME STRUCTURE */
    .platform-explanation {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 3rem 2rem;
        border-radius: 1rem;
        margin: 2rem auto 3rem auto;
        max-width: 1000px;
        width: 100%;
        text-align: center;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .platform-explanation h3 {
        font-size: 1.8rem;
        color: var(--primary);
        margin-bottom: 1rem;
        font-weight: 700;
    }
    
    .platform-explanation p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: var(--text-dark);
        max-width: 800px;
        margin: 0 auto 1.5rem auto;
    }
    
    .platform-features {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .platform-feature {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .platform-feature h4 {
        color: var(--secondary);
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .platform-feature p {
        font-size: 0.95rem;
        margin-bottom: 0;
        color: #4a5568;
    }
    
    /* VALUES SECTION - UPDATED WITH SAME GRID STRUCTURE */
    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 4rem;
        max-width: 1000px;
        margin: 0 auto 2rem auto;
    }
    
    .value-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
        transform: translateY(0);
        text-align: center;
    }
    
    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .value-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 1.5rem;
        background: var(--bg-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--secondary);
    }
    
    .value-title {
        font-size: 1.4rem;
        color: var(--primary);
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .value-description {
        color: var(--text-dark);
        line-height: 1.6;
        font-size: 1rem;
    }
    
    /* STATS SECTION - UPDATED NUMBERS WITH SAME STRUCTURE */
    .stats-section {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        padding: 3rem 0;
        margin-bottom: 4rem;
        border-radius: 1rem;
        max-width: 1000px;
        margin: 0 auto 2rem auto;
        width: 100%;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        text-align: center;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    .stat-item {
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    /* HOW IT WORKS SECTION - ADDED WITH SAME CARD STRUCTURE */
    .how-it-works {
        padding: 3rem 0;
        max-width: 1000px;
        margin: 0 auto 2rem auto;
        width: 100%;
    }
    
    .how-it-works h2 {
        text-align: center;
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 2rem;
        font-weight: 700;
    }
    
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    
    .step-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        text-align: center;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }
    
    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .step-number {
        width: 50px;
        height: 50px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0 auto 1rem;
    }
    
    .step-title {
        font-size: 1.3rem;
        color: var(--secondary);
        margin-bottom: 1rem;
    }
    
    .step-description {
        color: var(--text-dark);
        line-height: 1.6;
        font-size: 1rem;
    }
    
    /* 🚨 CTA SECTION - EXACT SAME AS FEATURES PAGE CTA */
    .about-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1rem;
    }
    
    .about-cta-section {
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
    
    .about-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
    }
    
    .about-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }
    
    .about-contact-email {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 1.5rem 0 2rem 0;
        display: block;
        color: #ffffff;
        text-decoration: underline;
    }
    
    /* CTA Buttons Container - EXACT SAME AS FEATURES PAGE */
    .about-cta-buttons-container {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        width: 100%;
        flex-wrap: wrap;
    }
    
    /* TRIAL BUTTON (White Background) - SAME AS FEATURES PAGE */
    .about-trial-button {
        background-color: white;
        color: #001F5B;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: none;
        cursor: button;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1rem;
        text-align: center;
    }
    
    .about-trial-button:hover:not(:disabled) {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: #001F5B;
    }
    
    /* OUTLINE PRICING BUTTON - SAME AS FEATURES PAGE */
    .about-outline-button {
        background: transparent;
        border: 2px solid white;
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1rem;
        text-align: center;
    }
    
    .about-outline-button:hover {
        background: white;
        color: #001F5B;
        transform: translateY(-2px);
    }
    
    .about-trial-button:disabled {
        background: #6c757d;
        color: white;
        cursor: not-allowed;
        transform: none;
        border: none;
    }

    .about-trial-button:disabled:hover {
        background: #6c757d;
        color: white;
        transform: none;
    }

    /* Mobile adjustments - EXACT SAME AS FEATURES PAGE */
    @media (max-width: 768px) {
        .about-header {
            margin: calc(60px + 0.25rem) auto 1rem auto !important;
            padding: 1.75rem 1rem;
            width: calc(100% - 2rem);
        }
        
        .about-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .about-header p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .about-content-section {
            padding-top: 0.25rem !important;
            margin: 0 auto 1rem auto;
        }

        .about-intro {
            grid-template-columns: 1fr;
            padding: 0 1rem;
        }
        
        .intro-image {
            order: -1;
        }
        
        .values-grid,
        .stats-grid,
        .steps-grid,
        .platform-features {
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 0 1rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
        }
        
        .platform-explanation {
            padding: 2rem 1rem;
        }
        
        .about-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .about-cta-section {
            padding: 2rem 1.5rem;
        }
        
        .about-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .about-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .about-contact-email {
            font-size: 1.1rem;
        }
        
        .about-cta-buttons-container {
            margin-top: 0.75rem;
            flex-direction: column;
            gap: 0.75rem;
        }

        .about-trial-button,
        .about-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 160px;
            width: 100%;
            max-width: 250px;
        }
        
        .about-intro,
        .values-grid,
        .how-it-works,
        .stats-section,
        .platform-explanation {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }

    @media (max-width: 480px) {
        .about-header h1 {
            font-size: 1.75rem;
        }
        
        .about-cta-wrapper {
            padding: 0.75rem 1rem 1.25rem 1rem;
        }
        
        .about-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .about-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .about-cta-section p {
            font-size: 0.9rem;
        }
        
        .about-contact-email {
            font-size: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 0 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Updated Hero Section - EXACT SAME STRUCTURE AS FEATURES PAGE -->
<div class="about-page-wrapper">
    <!-- Page Header - EXACT SAME SPACING AS FEATURES PAGE -->
    <div class="about-header">
        <h1>हाम्रो बारेमा</h1>
        <p>HostelHub नेपालको अग्रणी Multi-Tenant होस्टल व्यवस्थापन SaaS प्लेटफर्म हो</p>
        <p>हाम्रो कथा, हाम्रो टेक्नोलोजी र हाम्रो लक्ष्यहरू</p>
    </div>

    <!-- Main Content Section - EXACT SAME STRUCTURE AS FEATURES GRID SECTION -->
    <section class="about-content-section">
        <!-- Introduction Section -->
        <div class="about-intro">
            <div class="intro-content">
                <h2 class="intro-title">हाम्रो कथा</h2>
                <div class="intro-text">
                    <p>HostelHub नेपालको पहिलो Multi-Tenant होस्टल व्यवस्थापन SaaS प्लेटफर्म हो जसले होस्टलहरूको दैनिक कार्यहरूलाई डिजिटल रूपमा रूपान्तरण गर्दछ। हाम्रो उद्देश्य होस्टल व्यवस्थापनलाई सजिलो, द्रुत र विश्वसनीय बनाउनु हो।</p>
                    <p>हामी २०२५ मा सुरु भएको स्टार्टअप हौं जसले नेपाली शिक्षा क्षेत्रमा डिजिटल रूपान्तरण ल्याउने लक्ष्य राखेका छौं। हाम्रो प्लेटफर्मलाई विद्यार्थी र होस्टल मालिक दुवैको आवश्यकतालाई ध्यानमा राखेर डिजाइन गरिएको छ।</p>
                    <p>हामी विश्वास गर्छौं कि उन्नत टेक्नोलोजी र सरल इन्टरफेसले होस्टल व्यवस्थापनलाई पूर्ण रूपमा बदल्न सक्छ।</p>
                </div>
            </div>
            <div class="intro-image">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80" alt="HostelHub Vision" class="intro-img">
            </div>
        </div>

        <!-- Platform Explanation Section -->
        <div class="platform-explanation">
            <h3>Multi-Tenant SaaS प्लेटफर्म</h3>
            <p>HostelHub एउटै प्लेटफर्म भित्र धेरै होस्टलहरूको लागि अलग-अलग व्यवस्थापन ड्यासबोर्ड प्रदान गर्दछ। प्रत्येक होस्टलको डाटा पूर्ण रूपमा अलग, सुरक्षित र व्यक्तिगत हुन्छ।</p>
            
            <div class="platform-features">
                <div class="platform-feature">
                    <h4>सुरक्षित डाटा पृथकता</h4>
                    <p>प्रत्येक होस्टलको डाटा अलग डाटाबेस शेमामा राखिन्छ</p>
                </div>
                <div class="platform-feature">
                    <h4>सार्वजनिक पृष्ठ कस्टमाइजेशन</h4>
                    <p>प्रत्येक होस्टलले आफ्नो सार्वजनिक पृष्ठ अनुकूलित गर्न सक्छ</p>
                </div>
                <div class="platform-feature">
                    <h4>स्केलेबल आर्किटेक्चर</h4>
                    <p>साना देखि ठूला होस्टलहरूको लागि उपयुक्त</p>
                </div>
            </div>
        </div>

        <!-- Vision / Mission / Values -->
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="value-title">हाम्रो दृष्टि</h3>
                <p class="value-description">नेपालको प्रत्येक होस्टललाई उत्कृष्ट व्यवस्थापन प्रणाली प्रदान गर्ने र शिक्षा क्षेत्रमा डिजिटल रूपान्तरण ल्याउने।</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="value-title">हाम्रो मिशन</h3>
                <p class="value-description">होस्टल व्यवस्थापन प्रक्रियाहरूलाई सरल बनाएर प्रबन्धकहरूको समय बचत गर्ने र विद्यार्थीहरूलाई उत्कृष्ट सेवा प्रदान गर्ने।</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="value-title">हाम्रो मूल्य</h3>
                <p class="value-description">विश्वसनीयता, नवीनता र गुणस्तरलाई प्राथमिकता दिँदै ग्राहकहरूको आवश्यकतालाई केन्द्रमा राख्ने।</p>
            </div>
        </div>

        <!-- Stats Section - Updated Numbers -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-count="24">0</div>
                    <div class="stat-label">होस्टलहरू</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="125">0</div>
                    <div class="stat-label">विद्यार्थीहरू</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="5">0</div>
                    <div class="stat-label">शहरहरू</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="1">0</div>
                    <div class="stat-label">वर्ष अनुभव</div>
                </div>
            </div>
        </div>

        <!-- How It Works Section - Added -->
        <div class="how-it-works">
            <h2>HostelHub कसरी काम गर्छ?</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">१</div>
                    <h3 class="step-title">होस्टल जोड्नुहोस्</h3>
                    <p class="step-description">निःशुल्क खाता सिर्जना गर्नुहोस् र आफ्नो होस्टल विवरणहरू थप्नुहोस्</p>
                </div>
                <div class="step-card">
                    <div class="step-number">२</div>
                    <h3 class="step-title">ड्यासबोर्ड प्रयोग गर्नुहोस्</h3>
                    <p class="step-description">विद्यार्थीहरू थप्नुहोस्, कोठा आवंटन गर्नुहोस्, र भुक्तानीहरू ट्र्याक गर्नुहोस्</p>
                </div>
                <div class="step-card">
                    <div class="step-number">३</div>
                    <h3 class="step-title">विस्तार गर्नुहोस्</h3>
                    <p class="step-description">हाम्रा उन्नत सुविधाहरू प्रयोग गरेर आफ्नो होस्टल व्यवसायलाई बढाउनुहोस्</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 🚨 CTA SECTION - EXACT SAME AS FEATURES PAGE CTA -->
    <div class="about-cta-wrapper">
        <section class="about-cta-section">
            <h2>हामीलाई सम्पर्क गर्नुहोस्</h2>
            <p>हामी तपाईंलाई सहयोग गर्न तत्पर छौं</p>
            <a href="mailto:support@hostelhub.com" class="about-contact-email">support@hostelhub.com</a>
            
            <div class="about-cta-buttons-container">
                <!-- BUTTON 1: FREE TRIAL -->
                @auth
                    @php
                        $organizationId = session('current_organization_id');
                        $hasSubscription = false;
                        
                        if ($organizationId) {
                            try {
                                $organization = \App\Models\Organization::with('subscription')->find($organizationId);
                                $hasSubscription = $organization->subscription ?? false;
                            } catch (Exception $e) {
                                $hasSubscription = false;
                            }
                        }
                    @endphp
                    
                    @if($hasSubscription)
                        <button class="about-trial-button" disabled>
                            <i class="fas fa-check-circle"></i> तपाईंसँग पहिले नै सदस्यता छ
                        </button>
                    @else
                        <form action="{{ route('subscription.start-trial') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="about-trial-button">
                                <i class="fas fa-rocket"></i> निःशुल्क परीक्षण
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('register.organization', ['plan' => 'starter']) }}" class="about-trial-button">
                        <i class="fas fa-rocket"></i> निःशुल्क परीक्षण सुरु गर्नुहोस्
                    </a>
                @endauth
                
                <!-- BUTTON 2: PRICING -->
                @php
                    // Try to determine the correct pricing route
                    $pricingRoute = null;
                    
                    if (Route::has('pricing')) {
                        $pricingRoute = route('pricing');
                    } elseif (Route::has('pricing.index')) {
                        $pricingRoute = route('pricing.index');
                    } elseif (Route::has('frontend.pricing')) {
                        $pricingRoute = route('frontend.pricing');
                    } elseif (Route::has('plans')) {
                        $pricingRoute = route('plans');
                    } else {
                        $pricingRoute = url('/pricing');
                    }
                @endphp
                
                <a href="{{ $pricingRoute }}" class="about-outline-button">
                    <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                </a>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Counter animation for stats - EXACT SAME AS FEATURES PAGE ANIMATION
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.stat-number');
        const speed = 200;
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            let count = 0;
            
            const updateCount = () => {
                const increment = Math.ceil(target / speed);
                
                if (count < target) {
                    count += increment;
                    if (count > target) count = target;
                    counter.innerText = count;
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            };
            
            updateCount();
        });
    });
</script>
@endpush