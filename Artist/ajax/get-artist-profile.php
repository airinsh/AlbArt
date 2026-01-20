<?php
require_once '../../includes/auth.php'; // sigurohet session dh conn
header("Content-Type: application/json");

// Lidhja me DB
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Gabim lidhjeje me DB"]);
    exit;
}
$artist_id = getArtistID($conn);
if (!$artist_id) {
    echo json_encode(["status" => "error", "message" => "Ky user nuk është artist."]);
    exit;
}

// Artist + User + Fotografi
$stmt = $conn->prepare("
    SELECT a.Artist_ID, u.name, u.surname, a.Description, a.Certifikime, a.Fotografi
    FROM Artisti a
    JOIN Users u ON a.User_ID = u.id
    WHERE a.Artist_ID = ?
");
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Artist nuk u gjet"]);
    exit;
}

$artist = $res->fetch_assoc();

// Rating mesatar
$r = $conn->prepare("SELECT AVG(Vleresimi) as avg FROM Review WHERE Artist_ID=?");
$r->bind_param("i", $artist_id);
$r->execute();
$ratingRes = $r->get_result()->fetch_assoc();
$artist['Vleresimi_Total'] = floatval($ratingRes['avg'] ?? 0);


// Produkte me kategori dhe cmim
$p = $conn->prepare("
    SELECT 
        pr.Produkt_ID,
        pr.Emri,
        pr.Pershkrimi,
        pr.Foto_Produktit,
        pr.Cmimi,
        k.Emri AS Kategoria_Emri
    FROM Produkti pr
    LEFT JOIN Kategoria k ON pr.Kategori_ID = k.Kategori_ID
    WHERE pr.Artist_ID=?
");
$p->bind_param("i", $artist_id);
$p->execute();
$produkti = $p->get_result()->fetch_all(MYSQLI_ASSOC);

// Reviewt
$rev = $conn->prepare("
    SELECT r.Vleresimi, r.Koment, u.name AS klient_emri
    FROM Review r
    JOIN Klient k ON r.Klient_ID = k.Klient_ID
    JOIN Users u ON k.User_ID = u.id
    WHERE r.Artist_ID=?
");
$rev->bind_param("i", $artist_id);
$rev->execute();
$reviews = $rev->get_result()->fetch_all(MYSQLI_ASSOC);

// jsoni
echo json_encode([
    "status" => "success",
    "artist" => $artist,
    "produkti" => $produkti,
    "reviews" => $reviews
]);
