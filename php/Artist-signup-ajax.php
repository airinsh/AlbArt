<?php
header("Content-Type: application/json");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/Exception.php';
require __DIR__ . '/../PHPMailer/PHPMailer.php';
require __DIR__ . '/../PHPMailer/SMTP.php';

/* ---------------- EMAIL VERIFICATION FUNCTION ---------------- */
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
        $mail->Body = "
            Përshëndetje $name,<br><br>
            Kodi yt i verifikimit është: <b>$code</b><br><br>
            Jep këtë kod në faqen e verifikimit për të aktivizuar llogarinë.
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
    }
}

/* ---------------- MARRJA E TË DHËNAVE ---------------- */
$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
$surname = isset($_POST["surname"]) ? trim($_POST["surname"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = $_POST["password"] ?? "";
$description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
$certification = $_FILES["certification"] ?? null;

/* ---------------- VALIDIME ---------------- */
if ($name === "" || $surname === "" || $email === "" || $password === "" || $description === "" || !$certification) {
    echo json_encode([
        "status" => "error",
        "message" => "Plotëso të gjitha fushat e detyrueshme."
    ]);
    exit;
}

/* ---------------- DB CONNECTION ---------------- */
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Gabim lidhjeje me databazën."
    ]);
    exit;
}

/* ---------------- EMAIL UNIK ---------------- */
$check = $conn->prepare("SELECT id FROM Users WHERE email = ? LIMIT 1");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => 'Ky email është i regjistruar më parë. 
            <a href="login.php" style="color:blue; text-decoration:underline;">LOGIN</a>'
    ]);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

/* ---------------- INSERT USERS ---------------- */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO Users (name, surname, email, password) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssss", $name, $surname, $email, $hashedPassword);

if (!$stmt->execute()) {
    echo json_encode([
        "status" => "error",
        "message" => "Gabim gjatë regjistrimit."
    ]);
    $stmt->close();
    $conn->close();
    exit;
}

$user_id = $stmt->insert_id;
$stmt->close();

/* ---------------- UPLOAD CERTIFICATION ---------------- */
$certPath = null;

if ($certification["error"] === 0) {
    $ext = pathinfo($certification["name"], PATHINFO_EXTENSION);
    $safeName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $name . "_" . $surname);
    $certPath = "uploads/certifikime_" . $safeName . "." . $ext;

    if (!is_dir("../uploads")) {
        mkdir("../uploads", 0755, true);
    }

    move_uploaded_file($certification["tmp_name"], "../" . $certPath);
}

/* ---------------- INSERT ARTIST ---------------- */
$stmt2 = $conn->prepare(
    "INSERT INTO Artisti (Description, Certifikime, User_ID, Vleresimi_Total)
     VALUES (?, ?, ?, 0)"
);
$stmt2->bind_param("ssi", $description, $certPath, $user_id);
$stmt2->execute();
$stmt2->close();

/* ---------------- VERIFICATION CODE ---------------- */
$verification_code = rand(100000, 999999);

$stmt_verify = $conn->prepare(
    "UPDATE Users SET verification_code = ? WHERE id = ?"
);
$stmt_verify->bind_param("si", $verification_code, $user_id);
$stmt_verify->execute();
$stmt_verify->close();

/* ---------------- SEND EMAIL ---------------- */
sendVerificationEmail($email, $name, $verification_code);

/* ---------------- RESPONSE ---------------- */
echo json_encode([
    "status" => "verify",
    "message" => "Regjistrimi u krye me sukses. Kontrollo email-in për kodin e verifikimit.",
    "email" => $email
]);

$conn->close();
exit;
