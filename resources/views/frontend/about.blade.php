@extends('layouts.frontend')

@section('page-title', 'हाम्रो बारेमा - HostelHub')
@section('page-header', 'हाम्रो बारेमा')
@section('page-description', 'HostelHub नेपालको अग्रणी होस्टल व्यवस्थापन प्रणाली हो जसले होस्टलहरूको दैनिक कार्यहरूलाई डिजिटल रूपमा रूपान्तरण गर्न मद्दत गर्दछ।')

@section('content')
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

    <!-- CTA Section -->
    <div class="about-cta">
        <h2 class="cta-title">हामीसँग जडान गर्नुहोस्</h2>
        <p class="cta-text">हामी सधैं नयाँ साझेदारहरू र ग्राहकहरूको लागि खुल्ला छौं। यदि तपाईंले आफ्नो होस्टलको व्यवस्थापन सजिलो बनाउन चाहनुहुन्छ भने, हामीलाई सम्पर्क गर्न नहिच्किचाउनुहोस्।</p>
        <a href="{{ route('contact') }}" class="cta-button">सम्पर्क गर्नुहोस्</a>
    </div>
</div>

<style>
    .about-container {
        padding: 2rem 0;
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
    
    .about-cta {
        background: var(--bg-light);
        padding: 3rem 2rem;
        border-radius: var(--radius);
        text-align: center;
    }
    
    .cta-title {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 1rem;
        font-weight: 700;
    }
    
    .cta-text {
        font-size: 1.1rem;
        color: var(--text-dark);
        max-width: 700px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }
    
    .cta-button {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        padding: 1rem 2.5rem;
        border-radius: var(--radius);
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: var(--transition);
    }
    
    .cta-button:hover {
        background: linear-gradient(to right, var(--primary-dark), var(--secondary-dark));
        transform: translateY(-2px);
        box-shadow: var(--glow);
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
    }
</style>

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
@endsection