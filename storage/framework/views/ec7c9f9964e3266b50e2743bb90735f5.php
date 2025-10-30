<?php

@extends('layouts.owner')

@section('title', 'सूचना विश्लेषण')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-bar mr-2"></i>सूचना विश्लेषण
                    </h3>
                </div>

                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-bullhorn"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">कुल सूचनाहरू</span>
                                    <span class="info-box-number">{{ $stats['total_circulars'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-paper-plane"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">प्रकाशित सूचनाहरू</span>
                                    <span class="info-box-number">{{ $stats['published_circulars'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-gradient-primary">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">कुल विद्यार्थीहरू</span>
                                    <span class="info-box-number">{{ $stats['student_count'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">औसत पढ्ने दर</span>
                                    <span class="info-box-number">
                                        <?php
                                            $avgRate = $stats['total_circulars'] > 0 ? 
                                                round(($stats['total_read'] / $stats['total_recipients']) * 100, 1) : 0;
                                        ?>
                                        {{ $avgRate }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Circular Performance -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">सूचनाहरूको प्रदर्शन</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>शीर्षक</th>
                                                    <th>प्रकाशन मिति</th>
                                                    <th>पढ्ने दर</th>
                                                    <th>कार्यहरू</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentCirculars as $recentCircular)
                                                    <tr>
                                                        <td>{{ Str::limit($recentCircular->title, 30) }}</td>
                                                        <td>{{ $recentCircular->published_at->format('Y-m-d') }}</td>
                                                        <td>
                                                            <?php
                                                                $readStats = $recentCircular->recipients()
                                                                    ->selectRaw('COUNT(*) as total, SUM(is_read) as read_count')
                                                                    ->first();
                                                                $readRate = $readStats->total > 0 ? 
                                                                    round(($readStats->read_count / $readStats->total) * 100, 1) : 0;
                                                            ?>
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar bg-success" style="width: {{ $readRate }}%">
                                                                    {{ $readRate }}%
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('owner.circulars.analytics.single', $recentCircular) }}" 
                                                               class="btn btn-sm btn-info">
                                                                <i class="fas fa-chart-bar"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">प्राथमिकता-अनुसार वितरण</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="priorityChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Statistics -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">मासिक तथ्याङ्क</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlyChart" width="400" height="150"></canvas>
                                </div>
                            </div>
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
    // Priority distribution chart
    const ctx = document.getElementById('priorityChart').getContext('2d');
    const priorityChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['जरुरी', 'सामान्य', 'जानकारी'],
            datasets: [{
                data: [{{ $stats['urgent_count'] }}, {{ $stats['normal_count'] }}, {{ $stats['info_count'] }}],
                backgroundColor: [
                    '#dc3545',
                    '#007bff', 
                    '#17a2b8'
                ]
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
                    text: 'सूचनाहरूको प्राथमिकता वितरण'
                }
            }
        }
    });

    // Monthly statistics chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: ['जनवरी', 'फेब्रुअरी', 'मार्च', 'अप्रिल', 'मे', 'जुन'],
            datasets: [{
                label: 'प्रकाशित सूचनाहरू',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
});
</script>
@endpush<?php /**PATH C:\laragon\www\HostelHub\resources\views\owner\circulars\analytics.blade.php ENDPATH**/ ?>