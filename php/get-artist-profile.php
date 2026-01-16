<?php
header("Content-Type: application/json");

$user_id = $_GET['id'] ?? null;
if(!$user_id){
    echo json_encode(["status"=>"error","message"=>"ID mungon"]);
    exit;
}

$conn = new mysqli("localhost","root","","albart");

// Artist + User
$stmt = $conn->prepare("
    SELECT a.Artist_ID, u.name, u.surname, a.Description, a.Certifikime
    FROM Artisti a
    JOIN Users u ON a.User_ID = u.id
    WHERE u.id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows === 0){
    echo json_encode(["status"=>"error","message"=>"Artist nuk u gjet"]);
    exit;
}

$artist = $res->fetch_assoc();
$artist_id = $artist['Artist_ID'];

// ⭐ Rating
$r = $conn->prepare("SELECT AVG(Vleresimi) avg FROM Review WHERE Artist_ID=?");
$r->bind_param("i",$artist_id);
$r->execute();
$artist['rating'] = floatval($r->get_result()->fetch_assoc()['avg'] ?? 0);

// 🎨 Produkte
$p = $conn->prepare("SELECT Emri, Pershkrimi, Foto_Produktit FROM Produkti WHERE Artist_ID=?");
$p->bind_param("i",$artist_id);
$p->execute();
$produkti = $p->get_result()->fetch_all(MYSQLI_ASSOC);

// 💬 Reviews
$rev = $conn->prepare("
    SELECT r.Vleresimi, r.Koment, u.name klient_emri
    FROM Review r
    JOIN Klient k ON r.Klient_ID = k.Klient_ID
    JOIN Users u ON k.User_ID = u.id
    WHERE r.Artist_ID=?
");
$rev->bind_param("i",$artist_id);
$rev->execute();
$reviews = $rev->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "status"=>"success",
    "artist"=>$artist,
    "produkti"=>$produkti,
    "reviews"=>$reviews
]);
