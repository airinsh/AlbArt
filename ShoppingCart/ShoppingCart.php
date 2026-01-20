<?php
session_start();

// Kontrollo nëse përdoruesi është loguar
if(!isset($_SESSION['user_id'])){
    header("Location: ../Login/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f2f2f2; }
        .main-color { background-color: #a2b5cc !important; }
        .work-card {
            display: flex;
            gap: 20px;
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            align-items: center;
        }
        .work-image img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
        }
        .work-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .work-name { font-weight: bold; font-size: 1.2rem; }
        .artist-photo { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; }
        .info-row { display: flex; align-items: center; gap: 10px; }
        .category, .price { margin: 0; font-size: 0.95rem; }
        /* Summary section */
        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            text-align: right;
            margin-top: 30px;
        }
        .checkout-btn {
            background-color: #a2b5cc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: #8ca0b4;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgb(182, 15, 28);
        }

        /* Për butonin Homepage që është gri fillimisht */
        #returnBtn {
            background-color: #888888;
            transition: all 0.3s ease;
        }

        #returnBtn:hover {
            background-color: #6f768a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgb(5, 57, 94);
        }
    </style>

</head>

<body>

<nav class="navbar navbar-dark main-color px-4">
    <span class="navbar-brand mb-0 h1 mx-auto">Shopping Cart</span>
</nav>

<div class="container my-5">
    <div class="works-container">
        <!-- Këtu JS do ngarkojë veprat -->
    </div>

    <!-- Summary + Checkout + Return -->
    <div class="summary-card d-flex justify-content-between align-items-center">
        <button class="checkout-btn" id="returnBtn" style="background-color:#888888;">Homepage</button>

        <div class="text-end">
            <p>Total Produkte: <span id="total-items">0</span></p>
            <p>Total Cmimi: $<span id="total-price">0.00</span></p>
            <button class="checkout-btn" id="checkoutBtn">Checkout</button>
        </div>
    </div>


    <script src="ShoppingCart.js"></script>
<script>
    // Shto event listener për butonin Checkout
    document.getElementById("checkoutBtn").addEventListener("click", () => {
        if(cart.length === 0){
            alert("Shporta është bosh!");
            return;
        }
        // Redirekto në faqen e pagesës ose krye Checkout
        window.location.href = "../Blerje/Checkout.php"; // ndrysho në faqen reale të pagesës
    });
</script>
</body>
</html>
