@extends('layouts.frontend')
@section('title', 'सेवा सर्तहरू - HostelHub')

@push('styles')
<style>
    /* 🚨 IMPORTANT: Terms page spacing fix - EXACT SAME AS PRIVACY PAGE */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .terms-page-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }

    /* Page Header - EXACT SAME AS PRIVACY PAGE */
    .terms-header {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2.5rem 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 1000px;
        width: 90%;
        margin: calc(var(--header-height, 70px) + 0.9rem) auto 1.5rem auto !important;
    }
    
    .terms-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
    }
    
    .terms-header p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 800px;
        margin: 0 auto 0.75rem auto;
    }

    /* Last Updated */
    .last-updated {
        text-align: center;
        margin-bottom: 2.5rem;
        color: #6b7280;
        font-size: 0.875rem;
        max-width: 1200px;
        margin: 0 auto 2.5rem auto;
        width: 95%;
    }

    /* Content Sections */
    .terms-content-section {
        padding-top: 0.5rem !important;
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }

    .terms-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .terms-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #001F5B;
        margin: 2rem 0 1rem;
    }

    .terms-content p {
        margin-bottom: 1rem;
        line-height: 1.7;
    }

    .terms-content ul {
        margin: 1rem 0 1rem 1.5rem;
        list-style: disc;
        line-height: 1.8;
    }

    .terms-content li {
        margin-bottom: 0.5rem;
    }

    /* Plain text contact info */
    .contact-plain {
        margin: 1rem 0;
        padding: 0;
    }

    .contact-plain a {
        color: #001F5B;
        text-decoration: none;
        font-weight: 500;
    }

    .contact-plain a:hover {
        text-decoration: underline;
    }

    /* 🚨 UPDATED CTA SECTION - PROFESSIONAL STRATEGY (FIXED BORDER ISSUE) */
    .terms-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 2rem 1.5rem 3rem 1.5rem;
        margin-top: 2rem;
        /* 🚨 BACKGROUND REMOVED - केवल सफेद ब्याकग्राउन्ड राख्ने */
        background: transparent;
    }

    .terms-cta-section {
        text-align: center;
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
        /* 🚨 ग्रेडियन्ट केवल यो सेक्सनमा मात्र */
        background: linear-gradient(135deg, var(--primary), var(--secondary));
    }

    .terms-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
    }

    .terms-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .terms-cta-buttons-container {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        justify-content: center;
        margin-top: 2rem;
        width: 100%;
    }

    /* Terms CTA Button Styles */
    .terms-trial-button {
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
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1rem;
    }
    
    .terms-trial-button:hover:not(:disabled) {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: #001F5B;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .terms-outline-button {
        background-color: transparent;
        color: white;
        border: 2px solid white;
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
    }
    
    .terms-outline-button:hover {
        background-color: white;
        color: #001F5B;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .terms-trial-button:disabled {
        background: #6c757d;
        color: white;
        cursor: not-allowed;
        transform: none;
        border: none;
    }

    .terms-trial-button:disabled:hover {
        background: #6c757d;
        color: white;
        transform: none;
    }

    /* Loading button styles */
    .terms-trial-button.loading,
    .terms-outline-button.loading {
        position: relative;
        color: transparent;
    }
    
    .terms-trial-button.loading::after,
    .terms-outline-button.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin: -10px 0 0 -10px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }
    
    .terms-trial-button.loading::after {
        border: 2px solid rgba(0,31,91,0.3);
        border-top-color: #001F5B;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .terms-header {
            margin: calc(60px + 0.25rem) auto 1rem auto !important;
            padding: 1.75rem 1rem;
            width: calc(100% - 2rem);
        }
        
        .terms-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .terms-header p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .last-updated {
            margin: 0 auto 1.5rem auto;
        }

        .terms-content-section {
            padding-top: 0.25rem !important;
            margin: 0 auto 1rem auto;
        }

        .terms-cta-wrapper {
            padding: 1.5rem 1rem 2rem 1rem;
        }
        
        .terms-cta-section {
            padding: 2rem 1.5rem;
        }
        
        .terms-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .terms-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .terms-cta-buttons-container {
            margin-top: 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .terms-trial-button,
        .terms-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 160px;
            width: 100%;
            max-width: 250px;
        }
    }

    @media (max-width: 480px) {
        .terms-header h1 {
            font-size: 1.75rem;
        }
        
        .terms-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .terms-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .terms-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .terms-cta-section p {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<div class="terms-page-wrapper">
    <!-- Page Header -->
    <div class="terms-header">
        <h1>सेवा सर्तहरू</h1>
        <p>HostelHub को सेवाहरू प्रयोग गर्दा लागू हुने कानूनी सर्तहरू।</p>
    </div>

    <!-- Last Updated -->
    <div class="last-updated">
        अन्तिम अद्यावधिक: १० जेठ, २०८२
    </div>

    <!-- Content Sections -->
    <section class="terms-content-section">
        <div class="terms-content">
            <h2>१. स्वीकृति</h2>
            <p>
                यी सेवा सर्तहरूमा उल्लेखित नियमहरूमा तपाइँको पहुँच, प्रयोग, र निर्भरताले तपाइँले यी सर्तहरूलाई पढेको, बुझेको र स्वीकार गरेको मानिनेछ।
            </p>

            <h2>२. सेवा प्रयोग</h2>
            <p>
                तपाइँले HostelHub लाई निम्न उद्देश्यका लागि प्रयोग गर्न सक्नुहुन्छ:
            </p>
            <ul>
                <li>होस्टल प्रबन्धन गर्न</li>
                <li>विद्यार्थी, कोठा, भुक्तानी, र भोजन व्यवस्थापन गर्न</li>
                <li>मोबाइल एप्प प्रयोग गर्न</li>
            </ul>
            <p>
                तपाइँले यसलाई अवैध, धोखाधडी, वा हानिकारक उद्देश्यका लागि प्रयोग गर्नु हुँदैन।
            </p>

            <h2>३. खाता दायित्व</h2>
            <p>
                तपाइँले आफ्नो खाताको लागि निम्न दायित्वहरू बहन गर्नुपर्छ:
            </p>
            <ul>
                <li>सही र अद्यावधिक जानकारी प्रदान गर्ने</li>
                <li>पासवर्ड र लगइन विवरण सुरक्षित राख्ने</li>
                <li>अनधिकृत प्रयोग भएमा तुरुन्तै सूचना दिने</li>
            </ul>

            <h2>४. भुक्तानी र योजना</h2>
            <p>
                - योजना शुल्क मासिक वा वार्षिक आधारमा लिइन्छ।<br>
                - भुक्तानी असफल भएमा, सेवा ७ दिनपछि निलम्बित गरिन सक्छ।<br>
                - कुनै योजनाबाट रद्दीकरण गर्दा, पहिले भुक्तानी गरिएको रकम फिर्ता गरिँदैन।
            </p>

            <h2>५. बौद्धिक सम्पदा</h2>
            <p>
                HostelHub को सबै डिजाइन, कोड, लोगो, र ब्रान्डिङ नेपालमा दर्ता कापीराइट भएको छ। तपाइँले यसलाई बिना अनुमति प्रयोग, पुन:उत्पादन वा बिक्री गर्न सक्नुहुन्न।
            </p>

            <h2>६. सेवा नीति परिवर्तन</h2>
            <p>
                हामी कानूनी आवश्यकता, सुविधा विस्तार, वा सुरक्षा कारणले यी सर्तहरू परिवर्तन गर्न सक्छौं। परिवर्तन पछि यहाँ अद्यावधिक गरिनेछ र उल्लेखनीय परिवर्तनको लागि ईमेल सूचना पठाइनेछ।
            </p>

            <h2>७. जिम्मेवारी सीमित</h2>
            <p>
                - हामी तकनीकी त्रुटि, डाटा हराउने, वा अस्थायी सेवा बाधाको लागि सीमित जिम्मेवार छौं।<br>
                - तपाइँले आफ्नो डाटा नियमित रूपमा ब्याकअप गर्नुपर्छ।
            </p>

            <h2>८. सम्पर्क गर्नुहोस्</h2>
            <p>
                यदि तपाइँसँग सेवा सर्तहरू बारे कुनै प्रश्न छ भने, हामीलाई सम्पर्क गर्नुहोस्:
            </p>
            
            <div class="contact-plain">
                <strong>ईमेल:</strong> <a href="mailto:legal@hostelhub.com">legal@hostelhub.com</a><br>
                <strong>ठेगाना:</strong> कमलपोखरी, काठमाडौं, नेपाल
            </div>
        </div>
    </section>

    <!-- 🚨 UPDATED CTA SECTION - FIXED BORDER ISSUE -->
    <div class="terms-cta-wrapper">
        <section class="terms-cta-section">
            <h2>सुरक्षित र विश्वसनीय सेवा</h2>
            <p>हामी तपाइँको व्यवसायलाई नियम र पारदर्शिताका आधारमा सहयोग गर्छौं।</p>
            
            <div class="terms-cta-buttons-container">
                <!-- BUTTON 1: PRIVACY POLICY -->
                <a href="{{ route('privacy') }}" class="terms-outline-button">
                    <i class="fas fa-shield-alt"></i> गोपनीयता नीति हेर्नुहोस्
                </a>
                
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
                
                <a href="{{ $pricingRoute }}" class="terms-trial-button">
                    <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                </a>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@endpush