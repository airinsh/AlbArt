<?php
header("Content-Type: application/json");

// Kontrollo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "POST kërkohet"]);
    exit;
}

// Merr user_id dhe file
$user_id = $_POST['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "ID e artistit mungon"]);
    exit;
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0) {
    echo json_encode(["status" => "error", "message" => "Foto nuk u ngarkua"]);
    exit;
}

// Lidhja me DB
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Gabim lidhjeje me DB"]);
    exit;
}

// Folder ku do ruhen fotot
$uploadDir = "../uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Emri unik i fotos për të mos e mbishkruar
$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$filename = "profile_" . $user_id . "_" . time() . "." . $ext;
$targetPath = $uploadDir . $filename;

// Lëviz file-in në folderin e uploads
if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
    echo json_encode(["status" => "error", "message" => "Gabim gjatë ruajtjes së fotos"]);
    exit;
}

// Ruaj path-in në DB (vetëm link relativ)
$relativePath = "uploads/" . $filename;
$stmt = $conn->prepare("UPDATE Artisti SET Fotografi = ? WHERE User_ID = ?");
$stmt->bind_param("si", $relativePath, $user_id);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Foto e ruajtur me sukses", "path" => $relativePath]);
} else {
    echo json_encode(["status" => "error", "message" => "Gabim gjatë update në DB"]);
}
?>
