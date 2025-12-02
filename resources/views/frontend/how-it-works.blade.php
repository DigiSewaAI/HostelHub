@extends('layouts.frontend')
@section('title', 'कसरी काम गर्छ - HostelHub')

@push('styles')
<style>
    /* 🚨 IMPORTANT: How it works page spacing fix - EXACT SAME AS GALLERY PAGE */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .how-it-works-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }

    /* Page Header - EXACT SAME AS GALLERY PAGE HEADER */
    .how-it-works-header {
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
    
    .how-it-works-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
    }
    
    .how-it-works-header p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 700px;
        margin: 0 auto 0.75rem auto;
        line-height: 1.6;
    }

    /* Steps Section - SAME STRUCTURE AS GALLERY FILTERS SECTION */
    .steps-section {
        padding-top: 0.5rem !important;
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }

    .steps-timeline {
        max-width: 800px;
        margin: 0 auto;
    }

    .step-container {
        display: flex;
        flex-direction: column;
        margin-bottom: 2rem;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
        .step-container {
            flex-direction: row;
            align-items: center;
            gap: 2rem;
        }
    }

    .step-number {
        flex-shrink: 0;
        width: 3.5rem;
        height: 3.5rem;
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: white;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.25rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .step-container:hover .step-number {
        transform: scale(1.05);
    }

    .step-content {
        flex: 1;
        background: white;
        padding: 1.75rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }

    .step-container:hover .step-content {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #e5e7eb;
    }

    .step-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 0.75rem;
        transition: color 0.3s ease;
    }

    .step-container:hover .step-title {
        color: #001F5B;
    }

    .step-description {
        color: #4b5563;
        line-height: 1.6;
    }

    /* 🚨 CTA Section - EXACT SAME AS GALLERY PAGE */
    .how-it-works-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1rem;
    }

    .how-it-works-cta-section {
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

    .how-it-works-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
    }

    .how-it-works-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .how-it-works-cta-buttons-container {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        width: 100%;
        flex-wrap: wrap;
    }

    .how-it-works-trial-button {
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
        display: inline-block;
        font-size: 1rem;
        text-align: center;
    }
    
    .how-it-works-trial-button:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: #001F5B;
    }

    .how-it-works-outline-button {
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
        display: inline-block;
        font-size: 1rem;
        text-align: center;
    }
    
    .how-it-works-outline-button:hover {
        background: white;
        color: #001F5B;
        transform: translateY(-2px);
    }

    /* Mobile adjustments - EXACT SAME AS GALLERY PAGE */
    @media (max-width: 768px) {
        .how-it-works-header {
            margin: calc(60px + 0.25rem) auto 1rem auto !important;
            padding: 1.75rem 1rem;
            width: calc(100% - 2rem);
        }
        
        .how-it-works-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .how-it-works-header p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .steps-section {
            padding-top: 0.25rem !important;
            margin: 0 auto 1rem auto;
        }

        .step-number {
            width: 3rem;
            height: 3rem;
            font-size: 1.125rem;
        }

        .step-content {
            padding: 1.25rem;
        }

        .step-title {
            font-size: 1.25rem;
        }

        .how-it-works-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .how-it-works-cta-section {
            padding: 2rem 1.5rem;
        }
        
        .how-it-works-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .how-it-works-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .how-it-works-cta-buttons-container {
            margin-top: 0.75rem;
            flex-direction: column;
        }

        .how-it-works-trial-button,
        .how-it-works-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 160px;
        }
    }

    @media (max-width: 480px) {
        .how-it-works-header h1 {
            font-size: 1.75rem;
        }
        
        .how-it-works-cta-wrapper {
            padding: 0.75rem 1rem 1.25rem 1rem;
        }
        
        .how-it-works-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .how-it-works-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .how-it-works-cta-section p {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')

<div class="how-it-works-wrapper">
    <!-- Page Header - EXACT SAME SPACING AS GALLERY PAGE -->
    <div class="how-it-works-header">
        <h1>HostelHub कसरी काम गर्छ?</h1>
        <p>तपाइँको होस्टल व्यवस्थापनलाई स्वचालित बनाउने ५ सजिला चरणहरू।<br>
           केवल केही मिनेटमा सेटअप गर्नुहोस् र तुरुन्तै उत्पादकता बढाउनुहोस्।</p>
    </div>

    <!-- Steps Section - Structured like gallery filters -->
    <section class="steps-section">
        <div class="steps-timeline">
            <!-- Step 1 -->
            <div class="step-container">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3 class="step-title">साइनअप र होस्टल प्रोफाइल सेटअप</h3>
                    <p class="step-description">
                        निःशुल्क खाता बनाउनुहोस् र आफ्नो होस्टलको नाम, ठेगाना, सम्पर्क जानकारी र लोगो थप्नुहोस्। सेटअप विजार्डले तपाइँलाई चरणदरचरण सहयोग गर्छ।
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step-container">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3 class="step-title">विद्यार्थी/कोठा Import वा Add</h3>
                    <p class="step-description">
                        विद्यार्थी र कोठाहरू सीधा CSV फाइलबाट आयात गर्नुहोस् वा म्यानुअल रूपमा थप्नुहोस्। KYC विवरण, फोटो, रूम टाइप, र चार्जहरू सहित।
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step-container">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3 class="step-title">भुक्तानी, भोजन, उपस्थिति सक्षम गर्नुहोस्</h3>
                    <p class="step-description">
                        आवश्यक मोड्युलहरू सक्षम गर्नुहोस् — मासिक भुक्तानी, मेनु योजना, र उपस्थिति ट्र्याकिंग। प्रत्येक मोड्युल स्वचालित र अनुकूलन योग्य छ।
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="step-container">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3 class="step-title">मोबाइल एप शेयर गर्नुहोस्</h3>
                    <p class="step-description">
                        विद्यार्थीहरूलाई Android वा iOS एप्लिकेसनको लिंक पठाउनुहोस्। उनीहरूले आफ्नो भुक्तानी, कोठा स्थिति, भोजन अर्डर र उपस्थिति हेर्न सक्छन्।
                    </p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="step-container">
                <div class="step-number">5</div>
                <div class="step-content">
                    <h3 class="step-title">रिपोर्ट/विश्लेषणबाट ट्र्याक गर्नुहोस्</h3>
                    <p class="step-description">
                        ड्यासबोर्डबाट भुक्तानी प्रगति, कोठा उपयोग, भोजन खपत र विद्यार्थी डाटा विश्लेषण गर्नुहोस्। आगामी निर्णयहरूको लागि वास्तविक समयको अन्तर्दृष्टि प्राप्त गर्नुहोस्।
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 🚨 CTA Section - EXACT SAME SPACING AS GALLERY PAGE -->
    <div class="how-it-works-cta-wrapper">
        <section class="how-it-works-cta-section">
            <h2>अहिले नै सुरु गर्नुहोस्</h2>
            <p>तपाइँको होस्टललाई आधुनिक प्रणालीमा रूपान्तरण गर्नुहोस्।</p>
            <div class="how-it-works-cta-buttons-container">
                <a href="{{ route('register') }}" class="how-it-works-trial-button">निःशुल्क साइन अप</a>
                <a href="{{ route('demo') }}" class="how-it-works-outline-button">डेमो हेर्नुहोस्</a>
            </div>
        </section>
    </div>
</div>
@endsection