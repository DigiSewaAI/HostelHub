@php
    $image = null;
    $name = 'प्रयोगकर्ता';
    $isHostelHub = is_null($review->hostel_id);

    if ($review->student) {
        $image = $review->student->image;
        $name = $review->student->name;
    } elseif ($review->user) {
        $name = $review->user->name;
        if (!empty($review->user->avatar)) {
            $image = $review->user->avatar;
        }
    } else {
        $name = $review->name ?? 'प्रयोगकर्ता';
    }

    $words = explode(' ', trim($name));
    if (count($words) >= 2) {
        $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
    } else {
        $initials = mb_substr($name, 0, 2);
    }
    $initials = strtoupper($initials);
@endphp

<div class="testimonial-card">
    <div class="testimonial-rating">
        @for($i = 1; $i <= 5; $i++)
            @if($i <= $review->rating)
                <i class="fas fa-star"></i>
            @else
                <i class="far fa-star"></i>
            @endif
        @endfor
    </div>

    <div class="testimonial-text">
        "{{ $review->comment ?? $review->content }}"
    </div>

    <div class="testimonial-author">
        <div class="author-avatar">
            @if($image && \Storage::disk('public')->exists($image))
                <img src="{{ \Storage::url($image) }}" alt="{{ $name }}">
            @else
                {{ $initials }}
            @endif
        </div>
        <div class="author-info">
            <h4>{{ $name }}</h4>
            @if($isHostelHub)
                <span class="hostel-badge">HostelHub</span>
            @elseif($review->hostel)
                <span class="hostel-badge">{{ $review->hostel->name }}</span>
            @endif
            <p>{{ $review->created_at->format('d M, Y') }}</p>
        </div>
    </div>
</div>