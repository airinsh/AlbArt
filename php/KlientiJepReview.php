<?php
require_once "../php/auth.php"; // thjesht require auth.php që bën lidhjen dhe session

// Kontrollo që përdoruesi është loguar
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Merr Artist_ID nga URL
$Artist_ID = isset($_GET['Artist_ID']) ? intval($_GET['Artist_ID']) : 0;
if ($Artist_ID === 0) {
    die("Gabim: Artist i pavlefshëm.");
}

// Merr të dhënat e artistit: emri nga Users, foto dhe rating nga Artisti
$stmt = $conn->prepare("
    SELECT u.name, u.surname, a.Fotografi, a.Vleresimi_Total
    FROM Artisti a
    JOIN Users u ON a.User_ID = u.id
    WHERE a.Artist_ID = ?
");
$stmt->bind_param("i", $Artist_ID);
$stmt->execute();
$artist = $stmt->get_result()->fetch_assoc();

if (!$artist) {
    die("Artist nuk u gjet.");
}

// Vendos default nëse nuk ka foto
$artistPhoto = $artist['Fotografi'] ? "../" . $artist['Fotografi'] : "../img/default-artist.png";
$artistName = $artist['name'] . " " . $artist['surname'];
$artistRating = $artist['Vleresimi_Total'] ?? 0;

// Për AJAX: nëse është klient vendos Klient_ID, nëse jo, vendos 0 ose null
$klient_id = ($_SESSION['role'] ?? '') === 'klient' ? getKlientID($conn) : 0;
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review-Artistit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/KlientiJepReview.css">
</head>
<body>

<nav class="navbar navbar-dark main-color px-4">
    <span class="navbar-brand mb-0 h1">Review</span>
</nav>

<div class="container my-5">
    <div class="card shadow-lg rounded-4 p-4">

        <!-- ARTIST INFO -->
        <div class="text-center mb-4">
            <img src="<?= $artistPhoto ?>" class="artist-photo mb-3" alt="Artist">
            <h3 class="mb-1"><?= $artistName ?></h3>
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
            <input type="hidden" id="Artist_ID" value="<?= $Artist_ID ?>" data-rating="<?= $artistRating ?>">
            <input type="hidden" id="Klient_ID" value="<?= $klient_id ?>">
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

<script src="../php/KlientiJepReview.js"></script>
</body>
</html>
