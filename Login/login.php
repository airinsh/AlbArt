<?php
session_start();
$conn = new mysqli("localhost", "root", "", "albart");

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT * FROM Users WHERE remember_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['last_activity'] = time();

        // përcakto rolin sërish këtu nëse do
        header("Location: HomePage.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In - AlbArt</title>
    <link rel="stylesheet" href="../Login/login.css">
</head>
<body>
<h1>Log In</h1>

<form id="loginForm" class="login-container">
    <input type="email" name="email" placeholder="Email" id="email" class="input-box" required>
    <input type="password" placeholder="Password" id="password" class="input-box" required>

    <div class="remember-me">
        <input type="checkbox" id="remember">
        <label for="remember">Remember me</label>
    </div>

    <div class="forgot-password">
        <a href="../Login/forgot-password.php">Forgot Password?</a>
    </div>

    <p id="message"></p>

    <button type="submit" class="btn">Continue</button>
</form>

<script src="login.js"></script>
</body>
</html>
