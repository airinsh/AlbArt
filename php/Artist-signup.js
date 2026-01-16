document.getElementById("artistSignupForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const name = document.getElementById("name");
    const surname = document.getElementById("surname"); // **fusha e re**
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const description = document.getElementById("description");
    const certification = document.getElementById("certification");
    const terms = document.getElementById("terms");
    const message = document.getElementById("message");

    message.innerHTML = "";

    // Kontroll fushash required
    if (!name.value.trim()) { message.innerText = "Fusha 'Emri' është e detyrueshme."; message.style.color="red"; name.focus(); return; }
    if (!surname.value.trim()) { message.innerText = "Fusha 'Mbiemri' është e detyrueshme."; message.style.color="red"; surname.focus(); return; }
    if (!email.value.trim()) { message.innerText = "Fusha 'Email' është e detyrueshme."; message.style.color="red"; email.focus(); return; }
    if (!password.value) { message.innerText = "Fusha 'Password' është e detyrueshme."; message.style.color="red"; password.focus(); return; }
    if (!description.value.trim()) { message.innerText = "Fusha 'Description' është e detyrueshme."; message.style.color="red"; description.focus(); return; }
    if (!certification.files[0]) { message.innerText = "Duhet të ngarkoni certifikimin."; message.style.color="red"; certification.focus(); return; }
    if (!terms.checked) { message.innerText = "Duhet të pranosh kushtet."; message.style.color="red"; terms.focus(); return; }

    // Validim i password
    const errors = [];
    const pw = password.value;
    if (pw.length < 6) errors.push("Të ketë minimum 6 karaktere");
    if (!/[A-Z]/.test(pw)) errors.push("Të ketë të paktën një shkronjë të madhe");
    if (!/[!@#$%^&*(),.?\":{}|<>]/.test(pw)) errors.push("Të ketë të paktën një simbol special (!@#$%^&* etj.)");

    if (errors.length > 0) {
        message.innerHTML = "Password duhet të përmbajë: <ul><li>" + errors.join("</li><li>") + "</li></ul>";
        message.style.color = "red";
        password.focus();
        return;
    }

    // FormData për AJAX
    const formData = new FormData();
    formData.append("name", name.value.trim());
    formData.append("surname", surname.value.trim()); // **shtuar**
    formData.append("email", email.value.trim());
    formData.append("password", password.value);
    formData.append("description", description.value.trim());
    formData.append("certification", certification.files[0]);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/Artist-signup-ajax.php", true);

    xhr.onload = function() {
        try {
            const res = JSON.parse(this.responseText);
            if(res.status === "error") {
                message.innerHTML = res.message;
                message.style.color = "red";
            } else {
                message.innerHTML = res.message;
                message.style.color = "green";
                setTimeout(() => { window.location.href = "login.php"; }, 1500);
            }
        } catch(err) {
            console.error("Gabim në JSON:", err, this.responseText);
            message.innerText = "Diçka shkoi gabim. Provoni përsëri.";
            message.style.color = "red";
        }
    };

    xhr.onerror = function() {
        message.innerText = "Gabim gjatë dërgimit. Provoni përsëri.";
        message.style.color = "red";
    };

    xhr.send(formData);
});
