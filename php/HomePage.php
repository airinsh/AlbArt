<?php
require_once "auth.php";
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="../css/HomePage.css">
</head>
<body>

<header>
    <div class="logo">AlbArt</div>
    <div class="toolbar">

        <!-- Search me input që shfaqet brenda toolbar-it -->
        <div class="toolbar-item search" onclick="toggleSearch(event)">
            🔍 Search
            <input type="text" id="searchInput" placeholder="Kërko..." style="display:none;">
        </div>

        <div class="toolbar-item cart">Cart</div>
        <div class="toolbar-item profile" id="profileBtn">Profile</div>
    </div>
</header>

<main>

    <div class="gallery">
        <div class="art-item">
            <img src="../img/Piktura.jpeg" alt="Foto 1">
            <div class="art-caption">Piktura</div>
        </div>
        <div class="art-item">
            <img src="../img/Skulptura.jpeg" alt="Foto 2">
            <div class="art-caption">Skulptura</div>
        </div>
        <div class="art-item">
            <img src="../img/Artizanale.jpeg" alt="Foto 3">
            <div class="art-caption">Artizanale</div>
        </div>
    </div>

    <section class="artists-section">
        <h2>Artist</h2>
        <div class="artists-container">
            <button class="artist">
                <img src="artist1.jpg" alt="Artist 1">
                <p>Emri 1</p>
            </button>
            <button class="artist">
                <img src="artist2.jpg" alt="Artist 2">
                <p>Emri 2</p>
            </button>
            <button class="artist">
                <img src="artist3.jpg" alt="Artist 3">
                <p>Emri 3</p>
            </button>
            <button class="artist">
                <img src="artist4.jpg" alt="Artist 4">
                <p>Emri 4</p>
            </button>
            <button class="artist">
                <img src="artist5.jpg" alt="Artist 5">
                <p>Emri 5</p>
            </button>
            <button class="artist">
                <img src="artist6.jpg" alt="Artist 6">
                <p>Emri 6</p>
            </button>
        </div>
    </section>

    <!-- Seksioni Vepra -->
    <section class="works-section">
        <h2>Vepra</h2>
        <div class="works-container">

            <!-- Kutitë e veprave do të ngarkohen dinamikisht nga HomePage.js -->
        </div>
    </section>
</main>

<script>
    // Funksioni për Search input
    function toggleSearch(event) {
        event.stopPropagation(); // ndalon propagimin te body
        const input = document.getElementById('searchInput');
        if(input.style.display === 'none'){
            input.style.display = 'inline-block';
            input.focus();
        } else {
            input.style.display = 'none';
        }
    }

    // Fsheh input-in nëse klikoj jashtë tij
    document.body.addEventListener('click', function(){
        const input = document.getElementById('searchInput');
        input.style.display = 'none';
    });

    // Funksioni Add to Cart
    function addToCart(event, workName){
        event.stopPropagation(); // ndalon klikimin e kutisë
        alert("Shto në Cart: " + workName);
    }
</script>

<footer>
    <div class="footer-content">
        <p>&copy; 2026 AlbArt. Të gjitha të drejtat e rezervuara.</p>
    </div>
</footer>

<!-- JS që ngarkon produktet nga DB dhe hap DetajeProdukti.php -->
<script src="../php/HomePage.js"></script>

<script>
    // Funksioni për Search input
    function toggleSearch(event) {
        event.stopPropagation(); // ndalon propagimin te body
        const input = document.getElementById('searchInput');
        if(input.style.display === 'none'){
            input.style.display = 'inline-block';
            input.focus();
        } else {
            input.style.display = 'none';
        }
    }

    // Fsheh input-in nëse klikoj jashtë tij
    document.body.addEventListener('click', function(){
        const input = document.getElementById('searchInput');
        input.style.display = 'none';
    });

    // Klikimi i ikonës Cart
    const cartBtn = document.querySelector(".toolbar-item.cart");
    cartBtn.addEventListener("click", () => {
        window.location.href = "ShoppingCart.php"; // redirect te faqja e Cart
    });

    // Lidh me nderfaqen Search
        document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById("searchInput");
        const searchToolbar = document.querySelector(".toolbar-item.search");

        // Hap search kur klikon ikonën
        searchToolbar.addEventListener("click", (e) => {
        e.stopPropagation();
        searchInput.style.display = "inline-block";
        searchInput.focus();
    });

        // Parandalon mbylljen kur klikon brenda input-it
        searchInput.addEventListener("click", (e) => {
        e.stopPropagation();
    });

        // Enter → hap faqen SearchResults.php
        searchInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
        const query = searchInput.value.trim();
        if (query !== "") {
        window.location.href = `../php/search.php?q=${encodeURIComponent(query)}`;
    }
    }
    });

        // Klikimi jashtë e mbyll input-in
        document.body.addEventListener("click", () => {
        searchInput.style.display = "none";
    });
    });

</script>

<script>
    document.getElementById("profileBtn").addEventListener("click", () => {
        // Thërrasim PHP për të marrë tipin e userit
        fetch("../php/get-user-type.php")
            .then(res => res.json())
            .then(data => {
                if(data.status === "success") {
                    if(data.type === "artist") {
                        window.location.href = "Profili-Artistit.php";
                    } else if(data.type === "klient") {
                        window.location.href = "Profili-Klient.php";
                    } else {
                        alert("Tip useri i panjohur!");
                    }
                } else {
                    alert("Gabim: " + data.message);
                }
            })
            .catch(err => console.error("Gabim fetch user type:", err));
    });
</script>

</body>
</html>
