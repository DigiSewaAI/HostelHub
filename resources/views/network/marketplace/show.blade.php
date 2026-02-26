@extends('layouts.owner')

@section('title', $listing->title)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">ड्यासबोर्ड</a></li>
    <li class="breadcrumb-item"><a href="{{ route('network.marketplace.index') }}">बजार सूची</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $listing->title }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $listing->title }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('network.marketplace.index') }}" class="btn btn-sm btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> फिर्ता
        </a>
        @if($listing->owner_id === Auth::id())
            <a href="{{ route('network.marketplace.edit', $listing->slug) }}" class="btn btn-sm btn-primary me-2">
                <i class="fas fa-edit"></i> सम्पादन
            </a>
            <form action="{{ route('network.marketplace.destroy', $listing->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('के तपाईं यो सूची स्थायी रूपमा मेटाउन चाहनुहुन्छ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i> मेटाउनुहोस्
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- मिडिया ग्यालरी -->
        @if($listing->media->count())
            <div id="listingCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach($listing->media as $index => $media)
                        <button type="button" data-bs-target="#listingCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : '' }}" aria-label="Slide {{ $index+1 }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($listing->media as $index => $media)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ Storage::url($media->file_path) }}" class="d-block w-100" alt="{{ $listing->title }}" style="max-height: 400px; object-fit: contain; background-color: #f8f9fa;">
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
            <div class="bg-light d-flex align-items-center justify-content-center mb-4" style="height: 200px; border-radius: 0.375rem;">
                <span class="text-muted">तस्वीर छैन</span>
            </div>
        @endif

        <!-- विवरण -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">विवरण</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $listing->description }}</p>
            </div>
        </div>

        <!-- विवरण तालिका -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">विवरणहरू</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">प्रकार</th>
                        <td>
                            @switch($listing->type)
                                @case('sale') बिक्री @break
                                @case('lease') भाडा @break
                                @case('partnership') साझेदारी @break
                                @case('investment') लगानी @break
                                @default {{ $listing->type }}
                            @endswitch
                        </td>
                    </tr>
                    @if($listing->price)
                    <tr>
                        <th>मूल्य</th>
                        <td>रू. {{ number_format($listing->price, 2) }}
                            @if($listing->price_type == 'negotiable')
                                <span class="text-muted">(मोलमोलाइ हुने)</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>मात्रा</th>
                        <td>{{ $listing->quantity }}</td>
                    </tr>
                    @if($listing->category)
                    <tr>
                        <th>वर्ग</th>
                        <td>{{ $listing->category->name_np }} ({{ $listing->category->name_en }})</td>
                    </tr>
                    @endif
                    @if($listing->condition)
                    <tr>
                        <th>अवस्था</th>
                        <td>{{ $listing->condition == 'new' ? 'नयाँ' : 'प्रयोग गरिएको' }}</td>
                    </tr>
                    @endif
                    @if($listing->location)
                    <tr>
                        <th>स्थान</th>
                        <td>{{ $listing->location }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>दृश्यता</th>
                        <td>
                            @if($listing->visibility == 'private')
                                केवल मालिकहरू
                            @elseif($listing->visibility == 'both')
                                सार्वजनिक + मालिकहरू
                            @elseif($listing->visibility == 'public')
                                सार्वजनिक
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>स्थिति</th>
                        <td>
                            <span class="badge bg-{{ $listing->status === 'approved' ? 'success' : ($listing->status === 'pending' ? 'warning' : 'secondary') }}">
                                @switch($listing->status)
                                    @case('approved') स्वीकृत @break
                                    @case('pending') पेन्डिङ @break
                                    @case('sold') बिक्री भयो @break
                                    @case('closed') बन्द @break
                                    @default {{ $listing->status }}
                                @endswitch
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>हेराइ</th>
                        <td>{{ $listing->views }}</td>
                    </tr>
                    <tr>
                        <th>प्रकाशित मिति</th>
                        <td>{{ $listing->created_at->format('Y-m-d') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- मालिक जानकारी -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">मालिकको जानकारी</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $listing->owner->name }}</h5>
                @if($listing->owner->phone)
                    <p><i class="bi bi-telephone"></i> {{ $listing->owner->phone }}</p>
                @endif
                @if($listing->owner->email)
                    <p><i class="bi bi-envelope"></i> {{ $listing->owner->email }}</p>
                @endif
                @if($listing->owner_id !== Auth::id())
                    <a href="{{ route('network.marketplace.contact', $listing->id) }}" class="btn btn-primary w-100">
                        <i class="fas fa-envelope"></i> मालिकलाई सन्देश पठाउनुहोस्
                    </a>
                @endif
            </div>
        </div>

        <!-- सम्बन्धित सूचीहरू (यदि कन्ट्रोलरबाट पठाइएको छ भने) -->
        @if(isset($related) && $related->count())
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">सम्बन्धित सूचीहरू</h5>
            </div>
            <div class="list-group list-group-flush">
                @foreach($related as $rel)
                    <a href="{{ route('network.marketplace.show', $rel->slug) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            @if($rel->media->first())
                                <img src="{{ Storage::url($rel->media->first()->file_path) }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" class="me-2">
                            @else
                                <div class="bg-light me-2" style="width: 50px; height: 50px; border-radius: 4px;"></div>
                            @endif
                            <div>
                                <div class="fw-semibold">{{ $rel->title }}</div>
                                <small class="text-muted">रू. {{ number_format($rel->price) }}</small>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection