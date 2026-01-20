<?php
session_start();
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$remember = !empty($data['remember']);

$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode(["status"=>"error","message"=>"Gabim lidhjeje me databazën."]);
    exit;
}

/* ================= BLOCK CHECK ================= */
$stmt = $conn->prepare("SELECT attempts, blocked_until FROM login_attempts WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if ($res && $res['blocked_until'] && strtotime($res['blocked_until']) > time()) {
    echo json_encode(["status"=>"error","message"=>"Login i bllokuar për 30 minuta."]);
    exit;
}

/* ================= USER CHECK ================= */
$stmt = $conn->prepare("SELECT * FROM Users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($password, $user['password'])) {

    if ($res) {
        $attempts = $res['attempts'] + 1;
        $blocked = ($attempts >= 7) ? date("Y-m-d H:i:s", strtotime("+30 minutes")) : null;

        $upd = $conn->prepare("UPDATE login_attempts SET attempts=?, last_attempt=NOW(), blocked_until=? WHERE email=?");
        $upd->bind_param("iss", $attempts, $blocked, $email);
        $upd->execute();
    } else {
        $ins = $conn->prepare("INSERT INTO login_attempts (email, attempts, last_attempt) VALUES (?,1,NOW())");
        $ins->bind_param("s", $email);
        $ins->execute();
    }

    logAction($conn, $user['id'] ?? null, "LOGIN_FAILED");
    echo json_encode(["status"=>"error","message"=>"Email ose password gabim."]);
    exit;
}

/* ================= SUCCESS LOGIN ================= */
$conn->query("DELETE FROM login_attempts WHERE email='$email'");

$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $user['email'];
$_SESSION['last_activity'] = time();

/* ================= ROLE DETECTION ================= */
$role = "klient";

if ($conn->query("SELECT 1 FROM Admin WHERE User_ID=".$user['id'])->num_rows > 0) {
    $role = "Admin";
}
elseif ($conn->query("SELECT 1 FROM Artisti WHERE User_ID=".$user['id'])->num_rows > 0) {
    $role = "artist";
}
elseif ($conn->query("SELECT 1 FROM Klient WHERE User_ID=".$user['id'])->num_rows > 0) {
    $role = "klient";
}

$_SESSION['role'] = $role;

/* ================= SET KLENT_ID ================= */
if ($role === "klient") {
    $stmt = $conn->prepare("SELECT Klient_ID FROM Klient WHERE User_ID = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if ($res) {
        $_SESSION['Klient_ID'] = $res['Klient_ID'];
    }
}

/* ================= REMEMBER ME ================= */
if ($remember) {
    $token = bin2hex(random_bytes(32));
    setcookie("remember_token", $token, time()+60*60*24*30, "/", "", true, true);

    $upd = $conn->prepare("UPDATE Users SET remember_token=?, remember_token_date=NOW() WHERE id=?");
    $upd->bind_param("si", $token, $user['id']);
    $upd->execute();
}

/* ================= LOG ================= */
logAction($conn, $user['id'], "LOGIN_SUCCESS");

/* ================= REDIRECT ================= */
$redirect = match($role) {
    "Admin" => "../Admin/admin.html",
    "artist" => "../Artist/Profili-Artistit.php",
    "klient" => "../Klient/Profili-Klient.php"
};

echo json_encode([
    "status"=>"success",
    "redirect"=>$redirect
]);

/* ================= LOG FUNCTION ================= */
function logAction($conn, $uid, $action) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, ip) VALUES (?,?,?)");
    $stmt->bind_param("iss", $uid, $action, $ip);
    $stmt->execute();
}
