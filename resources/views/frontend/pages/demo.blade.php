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
    }
    
    .demo-hero {
        text-align: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        margin: 20px 0;
        border-radius: 10px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .demo-hero h1 {
        font-size: 36px;
        margin-bottom: 15px;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        color: white;
    }
    
    .demo-hero p {
        font-size: 18px;
        max-width: 800px;
        margin: 0 auto;
        opacity: 0.9;
        color: rgba(255, 255, 255, 0.9);
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
        margin: 40px 0;
    }
    
    .video-container {
        border-radius: 16px;
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
        padding: 15px;
        margin-top: 20px;
        border-radius: 8px;
        max-width: 900px;
        margin: 20px auto 0;
    }

    .video-options {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .video-option-btn {
        padding: 8px 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .video-option-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .video-option-btn.active {
        background: var(--secondary);
    }
    
    /* Feature Cards */
    .feature-section {
        margin: 40px 0;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin: 30px 0;
    }
    
    .feature-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 30px;
        text-align: center;
        transition: transform 0.3s ease;
        position: relative;
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 24px;
    }
    
    .feature-card h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 15px;
    }
    
    .feature-card p {
        color: #666;
        line-height: 1.6;
    }
    
    /* Steps Section */
    .steps-section {
        margin: 40px 0;
    }
    
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin: 30px 0;
    }
    
    .step-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 30px;
        text-align: center;
        transition: transform 0.3s ease;
        position: relative;
        height: 100%;
    }
    
    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .step-number {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 24px;
        font-weight: bold;
    }
    
    .step-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 15px;
    }
    
    .step-card p {
        color: #666;
        line-height: 1.6;
    }
    
    /* 🚨 PERFECT CTA SECTION - EXACT SAME AS PRICING PAGE */
    .demo-cta {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        padding: 50px 30px;
        border-radius: 10px;
        color: white;
        margin: 40px 0;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .demo-cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.05"><text x="50%" y="50%" font-size="16" text-anchor="middle" dominant-baseline="middle" fill="white">HostelHub</text></svg>');
        background-repeat: repeat;
    }
    
    .demo-cta h2 {
        font-size: 32px;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
        color: white;
    }
    
    .demo-cta p {
        font-size: 18px;
        max-width: 600px;
        margin: 0 auto 30px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .cta-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    
    .btn-primary-cta {
        display: inline-block;
        background: white;
        color: var(--primary);
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
    
    .btn-primary-cta:hover {
        background: transparent;
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(255,255,255,0.2);
        border-color: #ffffff;
    }
    
    .btn-outline-cta {
        display: inline-block;
        background: transparent;
        color: white;
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
    
    .btn-outline-cta:hover {
        background: white;
        color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(255,255,255,0.2);
    }
    
    /* Section Headers */
    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .section-header h2 {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 15px;
    }
    
    .section-header p {
        font-size: 18px;
        color: #666;
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
    
    /* 🚨 CONSISTENT RESPONSIVE DESIGN */
    @media (max-width: 768px) {
        .demo-hero h1 {
            font-size: 28px;
        }
        
        .demo-hero p {
            font-size: 16px;
        }
        
        .video-container iframe,
        .video-container video {
            height: 300px;
        }
        
        .features-grid,
        .steps-grid {
            grid-template-columns: 1fr;
        }
        
        .section-header h2 {
            font-size: 28px;
        }
        
        .section-header p {
            font-size: 16px;
        }
        
        .demo-cta h2 {
            font-size: 28px;
        }
        
        .demo-cta p {
            font-size: 16px;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-primary-cta,
        .btn-outline-cta {
            width: 100%;
            max-width: 300px;
            text-align: center;
            padding: 12px 30px;
            font-size: 16px;
        }

        .demo-cta {
            padding: 40px 20px;
            margin: 30px 0;
        }
        
        .feature-section,
        .steps-section,
        .video-section {
            margin: 30px 0;
        }
    }
    
    @media (max-width: 480px) {
        .demo-hero {
            padding: 30px 15px;
            margin: 15px 0;
        }
        
        .demo-hero h1 {
            font-size: 24px;
        }
        
        .feature-card,
        .step-card {
            padding: 20px;
        }
        
        .demo-cta {
            padding: 30px 15px;
            margin: 25px 0;
        }

        .video-options {
            flex-direction: column;
            align-items: center;
        }

        .video-option-btn {
            width: 100%;
            max-width: 200px;
        }

        .demo-cta h2 {
            font-size: 24px;
        }
        
        .demo-cta p {
            font-size: 15px;
        }
        
        .section-header h2 {
            font-size: 24px;
        }
        
        .section-header p {
            font-size: 15px;
        }
    }
</style>

<div class="demo-page-container">
    <!-- Hero Section -->
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

    <!-- 🚨 PERFECT CTA SECTION - EXACT SAME SPACING AS PRICING PAGE -->
    <section class="demo-cta">
        <h2>तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
        <p>७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्</p>
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn-primary-cta">निःशुल्क साइन अप गर्नुहोस्</a>
            <a href="{{ route('gallery') }}" class="btn-outline-cta">ग्यालरी हेर्नुहोस्</a>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple animation for cards
        const featureCards = document.querySelectorAll('.feature-card');
        const stepCards = document.querySelectorAll('.step-card');
        
        featureCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.08)';
            });
        });
        
        stepCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.08)';
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