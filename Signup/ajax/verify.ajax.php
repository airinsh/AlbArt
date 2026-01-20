<?php
$conn = new mysqli("localhost", "root", "", "albart");

if (isset($_POST['email']) && isset($_POST['code'])) {

    $email = trim($_POST['email']);
    $code  = trim($_POST['code']);

    $stmt = $conn->prepare(
        "SELECT id FROM Users 
         WHERE email=? AND verification_code=? AND email_verified=0"
    );
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        $update = $conn->prepare(
            "UPDATE Users 
             SET email_verified=1, verification_code=NULL 
             WHERE email=?"
        );
        $update->bind_param("s", $email);
        $update->execute();
        $update->close();

        echo "Email u verifikua me sukses!";
    } else {
        echo "Kodi i verifikimit nuk është i saktë.";
    }

    $stmt->close();
}

$conn->close();
