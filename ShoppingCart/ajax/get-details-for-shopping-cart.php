<?php
session_start();
header("Content-Type: application/json");

// Kontrollo nëse përdoruesi është loguar
if(!isset($_SESSION['user_id'])){
    echo json_encode(["error" => "Duhet të jeni të loguar"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Lidhja me DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "albart";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    echo json_encode(["error" => "Gabim lidhjeje me DB: " . $conn->connect_error]);
    exit;
}

// Merr Klient_ID nga user
$result = $conn->query("SELECT Klient_ID FROM Klient WHERE User_ID = $user_id LIMIT 1");
if($result->num_rows === 0){
    echo json_encode(["error" => "Klienti nuk u gjet"]);
    exit;
}
$klient = $result->fetch_assoc();
$klient_id = $klient['Klient_ID'];

// Merr produktet qe jane ne shporten e ketij klienti
$sql = "
    SELECT 
        p.Produkt_ID, 
        p.Emri, 
        p.Cmimi, 
        p.Foto_Produktit, 
        k.Emri AS Kategori_Emri,
        a.Artist_ID,
        u.name AS Artist_Name,
        u.surname AS Artist_Surname
    FROM Artikull_Cart ac
    INNER JOIN Produkti p ON ac.Produkt_ID = p.Produkt_ID
    LEFT JOIN Kategoria k ON p.Kategori_ID = k.Kategori_ID
    LEFT JOIN Artisti a ON p.Artist_ID = a.Artist_ID
    LEFT JOIN Users u ON a.User_ID = u.id
    WHERE ac.Klient_ID = $klient_id
";

$result = $conn->query($sql);
$products = [];
$imgFolder = "../uploads/";

if($result){
    while($row = $result->fetch_assoc()){
        $row['Foto_Produktit'] = $imgFolder . $row['Foto_Produktit'];
        $products[] = $row;
    }
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
