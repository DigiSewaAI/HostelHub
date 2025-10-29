

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="fas fa-chart-bar me-2"></i>प्रतिवेदन ड्यासबोर्ड</h1>
        <div class="d-flex">
            <form action="<?php echo e(route('admin.reports.download.pdf')); ?>" method="POST" class="me-2">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" value="summary">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-download me-1"></i> PDF डाउनलोड
                </button>
            </form>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i> प्रिन्ट गर्नुहोस्
            </button>
        </div>
    </div>

    <!-- Date Range Selector -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form id="reportFilterForm" class="row g-3">
                        <?php echo csrf_field(); ?>
                        <div class="col-md-3">
                            <label for="report_type" class="form-label">प्रतिवेदन प्रकार</label>
                            <select class="form-select" id="report_type" name="report_type">
                                <option value="summary">सामान्य सारांश</option>
                                <option value="monthly">मासिक प्रतिवेदन</option>
                                <option value="yearly">वार्षिक प्रतिवेदन</option>
                                <option value="custom">अनुकूलित मिति</option>
                            </select>
                        </div>
                        <div class="col-md-3 monthly-selector" style="display: none;">
                            <label for="month" class="form-label">महिना</label>
                            <select class="form-select" id="month" name="month">
                                <?php $__currentLoopData = ['जनवरी', 'फेब्रुअरी', 'मार्च', 'अप्रिल', 'मे', 'जुन', 'जुलाई', 'अगस्ट', 'सेप्टेम्बर', 'अक्टोबर', 'नोभेम्बर', 'डिसेम्बर']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($index + 1); ?>" <?php echo e((now()->month == $index + 1) ? 'selected' : ''); ?>><?php echo e($month); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 yearly-selector" style="display: none;">
                            <label for="year" class="form-label">वर्ष</label>
                            <select class="form-select" id="year" name="year">
                                <?php for($y = now()->year; $y >= 2020; $y--): ?>
                                    <option value="<?php echo e($y); ?>" <?php echo e((now()->year == $y) ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3 custom-selector" style="display: none;">
                            <label for="start_date" class="form-label">सुरु मिति</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e(now()->subMonth()->format('Y-m-d')); ?>">
                        </div>
                        <div class="col-md-3 custom-selector" style="display: none;">
                            <label for="end_date" class="form-label">अन्तिम मिति</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e(now()->format('Y-m-d')); ?>">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="button" id="filterReport" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> प्रतिवेदन देखाउनुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- मुख्य तथ्याङ्क -->
    <div class="row mb-4" id="mainStats">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">कुल विद्यार्थी</h5>
                            <h2 class="card-text mb-0"><?php echo e($reportData['student_registrations']); ?></h2>
                            <small id="studentTrend">हजार बढ्दो</small>
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
                            <h2 class="card-text mb-0"><?php echo e($reportData['room_occupancy']); ?></h2>
                            <small>कुल कोठाको <?php echo e(round(($reportData['room_occupancy']/$reportData['total_rooms'])*100, 2)); ?>%</small>
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
                            <h2 class="card-text mb-0">रु. <?php echo e(number_format($reportData['revenue'], 2)); ?></h2>
                            <small>यस महिना: रु. <?php echo e(number_format($reportData['monthly_revenue'] ?? 0, 2)); ?></small>
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
                            <h2 class="card-text mb-0"><?php echo e($reportData['available_rooms'] ?? 0); ?></h2>
                            <small>कुल कोठाको <?php echo e(round(($reportData['available_rooms']/$reportData['total_rooms'])*100, 2)); ?>%</small>
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
                                <?php $__currentLoopData = $reportData['recent_payments'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($payment['student_name']); ?></td>
                                    <td><?php echo e($payment['date']); ?></td>
                                    <td>रु. <?php echo e(number_format($payment['amount'], 2)); ?></td>
                                    <td><?php echo e($payment['method']); ?></td>
                                    <td><span class="badge bg-success"><?php echo e($payment['status']); ?></span></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- चार्ट जाभास्क्रिप्ट -->
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Report type selector logic
    document.getElementById('report_type').addEventListener('change', function() {
        const type = this.value;
        document.querySelectorAll('.monthly-selector, .yearly-selector, .custom-selector').forEach(el => {
            el.style.display = 'none';
        });
        
        if (type === 'monthly') {
            document.querySelectorAll('.monthly-selector, .yearly-selector').forEach(el => {
                el.style.display = 'block';
            });
        } else if (type === 'yearly') {
            document.querySelector('.yearly-selector').style.display = 'block';
        } else if (type === 'custom') {
            document.querySelectorAll('.custom-selector').forEach(el => {
                el.style.display = 'block';
            });
        }
    });

    // Trigger change event on page load to show/hide appropriate fields
    document.getElementById('report_type').dispatchEvent(new Event('change'));

    // Filter report button handler
    document.getElementById('filterReport').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('reportFilterForm'));
        const reportType = formData.get('report_type');
        
        // Show loading state
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> लोड हुँदै...';
        this.disabled = true;
        
        let url = '<?php echo e(route("admin.reports.filter")); ?>';
        let data = {
            _token: '<?php echo e(csrf_token()); ?>',
            type: reportType
        };
        
        if (reportType === 'monthly') {
            data.year = formData.get('year');
            data.month = formData.get('month');
        } else if (reportType === 'yearly') {
            data.year = formData.get('year');
        } else if (reportType === 'custom') {
            data.start_date = formData.get('start_date');
            data.end_date = formData.get('end_date');
        }
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the dashboard with new data
                updateDashboard(data.data, reportType);
            } else {
                alert('प्रतिवेदन लोड गर्न असफल: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('प्रतिवेदन लोड गर्न असफल।');
        })
        .finally(() => {
            // Reset button state
            this.innerHTML = '<i class="fas fa-filter me-1"></i> प्रतिवेदन देखाउनुहोस्';
            this.disabled = false;
        });
    });

    // Function to update dashboard with filtered data
    function updateDashboard(data, type) {
        // Update main stats based on report type
        if (type === 'summary') {
            // This would be the default dashboard data
            location.reload(); // Reload page for summary data
        } else {
            // For other report types, update the UI accordingly
            document.getElementById('mainStats').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        ${type} प्रतिवेदनको लागि विस्तृत तथ्याङ्क तलको तालिकामा देखाइएको छ।
                    </div>
                </div>
            `;
            
            // Update charts with filtered data if available
            if (data.daily_revenue) {
                // Update revenue chart with daily data
                updateRevenueChart(data.daily_revenue);
            }
            
            // You can add more UI updates based on the returned data
        }
    }

    // Function to update revenue chart with daily data
    function updateRevenueChart(dailyRevenue) {
        // This is a placeholder - you would need to implement based on your data structure
        console.log('Updating chart with:', dailyRevenue);
    }

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
                data: [<?php echo e($reportData['room_occupancy']); ?>, <?php echo e($reportData['available_rooms']); ?>, 10, 5],
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
<?php $__env->stopSection(); ?>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\reports\index.blade.php ENDPATH**/ ?>