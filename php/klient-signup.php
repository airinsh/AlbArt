<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Sign Up - AlbArt</title>
    <link rel="stylesheet" href="../css/klient-signup.css">
</head>
<body>

<div class="signup-section">
    <h1>Klient</h1>

    <form class="signup-form">
        <div class="form-row">
            <label>Emri</label>
            <input type="text" required>
        </div>

        <div class="form-row">
            <label>Email</label>
            <input type="email" required>
        </div>

        <div class="form-row">
            <label>Password</label>
            <input type="password" required>
        </div>

        <div class="checkbox-row">
            <input type="checkbox" required>
            <span>I agree to <a href="#">AlbArt Terms and Conditions</a></span>
        </div>

        <h2>Preferenca</h2>

        <div class="preferences">
            <div class="pref-item">
                <img src="../img/NatyreDhePeisazhe.jpeg">
                <span>Natyrë dhe peisazhe</span>
                <input type="checkbox" class="pref-check">
            </div>

            <div class="pref-item">
                <img src="../img/SkulptureKlasike.jpeg">
                <span>Skulpturë Klasike</span>
                <input type="checkbox" class="pref-check">
            </div>

            <div class="pref-item">
                <img src="../img/Pocari.jpeg">
                <span>Poçari</span>
                <input type="checkbox" class="pref-check">
            </div>

            <div class="pref-item">
                <img src="../img/Portrete.jpeg">
                <span>Portrete</span>
                <input type="checkbox" class="pref-check">
            </div>

            <div class="pref-item">
                <img src="../img/DhurataTePersonalizuara.jpeg">
                <span>Dhurata të personalizuara</span>
                <input type="checkbox" class="pref-check">
            </div>

            <div class="pref-item">
                <img src="../img/SkulptureAbstrakte.jpeg">
                <span>Skulpturë Abstrakte</span>
                <input type="checkbox" class="pref-check">
            </div>
        </div>

        <button class="btn" onclick="window.location.href='Profili-Klient.html'">Continue</button>
    </form>
</div>

</body>
</html>
