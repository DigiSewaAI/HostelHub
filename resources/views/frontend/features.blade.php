@extends('layouts.frontend')
@section('title', 'हाम्रा सुविधाहरू - HostelHub')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* 🚨 IMPORTANT: Features page spacing fix - EXACT SAME AS GALLERY PAGE */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .features-page-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }

    /* Page Header - EXACT SAME AS GALLERY PAGE HEADER */
    .features-header {
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
    
    .features-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
    }
    
    .features-header p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 700px;
        margin: 0 auto 0.75rem auto;
        line-height: 1.6;
    }

    /* Features Grid Section - SAME STRUCTURE AS GALLERY FILTERS SECTION */
    .features-grid-section {
        padding-top: 0.5rem !important;
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .feature-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
        transform: translateY(0);
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        width: 48px;
        height: 48px;
        background-color: #001F5B;
        color: white;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .feature-icon svg {
        stroke-width: 2;
    }

    .feature-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .feature-description {
        color: #4b5563;
        line-height: 1.5;
    }

    /* 🚨 CTA Section - UPDATED WITH 3 BUTTONS */
    .features-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1rem;
    }

    .features-cta-section {
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

    .features-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
    }

    .features-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    /* DEMO BUTTON (Orange Gradient) */
    .features-demo-button {
        background: linear-gradient(135deg, #FF6B6B, #FF8E53);
        color: white;
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

    .features-demo-button:hover {
        background: linear-gradient(135deg, #FF5252, #FF7A3D);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
        color: white;
    }

    /* PRIMARY TRIAL BUTTON (White Background) */
    .features-trial-button {
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
    
    .features-trial-button:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: #001F5B;
    }

    /* OUTLINE PRICING BUTTON */
    .features-outline-button {
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
    
    .features-outline-button:hover {
        background: white;
        color: #001F5B;
        transform: translateY(-2px);
    }

    .features-cta-buttons-container {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        width: 100%;
        flex-wrap: wrap;
    }

    /* Loading states */
    .features-outline-button.loading,
    .features-trial-button.loading,
    .features-demo-button.loading {
        position: relative;
        color: transparent;
    }
    
    .features-outline-button.loading::after,
    .features-trial-button.loading::after,
    .features-demo-button.loading::after {
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
    
    .features-trial-button.loading::after {
        border: 2px solid rgba(0,31,91,0.3);
        border-top-color: #001F5B;
    }
    
    .features-demo-button.loading::after {
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Mobile adjustments - EXACT SAME AS GALLERY PAGE */
    @media (max-width: 768px) {
        .features-header {
            margin: calc(60px + 0.25rem) auto 1rem auto !important;
            padding: 1.75rem 1rem;
            width: calc(100% - 2rem);
        }
        
        .features-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .features-header p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .features-grid-section {
            padding-top: 0.25rem !important;
            margin: 0 auto 1rem auto;
        }

        .features-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .features-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .features-cta-section {
            padding: 2rem 1.5rem;
        }
        
        .features-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .features-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .features-cta-buttons-container {
            margin-top: 0.75rem;
            flex-direction: column;
            gap: 0.75rem;
        }

        .features-demo-button,
        .features-trial-button,
        .features-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 160px;
            width: 100%;
            max-width: 250px;
        }
    }

    @media (max-width: 480px) {
        .features-header h1 {
            font-size: 1.75rem;
        }
        
        .features-cta-wrapper {
            padding: 0.75rem 1rem 1.25rem 1rem;
        }
        
        .features-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .features-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .features-cta-section p {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')

<div class="features-page-wrapper">
    <!-- Page Header - EXACT SAME SPACING AS GALLERY PAGE -->
    <div class="features-header">
        <h1>हाम्रा शक्तिशाली सुविधाहरू</h1>
        <p>HostelHub ले तपाइँको होस्टललाई आधुनिक, स्वचालित र दक्ष बनाउँछ।<br>
           सबै कार्यहरू एउटै सजिलो प्लेटफर्ममा एकीकृत गर्नुहोस्।</p>
    </div>

    <!-- Features Grid Section - Structured like gallery filters -->
    <section class="features-grid-section">
        <div class="features-grid">
            <!-- Feature 1 -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a3 3 0 015.356-1.857" />
                    </svg>
                </div>
                <h3 class="feature-title">विद्यार्थी व्यवस्थापन</h3>
                <p class="feature-description">
                    KYC विवरण, उपस्थिति ट्र्याकिंग, फोटो प्रोफाइल, सम्पर्क जानकारी र फीस इतिहास सहित सबै विद्यार्थी डाटा एउटै ठाउँमा प्रबन्धन गर्नुहोस्।
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="feature-title">कोठा/बेड आवंटन</h3>
                <p class="feature-description">
                    रियल-टाइम कोठा उपलब्धता, बेड भिजुअलाइजेसन, चार्ज अनुसार आवंटन र बुकिंग प्रबन्धन गर्नुहोस्। स्वचालित अलर्ट र डुप्लिकेट बुकिंग रोक्नुहोस्।
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h3 class="feature-title">भुक्तानी ट्र्याकिंग</h3>
                <p class="feature-description">
                    स्वचालित बिल जनरेसन, भुक्तानी इतिहास, बक्यौता ट्र्याकिंग र SMS/इमेल रिमाइन्डर पठाउनुहोस्। सबै वित्तीय लेनदेन सुरक्षित र ट्रेसेबल हुन्छन्।
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="feature-title">भोजन व्यवस्थापन</h3>
                <p class="feature-description">
                    साप्ताहिक मेनु योजना बनाउनुहोस्, भोजन अर्डर ट्र्याक गर्नुहोस्, र खानेकुराको इन्भेन्टरी र खर्च प्रबन्धन गर्नुहोस्। विद्यार्थीले आफैंले अर्डर गर्न सक्छन्।
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="feature-title">मोबाइल एप्प</h3>
                <p class="feature-description">
                    Android र iOS को लागि उपलब्ध मोबाइल एप्प मार्फत प्रशासक र विद्यार्थी दुवैले कहींबाट पनि कोठा स्थिति, भुक्तानी, भोजन अर्डर र उपस्थिति हेर्न सक्छन्।
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="feature-title">विश्लेषण र रिपोर्ट</h3>
                <p class="feature-description">
                    भुक्तानी प्रगति, कोठा उपयोगिता, भोजन खपत र विद्यार्थी आगमन जस्ता विस्तृत रिपोर्टहरू प्राप्त गर्नुहोस्। आगामी निर्णयहरूको लागि डाटा आधारित विश्लेषण।
                </p>
            </div>
        </div>
    </section>

    <!-- 🚨 UPDATED CTA Section with 3 BUTTONS -->
    <div class="features-cta-wrapper">
        <section class="features-cta-section">
            <h2>अहिले नै प्रयोग सुरु गर्नुहोस्</h2>
            <p>हाम्रो सबै सुविधाहरू ७ दिनको निःशुल्क परीक्षणमा अनुभव गर्नुहोस्।</p>
            
            <div class="features-cta-buttons-container">
                <!-- BUTTON 1: DEMO (Orange Gradient with play icon) -->
                <a href="{{ route('demo') }}" class="features-demo-button">
                    <i class="fas fa-play-circle"></i> डेमो हेर्नुहोस्
                </a>
                
                <!-- BUTTON 2: FREE TRIAL (Primary with rocket icon) -->
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
                        <button class="features-outline-button" disabled>
                            <i class="fas fa-check-circle"></i> पहिले नै सदस्यता
                        </button>
                    @else
                        <form action="{{ route('subscription.start-trial') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="features-trial-button">
                                <i class="fas fa-rocket"></i> निःशुल्क परीक्षण
                            </button>
                        </form>
                    @endif
                @else
                    <!-- For non-logged in users -->
                    <a href="{{ url('/register/organization/starter') }}" 
                       class="features-trial-button">
                        <i class="fas fa-rocket"></i> निःशुल्क परीक्षण
                    </a>
                @endauth
                
                <!-- BUTTON 3: PRICING (Outline with tags icon) - FIXED ROUTE -->
                <!-- Use the correct route name from your routes file -->
                @if(Route::has('pricing'))
                    <a href="{{ route('pricing') }}" 
                       class="features-outline-button">
                        <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                    </a>
                @elseif(Route::has('frontend.pricing'))
                    <a href="{{ route('frontend.pricing') }}" 
                       class="features-outline-button">
                        <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                    </a>
                @else
                    <!-- Fallback to direct URL if route not found -->
                    <a href="{{ url('/pricing') }}" 
                       class="features-outline-button">
                        <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                    </a>
                @endif
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle trial form submission on features page
    const trialForm = document.querySelector('.features-cta-section form');
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
</script>
@endpush

@endsection