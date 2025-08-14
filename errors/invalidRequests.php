<?php
require_once '../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Invalid Request</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT_PATH ?>styles/main.css">
</head>

<body>

    <div class="container text-center mt-5">
        <img src="<?php echo BASE_URL ?>uploads/asstets/badRequest.png" alt="Error" loading="lazy"
            style="max-width:400px;">
        <h3 class="mt-3 text-danger">Invalid Request</h3>
        <p class="countdown">You will be redirected to the home page in <span id="countdown">3</span> seconds</p>
        <a href="index.php" class="btn btn-primary mt-2">Go to Home</a>
    </div>

    <script>
        function redirectToHome() {
            var countdownElement = document.getElementById('countdown');
            var countdownValue = 3;

            function updateCountdown() {
                countdownElement.textContent = countdownValue;
                if (countdownValue === 0) {
                    window.location.href = '<?php echo BASE_URL; ?>';
                } else {
                    countdownValue--;
                    setTimeout(updateCountdown, 1000);
                }
            }

            updateCountdown();
        }

        window.onload = redirectToHome;
    </script>

</body>

</html>