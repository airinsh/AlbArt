<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profili Artist</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/Profili-Artistit.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="site-header">
    <h1>Profili i Artistit</h1>
</header>

<!-- ================= MAIN ================= -->
<main class="profile-page">

    <!-- ===== CARD PROFILI ===== -->
    <section class="profile-card">

        <div class="profile-header">
            <img src="../img/Piktura.jpeg" alt="Foto Profilit" class="profile-photo" id="profile-photo">

            <h2 class="profile-name" id="artist-name">
                Duke u ngarkuar...
            </h2>

            <p class="profile-role" id="artist-role">
                Artist
            </p>
        </div>

        <div class="profile-body">
            <p class="profile-description" id="artist-description">
                Duke u ngarkuar përshkrimi...
            </p>

            <!-- ⭐ VLERËSIMI -->
            <div class="profile-rating">
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>

                <span class="rating-score" id="rating-score">
                    0.0
                </span>
            </div>
        </div>

    </section>

    <!-- ===== PËRMBAJTJE PROFILI ===== -->
    <section class="profile-content">

        <!-- VEPRAT -->
        <div class="tab-content" id="veprat">
            <h3>Veprat</h3>
            <p class="placeholder">Nuk ka ende vepra.</p>
        </div>

        <!-- CERTIFIKIME -->
        <div class="tab-content" id="certifikime">
            <h3>Certifikime</h3>
            <p class="placeholder">Nuk ka certifikime.</p>
        </div>

        <!-- REVIEWS -->
        <div class="tab-content" id="reviews">
            <h3>Reviews</h3>
            <p class="placeholder">Nuk ka ende vlerësime.</p>
        </div>

    </section>

</main>

<!-- ================= JS ================= -->
<script src="../php/Profili-Artistit.js"></script>
</body>
</html>
