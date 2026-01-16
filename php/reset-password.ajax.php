<?php
$conn = new mysqli("localhost", "root", "", "albart");

$email = trim($_POST['email']);
$password = $_POST['password'];

$hashed = password_hash($password, PASSWORD_DEFAULT);

$update = $conn->prepare("UPDATE Users SET password=?, verification_code=NULL, code_date=NULL WHERE email=?");
$update->bind_param("ss",$hashed,$email);
if($update->execute()){
    echo json_encode(["status"=>"success","message"=>"Password u ndryshua me sukses."]);
} else {
    echo json_encode(["status"=>"error","message"=>"Ndodhi një gabim."]);
}

$update->close();
$conn->close();
?>
