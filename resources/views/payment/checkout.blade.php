@extends('layouts.app')

@section('title', 'भुक्तानी विधि छनौट गर्नुहोस्')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 nepali">भुक्तानी विधि छनौट गर्नुहोस्</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- eSewa -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <img src="{{ asset('images/esewa-logo.png') }}" alt="eSewa" class="img-fluid" style="height: 50px;">
                                    </div>
                                    <h6 class="nepali">eSewa</h6>
                                    <form action="{{ route('payment.esewa.pay') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount" value="{{ $amount }}">
                                        <input type="hidden" name="purchase_type" value="{{ $purpose }}">
                                        <input type="hidden" name="payment_id" value="{{ $paymentId ?? '' }}">
                                        <button type="submit" class="btn btn-outline-primary btn-sm nepali mt-2">
                                            eSewa मार्फत भुक्तानी गर्नुहोस्
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Khalti -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <img src="{{ asset('images/khalti-logo.png') }}" alt="Khalti" class="img-fluid" style="height: 50px;">
                                    </div>
                                    <h6 class="nepali">खल्ती</h6>
                                    <form action="{{ route('payment.khalti.pay') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount" value="{{ $amount }}">
                                        <input type="hidden" name="purchase_type" value="{{ $purpose }}">
                                        <input type="hidden" name="payment_id" value="{{ $paymentId ?? '' }}">
                                        <button type="submit" class="btn btn-outline-danger btn-sm nepali mt-2">
                                            खल्ती मार्फत भुक्तानी गर्नुहोस्
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-university fa-3x text-info"></i>
                                    </div>
                                    <h6 class="nepali">बैंक हस्तान्तरण</h6>
                                    <a href="{{ route('payment.bank.form', ['amount' => $amount, 'purpose' => $purpose, 'booking_id' => $bookingId, 'plan_id' => $planId]) }}" 
                                       class="btn btn-outline-info btn-sm nepali mt-2">
                                        बैंक हस्तान्तरण गर्नुहोस्
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="nepali">भुक्तानी विवरण:</h6>
                        <div class="row">
                            <div class="col-6 nepali">रकम:</div>
                            <div class="col-6 text-end fw-bold">रु {{ number_format($amount, 2) }}</div>
                            
                            <div class="col-6 nepali">उद्देश्य:</div>
                            <div class="col-6 text-end">
                                @if($purpose === 'booking')
                                    कोठा बुकिंग
                                @elseif($purpose === 'subscription')
                                    सदस्यता शुल्क
                                @else
                                    अतिरिक्त होस्टल
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection