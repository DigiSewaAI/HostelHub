@extends('layouts.frontend')

@section('page-title', 'हाम्रो बारेमा - HostelHub')
@section('page-header', 'हाम्रो बारेमा')
@section('page-description', 'HostelHub नेपालको अग्रणी होस्टल व्यवस्थापन प्रणाली हो जसले होस्टलहरूको दैनिक कार्यहरूलाई डिजिटल रूपमा रूपान्तरण गर्न मद्दत गर्दछ।')

@push('styles')
<style>
    /* 🚨 CRITICAL: Reset any main content padding issues */
    .about-page-main {
        padding-top: 2rem !important;
        margin-top: 0 !important;
    }
    
    .about-content-wrapper {
        padding: 0;
        margin: 0;
    }
    
    /* Pricing Hero Styles - EXACT COPY */
    .pricing-hero {
        text-align: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        margin: 20px 0;
        border-radius: 10px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    .pricing-hero h1 {
        font-size: 36px;
        margin-bottom: 15px;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        color: white;
    }
    
    .pricing-hero p {
        font-size: 18px;
        max-width: 800px;
        margin: 0 auto;
        opacity: 0.9;
        color: rgba(255, 255, 255, 0.9);
    }

    /* About Page Specific Styles */
    .about-container {
        padding: 2rem 0;
        margin-top: 0;
    }
    
    .about-intro {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
        align-items: center;
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
    
    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-bottom: 4rem;
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
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        text-align: center;
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
    
    /* 🚨 UPDATED: EXACT PRICING PAGE FAQ & CTA SECTION */
    .about-faq-section {
        background: white;
        padding: 60px 0;
        margin: 0;
        text-align: center;
    }
    
    .about-faq-content {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .about-faq-title {
        color: #1a3a8f;
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 700;
    }
    
    .about-faq-item {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    
    .about-faq-question {
        font-weight: 600;
        color: #1a3a8f;
        margin-bottom: 15px;
        font-size: 20px;
    }
    
    .about-faq-answer {
        color: #666;
        line-height: 1.6;
        font-size: 16px;
    }
    
    .about-contact-cta {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        padding: 50px 40px;
        border-radius: 15px;
        color: white;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        max-width: 800px;
        margin: 50px auto 0 auto;
    }
    
    .about-contact-cta h3 {
        margin-bottom: 20px;
        font-size: 32px;
        color: white;
        font-weight: 700;
    }
    
    .about-contact-cta p {
        font-size: 18px;
        margin-bottom: 25px;
        opacity: 0.9;
    }
    
    .about-contact-email {
        font-size: 22px;
        font-weight: 600;
        margin: 25px 0;
        display: block;
        color: #ffffff;
        text-decoration: underline;
    }
    
    .about-trial-button {
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
    
    .about-trial-button:hover {
        background: transparent;
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(255,255,255,0.2);
        border-color: #ffffff;
    }

    .about-trial-button:disabled {
        background: #6c757d;
        border-color: #6c757d;
        color: white;
        cursor: not-allowed;
        transform: none;
    }

    .about-trial-button:disabled:hover {
        background: #6c757d;
        color: white;
        transform: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .values-grid,
        .stats-grid,
        .team-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .about-intro {
            grid-template-columns: 1fr;
        }
        
        .intro-image {
            order: -1;
        }
        
        .values-grid,
        .stats-grid,
        .team-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-number {
            font-size: 2.5rem;
        }

        .about-faq-section {
            padding: 40px 0;
        }
        
        .about-contact-cta {
            padding: 40px 25px;
            margin: 40px auto 0 auto;
        }
        
        .about-contact-cta h3 {
            font-size: 26px;
        }
        
        .about-contact-cta p {
            font-size: 16px;
        }
        
        .about-trial-button {
            padding: 12px 30px;
            font-size: 16px;
        }

        .pricing-hero {
            padding: 30px 15px;
        }
        
        .pricing-hero h1 {
            font-size: 28px;
        }
        
        .pricing-hero p {
            font-size: 16px;
        }

        .about-faq-title {
            font-size: 26px;
            margin-bottom: 30px;
        }
        
        .about-faq-question {
            font-size: 18px;
        }
    }

    @media (max-width: 480px) {
        .about-contact-cta {
            padding: 30px 20px;
            margin: 30px auto 0 auto;
        }
        
        .about-contact-cta h3 {
            font-size: 22px;
        }
        
        .about-contact-email {
            font-size: 18px;
        }
        
        .pricing-hero {
            padding: 25px 10px;
        }
        
        .pricing-hero h1 {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section - EXACT SAME AS PRICING PAGE -->
<section class="pricing-hero">
    <h1>हाम्रो बारेमा</h1>
    <p>HostelHub नेपालको अग्रणी होस्टल व्यवस्थापन प्रणाली हो</p>
    <p>हाम्रो कथा, हाम्रो टिम र हाम्रो लक्ष्यहरू</p>
</section>

<div class="about-content-wrapper">
    <div class="about-container">
        <!-- Introduction Section -->
        <div class="about-intro">
            <div class="intro-content">
                <h2 class="intro-title">हाम्रो कथा</h2>
                <div class="intro-text">
                    <p>HostelHub नेपालको अग्रणी होस्टल व्यवस्थापन प्रणाली हो जसले होस्टलहरूको दैनिक कार्यहरूलाई डिजिटल रूपमा रूपान्तरण गर्न मद्दत गर्दछ। हाम्रो उद्देश्य होस्टल व्यवस्थापनलाई सजिलो, द्रुत र विश्वसनीय बनाउनु हो।</p>
                    <p>हामी २०२५ मा सुरु भएको स्टार्टअप हौं र नेपालभरि २४ भन्दा बढी होस्टलहरू जडान भइसकेका छन्। हाम्रो टिममा प्राविधिक र व्यवसायिक क्षेत्रका अनुभवी विशेषज्ञहरू छन् जसको लक्ष्य नेपाली शिक्षा क्षेत्रमा सुधार ल्याउनु हो।</p>
                </div>
            </div>
            <div class="intro-image">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80" alt="HostelHub Team" class="intro-img">
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

    <!-- 🚨 UPDATED CTA SECTION - EXACTLY LIKE PRICING PAGE (Outside container for proper spacing) -->
    <section class="about-faq-section">
        <div class="about-faq-content">
            <h2 class="about-faq-title">अझै केही जिज्ञासा छन्? सहयोग चाहिन्छ?</h2>
            
            <div class="about-faq-item">
                <div class="about-faq-question">हाम्रो सेवा कसरी सुरु गर्न सकिन्छ?</div>
                <p class="about-faq-answer">तपाईं माथिको योजनाहरू मध्ये कुनै एक छान्नुहोस् र ७ दिने निःशुल्क परीक्षण सुरु गर्नुहोस्। कुनै क्रेडिट कार्ड आवश्यक छैन।</p>
            </div>
            
            <div class="about-faq-item">
                <div class="about-faq-question">परीक्षण अवधि पछि के हुन्छ?</div>
                <p class="about-faq-answer">परीक्षण अवधि समाप्त भएपछि, तपाईंले छान्नुभएको योजनाअनुसार सेवा सञ्चालन गर्न सक्नुहुन्छ वा कुनै पनि अतिरिक्त लागत बिना रद्द गर्न सक्नुहुन्छ।</p>
            </div>
            
            <!-- CTA Section - EXACT COPY FROM PRICING PAGE -->
            <div class="about-contact-cta">
                <h3>हामीलाई सम्पर्क गर्नुहोस्</h3>
                <p>हामी तपाईंलाई सहयोग गर्न तत्पर छौं</p>
                <a href="mailto:support@hostelhub.com" class="about-contact-email">support@hostelhub.com</a>
                <div>
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
                                तपाईंसँग पहिले नै सदस्यता छ
                            </button>
                        @else
                            <form action="{{ route('subscription.start-trial') }}" method="POST" class="trial-form" style="display: inline;">
                                @csrf
                                <button type="submit" class="about-trial-button">७ दिन निःशुल्क परीक्षण सुरु गर्नुहोस्</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('register.organization', ['plan' => 'starter']) }}" class="about-trial-button">७ दिन निःशुल्क परीक्षण सुरु गर्नुहोस्</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
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