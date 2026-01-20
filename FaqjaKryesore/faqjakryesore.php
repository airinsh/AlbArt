<?php
session_start();
$conn = new mysqli("localhost", "root", "", "albart");

if (isset($_SESSION['user_id'])) {
    header("Location: ../HomePage/HomePage.php");
    exit;
}

if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT * FROM Users WHERE remember_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['last_activity'] = time();
        header("Location: ../HomePage/HomePage.php");
        exit;
    } else {
        setcookie("remember_token", "", time()-3600, "/");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlbArt</title>
    <link rel="stylesheet" href="faqjakryesore.css">
</head>
<body>
<h1>AlbArt</h1>
<div>
    <button class="btn" onclick="window.location.href='../Login/login.php'">Log In</button>
    <button class="btn" onclick="window.location.href='../Signup/signup.php'">Sign Up</button>
</div>
</body>
</html>
