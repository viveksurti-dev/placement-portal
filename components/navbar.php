<?php
session_start();
define('BASE_URL', '/placementportal/');
$opr = new Config($database);



if (isset($_SESSION['mail'])) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}

if ($isLoggedIn == true) {
    $email = $_SESSION['mail'];
    $auth = $opr->login($email);
}
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    $isLoggedIn = false;
    header("LOCATION: " . BASE_URL . "auth/login.php");
    exit();
}
?>

<head>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>styles/main.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>

                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                </form>
                <form method="POST">
                    <?php if ($isLoggedIn): ?>
                        <input type="submit" value="Logout" name="logout" class="btn btn-outline-danger" />
                    <?php else: ?>
                        <a href="<?php echo BASE_URL . 'auth/login.php'; ?>">Login</a>
                    <?php endif; ?>
                </form>

            </div>
        </div>
    </nav>
</body>