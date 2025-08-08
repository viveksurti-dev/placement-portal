<?php
require_once '../config.php';
require_once '../components/navbar.php';

// Handle async content loading
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    header('Content-Type: application/json');

    $content = '';
    $section = $_GET['section'] ?? '';

    switch ($section) {
        case 'dashboard':
            $content = '<h2>Dashboard</h2><p>Welcome to the admin dashboard. Manage your system here.</p>';
            break;
        case 'users':
            $content = '<h2>User Management</h2><p>Manage user accounts, permissions, and roles.</p>';
            break;
        case 'settings':
            $content = '<h2>Settings</h2><p>Configure system settings and preferences.</p>';
            break;
        default:
            $content = '<h2>Select a section</h2><p>Choose an option from the sidebar to view content.</p>';
    }

    echo json_encode(['content' => $content]);
    exit;
}

// Get current section
$currentSection = $_GET['section'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
    :root {
        --sidebar-bg: #2c3e50;
        --sidebar-hover: #34495e;
        --accent-color: #3498db;
        --danger-color: #e74c3c;
    }

    body {
        background-color: #f8f9fa;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .admin-container {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 280px;
        background-color: var(--sidebar-bg);
        color: white;
        transition: all 0.3s ease;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1000;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--sidebar-hover);
        margin-bottom: 20px;
    }

    .sidebar-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .sidebar-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex: 1;
    }

    .sidebar-btn {
        background-color: transparent;
        color: white;
        border: 1px solid transparent;
        padding: 12px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .sidebar-btn:hover {
        background-color: var(--sidebar-hover);
        border-color: var(--accent-color);
    }

    .sidebar-btn.active {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .back-btn {
        background-color: #95a5a6;
        margin-top: auto;
        border: none;
    }

    .back-btn:hover {
        background-color: #7f8c8d;
    }

    .content {
        flex: 1;
        margin-left: 280px;
        height: 100vh;
        display: flex;
        flex-direction: column;
        background-color: white;
        transition: margin-left 0.3s ease;
    }

    .content-header {
        background-color: white;
        padding: 20px;
        border-bottom: 1px solid #dee2e6;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .content-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .content-section {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .content-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 200px;
        color: #6c757d;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--accent-color);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin-bottom: 10px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            transform: translateX(-100%);
            position: fixed;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .content {
            margin-left: 0;
        }

        .mobile-menu-toggle {
            display: block;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1001;
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    }

    .mobile-menu-toggle {
        display: none;
    }

    @media (max-width: 768px) {
        .mobile-menu-toggle {
            display: block;
        }
    }

    /* Bootstrap overrides */
    .btn-primary {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .card {
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    </style>
</head>

<body>
    <?php require_once '../components/navbar.php'; ?>

    <div class="admin-container">
        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle d-md-none" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2 class="sidebar-title">Admin Panel</h2>
            </div>

            <div class="sidebar-buttons">
                <button class="sidebar-btn active" data-section="dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </button>
                <button class="sidebar-btn" data-section="users">
                    <i class="fas fa-users"></i> User Management
                </button>
                <button class="sidebar-btn" data-section="settings">
                    <i class="fas fa-cog"></i> Settings
                </button>
            </div>

            <button class="sidebar-btn back-btn" onclick="window.location.href='../account/dashboard.php'">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </button>
        </aside>

        <!-- Content Area -->
        <main class="content" id="content">
            <div class="content-header">
                <h1>Admin Management</h1>
            </div>

            <div class="content-body">
                <div id="loading" class="loading">
                    <div class="spinner"></div>
                    <p>Loading content...</p>
                </div>

                <div id="content-area">
                    <?php if (empty($currentSection)): ?>
                    <div class="text-center">
                        <h2>Welcome to Admin Panel</h2>
                        <p class="text-muted">Select an option from the sidebar to manage your system.</p>
                    </div>
                    <?php else: ?>
                    <div class="content-section active">
                        <?php
                            switch ($currentSection) {
                                case 'dashboard':
                                    echo '<h2>Dashboard</h2><p>Welcome to the admin dashboard. Manage your system here.</p>';
                                    break;
                                case 'users':
                                    echo '<h2>User Management</h2><p>Manage user accounts, permissions, and roles.</p>';
                                    break;
                                case 'settings':
                                    echo '<h2>Settings</h2><p>Configure system settings and preferences.</p>';
                                    break;
                                default:
                                    echo '<h2>Select a section</h2><p>Choose an option from the sidebar to view content.</p>';
                            }
                            ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Mobile menu toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('show');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.querySelector('.mobile-menu-toggle');

        if (window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            !toggle.contains(e.target)) {
            sidebar.classList.remove('show');
        }
    });

    // Async content loading
    async function loadContent(section) {
        const contentArea = document.getElementById('content-area');
        const loading = document.getElementById('loading');

        loading.style.display = 'flex';
        contentArea.style.display = 'none';

        try {
            const response = await fetch(`?ajax=1&section=${section}`);
            const data = await response.json();

            // Update URL
            const url = new URL(window.location);
            url.searchParams.set('section', section);
            window.history.pushState({}, '', url);

            // Update active state
            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-section="${section}"]`).classList.add('active');

            // Hide loading and show content
            loading.style.display = 'none';
            contentArea.style.display = 'block';
            contentArea.innerHTML = data.content;

        } catch (error) {
            console.error('Error loading content:', error);
            loading.style.display = 'none';
            contentArea.style.display = 'block';
            contentArea.innerHTML =
                '<div class="alert alert-danger">Error loading content. Please try again.</div>';
        }
    }

    // Sidebar button click handlers
    document.querySelectorAll('.sidebar-btn[data-section]').forEach(button => {
        button.addEventListener('click', function() {
            const section = this.dataset.section;
            loadContent(section);
        });
    });

    // Handle browser back/forward
    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const section = urlParams.get('section');
        if (section) {
            loadContent(section);
        }
    });

    // Set initial active state
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const section = urlParams.get('section');
        if (section) {
            loadContent(section);
        }
    });
    </script>
</body>

</html>
]]>