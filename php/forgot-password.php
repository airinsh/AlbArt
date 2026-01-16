<?php
session_start();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - AlbArt</title>
    <link rel="stylesheet" href="../css/verify.css">
</head>
<body>

<div class="verify-section">
    <h1>Reset Password</h1>
    <span id="message"></span>
    <form id="forgotForm">
        <div class="form-row">
            <label>Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <button class="btn" type="submit">Dërgo Kod</button>
    </form>
</div>

<script>
    document.getElementById("forgotForm").addEventListener("submit", function(e){
        e.preventDefault();
        const message = document.getElementById("message");
        const formData = new FormData(this);

        fetch("forgot-password.ajax.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                message.innerText = data.message;
                message.style.color = data.status === "success" ? "green" : "red";
                if(data.status === "success") {
                    setTimeout(() => {
                        window.location.href = `verify-reset.php?email=${encodeURIComponent(document.getElementById("email").value)}`;
                    }, 1500);
                }
            })
            .catch(() => {
                message.innerText = "Gabim me serverin.";
                message.style.color = "red";
            });
    });
</script>

</body>
</html>
