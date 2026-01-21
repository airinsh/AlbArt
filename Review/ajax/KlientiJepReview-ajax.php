<?php
session_start();
header("Content-Type: text/plain");

// Lidhja me DB
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) die("db_error");

// Kontroll session
if (!isset($_SESSION['user_id'])) {
    echo "unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];

// Merr te dhenat nga AJAX
$artist_id = $_POST['Artist_ID'] ?? null;
$vleresimi = $_POST['vleresimi'] ?? null;
$koment    = trim($_POST['koment'] ?? "");

// Validim bazik
if (!$artist_id || !$vleresimi) {
    echo "missing_data";
    exit;
}
if ($vleresimi < 1 || $vleresimi > 5) {
    echo "invalid_rating";
    exit;
}

// Kontrollo nese ky user ka bere review per kete artist (pa marre parasysh rolin)
$check = $conn->prepare("
    SELECT r.Review_ID
    FROM Review r
    LEFT JOIN Klient k ON r.Klient_ID = k.Klient_ID
    LEFT JOIN Users u ON k.User_ID = u.id
    WHERE r.Artist_ID = ? AND u.id = ?
");
$check->bind_param("ii", $artist_id, $user_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "already_reviewed";
    exit;
}

// Merr Klient_ID nese ekziston
$klient_id = null;
$stmt = $conn->prepare("SELECT Klient_ID FROM Klient WHERE User_ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if ($res) $klient_id = $res['Klient_ID'];

// Insert review
$stmt = $conn->prepare("
    INSERT INTO Review (Klient_ID, Artist_ID, Vleresimi, Koment)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("iiis", $klient_id, $artist_id, $vleresimi, $koment);

if ($stmt->execute()) {

    // UPDATE vleresimi total te Artisti
    $update = $conn->prepare("
        UPDATE Artisti
        SET Vleresimi_Total = (
            SELECT ROUND(AVG(Vleresimi))
            FROM Review
            WHERE Artist_ID = ?
        )
        WHERE Artist_ID = ?
    ");
    $update->bind_param("ii", $artist_id, $artist_id);
    $update->execute();

    echo "success";
} else {
    echo "db_error";
}
