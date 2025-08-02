<?php
require_once 'config.php';
require_once './components/navbar.php';

// $admin = 'Admin@123';
// echo $pass = password_hash('Admin@123', PASSWORD_DEFAULT);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PORTAL - HOME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./styles/main.css">
    <style>
        /* Lazy load animation */
        .lazy-section {
            opacity: 0;
            transform: translateY(40px) scale(0.98);
            transition: opacity 0.8s cubic-bezier(.4, 2, .3, 1), transform 0.8s cubic-bezier(.4, 2, .3, 1);
            will-change: opacity, transform;
        }

        .lazy-section.loaded {
            opacity: 1;
            transform: none;
        }

        /* Animated gradient for hero */
        .hero-bg {
            background: linear-gradient(120deg, #0d6efd 60%, #6ea8fe 100%);
            background-size: 200% 200%;
            animation: gradientMove 6s ease-in-out infinite alternate;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 100% 50%;
            }
        }

        /* Animated underline for headings */
        .section-title {
            display: inline-block;
            position: relative;
            padding-bottom: 0.3em;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, #0d6efd 60%, #6ea8fe 100%);
            border-radius: 2px;
            position: absolute;
            left: 20%;
            bottom: 0;
            animation: underlineGrow 1.2s cubic-bezier(.4, 2, .3, 1);
        }

        @keyframes underlineGrow {
            from {
                width: 0;
            }

            to {
                width: 60%;
            }
        }

        /* Card and button hover/active effects */
        .feature-card,
        .card {
            transition: transform 0.25s cubic-bezier(.4, 2, .3, 1), box-shadow 0.25s;
        }

        .feature-card:hover,
        .card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 12px 32px rgba(13, 110, 253, 0.18);
        }

        .gradient-btn {
            position: relative;
            overflow: hidden;
        }

        .gradient-btn:active::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 100%;
            transform: translate(-50%, -50%);
            animation: ripple 0.5s linear;
        }

        @keyframes ripple {
            to {
                width: 200%;
                height: 200%;
                opacity: 0;
            }
        }

        .hero-img {
            filter: drop-shadow(0 8px 24px rgba(0, 0, 0, 0.12));
            border-radius: 1rem;
        }

        .feature-card {
            background: #fff;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .feature-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 8px 32px rgba(13, 110, 253, 0.13);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 0.5rem;
        }

        .stat-icon {
            font-size: 2.2rem;
            color: #0d6efd;
            margin-bottom: 0.3rem;
        }

        .hello-bar {
            background: linear-gradient(90deg, #e0e7ff 0%, #f8fafc 100%);
            border-radius: 8px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(13, 110, 253, 0.07);
        }

        .testimonial-img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #0d6efd;
        }

        .gradient-btn {
            background: linear-gradient(90deg, #0d6efd 60%, #6ea8fe 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 2rem;
            padding: 0.75rem 2.5rem;
            box-shadow: 0 4px 16px rgba(13, 110, 253, 0.13);
            transition: background 0.2s, box-shadow 0.2s;
        }

        .gradient-btn:hover {
            background: linear-gradient(90deg, #6ea8fe 0%, #0d6efd 100%);
            box-shadow: 0 8px 32px rgba(13, 110, 253, 0.18);
        }

        .carousel-indicators [data-bs-target] {
            background-color: #0d6efd;
        }

        .gradient-btn,
        .btn,
        button {
            border-radius: 10px !important;
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="container py-5 my-5 hero-bg lazy-section" data-section="hero">
        <div class="row align-items-center px-4 py-5">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <h1 class="fw-bold mb-4 display-5">Your Gateway to <span style="color:#ffe066;">Career Opportunities
                    </span>
                </h1>
                <h5 class="mb-4" style="color:#e0e7ff;">Connecting students and recruiters for a brighter future.
                </h5>
                <a href="opportunities.php" class="gradient-btn mb-3">Find Opportunities</a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://via.placeholder.com/500x300?text=Placement+Portal" class="img-fluid hero-img mb-3"
                    alt="Hero Image">
            </div>
        </div>
    </section>

    <!-- Hello Bar / Interactive Widget -->
    <div class="container mb-5 lazy-section" data-section="hello-bar">
        <div class="hello-bar mb-4">
            <span class="fw-semibold"><i class="bi bi-lightbulb me-2"></i>Not sure where to start? <a
                    href="fit-calculator.php" class="link-primary">Calculate Your Fit</a></span>
            <a href="chat.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-chat-dots me-1"></i>Live Chat</a>
        </div>
    </div>

    <!-- Search Bar -->
    <section class="container mb-5 pb-3 lazy-section" data-section="search">
        <form class="d-flex justify-content-center gap-3" action="search.php" method="get">
            <input class="form-control form-control-lg w-50 me-2 shadow-sm" type="search" name="q"
                placeholder="Search placements, companies, or resources..." aria-label="Search">
            <button class="gradient-btn" type="submit">Search</button>
        </form>
    </section>

    <!-- Key Features / Value Highlights -->
    <section class="container mb-5 pb-3 lazy-section" data-section="features">
        <h2 class="text-center mb-5 section-title">Why Choose Us?</h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card feature-card h-100 py-5 px-3">
                    <div class="feature-icon mb-3"><i class="bi bi-stars"></i></div>
                    <h5 class="mb-3">Smart Matching Algorithm</h5>
                    <p class="text-muted mb-0">Get personalized job recommendations based on your skills and interests.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100 py-5 px-3">
                    <div class="feature-icon mb-3"><i class="bi bi-shield-check"></i></div>
                    <h5 class="mb-3">Verified Employers</h5>
                    <p class="text-muted mb-0">Connect only with trusted and verified companies for safe placements.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100 py-5 px-3">
                    <div class="feature-icon mb-3"><i class="bi bi-clipboard-data"></i></div>
                    <h5 class="mb-3">Track Applications</h5>
                    <p class="text-muted mb-0">Easily monitor your application status and interview schedules in one
                        place.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats & Testimonials -->
    <section class="container mb-5 pb-3 lazy-section" data-section="stats">
        <h2 class="text-center mb-5 section-title">Our Impact</h2>
        <div class="row align-items-center gy-5">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="row text-center g-4">
                    <div class="col-6 mb-4">
                        <div class="stat-icon mb-2"><i class="bi bi-briefcase-fill"></i></div>
                        <h2 class="fw-bold mb-1">500+</h2>
                        <span class="text-muted">Placements</span>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="stat-icon mb-2"><i class="bi bi-building"></i></div>
                        <h2 class="fw-bold mb-1">200+</h2>
                        <span class="text-muted">Partner Companies</span>
                    </div>
                    <div class="col-6">
                        <div class="stat-icon mb-2"><i class="bi bi-bar-chart-line-fill"></i></div>
                        <h2 class="fw-bold mb-1">95%</h2>
                        <span class="text-muted">Success Rate</span>
                    </div>
                    <div class="col-6">
                        <div class="stat-icon mb-2"><i class="bi bi-clock-history"></i></div>
                        <h2 class="fw-bold mb-1">24/7</h2>
                        <span class="text-muted">Support</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Testimonial Carousel -->
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="card shadow-sm p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg"
                                        class="testimonial-img me-3" alt="Student">
                                    <div>
                                        <strong>Rahul Sharma</strong><br>
                                        <span class="text-muted" style="font-size: 0.9em;">Placed at Infosys</span>
                                    </div>
                                </div>
                                <p class="mb-0">“The portal made my placement journey smooth and stress-free. I found my
                                    dream job in just a few clicks!”</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="card shadow-sm p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg"
                                        class="testimonial-img me-3" alt="Student">
                                    <div>
                                        <strong>Priya Verma</strong><br>
                                        <span class="text-muted" style="font-size: 0.9em;">Placed at TCS</span>
                                    </div>
                                </div>
                                <p class="mb-0">“Great experience! The smart matching feature helped me discover roles I
                                    never thought of.”</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Placement Drives Section -->
    <section class="container mb-5 pb-3 lazy-section" data-section="drives">
        <h2 class="mb-5 text-center section-title">Upcoming Placement Drives</h2>
        <div class="row justify-content-center g-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Infosys</h5>
                        <p class="card-text mb-1"><i class="bi bi-calendar-event me-2"></i>25 July 2025</p>
                        <span class="badge bg-info text-dark mb-2">Software Engineer</span>
                        <p class="text-muted small">Technologies: Java, Python</p>
                        <a href="drive-details.php?id=1" class="btn btn-outline-primary btn-sm mt-2">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title mb-2">TCS</h5>
                        <p class="card-text mb-1"><i class="bi bi-calendar-event me-2"></i>30 July 2025</p>
                        <span class="badge bg-info text-dark mb-2">System Analyst</span>
                        <p class="text-muted small">Technologies: C++, SQL</p>
                        <a href="drive-details.php?id=2" class="btn btn-outline-primary btn-sm mt-2">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Capgemini</h5>
                        <p class="card-text mb-1"><i class="bi bi-calendar-event me-2"></i>5 August 2025</p>
                        <span class="badge bg-info text-dark mb-2">Data Analyst</span>
                        <p class="text-muted small">Technologies: Python, PowerBI</p>
                        <a href="drive-details.php?id=3" class="btn btn-outline-primary btn-sm mt-2">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Wipro</h5>
                        <p class="card-text mb-1"><i class="bi bi-calendar-event me-2"></i>10 August 2025</p>
                        <span class="badge bg-info text-dark mb-2">Web Developer</span>
                        <p class="text-muted small">Technologies: HTML, CSS, JS</p>
                        <a href="drive-details.php?id=4" class="btn btn-outline-primary btn-sm mt-2">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Signup Section -->
    <section class="container mb-5 pb-5 lazy-section" data-section="newsletter">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4 text-center">
                    <h4 class="mb-3">Stay Updated!</h4>
                    <p class="mb-4 text-muted">Subscribe to our newsletter for the latest placement drives, tips, and
                        resources.</p>
                    <form class="row g-2 justify-content-center">
                        <div class="col-md-8">
                            <input type="email" class="form-control form-control-lg" placeholder="Enter your email"
                                required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="gradient-btn w-100 py-2">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="container text-center mb-5 pb-5 lazy-section" data-section="cta">
        <h2 class="fw-bold mb-4">Ready to Start Your Journey?</h2>
        <a href="register.php" class="gradient-btn px-5 py-3">Sign Up Free</a>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lazy load animation for sections
        document.addEventListener("DOMContentLoaded", function() {
            const lazySections = document.querySelectorAll('.lazy-section');
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('loaded');
                        obs.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15
            });

            lazySections.forEach(section => observer.observe(section));
        });
    </script>
</body>

</html>
<?php
require_once './components/footer.php';
?>