<?php
require_once '../config.php';
require_once '../components/navbar.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Email and Password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $auth = $opr->login($email);
        if ($auth && password_verify($password, $auth['password'])) {
            $isLoggedIn = true;
            $_SESSION['mail'] = $email;

            $_SESSION['alert'] = [];
            $_SESSION['alert'][] = "<strong>Hey! $email </strong>Login Successfully";
            echo "<script>window.location.href='" . BASE_URL . "account/dashboard.php';</script>";
            exit();
        } else {
            $error = "Invalid Username or Password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal - Login</title>
</head>

<body>
    <section class="container-fluid">
        <div class="container-login mt-5">
            <div class="login-form d-flex justify-content-center align-item-center">
                <div class="card p-3 col-md-3">
                    <div class="card-body">
                        <?php
                        if (isset($error)) {
                            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                        }
                        ?>
                        <form method="post">
                            <div>
                                <h3 class="text-center">Login</h3>
                            </div>
                            <div class="form-group">
                                <input type="mail" class="form-control" placeholder="Enter Email" name="email">
                            </div>
                            <div class="form-group mt-2">
                                <input type="password" class="form-control" placeholder="Enter Password"
                                    name="password">
                            </div>
                            <div class="form-group mt-3">
                                <input type="submit" id="loginBtn" value="Login" name="login"
                                    class="btn btn-primary w-100">
                            </div>
                            <div class="form-group mt-2 d-flex justify-content-end">
                                <a href="<?php echo BASE_URL; ?>auth/forgotpassword.php">Forgot Password?</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</body>

</html>