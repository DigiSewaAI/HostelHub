@extends('layouts.frontend')
@section('title', 'प्रशंसापत्रहरू - HostelHub')
@section('content')
<div style="
  max-width: 1200px;
  margin: 0 auto;
  padding: 3rem 1.5rem;
  font-family: 'Segoe UI', sans-serif;
">

  <!-- Page Header - EXACT GALLERY CTA DESIGN -->
  <div style="
    text-align: center;
    margin-bottom: 3rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 2.5rem 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
  ">
    <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 1rem;">
      हाम्रा ग्राहकहरूको प्रशंसापत्र
    </h1>
    <p style="font-size: 1.125rem; color: rgba(255, 255, 255, 0.9); max-width: 700px; margin: 0 auto; line-height: 1.6;">
      HostelHub प्रयोग गर्ने होस्टल प्रबन्धक र मालिकहरूले के भन्छन् —<br>
      वास्तविक अनुभव, वास्तविक परिणाम।
    </p>
  </div>

  <!-- Testimonials Grid -->
  <div style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
  ">
    @forelse($testimonials as $testimonial)
    <div style="
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)';"
       onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
      
      <!-- Display image or initials if available -->
      <div style="margin-bottom: 1rem; text-align: center;">
        @if($testimonial->image)
        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
        @elseif($testimonial->initials)
        <div style="width: 60px; height: 60px; border-radius: 50%; background: #001F5B; color: white; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;">
          {{ $testimonial->initials }}
        </div>
        @else
        <div style="width: 60px; height: 60px; border-radius: 50%; background: #e5e7eb; color: #6b7280; display: inline-flex; align-items: center; justify-content: center; font-weight: bold;">
          <i class="fas fa-user" style="font-size: 1.2rem;"></i>
        </div>
        @endif
      </div>
      
      <p style="
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin: 0 0 1rem 0;
        flex-grow: 1;
      ">
        "{{ $testimonial->content }}"
      </p>
      
      <!-- Display rating if available -->
      @if($testimonial->rating)
      <div style="margin-bottom: 1rem; text-align: center;">
        @for($i = 1; $i <= 5; $i++)
          <span style="color: {{ $i <= $testimonial->rating ? '#fbbf24' : '#d1d5db' }}; font-size: 1.2rem;">★</span>
        @endfor
        <span style="margin-left: 0.5rem; color: #6b7280; font-size: 0.9rem;">({{ $testimonial->rating }}/5)</span>
      </div>
      @endif
      
      <div style="
        margin-top: auto;
        font-weight: 600;
        color: #001F5B;
        font-size: 0.875rem;
        text-align: center;
        border-top: 1px solid #f3f4f6;
        padding-top: 1rem;
      ">
        — {{ $testimonial->name }}{{ $testimonial->position ? ', ' . $testimonial->position : '' }}
      </div>
    </div>
    @empty
    <div style="
      grid-column: 1 / -1;
      text-align: center;
      padding: 3rem;
      background: white;
      border-radius: 0.75rem;
      border: 1px solid #e5e7eb;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    ">
      <div style="font-size: 4rem; color: #e5e7eb; margin-bottom: 1rem;">
        <i class="fas fa-comments"></i>
      </div>
      <h3 style="font-size: 1.5rem; color: #374151; margin-bottom: 0.5rem;">कुनै प्रशंसापत्र उपलब्ध छैन</h3>
      <p style="color: #6b7280;">हामी छिट्टै नयाँ प्रशंसापत्रहरू थप्नेछौं।</p>
    </div>
    @endforelse
  </div>

  <!-- CTA Section - EXACT COPY FROM GALLERY PAGE -->
  <div style="
    text-align: center;
    margin-top: 4rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 2.5rem 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
  ">
    <h2 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 1rem;">
      आफैंले अनुभव गर्नुहोस्
    </h2>
    <p style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9;">
      ७ दिनको निःशुल्क परीक्षणमा साइन अप गरेर तपाइँको होस्टललाई आधुनिक बनाउनुहोस्।
    </p>
    
    <!-- Buttons -->
    <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
      <a href="/register" style="
        background-color: white;
        color: #001F5B;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
      " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.transform='translateY(-2px)';"
         onmouseout="this.style.backgroundColor='white'; this.style.transform='none';">
        निःशुल्क साइन अप
      </a>
      <a href="/demo" style="
        border: 2px solid white;
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        background-color: transparent;
        transition: all 0.3s ease;
      " onmouseover="this.style.backgroundColor='white'; this.style.color='#001F5B'; this.style.transform='translateY(-2px)';"
         onmouseout="this.style.backgroundColor='transparent'; this.style.color='white'; this.style.transform='none';">
        डेमो हेर्नुहोस्
      </a>
    </div>
  </div>

</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
  /* Responsive adjustments */
  @media (max-width: 640px) {
    [style*="font-size: 2.5rem"] { font-size: 2rem !important; }
    [style*="font-size: 1.875rem"] { font-size: 1.5rem !important; }
    [style*="font-size: 1.25rem"] { font-size: 1.125rem !important; }
    [style*="font-size: 1.125rem"] { font-size: 1rem !important; }
    
    .testimonials-grid {
      grid-template-columns: 1fr !important;
    }
  }
</style>
@endsection