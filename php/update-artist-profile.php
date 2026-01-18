<?php
require_once 'auth.php';
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Gabim lidhjeje me DB."]);
    exit;
}

$artist_id = getArtistID($conn);
if (!$artist_id) {
    echo json_encode(["status" => "error", "message" => "Ky user nuk është artist."]);
    exit;
}

$action = $_POST['action'] ?? '';
$id = intval($_POST['id'] ?? 0);
$type = $_POST['type'] ?? '';

// ------------------ VEPRAT ------------------
// Delete
if ($action === "delete" && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM Produkti WHERE Produkt_ID=? AND Artist_ID=?");
    $stmt->bind_param("ii", $id, $artist_id);
    if ($stmt->execute()) echo json_encode(["status"=>"success"]);
    else echo json_encode(["status"=>"error","message"=>"Gabim gjatë fshirjes."]);
    exit;
}

// Edit
if ($action === "edit" && $id > 0) {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['desc'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    if (!$name || !$desc || $price <= 0) {
        echo json_encode(["status"=>"error","message"=>"Të gjitha fushat janë të detyrueshme dhe cmimi > 0"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE Produkti SET Emri=?, Pershkrimi=?, Cmimi=? WHERE Produkt_ID=? AND Artist_ID=?");
    $stmt->bind_param("ssdii", $name, $desc, $price, $id, $artist_id);
    if ($stmt->execute()) echo json_encode(["status"=>"success"]);
    else echo json_encode(["status"=>"error","message"=>"Gabim gjatë ruajtjes së veprës."]);
    exit;
}

// ------------------ EMRI ------------------
if ($type === "name") {
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    if (!$name || !$surname) {
        echo json_encode(["status" => "error", "message" => "Emri dhe mbiemri janë të detyrueshëm."]);
        exit;
    }

    $stmt = $conn->prepare("
        UPDATE Users u
        JOIN Artisti a ON u.id = a.User_ID
        SET u.name = ?, u.surname = ?
        WHERE a.Artist_ID = ?
    ");
    $stmt->bind_param("ssi", $name, $surname, $artist_id);

    if ($stmt->execute()) echo json_encode(["status" => "success"]);
    else echo json_encode(["status" => "error", "message" => "Gabim gjatë ruajtjes së emrit."]);
    exit;
}

// ------------------ PËRSHKRIMI ------------------
if ($type === "description") {
    $description = trim($_POST['description'] ?? '');
    if (!$description) {
        echo json_encode(["status" => "error", "message" => "Përshkrimi është i detyrueshëm."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE Artisti SET Description = ? WHERE Artist_ID = ?");
    $stmt->bind_param("si", $description, $artist_id);

    if ($stmt->execute()) echo json_encode(["status" => "success"]);
    else echo json_encode(["status" => "error", "message" => "Gabim gjatë ruajtjes së përshkrimit."]);
    exit;
}

echo json_encode(["status" => "error", "message" => "Veprim i pavlefshëm."]);
exit;
?>
