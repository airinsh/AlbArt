<?php
require_once 'auth.php';
header("Content-Type: application/json");

$artist_id = getArtistID(new mysqli("localhost","root","","albart"));
if (!$artist_id) {
    echo json_encode(["status"=>"error","message"=>"Ky user nuk është artist ose nuk është loguar."]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) { echo json_encode(["status"=>"error","message"=>"Gabim lidhjeje me DB"]); exit; }

$name = $_POST["name"] ?? '';
$category_id = intval($_POST['category_id'] ?? 0); // merr ID e kategorisë
$description = $_POST['description'] ?? '';
$price = floatval($_POST['price'] ?? 0);

if ($category_id <= 0) {
    echo json_encode(["status"=>"error","message"=>"Zgjidh një kategori."]);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
    echo json_encode(["status"=>"error","message"=>"Imazhi nuk u ngarkua."]);
    exit;
}

// Ruaj imazhin
$uploadDir = "../uploads/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$filename = "product_" . $artist_id . "_" . time() . "." . $ext;
$targetPath = $uploadDir . $filename;
if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
    echo json_encode(["status"=>"error","message"=>"Gabim gjatë ruajtjes së fotos"]);
    exit;
}

// Shkruaj produktin në DB (tani me Kategori_ID)
$stmt = $conn->prepare("INSERT INTO Produkti (Emri, Pershkrimi, Cmimi, Foto_Produktit, Artist_ID, Kategori_ID) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssdsii", $name, $description, $price, $filename, $artist_id, $category_id);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Gabim gjatë shtimit në DB"]);
}
