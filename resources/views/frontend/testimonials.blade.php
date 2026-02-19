@extends('layouts.frontend')
@section('title', 'प्रशंसापत्रहरू - HostelHub')

@push('styles')
<style>
.testimonial-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 3rem 1.5rem;
    border-radius: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}
.testimonial-hero h1 { font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; }
.testimonial-hero .stats { display: flex; justify-content: center; gap: 3rem; margin-top: 1.5rem; }
.stat-item { text-align: center; }
.stat-number { font-size: 2rem; font-weight: 700; line-height: 1.2; }
.stat-label { font-size: 0.95rem; opacity: 0.9; }
.section-title { font-size: 2rem; font-weight: 700; margin: 2.5rem 0 1.5rem; color: var(--primary); text-align:center; }
.testimonial-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-top: 1.5rem; }
.review-card { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); transition: all 0.3s ease; border:1px solid #e5e7eb; }
.review-card:hover { transform: translateY(-5px); box-shadow: 0 20px 30px -5px rgba(0,0,0,0.15); }
.review-header { display:flex; align-items:center; gap:1rem; margin-bottom:1rem; }
.author-avatar { width:48px; height:48px; border-radius:50%; overflow:hidden; background:linear-gradient(135deg,var(--primary),var(--secondary)); display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:1.2rem; flex-shrink:0; }
.author-avatar img { width:100%; height:100%; object-fit:cover; }
.reviewer-info h4 { font-weight:600; color:#1f2937; margin-bottom:0.2rem; font-size:1.1rem; }
.reviewer-label { color:#6b7280; font-size:0.85rem; margin:0; }
.review-rating { margin-left:auto; color:#fbbf24; display:flex; gap:0.1rem; }
.review-text { font-size:1rem; line-height:1.6; color:#4b5563; margin-bottom:1rem; font-style:italic; }
.review-date { font-size:0.85rem; color:#9ca3af; margin-top:0.5rem; text-align:right; }
.pagination-wrapper { margin-top:3rem; text-align:center; }

/* Review Form Styles */
.review-form-container { background:#f8fafc; padding:2rem; border-radius:1rem; margin:3rem auto 0; max-width:700px; width:100%; }
.compact-form-control { width:100%; padding:0.75rem; border:1px solid #e2e8f0; border-radius:0.5rem; font-size:1rem; transition:border-color 0.2s; }
.compact-form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(var(--primary-rgb),0.1); }
.rating-stars { display:flex; gap:0.5rem; align-items:center; justify-content:center; }
.rating-stars label { cursor:pointer; font-size:1.8rem; color:#cbd5e1; transition:color 0.2s; }
.rating-stars label:hover, .rating-stars label:hover ~ label { color:#fbbf24; }
@media (max-width:768px) { .testimonial-grid { grid-template-columns:1fr; } .testimonial-hero .stats { flex-direction:column; gap:1rem; } }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">

    <!-- Hero Summary -->
    <div class="testimonial-hero">
        <h1>हाम्रा प्रयोगकर्ताका अनुभवहरू</h1>
        <p class="text-xl opacity-90 max-w-2xl mx-auto">HostelHub प्रयोग गर्ने होस्टल मालिक, विद्यार्थी र व्यवस्थापकहरूको वास्तविक प्रतिक्रिया</p>
        <div class="stats">
            <div class="stat-item"><div class="stat-number">{{ $totalApproved }}</div><div class="stat-label">कुल समीक्षा</div></div>
            <div class="stat-item"><div class="stat-number">{{ number_format($avgRating, 1) }}</div><div class="stat-label">औसत मूल्यांकन</div></div>
            <div class="stat-item"><div class="stat-number">1000+</div><div class="stat-label">खुशी प्रयोगकर्ता</div></div>
        </div>
    </div>

    <!-- Testimonials Grid -->
    <div class="testimonial-grid">
        @forelse($reviews as $review)
            <div class="review-card">
                <div class="review-header">
                    <!-- Avatar -->
                    <div class="author-avatar">
                        @php
                        $imageUrl = null;
                        $useInitials = true;
                        $connectedHostel = null;

                        // 1. Student image priority
                        if($review->student_id && $review->student && $review->student->image) {
    $studentImage = $review->student->image;
    // ✅ यदि पथमा '/' छ भने पूरा पथ प्रयोग गर्नुहोस्, नभए 'students/' जोड्नुहोस् (पुरानो समर्थन)
    $studentImagePath = (str_contains($studentImage, '/')) ? $studentImage : 'students/' . $studentImage;
    if(Storage::disk('public')->exists($studentImagePath)){
        $imageUrl = asset('storage/'.$studentImagePath);
        $useInitials = false;
    }
}
                        // 2. Hostel image fallback
                        elseif($review->hostel_id && $review->hostel && $review->hostel->image) {
                            $hostelImage = $review->hostel->image;
                            $hostelImagePath = str_starts_with($hostelImage,'hostels/') ? $hostelImage : 'hostels/'.$hostelImage;
                            if(Storage::disk('public')->exists($hostelImagePath)){
                                $imageUrl = asset('storage/'.$hostelImagePath);
                                $useInitials = false;
                            }
                        }
                        // 3. Name-matched hostel
                        elseif($review->hostel_id===null){
                            $connectedHostel = \App\Models\Hostel::where('name', $review->name)->first();
                            if($connectedHostel && $connectedHostel->image){
                                $hostelImagePath = str_starts_with($connectedHostel->image,'hostels/') ? $connectedHostel->image : 'hostels/'.$connectedHostel->image;
                                if(Storage::disk('public')->exists($hostelImagePath)){
                                    $imageUrl = asset('storage/'.$hostelImagePath);
                                    $useInitials = false;
                                }
                            }
                        }
                        @endphp

                        @if(!$useInitials && $imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $review->name }}">
                        @else
                            @php
                                $name = trim($review->name);
                                $initials = 'HH';
                                if(!empty($name)){
                                    $parts = explode(' ',$name);
                                    if(count($parts)>=2){
                                        $initials = strtoupper(substr($parts[0],0,1).substr(end($parts),0,1));
                                    } else { $initials = strtoupper(substr($name,0,2)); }
                                }
                            @endphp
                            {{ $initials }}
                        @endif
                    </div>

                    <!-- Name + Label -->
                    <div class="reviewer-info">
                        <h4>{{ $review->name }}</h4>
                        <p class="reviewer-label">
                            @if($connectedHostel)
                                {{ $connectedHostel->name }}
                            @elseif($review->hostel)
                                {{ $review->hostel->name }}
                            @elseif(is_null($review->hostel_id) && is_null($review->student_id))
                                HostelHub
                            @else
                                प्रयोगकर्ता
                            @endif
                        </p>
                    </div>

                    <!-- Rating -->
                    <div class="review-rating">
                        @for($i=1;$i<=5;$i++)
                            @if($i<=$review->rating)<i class="fas fa-star"></i>@else<i class="far fa-star"></i>@endif
                        @endfor
                    </div>
                </div>

                <div class="review-text">{{ $review->comment }}</div>
                <div class="review-date">{{ $review->created_at->format('d M, Y') }}</div>
            </div>
        @empty
            <div class="text-center py-12 col-span-full"><p class="text-gray-500">अहिलेसम्म कुनै समीक्षा उपलब्ध छैन।</p></div>
        @endforelse
    </div>

    <div class="pagination-wrapper">{{ $reviews->links() }}</div>

    <!-- Review Form (HostelHub only) -->
    <div class="review-form-container">
        <h3 class="nepali" style="color: var(--primary); margin-bottom:1rem; text-align:center;">हाम्रो बारेमा प्रतिक्रिया दिनुहोस्</h3>
        @if(session('success'))
            <div class="alert alert-success" style="background:#d4edda; color:#155724; padding:0.75rem; border-radius:0.5rem; margin-bottom:1rem;">{{ session('success') }}</div>
        @endif
        <form action="{{ route('reviews.platform.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label nepali">नाम *</label>
                <input type="text" name="name" id="name" class="compact-form-control" required value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label nepali">इमेल (वैकल्पिक)</label>
                <input type="email" name="email" id="email" class="compact-form-control" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label nepali">रेटिङ *</label>
                <div class="rating-stars">
                    @for($i=1;$i<=5;$i++)
                        <input type="radio" id="star{{$i}}" name="rating" value="{{$i}}" {{ old('rating')==$i?'checked':'' }} required style="display:none;">
                        <label for="star{{$i}}"><i class="fas fa-star"></i></label>
                    @endfor
                </div>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label nepali">प्रतिक्रिया *</label>
                <textarea name="comment" id="comment" rows="4" class="compact-form-control" required>{{ old('comment') }}</textarea>
            </div>
            <input type="hidden" name="hostel_id" value="">
            <button type="submit" class="btn btn-primary nepali" style="width:100%;">पेश गर्नुहोस्</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const stars = document.querySelectorAll('.rating-stars label');
    if(stars.length){
        stars.forEach((label,index,labels)=>{
            label.addEventListener('mouseenter',()=>{
                for(let i=0;i<=index;i++) labels[i].style.color='#fbbf24';
                for(let i=index+1;i<labels.length;i++) labels[i].style.color='#cbd5e1';
            });
            label.addEventListener('mouseleave',()=>{
                let selected = document.querySelector('input[name="rating"]:checked');
                let val = selected ? selected.value : 0;
                labels.forEach((l,i)=>{ l.style.color = i<val ? '#fbbf24':'#cbd5e1'; });
            });
            label.addEventListener('click',()=>{ document.getElementById('star'+(index+1)).checked=true; });
        });
    }
});
</script>
@endpush
