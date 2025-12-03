@extends('layouts.frontend')
@section('title', 'प्रशंसापत्रहरू - HostelHub')

@push('styles')
<style>
    /* 🚨 IMPORTANT: Testimonials page spacing fix - EXACT SAME AS GALLERY PAGE */
    main#main.main-content-global.other-page-main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .testimonials-page-wrapper {
        padding: 0;
        margin: 0;
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }

    /* Page Header - EXACT SAME AS GALLERY PAGE HEADER */
    .testimonials-header {
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
    
    .testimonials-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
    }
    
    .testimonials-header p {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 700px;
        margin: 0 auto 0.75rem auto;
        line-height: 1.6;
    }

    /* Testimonials Grid Section - SAME STRUCTURE AS GALLERY FILTERS SECTION */
    .testimonials-grid-section {
        padding-top: 0.5rem !important;
        max-width: 1200px;
        margin: 0 auto 1.5rem auto;
        width: 95%;
    }

    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .testimonial-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .testimonial-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .testimonial-image-container {
        margin-bottom: 1rem;
        text-align: center;
    }

    .testimonial-image {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .testimonial-initials {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #001F5B;
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .testimonial-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .testimonial-content {
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin: 0 0 1rem 0;
        flex-grow: 1;
    }

    .testimonial-rating {
        margin-bottom: 1rem;
        text-align: center;
    }

    .star {
        color: #fbbf24;
        font-size: 1.2rem;
    }

    .star-empty {
        color: #d1d5db;
        font-size: 1.2rem;
    }

    .rating-text {
        margin-left: 0.5rem;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .testimonial-author {
        margin-top: auto;
        font-weight: 600;
        color: #001F5B;
        font-size: 0.875rem;
        text-align: center;
        border-top: 1px solid #f3f4f6;
        padding-top: 1rem;
    }

    .no-testimonials {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .no-testimonials-icon {
        font-size: 4rem;
        color: #e5e7eb;
        margin-bottom: 1rem;
    }

    .no-testimonials h3 {
        font-size: 1.5rem;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .no-testimonials p {
        color: #6b7280;
    }

    /* 🚨 CTA Section - EXACT SAME AS GALLERY PAGE */
    .testimonials-cta-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem 1.5rem 2rem 1.5rem;
        margin-top: 1rem;
    }

    .testimonials-cta-section {
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

    .testimonials-cta-section h2 {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: white;
    }

    .testimonials-cta-section p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .testimonials-cta-buttons-container {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        width: 100%;
        flex-wrap: wrap;
    }

    .testimonials-trial-button {
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
    
    .testimonials-trial-button:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        color: #001F5B;
    }

    .testimonials-outline-button {
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
    
    .testimonials-outline-button:hover {
        background: white;
        color: #001F5B;
        transform: translateY(-2px);
    }

    /* Loading button styles */
    .testimonials-trial-button.loading,
    .testimonials-outline-button.loading {
        position: relative;
        color: transparent;
    }
    
    .testimonials-trial-button.loading::after,
    .testimonials-outline-button.loading::after {
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
    
    .testimonials-trial-button.loading::after {
        border: 2px solid rgba(0,31,91,0.3);
        border-top-color: #001F5B;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Mobile adjustments - EXACT SAME AS GALLERY PAGE */
    @media (max-width: 768px) {
        .testimonials-header {
            margin: calc(60px + 0.25rem) auto 1rem auto !important;
            padding: 1.75rem 1rem;
            width: calc(100% - 2rem);
        }
        
        .testimonials-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .testimonials-header p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .testimonials-grid-section {
            padding-top: 0.25rem !important;
            margin: 0 auto 1rem auto;
        }

        .testimonials-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .testimonials-cta-wrapper {
            padding: 1rem 1rem 1.5rem 1rem;
        }
        
        .testimonials-cta-section {
            padding: 2rem 1.5rem;
        }
        
        .testimonials-cta-section h2 {
            font-size: 1.5rem;
        }
        
        .testimonials-cta-section p {
            font-size: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .testimonials-cta-buttons-container {
            margin-top: 0.75rem;
            flex-direction: column;
        }

        .testimonials-trial-button,
        .testimonials-outline-button {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            min-width: 160px;
        }
    }

    @media (max-width: 480px) {
        .testimonials-header h1 {
            font-size: 1.75rem;
        }
        
        .testimonials-cta-wrapper {
            padding: 0.75rem 1rem 1.25rem 1rem;
        }
        
        .testimonials-cta-section {
            padding: 1.5rem 1rem;
        }
        
        .testimonials-cta-section h2 {
            font-size: 1.3rem;
        }
        
        .testimonials-cta-section p {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')

<div class="testimonials-page-wrapper">
    <!-- Page Header - EXACT SAME SPACING AS GALLERY PAGE -->
    <div class="testimonials-header">
        <h1>हाम्रा ग्राहकहरूको प्रशंसापत्र</h1>
        <p>HostelHub प्रयोग गर्ने होस्टल प्रबन्धक र मालिकहरूले के भन्छन् —<br>
           वास्तविक अनुभव, वास्तविक परिणाम।</p>
    </div>

    <!-- Testimonials Grid Section - Structured like gallery filters -->
    <section class="testimonials-grid-section">
        <div class="testimonials-grid">
            @forelse($testimonials as $testimonial)
            <div class="testimonial-card">
                <!-- Display image or initials if available -->
                <div class="testimonial-image-container">
                    @if($testimonial->image)
                    <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" class="testimonial-image">
                    @elseif($testimonial->initials)
                    <div class="testimonial-initials">
                        {{ $testimonial->initials }}
                    </div>
                    @else
                    <div class="testimonial-placeholder">
                        <i class="fas fa-user" style="font-size: 1.2rem;"></i>
                    </div>
                    @endif
                </div>
                
                <p class="testimonial-content">
                    "{{ $testimonial->content }}"
                </p>
                
                <!-- Display rating if available -->
                @if($testimonial->rating)
                <div class="testimonial-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $testimonial->rating ? 'star' : 'star-empty' }}">★</span>
                    @endfor
                    <span class="rating-text">({{ $testimonial->rating }}/5)</span>
                </div>
                @endif
                
                <div class="testimonial-author">
                    — {{ $testimonial->name }}{{ $testimonial->position ? ', ' . $testimonial->position : '' }}
                </div>
            </div>
            @empty
            <div class="no-testimonials">
                <div class="no-testimonials-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>कुनै प्रशंसापत्र उपलब्ध छैन</h3>
                <p>हामी छिट्टै नयाँ प्रशंसापत्रहरू थप्नेछौं।</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- 🚨 FIXED CTA Section - CORRECT HOSTEL REGISTRATION -->
    <div class="testimonials-cta-wrapper">
        <section class="testimonials-cta-section">
            <h2>आफैंले अनुभव गर्नुहोस्</h2>
            <p>७ दिनको निःशुल्क परीक्षणमा साइन अप गरेर तपाइँको होस्टललाई आधुनिक बनाउनुहोस्।</p>
            <div class="testimonials-cta-buttons-container">
                <a href="{{ route('demo') }}" class="testimonials-trial-button">डेमो हेर्नुहोस्</a>
                
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
                        <button class="testimonials-outline-button" disabled>
                            तपाईंसँग पहिले नै सदस्यता छ
                        </button>
                    @else
                        <form action="{{ route('subscription.start-trial') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="testimonials-outline-button">
                                निःशुल्क साइन अप गर्नुहोस्
                            </button>
                        </form>
                    @endif
                @else
                    <!-- 🚨 CORRECT ROUTE FOR HOSTEL REGISTRATION -->
                    <a href="{{ url('/register/organization/starter') }}" 
                       class="testimonials-outline-button">
                        निःशुल्क साइन अप
                    </a>
                @endauth
            </div>
        </section>
    </div>
</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle trial form submission on testimonials page
    const trialForm = document.querySelector('.testimonials-cta-section form');
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