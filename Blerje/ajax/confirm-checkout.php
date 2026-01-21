<?php
session_start();
require_once "../../includes/auth.php";
header("Content-Type: application/json");

require_once __DIR__ . '/../../vendor/autoload.php';
$stripeSecret = getEnv('STRIPE_SECRET_KEY');
\Stripe\Stripe::setApiKey("STRIPE_SECRET_KEY"); // Secret Key

if(!isset($_SESSION['user_id'])){
    echo json_encode(["error" => "Jo i loguar"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$paymentIntentId = $input['payment_intent_id'] ?? "";
if($paymentIntentId == ""){
    echo json_encode(["error" => "Pagesë e pavlefshme"]);
    exit;
}

$conn = new mysqli("localhost","root","","albart");
if ($conn->connect_error) {
    echo json_encode(["error" => "Gabim lidhjeje me databazën"]);
    exit;
}

// Gjej klientin
$klientId = getKlientID($conn);

if (!$klientId) {
    echo json_encode(["error" => "Klienti nuk u gjet"]);
    exit;
}

// Verifiko PaymentIntent
try {
    $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
    if($paymentIntent->status !== "succeeded"){
        echo json_encode(["error" => "Pagesa nuk është konfirmuar nga Stripe"]);
        exit;
    }
} catch(Exception $e){
    echo json_encode(["error" => "Gabim Stripe: ".$e->getMessage()]);
    exit;
}

// Llogarit totalin
$total = 0;
$cartRes = $conn->query("
    SELECT p.Cmimi 
    FROM Artikull_Cart ac
    JOIN Produkti p ON ac.Produkt_ID = p.Produkt_ID
    WHERE ac.Klient_ID = $klientId
");
while($r = $cartRes->fetch_assoc()){
    $total += $r['Cmimi'];
}

// Gjej produktet ne databaze
$productIds = [];
$cartRes2 = $conn->query("
    SELECT Produkt_ID 
    FROM Artikull_Cart 
    WHERE Klient_ID = $klientId
");
while($row = $cartRes2->fetch_assoc()){
    $productIds[] = $row['Produkt_ID'];
}

// INSERT PAGESA
$stmt = $conn->prepare("
    INSERT INTO Pagesa (Stripe_PaymentIntent_ID, Shuma, Statusi)
    VALUES (?, ?, 'Paid')
");
$stmt->bind_param("sd", $paymentIntentId, $total);
$stmt->execute();
$pagesaId = $stmt->insert_id;

// INSERT POROSI
$stmt = $conn->prepare("
    INSERT INTO Porosi (Statusi, Klient_ID, Pagesa_ID)
    VALUES ('Confirmed', ?, ?)
");
$stmt->bind_param("ii", $klientId, $pagesaId);
$stmt->execute();
$porosiId = $stmt->insert_id;

// UPDATE CART-POROSI
$conn->query("
    UPDATE Artikull_Cart
    SET Porosi_ID = $porosiId
    WHERE Klient_ID = $klientId
");

// CLEAR CART
$conn->query("DELETE FROM Artikull_Cart WHERE Klient_ID = $klientId");

// Fshi produktet e shitura (sepse jane unike)
if (!empty($productIds)) {
    $ids = implode(",", array_map("intval", $productIds));
    $conn->query("DELETE FROM Produkti WHERE Produkt_ID IN ($ids)");
}

// Pasi pagesa u krye
$stmt = $conn->prepare("
    INSERT INTO stripe_logs 
    (user_id, payment_intent_id, status, amount, currency, data, error_message)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$userId = $_SESSION['user_id'];
$paymentIntentId = $paymentIntent->id;
$status = $paymentIntent->status;
$amount = $total; // nga totali i cart
$currency = $paymentIntent->currency;
$data = json_encode($paymentIntent); // ruaj te gjithe objektin Stripe
$error = $paymentIntent->last_payment_error->message ?? null;



$stmt->bind_param("issdsss", $userId, $paymentIntentId, $status, $amount, $currency, $data, $error);
$stmt->execute();

echo json_encode(["success" => true]);



