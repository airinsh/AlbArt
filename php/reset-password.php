<?php
$email = isset($_GET['email']) ? $_GET['email'] : '';
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/verify.css">
</head>
<body>

<div class="verify-section">
    <h1>Reset Password</h1>
    <span id="reset-message"></span>

    <form id="resetForm">
        <div class="form-row">
            <label>Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" readonly>
        </div>

        <div class="form-row">
            <label>Password i ri:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-row">
            <label>Konfirmo Password:</label>
            <input type="password" name="confirm" id="confirm" required>
        </div>

        <button class="btn" type="submit">Ndrysho Password</button>
    </form>
</div>

<script>
    document.getElementById("resetForm").addEventListener("submit", function(e){
        e.preventDefault();
        const message = document.getElementById("reset-message");

        const password = document.getElementById("password").value;
        const confirm = document.getElementById("confirm").value;
        if(password !== confirm){
            message.innerText = "Passwords nuk përputhen.";
            message.style.color = "red";
            return;
        }

        const formData = new FormData(this);

        fetch("reset-password.ajax.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                message.innerText = data.message;
                message.style.color = data.status === "success" ? "green" : "red";
                if(data.status === "success"){
                    setTimeout(() => {
                        window.location.href = "login.php";
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
