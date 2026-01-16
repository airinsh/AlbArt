<?php
$email = isset($_GET['email']) ? $_GET['email'] : '';
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Verifiko Kod - AlbArt</title>
    <link rel="stylesheet" href="../css/verify.css">
</head>
<body>

<div class="verify-section">
    <h1>Verifikimi</h1>
    <span id="verify-message"></span>

    <form id="verifyForm">
        <div class="form-row">
            <label>Email:</label>
            <input type="email" name="email" id="email"
                   value="<?= htmlspecialchars($email) ?>" required>
        </div>

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

        fetch("verify-reset.ajax.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                message.innerText = data.message;
                message.style.color = data.status === "success" ? "green" : "red";
                if(data.status === "success") {
                    setTimeout(() => {
                        window.location.href = `reset-password.php?email=${encodeURIComponent(document.getElementById("email").value)}`;
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
