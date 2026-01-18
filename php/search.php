<?php
require_once "auth.php";
$conn = new mysqli("localhost", "root", "", "albart");

$search = isset($_GET['q']) ? trim($_GET['q']) : "";

$artists = [];
$products = [];

if ($search !== "") {

    //  Kërkimi i artistëve
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

    //  Kërkimi i produkteve
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
    <link rel="stylesheet" href="../css/search.css">
</head>
<body>

<header>
    <div class="logo">AlbArt</div>
    <div class="toolbar">
        <div class="toolbar-item" onclick="window.location.href='HomePage.php'">Home</div>
    </div>
</header>

<main>
    <h2>Rezultatet për: "<span><?= htmlspecialchars($search) ?></span>"</h2>

    <!-- ================= ARTISTËT ================= -->
    <section class="artists-section">
        <h3>Artistë</h3>
        <div class="artists-container">
            <?php if (count($artists) > 0): ?>
                <?php foreach ($artists as $artist): ?>
                    <div class="artist-card">
                        <?php
                        $foto = !empty($artist['Fotografi']) ? "../uploads/" . $artist['Fotografi'] : "../img/default-profile.png";
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

    <!-- ================= PRODUKTET ================= -->
    <section class="works-section">
        <h3>Produkte</h3>
        <div class="works-container">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="work-item">
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
</main>

<footer>
    <p>&copy; 2026 AlbArt. Të gjitha të drejtat e rezervuara.</p>
</footer>



</body>
</html>
