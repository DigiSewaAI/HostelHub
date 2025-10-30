<?php

@extends('layouts.owner')

@section('title', 'मेरा सूचनाहरू')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-bullhorn mr-2"></i>मेरा सूचनाहरू
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('owner.circulars.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus mr-1"></i>नयाँ सूचना
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-bullhorn"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">कुल सूचनाहरू</span>
                                    <span class="info-box-number">{{ $circulars->total() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-paper-plane"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">प्रकाशित</span>
                                    <span class="info-box-number">
                                        {{ $circulars->where('status', 'published')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-edit"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">मस्यौदा</span>
                                    <span class="info-box-number">
                                        {{ $circulars->where('status', 'draft')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">जरुरी सूचनाहरू</span>
                                    <span class="info-box-number">
                                        {{ $circulars->where('priority', 'urgent')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" class="form-inline">
                                <div class="form-group mr-2 mb-2">
                                    <select name="status" class="form-control form-control-sm">
                                        <option value="">सबै स्थिति</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>मस्यौदा</option>
                                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>प्रकाशित</option>
                                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>संग्रहित</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mr-2 mb-2">
                                    <select name="priority" class="form-control form-control-sm">
                                        <option value="">सबै प्राथमिकता</option>
                                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>जरुरी</option>
                                        <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>सामान्य</option>
                                        <option value="info" {{ request('priority') == 'info' ? 'selected' : '' }}>जानकारी</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm mb-2">
                                    <i class="fas fa-filter mr-1"></i>फिल्टर गर्नुहोस्
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Circulars Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">शीर्षक</th>
                                    <th width="10%">प्राथमिकता</th>
                                    <th width="15%">लक्षित प्रयोगकर्ता</th>
                                    <th width="10%">स्थिति</th>
                                    <th width="15%">मिति</th>
                                    <th width="15%">कार्यहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($circulars as $circular)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $circular->title }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ Str::limit($circular->content, 50) }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($circular->priority == 'urgent')
                                                <span class="badge badge-danger">जरुरी</span>
                                            @elseif($circular->priority == 'normal')
                                                <span class="badge badge-primary">सामान्य</span>
                                            @else
                                                <span class="badge badge-info">जानकारी</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $circular->audience_type_nepali }}</small>
                                        </td>
                                        <td>
                                            @if($circular->status == 'published')
                                                <span class="badge badge-success">प्रकाशित</span>
                                            @elseif($circular->status == 'draft')
                                                <span class="badge badge-warning">मस्यौदा</span>
                                            @else
                                                <span class="badge badge-secondary">संग्रहित</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                <strong>सिर्जना:</strong> {{ $circular->created_at->format('Y-m-d') }}<br>
                                                @if($circular->published_at)
                                                    <strong>प्रकाशित:</strong> {{ $circular->published_at->format('Y-m-d') }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('owner.circulars.show', $circular) }}" 
                                                   class="btn btn-info" title="हेर्नुहोस्">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('owner.circulars.edit', $circular) }}" 
                                                   class="btn btn-primary" title="सम्पादन गर्नुहोस्">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('owner.circulars.analytics.single', $circular) }}" 
                                                   class="btn btn-warning" title="विश्लेषण">
                                                    <i class="fas fa-chart-bar"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                            अहिलेसम्म कुनै पनि सूचना सिर्जना गर्नुभएको छैन<br>
                                            <a href="{{ route('owner.circulars.create') }}" class="btn btn-primary mt-2">
                                                पहिलो सूचना सिर्जना गर्नुहोस्
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $circulars->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\owner\circulars\index.blade.php ENDPATH**/ ?>