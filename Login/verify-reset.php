<?php
session_start();

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Verifiko Kodin</title>
    <link rel="stylesheet" href="../Signup/verify.css">
</head>
<body>

<div class="verify-section">
    <h1>Verifikimi</h1>

    <span id="verify-message"></span>

    <form id="verifyForm">
        <div class="form-row">
            <label>Kodi:</label>
            <input type="text" name="code" id="code" required>
        </div>

        <button class="btn" type="submit">Verifiko</button>
    </form>
</div>

<script>
    document.getElementById("verifyForm").addEventListener("submit", function(e){
        e.preventDefault();

        const message = document.getElementById("verify-message");
        const formData = new FormData(this);

        fetch("ajax/verify-reset.ajax.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.text())
            .then(data => {
                message.innerHTML = data;

                if (data.toLowerCase().includes("sukses")) {
                    message.style.color = "green";
                    setTimeout(() => {
                        window.location.href = "../Login/reset-password.php";
                    }, 1500);
                } else {
                    message.style.color = "red";
                }
            })
            .catch(() => {
                message.innerText = "Ndodhi një gabim.";
                message.style.color = "red";
            });
    });
</script>

</body>
</html>
