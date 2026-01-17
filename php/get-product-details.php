<?php
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

// Merr produktet + emrin e kategorisë
$sql = "
    SELECT 
        p.Produkt_ID, 
        p.Emri, 
        p.Cmimi, 
        p.Foto_Produktit, 
        k.Emri AS Kategori_Emri
    FROM Produkti p
    LEFT JOIN Kategoria k ON p.Kategori_ID = k.Kategori_ID
";
$result = $conn->query($sql);

$products = [];
$imgFolder = "../uploads/"; // folderi ku janë fotot

if($result){
    while($row = $result->fetch_assoc()){
        // Shto rrugën e plotë për foton
        $row['Foto_Produktit'] = $imgFolder . $row['Foto_Produktit'];
        $products[] = $row;
    }
}


echo json_encode($products, JSON_UNESCAPED_UNICODE);


$conn->close();
?>
