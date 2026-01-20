<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// lidhja me db
$conn = new mysqli("localhost","root","","albart");
if($conn->connect_error){
    echo json_encode(["error"=>$conn->connect_error]);
    exit;
}

// kontrolli i ID
if(!isset($_GET['id'])){
    echo json_encode(["error"=>"ID nuk u dërgua"]);
    exit;
}
$id = intval($_GET['id']);

// marrja e produktit dhe artistit
$sql = "
SELECT 
    p.Produkt_ID,
    p.Emri,
    p.Pershkrimi,
    p.Cmimi,
    p.Foto_Produktit,
    k.Emri AS Kategori_Emri,
    a.Artist_ID,
    a.Fotografi AS Artist_Fotografi,
    u.name AS Artist_Name,
    u.surname AS Artist_Surname
FROM Produkti p
LEFT JOIN Kategoria k ON p.Kategori_ID=k.Kategori_ID
LEFT JOIN Artisti a ON p.Artist_ID=a.Artist_ID
LEFT JOIN Users u ON u.id = a.User_ID
WHERE p.Produkt_ID = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if(!$product){
    echo json_encode(["error"=>"Produkt nuk u gjet"]);
    exit;
}

// fotoja e produktit
$product['Foto_Produktit'] = "../uploads/".$product['Foto_Produktit'];

// fotoja e artistit
//merret vetem emri skedarit nga db
$artistFile = basename($product['Artist_Fotografi']); // p.sh. "profile_28_1768704712.jpeg"
$artistPathServer = __DIR__ . "/../../uploads/" . $artistFile; // path absolut në server
$projectRoot = "/AlbArt/"; // path për browser

if(!empty($artistFile) && file_exists($artistPathServer)){
    $product['Artist_Foto'] = $projectRoot."uploads/".$artistFile;
} else {
    // default nqs nuk ka foto
    $product['Artist_Foto'] = $projectRoot."uploads/profile_32_1768705082.jpeg";
}

// ktheht json
echo json_encode($product, JSON_UNESCAPED_UNICODE);

// mbyllet lidhja
$conn->close();
?>
