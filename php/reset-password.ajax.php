<?php
session_start();
header("Content-Type: application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_verified'])) {
    echo json_encode(["status"=>"error","message"=>"Session ka skaduar."]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "albart");

$email = $_SESSION['reset_email'];
$password = $_POST['password'];

$hashed = password_hash($password, PASSWORD_DEFAULT);

$update = $conn->prepare("UPDATE Users SET password=?, verification_code=NULL, code_date=NULL WHERE email=?");
$update->bind_param("ss",$hashed,$email);

if($update->execute()){
    // Pas suksesit, pastroj session
    unset($_SESSION['reset_email'], $_SESSION['reset_verified']);
    echo json_encode(["status"=>"success","message"=>"Password u ndryshua me sukses."]);
} else {
    echo json_encode(["status"=>"error","message"=>"Ndodhi një gabim."]);
}

$update->close();
$conn->close();
