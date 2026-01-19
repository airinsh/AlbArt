<?php
session_start();
header("Content-Type: application/json");

require_once __DIR__ . '/../vendor/autoload.php';
$stripeSecret = getEnv('STRIPE_SECRET_KEY');
\Stripe\Stripe::setApiKey("STRIPE_SECRET_KEY"); // 🔴 Vendos Secret Key këtu

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
$userId = $_SESSION['user_id'];
$res = $conn->query("SELECT Klient_ID FROM Klient WHERE User_ID = $userId");
if(!$res || $res->num_rows == 0){
    echo json_encode(["error" => "Klienti nuk u gjet"]);
    exit;
}
$row = $res->fetch_assoc();
$klientId = $row['Klient_ID'];

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

// INSERT PAYMENT
$stmt = $conn->prepare("
    INSERT INTO Pagesa (Stripe_PaymentIntent_ID, Shuma, Statusi)
    VALUES (?, ?, 'Paid')
");
$stmt->bind_param("sd", $paymentIntentId, $total);
$stmt->execute();
$pagesaId = $stmt->insert_id;

// INSERT ORDER
$stmt = $conn->prepare("
    INSERT INTO Porosi (Statusi, Klient_ID, Pagesa_ID)
    VALUES ('Confirmed', ?, ?)
");
$stmt->bind_param("ii", $klientId, $pagesaId);
$stmt->execute();
$porosiId = $stmt->insert_id;

// UPDATE CART -> ORDER
$conn->query("
    UPDATE Artikull_Cart
    SET Porosi_ID = $porosiId
    WHERE Klient_ID = $klientId
");

// EMPTY CART
$conn->query("DELETE FROM Artikull_Cart WHERE Klient_ID = $klientId");

echo json_encode(["success" => true]);

// Pasi pagesa u krye
$stmt = $conn->prepare("
    INSERT INTO stripe_logs 
    (user_id, payment_intent_id, status, amount, currency, data, error_message)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$userId = $_SESSION['user_id'];
$paymentIntentId = $paymentIntent->id;
$status = $paymentIntent->status;
$amount = $total; // nga totali i shportes
$currency = $paymentIntent->currency;
$data = json_encode($paymentIntent); // ruaj të gjithë objektin Stripe
$error = $paymentIntent->last_payment_error->message ?? null;

$stmt->bind_param("issdsss", $userId, $paymentIntentId, $status, $amount, $currency, $data, $error);
$stmt->execute();

