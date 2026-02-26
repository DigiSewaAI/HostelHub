@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('public.bazar.index') }}">बजार</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $listing->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card shadow-sm">
                {{-- ग्यालरी --}}
                @if($listing->media->count() > 0)
                    <div id="listingCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($listing->media as $index => $media)
                                <button type="button" data-bs-target="#listingCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : '' }}" aria-label="Slide {{ $index+1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($listing->media as $index => $media)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/'.$media->file_path) }}" class="d-block w-100" alt="{{ $listing->title }}" style="max-height: 500px; object-fit: contain; background-color: #f8f9fa;">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                        <i class="fas fa-image fa-4x text-muted"></i>
                    </div>
                @endif

                <div class="card-body">
                    <h1 class="h3 mb-3">{{ $listing->title }}</h1>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($listing->category)
                            <span class="badge bg-info text-dark px-3 py-2">{{ $listing->category->name_np }}</span>
                        @endif
                        @if($listing->condition)
                            <span class="badge bg-{{ $listing->condition == 'new' ? 'success' : 'warning' }} text-dark px-3 py-2">
                                {{ $listing->condition == 'new' ? 'नयाँ' : 'प्रयोग गरिएको' }}
                            </span>
                        @endif
                        <span class="badge bg-secondary px-3 py-2">
                            <i class="fas fa-eye me-1"></i> {{ $listing->views }} हेराइ
                        </span>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold">विवरण</h5>
                        <p class="text-muted">{{ $listing->description }}</p>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">मूल्य</div>
                                <div class="h4 mb-0 text-primary">
                                    रु. {{ number_format($listing->price) }}
                                    @if($listing->price_type == 'negotiable')
                                        <small class="text-muted fs-6">(मोलमोलाइ हुने)</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">मात्रा</div>
                                <div class="h5 mb-0">{{ $listing->quantity }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">स्थान</div>
                                <div class="h5 mb-0">{{ $listing->location ?? 'उल्लेख छैन' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">प्रकार</div>
                                <div class="h5 mb-0">
                                    @switch($listing->type)
                                        @case('sale') बिक्री @break
                                        @case('lease') भाडा @break
                                        @case('partnership') साझेदारी @break
                                        @case('investment') लगानी @break
                                        @default {{ $listing->type }}
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <p class="text-muted small mb-0">
                            <i class="fas fa-calendar-alt me-1"></i> प्रकाशित मिति: {{ $listing->created_at->format('Y-m-d') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm sticky-lg-top" style="top: 2rem;">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-store me-2"></i> विक्रेताको जानकारी</h5>
                </div>
                <div class="card-body">
                    @if($listing->owner)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle bg-primary text-white me-3" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                {{ substr($listing->owner->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $listing->owner->name }}</h6>
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-building me-1"></i>
                                    @if($listing->owner->ownerProfile && $listing->owner->ownerProfile->hostel)
                                        {{ $listing->owner->ownerProfile->hostel->name }}
                                    @else
                                        स्वतन्त्र विक्रेता
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- सम्पर्क बटन --}}
                        @auth
                            @if(auth()->id() !== $listing->owner_id)
                                <a href="{{ route('network.marketplace.contact', $listing->id) }}" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-envelope me-2"></i> सन्देश पठाउनुहोस्
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i> सम्पर्क गर्न लगइन गर्नुहोस्
                            </a>
                        @endauth
                    @else
                        <p class="text-muted">विक्रेताको जानकारी उपलब्ध छैन।</p>
                    @endif
                </div>
            </div>

            {{-- सम्बन्धित लिस्टिङ --}}
            @if(isset($related) && $related->count() > 0)
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i> सम्बन्धित सूचीहरू</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($related as $rel)
                                <li class="list-group-item">
                                    <a href="{{ route('public.bazar.show', $rel->slug) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <div class="me-2" style="width: 50px; height: 50px; overflow: hidden; border-radius: 4px;">
                                            @if($rel->media->first())
                                                <img src="{{ asset('storage/'.$rel->media->first()->file_path) }}" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $rel->title }}</div>
                                            <div class="small text-muted">रु. {{ number_format($rel->price) }}</div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar-circle {
        background-color: #0d6efd;
        color: white;
        font-weight: bold;
    }
    .sticky-lg-top {
        z-index: 10;
    }
</style>
@endpush
@endsection