<?php
header("Content-Type: application/json");

// Marrja e të dhënave
$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
$surname = isset($_POST["surname"]) ? trim($_POST["surname"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = $_POST["password"] ?? "";
$description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
$certification = $_FILES["certification"] ?? null;

// Kontroll fushash required
if ($name === "" || $surname === "" || $email === "" || $password === "" || $description === "" || !$certification) {
    echo json_encode(["status"=>"error","message"=>"Plotëso të gjitha fushat e detyrueshme."]);
    exit;
}

// Lidhja me DB
$conn = new mysqli("localhost","root","","albart");
if($conn->connect_error){
    echo json_encode(["status"=>"error","message"=>"Gabim lidhjeje me databazën."]);
    exit;
}

// Kontroll email unik
$check = $conn->prepare("SELECT id FROM Users WHERE email=? LIMIT 1");
$check->bind_param("s",$email);
$check->execute();
$check->store_result();
if($check->num_rows>0){
    echo json_encode([
        "status"=>"error",
        "message"=>'Ky email është i regjistruar më parë. <a href="login.php" style="color:blue; text-decoration:underline;">LOGIN</a>'
    ]);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// Hash password
$hashedPassword = password_hash($password,PASSWORD_DEFAULT);

// INSERT në Users me mbiemrin
$stmt = $conn->prepare("INSERT INTO Users (name, surname, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss",$name, $surname, $email, $hashedPassword);

if($stmt->execute()){
    $user_id = $stmt->insert_id;

    // Ruajtja e certifikimit me emrin e përdoruesit
    $certPath = null;
    if($certification["error"] == 0){
        $ext = pathinfo($certification["name"], PATHINFO_EXTENSION);
        $safeName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $name . "_" . $surname);
        $certPath = "uploads/certifikime_" . $safeName . "." . $ext;

        if(!is_dir("../uploads")) {
            mkdir("../uploads", 0755, true);
        }

        move_uploaded_file($certification["tmp_name"], "../" . $certPath);
    }

    // INSERT në Artisti
    $stmt2 = $conn->prepare("INSERT INTO Artisti (Description, Certifikime, User_ID, Vleresimi_Total) VALUES (?, ?, ?, 0)");
    $stmt2->bind_param("ssi", $description, $certPath, $user_id);
    $stmt2->execute();
    $stmt2->close();

    echo json_encode(["status"=>"success","message"=>"Regjistrimi u krye me sukses."]);
} else {
    echo json_encode(["status"=>"error","message"=>"Gabim gjatë regjistrimit."]);
}

$stmt->close();
$conn->close();
