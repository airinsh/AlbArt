<?php
require_once "../includes/auth.php";
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f2f2f2; }
        .main-color { background:#a2b5cc; }
        .work-card {
            background:white;
            border-radius:12px;
            padding:15px;
            margin-bottom:15px;
            display:flex;
            gap:15px;
            align-items:center;
        }
        .return-btn {
            background-color: #ffffff;
            color: #a2b5cc;
            border: 2px solid #ffffff;
            border-radius: 20px;
            padding: 5px 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .return-btn:hover {
            background-color: transparent;
            color: #ffffff;
            border-color: #ffffff;
        }

        .work-card img {
            width:120px;
            height:120px;
            object-fit:cover;
            border-radius:10px;
        }
    </style>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const STRIPE_PUBLISHABLE_KEY = "STRIPE_PUBLISHABLE_KEY";
    </script>
</head>
<body>

<!-- TOOLBAR -->
<nav class="navbar navbar-dark main-color px-4 d-flex justify-content-between align-items-center">
    <span class="navbar-brand mb-0 h1">Checkout</span>

    <!-- Butoni Return to ShoppingCart -->
    <button class="return-btn"
            onclick="window.location.href='../ShoppingCart/ShoppingCart.php'">
        Shopping Cart
    </button>
</nav>


<div class="container my-5">

    <!-- PRODUKTET -->
    <div class="works-container mb-4">
        <!-- ngarkohen nga Checkout.js -->
    </div>

    <!-- TOTALI -->
    <div class="card mb-4 p-3 text-end">
        <h5>Total Produkte: <span id="total-items">0</span></h5>
        <h4>Total Cmimi: $<span id="total-price">0.00</span></h4>
    </div>

    <!-- PAGESA -->
    <div class="card p-4">
        <h5 class="mb-3">Payment</h5>

        <div class="mb-3">
            <label class="form-label">Kartë krediti / debiti</label>
            <div id="card-element" class="form-control"></div>
        </div>


        <button class="btn btn-success w-100" id="confirmBtn">
            Confirm Order
        </button>
    </div>

</div>

<script src="../Blerje/Checkout.js"></script>

</body>
</html>
