@extends('layouts.frontend')

@section('page-title', 'हाम्रो बारेमा - HostelHub')

@push('styles')
<style>
    /* 🚨 CRITICAL: About page specific fixes */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    .about-content-wrapper {
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
    
    /* Updated Header Styles - EXACT SAME AS GALLERY PAGE */
    .about-header {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2.5rem 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 1000px;
        width: 90%;
        
        /* 🚨 EXACT SAME SPACING AS GALLERY PAGE */
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
    }

    /* About Page Specific Styles */
    .about-container {
        padding: 0 0 2rem 0;
        margin: 0 auto;
        max-width: 1200px;
        width: 100%;
        flex: 1;
    }
    
    .about-intro {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
        align-items: center;
        padding: 0 1.5rem;
        max-width: 1200px;
        margin: 0 auto 2rem auto;
        width: 95%;
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
        height: auto;
        display: block;
    }
    
    /* Platform Explanation Section */
    .platform-explanation {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 3rem 2rem;
        border-radius: 1rem;
        margin: 2rem auto 3rem auto;
        max-width: 1000px;
        width: 95%;
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
    
    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-bottom: 4rem;
        padding: 0 1.5rem;
        max-width: 1200px;
        margin: 0 auto 2rem auto;
        width: 95%;
    }
    
    .value-card {
        background: white;
        padding: 2rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        text-align: center;
        transition: var(--transition);
    }
    
    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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
    }
    
    .stats-section {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        padding: 3rem 0;
        margin-bottom: 4rem;
        border-radius: var(--radius);
        max-width: 1200px;
        margin: 0 auto 2rem auto;
        width: 95%;
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
    
    .team-section {
        margin-bottom: 4rem;
        padding: 0 1.5rem;
        max-width: 1200px;
        margin: 0 auto 2rem auto;
        width: 95%;
    }
    
    .section-title {
        text-align: center;
        font-size: 2.2rem;
        color: var(--primary);
        margin-bottom: 1rem;
        font-weight: 700;
    }
    
    .section-subtitle {
        text-align: center;
        font-size: 1.1rem;
        color: var(--text-dark);
        max-width: 600px;
        margin: 0 auto 3rem;
        line-height: 1.6;
    }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
    
    .team-member {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        text-align: center;
        transition: var(--transition);
    }
    
    .team-member:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .member-image {
        height: 250px;
        overflow: hidden;
    }
    
    .member-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }
    
    .team-member:hover .member-image img {
        transform: scale(1.05);
    }
    
    .member-name {
        font-size: 1.3rem;
        color: var(--primary);
        margin: 1.5rem 0 0.5rem;
        font-weight: 600;
    }
    
    .member-role {
        color: var(--secondary);
        margin-bottom: 1.5rem;
        font-weight: 500;
    }
    
    .member-social {
        display: flex;
        justify-content: center;
        gap: 0.8rem;
        padding: 0 0 1.5rem;
    }
    
    .member-social a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: var(--bg-light);
        border-radius: 50%;
        color: var(--primary);
        transition: var(--transition);
    }
    
    .member-social a:hover {
        background: var(--secondary);
        color: white;
    }
    
    /* 🚨 UPDATED CTA SECTION - PROFESSIONAL STRATEGY (FIXED BORDER ISSUE) */
    .about-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 2rem 1.5rem 3rem 1.5rem;
        margin-top: 2rem;
        /* 🚨 BACKGROUND REMOVED - केवल सफेद ब्याकग्राउन्ड राख्ने */
        background: transparent;
    }
    
    .about-cta-section {
        text-align: center;
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 900px;
        width: 100%;
        margin: 0 auto;
        /* 🚨 ग्रेडियन्ट केवल यो सेक्सनमा मात्र */
        background: linear-gradient(135deg, var(--primary), var(--secondary));
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
    
    /* CTA Buttons Container */
    .about-cta-buttons-container {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        width: 100%;
    }
    
    /* ABOUT CTA BUTTON STYLES */
    .about-trial-button {
        background-color: white;
        color: var(--primary);
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
    
    .about-trial-button:hover:not(:disabled) {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: var(--primary);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    
    .about-outline-button {
        background-color: transparent;
        color: white;
        border: 2px solid white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1rem;
    }
    
    .about-outline-button:hover {
        background-color: white;
        color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
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

    /* Mobile adjustments */
    @media (max-width: 1024px) {
        .values-grid,
        .stats-grid,
        .team-grid,
        .platform-features {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .about-container {
            max-width: 95%;
        }
        
        .about-cta-buttons-container {
            gap: 1rem;
        }
    }
    
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

        .about-intro {
            grid-template-columns: 1fr;
        }
        
        .intro-image {
            order: -1;
        }
        
        .values-grid,
        .stats-grid,
        .team-grid,
        .platform-features {
            grid-template-columns: 1fr;
        }
        
        .stat-number {
            font-size: 2.5rem;
        }
        
        .platform-explanation {
            padding: 2rem 1rem;
        }
        
        .about-cta-buttons-container {
            flex-direction: column;
            gap: 1rem;
        }
        
        .about-trial-button,
        .about-outline-button {
            width: 100%;
            max-width: 250px;
        }

        .about-cta-wrapper {
            padding: 1.5rem 1rem 2rem 1rem;
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
        
        .about-trial-button,
        .about-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 180px;
        }
        
        .about-intro,
        .values-grid,
        .team-section,
        .stats-section {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }

    @media (max-width: 480px) {
        .about-header h1 {
            font-size: 1.75rem;
        }
        
        .about-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
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
        
        .about-container {
            padding-bottom: 1rem;
        }
        
        .platform-explanation h3 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Updated Hero Section -->
<div class="about-header">
    <h1>हाम्रो बारेमा</h1>
    <p>HostelHub नेपालको अग्रणी Multi-Tenant होस्टल व्यवस्थापन SaaS प्लेटफर्म हो</p>
    <p>हाम्रो कथा, हाम्रो टेक्नोलोजी र हाम्रो लक्ष्यहरू</p>
</div>

<div class="about-content-wrapper">
    <div class="about-container">
        <!-- Introduction Section -->
        <div class="about-intro">
            <div class="intro-content">
                <h2 class="intro-title">हाम्रो कथा</h2>
                <div class="intro-text">
                    <p>HostelHub नेपालको पहिलो Multi-Tenant होस्टल व्यवस्थापन SaaS प्लेटफर्म हो जसले होस्टलहरूको दैनिक कार्यहरूलाई डिजिटल रूपमा रूपान्तरण गर्दछ। हाम्रो उद्देश्य होस्टल व्यवस्थापनलाई सजिलो, द्रुत र विश्वसनीय बनाउनु हो।</p>
                    <p>हामी २०२५ मा सुरु भएको स्टार्टअप हौं र नेपालभरि २४ भन्दा बढी होस्टलहरू हाम्रो प्लेटफर्ममा जडान भइसकेका छन्। हाम्रो टिममा प्राविधिक र व्यवसायिक क्षेत्रका अनुभवी विशेषज्ञहरू छन् जसको लक्ष्य नेपाली शिक्षा क्षेत्रमा सुधार ल्याउनु हो।</p>
                </div>
            </div>
            <div class="intro-image">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80" alt="HostelHub Team" class="intro-img">
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

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-count="24">0</div>
                    <div class="stat-label">होस्टलहरू</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="5000">0</div>
                    <div class="stat-label">विद्यार्थीहरू</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="15">0</div>
                    <div class="stat-label">टिम सदस्यहरू</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="3">0</div>
                    <div class="stat-label">वर्षहरू</div>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="team-section">
            <h2 class="section-title">हाम्रो टिम</h2>
            <p class="section-subtitle">हामी एक समर्पित टिम हौं जसले होस्टल व्यवस्थापनलाई नयाँ तहमा पुर्याउने लक्ष्य राखेका छौं</p>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80" alt="रमेश श्रेष्ठ">
                    </div>
                    <h3 class="member-name">रमेश श्रेष्ठ</h3>
                    <p class="member-role">संस्थापक & CEO</p>
                    <div class="member-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80" alt="सीता अधिकारी">
                    </div>
                    <h3 class="member-name">सीता अधिकारी</h3>
                    <p class="member-role">प्राविधिक प्रमुख</p>
                    <div class="member-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&q=80" alt="हरि गुरुङ">
                    </div>
                    <h3 class="member-name">हरि गुरुङ</h3>
                    <p class="member-role">व्यवसायिक विकास</p>
                    <div class="member-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="https://images.unsplash.com/photo-1567532939604-b6b5b0db1604?auto=format&fit=crop&q=80" alt="गीता शर्मा">
                    </div>
                    <h3 class="member-name">गीता शर्मा</h3>
                    <p class="member-role">ग्राहक समर्थन</p>
                    <div class="member-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🚨 UPDATED CTA SECTION - FIXED BORDER ISSUE -->
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
                                <i class="fas fa-rocket"></i> ७ दिन निःशुल्क परीक्षण
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
    // Counter animation for stats
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