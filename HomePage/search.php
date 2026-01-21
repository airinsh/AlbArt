<?php
require_once "../includes/auth.php";
$conn = new mysqli("localhost", "root", "", "albart");

$search = isset($_GET['q']) ? trim($_GET['q']) : "";

$artists = [];
$products = [];

if ($search !== "") {

    //  Kerkimi i artisteve
    $stmt = $conn->prepare("
    SELECT 
        Users.id, 
        Users.name, 
        Users.surname, 
        Artisti.Artist_ID, 
        Artisti.Fotografi
    FROM Artisti
    JOIN Users ON Artisti.User_ID = Users.id
    WHERE Users.name LIKE ? OR Users.surname LIKE ?
");

    $like = "%" . $search . "%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $artists = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    //  Kerkimi i produkteve
    $stmt2 = $conn->prepare("
        SELECT Produkt_ID, Emri, Foto_Produktit
        FROM Produkti
        WHERE Emri LIKE ?
    ");
    $stmt2->bind_param("s", $like);
    $stmt2->execute();
    $products = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Rezultatet e kërkimit</title>
    <link rel="stylesheet" href="search.css">
</head>
<body>

<header>
    <div class="logo">AlbArt</div>
    <div class="toolbar">
        <div class="toolbar-item" onclick="window.location.href='../HomePage/HomePage.php'">Home</div>
    </div>
</header>

<main>
    <h2>Rezultatet për: "<span><?= htmlspecialchars($search) ?></span>"</h2>

    //Artistet
    <section class="artists-section">
        <h3>Artistë</h3>
        <div class="artists-container">
            <?php if (count($artists) > 0): ?>
                <?php foreach ($artists as $artist): ?>
                    <div class="artist-card clickable-artist" data-id="<?= $artist['Artist_ID'] ?>">
                        <?php
                        $foto = !empty($artist['Fotografi']) ? "../" . $artist['Fotografi'] : "../img/default-profile.png";
                        ?>
                        <img src="<?= htmlspecialchars($foto) ?>" alt="Artist">
                        <p><?= htmlspecialchars($artist['name'] . " " . $artist['surname']) ?></p>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <p class="no-results">Nuk u gjet asnjë artist.</p>
            <?php endif; ?>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // Gjej te gjithe artistet me klasen clickable-artist
            const artistCards = document.querySelectorAll(".clickable-artist");
            artistCards.forEach(card => {
                card.addEventListener("click", () => {
                    const artistId = card.getAttribute("data-id");
                    if (artistId) {
                        // Redirect te KurKlikonArtist.php me ID e artistit
                        window.location.href = `../Review/KurKlikonArtist.php?id=${artistId}`;
                    }
                });
            });
        });
    </script>


    //Produktet
    <section class="works-section">
        <h3>Produkte</h3>
        <div class="works-container">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="work-item clickable-product" data-id="<?= $product['Produkt_ID'] ?>">
                        <div class="work-image">
                            <img src="../uploads/<?= htmlspecialchars($product['Foto_Produktit']) ?>" alt="Produkt">
                        </div>
                        <div class="work-info">
                            <p class="name"><?= htmlspecialchars($product['Emri']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results">Nuk u gjet asnjë produkt.</p>
            <?php endif; ?>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // Klikimi i produkteve
            const productCards = document.querySelectorAll(".clickable-product");
            productCards.forEach(card => {
                card.addEventListener("click", () => {
                    const productId = card.getAttribute("data-id");
                    if (productId) {
                        // Redirect te DetajeProdukti.php me ID e produktit
                        window.location.href = `../Produkt/DetajeProdukti.php?id=${productId}`;
                    }
                });
            });
        });
    </script>

</main>

<footer>
    <p>&copy; 2026 AlbArt. Të gjitha të drejtat e rezervuara.</p>
</footer>



</body>
</html>
