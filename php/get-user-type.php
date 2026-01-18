<?php
require_once "auth.php"; // siguro që ky file ka funksionet getArtistID/getKlientID

header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "albart");
if($conn->connect_error) {
    echo json_encode(["status"=>"error", "message"=>"Gabim lidhjeje me DB"]);
    exit;
}

// Kontrollo nëse është artist
$artist_id = getArtistID($conn);
if($artist_id) {
    echo json_encode(["status"=>"success", "type"=>"artist"]);
    exit;
}

// Kontrollo nëse është klient
$klient_id = getKlientID($conn);
if($klient_id) {
    echo json_encode(["status"=>"success", "type"=>"klient"]);
    exit;
}

// Nëse nuk është asnjë
echo json_encode(["status"=>"error", "message"=>"User nuk është i identifikuar"]);
?>
