<?php
// auth.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); exit;
}
if (time() - $_SESSION['last_activity'] > 900) {
    session_destroy();
    header("Location: login.php"); exit;
}
$_SESSION['last_activity'] = time();
