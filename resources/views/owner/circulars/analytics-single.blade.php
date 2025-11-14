@extends('layouts.owner')

@section('title', $circular->title . ' - विश्लेषण')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-bar mr-2"></i>{{ $circular->title }} - विस्तृत विश्लेषण
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('owner.circulars.show', $circular) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>सूचनामा फर्कनुहोस्
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Circular Summary -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4 class="text-primary">{{ $circular->title }}</h4>
                            <p class="text-muted mb-2">
                                <strong>प्रकाशन मिति:</strong> 
                                {{ $circular->published_at ? $circular->published_at->format('Y-m-d H:i') : 'प्रकाशित भएको छैन' }}
                            </p>
                            <p class="text-muted">
                                <strong>लक्षित प्रयोगकर्ता:</strong> 
                                {{ $circular->audience_type_nepali }}
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge 
                                @if($circular->priority == 'urgent') badge-danger
                                @elseif($circular->priority == 'normal') badge-primary
                                @else badge-info @endif 
                                text-white" style="font-size: 1em; padding: 0.5em 1em; background-color: #17a2b8 !important;">
                                {{ $circular->priority_nepali }}
                            </span>
                        </div>
                    </div>

                    @php
                        // Calculate read statistics from the available data
                        $totalRecipients = $stats['total_recipients'] ?? 0;
                        $readCount = $stats['total_read'] ?? 0;
                        $unreadCount = $totalRecipients - $readCount;
                        $readRate = $totalRecipients > 0 ? round(($readCount / $totalRecipients) * 100, 1) : 0;
                        
                        // User type breakdown data
                        $userTypeBreakdown = $stats['by_user_type'] ?? collect([]);
                    @endphp

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">कुल प्राप्तकर्ता</span>
                                    <span class="info-box-number">{{ $totalRecipients }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">पढिसकेका</span>
                                    <span class="info-box-number">{{ $readCount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">नपढेका</span>
                                    <span class="info-box-number">{{ $unreadCount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-gradient-primary">
                                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">पढ्ने दर</span>
                                    <span class="info-box-number">{{ $readRate }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0" style="font-size: 1.2em; font-weight: bold;">पढ्ने प्रगति</h5>
                                </div>
                                <div class="card-body">
                                    <div class="progress-container" style="position: relative; height: 60px; background: #f8f9fa; border-radius: 10px; border: 2px solid #dee2e6; overflow: hidden;">
                                        @if($readRate > 0)
                                        <div class="progress-segment bg-success" 
                                             style="position: absolute; left: 0; width: {{ $readRate }}%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.4em; font-weight: bold; color: white; z-index: 2;">
                                            {{ $readRate }}% पढिसकेका
                                        </div>
                                        @endif
                                        @if($unreadCount > 0)
                                        <div class="progress-segment bg-warning" 
                                             style="position: absolute; left: {{ $readRate }}%; width: {{ 100 - $readRate }}%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.4em; font-weight: bold; color: #212529; z-index: 1;">
                                            {{ 100 - $readRate }}% नपढेका
                                        </div>
                                        @endif
                                    </div>
                                    @if($totalRecipients == 0)
                                    <div class="text-center mt-2">
                                        <span class="text-muted" style="font-size: 1.1em;">कुनै प्राप्तकर्ता छैन</span>
                                    </div>
                                    @endif
                                    
                                    <!-- Additional Info -->
                                    <div class="row mt-3 text-center">
                                        <div class="col-md-6 mb-2">
                                            <div class="bg-success text-white p-3 rounded shadow-sm" style="font-size: 1.2em; font-weight: bold;">
                                                <i class="fas fa-check-circle mr-2"></i>पढिसकेका: {{ $readCount }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="bg-warning text-dark p-3 rounded shadow-sm" style="font-size: 1.2em; font-weight: bold;">
                                                <i class="fas fa-clock mr-2"></i>नपढेका: {{ $unreadCount }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Statistics -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">पढ्ने तथ्याङ्क</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="readStatsChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">प्रयोगकर्ता प्रकार अनुसार</h5>
                                </div>
                                <div class="card-body">
                                    @if($userTypeBreakdown->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>प्रयोगकर्ता प्रकार</th>
                                                        <th>कुल</th>
                                                        <th>पढिसकेका</th>
                                                        <th>दर</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($userTypeBreakdown as $breakdown)
                                                        <tr>
                                                            <td>
                                                                @if($breakdown->user_type == 'student')
                                                                    विद्यार्थी
                                                                @elseif($breakdown->user_type == 'staff')
                                                                    कर्मचारी
                                                                @else
                                                                    {{ $breakdown->user_type }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $breakdown->total }}</td>
                                                            <td>{{ $breakdown->read_count }}</td>
                                                            <td>
                                                                @php
                                                                    $rate = $breakdown->total > 0 ? 
                                                                        round(($breakdown->read_count / $breakdown->total) * 100, 1) : 0;
                                                                @endphp
                                                                <span class="badge 
                                                                    @if($rate >= 80) badge-success
                                                                    @elseif($rate >= 50) badge-warning
                                                                    @else badge-danger @endif">
                                                                    {{ $rate }}%
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center">तथ्याङ्क उपलब्ध छैन</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Engagement Rate -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">सूचना सञ्चार विश्लेषण</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="display-4 text-primary">{{ $totalRecipients }}</div>
                                            <small class="text-muted">कुल प्राप्तकर्ता</small>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="display-4 text-success">{{ $readCount }}</div>
                                            <small class="text-muted">सक्रिय पाठक</small>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="display-4 text-info">{{ $stats['engagement_rate'] ?? 0 }}%</div>
                                            <small class="text-muted">सक्रियता दर</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>अन्तिम अपडेट:</strong> {{ now()->format('Y-m-d H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('owner.circulars.analytics') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-chart-pie mr-1"></i>सामान्य विश्लेषण
                            </a>
                            <a href="{{ route('owner.circulars.show', $circular) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye mr-1"></i>सूचना हेर्नुहोस्
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Read Statistics Chart
    const readCtx = document.getElementById('readStatsChart').getContext('2d');
    const readStatsChart = new Chart(readCtx, {
        type: 'doughnut',
        data: {
            labels: ['पढिसकेका', 'नपढेका'],
            datasets: [{
                data: [
                    {{ $readCount }},
                    {{ $unreadCount }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#ffc107'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'पढ्ने स्थिति'
                }
            }
        }
    });
});
</script>
@endpush