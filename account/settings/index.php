<?php
require_once '../../config.php';
require_once ROOT_PATH . 'components/navbar.php';
require_once ROOT_PATH . 'mailStructure.php';


if (!$isLoggedIn) {
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
        Placement Portal - Settings
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
        }

        .sidebar-heading {
            padding: 20px 10px;
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
            font-size: 14px;
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
                <small><span class="text-uppercase">settings</small>
            </div>
            <div class="sidebar-menus ">
                <div class="text-center my-2">
                    <small class="caption"><strong>-- Account --</strong></small>
                </div>
                <a href="<?php echo BASE_URL ?>account/settings/"
                    class="<?php echo $page === '' ? 'btn-active' : '' ?>">
                    <i class="bi bi-person-lines-fill"></i>
                    <?php echo $auth['authrole'] === 'company' ? 'HR' : 'Basic' ?>
                    Info
                </a>
                <?php if ($auth['authrole'] === 'student') { ?>
                    <a href="?p=academicdetails/" class="<?php echo $page === 'academicdetails/' ? 'btn-active' : '' ?>">
                        <i class="bi bi-book-half"></i> Academic
                    </a>
                <?php } ?>
                <?php if ($auth['authrole'] === 'co-ordinator') { ?>
                    <a href="?p=co-ordinatordetails/"
                        class="<?php echo $page === 'co-ordinatordetails/' ? 'btn-active' : '' ?>">
                        <i class="bi bi-bar-chart-steps"></i> Co-Or Detail
                    </a>
                <?php } ?>
                <?php if ($auth['authrole'] === 'company') { ?>
                    <a href="?p=company/" class="<?php echo $page === 'company/' ? 'btn-active' : '' ?>">
                        <i class="bi bi-building-fill-gear"></i> Company
                    </a>
                <?php } ?>
                <a href="?p=sociallinks/" class="<?php echo $page === 'sociallinks/' ? 'btn-active' : '' ?>">
                    <i class="bi bi-link-45deg"></i>
                    <?php echo $auth['authrole'] === 'company' ? 'HR' : '' ?> Social Links
                </a>

                <div class="text-center my-2">
                    <small class="caption"><strong>-- Genaral --</strong></small>
                </div>
                <a href="?p=terms/" class="<?php echo $page === 'terms/' ? 'btn-active' : '' ?>">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    Terms
                </a>
            </div>
            <div class="back">
                <a href="<?php echo BASE_URL ?>?logout" class="btn btn-outline-danger w-100">Logout</a>
            </div>
        </div>
        <div class="container-admin-content  flex-grow-1 p-4">
            <?php


            switch ($page) {
                case '':
                    require  './editBasic.php';
                    break;
                case 'academicdetails/':
                    require_once './editAcademic.php';
                    break;
                case 'sociallinks/':
                    require_once './editSocial.php';
                    break;
                case 'co-ordinatordetails/':
                    require_once './editCo_ordinator.php';
                    break;
                case 'company/':
                    require_once './editCompany.php';
                    break;
                case 'terms/':
                    require_once './terms_conditions.php';
                    break;

                default:
                    echo 'Page Not Found';
            }
            ?>
        </div>
    </div>

</body>

</html>