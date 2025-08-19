<?php
require_once '../config.php';
require_once '../components/navbar.php';

if ($isLoggedIn === false && !isset($_SESSION['mail'])) {
    echo "<script>window.location.href = '" . BASE_URL . "auth/login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>styles/main.css">
    <style>
    .dashboard-profile {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .profile-image img {
        width: 100%;
        max-width: 150px;
        height: auto;
        object-fit: cover;
        display: block;
        margin: 0 auto;
        border-radius: 10px;
        aspect-ratio: 1;
    }

    .profile-name {
        text-align: center;
        font-size: 1.1rem;
        margin-top: 10px;
    }

    .profile-role,
    .profile-location {
        text-align: center;
        color: #6c757d;
    }

    .option {
        text-align: center;
        margin-top: 15px;
    }
    </style>
</head>

<body>
    <section class="container-fluid mt-2">
        <div class="row g-3">
            <!-- Profile Section -->
            <div class="col-12 col-lg-2 col-md-3 col-sm-5">
                <div class="dashboard-profile">
                    <div class="profile-content">
                        <div class="profile-image">
                            <?php if ($auth['userimage']) { ?>
                            <img src="<?php echo BASE_URL . 'uploads/auth/' . $auth['userimage']; ?>" alt="User Image">
                            <?php } else { ?>
                            <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" alt="User Image">
                            <?php } ?>
                        </div>
                        <div class="profile-name">
                            <strong><?php echo $auth['firstname'] . ' ' . $auth['lastname']; ?></strong>
                        </div>
                        <div class="profile-role">
                            <small><?php echo $auth['authrole'] ?: " "; ?></small>
                        </div>
                        <div class="profile-location">
                            <small>
                                <?php echo $auth['city'] ?: " ";
                                if ($auth['state']) {
                                    echo ', ';
                                }
                                echo $auth['state'] ?: " "; ?>
                            </small>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="option">
                        <?php if ($auth['authrole'] === 'admin' || $auth['authrole'] === 'co-ordinator') { ?>
                        <a href="<?php echo BASE_URL ?>admin/" class="btn btn-outline-danger">Manage</a>
                        <?php } ?>
                        <?php $encId = base64_encode($auth['mail']); ?>
                        <a href="<?php echo BASE_URL ?>account/settings/"
                            class="btn btn-outline-primary <?php echo $auth['authrole'] === 'student' ? 'w-100' : ''; ?>">Settings</a>
                    </div>

                    <!-- Reminders -->
                    <div class="reminders mt-4">
                        <h5>Reminders</h5>
                        <p>There are no reminders set.</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="col-12 col-lg-10 col-md-9 col-sm-7">
                <div class="dashboard-content">
                    <?php
                    if ($auth['authrole'] === 'admin') {
                        require_once 'admin.php';
                    } else if ($auth['authrole'] === 'student') {
                        require_once 'student.php';
                    }
                    ?>
                    <?php require_once '../components/footer.php'; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>