<?php
session_start();
header("Content-Type: text/plain");

// ============================
// Krijo lidhjen me DB direkt
// ============================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "albart";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Gabim lidhjeje me DB: " . $conn->connect_error);
}

// ============================
// KONTROLL LOGIN
// ============================
if (!isset($_SESSION['Klient_ID'])) {
    echo "unauthorized";
    exit;
}

$klient_id = $_SESSION['Klient_ID'];

// ============================
// TË DHËNAT NGA AJAX
// ============================
$artist_id = $_POST['Artist_ID'] ?? null;
$vleresimi = $_POST['vleresimi'] ?? null;
$koment    = trim($_POST['koment'] ?? "");

// ============================
// VALIDIM
// ============================
if (!$artist_id || !$vleresimi) {
    echo "missing_data";
    exit;
}

if ($vleresimi < 1 || $vleresimi > 5) {
    echo "invalid_rating";
    exit;
}

// ============================
// KONTROLLO NËSE KLIENTI KA BËRË REVIEW MË PARË
// ============================
$check = $conn->prepare(
    "SELECT Review_ID FROM Review WHERE Klient_ID = ? AND Artist_ID = ?"
);
$check->bind_param("ii", $klient_id, $artist_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "already_reviewed";
    exit;
}

// ============================
// INSERT REVIEW
// ============================
$stmt = $conn->prepare(
    "INSERT INTO Review (Klient_ID, Artist_ID, Vleresimi, Koment)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("iiis", $klient_id, $artist_id, $vleresimi, $koment);

if ($stmt->execute()) {
    // ============================
    // UPDATE VLERËSIMI TOTAL TË ARTISTIT
    // ============================
    $update = $conn->prepare(
        "UPDATE Artisti
         SET Vleresimi_Total = (
             SELECT ROUND(AVG(Vleresimi))
             FROM Review
             WHERE Artist_ID = ?
         )
         WHERE Artist_ID = ?"
    );
    $update->bind_param("ii", $artist_id, $artist_id);
    $update->execute();

    echo "success";
} else {
    echo "db_error";
}
?>
