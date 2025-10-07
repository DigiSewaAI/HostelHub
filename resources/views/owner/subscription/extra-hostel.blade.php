@extends('layouts.owner')

@section('title', 'अतिरिक्त होस्टेल किन्ने')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">अतिरिक्त होस्टेल किन्ने</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">उपलब्ध प्याकेजहरू</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($packages as $package)
                        <div class="col-lg-4 mb-4">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <div class="text-center">
                                        <h5 class="font-weight-bold">{{ $package['name'] }}</h5>
                                        <h2 class="text-primary">रु {{ number_format($package['price']) }}</h2>
                                        <p class="text-muted">{{ $package['hostels'] }} होस्टेल</p>
                                        <p class="small text-muted">{{ $package['duration'] }} महिनाको लागि</p>
                                        <button class="btn btn-primary btn-block">
                                            <i class="fas fa-shopping-cart"></i> किन्नुहोस्
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">हालको स्थिति</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="h4 text-gray-800">{{ $currentHostels }}</div>
                        <div class="text-muted">हालको होस्टेलहरू</div>
                        <hr>
                        <div class="h4 text-primary">{{ $subscription->hostel_limit ?? 1 }}</div>
                        <div class="text-muted">सदस्यता सीमा</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection