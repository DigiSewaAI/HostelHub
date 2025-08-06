<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelHub - होस्टल प्रबन्धन प्रणाली</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            color: #333;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
        }

        /* Header Styles */
        header {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 50px;
            margin-right: 10px;
        }

        .logo h1 {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            font-size: 1.1rem;
            transition: color 0.3s;
            display: flex;
            align-items: center;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links i {
            margin-right: 8px;
            font-size: 1.2rem;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(67, 97, 238, 0.9), rgba(63, 55, 201, 0.9)), url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 8rem 5%;
            text-align: center;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h2 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.5rem;
            margin-bottom: 2.5rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 2rem;
        }

        .btn {
            padding: 15px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: white;
            color: var(--primary);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }

        /* Stats Section */
        .stats {
            padding: 5rem 5%;
            max-width: 1400px;
            margin: -50px auto 0;
            position: relative;
            z-index: 2;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--primary);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.2rem;
            color: #666;
        }

        /* Rooms Section */
        .rooms {
            padding: 5rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-header h2::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }

        .section-header p {
            font-size: 1.2rem;
            color: #666;
            max-width: 700px;
            margin: 20px auto 0;
        }

        .room-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-10px);
        }

        .room-image {
            height: 250px;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            font-size: 1.2rem;
        }

        .room-content {
            padding: 25px;
            text-align: center;
        }

        .room-content h3 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .room-content p {
            color: #666;
            margin-bottom: 20px;
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(to right, #4361ee, #3a0ca3);
            color: white;
            padding: 6rem 5%;
            text-align: center;
            margin: 5rem 0;
        }

        .cta h2 {
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
        }

        .cta p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto 2.5rem;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 4rem 5% 2rem;
            text-align: center;
        }

        .footer-logo {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: white;
        }

        .copyright {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .nav-links {
                display: none;
            }

            .hero h2 {
                font-size: 2.8rem;
            }

            .hero p {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 768px) {
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .hero {
                padding: 5rem 5%;
            }

            .hero h2 {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>HostelHub</h1>
            </div>
            <ul class="nav-links">
                <li><a href="#"><i class="fas fa-home"></i> ड्यासबोर्ड</a></li>
                <li><a href="#"><i class="fas fa-users"></i> विद्यार्थीहरू</a></li>
                <li><a href="#"><i class="fas fa-door-open"></i> कोठाहरू</a></li>
                <li><a href="#"><i class="fas fa-utensils"></i> भोजन</a></li>
                <li><a href="#"><i class="fas fa-images"></i> ग्यालरी</a></li>
                <li><a href="#"><i class="fas fa-phone"></i> सम्पर्क</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>होस्टल प्रबन्धन प्रणाली</h2>
            <p>HostelHub - Nepal's Most Reliable Hostel Management System</p>
            <p>विद्यार्थीहरूको नाम, कोठा व्यवस्थापन, भुक्तानी प्रणाली र अन्य सबै कार्यहरू एउटै प्लेटफर्ममा सञ्चालन गर्नुहोस्</p>
            <div class="cta-buttons">
                <a href="#" class="btn btn-primary">लगइन गर्नुहोस् / Login</a>
                <a href="#" class="btn btn-secondary">सुविधाहरू हेर्नुहोस् / View Features</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-value">125</div>
                <div class="stat-label">कुल विद्यार्थीहरू / Total Students</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-door-closed"></i>
                </div>
                <div class="stat-value">42</div>
                <div class="stat-label">कोठाहरू / Rooms</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bed"></i>
                </div>
                <div class="stat-value">15</div>
                <div class="stat-label">उपलब्ध कोठाहरू / Available Rooms</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value">64%</div>
                <div class="stat-label">कोठा भराइ दर / Room Occupancy</div>
            </div>
        </div>
    </section>

    <!-- Rooms Section -->
    <section class="rooms">
        <div class="section-header">
            <h2>उपलब्ध कोठाहरू / Available Rooms</h2>
            <p>हामीसँग विभिन्न प्रकारका कोठाहरू उपलब्ध छन् / We have various types of rooms available</p>
        </div>

        <div class="room-card">
            <div class="room-image">
                <i class="fas fa-home fa-3x"></i>
            </div>
            <div class="room-content">
                <h3>हालका लागि कुनै उपलब्ध कोठा छैन</h3>
                <p>No rooms available at the moment</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>निःशुल्क परीक्षण सुरु गर्नुहोस्</h2>
        <p>७ दिनको निःशुल्क परीक्षण अवधिमा सबै सुविधाहरू अनलिमिटेड रूपमा प्रयोग गर्नुहोस्</p>
        <p>Enjoy all features unlimited during 7 days free trial period</p>
        <div class="cta-buttons">
            <a href="#" class="btn btn-primary">लगइन गर्नुहोस् / Login</a>
            <a href="#" class="btn btn-secondary">निःशुल्क साइन अप गर्नुहोस् / Sign Up Free</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-logo">HostelHub</div>
        <div class="copyright">
            © 2025 HostelHub. सबै अधिकार सुरक्षित। / All rights reserved.
        </div>
    </footer>

    <script>
        // Simple animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            const roomCard = document.querySelector('.room-card');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            // Add initial styles for animation
            statCards.forEach(card => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            roomCard.style.opacity = 0;
            roomCard.style.transform = 'translateY(30px)';
            roomCard.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(roomCard);
        });
    </script>
</body>
</html>
