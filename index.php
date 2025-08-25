<?php
require_once 'config.php';
require_once './components/navbar.php';

// Get dynamic data for the homepage
$totalStudents = 0;
$totalCompanies = 0;
$totalCoordinators = 0;
$recentStudents = [];
$recentCompanies = [];

try {
    // Use $con from config.php or adjust as per your config file
    // $con = $opr->con;
    // If config.php defines $con, just use it directly:
    // (Remove or comment out this line if $con is already available)

    $stmt = $con->query("SELECT COUNT(*) as count FROM student s JOIN auth a ON a.id = s.authid WHERE a.authrole = 'student'");
    $totalStudents = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt = $con->query("SELECT COUNT(*) as count FROM company c JOIN auth a ON a.id = c.authid WHERE a.authrole = 'company'");
    $totalCompanies = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt = $con->query("SELECT COUNT(*) as count FROM coordinator co JOIN auth a ON a.id = co.authid WHERE a.authrole = 'co-ordinator'");
    $totalCoordinators = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get recent students
    $stmt = $con->query("SELECT a.firstname, a.lastname, s.branch, s.cgpa, a.userimage FROM student s JOIN auth a ON a.id = s.authid WHERE a.authrole = 'student' ORDER BY s.id DESC LIMIT 6");
    $recentStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get recent companies
    $stmt = $con->query("SELECT c.cname, c.ctype, c.csize, a.userimage FROM company c JOIN auth a ON a.id = c.authid WHERE a.authrole = 'company' ORDER BY c.id DESC LIMIT 6");
    $recentCompanies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Handle errors gracefully
    error_log("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Portal - Connect, Grow, Succeed</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6c63ff;
            --primary-light: rgba(108, 99, 255, 0.1);
            --primary-dark: rgba(108, 99, 255, 0.85);
            --secondary-color: #8b5cf6;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8f9ff;
            --dark-text: #1f2937;
            --muted-text: #6b7280;
            --border-color: #e5e7eb;
            --shadow-sm: 0 2px 4px rgba(108, 99, 255, 0.1);
            --shadow-md: 0 4px 12px rgba(108, 99, 255, 0.15);
            --shadow-lg: 0 8px 24px rgba(108, 99, 255, 0.2);
            --gradient-primary: linear-gradient(135deg, #6c63ff 0%, #8b5cf6 100%);
            --gradient-secondary: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(108, 99, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 50%, #e8ebff 100%);
            color: var(--dark-text);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #6c63ff 50%, #8b5cf6 75%, #a855f7 100%);
            background-size: 400% 400%;
            animation: gradientShift 8s ease-in-out infinite;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            opacity: 0.6;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 50%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% {
                filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
            }

            100% {
                filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.6));
            }
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .hero-stat {
            text-align: center;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            padding: 1.5rem;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            min-width: 120px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .hero-stat:hover {
            transform: translateY(-5px);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .hero-stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .hero-stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
            font-weight: 500;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-hero {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: var(--primary-color);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .btn-primary-hero:hover::before {
            left: 100%;
        }

        .btn-primary-hero:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
            color: var(--primary-color);
        }

        .btn-outline-hero {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .btn-outline-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-outline-hero:hover::before {
            left: 100%;
        }

        .btn-outline-hero:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: white;
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Features Section */
        .features-section {
            padding: 6rem 0;
            background: white;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: var(--muted-text);
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            background: var(--gradient-primary);
            box-shadow: var(--shadow-sm);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark-text);
        }

        .feature-description {
            color: var(--muted-text);
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            padding: 6rem 0;
            background: var(--light-bg);
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--muted-text);
            font-weight: 500;
        }

        /* Recent Section */
        .recent-section {
            padding: 6rem 0;
            background: white;
        }

        .recent-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .recent-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-color);
        }

        .recent-avatar {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 3px solid var(--primary-light);
        }

        .recent-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark-text);
        }

        .recent-details {
            font-size: 0.9rem;
            color: var(--muted-text);
            margin-bottom: 0.5rem;
        }

        .recent-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            background: var(--primary-light);
            color: var(--primary-color);
        }

        /* CTA Section */
        .cta-section {
            padding: 6rem 0;
            background: var(--gradient-primary);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .cta-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: var(--dark-text);
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-content {
            text-align: center;
        }

        .footer-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-description {
            opacity: 0.8;
            margin-bottom: 2rem;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .social-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .social-link:hover {
            transform: translateY(-3px);
            background: var(--secondary-color);
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
            text-align: center;
            opacity: 0.7;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-stats {
                gap: 1rem;
            }

            .hero-stat {
                min-width: 100px;
                padding: 1rem;
            }

            .hero-stat-value {
                font-size: 2rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn-hero {
                width: 100%;
                justify-content: center;
            }

            .section-title {
                font-size: 2rem;
            }

            .feature-card {
                padding: 2rem;
            }

            .stat-card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-stats {
                flex-direction: column;
                align-items: center;
            }

            .hero-stat {
                width: 100%;
                max-width: 200px;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="hero-content">
                        <h1 class="hero-title">Connect, Grow, Succeed</h1>
                        <p class="hero-subtitle">
                            Empowering students and companies to build meaningful connections.
                            Discover opportunities, showcase talents, and accelerate your career journey.
                        </p>

                        <div class="hero-stats">
                            <div class="hero-stat">
                                <div class="hero-stat-value"><?php echo number_format($totalStudents); ?>+</div>
                                <div class="hero-stat-label">Students</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hero-stat-value"><?php echo number_format($totalCompanies); ?>+</div>
                                <div class="hero-stat-label">Companies</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hero-stat-value"><?php echo number_format($totalCoordinators); ?>+</div>
                                <div class="hero-stat-label">Coordinators</div>
                            </div>
                        </div>

                        <div class="hero-buttons">
                            <a href="auth/login.php" class="btn-hero btn-primary-hero">
                                <i class="fas fa-sign-in-alt"></i>
                                Get Started
                            </a>
                            <a href="#features" class="btn-hero btn-outline-hero">
                                <i class="fas fa-info-circle"></i>
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="text-center">
                        <img src="assets/images/hero-illustration.svg" alt="Placement Portal" class="img-fluid"
                            style="max-width: 500px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Why Choose Our Platform?</h2>
                <p class="section-subtitle">
                    Experience a comprehensive placement solution designed to bridge the gap between talented students
                    and innovative companies.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Smart Matching</h3>
                        <p class="feature-description">
                            Advanced algorithms match students with companies based on skills, preferences, and
                            requirements for optimal placement success.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Analytics Dashboard</h3>
                        <p class="feature-description">
                            Comprehensive analytics and insights help track placement progress, success rates, and
                            performance metrics.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Secure & Reliable</h3>
                        <p class="feature-description">
                            Enterprise-grade security ensures your data is protected with advanced encryption and
                            privacy controls.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Mobile Friendly</h3>
                        <p class="feature-description">
                            Access the platform from anywhere with our responsive design that works seamlessly on all
                            devices.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="feature-title">24/7 Support</h3>
                        <p class="feature-description">
                            Round-the-clock support ensures you get help whenever you need it with our dedicated support
                            team.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3 class="feature-title">Fast & Efficient</h3>
                        <p class="feature-description">
                            Streamlined processes and automated workflows make placement management faster and more
                            efficient.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Platform Statistics</h2>
                <p class="section-subtitle">
                    Real-time data showcasing the success and growth of our placement platform.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($totalStudents); ?>+</div>
                        <div class="stat-label">Active Students</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($totalCompanies); ?>+</div>
                        <div class="stat-label">Partner Companies</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($totalCoordinators); ?>+</div>
                        <div class="stat-label">Coordinators</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Success Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Students Section -->
    <?php if (!empty($recentStudents)): ?>
        <section class="recent-section">
            <div class="container">
                <div class="section-header" data-aos="fade-up">
                    <h2 class="section-title">Recent Students</h2>
                    <p class="section-subtitle">
                        Meet some of our talented students who are ready to make their mark in the industry.
                    </p>
                </div>

                <div class="row g-4">
                    <?php foreach ($recentStudents as $index => $student): ?>
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                            <div class="recent-card">
                                <img src="<?php echo BASE_URL . 'uploads/auth/' . ($student['userimage'] ?: 'unkown.png'); ?>"
                                    alt="<?php echo htmlspecialchars($student['firstname']); ?>" class="recent-avatar">
                                <h4 class="recent-name">
                                    <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></h4>
                                <p class="recent-details"><?php echo htmlspecialchars($student['branch']); ?></p>
                                <span class="recent-badge">CGPA: <?php echo number_format($student['cgpa'], 2); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Recent Companies Section -->
    <?php if (!empty($recentCompanies)): ?>
        <section class="recent-section" style="background: var(--light-bg);">
            <div class="container">
                <div class="section-header" data-aos="fade-up">
                    <h2 class="section-title">Partner Companies</h2>
                    <p class="section-subtitle">
                        Leading companies that trust our platform for finding exceptional talent.
                    </p>
                </div>

                <div class="row g-4">
                    <?php foreach ($recentCompanies as $index => $company): ?>
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                            <div class="recent-card">
                                <img src="<?php echo BASE_URL . 'uploads/auth/' . ($company['userimage'] ?: 'unkown.png'); ?>"
                                    alt="<?php echo htmlspecialchars($company['cname']); ?>" class="recent-avatar">
                                <h4 class="recent-name"><?php echo htmlspecialchars($company['cname']); ?></h4>
                                <p class="recent-details"><?php echo htmlspecialchars($company['ctype']); ?></p>
                                <span class="recent-badge"><?php echo htmlspecialchars($company['csize']); ?> employees</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content" data-aos="fade-up">
                <h2 class="cta-title">Ready to Get Started?</h2>
                <p class="cta-description">
                    Join thousands of students and companies who have already discovered the power of our placement
                    platform.
                </p>
                <div class="hero-buttons justify-content-center">
                    <a href="auth/register.php" class="btn-hero btn-primary-hero">
                        <i class="fas fa-user-plus"></i>
                        Register Now
                    </a>
                    <a href="auth/login.php" class="btn-hero btn-outline-hero">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <h3 class="footer-title">Placement Portal</h3>
                <p class="footer-description">
                    Connecting talent with opportunity. Empowering the future of work through innovative placement
                    solutions.
                </p>

                <div class="social-links">
                    <a href="#" class="social-link">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>

                <div class="footer-bottom">
                    <p>&copy; 2024 Placement Portal. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer> -->
    <?php include './components/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    </script>
</body>

</html>