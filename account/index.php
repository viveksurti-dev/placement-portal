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

</head>

<body>
    <section>
        <!-- Dashboard  -->
        <section class="container-dashboard ">
            <div class="dashboard-content col-md-9 d-flex flex-wrap">
                <?php
                if ($auth['authrole'] === 'admin') {
                    require_once 'admin.php';
                } else if ($auth['authrole'] === 'student') {
                    require_once 'student.php';
                } ?>
            </div>
            <div class="dashboard-profile col-md-3">
                <div class="profile-content">
                    <div class="profile-menus">
                        <!-- menus on right side -->
                    </div>
                    <div class="profile-image">
                        <?php if ($auth['userimage']) { ?>
                            <img src="<?php echo BASE_URL . 'uploads/auth/' . $auth['userimage']; ?>" alt="User Image">
                        <?php } else { ?>
                            <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" alt="User Image">
                        <?php } ?>
                    </div>
                    <div class="profile-name mt-2">
                        <strong><?php echo $auth['firstname'] . ' ' . $auth['lastname']; ?></strong>
                    </div>
                    <div class="profile-role">
                        <small class="caption"><?php echo $auth['authrole'] ? $auth['authrole'] : " "; ?></small>
                    </div>
                    <div class="profile-location">
                        <small><?php echo $auth['city'] ? $auth['city'] : " ";
                                if ($auth['state']) {
                                    echo ', ';
                                }
                                echo $auth['state'] ? $auth['state'] : " " ?></small>
                    </div>
                </div>

                <!-- options -->
                <div class="option">
                    <a href="<?php echo BASE_URL ?>admin/" class="btn btn-outline-danger">Manage</a>
                    <?php
                    $encId = base64_encode($auth['mail']);
                    ?>
                    <a href="<?php echo BASE_URL ?>account/editProfile.php?user=<?php echo $encId ?>"
                        class="btn btn-outline-primary">Edit
                        Profile</a>
                </div>
                <div class="reminders">
                    <div class="container-reminder">
                        <div class="reminder">
                            <div class="reminder-title">
                                <h5>Reminders</h5>
                            </div>
                            <div class="reminder-content">
                                <p>There are no reminders set.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </section>
</body>

</html>

<script>
    // Wait until the DOM is ready
    document.addEventListener("DOMContentLoaded", function() {
        // Get the canvas element
        var ctx = document.getElementById('comboChart').getContext('2d');

        // Create a new Chart
        var myComboChart = new Chart(ctx, {
            type: 'bar', // Start with 'bar' type for bars
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], // X-axis labels
                datasets: [{
                    label: 'Bar Dataset',
                    data: [12, 19, 3, 5, 2, 3, 9], // Data for bars
                    backgroundColor: 'rgba(114, 103, 239, 0.85)', // Bar color
                    borderColor: 'rgb(114, 103, 239)', // Bar border color
                    borderWidth: 1,
                    yAxisID: 'y'
                }, {
                    label: 'Line Dataset',
                    data: [7, 11, 5, 8, 6, 3, 4], // Data for line
                    type: 'line', // Specify type as 'line' for the second dataset
                    fill: false, // Disable fill for the line chart
                    borderColor: 'rgba(255, 0, 34, 1)', // Line color
                    tension: 0.1, // Line smoothness
                    // yAxisID: 'y1' // Use different y-axis for the line dataset
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false, // Disable vertical grid lines
                        }
                    },
                    y: {
                        ticks: {
                            beginAtZero: true
                        }
                    }
                }
            }
        });
    });
</script>