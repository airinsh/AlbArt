<?php
require_once 'auth.php';
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode(["status"=>"error","message"=>"Gabim DB"]);
    exit;
}

$klient_id = getKlientID($conn);
if (!$klient_id) {
    echo json_encode(["status"=>"error","message"=>"User nuk është klient ose nuk është loguar"]);
    exit;
}

/* ================= KLIENT + USER ================= */
$stmt = $conn->prepare("
    SELECT k.Klient_ID, u.name, u.surname, u.email
    FROM Klient k
    JOIN Users u ON k.User_ID = u.id
    WHERE k.Klient_ID = ?
");
$stmt->bind_param("i", $klient_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["status"=>"error","message"=>"Klient nuk u gjet"]);
    exit;
}

$klient = $res->fetch_assoc();

/* ================= BLERJET ================= */
$b = $conn->prepare("
    SELECT p.Emri, p.Foto_Produktit, p.Cmimi
    FROM Artikull_Cart ac
    JOIN Produkti p ON ac.Produkt_ID = p.Produkt_ID
    WHERE ac.Klient_ID = ?
");
$b->bind_param("i", $klient_id);
$b->execute();
$blerjet = $b->get_result()->fetch_all(MYSQLI_ASSOC);

/* ================= REVIEWS ================= */
$r = $conn->prepare("
    SELECT r.Vleresimi, r.Koment
    FROM Review r
    WHERE r.Klient_ID = ?
");
$r->bind_param("i", $klient_id);
$r->execute();
$reviews = $r->get_result()->fetch_all(MYSQLI_ASSOC);

/* ================= JSON ================= */
echo json_encode([
    "status" => "success",
    "klient" => $klient,
    "blerjet" => $blerjet,
    "reviews" => $reviews
]);
