<?php
// Include manual PHPMailer files
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Example:
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'adelaaagalliu@gmail.com';
    $mail->Password   = 'dfpx qyjw cfsa qnwd'; // App password Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('adelaaagalliu@gmail.com', 'AlbArt App');
    $mail->addAddress('adelaaagalliu@gmail.com', 'Recipient Name');

    $mail->isHTML(true);
    $mail->Subject = 'Kod verifikimi';
    $data["code"] = rand(100000, 999999); // ose mënyra alfanumerike
    $mail->Body = 'Kodi yt i verifikimit është: <b>' . $data["code"] . '</b>';

    $mail->send();
    echo 'Email u dërgua me sukses!';
} catch (Exception $e) {
    echo "Email nuk mund të dërgohet. Gabim: {$mail->ErrorInfo}";
}
