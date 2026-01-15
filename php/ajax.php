<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data["name"]);
$email = trim($data["email"]);
$password = $data["password"];
$preferenca = isset($data["preferenca"]) ? implode(", ", $data["preferenca"]) : "";


// Kontrollim të dhënash të plota
if ($name === "" || $email === "" || $password === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Të dhëna të paplota."
    ]);
    exit;
}


// Lidhja me DB
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Gabim lidhjeje me databazën."
    ]);
    exit;
}


// Kontroll email unik
$check = $conn->prepare("SELECT id FROM Users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // Email ekziston → mos fut asgjë në DB
    echo json_encode([
        "status" => "error",
        "message" => 'Ky email është i regjistruar më parë. <a href="login.php" style="color:blue; text-decoration:underline;">LOGIN</a>'
    ]);
    $check->close();
    $conn->close();
    exit;
}


// Email nuk ekziston → vazhdo me regjistrimin
$check->close();


// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


// INSERT në tabelën Users
$stmt = $conn->prepare("INSERT INTO Users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;


    // INSERT në tabelën Klient
    $stmt2 = $conn->prepare("INSERT INTO Klient (Preferenca, User_ID) VALUES (?, ?)");
    $stmt2->bind_param("si", $preferenca, $user_id);
    $stmt2->execute();
    $stmt2->close();

    echo json_encode([
        "status" => "success",
        "message" => "Regjistrimi u krye me sukses."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gabim gjatë regjistrimit."
    ]);
}

$stmt->close();
$conn->close();
