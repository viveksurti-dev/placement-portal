<?php
require_once '../config.php';
require_once ROOT_PATH . 'components/navbar.php';
require_once ROOT_PATH . 'mailStructure.php';


if ($auth['authrole'] !== 'admin' && !$isLoggedIn) {
    echo "<script>window.location.href='" . BASE_URL . "auth/login.php';</script>";
    exit();
}

$allowedParams = ['p'];
if (!empty(array_diff(array_keys($_GET), $allowedParams))) {
    header("Location: " . BASE_URL . "errors/invalidRequests.php");
    exit();
}
$page = $_GET['p'] ?? '';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Dashboard
    </title>
    <link rel="stylesheet" href="<?php echo BASE_URL ?>styles/main.css">
    <style>
    .container-sidebar {
        width: 250px;
        height: 91.4vh;
        background: #f8f9fa;
        border-right: 1px solid #ddd;
        flex-direction: column;
        justify-content: space-between;
    }

    .sidebar-menus .btn-active {
        background-color: #d5d5d5ff;
        border: 1px solid gray;
        color: #252525ff;
        font-weight: 600;
        /* border: 1px solid black; */
    }


    .sidebar-heading {
        padding: 20px 0 10px 10px;
        font-weight: bold;
        font-size: 1.2rem;
        background-color: #ddd;
        margin: 10px;
        text-align: center;
        border-radius: 5px;
    }

    .sidebar-menus {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 0 10px;
    }

    .sidebar-menus i {
        font-size: 18px;
        margin: 0px 10px 0px 0px;
        font-weight: bold;
    }

    .sidebar-menus a,
    .sidebar-menus button {
        border: 1px solid gray;
        text-align: left;
        width: 100%;
        background: #f3f3f3ff;
        border: 1px solid gray;
        color: #6d6d6dff;
        padding: 10px 15px;
        border-radius: 4px;
        transition: background 0.2s;
        font-size: 1rem;
        text-decoration: none;
    }

    .sidebar-menus a:hover,
    .sidebar-menus button:hover {
        background: #d6d6d6ff;
        color: black;
    }

    .back {
        padding: 10px;
    }

    .container-admin-content {
        height: 91.4vh;
        overflow-y: scroll;

    }
    </style>
</head>

<body>
    <div class="d-flex container-main">
        <div class="container-sidebar d-flex flex-column">
            <div class="sidebar-heading">
                <h5><span class="text-capitalize"><?php echo $auth['authrole'] ?></span> Panel</h5>
            </div>
            <div class="sidebar-menus mt-5">

                <a href="<?php echo BASE_URL ?>admin/" class="<?php echo $page === '' ? 'btn-active' : '' ?>">
                    <i class="bi bi-person-lines-fill"></i> Dashboard
                </a>
                <a href="?p=newauth/" class="<?php echo $page === 'newauth/' ? 'btn-active' : '' ?>">
                    <i class="bi bi-person-lines-fill"></i> New Auth
                </a>
                <a href="?p=managestudents/" class="<?php echo $page === 'managestudents/' ? 'btn-active' : '' ?>">
                    <i class="bi bi-people"></i> Students
                </a>
                <a href="?p=managecordinators/"
                    class="<?php echo $page === 'managecordinators/' ? 'btn-active' : '' ?>">
                    <i class="bi bi-people"></i> Co-Ordinators
                </a>
                <a href="?p=managecompany/" class="<?php echo $page === 'managecompany/' ? 'btn-active' : '' ?>">
                    <i class="bi bi-building-fill-check"></i> Companies
                </a>

            </div>
            <div class="back">
                <a href="<?php echo BASE_URL; ?>account/" class="btn btn-outline-danger w-100">Back</a>
            </div>
        </div>
        <div class="container-admin-content flex-grow-1 p-4">
            <?php


            switch ($page) {
                case '':
                    echo 'dashboard';
                    break;
                case 'newauth/':
                    require_once './newAuth.php';
                    break;
                case 'managestudents/':
                    require_once './manageStudent.php';
                    break;
                case 'managecompany/':
                    require_once './manageCompany.php';
                    break;
                case 'managecordinators/':
                    require_once './manageCoordinator.php';
                    break;
                case 'editstudent/':
                    require_once './editStudent.php';
                    break;
                case 'editcompany/':
                    require_once './editCompany.php';
                    break;
                case 'editcoordinator/':
                    require_once './editCoordinator.php';
                    break;
                default:
                    echo 'Page Not Found';
            }
            ?>
        </div>
    </div>

</body>

</html>