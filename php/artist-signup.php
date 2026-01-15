<?php
require_once "../includes/no_login/header.php";
?>

    <link rel="stylesheet" href="../css/artist-signup.css">
<div class="signup-section">
    <h1>Artist</h1>
    <form class="signup-form" enctype="multipart/form-data" onsubmit="return false;" role="form" action="login.php">
        <div class="form-row">
            <label>Emri</label>
            <input type="text" name="name" id="name" required>
            <small id="name_message" class="error-message"></small>        </div>

        <div class="form-row">
            <label>Email</label>
            <input type="email" name="email" id="email" required>
            <span id = "email_message" class="pull-left text-danger"></span>
</div>

        <div class="form-row">
            <label>Password</label>
            <input type="password" name="password" id="password" required>
            <span id = "password_message" class="pull-left text-danger"></span>
        </div>

        <div class="form-row">
            <label>Description</label>
            <textarea name="description" id="description" rows="4" placeholder="Tell us about yourself"></textarea>
        </div>

        <div class="form-row">
            <label>Certifikime</label>
            <input type="file" name="certification" id="certification" accept=".pdf">
            <span id = "certification_message" class="pull-left text-danger"></span>
        </div>

        <div class="form-row checkbox-row">
            <input type="checkbox" name="terms" id="terms" required>
            <span>I agree to <a href="#" title="Click to read AlbArt Terms and Conditions">AlbArt Terms and Conditions</a></span>
            <span id = "terms_message" class="pull-left text-danger"></span>
        </div>

        <button type="button" class="btn" onclick="registerArtist()">Continue</button>
    </form>
</div>

<?php
require_once "../includes/no_login/footer.php";
?>
<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        preventDuplicates: true,
        positionClass: "toast-top-right",
        timeOut: "7000"
    };

    function registerArtist() {
        let name = $("#name").val().trim();
        let email = $("#email").val().trim();
        let password = $("#password").val();
        let description = $("#description").val().trim();
        let certification = $("#certification")[0].files[0];
        let terms = $("#terms").is(":checked");

        let email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let name_regex = /^[a-zA-Z0-9 ]{3,40}$/;

        let error = 0;

        // Name
        if (!name_regex.test(name)) {
            $("#name").addClass("border-danger");
            $("#name_message").text("Name must be 3–40 letters or numbers.");
            error++;
        } else {
            $("#name").removeClass("border-danger");
            $("#name_message").text("");
        }

        // Email
        if (!email_regex.test(email)) {
            $("#email").addClass("border-danger");
            $("#email_message").text("Invalid email format.");
            error++;
        } else {
            $("#email").removeClass("border-danger");
            $("#email_message").text("");
        }

        // Password
        if (password.length < 6) {
            $("#password").addClass("border-danger");
            $("#password_message").text("Password must be at least 6 characters.");
            error++;
        } else {
            $("#password").removeClass("border-danger");
            $("#password_message").text("");
        }

        // Certification
        if (certification && certification.type !== "application/pdf") {
            $("#certification_message").text("Only PDF files allowed.");
            error++;
        } else {
            $("#certification_message").text("");
        }

        // Terms
        if (!terms) {
            $("#terms_message").text("You must accept Terms and Conditions.");
            error++;
        } else {
            $("#terms_message").text("");
        }

        if (error > 0) return;

        let data = new FormData();
        data.append("action", "artist_register");
        data.append("name", name);
        data.append("email", email);
        data.append("password", password);
        data.append("description", description);
        if (certification) data.append("certification", certification);

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                try {
                    response = JSON.parse(response);

                    if (response.status === 200) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.href = response.location;
                        }, 2000);
                    } else {
                        toastr.warning(response.message);
                    }
                } catch (e) {
                    toastr.error("Server error. Invalid response.");
                    console.error(response);
                }
            },
            error: function () {
                toastr.error("AJAX request failed.");
            }
        });
    }
</script>




