<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require __DIR__ . '/../PHPMailer/Exception.php';
require __DIR__ . '/../PHPMailer/PHPMailer.php';
require __DIR__ . '/../PHPMailer/SMTP.php';

$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode(["status"=>"error","message"=>"Gabim lidhjeje me databazën."]);
    exit;
}

if (!isset($_SESSION['email'])) {
    echo json_encode(["status"=>"error","message"=>"Sessionit i mungon email-i. Logohu përsëri."]);
    exit;
}

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT id FROM Users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo json_encode(["status"=>"error","message"=>"Email nuk ekziston në sistem."]);
    exit;
}

// Ruaj email për reset
$_SESSION['reset_email'] = $email;

// Gjenero kodin
$code = rand(100000, 999999);

$update = $conn->prepare("UPDATE Users SET verification_code=?, code_date=NOW() WHERE email=?");
$update->bind_param("ss", $code, $email);
$update->execute();

// Dërgo email
function sendVerificationEmail($email, $code) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'adelaaagalliu@gmail.com';
        $mail->Password   = 'dfpx qyjw cfsa qnwd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('adelaaagalliu@gmail.com', 'AlbArt App');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Kod per Reset Password';
        $mail->Body = "Kodi juaj për resetimin e password-it është: <b>$code</b>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email verification error: {$mail->ErrorInfo}");
    }
}
sendVerificationEmail($email, $code);

$stmt->close();
$conn->close();

echo json_encode([
    "status" => "success",
    "message" => "Kodi u dërgua në email."
]);