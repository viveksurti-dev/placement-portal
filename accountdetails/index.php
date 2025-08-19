<?php
require_once '../config.php';
require_once '../components/navbar.php';

if (isset($_GET['u']) && isset($_GET['r'])) {
    $user = base64_decode(base64_decode($_GET['u']));
    $role = strtolower($_GET['r']);

    if (!in_array($role, ['student', 'co-ordinator', 'company'])) {
        echo 'profiles are not viewable.';
        exit();
    }

    $profile = $opr->getUserProfile($user, $role);

    // Get social links
    $socialLinks = [];
    if ($profile && isset($profile['id'])) {
        $socialLinks = $opr->getSocialLinks($profile['id']);
    }

    if ($profile) {
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($profile['firstname'] . ' ' . $profile['lastname']); ?> - Dashboard</title>

            <!-- Bootstrap 5.3 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Font Awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <!-- Google Fonts -->
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
                rel="stylesheet">
            <!-- AOS Animation -->
            <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

            <style>
                :root {
                    --primary-color: #002B6A;
                    --secondary-color: #7267EF;
                    --accent-color: #FF6B6B;
                    --success-color: #28a745;
                    --warning-color: #ffc107;
                    --light-bg: #F8F9FA;
                    --dark-text: #2C3E50;
                    --muted-text: #6C757D;
                    --border-color: #E9ECEF;
                    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.08);
                    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
                    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.16);
                    --gradient-student: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    --gradient-company: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    --gradient-coordinator: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                    --glass-bg: rgba(255, 255, 255, 0.95);
                    --glass-border: rgba(255, 255, 255, 0.2);
                }

                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Inter', sans-serif;
                    background-color: aliceblue;
                    /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
                    color: var(--dark-text);
                    line-height: 1.6;
                    min-height: 100vh;
                    position: relative;
                    overflow-x: hidden;
                }

                body::before {
                    content: '';
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                    pointer-events: none;
                    z-index: -1;
                }

                .dashboard-container {
                    max-width: 1400px;
                    margin: 0 auto;
                    padding: 2rem 1rem;
                    position: relative;
                }

                .dashboard-header {
                    text-align: center;
                    margin-bottom: 3rem;
                    color: white;
                }

                .dashboard-title {
                    font-size: 2.5rem;
                    font-weight: 800;
                    margin-bottom: 0.5rem;
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
                    background: linear-gradient(45deg, #fff, #f0f0f0);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }

                .dashboard-subtitle {
                    font-size: 1.1rem;
                    opacity: 0.9;
                    font-weight: 300;
                }

                .profile-header {
                    background: var(--glass-bg);
                    backdrop-filter: blur(20px);
                    border: 1px solid var(--glass-border);
                    border-radius: 25px;
                    box-shadow: var(--shadow-lg);
                    overflow: hidden;
                    margin-bottom: 2rem;
                    position: relative;
                    display: flex;
                    flex-wrap: wrap;
                    align-items: flex-end;
                    gap: 2rem;
                    padding: 2.5rem 2.5rem 1.5rem 2.5rem;
                    transition: all 0.3s ease;
                }

                .profile-header:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
                }

                .profile-avatar {
                    width: 200px;
                    height: 200px;
                    border-radius: 10px;
                    border: 4px solid white;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    box-shadow: var(--shadow-lg);
                    background: white;
                    overflow: hidden;
                    transition: all 0.3s ease;
                    margin-bottom: 0;
                    position: relative;
                }

                .profile-avatar::before {
                    content: '';
                    position: absolute;
                    top: -2px;
                    left: -2px;
                    right: -2px;
                    bottom: -2px;
                    background: var(--gradient-student);
                    border-radius: 50%;
                    z-index: -1;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .profile-avatar:hover::before {
                    opacity: 1;
                }

                .profile-avatar:hover {
                    transform: scale(1.05) rotate(5deg);
                }

                .profile-avatar img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .profile-info {
                    flex: 1 1 300px;
                    min-width: 250px;
                    padding-left: 0;
                }

                .profile-name {
                    font-size: 2.2rem;
                    font-weight: 700;
                    margin-bottom: 0.5rem;
                    color: var(--dark-text);
                    background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }

                .role-badge {
                    display: inline-block;
                    padding: 0.6rem 1.2rem;
                    border-radius: 10px;
                    font-size: 0.9rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 1rem;
                    box-shadow: var(--shadow-sm);
                    transition: all 0.3s ease;
                }

                .role-badge:hover {
                    transform: translateY(-2px);
                    box-shadow: var(--shadow-md);
                }

                .role-badge.student {
                    background: var(--gradient-student);
                    color: white;
                }

                .role-badge.company {
                    background: var(--gradient-company);
                    color: white;
                }

                .role-badge.coordinator {
                    background: var(--gradient-coordinator);
                    color: white;
                }

                .profile-stats {
                    display: flex;
                    gap: 1.5rem;
                    margin-top: 1rem;
                    flex-wrap: wrap;
                }

                .stat-item {
                    text-align: center;
                    background: rgba(255, 255, 255, 0.9);
                    padding: 1.2rem;
                    border-radius: 15px;
                    min-width: 100px;
                    box-shadow: var(--shadow-sm);
                    transition: all 0.3s ease;
                    border: 1px solid rgba(255, 255, 255, 0.3);
                }

                .stat-item:hover {
                    transform: translateY(-3px);
                    box-shadow: var(--shadow-md);
                    background: rgba(255, 255, 255, 1);
                }

                .stat-value {
                    font-size: 1.8rem;
                    font-weight: 700;
                    color: var(--primary-color);
                    margin-bottom: 0.2rem;
                }

                .stat-label {
                    font-size: 0.8rem;
                    color: var(--muted-text);
                    text-transform: uppercase;
                    font-weight: 500;
                }

                .contact-actions {
                    display: flex;
                    gap: 1rem;
                    margin-top: 1.5rem;
                    flex-wrap: wrap;
                }

                .btn-custom {
                    padding: 0.8rem 1.8rem;
                    border: none;
                    border-radius: 10px;
                    font-weight: 600;
                    text-decoration: none;
                    transition: all 0.3s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-size: 0.9rem;
                    position: relative;
                    overflow: hidden;
                }

                .btn-custom::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                    transition: left 0.5s;
                }

                .btn-custom:hover::before {
                    left: 100%;
                }

                .btn-primary-custom {
                    background: var(--primary-color);
                    color: white;
                    box-shadow: var(--shadow-sm);
                }

                .btn-primary-custom:hover {
                    background: #001a4f;
                    transform: translateY(-2px);
                    box-shadow: var(--shadow-md);
                    color: white;
                }

                .btn-outline-custom {
                    background: transparent;
                    color: var(--primary-color);
                    border: 2px solid var(--primary-color);
                }

                .btn-outline-custom:hover {
                    background: var(--primary-color);
                    color: white;
                    transform: translateY(-2px);
                    box-shadow: var(--shadow-md);
                }

                .dashboard-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                    gap: 2rem;
                    margin-top: 2rem;
                }

                .dashboard-card {
                    background: var(--glass-bg);
                    backdrop-filter: blur(20px);
                    border: 1px solid var(--glass-border);
                    border-radius: 20px;
                    padding: 2rem;
                    box-shadow: var(--shadow-md);
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }

                .dashboard-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 4px;
                    background: var(--gradient-student);
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .dashboard-card:hover::before {
                    opacity: 1;
                }

                .dashboard-card:hover {
                    transform: translateY(-8px);
                    box-shadow: var(--shadow-lg);
                }

                .dashboard-card.student::before {
                    background: var(--gradient-student);
                }

                .dashboard-card.company::before {
                    background: var(--gradient-company);
                }

                .dashboard-card.coordinator::before {
                    background: var(--gradient-coordinator);
                }

                .card-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1.5rem;
                    padding-bottom: 1rem;
                    border-bottom: 1px solid var(--border-color);
                }

                .card-icon {
                    width: 50px;
                    height: 50px;
                    border-radius: 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 1rem;
                    font-size: 1.4rem;
                    color: white;
                    box-shadow: var(--shadow-sm);
                    transition: all 0.3s ease;
                }

                .card-icon:hover {
                    transform: scale(1.1) rotate(5deg);
                }

                .card-icon.student {
                    background: var(--gradient-student);
                }

                .card-icon.company {
                    background: var(--gradient-company);
                }

                .card-icon.coordinator {
                    background: var(--gradient-coordinator);
                }

                .card-title {
                    font-size: 1.5rem;
                    font-weight: 600;
                    margin: 0;
                    color: var(--dark-text);
                }

                .info-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 1rem 0;
                    border-bottom: 1px solid var(--border-color);
                    transition: all 0.3s ease;
                }

                .info-row:hover {
                    background: rgba(255, 255, 255, 0.5);
                    margin: 0 -1rem;
                    padding: 1rem;
                    border-radius: 10px;
                }

                .info-row:last-child {
                    border-bottom: none;
                }

                .info-label {
                    font-weight: 500;
                    color: var(--muted-text);
                    font-size: 0.9rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .info-value {
                    font-weight: 600;
                    color: var(--dark-text);
                    text-align: right;
                }

                .skills-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.8rem;
                    margin-top: 1rem;
                }

                .skill-tag {
                    background: var(--gradient-student);
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 20px;
                    font-size: 0.85rem;
                    font-weight: 500;
                    transition: all 0.3s ease;
                    cursor: pointer;
                }

                .skill-tag:hover {
                    transform: translateY(-2px) scale(1.05);
                    box-shadow: var(--shadow-md);
                }

                .social-links {
                    display: flex;
                    gap: 1rem;
                    margin-top: 1.5rem;
                    flex-wrap: wrap;
                }

                .social-link {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: var(--light-bg);
                    color: var(--muted-text);
                    transition: all 0.3s ease;
                    text-decoration: none;
                    font-size: 1.3rem;
                    box-shadow: var(--shadow-sm);
                }

                .social-link:hover {
                    transform: translateY(-5px) scale(1.1);
                    box-shadow: var(--shadow-lg);
                }

                .social-link.linkedin:hover {
                    background: #0077b5;
                    color: white;
                }

                .social-link.github:hover {
                    background: #333;
                    color: white;
                }

                .social-link.portfolio:hover {
                    background: #ff6b6b;
                    color: white;
                }

                .social-link.youtube:hover {
                    background: #ff0000;
                    color: white;
                }

                .social-link.instagram:hover {
                    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
                    color: white;
                }

                .social-link.leetcode:hover {
                    background: #ffa116;
                    color: white;
                }

                .social-link.hackerrank:hover {
                    background: #1ba94c;
                    color: white;
                }

                .social-link.other:hover {
                    background: var(--primary-color);
                    color: white;
                }

                .progress-section {
                    margin-top: 1.5rem;
                }

                .progress-item {
                    margin-bottom: 1rem;
                }

                .progress-label {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 0.5rem;
                    font-weight: 500;
                }

                .progress-bar-custom {
                    height: 8px;
                    border-radius: 10px;
                    background: rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }

                .progress-fill {
                    height: 100%;
                    border-radius: 10px;
                    background: var(--gradient-student);
                    transition: width 1s ease;
                }

                .achievement-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                    gap: 1rem;
                    margin-top: 1rem;
                }

                .achievement-item {
                    text-align: center;
                    padding: 1rem;
                    background: rgba(255, 255, 255, 0.8);
                    border-radius: 15px;
                    transition: all 0.3s ease;
                }

                .achievement-item:hover {
                    transform: translateY(-3px);
                    background: rgba(255, 255, 255, 1);
                    box-shadow: var(--shadow-md);
                }

                .achievement-icon {
                    font-size: 2rem;
                    margin-bottom: 0.5rem;
                    color: var(--primary-color);
                }

                .achievement-value {
                    font-size: 1.2rem;
                    font-weight: 700;
                    color: var(--dark-text);
                }

                .achievement-label {
                    font-size: 0.8rem;
                    color: var(--muted-text);
                    text-transform: uppercase;
                }

                /* Responsive Design */
                @media (max-width: 1200px) {
                    .dashboard-grid {
                        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    }
                }

                @media (max-width: 768px) {
                    .dashboard-container {
                        padding: 1rem;
                    }

                    .dashboard-title {
                        font-size: 2rem;
                    }

                    .profile-header {
                        margin-bottom: 1rem;
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 1rem;
                        padding: 1.5rem 1rem 1rem 1rem;
                    }

                    .profile-avatar {
                        width: 100px;
                        height: 100px;
                    }

                    .profile-name {
                        font-size: 1.8rem;
                    }

                    .dashboard-grid {
                        grid-template-columns: 1fr;
                        gap: 1.5rem;
                    }

                    .profile-stats {
                        gap: 1rem;
                    }

                    .stat-item {
                        min-width: 80px;
                        padding: 1rem;
                    }

                    .stat-value {
                        font-size: 1.5rem;
                    }
                }

                @media (max-width: 480px) {
                    .profile-avatar {
                        width: 80px;
                        height: 80px;
                    }

                    .profile-name {
                        font-size: 1.5rem;
                    }

                    .contact-actions {
                        flex-direction: column;
                    }

                    .btn-custom {
                        width: 100%;
                        justify-content: center;
                    }
                }

                /* Animation Classes */
                .fade-in {
                    animation: fadeIn 0.8s ease-in-out;
                }

                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .slide-up {
                    animation: slideUp 0.6s ease-out;
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(40px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .pulse {
                    animation: pulse 2s infinite;
                }

                @keyframes pulse {
                    0% {
                        transform: scale(1);
                    }

                    50% {
                        transform: scale(1.05);
                    }

                    100% {
                        transform: scale(1);
                    }
                }

                .float {
                    animation: float 3s ease-in-out infinite;
                }

                @keyframes float {

                    0%,
                    100% {
                        transform: translateY(0px);
                    }

                    50% {
                        transform: translateY(-10px);
                    }
                }

                .glow {
                    animation: glow 2s ease-in-out infinite alternate;
                }

                @keyframes glow {
                    from {
                        box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
                    }

                    to {
                        box-shadow: 0 0 20px rgba(102, 126, 234, 0.8);
                    }
                }
            </style>
        </head>

        <body>
            <div class="dashboard-container">

                <!-- Profile Header -->
                <div class="profile-header <?php echo $role; ?> fade-in" data-aos="fade-up">
                    <div class="profile-avatar pulse">
                        <?php if ($profile['userimage']): ?>
                            <img src="<?php echo BASE_URL . 'uploads/auth/' . htmlspecialchars($profile['userimage']); ?>"
                                alt="<?php echo htmlspecialchars($profile['firstname']); ?>">
                        <?php else: ?>
                            <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" alt="Default Profile">
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <h1 class="profile-name d-flex align-items-center gap-2">
                            <?php
                            if ($role === 'company') {
                                echo htmlspecialchars($profile['cname'] ?? $profile['firstname']);
                            } else {
                                echo htmlspecialchars($profile['firstname'] . ' ' . $profile['lastname']);
                            }
                            ?>

                        </h1>
                        <span class="role-badge <?php echo $role; ?>">
                            <i
                                class="fas fa-<?php echo $role === 'student' ? 'graduation-cap' : ($role === 'company' ? 'building' : 'user-tie'); ?> me-2"></i>
                            <?php echo ucfirst(str_replace('-', ' ', $role)); ?>
                        </span>

                        <div class="profile-stats">
                            <?php if ($role === 'student'): ?>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo number_format($profile['cgpa'] ?? 0, 2); ?></div>
                                    <div class="stat-label">CGPA</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">
                                        <?php echo count(array_filter(explode(',', $profile['skills'] ?? ''))); ?></div>
                                    <div class="stat-label">Skills</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo htmlspecialchars($profile['branch'] ?? 'N/A'); ?></div>
                                    <div class="stat-label">Branch</div>
                                </div>
                            <?php elseif ($role === 'company'): ?>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo htmlspecialchars($profile['csize'] ?? 'N/A'); ?></div>
                                    <div class="stat-label">Employees</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo htmlspecialchars($profile['ctype'] ?? 'N/A'); ?></div>
                                    <div class="stat-label">Type</div>
                                </div>
                            <?php elseif ($role === 'co-ordinator'): ?>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo htmlspecialchars($profile['designation'] ?? 'N/A'); ?></div>
                                    <div class="stat-label">Designation</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo htmlspecialchars($profile['department'] ?? 'N/A'); ?></div>
                                    <div class="stat-label">Department</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="contact-actions">
                            <a href="mailto:<?php echo htmlspecialchars($profile['mail']); ?>"
                                class="btn-custom btn-primary-custom">
                                <i class="fas fa-envelope"></i>
                                Contact
                            </a>
                            <?php
                            $linkedinUrl = '';
                            if (isset($profile['type']) && strtolower($profile['type']) === 'link' && !empty($profile['link'])) {
                                $linkedinUrl = $profile['link'];
                            } elseif (!empty($profile['linkedin'])) {
                                $linkedinUrl = $profile['linkedin'];
                            }
                            ?>
                            <?php if ($linkedinUrl): ?>
                                <a href="<?php echo htmlspecialchars($linkedinUrl); ?>" class="btn-custom btn-outline-custom"
                                    target="_blank">
                                    <i class="fab fa-linkedin"></i>
                                    LinkedIn
                                </a>
                            <?php endif; ?>
                            <?php if ($role === 'company' && $profile['cweb']): ?>
                                <a href="<?php echo htmlspecialchars($profile['cweb']); ?>" class="btn-custom btn-outline-custom"
                                    target="_blank">
                                    <i class="fas fa-globe"></i>
                                    Website
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Grid -->
                <div class="dashboard-grid">
                    <!-- Personal Information -->
                    <div class="dashboard-card <?php echo $role; ?> slide-up" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header">
                            <div class="card-icon <?php echo $role; ?>">
                                <i class="fas fa-user"></i>
                            </div>
                            <h3 class="card-title">Personal Information</h3>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-id-card"></i>
                                Full Name
                            </span>
                            <span class="info-value">
                                <?php echo htmlspecialchars($profile['firstname'] . ' ' . $profile['middlename'] . ' ' . $profile['lastname']); ?>
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </span>
                            <span class="info-value"><?php echo htmlspecialchars($profile['mail']); ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-phone"></i>
                                Phone
                            </span>
                            <span class="info-value"><?php echo htmlspecialchars($profile['contact']); ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-venus-mars"></i>
                                Gender
                            </span>
                            <span class="info-value text-capitalize"><?php echo htmlspecialchars($profile['gender']); ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Location
                            </span>
                            <span class="info-value">
                                <?php echo htmlspecialchars($profile['city'] . ', ' . $profile['state']); ?>
                            </span>
                        </div>

                        <?php if ($role === 'company'): ?>
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-globe"></i>
                                    Website
                                </span>
                                <span class="info-value">
                                    <a href="<?php echo htmlspecialchars($profile['cweb'] ?? '#'); ?>" target="_blank"
                                        class="text-decoration-none">
                                        <?php echo htmlspecialchars($profile['cweb'] ?? 'N/A'); ?>
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($role === 'student'): ?>
                        <!-- Academic Information -->
                        <div class="dashboard-card student slide-up" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header">
                                <div class="card-icon student">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h3 class="card-title">Academic Details</h3>
                            </div>

                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-id-badge"></i>
                                    Student ID
                                </span>
                                <span class="info-value"><?php echo htmlspecialchars($profile['studentid'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-code-branch"></i>
                                    Branch
                                </span>
                                <span class="info-value"><?php echo htmlspecialchars($profile['branch'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-star"></i>
                                    CGPA
                                </span>
                                <span class="info-value"><?php echo number_format($profile['cgpa'] ?? 0, 2); ?></span>
                            </div>

                            <div class="progress-section">
                                <div class="progress-item">
                                    <div class="progress-label">
                                        <span>10th Percentage</span>
                                        <span><?php echo number_format($profile['pass10percentage'] ?? 0, 2); ?>%</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-fill"
                                            style="width: <?php echo min(100, $profile['pass10percentage'] ?? 0); ?>%"></div>
                                    </div>
                                </div>

                                <div class="progress-item">
                                    <div class="progress-label">
                                        <span>12th Percentage</span>
                                        <span><?php echo number_format($profile['pass12percentage'] ?? 0, 2); ?>%</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-fill"
                                            style="width: <?php echo min(100, $profile['pass12percentage'] ?? 0); ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Skills & Achievements -->
                        <div class="dashboard-card student slide-up" data-aos="fade-up" data-aos-delay="300">
                            <div class="card-header">
                                <div class="card-icon student">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <h3 class="card-title">Skills & Expertise</h3>
                            </div>

                            <div class="skills-container">
                                <?php
                                $skills = array_filter(explode(',', $profile['skills'] ?? ''));
                                foreach ($skills as $skill):
                                    if (trim($skill)): ?>
                                        <span class="skill-tag"><?php echo htmlspecialchars(trim($skill)); ?></span>
                                <?php endif;
                                endforeach;
                                ?>
                            </div>

                            <div class="achievement-grid">
                                <div class="achievement-item">
                                    <div class="achievement-icon">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div class="achievement-value"><?php echo count($skills); ?></div>
                                    <div class="achievement-label">Skills</div>
                                </div>
                                <div class="achievement-item">
                                    <div class="achievement-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="achievement-value"><?php echo number_format($profile['cgpa'] ?? 0, 1); ?></div>
                                    <div class="achievement-label">CGPA</div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="dashboard-card student slide-up" data-aos="fade-up" data-aos-delay="400">
                            <div class="card-header">
                                <div class="card-icon student">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h3 class="card-title">Documents</h3>
                            </div>

                            <?php if ($profile['resume']): ?>
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="fas fa-file-pdf"></i>
                                        Resume
                                    </span>
                                    <span class="info-value">
                                        <a href="<?php echo BASE_URL . htmlspecialchars($profile['resume']); ?>" target="_blank"
                                            class="text-decoration-none">
                                            <i class="fas fa-download me-1"></i>Download
                                        </a>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if ($profile['certificate']): ?>
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="fas fa-certificate"></i>
                                        Certificate
                                    </span>
                                    <span class="info-value">
                                        <a href="<?php echo BASE_URL . htmlspecialchars($profile['certificate']); ?>" target="_blank"
                                            class="text-decoration-none">
                                            <i class="fas fa-download me-1"></i>Download
                                        </a>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php elseif ($role === 'company'): ?>
                        <!-- Company Details -->
                        <div class="dashboard-card company slide-up" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header">
                                <div class="card-icon company">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h3 class="card-title">Company Details</h3>
                            </div>

                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-building"></i>
                                    Company Name
                                </span>
                                <span class="info-value"><?php echo htmlspecialchars($profile['cname'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-industry"></i>
                                    Type
                                </span>
                                <span class="info-value"><?php echo htmlspecialchars($profile['ctype'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-users"></i>
                                    Size
                                </span>
                                <span class="info-value"><?php echo htmlspecialchars($profile['csize'] ?? 'N/A'); ?>
                                    employees</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-briefcase"></i>
                                    Specialization
                                </span>
                                <span class="info-value">
                                    <?php echo htmlspecialchars($profile['specialization'] ?? 'N/A'); ?>
                                </span>
                            </div>
                        </div>

                    <?php elseif ($role === 'co-ordinator'): ?>
                        <!-- Co-ordinator Details -->
                        <div class="dashboard-card coordinator slide-up" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header">
                                <div class="card-icon coordinator">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <h3 class="card-title">Coordinator Details</h3>
                            </div>

                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-id-badge"></i>
                                    Designation
                                </span>
                                <span class="info-value"><?php echo htmlspecialchars($profile['designation'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-university"></i>
                                    Department
                                </span>
                                <span class="info-value"><?php echo htmlspecialchars($profile['department'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-clock"></i>
                                    Experience
                                </span>
                                <span class="info-value"><?php echo htmlspecialchars($profile['experience'] ?? 'N/A'); ?>
                                    years</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Bio Section -->
                    <?php if (!empty($profile['bio'])): ?>
                        <div class="dashboard-card <?php echo $role; ?> slide-up" data-aos="fade-up" data-aos-delay="500">
                            <div class="card-header">
                                <div class="card-icon <?php echo $role; ?>">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h3 class="card-title">About</h3>
                            </div>
                            <p class="bio-text"><?php echo nl2br(htmlspecialchars($profile['bio'])); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Social Links -->
                    <?php if (!empty($socialLinks)): ?>
                        <div class="dashboard-card <?php echo $role; ?> slide-up" data-aos="fade-up" data-aos-delay="600">
                            <div class="card-header">
                                <div class="card-icon <?php echo $role; ?>">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                                <h3 class="card-title">Social Profiles</h3>
                            </div>
                            <div class="social-links">
                                <?php foreach ($socialLinks as $link):
                                    $icon = '';
                                    $class = '';
                                    $url = htmlspecialchars($link['link']);
                                    switch (strtolower($link['platform'])) {
                                        case 'linkedin':
                                            $icon = 'fab fa-linkedin-in';
                                            $class = 'linkedin';
                                            break;
                                        case 'github':
                                            $icon = 'fab fa-github';
                                            $class = 'github';
                                            break;
                                        case 'portfolio':
                                            $icon = 'fas fa-globe';
                                            $class = 'portfolio';
                                            break;
                                        case 'youtube':
                                            $icon = 'fab fa-youtube';
                                            $class = 'youtube';
                                            break;
                                        case 'instagram':
                                            $icon = 'fab fa-instagram';
                                            $class = 'instagram';
                                            break;
                                        case 'leetcode':
                                            $icon = 'fa-solid fa-code';
                                            $class = 'leetcode';
                                            break;
                                        case 'hackerrank':
                                            $icon = 'fab fa-hackerrank';
                                            $class = 'hackerrank';
                                            break;
                                        default:
                                            $icon = 'fas fa-link';
                                            $class = 'other';
                                    }
                                ?>
                                    <a href="<?php echo $url; ?>" class="social-link <?php echo $class; ?>" target="_blank"
                                        title="<?php echo ucfirst($link['platform']); ?>">
                                        <i class="<?php echo $icon; ?>"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
            <!-- AOS Animation -->
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
            <script>
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true
                });

                // Add scroll animations
                document.addEventListener('DOMContentLoaded', function() {
                    const cards = document.querySelectorAll('.dashboard-card');
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.style.opacity = '1';
                                entry.target.style.transform = 'translateY(0)';
                            }
                        });
                    });

                    cards.forEach(card => {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        card.style.transition = 'all 0.6s ease';
                        observer.observe(card);
                    });
                });
            </script>
        </body>

        </html>
<?php
    } else {
        echo '<div class="container mt-5"><div class="alert alert-danger">Profile not found.</div></div>';
    }
} else {
    echo '<div class="container mt-5"><div class="alert alert-warning">Invalid profile link.</div></div>';
}
?>