<?php
require_once '../../includes/auth.php';

header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "albart");
if($conn->connect_error) {
    echo json_encode(["status"=>"error", "message"=>"Gabim lidhjeje me DB"]);
    exit;
}

// Kontrollo nese eshte artist
$artist_id = getArtistID($conn);
if($artist_id) {
    echo json_encode(["status"=>"success", "type"=>"artist"]);
    exit;
}

// Kontrollo nese eshte klient
$klient_id = getKlientID($conn);
if($klient_id) {
    echo json_encode(["status"=>"success", "type"=>"klient"]);
    exit;
}

// Nese nuk eshte asnje
echo json_encode(["status"=>"error", "message"=>"User nuk është i identifikuar"]);
?>
