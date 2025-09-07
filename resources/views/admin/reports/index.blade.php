@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="fas fa-chart-bar me-2"></i>प्रतिवेदन ड्यासबोर्ड</h1>
        <div class="d-flex">
            <button class="btn btn-outline-primary me-2">
                <i class="fas fa-download me-1"></i> PDF डाउनलोड
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-print me-1"></i> प्रिन्ट गर्नुहोस्
            </button>
        </div>
    </div>

    <!-- मुख्य तथ्याङ्क -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">कुल विद्यार्थी</h5>
                            <h2 class="card-text mb-0">{{ $reportData['student_registrations'] }}</h2>
                            <small>हजार बढ्दो</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">अधिभृत कोठाहरू</h5>
                            <h2 class="card-text mb-0">{{ $reportData['room_occupancy'] }}</h2>
                            <small>कुल कोठाको {{ round(($reportData['room_occupancy']/$reportData['total_rooms'])*100, 2) }}%</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-bed fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">कुल आय</h5>
                            <h2 class="card-text mb-0">रु. {{ number_format($reportData['revenue'], 2) }}</h2>
                            <small>यस महिना: रु. {{ number_format($reportData['monthly_revenue'] ?? 0, 2) }}</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">उपलब्ध कोठाहरू</h5>
                            <h2 class="card-text mb-0">{{ $reportData['available_rooms'] ?? 0 }}</h2>
                            <small>कुल कोठाको {{ round(($reportData['available_rooms']/$reportData['total_rooms'])*100, 2) }}%</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-door-open fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- चार्ट र विस्तृत प्रतिवेदन -->
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>मासिक आय र आरक्षण</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>कोठा स्थिति</h5>
                </div>
                <div class="card-body">
                    <canvas id="roomStatusChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- तालिका प्रतिवेदन -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>भर्खरका भुक्तानीहरू</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">सबै हेर्नुहोस्</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>विद्यार्थी</th>
                                    <th>मिति</th>
                                    <th>रकम</th>
                                    <th>विधि</th>
                                    <th>स्थिति</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData['recent_payments'] ?? [] as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment['student_name'] }}</td>
                                    <td>{{ $payment['date'] }}</td>
                                    <td>रु. {{ number_format($payment['amount'], 2) }}</td>
                                    <td>{{ $payment['method'] }}</td>
                                    <td><span class="badge bg-success">{{ $payment['status'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- चार्ट जाभास्क्रिप्ट -->
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // आय चार्ट
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['जनवरी', 'फेब्रुअरी', 'मार्च', 'अप्रिल', 'मे', 'जुन', 'जुलाई', 'अगस्ट', 'सेप्टेम्बर', 'अक्टोबर', 'नोभेम्बर', 'डिसेम्बर'],
            datasets: [{
                label: 'मासिक आय (रु.)',
                data: [120000, 190000, 150000, 180000, 220000, 210000, 250000, 280000, 300000, 320000, 350000, 400000],
                borderColor: '#0d6efd',
                tension: 0.1,
                fill: true,
                backgroundColor: 'rgba(13, 110, 253, 0.1)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'मासिक आय प्रवृत्ति'
                },
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // कोठा स्थिति चार्ट
    const roomStatusCtx = document.getElementById('roomStatusChart').getContext('2d');
    const roomStatusChart = new Chart(roomStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['अधिभृत', 'उपलब्ध', 'आरक्षित', 'मर्मतमा'],
            datasets: [{
                data: [65, 15, 10, 10],
                backgroundColor: [
                    '#0d6efd',
                    '#198754',
                    '#ffc107',
                    '#dc3545'
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
                    text: 'कोठा वितरण'
                }
            }
        }
    });
</script>
@endsection

<style>
.card {
    border-radius: 12px;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.table th {
    font-weight: 600;
    border-top: none;
}
</style>
@endsection