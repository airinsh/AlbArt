<?php
header("Content-Type: application/json");

// ----------------------
// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Funksioni për të dërguar emailin me kod verifikimi
function sendVerificationEmail($email, $name, $code) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'adelaaagalliu@gmail.com'; // vendos email-in tënd
        $mail->Password   = 'dfpx qyjw cfsa qnwd';     // app password Gmail
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

// ----------------------
// Merr të dhënat nga AJAX
$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data["name"]);
$email = trim($data["email"]);
$password = $data["password"];
$preferenca = isset($data["preferenca"]) ? implode(", ", $data["preferenca"]) : "";

// ----------------------
// Kontroll i dhënave të plota
if ($name === "" || $email === "" || $password === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Të dhëna të paplota."
    ]);
    exit;
}

// ----------------------
// Lidhja me DB
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Gabim lidhjeje me databazën."
    ]);
    exit;
}

// ----------------------
// Kontroll email unik
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

// ----------------------
// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ----------------------
// INSERT në tabelën Users
$stmt = $conn->prepare("INSERT INTO Users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;

    // ----------------------
    // Gjenero kod verification 6-shifror
    $verification_code = rand(100000, 999999);

    // Update verification code në DB
    $stmt_update = $conn->prepare("UPDATE Users SET verification_code = ? WHERE id = ?");
    $stmt_update->bind_param("si", $verification_code, $user_id);
    $stmt_update->execute();
    $stmt_update->close();

    // ----------------------
    // INSERT në tabelën Klient
    $stmt2 = $conn->prepare("INSERT INTO Klient (Preferenca, User_ID) VALUES (?, ?)");
    $stmt2->bind_param("si", $preferenca, $user_id);
    $stmt2->execute();
    $stmt2->close();

    // ----------------------
    // Dërgo email verification
    sendVerificationEmail($email, $name, $verification_code);

    echo json_encode([
        "status" => "success",
        "message" => "Regjistrimi u krye me sukses. Kontrollo emailin për kodin e verifikimit."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gabim gjatë regjistrimit."
    ]);
}

$stmt->close();
$conn->close();
