<?php

@extends('layouts.owner')

@section('title', $circular->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
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
                    <div class="mb-4">
                        {!! nl2br(e($circular->content)) !!}
                    </div>

                    <!-- Metadata -->
                    <div class="row text-muted">
                        <div class="col-md-6">
                            <small>
                                <strong>लक्षित प्रयोगकर्ता:</strong> 
                                {{ $circular->audience_type_nepali }}
                            </small><br>
                            <small>
                                <strong>प्रकाशन मिति:</strong> 
                                @if($circular->published_at)
                                    {{ $circular->published_at->format('Y-m-d H:i') }}
                                @else
                                    प्रकाशित भएको छैन
                                @endif
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small>
                                <strong>सिर्जनाकर्ता:</strong> {{ $circular->creator->name }}<br>
                                <strong>सिर्जना मिति:</strong> {{ $circular->created_at->format('Y-m-d H:i') }}
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('owner.circulars.edit', $circular) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit mr-1"></i>सम्पादन गर्नुहोस्
                            </a>
                            <a href="{{ route('owner.circulars.analytics.single', $circular) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-chart-bar mr-1"></i>विश्लेषण हेर्नुहोस्
                            </a>
                            <form action="{{ route('owner.circulars.destroy', $circular) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('के तपाईं यो सूचना मेटाउन निश्चित हुनुहुन्छ?')">
                                    <i class="fas fa-trash mr-1"></i>मेटाउनुहोस्
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Status Card -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle mr-2"></i>सूचना विवरण
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>स्थिति:</strong>
                        @if($circular->status == 'published')
                            <span class="badge badge-success">प्रकाशित</span>
                        @elseif($circular->status == 'draft')
                            <span class="badge badge-warning">मस्यौदा</span>
                        @else
                            <span class="badge badge-secondary">संग्रहित</span>
                        @endif
                    </div>

                    @if($circular->scheduled_at)
                    <div class="mb-3">
                        <strong>तोकिएको मिति:</strong><br>
                        {{ $circular->scheduled_at->format('Y-m-d H:i') }}
                    </div>
                    @endif

                    @if($circular->published_at)
                    <div class="mb-3">
                        <strong>प्रकाशन मिति:</strong><br>
                        {{ $circular->published_at->format('Y-m-d H:i') }}
                    </div>
                    @endif

                    @if($circular->expires_at)
                    <div class="mb-3">
                        <strong>समाप्ति मिति:</strong><br>
                        {{ $circular->expires_at->format('Y-m-d H:i') }}
                    </div>
                    @endif

                    @if($circular->status == 'draft')
                    <form action="{{ route('owner.circulars.publish', $circular) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-paper-plane mr-1"></i>प्रकाशन गर्नुहोस्
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Read Statistics Card -->
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie mr-2"></i>पढ्ने तथ्याङ्क
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-4">
                            <h3 class="text-primary">{{ $readStats['total'] }}</h3>
                            <small class="text-muted">कुल प्राप्तकर्ता</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success">{{ $readStats['read'] }}</h3>
                            <small class="text-muted">पढिसकेका</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-warning">{{ $readStats['unread'] }}</h3>
                            <small class="text-muted">नपढेका</small>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $readStats['read_percentage'] }}%;" 
                             aria-valuenow="{{ $readStats['read_percentage'] }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            {{ $readStats['read_percentage'] }}%
                        </div>
                    </div>
                    <small class="text-muted">पढ्ने दर</small>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mt-3">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt mr-2"></i>द्रुत कार्यहरू
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('owner.circulars.create') }}" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-plus mr-1"></i>नयाँ सूचना
                    </a>
                    <a href="{{ route('owner.circulars.index') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-list mr-1"></i>सबै सूचनाहरू
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\owner\circulars\show.blade.php ENDPATH**/ ?>