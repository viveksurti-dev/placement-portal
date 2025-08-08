<?php
require_once '../config.php';
require_once '../components/navbar.php';
require_once '../mailStructure.php';

$token = $_GET['t'] ?? '';
$encryptedEmail = $_GET['e'] ?? '';

if (empty($token) || empty($encryptedEmail)) {
    header("Location: ../auth/login.php");
    exit();
}

$email = $obj->decryptEmail($encryptedEmail);
$opr->mailSendForPassword($email);

if (!isset($_COOKIE['reset_token']) || $_COOKIE['reset_token'] !== $token) {
    $error = "Invalid or expired reset link. Please request a new password reset.";
}

if (isset($_POST['resetpassword'])) {
    $newPassword = $_POST['newpassword'];
    $confirmPassword = $_POST['confirmpassword'];

    // Validate passwords
    if (empty($newPassword) || empty($confirmPassword)) {
        $error = "Please enter both password fields.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($newPassword) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        if ($obj->updatePassword($email, $newPassword)) {
            try {
                $firstname = $user['firstname'];
                require_once '../mails/newpassword.php';
                $mail->Body = ob_get_clean();
                setcookie('reset_token', '', time() - 3600, '/');
                $success = "Your password has been successfully updated. You can now login with your new password.";

                echo "<script>setTimeout(function(){ window.location.href = '../auth/login.php'; }, 3000);</script>";
            } catch (Exception $e) {
                $error = "Password updated successfully, but failed to send confirmation email.";
            }
        } else {
            $error = "Failed to update password. Please try again.";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body>
    <section>
        <div class="container d-flex justify-content-center align-items-center mt-5">
            <div class="card p-3 col-md-4">
                <div class="heading text-center mt-3">
                    <h4>
                        Reset Password
                    </h4>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php else: ?>
                    <form method="post" autocomplete="off">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($encryptedEmail); ?>">

                        <div class="form-group mt-3">
                            <label for="newpassword">New Password</label>
                            <input type="password" class="form-control" name="newpassword" placeholder="Enter new password"
                                required minlength="6" />
                        </div>
                        <div class="form-group mt-3">
                            <label for="confirmpassword">Confirm Password</label>
                            <input type="password" class="form-control" name="confirmpassword"
                                placeholder="Confirm new password" required minlength="6" />
                        </div>
                        <div class="form-group mt-3">
                            <input type="submit" name="resetpassword" value="Reset Password"
                                class="btn btn-primary w-100" />
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>

</html>