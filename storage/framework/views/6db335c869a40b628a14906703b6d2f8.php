<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>भुक्तानी प्रबन्धन</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <style>
        .nepali {
            font-family: 'Preeti', 'Roboto', sans-serif;
        }
        .btn {
            border-radius: 0.5rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
            border: none;
            box-shadow: 0 2px 5px rgba(78, 115, 223, 0.3);
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #224abe, #4e73df);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.4);
        }
        .btn-success {
            background: linear-gradient(45deg, #1cc88a, #13855c);
            border: none;
            box-shadow: 0 2px 5px rgba(28, 200, 138, 0.3);
        }
        .btn-success:hover {
            background: linear-gradient(45deg, #13855c, #1cc88a);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(28, 200, 138, 0.4);
        }
        .table-hover tbody tr {
            transition: all 0.2s;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        .badge.rounded-pill {
            padding: 0.6em 1em;
            font-size: 0.85em;
        }
        .bg-gradient-primary {
            background: linear-gradient(45deg, #4e73df, #224abe) !important;
        }
        .card-header.bg-primary {
            background: linear-gradient(45deg, #4e73df, #224abe) !important;
        }
        .alert {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.1);
        }
        #refreshBtn:hover {
            background-color: #eaecf4;
            transform: rotate(180deg);
        }
        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 0.35rem;
            margin: 0.1rem 0.25rem;
            width: auto;
            display: flex;
            align-items: center;
        }
        .dropdown-item:hover {
            background-color: #f8f9fc;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.5rem;
        }
        .role-badge {
            font-size: 0.7rem;
            padding: 0.35em 0.65em;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark fixed-top" style="background: linear-gradient(45deg, #4e73df, #224abe);">
        <div class="container-fluid">
            <a class="navbar-brand nepali" href="#">
                <i class="fas fa-hotel me-2"></i>होस्टलहब - प्रशासक
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 nepali">पराशर रेग्मी</span>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <span class="nepali">प्रशासक</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item nepali" href="#"><i class="fas fa-user me-2"></i>मेरो प्रोफाइल</a></li>
                        <li><a class="dropdown-item nepali" href="#"><i class="fas fa-cog me-2"></i>सेटिङ्हरू</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item nepali" href="#"><i class="fas fa-sign-out-alt me-2"></i>लगआउट</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid" style="margin-top: 80px;">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse" style="background: linear-gradient(45deg, #4e73df, #224abe); min-height: calc(100vh - 80px);">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                ड्यासबोर्ड
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                होस्टलहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                कोठाहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                विद्यार्थीहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active nepali" href="#" style="background: linear-gradient(45deg, #4e73df, #224abe);">
                                भुक्तानीहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                भोजन
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                ग्यालरी
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                सम्पर्क
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                प्रतिवेदनहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                सेटिङ्हरू
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
                    <h1 class="h3 mb-0 text-gray-800 nepali">
                        <i class="fas fa-credit-card me-2 text-primary"></i>भुक्तानी प्रबन्धन
                    </h1>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                            <i class="fas fa-plus-circle me-2"></i>
                            <span class="nepali">नयाँ भुक्तानी</span>
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle" type="button" id="reportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-file-export me-2"></i>
                                <span class="nepali">प्रतिवेदनहरू</span>
                            </button>
                            <ul class="dropdown-menu shadow animated--grow-in" aria-labelledby="reportDropdown">
                                <li>
                                    <a class="dropdown-item nepali" href="#">
                                        <i class="fas fa-file-csv text-info me-2"></i>CSV निर्यात गर्नुहोस्
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item nepali" href="#">
                                        <i class="fas fa-file-excel text-success me-2"></i>Excel निर्यात गर्नुहोस्
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item nepali" href="#">
                                        <i class="fas fa-chart-bar text-warning me-2"></i>प्रतिवेदन हेर्नुहोस्
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Role-based access message -->
                <div class="alert alert-info d-flex align-items-center nepali shadow-sm">
                    <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                    <div>
                        <strong>प्रशासक खाता:</strong> तपाईंलाई सबै भुक्तानीहरू हेर्न, थप्न, सम्पादन गर्न र मेट्न अनुमति छ।
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Total Payments Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 nepali">कुल भुक्तानी</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">रु १,२५,६४०</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-wallet fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Successful Payments Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1 nepali">सफल भुक्तानी</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">८९</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Payments Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 nepali">पेन्डिङ भुक्तानी</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">५</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Failed Payments Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1 nepali">असफल भुक्तानी</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">३</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <h6 class="m-0 font-weight-bold nepali"><i class="fas fa-filter me-2"></i>भुक्तानीहरू फिल्टर गर्नुहोस्</h6>
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true" aria-controls="filterCollapse">
                            <i class="fas fa-sliders-h text-primary"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="filterCollapse">
                        <div class="card-body">
                            <form method="GET" action="#">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold nepali">मिति सीमा</label>
                                        <input type="text" class="form-control date-range" name="date_range"
                                               value="2025-08-05 to 2025-09-04">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold nepali">भुक्तानी विधि</label>
                                        <select class="form-select nepali" name="method">
                                            <option value="">सबै विधिहरू</option>
                                            <option value="khalti">खल्ती</option>
                                            <option value="esewa">e-Sewa</option>
                                            <option value="cash">नगद</option>
                                            <option value="bank">बैंक हस्तान्तरण</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold nepali">स्थिति</label>
                                        <select class="form-select nepali" name="status">
                                            <option value="">सबै स्थितिहरू</option>
                                            <option value="completed">पूर्ण</option>
                                            <option value="pending">प्रतीक्षामा</option>
                                            <option value="failed">असफल</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold nepali">विद्यार्थी खोज्नुहोस्</label>
                                        <input type="text" class="form-control nepali" name="search"
                                               placeholder="विद्यार्थीको नाम वा मोबाइल नम्बरले खोज्नुहोस्">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter me-2"></i> फिल्टर
                                            </button>
                                            <a href="#" class="btn btn-outline-secondary">
                                                <i class="fas fa-sync-alt me-2"></i> रीसेट
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Payments Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-gradient-primary text-white">
                        <h6 class="m-0 font-weight-bold nepali">
                            <i class="fas fa-list me-2"></i>सबै भुक्तानीहरू
                        </h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-primary rounded-pill me-2 nepali">९७ रेकर्डहरू</span>
                            <button class="btn btn-sm btn-light rounded-circle" id="refreshBtn" title="रिफ्रेश गर्नुहोस्">
                                <i class="fas fa-sync-alt text-primary"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead class="thead-dark bg-gradient-primary text-white">
                                    <tr>
                                        <th class="nepali">आईडी</th>
                                        <th class="nepali">विद्यार्थी</th>
                                        <th class="nepali">रकम</th>
                                        <th class="nepali">विधि</th>
                                        <th class="nepali">लेनदेन आईडी</th>
                                        <th class="nepali">मिति</th>
                                        <th class="nepali">स्थिति</th>
                                        <th class="nepali text-center">कार्यहरू</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="fw-bold text-primary">#000123</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light text-center me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium text-dark">राम बहादुर</div>
                                                    <small class="text-muted">9841234567</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success">रु १२,०००</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill p-2"><i class="fas fa-mobile-alt me-1"></i> खल्ती</span>
                                        </td>
                                        <td>
                                            <span class="font-monospace text-truncate d-inline-block" style="max-width: 150px;"
                                                  title="KH1234567890">
                                                KH1234567890
                                            </span>
                                        </td>
                                        <td>१५ सेप्टेम्बर, ११:३० AM</td>
                                        <td>
                                            <span class="badge bg-success rounded-pill p-2"><i class="fas fa-check-circle me-1"></i> पूर्ण</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="#" class="btn btn-info btn-sm view-payment" data-bs-toggle="tooltip" title="विवरण हेर्नुहोस्">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="पूर्ण गर्नुहोस्">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="मेट्नुहोस्">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold text-primary">#000124</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light text-center me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium text-dark">सीता देवी</div>
                                                    <small class="text-muted">9852345678</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success">रु १०,५००</td>
                                        <td>
                                            <span class="badge bg-info rounded-pill p-2"><i class="fas fa-mobile me-1"></i> e-Sewa</span>
                                        </td>
                                        <td>
                                            <span class="font-monospace text-truncate d-inline-block" style="max-width: 150px;"
                                                  title="ES9876543210">
                                                ES9876543210
                                            </span>
                                        </td>
                                        <td>१४ सेप्टेम्बर, ०२:१५ PM</td>
                                        <td>
                                            <span class="badge bg-success rounded-pill p-2"><i class="fas fa-check-circle me-1"></i> पूर्ण</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="#" class="btn btn-info btn-sm view-payment" data-bs-toggle="tooltip" title="विवरण हेर्नुहोस्">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="पूर्ण गर्नुहोस्">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="मेट्नुहोस्">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold text-primary">#000125</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light text-center me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium text-dark">हरि प्रसाद</div>
                                                    <small class="text-muted">9863456789</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success">रु ११,०००</td>
                                        <td>
                                            <span class="badge bg-success rounded-pill p-2"><i class="fas fa-money-bill-wave me-1"></i> नगद</span>
                                        </td>
                                        <td>
                                            <span class="font-monospace text-truncate d-inline-block" style="max-width: 150px;"
                                                  title="CASH09876">
                                                CASH09876
                                            </span>
                                        </td>
                                        <td>१३ सेप्टेम्बर, १०:०० AM</td>
                                        <td>
                                            <span class="badge bg-warning text-dark rounded-pill p-2"><i class="fas fa-clock me-1"></i> प्रतीक्षामा</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="#" class="btn btn-info btn-sm view-payment" data-bs-toggle="tooltip" title="विवरण हेर्नुहोस्">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="पूर्ण गर्नुहोस्">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="मेट्नुहोस्">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold text-primary">#000126</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light text-center me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium text-dark">गीता शर्मा</div>
                                                    <small class="text-muted">9874567890</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success">रु ९,८००</td>
                                        <td>
                                            <span class="badge bg-secondary rounded-pill p-2"><i class="fas fa-university me-1"></i> बैंक</span>
                                        </td>
                                        <td>
                                            <span class="font-monospace text-truncate d-inline-block" style="max-width: 150px;"
                                                  title="BANK12345">
                                                BANK12345
                                            </span>
                                        </td>
                                        <td>१२ सेप्टेम्बर, ०३:४५ PM</td>
                                        <td>
                                            <span class="badge bg-danger rounded-pill p-2"><i class="fas fa-times-circle me-1"></i> असफल</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="#" class="btn btn-info btn-sm view-payment" data-bs-toggle="tooltip" title="विवरण हेर्नुहोस्">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="पूर्ण गर्नुहोस्">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="मेट्नुहोस्">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="nepali text-muted">
                                १ देखि ४ सम्मको ९७ रेकर्डहरू देखाइँदै
                            </div>
                            <div>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <li class="page-item disabled"><a class="page-link" href="#">अघिल्लो</a></li>
                                        <li class="page-item active"><a class="page-link" href="#">१</a></li>
                                        <li class="page-item"><a class="page-link" href="#">२</a></li>
                                        <li class="page-item"><a class="page-link" href="#">३</a></li>
                                        <li class="page-item"><a class="page-link" href="#">४</a></li>
                                        <li class="page-item"><a class="page-link" href="#">अर्को</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title nepali" id="addPaymentModalLabel"><i class="fas fa-plus-circle me-2"></i>नयाँ भुक्तानी थप्नुहोस्</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="studentSelect" class="form-label nepali">विद्यार्थी चयन गर्नुहोस्</label>
                                <select class="form-select" id="studentSelect">
                                    <option selected>विद्यार्थी चयन गर्नुहोस्...</option>
                                    <option value="1">राम बहादुर (9841234567)</option>
                                    <option value="2">सीता देवी (9852345678)</option>
                                    <option value="3">हरि प्रसाद (9863456789)</option>
                                    <option value="4">गीता शर्मा (9874567890)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label nepali">रकम</label>
                                <div class="input-group">
                                    <span class="input-group-text">रु</span>
                                    <input type="number" class="form-control" id="amount" placeholder="रकम राख्नुहोस्">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="paymentMethod" class="form-label nepali">भुक्तानी विधि</label>
                                <select class="form-select" id="paymentMethod">
                                    <option value="cash">नगद</option>
                                    <option value="khalti">खल्ती</option>
                                    <option value="esewa">e-Sewa</option>
                                    <option value="bank">बैंक हस्तान्तरण</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="transactionId" class="form-label nepali">लेनदेन आईडी</label>
                                <input type="text" class="form-control" id="transactionId" placeholder="लेनदेन आईडी राख्नुहोस्">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="paymentDate" class="form-label nepali">भुक्तानी मिति</label>
                                <input type="date" class="form-control" id="paymentDate">
                            </div>
                            <div class="col-md-6">
                                <label for="paymentStatus" class="form-label nepali">स्थिति</label>
                                <select class="form-select" id="paymentStatus">
                                    <option value="completed">पूर्ण</option>
                                    <option value="pending">प्रतीक्षामा</option>
                                    <option value="failed">असफल</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label nepali">टिप्पणीहरू</label>
                            <textarea class="form-control" id="remarks" rows="3" placeholder="अतिरिक्त टिप्पणीहरू..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary nepali" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                    <button type="button" class="btn btn-primary nepali" id="submitPayment">भुक्तानी सुरक्षित गर्नुहोस्</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Payment Modal -->
    <div class="modal fade" id="viewPaymentModal" tabindex="-1" aria-labelledby="viewPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title nepali" id="viewPaymentModalLabel"><i class="fas fa-eye me-2"></i>भुक्तानी विवरण</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong class="nepali">भुक्तानी आईडी:</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-primary">#000123</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong class="nepali">विद्यार्थी:</strong>
                        </div>
                        <div class="col-6">
                            राम बहादुर
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong class="nepali">मोबाइल नम्बर:</strong>
                        </div>
                        <div class="col-6">
                            9841234567
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong class="nepali">रकम:</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-success">रु १२,०००</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong class="nepali">भुक्तानी विधि:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-primary rounded-pill">खल्ती</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong class="nepali">लेनदेन आईडी:</strong>
                        </div>
                        <div class="col-6">
                            KH1234567890
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong class="nepali">मिति:</strong>
                        </div>
                        <div class="col-6">
                            १५ सेप्टेम्बर, ११:३० AM
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong class="nepali">स्थिति:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-success rounded-pill">पूर्ण</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong class="nepali">टिप्पणीहरू:</strong>
                        <p class="text-muted">मासिक भुक्तानी - सेप्टेम्बर २०२५</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary nepali" data-bs-dismiss="modal">बन्द गर्नुहोस्</button>
                    <button type="button" class="btn btn-primary nepali">सम्पादन गर्नुहोस्</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white text-center text-gray-600 py-3 shadow-inner mt-5 nepali">
        <div class="container">
            <p>© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
            <div class="d-flex justify-content-center gap-4">
                <a href="#" class="text-gray-600 hover:text-gray-800 text-decoration-none nepali">गोपनीयता नीति</a>
                <a href="#" class="text-gray-600 hover:text-gray-800 text-decoration-none nepali>">सेवा सर्तहरू</a>
                <span class="text-gray-400 nepali">संस्करण: १.०.०</span>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date range picker with Nepali locale
            $('.date-range').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'लागू गर्नुहोस्',
                    cancelLabel: 'रद्द गर्नुहोस्',
                    fromLabel: 'देखि',
                    toLabel: 'सम्म',
                    customRangeLabel: 'मेरो सीमा',
                    daysOfWeek: ['आइत', 'सोम', 'मंगल', 'बुध', 'बिही', 'शुक्र', 'शनि'],
                    monthNames: ['जनवरी', 'फेब्रुअरी', 'मार्च', 'अप्रिल', 'मे', 'जुन', 'जुलाई', 'अगस्ट', 'सेप्टेम्बर', 'अक्टोबर', 'नोभेम्बर', 'डिसेम्बर'],
                }
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Refresh button functionality
            document.getElementById('refreshBtn').addEventListener('click', function() {
                this.classList.add('rotating');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            });
            
            // View payment buttons
            var viewPaymentButtons = document.querySelectorAll('.view-payment');
            var viewPaymentModal = new bootstrap.Modal(document.getElementById('viewPaymentModal'));
            
            viewPaymentButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    viewPaymentModal.show();
                });
            });
            
            // Submit payment form
            document.getElementById('submitPayment').addEventListener('click', function() {
                // Validate form
                var isValid = true;
                var inputs = document.querySelectorAll('#paymentForm input, #paymentForm select');
                
                inputs.forEach(function(input) {
                    if (!input.value) {
                        isValid = false;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                if (isValid) {
                    // Simulate successful form submission
                    var addPaymentModal = bootstrap.Modal.getInstance(document.getElementById('addPaymentModal'));
                    addPaymentModal.hide();
                    
                    // Show success message
                    var alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show nepali shadow-sm';
                    alertDiv.setAttribute('role', 'alert');
                    alertDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <strong>सफलता!</strong> भुक्तानी सफलतापूर्वक थपियो।
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    
                    document.querySelector('main').prepend(alertDiv);
                    
                    // Auto-hide alert after 5 seconds
                    setTimeout(function() {
                        bootstrap.Alert.getOrCreateInstance(alertDiv).close();
                    }, 5000);
                }
            });
            
            // Simulate loading data
            console.log("भुक्तानी डाटा लोड भइरहेको छ...");
        });
    </script>
</body>
</html><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>