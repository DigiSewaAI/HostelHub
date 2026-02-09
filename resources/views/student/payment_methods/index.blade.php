@extends('layouts.student')

@section('title', 'भुक्तानी विधिहरू')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 nepali">
                            <i class="fas fa-credit-card me-2"></i>
                            भुक्तानी गर्ने विधिहरू
                        </h4>
                        <a href="{{ route('student.payments.history') }}" class="btn btn-light btn-sm nepali">
                            <i class="fas fa-history"></i> भुक्तानी इतिहास
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $student = auth()->user()->student;
                        $studentHostel = null;
                        $paymentMethods = [];
                        
                        if ($student) {
                            if ($student->hostel) {
                                $studentHostel = $student->hostel;
                            } elseif ($student->room && $student->room->hostel) {
                                $studentHostel = $student->room->hostel;
                            }
                            
                            if ($studentHostel) {
                                $paymentMethods = $studentHostel->bill_payment_methods ?? [];
                            }
                        }
                        
                        $hasPaymentMethods = !empty($paymentMethods);
                    @endphp

                    @if(!$studentHostel)
                        <div class="alert alert-warning text-center py-4">
                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                            <h5 class="nepali">होस्टेल भेटिएन</h5>
                            <p class="nepali">तपाईं अहिले कुनै होस्टेलमा बस्दै हुनुहुन्न।</p>
                            <a href="{{ route('hostels.index') }}" class="btn btn-primary nepali">
                                <i class="fas fa-search"></i> होस्टेल खोज्नुहोस्
                            </a>
                        </div>
                    @elseif(!$hasPaymentMethods)
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h5 class="nepali">भुक्तानी विवरण उपलब्ध छैन</h5>
                            <p class="nepali mb-3">
                                {{ $studentHostel->name }} होस्टेलले अहिलेसम्म भुक्तानी विधिहरू सेटअप गर्नुभएको छैन।
                            </p>
                            <div class="bg-light p-4 rounded">
                                <h6 class="nepali">सम्पर्क गर्नुहोस्:</h6>
                                <p class="mb-1 nepali">
                                    <strong>फोन:</strong> {{ $studentHostel->contact_phone_formatted }}
                                </p>
                                <p class="mb-1 nepali">
                                    <strong>इमेल:</strong> {{ $studentHostel->contact_email_formatted }}
                                </p>
                                <p class="mb-0 nepali">
                                    <strong>ठेगाना:</strong> {{ $studentHostel->address ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    @else
                        {{-- Hostel Info --}}
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="nepali mb-1">{{ $studentHostel->name }}</h5>
                                        <p class="text-muted nepali mb-0">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $studentHostel->address ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <a href="tel:{{ $studentHostel->contact_phone_formatted }}" 
                                           class="btn btn-outline-primary btn-sm nepali">
                                            <i class="fas fa-phone"></i> कल गर्नुहोस्
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Methods --}}
                        <h5 class="nepali mb-4 border-bottom pb-2">उपलब्ध भुक्तानी विधिहरू</h5>
                        
                        <div class="row">
                            @foreach($paymentMethods as $method)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border payment-method-card 
                                        @if($method['type'] == 'bank') bank
                                        @elseif(in_array($method['type'], ['esewa', 'khalti', 'fonepay'])) digital
                                        @elseif($method['type'] == 'cash') cash
                                        @endif">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 nepali">
                                                    <i class="fas 
                                                        @if($method['type'] == 'bank') fa-university 
                                                        @elseif(in_array($method['type'], ['esewa', 'khalti', 'fonepay'])) fa-mobile-alt 
                                                        @elseif($method['type'] == 'cash') fa-money-bill 
                                                        @else fa-credit-card @endif
                                                        me-2">
                                                    </i>
                                                    {{ $method['type_text'] }}
                                                </h6>
                                                <small class="text-muted nepali">{{ $method['title'] }}</small>
                                            </div>
                                            @if($method['is_default'])
                                                <span class="badge bg-warning nepali">मुख्य</span>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            @foreach($method['display_info']['details'] as $label => $value)
                                                @if(!empty($value))
                                                    <div class="mb-3">
                                                        <small class="text-muted nepali d-block">{{ $label }}</small>
                                                        <strong class="nepali">{{ $value }}</strong>
                                                    </div>
                                                @endif
                                            @endforeach
                                            
                                            @if(!empty($method['qr_code_url']))
                                                <div class="text-center mt-4">
                                                    <p class="small text-muted nepali mb-2">QR कोड स्क्यान गर्नुहोस्</p>
                                                    <img src="{{ $method['qr_code_url'] }}" 
                                                         class="img-fluid rounded border" 
                                                         style="max-width: 150px;">
                                                    <p class="small text-muted nepali mt-2 mb-0">
                                                        स्क्यान गरेर सिधै भुक्तानी गर्न सकिन्छ
                                                    </p>
                                                </div>
                                            @endif
                                            
                                            @if(!empty($method['instructions']))
                                                <div class="mt-4 pt-3 border-top">
                                                    <h6 class="small text-muted nepali mb-2">
                                                        <i class="fas fa-list-ol"></i> निर्देशनहरू
                                                    </h6>
                                                    <ol class="small nepali mb-0" style="padding-left: 20px;">
                                                        @foreach($method['instructions'] as $instruction)
                                                            <li>{{ $instruction }}</li>
                                                        @endforeach
                                                    </ol>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <small class="text-muted nepali">
                                                <i class="fas fa-info-circle"></i>
                                                भुक्तानी गरेपछि रसिद सुरक्षित राख्नुहोस्।
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Payment Guidelines --}}
                        <div class="card border-info mt-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0 nepali">
                                    <i class="fas fa-lightbulb"></i> भुक्तानी सुझावहरू
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="nepali mb-0">
                                    <li>मासिक भुक्तानी हरेक महिनाको पहिलो साता भित्र गर्नुहोस्</li>
                                    <li>भुक्तानी रसिद सधैं सुरक्षित राख्नुहोस्</li>
                                    <li>भुक्तानीमा कुनै समस्या भएमा तुरुन्तै जानकारी गराउनुहोस्</li>
                                    <li>भुक्तानी गर्दा तपाईंको नाम र रुम नम्बर उल्लेख गर्न नबिर्सनुहोस्</li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection