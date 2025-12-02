@extends('layouts.frontend')
@section('title', 'गोपनीयता नीति - HostelHub')

@push('styles')
<style>
    /* 🚨 IMPORTANT: Privacy page spacing fix - EXACT SAME AS GALLERY PAGE */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .privacy-page-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }

    /* Page Header - EXACT SAME AS GALLERY PAGE HEADER */
    .privacy-header {
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
    
    .privacy-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
    }
    
    .privacy-header p {
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

    /* Content Sections - SAME STRUCTURE AS GALLERY FILTERS SECTION */
    .privacy-content-section {
        padding-top: 0.5rem !important;
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }

    .privacy-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .privacy-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #001F5B;
        margin: 2rem 0 1rem;
    }

    .privacy-content p {
        margin-bottom: 1rem;
    }

    .privacy-content ul {
        margin: 1rem 0 1rem 1.5rem;
        list-style: disc;
    }

    /* 🚨 Plain text contact info - NO WHITE CARD */
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

    /* 🚨 CTA Section - EXACT SAME AS GALLERY PAGE */
    .privacy-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1rem;
    }

    .privacy-cta-section {
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

    .privacy-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
    }

    .privacy-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .privacy-cta-buttons-container {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        width: 100%;
        flex-wrap: wrap;
    }

    .privacy-trial-button {
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
    
    .privacy-trial-button:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: #001F5B;
    }

    .privacy-outline-button {
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
    
    .privacy-outline-button:hover {
        background: white;
        color: #001F5B;
        transform: translateY(-2px);
    }

    /* Mobile adjustments - EXACT SAME AS GALLERY PAGE */
    @media (max-width: 768px) {
        .privacy-header {
            margin: calc(60px + 0.25rem) auto 1rem auto !important;
            padding: 1.75rem 1rem;
            width: calc(100% - 2rem);
        }
        
        .privacy-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .privacy-header p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .last-updated {
            margin: 0 auto 1.5rem auto;
        }

        .privacy-content-section {
            padding-top: 0.25rem !important;
            margin: 0 auto 1rem auto;
        }

        .privacy-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .privacy-cta-section {
            padding: 2rem 1.5rem;
        }
        
        .privacy-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .privacy-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .privacy-cta-buttons-container {
            margin-top: 0.75rem;
            flex-direction: column;
        }

        .privacy-trial-button,
        .privacy-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 160px;
        }
    }

    @media (max-width: 480px) {
        .privacy-header h1 {
            font-size: 1.75rem;
        }
        
        .privacy-cta-wrapper {
            padding: 0.75rem 1rem 1.25rem 1rem;
        }
        
        .privacy-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .privacy-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .privacy-cta-section p {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')

<div class="privacy-page-wrapper">
    <!-- Page Header - EXACT SAME SPACING AS GALLERY PAGE -->
    <div class="privacy-header">
        <h1>गोपनीयता नीति</h1>
        <p>HostelHub प्रयोग गर्दा तपाइँको व्यक्तिगत जानकारी कसरी संकलन, प्रयोग, र सुरक्षित गरिन्छ — हाम्रो पारदर्शी नीति।</p>
    </div>

    <!-- Last Updated - Positioned like gallery filters section -->
    <div class="last-updated">
        अन्तिम अद्यावधिक: १० जेठ, २०८२
    </div>

    <!-- Content Sections - Structured like gallery filters -->
    <section class="privacy-content-section">
        <div class="privacy-content">
            <h2>१. हामी के जानकारी संकलन गर्छौं?</h2>
            <p>
                हामी तपाइँको निम्न जानकारीहरू संकलन गर्छौं:
            </p>
            <ul>
                <li><strong>व्यक्तिगत जानकारी:</strong> नाम, ईमेल, फोन नम्बर, ठेगाना।</li>
                <li><strong>होस्टल डाटा:</strong> कोठा विवरण, विद्यार्थी विवरण, भुक्तानी इतिहास।</li>
                <li><strong>उपयोग डाटा:</strong> लगइन समय, IP ठेगाना, ब्राउजर प्रकार।</li>
            </ul>

            <h2>२. जानकारी किन संकलन गरिन्छ?</h2>
            <p>
                हामी तपाइँको जानकारी निम्न उद्देश्यका लागि प्रयोग गर्छौं:
            </p>
            <ul>
                <li>होस्टल प्रबन्धन सेवा प्रदान गर्न</li>
                <li>भुक्तानी प्रक्रिया सुचारु बनाउन</li>
                <li>ग्राहक सहयोग र सपोर्ट दिन</li>
                <li>सुरक्षा र धोखाधडी रोकथाम गर्न</li>
            </ul>

            <h2>३. डाटा सुरक्षा</h2>
            <p>
                हामी तपाइँको डाटालाई निम्न तरिकाले सुरक्षित राख्छौं:
            </p>
            <ul>
                <li>SSL/TLS एन्क्रिप्सन प्रयोग गरी डाटा संक्रमण सुरक्षित गर्ने</li>
                <li>सख्त पहुँच नियन्त्रण (Role-based access)</li>
                <li>नियमित सुरक्षा परीक्षण र अद्यावधिक</li>
            </ul>

            <h2>४. तेस्रो पक्ष सेवा प्रदायक</h2>
            <p>
                हामी तलका तेस्रो पक्ष सेवाहरू प्रयोग गर्छौं (उदाहरणका लागि):
            </p>
            <ul>
                <li><strong>भुक्तानी प्रोसेसर:</strong> Khalti, eSewa (तपाइँको कार्ड डाटा हामीसँग राखिँदैन)</li>
                <li><strong>इमेल सेवा:</strong> SMTP, Mailgun (सन्देश पठाउन)</li>
                <li><strong>एनालिटिक्स:</strong> Google Analytics (उपयोग विश्लेषणका लागि)</li>
            </ul>

            <h2>५. तपाइँको अधिकारहरू</h2>
            <p>
                तपाइँले निम्न अधिकारहरू आनन्द लिन सक्नुहुन्छ:
            </p>
            <ul>
                <li>आफ्नो डाटा हेर्न र डाउनलोड गर्न</li>
                <li>गलत डाटा सच्याउन</li>
                <li>डाटा हटाउन (डिलीट) अनुरोध गर्न</li>
                <li>डाटा संकलन बन्द गर्न अनुरोध गर्न</li>
            </ul>

            <h2>६. नीति परिवर्तन</h2>
            <p>
                हामी कानूनी आवश्यकता वा सेवा सुधारका लागि यो नीति परिवर्तन गर्न सक्छौं। परिवर्तन पछि यहाँ अद्यावधिक गरिनेछ। उल्लेखनीय परिवर्तनहरूको लागि ईमेल सूचना पनि पठाइनेछ।
            </p>

            <h2>७. सम्पर्क गर्नुहोस्</h2>
            <p>
                यदि तपाइँसँग गोपनीयता नीति बारे कुनै प्रश्न छ भने, हामीलाई यहाँ सम्पर्क गर्नुहोस्:
            </p>
            
            <!-- 🚨 Plain text without white card -->
            <div class="contact-plain">
                <strong>ईमेल:</strong> <a href="mailto:privacy@hostelhub.com">privacy@hostelhub.com</a><br>
                <strong>ठेगाना:</strong> कमलपोखरी, काठमाडौं, नेपाल
            </div>
        </div>
    </section>

    <!-- 🚨 CTA Section - EXACT SAME SPACING AS GALLERY PAGE -->
    <div class="privacy-cta-wrapper">
        <section class="privacy-cta-section">
            <h2>विश्वास र सुरक्षा</h2>
            <p>हामी तपाइँको गोपनीयतालाई गम्भीरतापूर्वक लिन्छौं।</p>
            <div class="privacy-cta-buttons-container">
                <a href="{{ route('register') }}" class="privacy-trial-button">निःशुल्क साइन अप</a>
                <a href="{{ route('terms') }}" class="privacy-outline-button">सेवा सर्तहरू हेर्नुहोस्</a>
            </div>
        </section>
    </div>
</div>
@endsection