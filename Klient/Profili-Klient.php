<?php
require_once '../includes/auth.php';
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) die("Gabim lidhjeje me DB");

$klient_id = getKlientID($conn);
if (!$klient_id) die("Ky user nuk është klient ose nuk ekziston.");
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profili i Klientit</title>
    <link rel="stylesheet" href="Profili-Klient.css">
</head>
<body>

<header class="site-header">
    <button onclick="window.location.href='../HomePage/HomePage.php'"
            style="background-color:#fff; color:#a2b5cc; font-family:'Segoe Print', cursive; font-size:16px; font-weight:bold; padding:10px 20px; border:none; border-radius:12px; cursor:pointer; box-shadow:0 4px 8px rgba(0,0,0,0.2); transition:0.3s; margin-bottom:10px;">
        Homepage
    </button>
    <h1>Profili i Klientit</h1>
</header>

<main class="profile-page">

    <section class="profile-card">
        <div class="profile-header">
            <img src="../img/Piktura.jpeg" alt="Foto Klienti" class="profile-photo" id="klient-photo">
            <h2 class="profile-name" id="klient-name">Duke u ngarkuar...</h2>
        </div>

        <div class="profile-body">
            <h3>Blerjet</h3>
            <ul class="orders-list" id="orders-list">
                <li class="placeholder">Duke u ngarkuar...</li>
            </ul>

            <h3>Reviews</h3>
            <ul class="reviews-list" id="reviews-list">
                <li class="placeholder">Duke u ngarkuar...</li>
            </ul>
        </div>
    </section>

</main>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        fetch("ajax/get-klient-profile.php")
            .then(res => res.json())
            .then(data => {
                if(data.status !== "success"){
                    alert(data.message);
                    return;
                }

                // EMRI I KLIENTIT
                const k = data.klient;
                document.getElementById("klient-name").innerText = k.name + " " + k.surname;

                // ================= BLERJET =================
                const ordersList = document.getElementById("orders-list");
                ordersList.innerHTML = "";
                if(data.blerjet.length > 0){
                    data.blerjet.forEach(b => {
                        ordersList.innerHTML += `
            <li>
                <strong>Vepra:</strong> ${b.Emri}<br>
                <strong>Artisti:</strong> ${b.artist_name} ${b.artist_surname}
            </li>
        `;
                    });
                } else {
                    ordersList.innerHTML = `<li class="placeholder">Nuk ka ende blerje.</li>`;
                }


                // ================= REVIEWS =================
                const reviewsList = document.getElementById("reviews-list");
                reviewsList.innerHTML = "";
                if(data.reviews.length > 0){
                    data.reviews.forEach(r => {
                        reviewsList.innerHTML += `
                    <li>
                        <strong>${r.Vleresimi}★</strong> – ${r.Koment || "(pa koment)"}
                        <em>(Artist: ${r.artist_name} ${r.artist_surname})</em>
                    </li>
                `;
                    });
                } else {
                    reviewsList.innerHTML = `<li class="placeholder">Nuk ka ende vlerësime.</li>`;
                }

            })
            .catch(err => console.error("Gabim JS:", err));
    });
</script>

</body>
</html>
