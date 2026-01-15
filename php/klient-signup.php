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

    <form class="signup-form" id="signupForm">

        <div class="form-row">
            <label>Emri</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-row">
            <label>Email</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-row">
            <label>Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="checkbox-row">
            <input type="checkbox" id="terms" required>
            <span>I agree to <a href="#">AlbArt Terms and Conditions</a></span>
        </div>

        <!-- Preferenca -->
        <h2>Preferenca</h2>
        <div class="preferences">
            <div class="pref-item">
                <img src="../img/NatyreDhePeisazhe.jpeg">
                <span>Natyrë dhe peisazhe</span>
                <input type="checkbox" class="pref-check" name="preferenca[]" value="Natyrë dhe peisazhe">
            </div>

            <div class="pref-item">
                <img src="../img/SkulptureKlasike.jpeg">
                <span>Skulpturë Klasike</span>
                <input type="checkbox" class="pref-check" name="preferenca[]" value="Skulpturë Klasike">
            </div>

            <div class="pref-item">
                <img src="../img/Pocari.jpeg">
                <span>Poçari</span>
                <input type="checkbox" class="pref-check" name="preferenca[]" value="Poçari">
            </div>

            <div class="pref-item">
                <img src="../img/Portrete.jpeg">
                <span>Portrete</span>
                <input type="checkbox" class="pref-check" name="preferenca[]" value="Portrete">
            </div>

            <div class="pref-item">
                <img src="../img/DhurataTePersonalizuara.jpeg">
                <span>Dhurata të personalizuara</span>
                <input type="checkbox" class="pref-check" name="preferenca[]" value="Dhurata të personalizuara">
            </div>

            <div class="pref-item">
                <img src="../img/SkulptureAbstrakte.jpeg">
                <span>Skulpturë Abstrakte</span>
                <input type="checkbox" class="pref-check" name="preferenca[]" value="Skulpturë Abstrakte">
            </div>
        </div>

        <p id="message"></p>

        <button type="submit" class="btn">Continue</button>
    </form>

    <script src="signup.js"></script>

</div>

</body>
</html>
