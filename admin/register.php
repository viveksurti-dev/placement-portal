<?php
require_once '../config.php';
require_once '../components/navbar.php';
require_once '../mailStructure.php';

if ($isLoggedIn === false && !isset($_SESSION['mail'])) {
    echo "<script>window.location.href = '" . BASE_URL . "auth/login.php';</script>";
    exit;
}

$errors = [
    'firstname' => '',
    'lastname' => '',
    'email' => '',
    'gender' => '',
    'city' => '',
    'state' => '',
    'contact' => '',
    'role' => ''
];

$firstname = $middlename = $lastname = $email = $gender = $city = $state = $contact = $role = '';

if (isset($_POST['register'])) {

    function generatePassword(int $length = 8): string
    {
        $chars = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, count($chars) - 1)];
        }
        return $str;
    }

    $corePass = generatePassword();
    $password = password_hash($corePass, PASSWORD_DEFAULT);

    $firstname = trim($_POST['firstname'] ?? '');
    $middlename = trim($_POST['middlename'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['State'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $role = $_POST['role'] ?? '';

    if ($firstname === '') {
        $errors['firstname'] = "First Name is required.";
    }
    if ($lastname === '') {
        $errors['lastname'] = "Last Name is required.";
    }
    if ($email === '') {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }
    if ($gender === '' || !in_array($gender, ['male', 'female', 'other'])) {
        $errors['gender'] = "Gender is required.";
    }
    if ($city === '') {
        $errors['city'] = "City is required.";
    }
    if ($state === '') {
        $errors['state'] = "State is required.";
    }
    if ($contact === '') {
        $errors['contact'] = "Contact Number is required.";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $contact)) {
        $errors['contact'] = "Contact Number must be 10-15 digits.";
    }
    if ($role === '' || !in_array($role, ['co-ordinator', 'company', 'student'])) {
        $errors['role'] = "Role is required.";
    }

    $hasError = false;
    foreach ($errors as $err) {
        if (!empty($err)) {
            $hasError = true;
            break;
        }
    }

    if (!$hasError) {
        $authId = $opr->register($firstname, $middlename, $lastname, $email, $gender, $city, $state, $contact, $role, $password);

        if ($authId) {
            if ($role === 'student') {
            }

            require_once '../mails/newAuth.php';
            $_SESSION['alert'] = [];
            $_SESSION['alert'][] = "New $role registered!";
            echo "<script>window.location.href = '" . BASE_URL . "auth/login.php';</script>";
            exit;
        } else {
            echo "Registration failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal - Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <section class="container-fluid d-flex justify-content-center mt-5">
        <form method="POST">
            <div class="container-register col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <h3 class="text-center">Registration</h3>
                        </div>
                        <div class="form-group d-flex justify-content-between mt-3">
                            <div class="col-md-4 pe-2">
                                <input type="text" name="firstname" placeholder="First Name" class="form-control"
                                    value="<?php echo htmlspecialchars($firstname); ?>" />
                                <?php if ($errors['firstname']): ?>
                                    <span class="text-danger"><small><?php echo $errors['firstname']; ?></small></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="middlename" placeholder="Middle Name" class="form-control"
                                    value="<?php echo htmlspecialchars($middlename); ?>" />
                            </div>
                            <div class="col-md-4 ps-2">
                                <input type="text" name="lastname" placeholder="Last Name" class="form-control"
                                    value="<?php echo htmlspecialchars($lastname); ?>" />
                                <?php if ($errors['lastname']): ?>
                                    <span class="text-danger"><small><?php echo $errors['lastname']; ?></small></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-between mt-2">
                            <div class="col-md-6 pe-2">
                                <input type="email" name="email" placeholder="Email" class="form-control"
                                    value="<?php echo htmlspecialchars($email); ?>" />
                                <?php if ($errors['email']): ?>
                                    <span class="text-danger"><small><?php echo $errors['email']; ?></small></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <select name="gender" class="form-select">
                                    <option value="" disabled <?php echo empty($gender) ? 'selected' : ''; ?>>Select
                                        Gender</option>
                                    <option value="male" <?php echo $gender === 'male' ? 'selected' : ''; ?>>Male
                                    </option>
                                    <option value="female" <?php echo $gender === 'female' ? 'selected' : ''; ?>>Female
                                    </option>
                                    <option value="other" <?php echo $gender === 'other' ? 'selected' : ''; ?>>Other
                                    </option>
                                </select>
                                <?php if ($errors['gender']): ?>
                                    <span class="text-danger"><small><?php echo $errors['gender']; ?></small></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-between mt-2">
                            <div class="col-md-6 pe-2">
                                <input type="text" name="city" placeholder="City" class="form-control"
                                    value="<?php echo htmlspecialchars($city); ?>" />
                                <?php if ($errors['city']): ?>
                                    <span class="text-danger"><small><?php echo $errors['city']; ?></small></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="State" placeholder="State" class="form-control"
                                    value="<?php echo htmlspecialchars($state); ?>" />
                                <?php if ($errors['state']): ?>
                                    <span class="text-danger"><small><?php echo $errors['state']; ?></small></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-between mt-2">
                            <div class="col-md-6 pe-2">
                                <input type="number" name="contact" placeholder="Contact Number" class="form-control"
                                    value="<?php echo htmlspecialchars($contact); ?>" />
                                <?php if ($errors['contact']): ?>
                                    <span class="text-danger"><small><?php echo $errors['contact']; ?></small></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <select name="role" class="form-select">
                                    <option value="" disabled <?php echo empty($role) ? 'selected' : ''; ?>>Select role
                                    </option>
                                    <option value="co-ordinator"
                                        <?php echo $role === 'co-ordinator' ? 'selected' : ''; ?>>Co-ordinator</option>
                                    <option value="company" <?php echo $role === 'company' ? 'selected' : ''; ?>>Company
                                    </option>
                                    <option value="student" <?php echo $role === 'student' ? 'selected' : ''; ?>>Student
                                    </option>
                                </select>
                                <?php if ($errors['role']): ?>
                                    <span class="text-danger"><small><?php echo $errors['role']; ?></small></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <input type="submit" value="Register" name="register" class="btn btn-primary w-100" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</body>

</html>