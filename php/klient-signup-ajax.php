<?php
header("Content-Type: application/json");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/Exception.php';
require __DIR__ . '/../PHPMailer/PHPMailer.php';
require __DIR__ . '/../PHPMailer/SMTP.php';

function sendVerificationEmail($email, $name, $code) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'adelaaagalliu@gmail.com';
        $mail->Password = 'dfpx qyjw cfsa qnwd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('adelaaagalliu@gmail.com', 'AlbArt');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Verifikimi i Email-it';
        $mail->Body = "
            Përshëndetje <b>$name</b>,<br><br>
            Kodi yt i verifikimit është:<br>
            <h2>$code</h2>
            Vendose këtë kod për të aktivizuar llogarinë.
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
    }
}

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data["name"] ?? "");
$surname = trim($data["surname"] ?? "");
$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";
$preferenca = isset($data["preferenca"]) ? implode(", ", $data["preferenca"]) : "";

if ($name === "" || $surname === "" || $email === "" || $password === "") {
    echo json_encode(["status"=>"error","message"=>"Të dhëna të paplota."]);
    exit;
}

$conn = new mysqli("localhost","root","","albart");
if ($conn->connect_error) {
    echo json_encode(["status"=>"error","message"=>"Gabim databaze"]);
    exit;
}

$check = $conn->prepare("SELECT id FROM Users WHERE email=?");
$check->bind_param("s",$email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Ky email ekziston. <a href='login.php'>Login</a>"
    ]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO Users (name, surname, email, password)
    VALUES (?,?,?,?)
");
$stmt->bind_param("ssss",$name,$surname,$email,$hashedPassword);
$stmt->execute();

$user_id = $stmt->insert_id;

$verification_code = rand(100000,999999);
$upd = $conn->prepare("UPDATE Users SET verification_code=? WHERE id=?");
$upd->bind_param("si",$verification_code,$user_id);
$upd->execute();

$stmt2 = $conn->prepare("
    INSERT INTO Klient (Preferenca, User_ID)
    VALUES (?,?)
");
$stmt2->bind_param("si",$preferenca,$user_id);
$stmt2->execute();

sendVerificationEmail($email, $name, $verification_code);

echo json_encode([
    "status"=>"verify",
    "message"=>"Regjistrimi u krye me sukses. Kontrollo email-in.",
    "email"=>$email
]);
