<?php
// Lidhja me databazen
$servername = "localhost";
$username = "root";
$password = ""; // nëse ka password vendos këtu
$dbname = "albart";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrollo lidhjen
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if (!isset($_GET['kategori'])) {
    echo json_encode([]);
    exit;
}

$kategori = intval($_GET['kategori']);

// query per produktet
$sql = "
SELECT 
    p.Produkt_ID,
    p.Emri,
    p.Cmimi,
    p.Foto_Produktit,
    k.Emri AS Kategori_Emri
FROM Produkti p
JOIN Kategoria k ON p.Kategori_ID = k.Kategori_ID
WHERE p.Kategori_ID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $kategori);
$stmt->execute();

$result = $stmt->get_result();
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
