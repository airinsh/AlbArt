<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikimi i Email-it</title>
    <link rel="stylesheet" href="../css/verify.css">
</head>
<body>
<div class="verify-section">
    <h1>Verifiko Email-in</h1>

    <div id="verify-message"></div>

    <form id="verify-form">
        <div class="form-row">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-row">
            <label for="code">Kodi:</label>
            <input type="text" id="code" name="code" required>
        </div>

        <button type="submit" class="btn">Verifiko</button>
    </form>

    <div class="verify-footer">
        Nuk ke marrë kodin? <a href="resend-code.php">Dërgo përsëri kodin</a>
    </div>
</div>

<script>
    const form = document.getElementById('verify-form');
    const message = document.getElementById('verify-message');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('verify-jax.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                message.textContent = data;
                if(data.includes('sukses')) {
                    message.style.color = 'green';
                    form.reset();
                } else {
                    message.style.color = '#dc3545';
                }
            })
            .catch(error => {
                message.textContent = 'Ndodhi një gabim. Provoni përsëri.';
                message.style.color = '#dc3545';
            });
    });
</script>
</body>
</html>
