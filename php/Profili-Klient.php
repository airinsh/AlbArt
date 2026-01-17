<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profili i Klientit</title>
    <link rel="stylesheet" href="../css/Profili-Klient.css">
</head>
<body>

<header class="site-header">
    <!-- BUTON HOMEPAGE -->
    <button onclick="window.location.href='../php/Homepage.php'"
            style="background-color:#fff; color:#a2b5cc; font-family:'Segoe Print', cursive; font-size:16px; font-weight:bold; padding:10px 20px; border:none; border-radius:12px; cursor:pointer; box-shadow:0 4px 8px rgba(0,0,0,0.2); transition:0.3s; margin-bottom:10px;">
        Homepage
    </button>

    <h1>Profili i Klientit</h1>
</header>

<main class="profile-page">

    <section class="profile-card">

        <div class="profile-header">
            <img src="../img/Piktura.jpeg" alt="Foto Klienti" class="profile-photo" id="client-photo">
            <h2 class="profile-name" id="client-name">Duke u ngarkuar...</h2>
        </div>

        <div class="profile-body">

            <h3>Orders</h3>
            <ul class="orders-list" id="orders-list">
                <li class="placeholder">Nuk ka porosi.</li>
            </ul>

            <h3>Reviews</h3>
            <ul class="reviews-list" id="reviews-list">
                <li class="placeholder">Nuk ka reviews.</li>
            </ul>

        </div>

    </section>

</main>

<script src="../php/Profili-Klient.js"></script>
</body>
</html>
