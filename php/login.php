<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In - AlbArt</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
<h1>Log In</h1>

<form id="loginForm" class="login-container">
    <input type="email" placeholder="Email" id="email" class="input-box" required>
    <input type="password" placeholder="Password" id="password" class="input-box" required>

    <div class="remember-me">
        <input type="checkbox" id="remember">
        <label for="remember">Remember me</label>
    </div>

    <p id="message"></p>

    <button type="submit" class="btn">Continue</button>
</form>

<script src="login.js"></script>
</body>
</html>
