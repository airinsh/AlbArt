<?php
require_once '../php/auth.php';
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) die("Gabim lidhjeje me DB");

$artist_id = getArtistID($conn);
if (!$artist_id) {
    die("Ky user nuk është artist ose nuk ekziston.");
}

?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profili Artist</title>


    <link rel="stylesheet" href="../css/Profili-Artistit.css">
</head>
<body>


<header class="site-header">
    <div class="header-left">
        <button onclick="window.location.href='../php/Homepage.php'"
                style="background-color:#fff; color:#a2b5cc; font-family:'Segoe Print', cursive; font-size:16px; font-weight:bold; padding:10px 20px; border:none; border-radius:12px; cursor:pointer; box-shadow:0 4px 8px rgba(0,0,0,0.2); transition:0.3s;">
            Homepage
        </button>
    </div>
    <div class="header-center">
        <h1>Profili i Artistit</h1>
    </div>
</header>




<main class="profile-page">



    <section class="profile-card">



        <div class="profile-header">

            <!-- FOTO -->
            <img src="../img/Piktura.jpeg" alt="Foto Profilit" class="profile-photo" id="profile-photo">


            <!-- LINK PËR EDITO FOTON -->
            <p>
                <a href="#" id="edit-photo-link" style="color:#fff; text-decoration:underline; cursor:pointer;">Edito foton</a>
            </p>


            <input type="file" id="photo-input" accept="image/*" style="display:none;">


            <h2 class="profile-name">
                <span id="artist-name">Duke u ngarkuar...</span>
                <button id="edit-name-btn" class="edit-btn">Edit</button>
            </h2>


            <p class="profile-role" id="artist-role">
                Artist
            </p>
        </div>


        <div class="profile-body">


            <div class="profile-description-container">
                <p id="artist-description">Duke u ngarkuar përshkrimi...</p>
                <button id="edit-desc-btn" class="edit-btn">Edit</button>
            </div>





            <div class="profile-rating-container">
                <div class="profile-rating">
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="rating-score" id="rating-score">0.0</span>
                </div>

                <div class="add-product-btn">
                    <button onclick="window.location.href='ArtistiShtonProdukt.php'">
                        Shto Produkt
                    </button>
                </div>
            </div>

        </div>

    </section>


    <section class="profile-content">


        <div class="tab-content" id="veprat">
            <h3>Veprat</h3>
            <p class="placeholder">Nuk ka ende vepra.</p>
        </div>


        <div class="tab-content" id="certifikime">
            <h3>Certifikime</h3>
            <p class="placeholder">Nuk ka certifikime.</p>
        </div>


        <div class="tab-content" id="reviews">
            <h3>Reviews</h3>
            <p class="placeholder">Nuk ka ende vlerësime.</p>
        </div>

    </section>

</main>

<!--popup edit emri-->
<div id="editNameModal" class="modal">
    <div class="modal-content">
        <h3>Edit Emrin</h3>

        <label>Emri:</label>
        <input type="text" id="edit-name-input">

        <label>Mbiemri:</label>
        <input type="text" id="edit-surname-input">

        <div class="modal-actions">
            <button id="save-name-btn">Save</button>
            <button id="cancel-name-btn">Cancel</button>
        </div>
    </div>
</div>

<!--popup edit description-->
<div id="editDescModal" class="modal">
    <div class="modal-content">
        <h3>Edit Përshkrimin</h3>
        <textarea id="edit-desc-input" rows="5" placeholder="Shkruaj përshkrimin e artistit..."></textarea>
        <div class="modal-actions">
            <button id="save-desc-btn">Save</button>
            <button id="cancel-desc-btn">Cancel</button>
        </div>
    </div>
</div>

<!--popup edit produkt-->
<div id="editWorkModal" class="modal">
    <div class="modal-content">
        <h3>Edit Veprën</h3>
        <label>Emri:</label>
        <input type="text" id="edit-work-name">
        <label>Përshkrimi:</label>
        <textarea id="edit-work-desc" rows="4"></textarea>
        <label>Cmimi:</label>
        <input type="number" id="edit-work-price" step="0.01">
        <div class="modal-actions">
            <button id="save-work-btn" class="save-btn">Save</button>
            <button id="cancel-work-btn">Cancel</button>
        </div>
    </div>
</div>


<script src="../php/Profili-Artistit.js"></script>
</body>
</html>
