<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Sign Up - AlbArt</title>
    <link rel="stylesheet" href="../css/artist-signup.css">
</head>
<body>
<div class="signup-section">
    <h1>Artist</h1>
    <form class="signup-form" id="artistSignupForm" enctype="multipart/form-data">

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

        <div class="form-row">
            <label>Description</label>
            <textarea name="description" id="description" rows="4" placeholder="Tell us about yourself" required></textarea>
        </div>

        <div class="form-row">
            <label>Certifikime (PDF)</label>
            <input type="file" name="certification" id="certification" accept=".pdf" required>
        </div>

        <div class="form-row checkbox-row">
            <input type="checkbox" id="terms" required>
            <span>I agree to <a href="#" title="Click to read AlbArt Terms and Conditions">AlbArt Terms and Conditions</a></span>
        </div>

        <p id="message"></p>
        <button type="submit" class="btn">Continue</button>
    </form>
</div>

<script src="../php/Artist-signup.js"></script>
</body>
</html>
