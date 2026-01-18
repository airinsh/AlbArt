<?php
require_once "auth.php";

if (!isset($_GET['kategori'])) {
    header("Location: Homepage.php");
    exit;
}

$kategoriId = intval($_GET['kategori']);
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Produktet</title>
    <link rel="stylesheet" href="../css/HomePage.css">
</head>
<body>



<header style="display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background-color: #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
    <!-- Logo -->
    <div class="logo" style="font-weight: bold; font-size: 2rem; color: #000;">AlbArt</div>

    <!-- Butoni HomePage -->
    <button
            onclick="window.location.href='Homepage.php'"
            style="
            background-color: #8c9abf;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        "
            onmouseover="this.style.backgroundColor='#7481a3'; this.style.transform='scale(1.05)';"
            onmouseout="this.style.backgroundColor='#4d6883'; this.style.transform='scale(1)';"
    >
        HomePage
    </button>
</header>



<main>
    <section class="works-section">
        <h2>Produktet</h2>
        <div class="works-container"></div>
    </section>
</main>

<footer>
    <p>&copy; 2026 AlbArt</p>
</footer>

<script>
    const kategoriId = <?= $kategoriId ?>;

    fetch(ProduktetSipasKategorise.php?kategori=${kategoriId})

        .then(res => res.json())
        .then(products => {
            const container = document.querySelector(".works-container");
            container.innerHTML = "";

            if (products.length === 0) {
                container.innerHTML = "<p>Nuk ka produkte për këtë kategori.</p>";
                return;
            }

            products.forEach(p => {
                const div = document.createElement("div");
                div.className = "work-item";

                div.innerHTML = `
                <div class="work-image">
                    <img src="../uploads/${p.Foto_Produktit}" alt="${p.Emri}">
                </div>
                <div class="work-info">
                    <p class="category">${p.Kategori_Emri}</p>
                    <p class="name">${p.Emri}</p>
                    <p class="price">$${p.Cmimi}</p>
                    <button class="details-btn">Details</button>
                </div>
            `;

                div.querySelector(".details-btn").onclick = () => {
                    window.location.href = DetajeProdukti.php?id=${p.Produkt_ID};
                };

                container.appendChild(div);
            });
        })
        .catch(err => console.error("Gabim fetch:", err));
</script>

</body>
</html>