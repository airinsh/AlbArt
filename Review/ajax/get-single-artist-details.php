<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

// Lidhja me DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "albart";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Gabim lidhjeje me DB: " . $conn->connect_error]);
    exit;
}

// Merrim ID e artistit nga GET ose session
if(isset($_GET['id'])){
    $artist_id = intval($_GET['id']);
} elseif(isset($_SESSION['Artist_ID'])){
    $artist_id = intval($_SESSION['Artist_ID']);
} else {
    echo json_encode(["error" => "ID e artistit nuk u dërgua"]);
    exit;
}

$imgFolder = "../uploads/"; // folderi ku ruhen fotot

// Marrim të dhënat e artistit
$sql = "
    SELECT 
        a.Artist_ID,
        u.name AS Artist_Name,
        u.surname AS Artist_Surname,
        a.Certifikime,
        a.Description,
        a.Vleresimi_Total,
        a.Fotografi
    FROM Artisti a
    JOIN Users u ON a.User_ID = u.id
    WHERE a.Artist_ID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$result = $stmt->get_result();
$artist = $result->fetch_assoc();

if(!$artist){
    echo json_encode(["error" => "Artist nuk u gjet"]);
    exit;
}

// Fotografia nuk ndryshohet, ruhet si në DB
$artist['Foto_Artistit'] = !empty($artist['Fotografi']) ? $imgFolder . $artist['Fotografi'] : '../img/default-artist.png';

// --------------------------
// Merr review-t e artistit
$sqlReview = "
    SELECT r.Vleresimi, r.Koment, u.name AS klient_emri, u.surname AS klient_mbiemri
    FROM Review r
    JOIN Klient k ON r.Klient_ID = k.Klient_ID
    JOIN Users u ON k.User_ID = u.id
    WHERE r.Artist_ID = ?
";
$stmtReview = $conn->prepare($sqlReview);
$stmtReview->bind_param("i", $artist_id);
$stmtReview->execute();
$resultReview = $stmtReview->get_result();

$reviews = [];
while($row = $resultReview->fetch_assoc()){
    $reviews[] = $row;
}

// Shto reviews në array artist
$artist['Reviews'] = $reviews;

// --------------------------
// Opsionale: Merr veprat e artistit
$sqlVeprat = "
    SELECT p.Emri, p.Pershkrimi, p.Cmimi, p.Foto_Produktit, k.Emri AS Kategoria_Emri
    FROM Produkti p
    LEFT JOIN Kategoria k ON p.Kategori_ID = k.Kategori_ID
    WHERE p.Artist_ID = ?
";
$stmtVeprat = $conn->prepare($sqlVeprat);
$stmtVeprat->bind_param("i", $artist_id);
$stmtVeprat->execute();
$resultVeprat = $stmtVeprat->get_result();

$veprat = [];
while($row = $resultVeprat->fetch_assoc()){
    $veprat[] = $row;
}
$artist['veprat'] = $veprat;

// Kthe JSON
echo json_encode($artist, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
