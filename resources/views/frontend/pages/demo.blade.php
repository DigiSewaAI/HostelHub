@extends('layouts.frontend')

@section('page-title', 'HostelHub डेमो')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* 🚨 CRITICAL FIX: Consistent Spacing System */
    .demo-page-container {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }
    
    .demo-hero {
        text-align: center;
        padding: 2.5rem 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 1000px;
        width: 90%;
        margin: calc(var(--header-height, 70px) + 0.9rem) auto 1.5rem auto !important;
        position: relative;
        overflow: hidden;
    }
    
    .demo-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
    }
    
    .demo-hero p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 700px;
        margin: 0 auto 0.75rem auto;
        line-height: 1.6;
    }

    .demo-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.05"><text x="50%" y="50%" font-size="16" text-anchor="middle" dominant-baseline="middle" fill="white">HostelHub</text></svg>');
        background-repeat: repeat;
    }
    
    /* Video Section */
    .video-section {
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }
    
    .video-container {
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        max-width: 900px;
        margin: 0 auto;
        background: #000;
    }
    
    .video-container iframe,
    .video-container video {
        width: 100%;
        height: 500px;
        border: none;
        display: block;
    }
    
    .video-suggestion {
        background: #f0f9ff;
        border-left: 4px solid var(--secondary);
        padding: 1rem;
        margin-top: 1.25rem;
        border-radius: 0.75rem;
        max-width: 900px;
        margin: 1.25rem auto 0;
        font-size: 0.95rem;
    }

    .video-options {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        margin-top: 1.25rem;
        flex-wrap: wrap;
    }

    .video-option-btn {
        padding: 0.6rem 1.25rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 1.25rem;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .video-option-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .video-option-btn.active {
        background: var(--secondary);
    }
    
    /* Feature Cards */
    .feature-section {
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin: 0 auto;
    }
    
    .feature-card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s ease;
        border: 1px solid #f3f4f6;
        position: relative;
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }
    
    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: white;
        font-size: 1.5rem;
    }
    
    .feature-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.75rem;
    }
    
    .feature-card p {
        color: #4b5563;
        line-height: 1.5;
        font-size: 0.95rem;
    }
    
    /* Steps Section */
    .steps-section {
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }
    
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin: 0 auto;
    }
    
    .step-card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s ease;
        border: 1px solid #f3f4f6;
        position: relative;
        height: 100%;
    }
    
    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 20px -5px rgba(0,0,0,0.1);
    }
    
    .step-number {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .step-card h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.75rem;
    }
    
    .step-card p {
        color: #4b5563;
        line-height: 1.5;
        font-size: 0.95rem;
    }
    
    /* 🚨 FIXED CTA SECTION - EXACT SAME SPACING AS FEATURES PAGE */
    .demo-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    .demo-cta-section {
        text-align: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
    }
    
    .demo-cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.05"><text x="50%" y="50%" font-size="16" text-anchor="middle" dominant-baseline="middle" fill="white">HostelHub</text></svg>');
        background-repeat: repeat;
    }
    
    .demo-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
        position: relative;
        z-index: 1;
    }
    
    .demo-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .cta-buttons {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        width: 100%;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    
    /* TRIAL BUTTON - SAME AS FEATURES PAGE */
    .demo-trial-button {
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
        text-align: center;
    }
    
    .demo-trial-button:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: #001F5B;
    }

    /* OUTLINE BUTTON */
    .demo-outline-button {
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
    
    .demo-outline-button:hover {
        background: white;
        color: #001F5B;
        transform: translateY(-2px);
    }

    /* Loading states - SAME AS FEATURES PAGE */
    .demo-outline-button.loading,
    .demo-trial-button.loading {
        position: relative;
        color: transparent;
    }
    
    .demo-outline-button.loading::after,
    .demo-trial-button.loading::after {
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
    
    .demo-trial-button.loading::after {
        border: 2px solid rgba(0,31,91,0.3);
        border-top-color: #001F5B;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Disabled state for already subscribed users */
    .demo-trial-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .demo-trial-button:disabled:hover {
        background: white;
        color: #001F5B;
        transform: none;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    
    /* Section Headers */
    .section-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .section-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.75rem;
    }
    
    .section-header p {
        font-size: 1.125rem;
        color: #4b5563;
        max-width: 700px;
        margin: 0 auto;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.6s ease forwards;
    }
    
    /* 🚨 CONSISTENT RESPONSIVE DESIGN - SAME AS FEATURES PAGE */
    @media (max-width: 768px) {
        .demo-hero {
            margin: calc(60px + 0.25rem) auto 1rem auto !important;
            padding: 1.75rem 1rem;
            width: calc(100% - 2rem);
        }
        
        .demo-hero h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .demo-hero p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .video-section,
        .feature-section,
        .steps-section {
            width: calc(100% - 2rem);
            margin: 0 auto 1rem auto;
        }

        .video-container iframe,
        .video-container video {
            height: 300px;
        }
        
        .features-grid,
        .steps-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .section-header h2 {
            font-size: 1.5rem;
        }
        
        .section-header p {
            font-size: 1rem;
        }
        
        .demo-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .demo-cta-section {
            padding: 2rem 1.5rem;
        }
        
        .demo-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .demo-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .cta-buttons {
            margin-top: 0.75rem;
            flex-direction: column;
            gap: 0.75rem;
        }

        .demo-trial-button,
        .demo-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 160px;
            width: 100%;
            max-width: 250px;
        }

        .video-suggestion {
            padding: 0.75rem;
            font-size: 0.85rem;
        }

        .video-options {
            flex-direction: column;
            align-items: center;
        }

        .video-option-btn {
            width: 100%;
            max-width: 200px;
        }
    }
    
    @media (max-width: 480px) {
        .demo-hero h1 {
            font-size: 1.75rem;
        }
        
        .demo-cta-wrapper {
            padding: 0.75rem 1rem 1.25rem 1rem;
        }
        
        .demo-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .demo-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .demo-cta-section p {
            font-size: 0.9rem;
        }
        
        .section-header h2 {
            font-size: 1.4rem;
        }
        
        .section-header p {
            font-size: 0.9rem;
        }
        
        .feature-card,
        .step-card {
            padding: 1.25rem;
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            font-size: 1.25rem;
        }
        
        .step-number {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }
</style>

<div class="demo-page-container">
    <!-- Hero Section - SAME SPACING AS FEATURES PAGE HEADER -->
    <section class="demo-hero">
        <h1>HostelHub को डेमो हेर्नुहोस्</h1>
        <p>हाम्रो प्रणालीको सबै सुविधाहरू निःशुल्क परीक्षण गर्नुहोस्, कुनै पनि बाध्यता बिना</p>
        <p>७ दिन निःशुल्क परीक्षण | कुनै पनि क्रेडिट कार्ड आवश्यक छैन</p>
    </section>

    <!-- Video Demo Section -->
    <section class="video-section">
        <div class="section-header">
            <h2>हाम्रो प्रणालीको भिडियो डेमो</h2>
            <p>यो भिडियोमा हामीले HostelHub को प्रमुख सुविधाहरू, प्रयोगकर्ता अनुभव, र व्यवस्थापन इन्टरफेस हेर्न सक्नुहुनेछ</p>
        </div>
        
        <div class="video-container animate-fade-in">
            <!-- YouTube Video (Default) -->
            <iframe 
                id="youtubeVideo"
                src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
            
            <!-- Local Video (Hidden by default) -->
            <video 
                id="localVideo" 
                controls 
                style="display: none;"
                poster="{{ asset('images/demo-poster.jpg') }}">
                <source src="{{ asset('videos/hostelhub-demo.mp4') }}" type="video/mp4">
                <source src="{{ asset('videos/hostelhub-demo.webm') }}" type="video/webm">
                तपाईंको ब्राउजरले भिडियोलाई सपोर्ट गर्दैन। कृपया आधुनिक ब्राउजर प्रयोग गर्नुहोस्।
            </video>
        </div>

        <!-- Video Options -->
        <div class="video-options">
            <button class="video-option-btn active" onclick="switchVideo('youtube')">
                <i class="fab fa-youtube"></i> YouTube डेमो
            </button>
            <button class="video-option-btn" onclick="switchVideo('local')">
                <i class="fas fa-play-circle"></i> लोकल डेमो
            </button>
        </div>

        <div class="video-suggestion animate-fade-in">
            <p><strong>सुझाव:</strong> पूर्ण प्रभावको लागि HD मा हेर्नुहोस् र पूर्णस्क्रीन मोडमा स्विच गर्नुहोस्।</p>
            <p><small><strong>नोट:</strong> लोकल डेमो भिडियो फाइलहरू <code>public/videos/</code> फोल्डरमा राख्नुहोस्।</small></p>
        </div>
    </section>

    <!-- Key Features Section -->
    <section class="feature-section">
        <div class="section-header">
            <h2>प्रमुख सुविधाहरू</h2>
            <p>हाम्रो प्रणालीले प्रदान गर्ने विशेष सुविधाहरू जसले तपाईंको होस्टल व्यवस्थापनलाई सजिलो बनाउँछ</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card animate-fade-in" style="animation-delay: 0.1s;">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>विद्यार्थी व्यवस्थापन</h3>
                <p>सबै विद्यार्थी विवरण एउटै ठाउँमा प्रबन्धन गर्नुहोस्, अध्ययन स्थिति, सम्पर्क जानकारी र भुक्तानी इतिहास</p>
            </div>
            
            <div class="feature-card animate-fade-in" style="animation-delay: 0.2s;">
                <div class="feature-icon">
                    <i class="fas fa-bed"></i>
                </div>
                <h3>कोठा उपलब्धता</h3>
                <p>रियल-टाइम कोठा उपलब्धता देख्नुहोस्, आवंटन गर्नुहोस् र बुकिंग प्रबन्धन गर्नुहोस्</p>
            </div>
            
            <div class="feature-card animate-fade-in" style="animation-delay: 0.3s;">
                <div class="feature-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3>भुक्तानी प्रणाली</h3>
                <p>स्वचालित भुक्तानी ट्र्याकिंग, बिल जनरेट गर्नुहोस्, रिमाइन्डर पठाउनुहोस् र वित्तीय विवरण हेर्नुहोस्</p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="steps-section">
        <div class="section-header">
            <h2>कसरी काम गर्छ</h2>
            <p>हाम्रो प्रणाली प्रयोग गर्ने सजिलो ३ चरणहरू</p>
        </div>
        
        <div class="steps-grid">
            <div class="step-card animate-fade-in" style="animation-delay: 0.1s;">
                <div class="step-number">1</div>
                <h3>खाता सिर्जना गर्नुहोस्</h3>
                <p>निःशुल्क खाताको लागि साइन अप गर्नुहोस् र आफ्नो होस्टल विवरणहरू थप्नुहोस्</p>
            </div>
            
            <div class="step-card animate-fade-in" style="animation-delay: 0.2s;">
                <div class="step-number">2</div>
                <h3>व्यवस्थापन सुरु गर्नुहोस्</h3>
                <p>विद्यार्थीहरू थप्नुहोस्, कोठा आवंटन गर्नुहोस्, र भुक्तानीहरू ट्र्याक गर्नुहोस्</p>
            </div>
            
            <div class="step-card animate-fade-in" style="animation-delay: 0.3s;">
                <div class="step-number">3</div>
                <h3>विस्तार गर्नुहोस्</h3>
                <p>हाम्रा उन्नत सुविधाहरू प्रयोग गरेर आफ्नो होस्टल व्यवसायलाई बढाउनुहोस्</p>
            </div>
        </div>
    </section>

    <!-- 🚨 FIXED CTA SECTION - EXACT SAME SPACING AS FEATURES PAGE -->
    <div class="demo-cta-wrapper">
        <section class="demo-cta-section">
            <h2>तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
            <p>७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्</p>
            
            <div class="cta-buttons">
                <!-- BUTTON 1: FREE TRIAL - SAME LOGIC AS FEATURES PAGE -->
                @auth
                    @php
                        $organizationId = session('current_organization_id');
                        $hasSubscription = false;
                        
                        if ($organizationId) {
                            $organization = \App\Models\Organization::with('subscription')->find($organizationId);
                            $hasSubscription = $organization->subscription ?? false;
                        }
                    @endphp
                    
                    @if($hasSubscription)
                        <button class="demo-outline-button" disabled>
                            <i class="fas fa-check-circle"></i> पहिले नै सदस्यता
                        </button>
                    @else
                        <form action="{{ route('subscription.start-trial') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="demo-trial-button">
                                <i class="fas fa-rocket"></i> निःशुल्क परीक्षण
                            </button>
                        </form>
                    @endif
                @else
                    <!-- For non-logged in users - Goes to organization registration -->
                    <a href="{{ url('/register/organization/starter') }}" 
                       class="demo-trial-button">
                        <i class="fas fa-rocket"></i> निःशुल्क परीक्षण
                    </a>
                @endauth
                
                <!-- BUTTON 2: PRICING -->
                @if(Route::has('pricing'))
                    <a href="{{ route('pricing') }}" class="demo-outline-button">
                        <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                    </a>
                @elseif(Route::has('frontend.pricing'))
                    <a href="{{ route('frontend.pricing') }}" class="demo-outline-button">
                        <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                    </a>
                @else
                    <a href="{{ url('/pricing') }}" class="demo-outline-button">
                        <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                    </a>
                @endif
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple animation for cards
        const featureCards = document.querySelectorAll('.feature-card');
        const stepCards = document.querySelectorAll('.step-card');
        
        featureCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 15px 20px -5px rgba(0,0,0,0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '0 10px 15px -3px rgba(0,0,0,0.1)';
            });
        });
        
        stepCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 15px 20px -5px rgba(0,0,0,0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '0 10px 15px -3px rgba(0,0,0,0.1)';
            });
        });

        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeIn 0.6s ease forwards';
                }
            });
        }, observerOptions);

        // Observe all animate-fade-in elements
        document.querySelectorAll('.animate-fade-in').forEach(el => {
            observer.observe(el);
        });

        // Handle trial form submission on demo page - SAME AS FEATURES PAGE
        const trialForm = document.querySelector('.demo-cta-section form');
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
    });

    // Video switching function
    function switchVideo(type) {
        const youtubeVideo = document.getElementById('youtubeVideo');
        const localVideo = document.getElementById('localVideo');
        const youtubeBtn = document.querySelector('.video-option-btn:nth-child(1)');
        const localBtn = document.querySelector('.video-option-btn:nth-child(2)');

        // Reset all buttons
        youtubeBtn.classList.remove('active');
        localBtn.classList.remove('active');

        if (type === 'youtube') {
            // Show YouTube, hide local
            youtubeVideo.style.display = 'block';
            localVideo.style.display = 'none';
            youtubeBtn.classList.add('active');
            
            // Pause local video if playing
            localVideo.pause();
        } else {
            // Show local, hide YouTube
            youtubeVideo.style.display = 'none';
            localVideo.style.display = 'block';
            localBtn.classList.add('active');
        }
    }

    // Auto-detect and handle local video availability
    document.addEventListener('DOMContentLoaded', function() {
        const localVideo = document.getElementById('localVideo');
        
        // Check if local video file exists and is accessible
        fetch("{{ asset('videos/hostelhub-demo.mp4') }}")
            .then(response => {
                if (!response.ok) {
                    // If local video doesn't exist, hide the local option
                    const localBtn = document.querySelector('.video-option-btn:nth-child(2)');
                    localBtn.style.display = 'none';
                    console.log('Local demo video not found. Hiding local option.');
                }
            })
            .catch(error => {
                console.log('Local demo video not available:', error);
                const localBtn = document.querySelector('.video-option-btn:nth-child(2)');
                localBtn.style.display = 'none';
            });
    });
</script>
@endsection