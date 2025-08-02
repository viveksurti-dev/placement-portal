<?php
require_once 'config.php';
require_once 'components/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Placement Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .about-section {
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            padding: 60px 0 40px 0;
        }

        .about-title {
            font-size: 2.8rem;
            font-weight: 800;
            color: #1a237e;
            letter-spacing: 2px;
        }

        .about-content {
            font-size: 1.2rem;
            color: #495057;
            max-width: 700px;
            margin: 0 auto;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #4f8cff;
            background: #e3f0ff;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px auto;
            box-shadow: 0 2px 12px rgba(79, 140, 255, 0.08);
        }

        .card {
            border: none;
            border-radius: 1.2rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 8px 32px rgba(44, 62, 80, 0.12);
        }

        .card-title {
            font-weight: 700;
            color: #1a237e;
        }

        .card-text {
            color: #374151;
        }

        .mission-section {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 16px rgba(44, 62, 80, 0.07);
            padding: 32px 24px;
            margin-top: 60px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .mission-section h4 {
            color: #0d47a1;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .timeline {
            position: relative;
            margin: 60px auto 0 auto;
            max-width: 900px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            width: 4px;
            height: 100%;
            background: #e3e3e3;
            transform: translateX(-50%);
        }

        .timeline-step {
            position: relative;
            width: 50%;
            padding: 24px 40px;
            box-sizing: border-box;
        }

        .timeline-step.left {
            left: 0;
            text-align: right;
        }

        .timeline-step.right {
            left: 50%;
            text-align: left;
        }

        .timeline-step .step-content {
            background: #f1f8ff;
            border-radius: 0.8rem;
            display: inline-block;
            padding: 18px 28px;
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.06);
        }

        .timeline-step .step-icon {
            position: absolute;
            top: 32px;
            width: 36px;
            height: 36px;
            background: #4f8cff;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            z-index: 1;
        }

        .timeline-step.left .step-icon {
            right: -18px;
        }

        .timeline-step.right .step-icon {
            left: -18px;
        }

        @media (max-width: 991px) {
            .timeline::before {
                left: 8px;
            }

            .timeline-step,
            .timeline-step.left,
            .timeline-step.right {
                width: 100%;
                left: 0;
                text-align: left;
                padding-left: 56px;
                padding-right: 16px;
            }

            .timeline-step .step-icon {
                left: -38px;
                right: auto;
            }
        }

        @media (max-width: 767px) {
            .about-title {
                font-size: 2rem;
            }

            .about-content {
                font-size: 1rem;
            }

            .mission-section {
                padding: 18px 8px;
            }
        }
    </style>
</head>

<body>
    <section class="about-section">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="about-title">About Us</h1>
                <p class="about-content mt-3">
                    Welcome to <b>Placement Portal</b> – your all-in-one platform for campus recruitment and placement
                    management. We connect students, companies, and coordinators, making the placement journey seamless,
                    transparent, and efficient for everyone.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5 class="card-title">For Students</h5>
                            <p class="card-text">
                                Create your profile, upload your resume, and apply for jobs and internships. Track your
                                application status, receive instant notifications, and access resources to ace your
                                interviews.
                            </p>
                            <span class="badge bg-primary bg-gradient mt-2">Career Growth</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-building"></i>
                            </div>
                            <h5 class="card-title">For Companies</h5>
                            <p class="card-text">
                                Post job openings, review student applications, and schedule interviews. Communicate
                                directly with candidates and coordinators to find the best talent for your organization.
                            </p>
                            <span class="badge bg-success bg-gradient mt-2">Smart Hiring</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <h5 class="card-title">For Coordinators</h5>
                            <p class="card-text">
                                Manage student and company registrations, oversee placement drives, send notifications,
                                and
                                generate insightful reports. Ensure a smooth and successful placement season.
                            </p>
                            <span class="badge bg-warning text-dark bg-gradient mt-2">Easy Management</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mission-section mt-5 text-center">
                <h4>Our Mission</h4>
                <p class="about-content">
                    To empower students and recruiters with a transparent, user-friendly, and robust platform that
                    simplifies campus placements and fosters career growth.
                </p>
            </div>
            <div class="timeline">
                <div class="timeline-step left">
                    <div class="step-icon"><i class="bi bi-person-plus"></i></div>
                    <div class="step-content">
                        <b>1. Register</b><br>
                        Students, companies, and coordinators sign up and create their profiles.
                    </div>
                </div>
                <div class="timeline-step right">
                    <div class="step-icon"><i class="bi bi-file-earmark-arrow-up"></i></div>
                    <div class="step-content">
                        <b>2. Apply & Post</b><br>
                        Students apply for jobs/internships, companies post openings.
                    </div>
                </div>
                <div class="timeline-step left">
                    <div class="step-icon"><i class="bi bi-search"></i></div>
                    <div class="step-content">
                        <b>3. Shortlisting</b><br>
                        Companies review applications and shortlist candidates.
                    </div>
                </div>
                <div class="timeline-step right">
                    <div class="step-icon"><i class="bi bi-calendar-check"></i></div>
                    <div class="step-content">
                        <b>4. Interviews</b><br>
                        Schedule and conduct interviews through the portal.
                    </div>
                </div>
                <div class="timeline-step left">
                    <div class="step-icon"><i class="bi bi-award"></i></div>
                    <div class="step-content">
                        <b>5. Placement</b><br>
                        Get placed and celebrate your success!
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
<?php
require_once './components/footer.php';
?>