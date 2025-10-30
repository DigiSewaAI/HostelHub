<?php

@extends('layouts.admin')

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
                                <span class="info-box-icon"><i class="fas fa-building"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">कुल संस्थाहरू</span>
                                    <span class="info-box-number">{{ $stats['total_organizations'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">सक्रिय प्रयोगकर्ताहरू</span>
                                    <span class="info-box-number">N/A</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Organization-wise Statistics -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">संस्था-अनुसार सूचनाहरू</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>संस्था</th>
                                                    <th>कुल सूचनाहरू</th>
                                                    <th>प्रकाशित</th>
                                                    <th>मस्यौदा</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- This would be populated with actual data -->
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        विश्लेषण डाटा शीघ्रै उपलब्ध हुनेछ
                                                    </td>
                                                </tr>
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

                    <!-- Recent Activity -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">भर्खरको सूचनाहरू</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>शीर्षक</th>
                                                    <th>संस्था</th>
                                                    <th>प्राथमिकता</th>
                                                    <th>प्रकाशन मिति</th>
                                                    <th>पढ्ने दर</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Recent circulars would be listed here -->
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        भर्खरको गतिविधि डाटा शीघ्रै उपलब्ध हुनेछ
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
                data: [12, 19, 3], // Sample data
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
});
</script>
@endpush ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\admin\circulars\analytics.blade.php ENDPATH**/ ?>