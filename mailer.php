<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';
require 'db.php';

function verify_email($email) {
    $mail = new PHPMailer(true);

    $adminInfo = getAdminEmail();

    if (!$adminInfo) {
        echo "Admin email not found.";
        return;
    }

    $mail->isSMTP();

    $mail->Host = 'asmtp.bilkent.edu.tr';
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->Username = $adminInfo[0];
    $mail->Password = $adminInfo[1];

    $code = rand(100000, 999999);
    $mail->setFrom($adminInfo[0], 'Gandalf the Grey');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Verification Code';
    $mail->Body = 'Your verification code is: ' . $code;
    $mail->send();

    return $code;
}

