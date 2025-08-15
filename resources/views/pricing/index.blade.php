@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 font-weight-bold">हाम्रा योजनाहरू</h1>
        <p class="lead">तपाईंको आवश्यकताअनुसार उपयुक्त योजना छान्नुहोस्</p>
    </div>

    <div class="row justify-content-center">
        @foreach($plans as $plan)
        <div class="col-md-4 mb-4">
            <div class="card border-{{ $plan->name === 'Pro' ? 'primary' : 'light' }} h-100">
                @if($plan->name === 'Pro')
                <div class="card-header bg-primary text-white text-center">
                    <h3>{{ $plan->name }}</h3>
                    <p class="lead mb-0">रु. {{ number_format($plan->price_month / 100) }}/महिना</p>
                </div>
                @else
                <div class="card-header bg-light text-center">
                    <h3>{{ $plan->name }}</h3>
                    <p class="lead mb-0">रु. {{ number_format($plan->price_month / 100) }}/महिना</p>
                </div>
                @endif
                
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">अधिकतम {{ $plan->max_students }} विद्यार्थी</li>
                        <li class="list-group-item">अधिकतम {{ $plan->max_hostels }} होस्टल</li>
                        
                        @foreach(json_decode($plan->features, true) as $feature)
                        <li class="list-group-item">{{ $feature }}</li>
                        @endforeach
                    </ul>
                    
                    <a href="{{ route('register.organization', ['plan' => $plan->id]) }}" 
                       class="btn btn-{{ $plan->name === 'Pro' ? 'primary' : 'outline-primary' }} btn-lg btn-block">
                        योजना छान्नुहोस्
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="text-center mt-5">
        <p>७ दिन निःशुल्क परीक्षण | कुनै पनि क्रेडिट कार्ड आवश्यक छैन</p>
    </div>
</div>
@endsection