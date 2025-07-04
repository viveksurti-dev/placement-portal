<?php
require_once '../config.php';
require_once '../components/navbar.php';

if (isset($_POST['forgotpassword'])) {
    $email = $_POST['email'];

    if (empty($email)) {
        $error = 'please enter an Email';
    } else {
        if ($opr->mailSendForPassword($email)) {
            echo 'mail sented';
        } else {
            $error = 'please enter valid email';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <section id="forgot-password">
        <div class="container-fluid d-flex justify-content-center align-items-center mt-5">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group text-center mb-4">
                            <h3>
                                Forgot Password
                            </h3>
                        </div>
                        <div>
                            <?php
                            if (isset($error)) {
                                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                            }
                            ?>
                        </div>
                        <form method="post">
                            <div class="form-group mt-3">
                                <input type="email" class="form-control" placeholder="Enter Your Email" name="email"
                                    required />
                            </div>
                            <div class="form-group mt-3">
                                <input type="submit" name="forgotpassword" value="Send" class="btn btn-primary w-100">
                            </div>
                        </form>
                        <div class="mt-3">
                            <small>
                                <strong>
                                    Enter Your Email :
                                </strong>
                                <ul>
                                    <li>
                                        Forget Password link will send on your email.
                                    </li>
                                    <li>
                                        please ensure that, this email is yours.
                                    </li>
                                </ul>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>