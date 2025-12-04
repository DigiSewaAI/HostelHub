@extends('layouts.frontend')
@section('title', 'प्रशंसापत्रहरू - HostelHub')

@push('styles')
<style>
    /* 🚨 IMPORTANT: Testimonial page spacing fix - EXACT SAME AS GALLERY PAGE */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .testimonial-page-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }

    /* Page Header - EXACT SAME AS GALLERY PAGE */
    .testimonial-header {
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
    
    .testimonial-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
    }
    
    .testimonial-header p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 800px;
        margin: 0 auto 0.75rem auto;
    }

    /* Testimonials Content */
    .testimonials-content-section {
        max-width: 1200px;
        margin: 0 auto 2rem auto;
        width: 95%;
        padding: 0 1.5rem;
    }

    .testimonials-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Testimonial Cards */
    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-top: 2rem;
    }

    .testimonial-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    }

    .testimonial-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #4b5563;
        margin-bottom: 1.5rem;
        font-style: italic;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .author-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .author-info h4 {
        font-size: 1.2rem;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .author-info p {
        color: #6b7280;
        font-size: 0.95rem;
    }

    /* 🚨 UPDATED CTA SECTION - PROFESSIONAL STRATEGY (FIXED BORDER ISSUE) */
    .testimonial-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 2rem 1.5rem 3rem 1.5rem;
        margin-top: 2rem;
        background: transparent;
    }

    .testimonial-cta-section {
        text-align: center;
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
    }

    .testimonial-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
    }

    .testimonial-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .testimonial-cta-buttons-container {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        justify-content: center;
        margin-top: 2rem;
        width: 100%;
    }

    /* Testimonial CTA Button Styles */
    .testimonial-demo-button {
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
    }
    
    .testimonial-demo-button:hover {
        background: linear-gradient(135deg, #FF5252, #FF7A3D);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
        color: white;
    }

    .testimonial-trial-button {
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
    
    .testimonial-trial-button:hover:not(:disabled) {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: var(--primary);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .testimonial-trial-button:disabled {
        background: #6c757d;
        color: white;
        cursor: not-allowed;
        transform: none;
        border: none;
    }

    .testimonial-trial-button:disabled:hover {
        background: #6c757d;
        color: white;
        transform: none;
    }

    /* Loading button styles */
    .testimonial-trial-button.loading,
    .testimonial-demo-button.loading {
        position: relative;
        color: transparent;
    }
    
    .testimonial-trial-button.loading::after,
    .testimonial-demo-button.loading::after {
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
    
    .testimonial-trial-button.loading::after {
        border: 2px solid rgba(0,31,91,0.3);
        border-top-color: #001F5B;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Empty State for No Testimonials */
    .empty-testimonials {
        text-align: center;
        padding: 4rem 2rem;
        background: #f8fafc;
        border-radius: 1rem;
        border: 2px dashed #cbd5e0;
    }

    .empty-icon {
        font-size: 4rem;
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }

    .empty-message {
        font-size: 1.2rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .empty-submessage {
        font-size: 1rem;
        color: #9ca3af;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .testimonial-header {
            margin: calc(60px + 0.25rem) auto 1rem auto !important;
            padding: 1.75rem 1rem;
            width: calc(100% - 2rem);
        }
        
        .testimonial-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .testimonial-header p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .testimonial-grid {
            grid-template-columns: 1fr;
        }

        .testimonial-cta-wrapper {
            padding: 1.5rem 1rem 2rem 1rem;
        }
        
        .testimonial-cta-section {
            padding: 2rem 1.5rem;
        }
        
        .testimonial-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .testimonial-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .testimonial-cta-buttons-container {
            margin-top: 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .testimonial-demo-button,
        .testimonial-trial-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 160px;
            width: 100%;
            max-width: 250px;
        }

        .empty-testimonials {
            padding: 3rem 1.5rem;
        }

        .empty-icon {
            font-size: 3rem;
        }
    }

    @media (max-width: 480px) {
        .testimonial-header h1 {
            font-size: 1.75rem;
        }
        
        .testimonial-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .testimonial-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .testimonial-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .testimonial-cta-section p {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<div class="testimonial-page-wrapper">
    <!-- Page Header -->
    <div class="testimonial-header">
        <h1>हाम्रा ग्राहकहरूको प्रशंसापत्र</h1>
        <p>HostelHub प्रयोग गर्ने होस्टल प्रबन्धक र मालिकहरूले के भन्छन् —</p>
        <p>वास्तविक अनुभव, वास्तविक परिणाम।</p>
    </div>

    <!-- Testimonials Content -->
    <section class="testimonials-content-section">
        <div class="testimonials-container">
            <!-- Note: यो डमी डाटा हो। वास्तविक डाटा database बाट ल्याउनुपर्छ -->
            <!-- यदि कुनै प्रशंसापत्र छैन भने empty state देखाउने -->
            
            @if(false) <!-- Database बाट प्रशंसापत्र check गर्ने -->
            <div class="testimonial-grid">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "HostelHub ले हाम्रो होस्टल व्यवस्थापन पूर्ण रूपमा बदलेको छ। अब विद्यार्थी र कोठाको ट्र्याकिंग एकदमै सजिलो भएको छ।"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">र</div>
                        <div class="author-info">
                            <h4>रमेश श्रेष्ठ</h4>
                            <p>सुन्दर होस्टल, काठमाडौं</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "मोबाइल एप्प र वेब इन्टरफेस दुवैको कम्बिनेशनले हाम्रो काम धेरै सहज बनाएको छ। भुक्तानी प्रणाली पनि अत्यन्तै सुरक्षित छ।"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">स</div>
                        <div class="author-info">
                            <h4>सीता अधिकारी</h4>
                            <p>ज्ञान होस्टल, पोखरा</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "७ दिनको निःशुल्क परीक्षण पछि हामीले तुरुन्तै प्रीमियम योजना लिएका छौं। यो साँच्चै राम्रो investment हो।"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">ह</div>
                        <div class="author-info">
                            <h4>हरि गुरुङ</h4>
                            <p>शान्ति होस्टल, चितवन</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "ग्राहक सहयोग टिमको प्रतिक्रिया एकदमै छिटो छ। कुनै समस्या आएमा तुरुन्तै समाधान गर्छन्।"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">ग</div>
                        <div class="author-info">
                            <h4>गीता शर्मा</h4>
                            <p>विद्या होस्टल, भक्तपुर</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Empty State - कुनै प्रशंसापत्र नभएको खण्डमा -->
            <div class="empty-testimonials">
                <div class="empty-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="empty-message">तपाइँ पहिलो ग्राहक बन्नुहोस्!</h3>
                <p class="empty-submessage">
                    HostelHub को सेवा प्रयोग गरेर आफ्नो अनुभव साझा गर्नुहोस्। 
                    हामी छिट्टै नयाँ प्रशंसापत्रहरू थप्नेछौं।
                </p>
            </div>
            @endif
        </div>
    </section>

    <!-- 🚨 UPDATED CTA SECTION - PROFESSIONAL STRATEGY -->
    <div class="testimonial-cta-wrapper">
        <section class="testimonial-cta-section">
            <h2>आफैंले अनुभव गर्नुहोस्</h2>
            <p>७ दिनको निःशुल्क परीक्षणमा साइन अप गरेर तपाइँको होस्टललाई आधुनिक बनाउनुहोस्।</p>
            
            <div class="testimonial-cta-buttons-container">
                <!-- BUTTON 1: DEMO (Orange Gradient) -->
                <a href="{{ route('demo') }}" class="testimonial-demo-button">
                    <i class="fas fa-play-circle"></i> डेमो हेर्नुहोस्
                </a>
                
                <!-- BUTTON 2: FREE TRIAL -->
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
                        <button class="testimonial-trial-button" disabled>
                            <i class="fas fa-check-circle"></i> तपाईंसँग पहिले नै सदस्यता छ
                        </button>
                    @else
                        <form action="{{ route('subscription.start-trial') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="testimonial-trial-button">
                                <i class="fas fa-rocket"></i> ७ दिन निःशुल्क परीक्षण
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('register.organization', ['plan' => 'starter']) }}" class="testimonial-trial-button">
                        <i class="fas fa-rocket"></i> निःशुल्क परीक्षण सुरु गर्नुहोस्
                    </a>
                @endauth
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