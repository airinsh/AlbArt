<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // PHPMailer

$conn = new mysqli("localhost", "root", "", "albart");

if(isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM Users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if(!$user){
        echo json_encode(["status"=>"error","message"=>"Email nuk ekziston."]);
        exit;
    }

    // Gjenero kodin 6-shifror
    $code = rand(100000,999999);

    $update = $conn->prepare("UPDATE Users SET verification_code=?, code_date=NOW() WHERE email=?");
    $update->bind_param("ss",$code,$email);
    $update->execute();

    // Dërgo email
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
        $mail->Subject = 'Kod për Reset Password';
        $mail->Body = "Kodi juaj për resetimin e password-it është: <b>$code</b>";

        $mail->send();
        echo json_encode(["status"=>"success","message"=>"Kod verifikimi u dërgua në email."]);
    } catch (Exception $e) {
        echo json_encode(["status"=>"error","message"=>"Gabim gjatë dërgimit të email-it."]);
    }
}
$conn->close();
?>
