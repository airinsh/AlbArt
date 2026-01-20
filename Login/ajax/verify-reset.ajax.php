<?php
session_start();
$conn = new mysqli("localhost", "root", "", "albart");

if (!isset($_SESSION['reset_email']) || !isset($_POST['code'])) {
    echo "Session ka skaduar ose kodi mungon.";
    exit;
}

$email = $_SESSION['reset_email'];
$code  = trim($_POST['code']);

$stmt = $conn->prepare(
    "SELECT id, code_date FROM Users 
     WHERE email=? AND verification_code=?"
);
$stmt->bind_param("ss", $email, $code);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {

    $stmt->bind_result($user_id, $code_date);
    $stmt->fetch();

    // Kontrollo nëse kodi ka skaduar (30 minuta)
    if (strtotime($code_date) + 1800 < time()) {
        echo "Kodi ka skaduar. Provo përsëri.";
    } else {
        $_SESSION['reset_verified'] = true;
        echo "Kodi u verifikua me sukses!";
    }

} else {
    echo "Kodi i verifikimit nuk është i saktë.";
}

$stmt->close();
$conn->close();
