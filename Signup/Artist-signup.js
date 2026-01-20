document.getElementById("artistSignupForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("name");
    const surname = document.getElementById("surname");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const description = document.getElementById("description");
    const certification = document.getElementById("certification");
    const terms = document.getElementById("terms");
    const message = document.getElementById("message");

    message.innerHTML = "";

    // -------- VALIDIME
    if (!name.value.trim()) {
        message.innerText = "Fusha 'Emri' është e detyrueshme.";
        message.style.color = "red";
        return;
    }
    if (!surname.value.trim()) {
        message.innerText = "Fusha 'Mbiemri' është e detyrueshme.";
        message.style.color = "red";
        return;
    }
    if (!email.value.trim()) {
        message.innerText = "Fusha 'Email' është e detyrueshme.";
        message.style.color = "red";
        return;
    }
    if (!password.value) {
        message.innerText = "Fusha 'Password' është e detyrueshme.";
        message.style.color = "red";
        return;
    }
    if (!description.value.trim()) {
        message.innerText = "Fusha 'Description' është e detyrueshme.";
        message.style.color = "red";
        return;
    }
    if (!certification.files[0]) {
        message.innerText = "Duhet të ngarkoni certifikimin.";
        message.style.color = "red";
        return;
    }
    if (!terms.checked) {
        message.innerText = "Duhet të pranosh kushtet.";
        message.style.color = "red";
        return;
    }

    // -------- VALIDIM PASSWORD
    const pw = password.value;
    const errors = [];

    if (pw.length < 6) errors.push("Minimum 6 karaktere");
    if (!/[A-Z]/.test(pw)) errors.push("Të paktën një shkronjë të madhe");
    if (!/[!@#$%^&*(),.?\":{}|<>]/.test(pw)) errors.push("Të paktën një simbol special");

    if (errors.length > 0) {
        message.innerHTML =
            "Password duhet të përmbajë:<ul><li>" +
            errors.join("</li><li>") +
            "</li></ul>";
        message.style.color = "red";
        return;
    }

    // -------- FORM DATA
    const formData = new FormData();
    formData.append("name", name.value.trim());
    formData.append("surname", surname.value.trim());
    formData.append("email", email.value.trim());
    formData.append("password", password.value);
    formData.append("description", description.value.trim());
    formData.append("certification", certification.files[0]);

    // -------- AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../Signup/ajax/Artist-signup-ajax.php", true);

    xhr.onload = function () {
        let res;

        try {
            res = JSON.parse(this.responseText);
        } catch (e) {
            console.error("JSON parse error:", this.responseText);
            message.innerText = "Gabim serveri.";
            message.style.color = "red";
            return;
        }

        if (res.status === "error") {
            message.innerHTML = res.message;
            message.style.color = "red";
            return;
        }

        if (res.status === "verify") {
            message.innerHTML = res.message;
            message.style.color = "green";

            setTimeout(() => {
                window.location.href =
                    "../Signup/verify.php?email=" + encodeURIComponent(res.email);
            }, 1500);
            return;
        }

        if (res.status === "success") {
            message.innerHTML = res.message;
            message.style.color = "green";

            setTimeout(() => {
                window.location.href = "../Login/login.php";
            }, 1500);
            return;
        }

        message.innerText = "Përgjigje e panjohur nga serveri.";
        message.style.color = "red";
    };

    xhr.onerror = function () {
        message.innerText = "Gabim gjatë dërgimit. Provoni përsëri.";
        message.style.color = "red";
    };

    xhr.send(formData);
});


function openTerms() {
    const modal = document.getElementById("termsModal");
    if (modal) {
        modal.style.display = "block";
    }
}

function closeTerms() {
    const modal = document.getElementById("termsModal");
    if (modal) {
        modal.style.display = "none";
    }
}

