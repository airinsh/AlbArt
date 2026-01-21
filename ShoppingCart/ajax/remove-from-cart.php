<?php
session_start();
header("Content-Type: application/json");

// Kontrollo nëse përdoruesi është loguar
if(!isset($_SESSION['user_id'])){
    echo json_encode(["success" => false, "error" => "Duhet të jeni të loguar"]);
    exit;
}

// Merr te dhenat nga JS
$input = json_decode(file_get_contents("php://input"), true);
if(!isset($input['Produkt_ID'])){
    echo json_encode(["success" => false, "error" => "Produkt ID nuk u dërgua"]);
    exit;
}

$produktId = intval($input['Produkt_ID']);
$user_id = $_SESSION['user_id'];

// Lidhja me DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "albart";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    echo json_encode(["success" => false, "error" => $conn->connect_error]);
    exit;
}

// Merr Klient_ID nga user
$result = $conn->query("SELECT Klient_ID FROM Klient WHERE User_ID = $user_id LIMIT 1");
if($result->num_rows === 0){
    echo json_encode(["success" => false, "error" => "Klienti nuk u gjet"]);
    exit;
}
$klient = $result->fetch_assoc();
$klient_id = $klient['Klient_ID'];

// Fshi produktin nga DB
$stmt = $conn->prepare("DELETE FROM Artikull_Cart WHERE Produkt_ID = ? AND Klient_ID = ?");
$stmt->bind_param("ii", $produktId, $klient_id);
$stmt->execute();
$stmt->close();
$conn->close();

// Fshi gjithashtu nga session
if(isset($_SESSION['cart'])){
    $key = array_search($produktId, $_SESSION['cart']);
    if($key !== false){
        unset($_SESSION['cart'][$key]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // rikonfigurimi indeksave
    }
}

// Kthe sukses
echo json_encode(["success" => true]);
?>
