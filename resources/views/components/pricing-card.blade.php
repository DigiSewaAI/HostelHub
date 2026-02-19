@props([
    'name',
    'price',
    'studentLimit',
    'hostelLimit',
    'popular' => false,
])

<div class="pricing-card @if($popular) popular @endif">
    @if($popular)
        <div class="popular-badge nepali">लोकप्रिय</div>
    @endif
    <div class="pricing-header">
        <h3 class="pricing-title nepali">{{ $name }}</h3>
        <div class="pricing-price">रु. {{ number_format($price) }}</div>
        <div class="pricing-period nepali">/महिना</div>
    </div>
    <div class="pricing-capacity">
        <div class="capacity-item">
            <i class="fas fa-users"></i>
            <span class="nepali"><strong>विद्यार्थी सीमा:</strong> {{ $studentLimit }}</span>
        </div>
        <div class="capacity-item">
            <i class="fas fa-building"></i>
            <span class="nepali"><strong>होस्टल सीमा:</strong> {{ $hostelLimit }}</span>
        </div>
    </div>
    <div class="trial-note">
        <i class="fas fa-check-circle"></i> <span class="nepali">७ दिन निःशुल्क परीक्षण</span>
    </div>
        {{ $slot }}
</div>