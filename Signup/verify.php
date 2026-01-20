<?php
$email = isset($_GET['email']) ? $_GET['email'] : '';
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Verifiko Email-in</title>
    <link rel="stylesheet" href="verify.css">
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

        fetch("../Signup/ajax/verify.ajax.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.text())
            .then(data => {
                message.innerHTML = data;

                if (data.includes("sukses")) {
                    message.style.color = "green";
                    setTimeout(() => {
                        window.location.href = "../Login/login.php";
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
