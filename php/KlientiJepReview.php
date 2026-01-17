<?php
session_start();

// Kontrollo nëse klienti është loguar
if (!isset($_SESSION['Klient_ID'])) {
    header("Location: login.php");
    exit;
}

// Merr Artist_ID nga URL
$Artist_ID = isset($_GET['Artist_ID']) ? intval($_GET['Artist_ID']) : 0;
if ($Artist_ID === 0) {
    die("Gabim: Artist i pavlefshëm.");
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review-Artistit</title>

    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS i veçantë -->
    <link rel="stylesheet" href="../css/KlientiJepReview.css">
</head>
<body>

<!-- TOOLBAR -->
<nav class="navbar navbar-dark main-color px-4">
    <span class="navbar-brand mb-0 h1">Review</span>
</nav>

<!-- PAGE -->
<div class="container my-5">
    <div class="card shadow-lg rounded-4 p-4">

        <!-- ARTIST INFO -->
        <div class="text-center mb-4">
            <img src="Piktura.jpeg" class="artist-photo mb-3" alt="Artist">
            <h3 class="mb-1">Emri i Artistit</h3>
        </div>

        <!-- VLERËSIMI -->
        <div class="text-center mb-4">
            <p class="fw-semibold mb-2">Vlerësimi</p>

            <div class="d-flex justify-content-center gap-2" id="stars">
                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
            </div>

            <small class="text-muted">Zgjidh vlerësimin</small>

            <!-- Input i fshehtë për AJAX -->
            <input type="hidden" id="Artist_ID" value="<?= $Artist_ID ?>">

        </div>

        <!-- KOMENTE -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Komente</label>
            <textarea id="comment" class="form-control" rows="4" placeholder="Shkruaj mendimin tënd këtu..."></textarea>
        </div>

        <!-- BUTON -->
        <div class="text-center">
            <button class="btn btn-send px-5 fw-semibold" onclick="sendReview()">
                Dërgo
            </button>
        </div>

        <!-- MESAZHI PAS DËRGIMIT -->
        <div id="successMessage" class="text-center mt-4 d-none">
            <div class="alert alert-success fw-semibold">
                Review juaj u dërgua ✅
            </div>

            <a href="Homepage.php" class="btn btn-outline-primary fw-semibold">
                Kthehu te Homepage
            </a>
        </div>

    </div>
</div>

<!-- JS FILE -->
<script src="../php/KlientiJepReview.js"></script>

</body>
</html>
