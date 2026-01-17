<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "albart";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Gabim lidhjeje me DB: " . $conn->connect_error]);
    exit;
}

if(!isset($_GET['id'])){
    echo json_encode(["error" => "ID e produktit nuk u dërgua"]);
    exit;
}

$id = intval($_GET['id']);
$imgFolder = "../uploads/"; // folderi ku ruhen fotot

$sql = "
    SELECT 
        p.Produkt_ID,
        p.Emri,
        p.Pershkrimi,
        p.Cmimi,
        p.Foto_Produktit,
        p.Statusi,
        k.Emri AS Kategori_Emri,
        a.Artist_ID,
        u.name AS Artist_Name,
        u.surname AS Artist_Surname,
        a.Vleresimi_Total AS Artist_Rating
    FROM Produkti p
    LEFT JOIN Kategoria k ON p.Kategori_ID = k.Kategori_ID
    LEFT JOIN Artisti a ON p.Artist_ID = a.Artist_ID
    LEFT JOIN Users u ON a.User_ID = u.id
    WHERE p.Produkt_ID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if(!$product){
    echo json_encode(["error" => "Produkt nuk u gjet"]);
    exit;
}

// Shto rrugën e plotë për foton e produktit
$product['Foto_Produktit'] = $imgFolder . $product['Foto_Produktit'];

// Vendos default për Artist_Foto pasi tabela nuk ka kolonën
$product['Artist_Foto'] = '../img/default-artist.png';

echo json_encode($product, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
