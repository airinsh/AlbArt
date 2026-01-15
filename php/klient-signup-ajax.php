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
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'adelaaagalliu@gmail.com';
        $mail->Password   = 'dfpx qyjw cfsa qnwd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('adelaaagalliu@gmail.com', 'AlbArt App');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Verifikimi i Email-it';
        $mail->Body    = "Përshëndetje $name,<br>Kodi yt i verifikimit është: <b>$code</b><br>
                          Jep kodin në faqen e verifikimit për të aktivizuar llogarinë.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email verification error: {$mail->ErrorInfo}");
    }
}

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data["name"]);
$email = trim($data["email"]);
$password = $data["password"];
$preferenca = isset($data["preferenca"]) ? implode(", ", $data["preferenca"]) : "";

if ($name === "" || $email === "" || $password === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Të dhëna të paplota."
    ]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Gabim lidhjeje me databazën."
    ]);
    exit;
}

$check = $conn->prepare("SELECT id FROM Users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => 'Ky email është i regjistruar më parë. <a href="login.php" style="color:blue; text-decoration:underline;">LOGIN</a>'
    ]);
    $check->close();
    $conn->close();
    exit;
}

$check->close();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO Users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;

    $verification_code = rand(100000, 999999);

    $stmt_update = $conn->prepare("UPDATE Users SET verification_code = ? WHERE id = ?");
    $stmt_update->bind_param("si", $verification_code, $user_id);
    $stmt_update->execute();
    $stmt_update->close();

    $stmt2 = $conn->prepare("INSERT INTO Klient (Preferenca, User_ID) VALUES (?, ?)");
    $stmt2->bind_param("si", $preferenca, $user_id);
    $stmt2->execute();
    $stmt2->close();

    sendVerificationEmail($email, $name, $verification_code);

    echo json_encode([
        "status" => "verify",
        "message" => "Regjistrimi u krye me sukses. Kontrollo emailin për kodin e verifikimit.",
        "email" => $email
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gabim gjatë regjistrimit."
    ]);
}

$stmt->close();
$conn->close();
