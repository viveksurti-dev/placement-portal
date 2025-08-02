<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'placementportal.ac@gmail.com';
$mail->Password   = 'aicwuoiuzzpeleee';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;

$mail->setFrom('placementportal.ac@gmail.com', 'Smart Placement Portal');
$mail->addReplyTo('placementportal.ac@gmail.com', 'Smart Placement Portal');
