<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    
    <!-- Custom CSS -->
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
        .sidebar .nav-link {
            color: white;
            padding: 0.8rem 1rem;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(3px);
        }
        .sidebar .nav-link.active {
            background: linear-gradient(45deg, #4e73df, #224abe);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <nav class="navbar navbar-dark fixed-top" style="background: linear-gradient(45deg, #4e73df, #224abe);">
        <div class="container-fluid">
            <a class="navbar-brand nepali" href="#">
                <i class="fas fa-hotel me-2"></i>होस्टलहब - प्रशासक प्यानल
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
                                <i class="fas fa-tachometer-alt me-2"></i>
                                ड्यासबोर्ड
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                <i class="fas fa-hotel me-2"></i>
                                होस्टलहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                <i class="fas fa-door-open me-2"></i>
                                कोठाहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                <i class="fas fa-users me-2"></i>
                                विद्यार्थीहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                <i class="fas fa-credit-card me-2"></i>
                                भुक्तानीहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                <i class="fas fa-utensils me-2"></i>
                                भोजन
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                <i class="fas fa-image me-2"></i>
                                ग्यालरी
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                <i class="fas fa-address-book me-2"></i>
                                सम्पर्क
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                <i class="fas fa-chart-bar me-2"></i>
                                प्रतिवेदनहरू
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white nepali" href="#">
                                <i class="fas fa-cogs me-2"></i>
                                सेटिङ्हरू
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center text-gray-600 py-3 shadow-inner mt-5 nepali">
        <div class="container">
            <p>© २०२५ HostelHub. सबै अधिकार सुरक्षित।</p>
            <div class="d-flex justify-content-center gap-4">
                <a href="#" class="text-gray-600 hover:text-gray-800 text-decoration-none nepali">गोपनीयता नीति</a>
                <a href="#" class="text-gray-600 hover:text-gray-800 text-decoration-none nepali">सेवा सर्तहरू</a>
                <span class="text-gray-400 nepali">संस्करण: १.०.०</span>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Refresh button functionality (if needed in other pages)
            var refreshBtn = document.getElementById('refreshBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    this.classList.add('rotating');
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>