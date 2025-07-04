<?php
require_once '../config.php';
require_once '../components/navbar.php';


if ($isLoggedIn === false && isset($_SESSION['mail'])) {
    echo "<script>window.location.href = '" . BASE_URL . "';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal - Dashboard</title>
</head>

<body>
    <section>
        <!-- Dashboard  -->
        <?php
        // Admin Dashboard
        if ($auth['authrole'] === 'admin') {
        ?>
        <section class="container-dashboard">
            <div class="dashboard-content col-md-8">

            </div>
            <div class="dashboard-profile col-md-4">
                <div class="profile-content">
                    <div class="profile-menus">
                        <!-- menus on right side -->
                    </div>
                    <div class="profile-image">
                        <?php if ($auth['userimage']) { ?>

                        <?php } else { ?>
                        <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" alt="User Image">
                        <?php } ?>
                    </div>
                    <div class="profile-name mt-2">
                        <strong><?php echo $auth['firstname'] . ' ' . $auth['lastname']; ?></strong>
                    </div>
                    <div class="profile-role">
                        <small class="caption"><?php echo $auth['authrole'] ?></small>
                    </div>
                    <div class="profile-location">
                        <small><?php echo $auth['city'] . ', ' . $auth['state']; ?></small>
                    </div>
                </div>
                <div class="option">

                </div>
                <div class="reminders">
                    <div class="container-reminder d-flex flex-wrap">

                    </div>
                </div>
            </div>
        </section>

        <?php } else if ($auth['authrole'] == 'company') {
            echo 'company';
        } else if ($auth['authrole'] == 'coordinator') {
            echo 'mentor';
        } else {
            echo 'student';
        }
        ?>
    </section>
</body>

</html>