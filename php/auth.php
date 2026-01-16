<?php
session_start();
$conn = new mysqli("localhost", "root", "", "albart");

if (!isset($_SESSION['user_id'])) {

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
        } else {
            setcookie("remember_token", "", time()-3600, "/");
            header("Location: login.php");
            exit;
        }
    } else {
        header("Location: login.php");
        exit;
    }
}

// Session timeout 15 minuta
if (time() - $_SESSION['last_activity'] > 900) {
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}

$_SESSION['last_activity'] = time();
