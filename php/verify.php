<?php
$conn = new mysqli("localhost", "root", "", "albart");

if (isset($_POST['email']) && isset($_POST['code'])) {
    $email = $_POST['email'];
    $code  = $_POST['code'];

    // Kontrollo në DB nëse ekziston email + verification code
    $stmt = $conn->prepare("SELECT id FROM Users WHERE email=? AND verification_code=?");
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Nëse ekziston, përditëso email_verified dhe fshi kodin
        $stmt_update = $conn->prepare(
                "UPDATE Users SET email_verified=1, verification_code=NULL WHERE email=?"
        );
        $stmt_update->bind_param("s", $email);
        $stmt_update->execute();
        $stmt_update->close();

        echo "Email u verifikua me sukses!";
    } else {
        echo "Kodi i verifikimit nuk është i saktë.";
    }

    $stmt->close();
}

$conn->close();
?>
