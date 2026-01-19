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

$conn = new mysqli("localhost","root","","albart");
if ($conn->connect_error) {
    echo json_encode(["error" => "Gabim lidhjeje me databazën"]);
    exit;
}

// Gjej klientin
$userId = $_SESSION['user_id'];
$res = $conn->query("SELECT Klient_ID FROM Klient WHERE User_ID=$userId");
if(!$res || $res->num_rows == 0){
    echo json_encode(["error" => "Klienti nuk u gjet"]);
    exit;
}
$row = $res->fetch_assoc();
$klientId = $row['Klient_ID'];

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

$amountInCents = intval(round($total * 100));
if($amountInCents <= 0){
    echo json_encode(["error" => "Shporta është bosh"]);
    exit;
}

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amountInCents,
        'currency' => 'usd',
        'automatic_payment_methods' => ['enabled' => true],
    ]);

    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret
    ]);
} catch(Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
