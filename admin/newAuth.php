<?php
// require_once '../config.php';
// require_once '../components/navbar.php';
// require_once '../mailStructure.php';

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
        try {
            $authId = $opr->register($firstname, $middlename, $lastname, $email, $gender, $city, $state, $contact, $role, $password);

            if ($authId) {
                require_once ROOT_PATH . 'mails/newAuth.php';
                $_SESSION['alert'] = [];
                $_SESSION['alert'][] = "New $role registered!";

                echo "<script>window.location.href = '';</script>";
                exit;
            } else {
                echo "<div class='alert alert-danger'>Registration failed. Please try again.</div>";
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            echo "<div class='alert alert-danger'>Registration failed: " . htmlspecialchars($e->getMessage()) . "</div>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>styles/main.css">
    <style>
        /* Modern Dashboard Styles */
        body {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 50%, #e8ebff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(108, 99, 255, 0.1);
            max-width: 800px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6c63ff, rgba(108, 99, 255, 0.7));
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .auth-subtitle {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #6c63ff;
            background: white;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .form-control.is-invalid:focus,
        .form-select.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 500;
        }

        .btn-register {
            background: linear-gradient(135deg, #6c63ff 0%, rgba(108, 99, 255, 0.9) 100%);
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-weight: 600;
            font-size: 16px;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, rgba(108, 99, 255, 0.9) 0%, #6c63ff 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 99, 255, 0.3);
            color: white;
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .role-icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            opacity: 0.7;
        }

        .form-section {
            background: rgba(108, 99, 255, 0.02);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(108, 99, 255, 0.1);
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #6c63ff;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            font-size: 20px;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-danger {
            background: #fff5f5;
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background: #f0fff4;
            color: #28a745;
            border-left: 4px solid #28a745;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-card {
                padding: 30px 20px;
                margin: 10px;
            }

            .auth-title {
                font-size: 24px;
            }

            .form-section {
                padding: 20px 15px;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">
                    <i class="fas fa-user-plus" style="color: #6c63ff; margin-right: 10px;"></i>
                    Create New Account
                </h1>
                <p class="auth-subtitle">Register a new user to the placement portal</p>
            </div>

            <?php if (isset($_SESSION['alert']) && !empty($_SESSION['alert'])): ?>
                <?php foreach ($_SESSION['alert'] as $alert): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($alert); ?>
                    </div>
                <?php endforeach; ?>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="firstname" placeholder="Enter first name"
                                class="form-control <?php echo !empty($errors['firstname']) ? 'is-invalid' : ''; ?>"
                                value="<?php echo htmlspecialchars($firstname); ?>" />
                            <?php if (!empty($errors['firstname'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['firstname']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middlename" placeholder="Enter middle name" class="form-control"
                                value="<?php echo htmlspecialchars($middlename); ?>" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="lastname" placeholder="Enter last name"
                                class="form-control <?php echo !empty($errors['lastname']) ? 'is-invalid' : ''; ?>"
                                value="<?php echo htmlspecialchars($lastname); ?>" />
                            <?php if (!empty($errors['lastname'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['lastname']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-envelope"></i>
                        Contact Information
                    </h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" placeholder="Enter email address"
                                class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : ''; ?>"
                                value="<?php echo htmlspecialchars($email); ?>" />
                            <?php if (!empty($errors['email'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number *</label>
                            <input type="tel" name="contact" placeholder="Enter contact number"
                                class="form-control <?php echo !empty($errors['contact']) ? 'is-invalid' : ''; ?>"
                                value="<?php echo htmlspecialchars($contact); ?>" />
                            <?php if (!empty($errors['contact'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['contact']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Location & Gender Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Location & Details
                    </h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Gender *</label>
                            <select name="gender"
                                class="form-select <?php echo !empty($errors['gender']) ? 'is-invalid' : ''; ?>">
                                <option value="" disabled <?php echo empty($gender) ? 'selected' : ''; ?>>
                                    Select Gender
                                </option>
                                <option value="male" <?php echo $gender === 'male' ? 'selected' : ''; ?>>
                                    <i class="fas fa-mars"></i> Male
                                </option>
                                <option value="female" <?php echo $gender === 'female' ? 'selected' : ''; ?>>
                                    <i class="fas fa-venus"></i> Female
                                </option>
                                <option value="other" <?php echo $gender === 'other' ? 'selected' : ''; ?>>
                                    <i class="fas fa-genderless"></i> Other
                                </option>
                            </select>
                            <?php if (!empty($errors['gender'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['gender']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City *</label>
                            <input type="text" name="city" placeholder="Enter city"
                                class="form-control <?php echo !empty($errors['city']) ? 'is-invalid' : ''; ?>"
                                value="<?php echo htmlspecialchars($city); ?>" />
                            <?php if (!empty($errors['city'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['city']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State *</label>
                            <input type="text" name="State" placeholder="Enter state"
                                class="form-control <?php echo !empty($errors['state']) ? 'is-invalid' : ''; ?>"
                                value="<?php echo htmlspecialchars($state); ?>" />
                            <?php if (!empty($errors['state'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['state']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Role Selection Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user-tag"></i>
                        Role Assignment
                    </h3>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Select Role *</label>
                            <select name="role"
                                class="form-select <?php echo !empty($errors['role']) ? 'is-invalid' : ''; ?>">
                                <option value="" disabled <?php echo empty($role) ? 'selected' : ''; ?>>
                                    Choose user role
                                </option>
                                <option value="co-ordinator" <?php echo $role === 'co-ordinator' ? 'selected' : ''; ?>>
                                    <i class="fas fa-user-tie"></i> Co-ordinator
                                </option>
                                <option value="company" <?php echo $role === 'company' ? 'selected' : ''; ?>>
                                    <i class="fas fa-building"></i> Company
                                </option>
                                <option value="student" <?php echo $role === 'student' ? 'selected' : ''; ?>>
                                    <i class="fas fa-user-graduate"></i> Student
                                </option>
                            </select>
                            <?php if (!empty($errors['role'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['role']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <button type="submit" name="register" class="btn btn-register">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>