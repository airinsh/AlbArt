<?php
session_start();
$_SESSION = array();
session_destroy();
setcookie("remember_token", "", time() - 3600, "/");
header("Location: ../Login/login.php");
exit;
?>
