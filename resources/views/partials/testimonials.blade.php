@if($featuredTestimonials->isNotEmpty())
<section class="homepage-testimonials" style="padding: 3rem 0; background: #f8fafc;">
    <div class="container">
        <h2 class="section-title nepali">हाम्रा ग्राहकहरू के भन्छन्</h2>
        <div class="testimonial-grid-home" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            @foreach($featuredTestimonials as $testimonial)
            <div class="testimonial-card" style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="testimonial-text" style="font-style: italic; margin-bottom: 1rem;">"{{ Str::limit($testimonial->comment, 100) }}"</div>
                <div class="testimonial-author" style="display: flex; align-items: center; gap: 1rem;">
                    <div class="author-avatar" style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        {{ substr($testimonial->name, 0, 2) }}
                    </div>
                    <div>
                        <h4 style="margin: 0; color: var(--primary);">{{ $testimonial->name }}</h4>
                        @if($testimonial->type == 'review' && $testimonial->hostel)
                            <p class="hostel-name" style="margin: 0; font-size: 0.9rem; color: #6b7280;">{{ $testimonial->hostel->name }}</p>
                        @endif
                        <div class="rating" style="color: #fbbf24;">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}" style="color: {{ $i <= $testimonial->rating ? '#fbbf24' : '#cbd5e1' }};"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('testimonials') }}" class="btn btn-primary nepali">सबै प्रशंसापत्र हेर्नुहोस्</a>
        </div>
    </div>
</section>
@endif