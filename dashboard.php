<?php
require_once './config.php';
require_once './components/navbar.php';

if ($isLoggedIn == false) {
    echo "<script>window.location.href = '';</script>";
    exit;
}
