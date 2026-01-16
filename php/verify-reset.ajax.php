<?php
$conn = new mysqli("localhost", "root", "", "albart");

$email = trim($_POST['email']);
$code  = trim($_POST['code']);

$stmt = $conn->prepare("SELECT id, code_date FROM Users WHERE email=? AND verification_code=?");
$stmt->bind_param("ss",$email,$code);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){
    $stmt->bind_result($user_id,$code_date);
    $stmt->fetch();

    // Kontrollo koha e skadimit (30 min)
    if(strtotime($code_date) + 1800 < time()){
        echo json_encode(["status"=>"error","message"=>"Kodi ka skaduar. Provo përsëri."]);
    } else {
        echo json_encode(["status"=>"success","message"=>"Kodi i saktë. Mund të ndryshosh password-in."]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"Kodi nuk është i saktë."]);
}
$stmt->close();
$conn->close();
?>
