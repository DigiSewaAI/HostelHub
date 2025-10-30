<?php

@extends('layouts.student')

@section('title', $circular->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header 
                    @if($circular->priority == 'urgent') bg-danger text-white
                    @elseif($circular->priority == 'normal') bg-primary text-white
                    @else bg-info text-white @endif">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-bullhorn mr-2"></i>{{ $circular->title }}
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light">{{ $circular->priority_nepali }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Circular Content -->
                    <div class="mb-4" style="font-size: 1.1em; line-height: 1.6;">
                        {!! nl2br(e($circular->content)) !!}
                    </div>

                    <!-- Metadata -->
                    <div class="row text-muted mt-4 pt-3 border-top">
                        <div class="col-md-6">
                            <small>
                                <strong>संस्था:</strong> {{ $circular->organization->name }}<br>
                                @if($circular->hostel)
                                    <strong>होस्टेल:</strong> {{ $circular->hostel->name }}<br>
                                @endif
                                <strong>लक्षित प्रयोगकर्ता:</strong> {{ $circular->audience_type_nepali }}
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small>
                                <strong>प्रकाशन मिति:</strong> 
                                @if($circular->published_at)
                                    {{ $circular->published_at->format('Y-m-d H:i') }}
                                @else
                                    {{ $circular->created_at->format('Y-m-d H:i') }}
                                @endif
                                <br>
                                <strong>सिर्जनाकर्ता:</strong> {{ $circular->creator->name }}<br>
                                @if($circular->expires_at)
                                    <strong>समाप्ति मिति:</strong> {{ $circular->expires_at->format('Y-m-d H:i') }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                यो सूचना तपाईंले 
                                @if($recipient && $recipient->read_at)
                                    {{ $recipient->read_at->format('Y-m-d H:i') }} मा पढ्नुभएको हो
                                @else
                                    अहिले पढ्नुभएको हो
                                @endif
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('student.circulars.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>सूचनाहरूमा फर्कनुहोस्
                            </a>
                            @if($circular->priority == 'urgent')
                                <button class="btn btn-warning" onclick="window.print()">
                                    <i class="fas fa-print mr-1"></i>प्रिन्ट गर्नुहोस्
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notice for Urgent Circulars -->
            @if($circular->priority == 'urgent')
            <div class="alert alert-danger mt-3">
                <h5><i class="fas fa-exclamation-triangle mr-2"></i>जरुरी सूचना</h5>
                <p class="mb-0">
                    यो जरुरी सूचना हो। कृपया यसको विषयमा ध्यान दिनुहोस् र आवश्यक कार्यहरू समयमै गर्नुहोस्।
                    कुनै प्रश्न भएमा सम्बन्धित अधिकारीसंग सम्पर्क गर्नुहोस्।
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .card-header, .card-footer, .alert {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endpush ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\student\circulars\show.blade.php ENDPATH**/ ?>