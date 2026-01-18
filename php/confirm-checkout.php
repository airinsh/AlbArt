<?php
session_start();
header("Content-Type: application/json");

if(!isset($_SESSION['user_id'])){
    echo json_encode(["error"=>"Jo i loguar"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$card = $input['card'] ?? "";

if($card == ""){
    echo json_encode(["error"=>"Kartë e pavlefshme"]);
    exit;
}

$conn = new mysqli("localhost","root","","albart");


// GJEJ KLIENT_ID

$userId = $_SESSION['user_id'];
$res = $conn->query("SELECT Klient_ID FROM Klient WHERE User_ID=$userId");
$row = $res->fetch_assoc();
$klientId = $row['Klient_ID'];


// LLOGARIT TOTALIN

$total = 0;
$cartRes = $conn->query("
    SELECT p.Cmimi 
    FROM Artikull_Cart ac
    JOIN Produkti p ON ac.Produkt_ID = p.Produkt_ID
    WHERE ac.Klient_ID = $klientId
");

while($r = $cartRes->fetch_assoc()){
    $total += $r['Cmimi'];
}


// INSERT PAYMENT

$conn->query("
    INSERT INTO Pagesa (Karta, Shuma, Statusi)
    VALUES ('$card', $total, 'Paid')
");
$pagesaId = $conn->insert_id;


// INSERT ORDER

$conn->query("
    INSERT INTO Porosi (Statusi, Klient_ID, Pagesa_ID)
    VALUES ('Confirmed', $klientId, $pagesaId)
");
$porosiId = $conn->insert_id;


// UPDATE CART -> ORDER

$conn->query("
    UPDATE Artikull_Cart
    SET Porosi_ID = $porosiId
    WHERE Klient_ID = $klientId
");


// EMPTY CART

$conn->query("DELETE FROM Artikull_Cart WHERE Klient_ID = $klientId");

echo json_encode(["success"=>true]);
